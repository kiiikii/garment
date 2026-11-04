# API Contracts

Status: APPROVED API GOVERNANCE BASELINE — PHASE 1

This document establishes the approved API design principles,
conventions, resource boundaries, and planned endpoint inventory
for Phase 1.

It does not represent a complete set of finalized endpoint contracts.

Detailed endpoint contracts must be reviewed and approved
incrementally before implementation.

The backend is the authoritative owner of business logic, workflow
transitions, validation, authorization, persistence, audit history,
and notification processing.

The frontend is an API consumer and must not independently implement
or override backend business rules.

---

## 1. API Architecture

### 1.1 API Style

- REST over HTTP.
- JSON request and response bodies.
- Resource-oriented endpoint design.
- Backend-owned business logic and workflow enforcement.
- Versioned API paths.
- Explicit HTTP status codes.
- Consistent request validation and error handling.

### 1.2 Base Path

All application API endpoints must use:

`/api/v1`

Infrastructure endpoints, such as health checks, may exist outside
this base path.

### 1.3 System Boundary

The API is the communication boundary between:

- React + Vite frontend.
- Go ERP Core backend.
- PostgreSQL database.

The frontend must communicate with the backend through the API.

The frontend must not access the database directly.

### 1.4 Backend Responsibilities

The backend must own:

- Business rule enforcement.
- Workflow state transitions.
- Request validation.
- Authentication.
- Role-based authorization.
- Data persistence.
- Transaction management.
- Audit history.
- Notification creation and dispatch initiation.
- Timeline calculations.
- Production progress data processing according to approved rules.
- Dashboard data aggregation according to approved business definitions.

The backend must reject invalid operations even when the frontend
has already performed client-side validation.

---

## 2. API Conventions

### 2.1 HTTP Methods

Use HTTP methods according to their intended resource operations.

| Method | Purpose |
|---|---|
| GET | Retrieve resources |
| POST | Create resources or execute explicit state-changing operations |
| PATCH | Partially update editable resource attributes |
| PUT | Replace a resource representation when full replacement is appropriate |
| DELETE | Delete a resource only when permitted by its business lifecycle |

State-changing workflow operations must use explicit endpoints
when the operation represents a business action.

Examples:

- Approving a sample.
- Approving an LoA.
- Resolving a production issue.
- Approving an Order Change Request.

Do not use generic resource updates to bypass workflow validation.

### 2.2 Resource Naming

- Use plural nouns for collection resources.
- Use lowercase kebab-case for multiword resource names.
- Use resource identifiers in path parameters.
- Use nested resources when the relationship is meaningful
  and consistent with the domain model.
- Avoid exposing database table names as API design requirements.

Use `{parameter}` notation for path parameters in documentation.

Examples:

- `/api/v1/orders`
- `/api/v1/orders/{id}`
- `/api/v1/delay-reports`
- `/api/v1/issue-reports`

### 2.3 Request and Response Format

- JSON must be used for standard request and response bodies.
- Request fields must have documented data types.
- Required and optional fields must be explicitly identified.
- Nullable fields must be explicitly documented.
- Enum values must follow the approved business vocabulary.
- Date and time serialization must be consistent across endpoints.

The final date/time serialization format must be documented
before implementing affected endpoints.

### 2.4 HTTP Status Codes

Use HTTP status codes according to:

`docs/engineering/ERROR_HANDLING.md`

The following conventions apply:

| Status Code | Usage |
|---|---|
| 400 Bad Request | Malformed request or invalid request format |
| 401 Unauthorized | Authentication is missing or invalid |
| 403 Forbidden | Authenticated user does not have permission |
| 404 Not Found | Requested resource does not exist or cannot be accessed according to the applicable security policy |
| 409 Conflict | Request conflicts with the current resource or workflow state |
| 422 Unprocessable Entity | Request structure is valid but business validation fails |
| 500 Internal Server Error | Unexpected server error |

The API must distinguish between:

- Invalid request format.
- Missing authentication.
- Insufficient authorization.
- Missing resources.
- Business rule conflicts.
- Business validation failures.
- Unexpected server errors.

