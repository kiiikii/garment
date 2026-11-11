# Sprint Progress Tracking

## Project Overview: Garment ERP Core (Laravel / PHP)

- **System**: Garment ERP Core
- **Backend Framework**: Laravel 13.x (PHP 8.5.x)
- **Database**: PostgreSQL (canonical database: `garment_erp`; Neon development database: `teemant_konveksi`)
- **Frontend**: React + Vite + TypeScript (Single Page Application in `apps/web/`)
- **Architecture**: Headless Modular Monolith, REST API (`/api/v1`)
- **Current Position**: **Milestone 2 COMPLETED** → **Milestone 3 IN PROGRESS** (Sprint 2 – 3: Database Schema & Foundational Order Persistence)

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
- **Status**: `COMPLETED`
- **Progress**:
  - **Task 1.1 (Laravel Application Scaffolding)**: `COMPLETED` — Scaffolded Laravel 13.40.0 on PHP 8.5.12 in `apps/api/`.
  - **Task 1.2 (PostgreSQL Connection Configuration)**: `COMPLETED` — Configured the `pgsql` driver for the Neon development database `teemant_konveksi` with SSL; the canonical database name remains `garment_erp`. Verified connectivity via `migrate:status`.
  - **Task 1.3 (Liveness Endpoint GET /health)**: `COMPLETED` — Registered `GET /health` in `routes/web.php` returning `200 OK` with JSON `{"status":"ok"}`; verified via `route:list`.
  - **Task 1.4 (API Error Handling Baseline)**: `COMPLETED` — Configured JSON exception handling in `bootstrap/app.php` for `api/*` routes; verified 404 returns `{"message":"Resource not found"}` with `application/json`.
  - **Task 1.5 (Automated Test & Verification)**: `COMPLETED` — Created `tests/Feature/HealthTest.php` with 2 automated feature tests covering `/health` (200 JSON) and `/api/v1/*` 404 handler (404 JSON); 4/4 tests passing cleanly.
- **Scope**:
  - Initialize Laravel 13.x in `apps/api/` using PHP 8.5.
  - Configure PostgreSQL database connectivity profile.
  - Implement liveness endpoint `GET /health` returning HTTP 200 JSON payload.
  - Establish base API response envelope and error handling baseline.
  - Implement automated test for `GET /health`.

---

### Milestone 2: Authentication, Authorization & User Roles Foundation (Sprint 1)
- **Status**: `COMPLETED`
- **Progress**:
  - **Task 2.1 (User Model & Role Taxonomy)**: `COMPLETED` — Created `App\Enums\Role` (`OWNER`, `ADMIN`, `PPIC`, `PRODUCTION_STAFF`), added `role` column to `users` table migration, configured Enum casting and fillable attributes on `User` model, and executed migrations.
  - **Task 2.2 (API Token Authentication via Laravel Sanctum)**: `COMPLETED` — Installed Sanctum v4.3.3, implemented login, logout, and profile endpoints with Bearer token authentication; 10/10 tests passing.
  - **Task 2.3 (Role-Based Authorization Middleware)**: `COMPLETED` — Implemented `App\Http\Middleware\EnsureUserHasRole` using `App\Enums\Role`, registered `role` middleware alias in `bootstrap/app.php`, returning HTTP 403 Forbidden.
  - **Task 2.4 (Automated Tests for 401 & 403 Access Barriers)**: `COMPLETED` — Authored `tests/Feature/AuthorizationTest.php` testing all 4 roles against confirmed permission boundaries; 16/16 tests passing cleanly on SQLite in-memory.
- **Scope**:
  - User model and migrations for confirmed roles (`OWNER`, `ADMIN`, `PPIC`, `PRODUCTION_STAFF`).
  - API token authentication mechanism.
  - RBAC policy and middleware enforcement returning `401 Unauthorized` and `403 Forbidden`.
  - Automated tests verifying role access barriers.

---

### Milestone 3: Database Schema & Foundational Order Persistence (Sprint 2 – 3)
- **Status**: `IN PROGRESS`
- **Completed Tasks**:
  - **Tasks 3.2.1–3.2.7**: `COMPLETED` — Implemented the foundational customer, size, order, order-size, and order-image migrations, order status enum, and size seeder. Confirmed size baseline: `S`, `M`, `L`, and `XL` are `STANDARD`; additional sizes such as `XXL` are `CUSTOM`.
  - **Verification**: Milestone 3 migrations ran successfully on Neon; all 4 PostgreSQL `CHECK` constraints passed (`chk_sizes_type_allowed`, `chk_orders_production_type_allowed`, `chk_orders_status_type_allowed`, `chk_order_sizes_quantity_non_negative`); `SizeSeeder` idempotency verified; full test suite: 17 tests passed, 49 assertions.
- **Remaining Work**:
  - Eloquent Models and Relationships for the implemented order-intake schema, consistent with `DATABASE_DESIGN.md`; resolve any affected `UNDECIDED` entity decisions before implementing behavior.
  - Order Intake API: `POST /api/v1/orders`, `GET /api/v1/orders`, and `GET /api/v1/orders/{id}`. Finalize detailed endpoint contracts before implementation, as required by `API_CONTRACTS.md`.
  - Customer endpoints in the planned API inventory where required to support order intake.
  - Backend validation for confirmed order requirements, including CMT/FOB, required customer information, and non-negative per-size quantities.
  - Focused persistence and API tests for the order-intake behavior and relationships.
  - Keep the database dictionary and ERD synchronized with the finalized schema and relationships.
- **Known Migration Tracking Note**:
  - Four default Laravel migrations remain `Pending` although their tables exist in the database. This discrepancy is intentionally left unreconciled.
- **Unresolved Decisions**:
  - **Customer field nullability**: `UNDECIDED` for database nullability of `phone` and `origin_address`; confirmed order-intake rules require these fields.
  - **Production type representation**: `UNDECIDED` (enum, constrained field, or reference entity); the current migration uses a constrained field.
  - **Cancellation workflow**: `UNDECIDED`; the `CANCELLED` order status exists, but its transitions and business consequences are not approved.
  - **Image requirements and storage**: `UNDECIDED` for minimum image count, accepted formats, file size constraints, storage provider, and retention strategy.
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
