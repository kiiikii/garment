# Application Architecture

**Product:** Garment ERP Core
**Status:** DRAFT FOR REVIEW — ALIGNED WITH PRD v1.0
**Architecture:** Headless Modular Monolith
**Backend:** Laravel / PHP
**Frontend:** React + Vite + TypeScript
**Database:** PostgreSQL

This document defines the internal application architecture and responsibilities of the backend and frontend applications.

It must remain consistent with SYSTEM_ARCHITECTURE.md, PRD.md, BUSINESS_RULES.md, BUSINESS_WORKFLOW.md, USER_ROLES.md, API_CONTRACTS.md, and DATABASE_DESIGN.md.

---

## 1. Backend Architecture

The backend is implemented using Laravel / PHP.

Laravel ERP Core is responsible for all authoritative business operations.

The backend uses a Modular Monolith architecture.

Business capabilities must be separated into logical modules without introducing unnecessary architectural complexity.

### 1.1 Backend Request Flow

The intended request flow is:

    HTTP REQUEST
         |
         v
    LARAVEL APPLICATION
         |
         v
    API ROUTER
         |
         v
    MIDDLEWARE
         |
         v
    FORM REQUEST VALIDATION
         |
         v
    CONTROLLER
         |
         v
    APPLICATION SERVICE / USE CASE
         |
         v
    BUSINESS RULES
         |
         v
    PERSISTENCE LAYER
         |
         v
    POSTGRESQL
         |
         v
    API RESOURCE / RESPONSE
         |
         v
    HTTP RESPONSE

This represents the logical responsibility flow.

It does not require every endpoint to introduce a separate class for every layer.

Layers must be introduced progressively according to actual application responsibilities.

### 1.2 Layer Responsibilities

| Layer                          | Responsibility                                             |
| ------------------------------ | ---------------------------------------------------------- |
| Router                         | Maps HTTP methods and paths to application controllers     |
| Middleware                     | Handles cross-cutting HTTP concerns                        |
| Form Request                   | Handles request structure and input validation             |
| Controller                     | Receives HTTP input and coordinates application operations |
| Application Service / Use Case | Coordinates business operations                            |
| Business Logic                 | Enforces approved business rules and workflow transitions  |
| Persistence Layer              | Handles database access and persistence                    |
| API Resource                   | Defines the external response representation               |
| PostgreSQL                     | Stores persistent business data                            |

### 1.3 Layer Boundaries

**Router**

- Defines API routes.
- Maps routes to controllers.
- Must not contain business logic.

**Middleware**

- Handles cross-cutting request concerns.
- May enforce configured authentication and authorization.
- Must not independently implement domain-specific workflows.

**Form Request**

- Validates request structure and field-level constraints.
- Provides a centralized location for applicable request validation.
- Must not replace business-level validation.
- Must not introduce unapproved business constraints.

**Controller**

- Handles HTTP input.
- Invokes the appropriate application operation.
- Returns the appropriate API response.
- Must remain focused on HTTP responsibilities.
- Must not directly implement complex business workflows.

**Application Service / Use Case**

- Coordinates business operations.
- Applies approved workflow rules.
- Coordinates related operations.
- Manages transactional business operations when required.
- Must not depend on frontend implementation details.

**Business Logic**

- Enforces approved business rules.
- Validates workflow transitions.
- Protects business invariants.
- Must remain independent of HTTP-specific implementation details.

**Persistence Layer**

- Handles database queries and persistence.
- Must follow the approved database access strategy.
- Must not independently determine business workflow decisions.

**API Resource**

- Controls response representation.
- Prevents accidental exposure of internal data.
- Must respect authorization and data visibility restrictions.

### 1.4 Architectural Flexibility

The architecture must remain practical.

Do not require:

- A separate Service class for every endpoint.
- A Repository interface for every database table.
- An additional abstraction for simple operations.
- A separate module for every API resource.
- A separate class for every business rule.

Introduce architectural abstractions only when they provide a clear benefit.

The Repository pattern is not mandatory.

The database access strategy remains subject to DATABASE_DESIGN.md.