The detailed error response schema must be consistent
with the error handling specification.

### 2.5 Authentication

Authentication is required for protected application endpoints.

Authentication mechanism and implementation details:

UNDECIDED — Sprint 15.

Do not assume a specific authentication technology or token format
before the authentication design is approved.

### 2.6 Authorization

Authorization must be enforced by the backend.

Role permissions must follow:

`docs/business/USER_ROLES.md`

The frontend must not be treated as an authorization boundary.

Every protected endpoint must document its required permissions.

Resource ownership and visibility restrictions must also be enforced
by the backend where applicable.

### 2.7 Pagination, Filtering, and Sorting

Collection endpoints must document whether they support:

- Pagination.
- Filtering.
- Sorting.
- Search.

The pagination and query parameter conventions must be consistent
across collection endpoints.

Exact parameter names and response metadata must be finalized
before implementing the first collection endpoint.

### 2.8 Resource Identifiers

Every resource must expose a stable identifier.

The identifier format must be consistent across the API.

The exact identifier strategy remains an implementation decision
and must be documented before implementation.

### 2.9 File Uploads

Order product images and other supported attachments must be
handled through an explicit backend-controlled upload mechanism.

The upload mechanism, storage provider, file constraints,
and upload response contract must be documented before implementation.

Do not assume that files are stored directly in PostgreSQL.

The minimum product image requirement remains UNDECIDED.

---

## 3. Resource Boundaries

