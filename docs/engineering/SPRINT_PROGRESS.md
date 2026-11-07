# Sprint Progress Tracking

## Project Overview
- **System**: Garment ERP Core
- **Architecture**: Modular Monolith, Headless Go ERP Core, REST API
- **Current Sprint**: Sprint 2 — PostgreSQL Setup, Migrations, Customer / Order Tables, Repository Foundation
- **Current Position**: Challenge 2.2 COMPLETED → Challenge 2.3 PENDING

---

## Sprint 0: Backend Bootstrap (COMPLETED)
- **Challenge 0.1**: Go module initialization, `apps/api/cmd/api/` entry point, standard library `net/http` server, `GET /health` liveness endpoint, graceful shutdown with `signal.NotifyContext` and 5s timeout, unit tests via `net/http/httptest`.
- **Milestone Commit**: `a89cba0` / `3f27055`

---

## Sprint 1: HTTP and REST Fundamentals, First Business Endpoint

### Challenge 1.1: Standard API Envelope & Order Intake Endpoint (COMPLETED)
- **Status**: `COMPLETED`
- **Implementation Summary**:
  - Defined standard response envelope `APIResponse` (`success`, `data`, `error`) and `APIError` (`code`, `message`, `details`).
  - Added helper functions `writeJSON` and `writeError` in `apps/api/cmd/api/main.go`.
  - Defined `CreateOrderRequest` and business validation method `Validate()`:
    - Rejects empty or whitespace-only customer name, phone, address.
    - Strictly restricts `production_type` to `"CMT"` or `"FOB"`.
    - Validates size quantities (`quantity >= 0`).
  - Registered route `POST /api/v1/orders` on `buildRouter()`.
  - Implemented proper error classification:
    - Malformed JSON / decoding failure → `400 Bad Request` (`MALFORMED_JSON`).
    - Domain validation failure → `422 Unprocessable Content` (`VALIDATION_ERROR`).
    - Valid request → `201 Created` with echoed payload.
- **Verification & Tests**:
  - `gofmt -l .` — 0 unformatted files.
  - `go vet ./...` — Passed cleanly.
  - `go test -count=1 -v ./...` — All 3 order tests passed:
    - `TestCreateOrder_Success` (201 Created, success=true)
    - `TestCreateOrder_MalformedJSON` (400 Bad Request, code=MALFORMED_JSON)
    - `TestCreateOrder_ValidationError` (422 Unprocessable Entity, code=VALIDATION_ERROR)
- **Known Observations**:
  - Supported sizes are currently checked against S, M, L, XL; PRD notes custom sizes remain `UNDECIDED`.

---

### Challenge 1.2: Path Parameters, In-Memory Storage & Order Retrieval (COMPLETED)
- **Status**: `COMPLETED`
- **Implementation Summary**:
  - Thread-safe in-memory order storage using `InMemoryOrderStore` with `sync.RWMutex`.
  - Sequential order ID generation (`ord_%d` format).
  - `POST /api/v1/orders` persists orders in the store with generated IDs.
  - `GET /api/v1/orders/{id}` retrieves single orders via Go 1.22+ `r.PathValue("id")`.
  - `GET /api/v1/orders` retrieves the collection of all stored orders.
  - 404 NOT_FOUND handling with code `"NOT_FOUND"` for non-existent IDs.
  - Automated test coverage: 6/6 tests passing in `main_test.go`, including restored `/health` check and matching order field assertions.
- **Verification & Tests**:
  - `gofmt -l .` — 0 unformatted files.
  - `go vet ./...` — Clean, 0 issues.
  - `go test -count=1 -v ./...` — 6/6 tests passing:
    - `TestHealthCheck` (200 OK, Content-Type: application/json, status: "ok")
    - `TestCreateOrder_Success` (201 Created, success: true)
    - `TestCreateOrder_MalformedJSON` (400 Bad Request, code: MALFORMED_JSON)
    - `TestCreateOrder_ValidationError` (422 Unprocessable Entity, code: VALIDATION_ERROR)
    - `TestGetByID` (200 OK matching order data, 404 Not Found code: NOT_FOUND)
    - `TestGetAllOrders` (200 OK array length and matching elements)

---

### Challenge 1.3: Sprint 1 Milestone Review & Git Commit (COMPLETED)
- **Status**: `COMPLETED`
- **Summary**:
  - Verified working tree clean, all 6 tests passing, `gofmt` and `go vet` clean.
  - Sprint 1 source committed in `a937742`.
  - Sprint progress document committed in `b22a734`.

---

## Sprint 2: PostgreSQL Setup, Migrations, Customer / Order Tables, Repository Foundation

### Challenge 2.1: PostgreSQL Connection & Configuration (COMPLETED)
- **Status**: `COMPLETED`
- **Implementation Summary**:
  - Added PostgreSQL driver dependency `github.com/jackc/pgx/v5` (`pgx/stdlib`) registering driver name `"pgx"`.
  - Added `DATABASE_URL` environment variable support and placeholder in `.env.example`.
  - Initialized database handle using `sql.Open("pgx", databaseURL)` in `main()`.
  - Validated connection at startup via `db.PingContext(pingCtx)` with a 5-second timeout context.
  - Handled startup ping error via log and early return, preserving deferred resource cleanup.
  - Registered `defer db.Close()` before HTTP server definition to guarantee HTTP shutdown drains active connections before the database connection closes.
  - Updated `buildRouter` signature to accept `*sql.DB` for dependency injection (`func buildRouter(db *sql.DB) *http.ServeMux`).
  - Updated all test calls in `main_test.go` to pass `nil` to `buildRouter(nil)`.
- **Verification & Tests**:
  - `gofmt -l .` — 0 unformatted files.
  - `go vet ./...` — Clean, 0 issues.
  - `go build ./...` — Clean build.
  - `go test -count=1 -v ./...` — All 6 tests passing.
- **Known Technical Debt**:
  - `log.Fatalf` in the `ListenAndServe` background goroutine is retained as minor technical debt.
- **Milestone Commit**: `7097f6d`

---

### Challenge 2.2: Database Migration Strategy & Schema Setup (COMPLETED)
- **Status**: `COMPLETED`
- **Implementation Summary**:
  - Adopted and configured `golang-migrate` CLI.
  - Authored forward and rollback migration pairs (`.up.sql` and `.down.sql`) in `apps/migration/`.
  - Created 6 foundational relational tables:
    - `customers`: identity PK, customer info, timestamps.
    - `sizes`: name with unique constraint, type check (`STANDARD`, `CUSTOM`), active flag, and initial seeds (S, M, L, XL).
    - `orders`: references `customers` (`ON DELETE RESTRICT`), production type check (`CMT`, `FOB`), status check (`ACTIVE`, `CANCELLED`, `COMPLETED`), indexed `customer_id`.
    - `order_sizes`: composite relational junction with positive quantity check, composite unique constraint `(order_id, size_id)`, and cascading deletion on order.
    - `order_images`: order reference image metadata with cascading deletion.
    - `order_status_history`: state transitions tracking `from_status` and `to_status` checks, indexed `order_id`.
  - Executed and validated migrations against Neon PostgreSQL using `DATABASE_URL`.
  - Diagnosed and resolved migration dirty state handling during schema iteration.
- **Verification**:
  - All 6 tables, indexes, constraints, and initial seed records verified in PostgreSQL.

---

### Challenge 2.3: Repository Pattern Foundation & Database Order Persistence
- **Status**: `PENDING`
