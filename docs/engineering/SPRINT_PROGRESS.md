# Sprint Progress Tracking

## Project Overview
- **System**: Garment ERP Core
- **Architecture**: Modular Monolith, Headless Go ERP Core, REST API
- **Current Sprint**: Sprint 1 — HTTP and REST Fundamentals, First Business Endpoint
- **Current Position**: Challenge 1.1 COMPLETED → Challenge 1.2 PENDING

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
  - `TestSystemEndpoint` (`GET /health`) was replaced during `main_test.go` updates; needs restoration to maintain health check regression coverage.
  - Supported sizes are currently checked against S, M, L, XL; PRD notes custom sizes remain `UNDECIDED`.

---

### Challenge 1.2: Path Parameters, In-Memory Storage & Order Retrieval (NEXT)
- **Status**: `PENDING`
- **Scope**:
  - Introduce thread-safe in-memory order store (map + sync.RWMutex) in `main.go`.
  - Assign generated sequential or UUID-like IDs to created orders on `POST /api/v1/orders`.
  - Implement `GET /api/v1/orders/{id}` using Go 1.22+ `r.PathValue("id")`.
  - Handle `404 Not Found` with code `"NOT_FOUND"` for unknown order IDs.
  - Implement `GET /api/v1/orders` returning all orders in `data`.
  - Add tests covering `GET /api/v1/orders`, `GET /api/v1/orders/{id}` success (200), and not found (404), while restoring `TestSystemEndpoint`.

