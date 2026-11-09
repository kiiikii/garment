# Sprint Progress Tracking

## Project Overview: Garment ERP Core (Laravel / PHP)

- **System**: Garment ERP Core
- **Backend Framework**: Laravel 13.x (PHP 8.5.x)
- **Database**: PostgreSQL (`garment_erp`)
- **Frontend**: React + Vite + TypeScript (Single Page Application in `apps/web/`)
- **Architecture**: Headless Modular Monolith, REST API (`/api/v1`)
- **Current Position**: **Milestone 0 COMPLETED** → **Milestone 1 PENDING** (Sprint 0: Laravel Bootstrap & Health Check)

---

### Milestone 0: Environment & Documentation Baseline (COMPLETED)
- **Status**: `COMPLETED`
- **Completed Deliverables**:
  - Verified Fedora Linux 44 environment, PHP 8.5.12 runtime, Composer 2.11.1, PostgreSQL 18.6 client CLI, Node.js v22.23.4, and npm 10.9.9.
  - Documented ADR `0003-BACKEND-LARAVEL-MIGRATION.md` formalizing the transition to Laravel 13.x (PHP 8.5.x) and updated the ADR registry index.
  - Harmonized outdated Go references across `GEMINI.md`, `skills/mentor/SKILL.md`, and `docs/architecture/decisions/0001-FRONTEND-STACK.md`.
  - Removed obsolete Go sprint and challenge records to maintain focus on the active Laravel implementation roadmap.
  - Established `.env.example` and `.gitignore` baseline for Laravel ERP Core.
  - Maintained `apps/api/` as completely empty in accordance with strict project constraints.
- **Verification**:
  - Environment checks executed cleanly.
  - Git diff verifies documentation consistency with zero active Go references.

---

### Milestone 1: Laravel ERP Core Bootstrap & Health Foundation (Sprint 0)
- **Status**: `IN PROGRESS`
- **Progress**:
  - **Task 1.1 (Laravel Application Scaffolding)**: `COMPLETED` — Scaffolded Laravel 13.40.0 on PHP 8.5.12 in `apps/api/`.
  - **Task 1.2 (PostgreSQL Connection Configuration)**: `COMPLETED` — Configured `pgsql` driver targeting PostgreSQL (`teemant_konveksi` on Neon with SSL); verified connectivity via `migrate:status`.
  - **Task 1.3 (Liveness Endpoint GET /health)**: `COMPLETED` — Registered `GET /health` in `routes/web.php` returning `200 OK` with JSON `{"status":"ok"}`; verified via `route:list`.
  - **Task 1.4 (API Error Handling Baseline)**: `COMPLETED` — Configured JSON exception handling in `bootstrap/app.php` for `api/*` routes; verified 404 returns `{"message":"Resource not found"}` with `application/json`.
  - **Task 1.5 (Automated Test & Verification)**: `PENDING`
- **Scope**:
  - Initialize Laravel 13.x in `apps/api/` using PHP 8.5.
  - Configure PostgreSQL database connectivity profile.
  - Implement liveness endpoint `GET /health` returning HTTP 200 JSON payload.
  - Establish base API response envelope and error handling baseline.
  - Implement automated test for `GET /health`.

---

### Milestone 2: Authentication, Authorization & User Roles Foundation (Sprint 1)
- **Status**: `PENDING`
- **Scope**:
  - User model and migrations for confirmed roles (`OWNER`, `ADMIN`, `PPIC`, `PRODUCTION_STAFF`).
  - API token authentication mechanism.
  - RBAC policy and middleware enforcement returning `401 Unauthorized` and `403 Forbidden`.
  - Automated tests verifying role access barriers.

---

### Milestone 3: Database Schema & Foundational Order Persistence (Sprint 2 – 3)
- **Status**: `PENDING`
- **Scope**:
  - Native Laravel migrations for `customers`, `sizes`, `orders`, `order_sizes`, `order_images`.
  - Order Intake endpoints: `POST /api/v1/orders`, `GET /api/v1/orders`, `GET /api/v1/orders/{id}`.
  - Domain validation: CMT/FOB check, individual size quantities (`quantity >= 0`).

---

### Milestone 4: Pre-Production & Commercial Gating (Sampling & LoA) (Sprint 4 – 5)
- **Status**: `PENDING`
- **Scope**:
  - Sampling tracking (4-day internal reference vs 7-day customer target).
  - Sample / Look Approval state machine (`PENDING`, `REVISION`, `APPROVED`, `REJECTED`).
  - LoA commercial gate administration and payment recording.
  - Payment correction workflow with Owner approval.

---

### Milestone 5: Production Readiness, Layout & PPIC Preparation (Sprint 6)
- **Status**: `PENDING`
- **Scope**:
  - Production Readiness checklist evaluation.
  - Waiting / On Hold subsystem with mandatory recorded reasons.
  - Functional Production Layout work area mapping (Cutting, Sewing, Overlock, Embroidery, Finishing).

---

### Milestone 6: Mass Production, Progress Monitoring & QC Loop (Sprint 7 – 8)
- **Status**: `PENDING`
- **Scope**:
  - Mass production stages: Cutting → Sewing → QC → Finishing.
  - PPIC percentage progress updates (Cutting, Sewing, Finishing).
  - Corrected QC inspection cycle: `PASS → FINISHING`, `FAIL → REWORK → QC AGAIN`.
  - Production Issue Reports (isolated staff submission, PPIC management with resolution notes).
  - Delay Reports and pre-cutting Order Change Requests.

---

### Milestone 7: Fulfillment, Completion & Audit Logging (Sprint 9)
- **Status**: `PENDING`
- **Scope**:
  - Packing and Delivery / Customer Pickup tracking.
  - Authorized order completion gate (confirmation authority: `UNDECIDED`).
  - Order status audit trail logging.

---

### Milestone 8: Security Hardening, Observability & Verification (Sprint 10)
- **Status**: `PENDING`
- **Scope**:
  - Rate limiting, CORS configuration, sensitive data protection.
  - Code style with Laravel Pint and static analysis with PHPStan.
  - Full automated regression test suite.

---

### Milestone 9: Frontend Web Client Integration (Sprint 11 – 12)
- **Status**: `PENDING`
- **Scope**:
  - React + Vite + TypeScript application in `apps/web/`.
  - Typed API client consuming Laravel ERP Core `/api/v1`.
  - Role-based views (Owner, Admin, PPIC, Staff).

---

### Milestone 10: Production Deployment & Operational Verification (Sprint 13)
- **Status**: `PENDING`
- **Scope**:
  - Production deployment configuration, environment variables, incident simulations.