---

## 2. Laravel Application Structure

The backend application resides in:

`apps/api/`

Laravel's application structure must be followed without introducing unnecessary custom conventions.

### 2.1 Proposed Logical Structure

The following structure is a reference for organizing
the application:

    apps/api/
    |
    +-- app/
    |   |
    |   +-- Domain/
    |   |   +-- Customers/
    |   |   +-- Orders/
    |   |   +-- Sampling/
    |   |   +-- Commercial/
    |   |   +-- Production/
    |   |   +-- Fulfillment/
    |   |
    |   +-- Application/
    |   |
    |   +-- Http/
    |   |   |
    |   |   +-- Controllers/
    |   |   |   +-- Api/
    |   |   |       +-- V1/
    |   |   |
    |   |   +-- Middleware/
    |   |   |
    |   |   +-- Requests/
    |   |   |   +-- Api/
    |   |   |       +-- V1/
    |   |   |
    |   |   +-- Resources/
    |   |       +-- Api/
    |   |           +-- V1/
    |   |
    |   +-- Infrastructure/
    |   |   +-- Persistence/
    |   |
    |   +-- Providers/
    |
    +-- bootstrap/
    +-- config/
    +-- database/
    |   +-- migrations/
    |   +-- factories/
    |   +-- seeders/
    |
    +-- routes/
    |   +-- api.php
    |
    +-- storage/
    +-- tests/
    |   +-- Feature/
    |   +-- Unit/
    |
    +-- artisan
    +-- composer.json
    +-- phpunit.xml

This is a proposed logical organization.

The final internal structure must be reviewed against the actual Laravel application skeleton and approved implementation requirements.

Do not create empty directories or unnecessary architectural layers merely to match this diagram.

### 2.2 Business Module Boundaries

The application should organize business responsibilities around the following capabilities:

- Customer Management.
- Order Management.
- Sampling.
- Sample / Look Approval.
- LoA Management.
- Payment Management.
- Production Readiness.
- Waiting / On Hold.
- Functional Layout.
- Mass Production.
- Production Progress.
- Timeline Management.
- Delay Reports.
- Production Issue Reports.
- Order Change Requests.
- Packing and Fulfillment.
- Notifications.
- Audit History.
- Dashboard Aggregation.

These are logical business capabilities.

They do not automatically require separate Laravel packages, database tables, or deployment services.

### 2.3 Dependency Rules

1. Business logic must not depend on frontend code.
2. Business logic must not depend on HTTP request objects.
3. Controllers must not directly own complex business rules.
4. Persistence implementations must not determine workflow transitions.
5. Modules must avoid unnecessary circular dependencies.
6. Cross-module operations must use clear application boundaries.
7. Database transactions must protect related business changes.
8. Business operations must preserve required historical records.

---

## 3. Backend Responsibilities

Laravel ERP Core is responsible for:

### 3.1 Business Operations

- Customer and order management.
- Sampling management.
- Sample / Look Approval.
- LoA administration.
- Payment recording and correction.
- Production Readiness evaluation.
- Waiting / On Hold management.
- PPIC Preparation.
- Functional Layout.
- Mass Production monitoring.
- Production progress submissions.
- QC and Rework operational tracking.
- Timeline management.
- Delay Report management.
- Production Issue Report management.
- Order Change Request management.
- Packing and Fulfillment.
- Dashboard aggregation.

### 3.2 System Operations

- Authentication.
- Authorization and RBAC.
- Request validation.
- Database persistence.
- Transaction management.
- Audit history.
- Notification processing.
- Email dispatch initiation.
- API response formatting.
- Error handling.

All responsibilities must follow confirmed business and technical requirements.

---

## 4. Business Logic and Workflow Enforcement

The backend is the single authority for business logic.

The following operations must be validated by ERP Core:

- Sample Approval Gate.
- LoA Gate.
- Production Readiness.
- Waiting / On Hold transitions.
- Mass Production start.
- Production stage progression.
- QC PASS and FAIL handling.
- Rework and QC repetition.
- Production completion.
- Fulfillment confirmation.
- Order Change Request approval.
- Payment correction approval.