The API must represent the business capabilities defined by:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`

The following resource boundaries define the intended API capabilities.

They do not automatically imply that every capability must become
a separate database entity.

### 3.1 Customer

Responsible for customer information used by orders.

Expected capabilities:

- Create customer.
- Retrieve customer.
- List customers.
- Update customer information.

Customer deletion and archival behavior must follow approved
business and data-retention rules.

The strategy for preserving customer information snapshots
when creating orders remains UNDECIDED.

### 3.2 Order

Responsible for the main customer order record.

Expected capabilities:

- Create order.
- Retrieve order details.
- List and filter orders.
- Update permitted order information.
- Retrieve order history.
- Retrieve order timeline.
- Retrieve related production information.

Order creation must support the confirmed Phase 1 requirements:

- Customer name.
- Customer phone number.
- Customer origin or address.
- Product reference images, if available.
- Size-based quantity information.
- Production type: CMT or FOB.

Additional fields must follow the approved business documentation.

The following decisions remain UNDECIDED:

- Minimum product image requirement.
- Total quantity storage and calculation strategy.
- Customer information snapshot strategy.

The API must not assume that total quantity is independently
submitted or calculated until the data model is approved.

### 3.3 Sampling

Responsible for sampling records associated with an order.

Expected capabilities:

- Create sampling record.
- Retrieve sampling details.
- Retrieve sampling history.
- Record sampling revisions.
- Record sampling approval or rejection.

Supported sampling statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

Only APPROVED sampling satisfies the sampling approval requirement.

The exact relationship between Order and Sampling must follow
the approved domain model.

The responsible role for sampling operations and the approval
authority remain subject to the approved role definitions.

### 3.4 Sample / Look Approval

Responsible for recording the customer's sample or look approval.

Expected capabilities:

- Record approval decision.
- Record rejection or revision decision.
- Retrieve approval history.

Approval status and transition rules must follow
`BUSINESS_RULES.md` and `BUSINESS_WORKFLOW.md`.

Do not assume that this capability is identical to internal
sampling approval.

The authorized reviewer and approval authority remain UNDECIDED.

Approval endpoints must not be implemented until the authorized
reviewer and required permissions are explicitly defined.

### 3.5 LoA

Responsible for the Letter of Agreement and its approval workflow.

Expected capabilities:

- Create LoA record.
- Retrieve LoA details.
- Record revision.
- Record approval or rejection.
- Retrieve LoA history.

Supported LoA statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

LoA approval enables production readiness evaluation.

It does not automatically mean the order is production-ready.

Admin is responsible for LoA administration.

LoA approval authority remains UNDECIDED.

Approval endpoints must not be implemented until the authorized
approver and required permissions are explicitly defined.

### 3.6 Payment

Responsible for recording received customer payments.

Expected capabilities:

- Record payment.
- Retrieve payment history.
- List payments associated with an order.
- Submit payment correction.
- Approve or reject payment correction.
- Retrieve payment correction history.

Confirmed responsibilities:

- Admin records received payments.
- Owner approves or rejects payment corrections.

Payment recording must remain separate from production readiness
unless an explicitly approved business rule establishes a payment gate.

Do not assume that recording a payment automatically changes
the order's production status.

Payment correction rules and transaction identification
must follow the approved payment requirements.

### 3.7 Production Readiness

Responsible for evaluating order-specific production prerequisites.

Expected capabilities:

- Retrieve readiness information.
- Record readiness evaluation.
- Retrieve unmet prerequisites.
- Retrieve readiness history.
- Re-evaluate readiness after prerequisites are satisfied.

The API must preserve the distinction between:

- Production readiness.
- Waiting / On Hold.
- Production stage execution.

Waiting / On Hold is not a production stage.

Waiting / On Hold state-changing operations must not be implemented
until the authorized roles, transition conditions, and resumption
rules are approved.

### 3.8 Functional Layout

Responsible for the functional production layout prepared by PPIC.

Expected capabilities:

- Create layout.
- Retrieve layout.
- Update permitted layout information.
- Retrieve layout history.

Functional layout represents production work areas or functional zones.

It is not a production stage and must not independently
contribute to mass production progress.

Do not introduce a separate layout approval gate unless
it is explicitly approved in business documentation.

### 3.9 Production Tracking

Responsible for mass production stage monitoring.

The confirmed production sequence is:

`CUTTING → SEWING → QC → (PASS → FINISHING | FAIL → REWORK → QC AGAIN) → PACKING → DELIVERY / CUSTOMER PICKUP → COMPLETED`

Expected capabilities:

- Retrieve production tracking information.
- Retrieve stage progress.
- Submit permitted stage progress updates.
- Retrieve stage progress history.
- Retrieve production status.

Confirmed percentage-monitored stages:

- CUTTING
- SEWING
- FINISHING

PPIC is responsible for submitting percentage-based progress
updates according to the approved role definitions.

Production Staff must not submit percentage-based progress updates.

QC and REWORK must be monitored through operational status records,
not percentage-based progress updates.

The exact QC and REWORK status transitions must follow
the approved production workflow.

Do not invent an overall production progress formula or weighting.

Phase 1 does not include actual-piece production quantity tracking,
reject quantity tracking, or rework quantity tracking.

### 3.10 Timeline

Responsible for planned and actual production timeline information.

Expected capabilities:

- Retrieve order timeline.
- Retrieve planned dates.
- Retrieve actual dates.
- Retrieve estimated completion date.
- Retrieve stage duration information.
- Retrieve delay-related timeline information.

The API must distinguish:

- Planned Start Date.
- Planned Completion Date.
- Actual Start Date.
- Actual Completion Date.
- Estimated Completion Date.

Original planned dates must remain traceable when estimated
completion dates change.

Timeline calculations must follow approved calendar and
duration rules.

Do not invent holiday handling or delay aggregation rules.

### 3.11 Delay Report

Responsible for production delay reporting.

Expected capabilities:

- Create delay report.
- Retrieve delay report.
- List delay reports.
- Update permitted follow-up information.
- Resolve delay report.
- Retrieve delay report history.

Delay report creation must preserve the required information,
including affected stage, reason, chronology, and estimated
additional working days.

Email processing must distinguish between:

- Email dispatch initiation.
- Dispatch attempt.
- Delivery outcome.

The API must not guarantee successful email delivery merely
because a delay report was created.

Delay Report email dispatch is separate from in-app notifications.

### 3.12 Production Issue Report

Responsible for internal production issue reporting.

Expected capabilities:

- Create issue report.
- Retrieve permitted issue reports.
- List issue reports according to authorization.
- Update permitted issue report information.
- Record issue resolution.
- Retrieve issue report history.

Supported statuses:

- OPEN
- IN_PROGRESS
- RESOLVED

Transition rules must follow approved business decisions.

Do not assume that IN_PROGRESS is mandatory before RESOLVED.

Confirmed Phase 1 in-app notification events:

- A new Production Issue Report notifies PPIC.
- A Production Issue Report status change notifies
  the reporting Production Staff user.

Notification creation and visibility must follow
the approved notification rules.

### 3.13 Order Change Request

Responsible for controlled order specification changes after LoA approval.

Expected capabilities:

- Create change request.
- Retrieve change request.
- List change requests.
- Approve change request.
- Reject change request.
- Retrieve change request history.

Confirmed responsibilities:

- Admin creates Order Change Requests.
- Owner approves or rejects Order Change Requests.

The backend must enforce these authorization rules.

Order Change Requests are permitted after LoA approval
and before Cutting begins.

Normal specification changes must not be allowed
after Cutting has actually started.

Previous approved order versions must remain traceable.

### 3.14 Packing and Fulfillment

Responsible for tracking required fulfillment completion.

Expected capabilities:

- Retrieve fulfillment information.
- Record permitted fulfillment information.
- Retrieve fulfillment history.

The system must distinguish between:

- Packing.
- Delivery.
- Customer Pickup.
- Order completion.

The exact operational responsibility and evidence requirements
must follow approved business decisions.

An order must not become COMPLETED before required fulfillment
completion is confirmed.

### 3.15 Dashboard

Responsible for role-based operational and business summaries.

Expected capabilities:

- Retrieve Owner dashboard.
- Retrieve Admin dashboard.
- Retrieve PPIC dashboard.
- Retrieve Production Staff dashboard.

Dashboard responses must use authoritative business records.

Dashboard authorization and visibility must follow
`USER_ROLES.md`.

Dashboard calculations must follow the approved definitions
in `BUSINESS_RULES.md`.

Do not calculate authoritative business metrics independently
in the frontend.

### 3.16 Notifications

Responsible for retrieving and managing supported in-app notifications.

Confirmed Phase 1 in-app notification scope:

- Production Issue Report notifications.

Confirmed notification events:

- A new Production Issue Report notifies PPIC.
- A Production Issue Report status change notifies
  the reporting Production Staff user.

Expected capabilities:

- Retrieve notifications.
- Retrieve unread notification count.
- Mark notification as read.
- Mark notification as unread.

Notification visibility must be enforced per user.

Do not introduce additional notification events unless approved.

Delay Report email dispatch is a separate capability from
in-app notification management.

### 3.17 Audit History

Responsible for retrieving permitted historical records.

Expected capabilities:

- Retrieve order status audit history.
- Retrieve permitted workflow history.
- Retrieve permitted change history.

The confirmed Owner access scope is read-only access
to order status audit history.

Access to other application audit records must follow
separately approved authorization requirements.

Users must not be able to modify audit history through
ordinary resource update endpoints.

The exact audit visibility and retention policies remain
subject to approved business decisions.

---

## 4. Endpoint Inventory

This inventory defines the planned API surface for Phase 1.

It is not a substitute for the detailed endpoint contracts.

Exact paths, request schemas, response schemas, and permissions
must be documented before implementation.

### 4.1 Infrastructure

| Method | Path | Purpose |
|---|---|---|
| GET | `/health` | Liveness check |

The health endpoint must follow the existing Sprint 0 implementation.

### 4.2 Customers

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/customers` | Create customer |
| GET | `/api/v1/customers` | List customers |
| GET | `/api/v1/customers/{id}` | Retrieve customer |
| PATCH | `/api/v1/customers/{id}` | Update customer |

