# 0003: Backend Architecture Transition from Go to Laravel

Status: Accepted

- **PREVIOUS:** Go (standard library `net/http`, `pgx/stdlib`, `golang-migrate`)
- **NEW:** Laravel 13.x (PHP 8.5.x)
- **CHANGE:** Backend ERP Core application framework and target persistence architecture
- **IMPACT:** Future backend implementation in `apps/api/`, schema migration mechanism upon initialization, dependency management via Composer, and backend testing strategy.
- **AFFECTED:** `apps/api/` target codebase, active database migration strategy (transitions to Laravel database migrations in `apps/api/database/migrations/` after backend initialization), environment configuration, backend test runner.
- **UNAFFECTED:** Approved database engine (PostgreSQL) and intended database name (`garment_erp`), Headless Modular Monolith architecture, REST API style (`/api/v1`), domain business rules and workflow sequences in `docs/business/`, frontend technology stack (React + Vite + TypeScript in `apps/web/`).
- **DEPRECATED:** Go toolchain for backend development, `apps/api/cmd/api/`, `apps/api/go.mod`, standalone SQL migration execution via `golang-migrate`.
- **UNKNOWN:** Authentication strategy package (Sanctum tokens vs session cookies), detailed persistence strategy (Eloquent ORM vs Query Builder), standard error response schema details.

---

## Context

The initial backend implementation used Go with the standard library `net/http` and `pgx/stdlib`. While performant, the garment manufacturing domain requires complex commercial gating, workflow invariants, audit logging, role-based authorization, reporting, and relational state management across multiple manufacturing lifecycle stages.

The project adopted PHP 8.5.x and stable Laravel 13.x to leverage Laravel's mature modular monolith conventions, built-in migration tooling, robust Form Request validation, authorization policies, notification infrastructure, and testing capabilities while maintaining a strict Headless ERP architecture.

PostgreSQL is the approved relational database engine, and `garment_erp` is the intended database name. This architectural designation establishes persistence governance and entity modeling targets; it does not assume or prove that a PostgreSQL database server daemon is currently running, configured, or accessible in the development environment.

## Decision

Adopt Laravel 13.x running on PHP 8.5.x as the sole authoritative target backend framework for Garment ERP Core.

The backend will be implemented as a Headless Modular Monolith in `apps/api/` once initialized, designed to expose REST API endpoints to API consumers over HTTP (`/api/v1`). At the current project stage, `apps/api/` remains intentionally empty.

PostgreSQL is the approved persistent relational source of truth. Laravel database migrations in `apps/api/database/migrations/` will become the active schema migration mechanism once the backend application is initialized.

The previous Go backend implementation and standalone `golang-migrate` SQL files are discontinued and archived as historical reference.

## Consequences

- The backend runtime requires PHP 8.5.x, Composer, the core extensions required by Laravel 13 (including `mbstring`, `xml`/`dom`, `curl`, `openssl`, `tokenizer`, `fileinfo`), plus `pdo_pgsql` for PostgreSQL database connectivity. Additional extensions (such as `zip`, `bcmath`, or `intl`) are dependency-driven and will be introduced as required by application packages or domain calculations.
- `apps/api/` will be structured as a standard Laravel application once initialized; until then, it remains intentionally empty.
- Laravel database migrations in `apps/api/database/migrations/` will become active only after backend initialization. Legacy SQL files in `apps/migration/` are historical design references and must not be executed directly.
- The approved database engine is PostgreSQL and `garment_erp` is the intended database name, but server availability and database connectivity must be explicitly established in the environment prior to migration execution.
- The React frontend will continue to communicate exclusively through the REST API contracts once the backend is initialized.
- Automated testing will transition to Laravel's testing facilities (`php artisan test`) once the application is scaffolded.