The exact authorized users and detailed transitions must follow BUSINESS_RULES.md and USER_ROLES.md.

Do not implement an approval operation while its approval authority remains UNKNOWN.

### 4.1 Protected Order Specifications

Before LoA approval:

- Admin may edit permitted order information.

After LoA approval:

- Protected specifications become locked.
- Changes require an Order Change Request.
- Owner approves or rejects the request.
- Approved changes must preserve previous order versions.
- Affected readiness requirements must be revalidated.
- Affected Functional Layout information must be revalidated.

After Cutting actually begins:

- Normal specification changes are prohibited.

The backend must enforce these restrictions.

### 4.2 Production Progress

Only PPIC may submit percentage-based progress for the following stages:

- CUTTING.
- SEWING.
- FINISHING.

Every submission must preserve:

- Percentage value.
- Submission timestamp.
- Submitting user.
- Relevant production stage.

Historical submissions must never be overwritten.

QC and REWORK must not receive percentage-progress mechanics until explicitly approved.

Overall progress formula and weighting remain UNDECIDED.

---

## 5. Authentication and Authorization

Authentication and authorization are separate concerns.

Authentication identifies the user.

Authorization determines what the user may access or perform.

The backend must enforce all protected operations.

Frontend visibility is not a security boundary.

### 5.1 Confirmed Internal Roles

- OWNER
- ADMIN
- PPIC
- PRODUCTION_STAFF

### 5.2 Confirmed Responsibilities

**OWNER**

- Management oversight.
- Payment correction approval.
- Order Change Request approval.
- Read-only Production Issue Report access.
- Order-status audit history access.

**ADMIN**

- Customer management.
- Order administration.
- LoA administration.
- Payment recording.
- Order Change Request creation.

**PPIC**

- Production Readiness.
- Waiting / On Hold follow-up.
- Functional Layout preparation.
- Production progress submission.
- Delay Report management.
- Production Issue Report follow-up.
- Fulfillment confirmation.

**PRODUCTION_STAFF**

- Relevant production information.
- Assigned production work visibility.
- Functional Layout access.
- Production Issue Report creation.
- Own issue report visibility.

### 5.3 Authorization Rules

- Every protected operation must be authorized by the backend.
- Direct REST API requests must not bypass authorization.
- Department and assignment restrictions must be enforced.
- Admin must not access Production Issue Reports.
- Production Staff must only access their own issue reports.
- Owner has read-only access to Production Issue Reports.
- Owner has read-only access to order-status audit history.

Unconfirmed permissions must remain UNDECIDED.

---

## 6. Database Interaction

PostgreSQL is the authoritative persistent data store.

The Laravel application is the only application component responsible for database access.

The database access strategy remains subject to DATABASE_DESIGN.md.

Potential Laravel database access mechanisms include:

- Eloquent ORM.
- Laravel Query Builder.
- Parameterized SQL through approved database facilities.

The final selection remains UNDECIDED.

Do not require a Repository pattern solely because the architecture contains a persistence layer.

### 6.1 Transactions

Transactions must be used when multiple related database changes must succeed or fail together.

Examples include:

- Creating an order with its related records.
- Applying an approved Order Change Request.
- Recording a payment correction.
- Performing workflow transitions that require multiple persistent changes.

The exact transaction boundaries must be determined by the relevant business operation.

### 6.2 Historical Data

Required historical records must remain traceable.

Examples:

- Order specification versions.
- Sampling and approval history.
- LoA history.
- Payment correction history.
- Production progress history.
- Delay Report history.
- Production Issue Report history.
- Workflow state changes.

Do not silently overwrite historical information when the business rules require preservation.

---

## 7. API Response Architecture

The backend exposes REST API responses.

The API contract is defined in:

`docs/api/API_CONTRACTS.md`

API response formatting must remain consistent.

Controllers must not independently create different response formats for equivalent operations.

API Resources or an equivalent approved mechanism may be used to control response representation.

The final response schema remains subject to the approved API contract.

---

## 8. Error Handling

