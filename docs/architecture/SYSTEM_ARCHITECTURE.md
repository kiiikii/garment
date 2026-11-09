# System Architecture

**Status:** DRAFT FOR REVIEW — ALIGNED WITH PRD v1.0
**Scope:** Phase 1 — CMT and FOB Production

This document defines the high-level system architecture of Garment ERP Core.

The architecture must remain consistent with:

- PRD.md
- BUSINESS_RULES.md
- BUSINESS_WORKFLOW.md
- USER_ROLES.md
- APPLICATION_ARCHITECTURE.md
- API_CONTRACTS.md
- DATABASE_DESIGN.md

---

## 1. Headless ERP Architecture

Garment ERP Core uses a Headless Modular Monolith architecture.

The Laravel / PHP application is the ERP Core.

It is the authoritative owner of:

- Business logic.
- Business rules.
- Workflow execution and transitions.
- Request validation.
- Authentication.
- Authorization and RBAC.
- Database persistence.
- Transaction management.
- Audit history.
- Notification processing.
- Timeline calculations.
- Dashboard data aggregation.
- REST API implementation.

The REST API defines the communication boundary between ERP Core and its clients.

The frontend consumes ERP Core through the REST API.

The ERP Core must not depend on a specific frontend implementation.

### High-Level Architecture

    +---------------------------------------+
    |              API CLIENTS              |
    |                                       |
    |     React SPA       Future Clients    |
    |     (Vite)          (Optional)        |
    +-------------------+-------------------+
                        |
                        v
                 REST API
                  /api/v1
                        |
                        v
    +---------------------------------------+
    |           LARAVEL ERP CORE            |
    |                                       |
    |  Business Modules                     |
    |  Business Rules                       |
    |  Workflow Enforcement                 |
    |  Authentication & Authorization       |
    |  Validation                           |
    |  Persistence                          |
    |  Audit & Notifications                |
    +-------------------+-------------------+
                        |
                        v
    +---------------------------------------+
    |              POSTGRESQL               |
    |                                       |
    |        Persistent Business Data       |
    +---------------------------------------+

### Architectural Rules

1. ERP Core is the single authority for business logic.
2. The frontend is an API client, not a business authority.
3. The backend must not depend on a specific client.
4. All protected operations require backend authorization.
5. Workflow transitions must be validated by the backend.
6. Business validation must not be bypassed through direct API requests.
7. PostgreSQL is the authoritative source of persistent business data.
8. The frontend must never access PostgreSQL directly.
9. Future clients must consume the same approved API contracts.
10. Business behavior must remain consistent regardless of which client initiates an operation.

---

## 2. Architecture Style

**Confirmed Architecture: Headless Modular Monolith**

The backend is implemented as one Laravel application organized into logically separated business modules.

### Modular Monolith Principles

- One deployable backend application.
- Clear module responsibilities.
- Explicit boundaries between business capabilities.
- Shared application infrastructure where appropriate.
- Controlled dependencies between modules.
- Centralized business authorization.
- Consistent database transaction management.
- No unnecessary service separation.

Modules should follow business capabilities and domain boundaries.

Do not create a separate module for every database table, API endpoint, or individual CRUD operation.

### Microservices

Microservices are not part of the confirmed Phase 1 architecture.

Do not introduce microservices unless a real requirement justifies the additional architectural complexity.

---

## 3. Confirmed Technology Stack

| Area                      | Technology                | Status    |
| ------------------------- | ------------------------- | --------- |
| Backend                   | Laravel / PHP             | CONFIRMED |
| API                       | REST API                  | CONFIRMED |
| Database                  | PostgreSQL                | CONFIRMED |
| Database Name             | `garment_erp`             | CONFIRMED |
| Frontend                  | React + Vite + TypeScript | CONFIRMED |
| Styling                   | Tailwind CSS              | CONFIRMED |
| UI Components             | shadcn/ui                 | PROPOSED  |
| Architecture              | Headless Modular Monolith | CONFIRMED |
| Repository Structure      | Monorepo                  | CONFIRMED |
| Frontend State Management | Not selected              | UNDECIDED |
| Authentication Strategy   | Not selected              | UNDECIDED |
| Database Access Strategy  | Not selected              | UNDECIDED |
| PHP Version               | Not specified             | UNDECIDED |
| Laravel Version           | Not specified             | UNDECIDED |
| Deployment / Hosting      | Not selected              | UNDECIDED |

### Development Environment

- Operating System: Fedora Linux.
- IDE: Visual Studio Code.
- Version Control: Git.
- Repository Hosting: GitHub.

Application code must remain independent of the development operating system.

### Planned Infrastructure

The following infrastructure technologies are planned for the deployment implementation phase:

- Docker.
- Docker Compose.
- Nginx.
- HTTPS.
- Ubuntu VPS.
- CI/CD.

These are planned deployment components, not confirmation that the production infrastructure has already been selected or implemented.

Detailed deployment decisions belong in:

`docs/architecture/DEPLOYMENT_ARCHITECTURE.md`

---

## 4. Monorepo Structure

The project uses a monorepo repository structure.

The logical application layout is:

    garment-erp/
    |
    +-- apps/
    |   |
    |   +-- api/
    |   |   +-- Laravel ERP Core
    |   |
    |   +-- web/
    |       +-- React + Vite + TypeScript
    |
    +-- docs/
    |   |
    |   +-- business/
    |   +-- architecture/
    |   +-- api/
    |   +-- database/
    |   +-- engineering/
    |   +-- diagrams/
    |
    +-- docker/
    |   +-- Deployment configuration
    |
    +-- docker-compose.yml
    +-- Makefile
    +-- README.md
    +-- .gitignore

