# Sprint Progress Tracking

## Project Overview
- **System**: Garment ERP Core
- **Architecture**: Modular Monolith, Headless Go ERP Core, REST API
- **Current Sprint**: Sprint 1 — HTTP and REST Fundamentals, First Business Endpoint
- **Current Position**: Challenge 1.3 COMPLETED → Sprint 1 COMPLETED → Sprint 2 PENDING

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

## Sprint 2: PostgreSQL Setup, Migrations, Customer / Order Tables, Repository Foundation (PENDING)
- **Status**: `PENDING`