### 4.3 Orders

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders` | Create order |
| GET | `/api/v1/orders` | List orders |
| GET | `/api/v1/orders/{id}` | Retrieve order |
| PATCH | `/api/v1/orders/{id}` | Update permitted order information |
| GET | `/api/v1/orders/{id}/timeline` | Retrieve order timeline |
| GET | `/api/v1/orders/{id}/history` | Retrieve order history |

### 4.4 Sampling

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/sampling` | Create sampling record |
| GET | `/api/v1/orders/{orderId}/sampling` | List order sampling records |
| GET | `/api/v1/sampling/{id}` | Retrieve sampling details |
| POST | `/api/v1/sampling/{id}/revisions` | Record sampling revision |
| POST | `/api/v1/sampling/{id}/approve` | Approve sampling |
| POST | `/api/v1/sampling/{id}/reject` | Reject sampling |

Approval and rejection endpoints remain PLANNED until
the authorized roles and permissions are defined.

### 4.5 Sample / Look Approval

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/sample-approvals` | Record sample/look approval decision |
| GET | `/api/v1/orders/{orderId}/sample-approvals` | Retrieve approval history |
| GET | `/api/v1/sample-approvals/{id}` | Retrieve approval details |

The exact revision, approval, and rejection operations must
follow the approved workflow transitions and authorization rules.

### 4.6 LoA

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/loa` | Create LoA |
| GET | `/api/v1/orders/{orderId}/loa` | Retrieve order LoA records |
| GET | `/api/v1/loa/{id}` | Retrieve LoA details |
| POST | `/api/v1/loa/{id}/revisions` | Record LoA revision |
| POST | `/api/v1/loa/{id}/approve` | Approve LoA |
| POST | `/api/v1/loa/{id}/reject` | Reject LoA |