Laravel application exceptions must be handled at the appropriate application boundary.

The backend must distinguish:

- Malformed requests.
- Validation failures.
- Authentication failures.
- Authorization failures.
- Missing resources.
- Workflow conflicts.
- Unexpected server failures.

Internal exceptions must not be exposed directly to API clients.

Error handling must follow:

`docs/engineering/ERROR_HANDLING.md`

The standard API error response structure must follow:

`docs/api/API_CONTRACTS.md`

---

## 9. Frontend Architecture

The frontend resides in:

`apps/web/`

Confirmed frontend technologies:

- React.
- Vite.
- TypeScript.
- Tailwind CSS.

shadcn/ui remains PROPOSED.

The frontend is a static Single Page Application (SPA).

The browser communicates with ERP Core through REST API.

The frontend must not connect directly to PostgreSQL.

### 9.1 Frontend Responsibilities

- User interface.
- User interaction.
- Client-side navigation.
- Form handling.
- UX validation.
- REST API communication.
- Loading and error states.
- Data presentation.
- Displaying authoritative business states.

### 9.2 Suggested Frontend Structure

    apps/web/
    |
    +-- src/
    |   |
    |   +-- components/
    |   +-- pages/
    |   +-- features/
    |   +-- hooks/
    |   +-- lib/
    |   +-- types/
    |   +-- assets/
    |   +-- styles/
    |
    +-- public/
    +-- package.json
    +-- vite.config.ts
    +-- tsconfig.json

This is a suggested organization.

The final structure must remain consistent with the actual React application and implementation needs.

### 9.3 Frontend Validation

Frontend validation is intended for user experience.

It must not replace backend validation.

The frontend must not independently determine:

- Approval decisions.
- Production Readiness.
- Workflow transitions.
- Authorization decisions.
- Authoritative progress calculations.
- Protected order changes.

### 9.4 Frontend API Client

The API client is responsible for:

- Centralizing HTTP communication.
- Handling request and response processing.
- Applying the configured authentication mechanism.
- Handling network failures.
- Handling API error responses.
- Providing consistent API access to frontend features.

Authentication implementation remains UNDECIDED.

### 9.5 Frontend Technical Decisions

The following remain UNDECIDED:

- Router.
- Data-fetching strategy.
- Form management.
- State management.
- Authentication integration.
- Light and dark mode defaults.
- Theme switching behavior.

Do not introduce additional libraries without technical justification and approval.

---

## 10. Theme Tokens

The proposed UI direction uses:

- Lime primary.
- Olive-tinted neutrals.
- Emerald chart ramp.
- Radius: `0.45rem`.

shadcn/ui theme implementation remains PROPOSED.

Use semantic theme tokens rather than hard-coded colors.

Domain-specific status color mappings remain UNDECIDED.

Visual status tokens must not introduce or define new business statuses.

