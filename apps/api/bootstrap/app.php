<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    api:__DIR__.'/../routes/api.php',
    apiPrefix:'api/v1',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
      'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->shouldRenderJsonWhen(
      fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
    );
    $exceptions->render(function (NotFoundHttpException $e, Request $request) {
      if (!$request->is('api/*')) {
        return null;
      }

      return response()->json([
        'message'=>"Resource not found",
      ], 404);
    }); 
  })->create();