Approval and rejection endpoints remain PLANNED until
the authorized approver and required permissions are defined.

### 4.7 Payments

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/payments` | Record payment |
| GET | `/api/v1/orders/{orderId}/payments` | List order payments |
| GET | `/api/v1/payments/{id}` | Retrieve payment |
| POST | `/api/v1/payments/{id}/corrections` | Submit payment correction |
| POST | `/api/v1/payment-corrections/{id}/approve` | Approve payment correction |
| POST | `/api/v1/payment-corrections/{id}/reject` | Reject payment correction |

### 4.8 Production Readiness

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/orders/{orderId}/readiness` | Retrieve readiness information |
| POST | `/api/v1/orders/{orderId}/readiness/evaluations` | Record readiness evaluation |
| GET | `/api/v1/orders/{orderId}/readiness/history` | Retrieve readiness history |

Waiting / On Hold state-changing endpoints are not included
until the authorized roles and transition rules are approved.

### 4.9 Functional Layout

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/layout` | Create functional layout |
| GET | `/api/v1/orders/{orderId}/layout` | Retrieve functional layout |
| PATCH | `/api/v1/layouts/{id}` | Update permitted layout information |
| GET | `/api/v1/layouts/{id}/history` | Retrieve layout history |

### 4.10 Production Tracking

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/orders/{orderId}/production` | Retrieve production information |
| GET | `/api/v1/orders/{orderId}/production/stages` | Retrieve production stages |
| GET | `/api/v1/orders/{orderId}/production/progress` | Retrieve percentage-based production progress |
| POST | `/api/v1/orders/{orderId}/production/stages/{stage}/progress` | Submit permitted stage progress |
| GET | `/api/v1/orders/{orderId}/production/progress/history` | Retrieve progress history |

The progress submission endpoint is restricted to:

- CUTTING
- SEWING
- FINISHING

The backend must validate the stage and the user's permission
before accepting progress updates.

QC and REWORK must not use the percentage-based progress endpoint.

Their operational status update contracts must be defined
after the required status transitions are approved.

### 4.11 Delay Reports

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/delay-reports` | Create delay report |
| GET | `/api/v1/delay-reports` | List permitted delay reports |
| GET | `/api/v1/delay-reports/{id}` | Retrieve delay report |
| PATCH | `/api/v1/delay-reports/{id}` | Update permitted follow-up information |
| POST | `/api/v1/delay-reports/{id}/resolve` | Resolve delay report |
| GET | `/api/v1/delay-reports/{id}/history` | Retrieve delay report history |

### 4.12 Production Issue Reports

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/issue-reports` | Create production issue report |
| GET | `/api/v1/issue-reports` | List permitted issue reports |
| GET | `/api/v1/issue-reports/{id}` | Retrieve issue report |
| PATCH | `/api/v1/issue-reports/{id}` | Update permitted issue information |
| POST | `/api/v1/issue-reports/{id}/resolve` | Resolve issue report |
| GET | `/api/v1/issue-reports/{id}/history` | Retrieve issue report history |