```css
:root {
	--background: oklch(1 0 0);
	--foreground: oklch(0.153 0.006 107.1);
	--card: oklch(1 0 0);
	--card-foreground: oklch(0.153 0.006 107.1);
	--popover: oklch(1 0 0);
	--popover-foreground: oklch(0.153 0.006 107.1);
	--primary: oklch(0.841 0.238 128.85);
	--primary-foreground: oklch(0.405 0.101 131.063);
	--secondary: oklch(0.967 0.001 286.375);
	--secondary-foreground: oklch(0.21 0.006 285.885);
	--muted: oklch(0.966 0.005 106.5);
	--muted-foreground: oklch(0.58 0.031 107.3);
	--accent: oklch(0.966 0.005 106.5);
	--accent-foreground: oklch(0.228 0.013 107.4);
	--destructive: oklch(0.577 0.245 27.325);
	--border: oklch(0.93 0.007 106.5);
	--input: oklch(0.93 0.007 106.5);
	--ring: oklch(0.737 0.021 106.9);
	--chart-1: oklch(0.845 0.143 164.978);
	--chart-2: oklch(0.696 0.17 162.48);
	--chart-3: oklch(0.596 0.145 163.225);
	--chart-4: oklch(0.508 0.118 165.612);
	--chart-5: oklch(0.432 0.095 166.913);
	--radius: 0.45rem;
	--sidebar: oklch(0.988 0.003 106.5);
	--sidebar-foreground: oklch(0.153 0.006 107.1);
	--sidebar-primary: oklch(0.648 0.2 131.684);
	--sidebar-primary-foreground: oklch(0.986 0.031 120.757);
	--sidebar-accent: oklch(0.966 0.005 106.5);
	--sidebar-accent-foreground: oklch(0.228 0.013 107.4);
	--sidebar-border: oklch(0.93 0.007 106.5);
	--sidebar-ring: oklch(0.737 0.021 106.9);
}

.dark {
	--background: oklch(0.153 0.006 107.1);
	--foreground: oklch(0.988 0.003 106.5);
	--card: oklch(0.228 0.013 107.4);
	--card-foreground: oklch(0.988 0.003 106.5);
	--popover: oklch(0.228 0.013 107.4);
	--popover-foreground: oklch(0.988 0.003 106.5);
	--primary: oklch(0.768 0.233 130.85);
	--primary-foreground: oklch(0.405 0.101 131.063);
	--secondary: oklch(0.274 0.006 286.033);
	--secondary-foreground: oklch(0.985 0 0);
	--muted: oklch(0.286 0.016 107.4);
	--muted-foreground: oklch(0.737 0.021 106.9);
	--accent: oklch(0.286 0.016 107.4);
	--accent-foreground: oklch(0.988 0.003 106.5);
	--destructive: oklch(0.704 0.191 22.216);
	--border: oklch(1 0 0 / 10%);
	--input: oklch(1 0 0 / 15%);
	--ring: oklch(0.58 0.031 107.3);
	--chart-1: oklch(0.845 0.143 164.978);
	--chart-2: oklch(0.696 0.17 162.48);
	--chart-3: oklch(0.596 0.145 163.225);
	--chart-4: oklch(0.508 0.118 165.612);
	--chart-5: oklch(0.432 0.095 166.913);
	--sidebar: oklch(0.228 0.013 107.4);
	--sidebar-foreground: oklch(0.988 0.003 106.5);
	--sidebar-primary: oklch(0.768 0.233 130.85);
	--sidebar-primary-foreground: oklch(0.274 0.072 132.109);
	--sidebar-accent: oklch(0.286 0.016 107.4);
	--sidebar-accent-foreground: oklch(0.988 0.003 106.5);
	--sidebar-border: oklch(1 0 0 / 10%);
	--sidebar-ring: oklch(0.58 0.031 107.3);
}
```

---

## 11. CORS and API Communication

The browser communicates with Laravel through the configured REST API origin.

CORS must reflect the actual deployment origins.

- Same-origin frontend and API deployment may not require cross-origin browser access.
- Different origins require explicit allowed-origin configuration.
- Wildcard origins must not be used as a substitute for a properly defined security policy.

CORS is not authentication or authorization.

---

## 12. Application Configuration

Application configuration must be separated from source code.

Environment-specific configuration must not be hard-coded into application logic.

Secrets must not be committed to Git.

Development and production configuration must remain separate.

---

## 13. Architecture Governance

1. Laravel is the only active backend framework.
2. The previous Go implementation is discontinued.
3. The backend remains a Modular Monolith.
4. React communicates through REST API.
5. PostgreSQL remains the persistent source of truth.
6. Business logic remains backend-authoritative.
7. Technical layers must be introduced progressively.
8. Do not introduce unnecessary abstractions.
9. Do not silently change approved architecture.
10. Document material architectural decisions.
11. Keep API contracts synchronized with implementation.
12. Preserve unresolved decisions until explicitly approved.

---

## 14. Related Documentation

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/DEPLOYMENT_ARCHITECTURE.md`
- `docs/api/API_CONTRACTS.md`
- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`
- `docs/engineering/CODE_STYLE.md`
- `docs/engineering/ERROR_HANDLING.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/GIT_WORKFLOW.md`
- `docs/business/PRD.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`