The `apps/api` directory contains the Laravel backend application.

The `apps/web` directory contains the React frontend application.

The `docs/` directory contains business, architecture, engineering, API, database, and diagram documentation.

The `docker/` directory contains deployment-related configuration and supporting resources.

Docker-related files are introduced according to the approved deployment implementation plan.

The exact internal Laravel and React directory structures are governed by their respective application architecture and engineering standards.

---

## 5. System Responsibilities

### 5.1 Laravel ERP Core

The Laravel backend is responsible for:

- Customer management.
- Order management.
- Sampling and Sample / Look Approval.
- LoA administration and approval workflow enforcement.
- Payment recording and correction workflow.
- Production Readiness.
- Waiting / On Hold management.
- PPIC Preparation.
- Functional Layout.
- Mass Production tracking.
- Production progress recording.
- QC and Rework operational tracking.
- Timeline management.
- Delay Reports.
- Production Issue Reports.
- Order Change Requests.
- Packing and Fulfillment tracking.
- Dashboard data aggregation.
- In-App Notifications.
- Delay Report email dispatch.
- Audit history.
- Authentication and authorization.

All capabilities must follow approved business requirements.

Unresolved business rules must not be converted into implementation assumptions.

### 5.2 React Frontend

The React application is responsible for:

- User interface.
- User interaction.
- Client-side navigation.
- Form handling.
- UX validation.
- REST API communication.
- Data presentation.
- Displaying business states returned by the backend.
- Presenting loading, success, and error states.

The frontend must not independently determine:

- Business workflow transitions.
- Approval decisions.
- Production Readiness.
- Authoritative production progress calculations.
- Authorization decisions.
- Business-critical status changes.

These decisions must be provided or enforced by ERP Core.

### 5.3 PostgreSQL

PostgreSQL is the authoritative persistent data store.

Responsibilities include:

- Persistent business records.
- Referential integrity.
- Data constraints.
- Transactional consistency.
- Historical business records.
- Required audit records.
- Production progress history.
- Notification persistence where applicable.

Database design must follow:

`docs/database/DATABASE_DESIGN.md`

The actual physical schema must be approved incrementally.

---

## 6. System Boundaries

### 6.1 Frontend Boundary

React communicates with ERP Core through REST API endpoints.

The frontend must not:

- Connect directly to PostgreSQL.
- Bypass backend validation.
- Perform authoritative workflow transitions.
- Grant itself permissions.
- Modify protected business records without backend authorization.

### 6.2 Backend Boundary

Laravel ERP Core must:

- Validate all incoming requests.
- Enforce approved business rules.
- Enforce role-based authorization.
- Manage database transactions.
- Preserve required historical records.
- Return consistent API responses.
- Protect unauthorized business information.

The backend must not rely on frontend restrictions for security enforcement.

### 6.3 Database Boundary

Only the backend application is responsible for application-level database access.

Database access must use approved backend database access mechanisms.

The frontend must never receive direct database credentials.

---

## 7. Persistent Business State

PostgreSQL is the authoritative source of truth for persistent business data.

The backend must not rely on in-memory application state as the authoritative source of business records.

Application instances must be able to process requests independently without depending on request history stored in a specific application instance.

Temporary in-memory state may be used for non-authoritative purposes when justified.

Persistent business records must survive application restarts and deployment replacement.

Historical data must follow approved business retention and traceability requirements.

---

## 8. Technology and Implementation Governance

1. Laravel / PHP is the only active backend implementation.
2. The previous Go implementation is discontinued.
3. Do not introduce Go-specific architecture, packages, commands, or development requirements into the active system.
4. Backend architecture must follow Laravel conventions while preserving the approved modular monolith boundaries.
5. React must remain independent of backend implementation details.
6. API contracts must remain the communication boundary.
7. PostgreSQL remains the persistent source of truth.
8. Database schema changes must use approved migrations.
9. Do not introduce additional frameworks or infrastructure without documented technical justification.
10. Do not introduce business requirements through technical design.
11. Technical decisions must remain separate from business decisions.
12. Unresolved decisions must retain their documented status.

---

## 9. Architecture Decision Records

Architectural decisions and their rationale must be maintained as Architecture Decision Records (ADRs).

ADRs must document:

- Decision context.
- Problem being addressed.
- Available alternatives.
- Selected decision.
- Reasons for selection.
- Consequences and trade-offs.
- Related documentation.

ADRs must be stored in:

`docs/decisions/`

A technical decision that materially changes the approved architecture must be documented.

Do not silently change architectural decisions through implementation alone.

---

## 10. Related Documentation

### Product and Business

- `docs/business/PRD.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`
- `docs/business/USER_PERSONAS.md`
- `docs/business/GLOSSARY.md`

### Architecture

- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/architecture/DEPLOYMENT_ARCHITECTURE.md`

### API

- `docs/api/API_CONTRACTS.md`

### Database

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`

### Engineering

- `docs/engineering/CODE_STYLE.md`
- `docs/engineering/ERROR_HANDLING.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/GIT_WORKFLOW.md`

---

## 11. Document Governance

This document defines the high-level architecture of Garment ERP Core.

Detailed implementation responsibilities belong to the relevant architecture and engineering documents.

Any architectural change must remain consistent with PRD.md and approved business requirements.

The system architecture, application architecture, database design, API contracts, and implementation must remain synchronized.

No implementation may silently introduce a new business capability, workflow, permission, or calculation.