### 4.13 Order Change Requests

| Method | Path | Purpose |
|---|---|---|
| POST | `/api/v1/orders/{orderId}/change-requests` | Create change request |
| GET | `/api/v1/orders/{orderId}/change-requests` | List order change requests |
| GET | `/api/v1/change-requests/{id}` | Retrieve change request |
| POST | `/api/v1/change-requests/{id}/approve` | Approve change request |
| POST | `/api/v1/change-requests/{id}/reject` | Reject change request |

The backend must enforce:

- Admin permission to create change requests.
- Owner permission to approve or reject change requests.
- The approved timing restrictions for order changes.

### 4.14 Packing and Fulfillment

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/orders/{orderId}/fulfillment` | Retrieve fulfillment information |
| POST | `/api/v1/orders/{orderId}/fulfillment` | Record fulfillment information |
| PATCH | `/api/v1/fulfillment/{id}` | Update permitted fulfillment information |

The exact fulfillment operations must be finalized against
the approved operational workflow before implementation.

### 4.15 Dashboards

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/dashboard/owner` | Owner dashboard |
| GET | `/api/v1/dashboard/admin` | Admin dashboard |
| GET | `/api/v1/dashboard/ppic` | PPIC dashboard |
| GET | `/api/v1/dashboard/production` | Production Staff dashboard |

Each dashboard endpoint must enforce role-based authorization.

