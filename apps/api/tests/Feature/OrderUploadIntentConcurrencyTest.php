<?php

namespace Tests\Feature;

use App\Enums\OrderUploadIntentStatus;
use App\Models\Order;
use App\Models\OrderUploadIntent;
use App\Services\OrderUploadIntentTransitionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

class OrderUploadIntentConcurrencyTest extends TestCase
{
  private function waitForChildProcess(
    int $pid,
    bool &$reaped,
    int $timeoutSeconds = 5,
  ): int {
    $reaped = false;

    if ($pid <= 0 || $timeoutSeconds <= 0) {
      throw new \InvalidArgumentException("Invalid child PID or timeout");
    }

    if (!function_exists("posix_kill")) {
      $this->fail("POSIX extension is required");
    }

    $status = 0;
    $deadline = microtime(true) + $timeoutSeconds;

    while (microtime(true) < $deadline) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;

        if (pcntl_wifsignaled($status)) {
          $signal = pcntl_wtermsig($status);
          $this->fail("Child {$pid} terminated by signal {$signal}");
        }

        $this->assertTrue(pcntl_wifexited($status));

        return pcntl_wexitstatus($status);
      }

      if ($result === -1) {
        $this->fail("Failed waiting for child process {$pid}");
      }

      usleep(10_000);
    }

    if (!posix_kill($pid, SIGTERM)) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        $this->fail("Child {$pid} exited as timeout was reached");
      }

      if ($result === -1) {
        $this->fail("Failed inspecting child {$pid} after SIGTERM");
      }

      if (!posix_kill($pid, SIGKILL)) {
        $this->fail("Failed sending SIGKILL to child {$pid}");
      }

      $reapResult = pcntl_waitpid($pid, $status);

      $this->assertSame($pid, $reapResult);
      $reaped = true;

      $this->fail("Child {$pid} required SIGKILL because SIGTERM failed");
    }

    $graceDeadline = microtime(true) + 0.5;

    while (microtime(true) < $graceDeadline) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;

        if (pcntl_wifsignaled($status)) {
          $signal = pcntl_wtermsig($status);

          $this->fail(
            "Child {$pid} timed out and terminated by signal {$signal}",
          );
        }

        if (pcntl_wifexited($status)) {
          $exitCode = pcntl_wexitstatus($status);

          $this->fail(
            "Child {$pid} timed out and exited during SIGTERM grace period with code {$exitCode}",
          );
        }

        $this->fail("Unexpected child process status");
      }

      if ($result === -1) {
        $this->fail("Failed waiting for child {$pid} during grace period");
      }

      usleep(10_000);
    }

    if (!posix_kill($pid, SIGKILL)) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        $this->fail("Child {$pid} exited as SIGKILL was being sent");
      }

      if ($result === -1) {
        $this->fail("Failed inspecting child {$pid} after SIGKILL");
      }

      $this->fail("Failed sending SIGKILL to child {$pid}");
    }

    $reapResult = pcntl_waitpid($pid, $status);

    $this->assertSame($pid, $reapResult, "Failed to reap child process {$pid}");

    if (pcntl_wifsignaled($status)) {
      $signal = pcntl_wtermsig($status);

      $this->fail(
        "Child {$pid} timed out and was forcefully terminated by signal {$signal}",
      );
    }

    if (pcntl_wifexited($status)) {
      $exitCode = pcntl_wexitstatus($status);

      $this->fail("Child {$pid} timed out and exited with code {$exitCode}");
    }

    $this->fail("Unexpected child process status after SIGKILL");
  }

  private function terminateAndReapChildProcess(
    int $pid,
    bool &$reaped,
    int $graceMilliseconds = 500,
  ): void {
    if ($pid <= 0 || $reaped) {
      return;
    }

    $status = 0;

    $deadline = microtime(true) + $graceMilliseconds / 1000;

    while (microtime(true) < $deadline) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        return;
      }

      if ($result === -1) {
        throw new \RuntimeException("Failed waiting for child {$pid}");
      }
      usleep(10_000);
    }

    if (!posix_kill($pid, SIGTERM)) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        return;
      }

      throw new \RuntimeException("Failed to send SIGTERM to child {$pid}");
    }

    $deadline = microtime(true) + $graceMilliseconds / 1000;

    while (microtime(true) < $deadline) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        return;
      }

      if ($result === -1) {
        throw new \RuntimeException(
          "Failed waiting for child {$pid} during SIGTERM",
        );
      }

      usleep(10_000);
    }

    if (!posix_kill($pid, SIGKILL)) {
      $result = pcntl_waitpid($pid, $status, WNOHANG);

      if ($result === $pid) {
        $reaped = true;
        return;
      }

      throw new \RuntimeException("Failed to send SIGKILL to child {$pid}");
    }

    $reapResult = pcntl_waitpid($pid, $status);

    if ($reapResult !== $pid) {
      throw new \RuntimeException("Failed to reap child {$pid}");
    }

    $reaped = true;
  }

  public function test_user_local_postgress_testing_database(): void
  {
    $this->assertSame("pgsql", DB::connection()->getDriverName());
    $this->assertSame("garment_erp_test", DB::connection()->getDatabaseName());
  }

  public function test_can_open_two_independent_postgresql_connection(): void
  {
    $connectionConfig = config("database.connections.pgsql");

    config([
      "database.connections.pgsql_worker_a" => $connectionConfig,
      "database.connections.pgsql_worker_b" => $connectionConfig,
    ]);

    try {
      $workerA = DB::connection("pgsql_worker_a");
      $workerB = DB::connection("pgsql_worker_b");

      $pidA = (int) $workerA->selectOne("SELECT pg_backend_pid() AS pid")->pid;
      $pidB = (int) $workerB->selectOne("SELECT pg_backend_pid() AS pid")->pid;

      $this->assertNotSame($pidA, $pidB);
      $this->assertSame("garment_erp_test", $workerA->getDatabaseName());
      $this->assertSame("garment_erp_test", $workerB->getDatabaseName());
    } finally {
      DB::purge("pgsql_worker_a");
      DB::purge("pgsql_worker_b");
    }
  }

  public function test_can_spawn_and_wait_for_worker_process(): void
  {
    $this->assertTrue(function_exists("pcntl_fork"));

    DB::purge("pgsql");

    $pid = pcntl_fork();

    $this->assertNotSame(-1, $pid);

    if ($pid === 0) {
      exit(0);
    }

    $waitedPid = pcntl_waitpid($pid, $status);

    $this->assertSame($pid, $waitedPid);
    $this->assertTrue(pcntl_wifexited($status));
    $this->assertSame(0, pcntl_wexitstatus($status));
  }

  public function test_parent_and_child_can_communicate(): void
  {
    $socket = stream_socket_pair(
      STREAM_PF_UNIX,
      STREAM_SOCK_STREAM,
      STREAM_IPPROTO_IP,
    );

    $this->assertNotFalse($socket, "failed to create socket pair");

    [$parentSocket, $childSocket] = $socket;

    DB::purge("pgsql");

    $pid = pcntl_fork();

    if ($pid === -1) {
      fclose($parentSocket);
      fclose($childSocket);

      $this->fail("Failed to fork process");
    }

    if ($pid === 0) {
      fclose($parentSocket);

      fwrite($childSocket, "worker-ready");
      fclose($childSocket);

      exit(0);
    }

    fclose($childSocket);

    $message = stream_get_contents($parentSocket);
    fclose($parentSocket);

    $waitedPid = pcntl_waitpid($pid, $status);

    $this->assertSame($pid, $waitedPid);

    $this->assertSame("worker-ready", $message);
    $this->assertTrue(pcntl_wifexited($status));
    $this->assertSame(0, pcntl_wexitstatus($status));
  }

  public function test_transition_waits_for_concurrent_row_lock_and_rechecks_status(): void
  {
    $uploadIntent = OrderUploadIntent::create([
      "request_id" => Str::uuid()->toString(),
      "object_key" => "orders/test-" . Str::uuid() . "/front.png",
    ]);

    $parentSocketA = null;
    $workerSocketA = null;
    $workerPidA = null;
    $childReapedA = false;

    $parentSocketB = null;
    $workerSocketB = null;
    $workerPidB = null;
    $childReapedB = false;

    try {
      $service = app(OrderUploadIntentTransitionService::class);
      $this->assertTrue(
        $service->transition($uploadIntent, OrderUploadIntentStatus::UPLOADING),
      );
      $this->assertTrue(
        $service->transition($uploadIntent, OrderUploadIntentStatus::UPLOADED),
      );

      $uploadIntentId = $uploadIntent->id;

      $freshUploadIntent = OrderUploadIntent::findOrFail($uploadIntentId);

      $this->assertSame(
        OrderUploadIntentStatus::UPLOADED,
        $freshUploadIntent->status,
      );

      $socketA = stream_socket_pair(
        STREAM_PF_UNIX,
        STREAM_SOCK_STREAM,
        STREAM_IPPROTO_IP,
      );

      $this->assertNotFalse($socketA);

      [$parentSocketA, $workerSocketA] = $socketA;

      DB::purge("pgsql");

      $workerPidA = pcntl_fork();

      if ($workerPidA === -1) {
        $this->fail("Failed to fork worker A");
      }

      if ($workerPidA === 0) {
        fclose($parentSocketA);

        $conn = null;

        try {
          DB::purge("pgsql");
          $conn = DB::connection("pgsql");
          $conn->beginTransaction();

          $workerAPgPid = (int) $conn->selectOne(
            "SELECT pg_backend_pid() AS pid",
          )->pid;

          $lockedIntent = OrderUploadIntent::on("pgsql")
            ->whereKey($uploadIntentId)
            ->lockForUpdate()
            ->firstOrFail();

          if ($lockedIntent->status !== OrderUploadIntentStatus::UPLOADED) {
            throw new \RuntimeException(
              "Unexpected initial upload intent status",
            );
          }

          if (!$lockedIntent->transitionTo(OrderUploadIntentStatus::ATTACHED)) {
            throw new \RuntimeException("Worker A transition rejected");
          }

          $lockedIntent->save();

          if (fwrite($workerSocketA, "LOCKED:{$workerAPgPid}\n") === false) {
            throw new \RuntimeException("Invalid release signal");
          }

          fflush($workerSocketA);

          $releaseSignal = trim((string) fgets($workerSocketA));

          if ($releaseSignal !== "RELEASE") {
            throw new \RuntimeException("Invalid release signal");
          }

          $conn->commit();

          fwrite($workerSocketA, "COMMITTED\n");
          fflush($workerSocketA);
          fclose($workerSocketA);
          exit(0);
        } catch (\Throwable $e) {
          if ($conn !== null && $conn->transactionLevel() > 0) {
            $conn->rollback();
          }

          @fwrite($workerSocketA, "ERROR: " . $e->getMessage() . "\n");
          @fflush($workerSocketA);
          fclose($workerSocketA);

          exit(1);
        }
      }

      fclose($workerSocketA);
      $workerSocketA = null;

      stream_set_timeout($parentSocketA, 5);
      $lockMessage = trim((string) fgets($parentSocketA));
      $this->assertStringStartsWith("LOCKED:", $lockMessage);
      $workerAPgPid = (int) substr($lockMessage, strlen("LOCKED:"));
      $this->assertGreaterThan(0, $workerAPgPid);

      $socketB = stream_socket_pair(
        STREAM_PF_UNIX,
        STREAM_SOCK_STREAM,
        STREAM_IPPROTO_IP,
      );

      $this->assertNotFalse($socketB);

      [$parentSocketB, $workerSocketB] = $socketB;

      DB::purge("pgsql");

      $workerPidB = pcntl_fork();

      if ($workerPidB === -1) {
        $this->fail("Failed to fork worker B");
      }

      if ($workerPidB === 0) {
        fclose($parentSocketA);
        fclose($parentSocketB);

        $conn = null;

        try {
          DB::purge("pgsql");

          $staleIntent = OrderUploadIntent::findOrFail($uploadIntentId);
          if ($staleIntent->status !== OrderUploadIntentStatus::UPLOADED) {
            throw new \RuntimeException(
              "Worker B did not read the expected stale status",
            );
          }

          $workerBPgPid = (int) DB::connection("pgsql")->selectOne(
            "SELECT pg_backend_pid() AS pid",
          )->pid;

          fwrite($workerSocketB, "ATTEMPTING:{$workerBPgPid}\n");
          fflush($workerSocketB);

          $workerBService = app(OrderUploadIntentTransitionService::class);
          $result = $workerBService->transition(
            $staleIntent,
            OrderUploadIntentStatus::CLEANUP_PENDING,
          );

          fwrite($workerSocketB, ($result ? "TRUE" : "FALSE") . "\n");
          fflush($workerSocketB);
          fclose($workerSocketB);

          exit(0);
        } catch (\Throwable $e) {
          @fwrite($workerSocketB, "ERROR: " . $e->getMessage() . "\n");
          @fflush($workerSocketB);
          fclose($workerSocketB);

          exit(1);
        }
      }

      fclose($workerSocketB);
      $workerSocketB = null;

      stream_set_timeout($parentSocketB, 5);

      $attemptMessage = trim((string) fgets($parentSocketB));
      $this->assertStringStartsWith("ATTEMPTING:", $attemptMessage);
      $workerBPgPid = (int) substr($attemptMessage, strlen("ATTEMPTING:"));
      $this->assertGreaterThan(0, $workerBPgPid);

      $deadline = microtime(true) + 3.0;
      $isBlockedByWorkerA = false;

      while (microtime(true) < $deadline) {
        $blocked = DB::connection("pgsql")->selectOne(
          "SELECT 1 FROM pg_stat_activity
           WHERE pid = CAST(? AS integer)
           AND datname = current_database()
           AND CAST(? AS integer) = ANY(pg_blocking_pids(pid))
           LIMIT 1",
          [$workerBPgPid, $workerAPgPid],
        );

        if ($blocked !== null) {
          $isBlockedByWorkerA = true;
          break;
        }

        usleep(5_000);
      }

      $this->assertTrue(
        $isBlockedByWorkerA,
        "Worker B did not enter lock wait blocked by Worker A (PG PID {$workerAPgPid})",
      );

      $written = fwrite($parentSocketA, "RELEASE\n");
      $this->assertNotFalse($written);
      fflush($parentSocketA);

      $commitMessage = fgets($parentSocketA);
      $this->assertSame("COMMITTED", trim((string) $commitMessage));

      fclose($parentSocketA);
      $parentSocketA = null;

      $workerBResult = fgets($parentSocketB);
      $this->assertSame("FALSE", trim((string) $workerBResult));

      fclose($parentSocketB);
      $parentSocketB = null;

      $exitCodeA = $this->waitForChildProcess($workerPidA, $childReapedA);
      $this->assertSame(0, $exitCodeA);

      $exitCodeB = $this->waitForChildProcess($workerPidB, $childReapedB);
      $this->assertSame(0, $exitCodeB);

      $finalIntent = OrderUploadIntent::findOrFail($uploadIntentId);
      $this->assertSame(
        OrderUploadIntentStatus::ATTACHED,
        $finalIntent->status,
      );
    } finally {
      if (is_resource($parentSocketA)) {
        @fwrite($parentSocketA, "RELEASE\n");
        @fflush($parentSocketA);
        fclose($parentSocketA);
      }

      if (is_resource($parentSocketA)) {
        fclose($parentSocketA);
      }

      if (is_resource($workerSocketA)) {
        fclose($workerSocketA);
      }

      if (is_resource($parentSocketB)) {
        fclose($parentSocketB);
      }

      if (is_resource($workerSocketB)) {
        fclose($workerSocketB);
      }

      if ($workerPidA !== null && $workerPidA > 0 && !$childReapedA) {
        $this->terminateAndReapChildProcess($workerPidA, $childReapedA);
      }

      if ($workerPidB !== null && $workerPidB > 0 && !$childReapedB) {
        $this->terminateAndReapChildProcess($workerPidB, $childReapedB);
      }

      $uploadIntent->delete();
    }
  }

  public function test_cleanup_reaps_child_after_sigterm(): void
  {
    DB::purge("pgsql");

    $pid = pcntl_fork();
    if ($pid === -1) {
      $this->fail("Failed to fork child");
    }

    if ($pid === 0) {
      while (true) {
        usleep(100_000);
      }
    }

    $reaped = false;

    $this->terminateAndReapChildProcess($pid, $reaped, 200);
    $this->assertTrue($reaped);
  }

  public function test_cleanup_uses_sigkill_when_sigterm_is_ignored(): void
  {
    DB::purge("pgsql");

    $pid = pcntl_fork();
    if ($pid === -1) {
      $this->fail("Failed to fork child");
    }

    if ($pid === 0) {
      while (true) {
        usleep(100_000);
      }
    }

    $reaped = false;

    $this->terminateAndReapChildProcess($pid, $reaped, 200);
    $this->assertTrue($reaped);
  }
}