### 4.16 Notifications

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/notifications` | Retrieve user notifications |
| GET | `/api/v1/notifications/unread-count` | Retrieve unread count |
| POST | `/api/v1/notifications/{id}/read` | Mark notification as read |
| POST | `/api/v1/notifications/{id}/unread` | Mark notification as unread |

Notification visibility and recipient access must be enforced per user.

Only approved Phase 1 notification events may create in-app notifications.

### 4.17 Audit History

| Method | Path | Purpose |
|---|---|---|
| GET | `/api/v1/orders/{orderId}/audit-history` | Retrieve permitted order status audit history |

Additional audit history endpoints must only be introduced
when required by an approved capability and its authorization rules.

---

## 5. Detailed Endpoint Contract Template

Every endpoint must have a detailed contract before implementation.

Use the following template.

### 5.1 Endpoint Definition

**Endpoint Name:**

A descriptive name for the API operation.

**Purpose:**

Explain the business capability provided by the endpoint.

**Method:**

HTTP method.

**Path:**

Complete API path.

**Implementation Status:**

Use one of:

- PLANNED
- READY FOR IMPLEMENTATION
- IMPLEMENTED
- TESTED
- DEPRECATED

An endpoint must not be marked READY FOR IMPLEMENTATION
until its required business rules, permissions, and technical
contract details have been approved.

### 5.2 Authentication and Authorization

**Authentication:**

Specify whether authentication is required.

**Required Role / Permission:**

Specify the permitted roles and required permissions.

**Resource Access:**

Document any resource ownership, department, or visibility restrictions.

Authorization must follow `USER_ROLES.md`.

### 5.3 Request Contract

**Path Parameters:**

| Parameter | Type | Required | Description |
|---|---|---|---|

**Query Parameters:**

| Parameter | Type | Required | Description |
|---|---|---|---|

**Request Body:**

| Field | Type | Required | Validation | Description |
|---|---|---|---|---|

Document:

- Required fields.
- Optional fields.
- Nullable fields.
- Allowed values.
- Field constraints.
- Related resource identifiers.

### 5.4 Success Response

Document:

- HTTP status code.
- Response structure.
- Returned fields.
- Resource identifiers.
- Relevant metadata.

The response must not expose unauthorized information.

### 5.5 Error Responses

Document applicable errors:

- 400 Bad Request.
- 401 Unauthorized.
- 403 Forbidden.
- 404 Not Found.
- 409 Conflict.
- 422 Unprocessable Entity.
- 500 Internal Server Error.

Use the standard error structure defined in:

`docs/engineering/ERROR_HANDLING.md`

Do not define a separate error format for individual endpoints.

### 5.6 Business Rules Enforced

List the relevant rules from:

- `BUSINESS_RULES.md`
- `BUSINESS_WORKFLOW.md`
- `USER_ROLES.md`

Do not introduce new business rules in API documentation.

### 5.7 Workflow Effects

Document:

- Whether the operation changes workflow state.
- Previous state.
- Resulting state.
- Conditions required for the transition.
- Invalid transitions that must be rejected.

If the operation does not change workflow state, explicitly state that.

### 5.8 Data and Transaction Effects

Document:

- Records created.
- Records updated.
- Related records affected.
- Transaction boundaries.
- Duplicate submission behavior.
- Required history preservation.

State-changing operations must preserve the required business history.

### 5.9 Audit and Notifications

Document:

- Whether an audit record is required.
- Whether a notification is created.
- Whether an email dispatch is initiated.
- Whether delivery outcomes must be retained.

Do not assume side effects that have not been approved.

### 5.10 Acceptance Criteria

Each endpoint must have testable acceptance criteria
covering applicable scenarios, including:

- Successful operation.
- Invalid request data.
- Unauthorized access, where authentication is required.
- Missing resource, where applicable.
- Invalid workflow transition, for state-changing operations.
- Business rule violation.
- Relevant duplicate or conflict scenarios.
- Required audit and notification side effects.

---

## 6. API Implementation Rules

1. Do not implement an endpoint without an approved detailed contract.
2. Do not invent business rules or workflow transitions.
3. Do not bypass backend validation.
4. Do not use frontend visibility as authorization.
5. Do not expose database internals as API requirements.
6. Do not introduce resource entities without domain justification.
7. Do not allow generic updates to bypass explicit workflow operations.
8. Do not delete historical business records when the business rules
   require history preservation.
9. Do not calculate authoritative business metrics in the frontend.
10. Do not return information that the authenticated user is not
    authorized to access.
11. Do not silently change existing API contracts.
12. Update the API documentation whenever an approved contract changes.
13. Add tests for successful operations and relevant failure conditions.
14. Keep API behavior consistent with the approved business documents.
15. Treat unresolved requirements as UNDECIDED rather than making
    implementation assumptions.

---

## 7. Open Technical Decisions

The following technical decisions must be resolved before
implementing the affected endpoints.

| Decision | Status |
|---|---|
| Authentication mechanism and token/session strategy | UNDECIDED |
| Standard error response schema | UNDECIDED |
| Pagination convention | UNDECIDED |
| Filtering and sorting convention | UNDECIDED |
| Date and time serialization format | UNDECIDED |
| Resource identifier format | UNDECIDED |
| File upload and storage mechanism | UNDECIDED |
| API rate limiting requirements | UNDECIDED |
| Request idempotency requirements for applicable operations | UNDECIDED |
| API documentation format and generation strategy | UNDECIDED |

These decisions must be documented before implementation
of affected capabilities.

---

## 8. Related Documentation

Business documentation:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/USER_PERSONAS.md`
- `docs/business/GLOSSARY.md`

Engineering documentation:

- `docs/engineering/ERROR_HANDLING.md`

Architecture and data documentation:

- `docs/architecture/`
- `docs/database/`

---

## 9. Contract Governance

This document is the baseline for Phase 1 API design.

Detailed endpoint contracts may be added incrementally,
but they must remain consistent with this document.

Any change affecting business behavior must first be reviewed
against the approved business documentation.

Any change affecting API structure must be documented here.

The backend implementation, automated tests, and frontend integration
must follow the same approved API contract.

No implementation may silently introduce a new business capability,
workflow transition, permission, or calculation.