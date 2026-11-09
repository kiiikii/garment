# API Contracts

**Product:** Garment ERP Core
**Status:** APPROVED API GOVERNANCE BASELINE — PHASE 1
**Backend:** Laravel / PHP
**API Style:** REST
**API Version:** v1

This document establishes the approved API design principles, conventions, resource boundaries, and planned endpoint inventory for Phase 1.

It does not represent a complete set of finalized endpoint contracts.

Detailed endpoint contracts must be reviewed and approved incrementally before implementation.

The backend is the authoritative owner of:

- Business logic.
- Workflow transitions.
- Request validation.
- Authentication.
- Authorization.
- Persistence.
- Audit history.
- Notification processing.

The frontend is an API consumer and must not independently implement or override backend business rules.

---

## 1. API Architecture

### 1.1 API Style

- REST over HTTP.
- JSON request and response bodies.
- Resource-oriented endpoint design.
- Laravel ERP Core as the backend.
- Versioned API paths.
- Explicit HTTP status codes.
- Consistent request validation.
- Consistent error handling.
- Backend-owned business logic and workflow enforcement.

### 1.2 Base Path

All application API endpoints must use:

`/api/v1`

Infrastructure endpoints, such as health checks, may exist outside this base path.

### 1.3 System Boundary

The API is the communication boundary between:

- React + Vite frontend.
- Laravel ERP Core backend.
- PostgreSQL database.

The frontend must communicate with the backend through REST API.

The frontend must not access PostgreSQL directly.

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
- Production progress processing according to approved rules.
- Dashboard data aggregation according to approved definitions.

The backend must reject invalid operations even when the frontend has already performed client-side validation.

---

## 2. API Conventions

### 2.1 HTTP Methods

| Method | Purpose                                                         |
| ------ | --------------------------------------------------------------- |
| GET    | Retrieve resources                                              |
| POST   | Create resources or execute explicit state-changing operations  |
| PATCH  | Partially update permitted resource attributes                  |
| PUT    | Replace a resource representation when appropriate              |
| DELETE | Delete a resource only when permitted by its business lifecycle |

State-changing workflow operations must use explicit endpoints when the operation represents a business action.

Examples:

- Recording an approval decision.
- Resolving a Production Issue Report.
- Resolving a Delay Report.
- Approving an Order Change Request.
- Approving a Payment Correction.

Generic resource updates must not bypass workflow validation.

### 2.2 Resource Naming

- Use plural nouns for collection resources.
- Use lowercase kebab-case for multiword resource names.
- Use resource identifiers in path parameters.
- Use nested resources when the relationship is meaningful.
- Avoid exposing database table names as API requirements.

Use `{parameter}` notation for path parameters.

Examples:

- `/api/v1/customers`
- `/api/v1/orders`
- `/api/v1/delay-reports`
- `/api/v1/issue-reports`
- `/api/v1/change-requests`

### 2.3 Request and Response Format

Standard request and response bodies must use JSON.

Request fields must document:

- Data type.
- Required or optional status.
- Nullable status.
- Allowed values.
- Validation constraints.
- Description.

Date and time serialization must remain consistent.

The final date/time serialization format remains UNDECIDED until approved in the API design process.

### 2.4 HTTP Status Codes

The following status codes define the API baseline.

| Code | Meaning               | Usage                                                                              |
| ---- | --------------------- | ---------------------------------------------------------------------------------- |
| 200  | OK                    | Successful retrieval or operation                                                  |
| 201  | Created               | Resource successfully created                                                      |
| 204  | No Content            | Successful operation without response body, where appropriate                      |
| 400  | Bad Request           | Malformed JSON or invalid request syntax                                           |
| 401  | Unauthorized          | Authentication is missing or invalid                                               |
| 403  | Forbidden             | Authenticated user lacks permission                                                |
| 404  | Not Found             | Resource does not exist or cannot be accessed under the applicable security policy |
| 409  | Conflict              | Operation conflicts with current resource or workflow state                        |
| 422  | Unprocessable Content | Structurally valid request that fails field or business validation                 |
| 500  | Internal Server Error | Unexpected server-side failure                                                     |

The distinction between 400 and 422 is:

**400**

The request cannot be processed because its structure or syntax is invalid.

**422**

The request is structurally valid, but its field values or business conditions fail approved validation rules.

The API must distinguish between:

- Invalid request syntax.
- Missing authentication.
- Insufficient authorization.
- Missing resources.
- Workflow conflicts.
- Business validation failures.
- Unexpected server errors.

The standard error response structure is governed by:

`docs/engineering/ERROR_HANDLING.md`

### 2.5 Authentication

Authentication is required for protected application endpoints.

Authentication mechanism and implementation details:

UNDECIDED.

Do not assume a specific authentication package, token format, session strategy, or credential mechanism before the authentication design is approved.

Customers have no login accounts in Phase 1.

### 2.6 Authorization

Authorization must be enforced by Laravel ERP Core.

Role permissions must follow:

`docs/business/USER_ROLES.md`

The frontend must not be treated as an authorization boundary.

Every protected endpoint must document:

- Required role or permission.
- Resource ownership restrictions.
- Department restrictions, where applicable.
- Assignment restrictions, where applicable.

Confirmed authorization boundaries:

| Capability                               | Confirmed Responsibility |
| ---------------------------------------- | ------------------------ |
| Customer Management                      | ADMIN                    |
| Order Administration                     | ADMIN                    |
| Payment Recording                        | ADMIN                    |
| Payment Correction Approval              | OWNER                    |
| Order Change Request Creation            | ADMIN                    |
| Order Change Request Approval            | OWNER                    |
| Production Readiness                     | PPIC                     |
| Functional Layout                        | PPIC                     |
| Production Progress                      | PPIC                     |
| Delay Reports                            | PPIC                     |
| Production Issue Report Creation         | PRODUCTION_STAFF         |
| Production Issue Report Management       | PPIC                     |
| Production Issue Report Read-only Access | OWNER                    |
| Own Issue Report Visibility              | PRODUCTION_STAFF         |
| Fulfillment Confirmation                 | PPIC                     |
| Order Status Audit History               | OWNER, read-only         |

The following remain unresolved:

- Sample / Look Approval authority.
- LoA Approval authority.
- Detailed Production Staff assignment restrictions.
- Complete permission matrix.
- Additional audit visibility.

Do not implement protected approval endpoints until the authorized role is defined.

### 2.7 Pagination, Filtering, and Sorting

Collection endpoints must document whether they support:

- Pagination.
- Filtering.
- Sorting.
- Search.

The pagination and query parameter conventions must remain consistent across collection endpoints.

Exact parameter names and response metadata remain UNDECIDED.

### 2.8 Resource Identifiers

Every resource must expose a stable identifier.

The identifier format must be consistent across the API.

The exact identifier strategy remains UNDECIDED.

### 2.9 File Uploads

Product reference images and supported attachments must use an explicit backend-controlled upload mechanism.

The following remain subject to technical design:

- Storage provider.
- File size constraints.
- Accepted formats.
- Upload response structure.
- File metadata.
- Access control.
- Retention behavior.

Do not assume binary files are stored directly in PostgreSQL.

The minimum product image requirement remains UNDECIDED.

---

## 3. Resource Boundaries

API resources represent business capabilities.

They do not automatically require separate database tables.

The resource boundaries must follow:

- `BUSINESS_WORKFLOW.md`
- `BUSINESS_RULES.md`
- `USER_ROLES.md`
- `GLOSSARY.md`

### 3.1 Customer

Responsible for customer information used by orders.

Expected capabilities:

- Create customer.
- Retrieve customer.
- List customers.
- Update customer information.

Customer deletion and archival behavior must follow approved business and data-retention rules.

The customer snapshot strategy at order creation remains UNDECIDED.

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

Confirmed order information:

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Size Quantities.
- Production Type: CMT or FOB.
- Product Reference Images, supported but not confirmed mandatory.

Rules:

1. Only CMT and FOB are supported in Phase 1.
2. Quantities are recorded individually by size.
3. Negative quantities are prohibited.
4. Total quantity equals the sum of recorded size quantities when calculated.
5. Total quantity storage strategy remains UNDECIDED.
6. Product image mandatory requirement remains UNDECIDED.

Before LoA approval, Admin may update permitted order details.

After LoA approval, protected specification changes require an Order Change Request.

After Cutting actually begins, normal specification changes are prohibited.

The backend must enforce these restrictions.

### 3.3 Sampling

Responsible for recording the sampling process associated with an order.

Sampling consists of:

1. Pattern Preparation.
2. Sample Production.

Duration reference:

| Activity               | Internal Duration |
| ---------------------- | ----------------: |
| Pattern Preparation    |            2 days |
| Sample Production      |            2 days |
| Internal Total         |            4 days |
| Customer-facing Target |    7 working days |

Expected capabilities:

- Create sampling record.
- Retrieve sampling details.
- Retrieve sampling history.
- Record sampling activity information.

Sampling is separate from Sample / Look Approval.

The exact sampling data structure and activity status model remain subject to domain and database design.

Do not assign approval statuses to internal sampling activities without an approved business decision.

### 3.4 Sample / Look Approval

Responsible for recording the approval decision after the sample is submitted.

Supported statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

Rules:

1. Only APPROVED satisfies the Sample Approval Gate.
2. REVISION does not satisfy the gate.
3. REJECTED does not automatically cancel the order.
4. Rejected samples must not automatically proceed to LoA.
5. Revision and resubmission must follow approved business rules.

Expected capabilities:

- Record approval decision.
- Retrieve approval details.
- Retrieve approval history.
- Record applicable revision decisions.

Approval authority remains UNKNOWN.

Approval endpoints must not be implemented until authorized reviewers and permissions are defined.

### 3.5 LoA

Responsible for the Letter of Agreement and commercial approval.

LoA includes:

- Letter of Agreement / MoU.
- Payment Agreement.
- Payment Terms.

Supported statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

Rules:

1. Sample Approval allows progression to LoA.
2. Only APPROVED satisfies the LoA Gate.
3. LoA approval enables Production Readiness evaluation.
4. LoA approval does not automatically establish Production Readiness.
5. LoA approval locks protected order specifications.
6. Changes to locked specifications require an Order Change Request.

Admin is responsible for LoA administration.

LoA approval authority remains UNKNOWN.

Approval endpoints must not be implemented until the authorized approver is defined.

### 3.6 Payment

Responsible for recording actual received customer payments.

Expected capabilities:

- Record payment.
- Retrieve payment.
- List payments associated with an order.
- Retrieve payment history.
- Submit payment correction.
- Approve or reject payment correction.
- Retrieve payment correction history.

Confirmed responsibilities:

- ADMIN records payments actually received.
- OWNER approves or rejects payment corrections.

Rules:

1. Expected payments must not be recorded as received payments.
2. Payment corrections must preserve original records.
3. Correction history must remain traceable.
4. Duplicate transactions must not be counted more than once.
5. Payment recording does not automatically establish Production Readiness.
6. DP as a production prerequisite remains UNDECIDED.

Detailed correction transitions and transaction identification must follow approved payment requirements.

### 3.7 Production Readiness

Responsible for evaluating order-specific prerequisites.

Expected capabilities:

- Retrieve readiness information.
- Record readiness evaluation.
- Retrieve unmet prerequisites.
- Retrieve readiness history.
- Re-evaluate readiness.

Rules:

1. Sample Approval and LoA Approval are necessary conditions.
2. Both approvals are insufficient by themselves.
3. PPIC evaluates Production Readiness.
4. Requirements depend on the actual order and production type.
5. Readiness must be satisfied before PPIC Preparation.
6. DP payment does not automatically establish readiness.

The complete readiness checklist remains UNKNOWN.

Do not invent a universal readiness checklist.

### 3.8 Waiting / On Hold

Responsible for tracking orders with unsatisfied Production Readiness conditions.

Rules:

- Waiting / On Hold is conditional.
- It is not a production process stage.
- Waiting reasons must be recorded.
- Readiness must be re-evaluated before resuming.
- Orders must not resume automatically based only on a date.
- Resolving one reason does not automatically establish full readiness.

Waiting categories, resume conditions, and complete authorization requirements remain UNDECIDED.

Do not implement unsupported state transitions.

### 3.9 Functional Layout

Responsible for the functional production layout prepared by PPIC.

Expected capabilities:

- Create layout.
- Retrieve layout.
- Update permitted layout information.
- Retrieve layout history.

Functional Layout represents physical production work areas.

Examples:

- Cutting.
- Sewing.
- Overlock.
- Embroidery.
- Finishing.

Rules:

1. PPIC prepares the Functional Layout.
2. Functional Layout is not a production process stage.
3. Functional Layout does not contribute to Mass Production progress.
4. A separate layout approval gate has not been confirmed.

### 3.10 Production Tracking

Responsible for monitoring Mass Production.

Confirmed production sequence:

`CUTTING → SEWING → QC`

QC result:

- PASS → FINISHING
- FAIL → REWORK → QC AGAIN

After successful production:

`FINISHING → PACKING → DELIVERY / CUSTOMER PICKUP → FULFILLMENT CONFIRMED → COMPLETED`

Expected capabilities:

- Retrieve production information.
- Retrieve production stages.
- Retrieve stage progress.
- Submit permitted stage progress.
- Retrieve progress history.
- Retrieve operational production status.

Percentage-monitored stages:

- CUTTING.
- SEWING.
- FINISHING.

Rules:

1. Only PPIC submits percentage-based progress.
2. Production Staff must not submit progress percentages.
3. Every submission preserves timestamp and submitting user.
4. Historical submissions must not be overwritten.
5. Unchanged percentage submissions must still be recorded.
6. QC and REWORK must not receive percentage-progress mechanics without approval.
7. Overall progress formula and weighting remain UNDECIDED.
8. Actual-piece production tracking is outside Phase 1.
9. Reject and Rework quantity tracking are not mandatory Phase 1 requirements.

### 3.11 Timeline

Responsible for planned and actual production timeline information.

Expected capabilities:

- Retrieve order timeline.
- Retrieve planned dates.
- Retrieve actual dates.
- Retrieve estimated completion date.
- Retrieve duration information.
- Retrieve related Delay Reports.

The API must distinguish:

- Planned Start Date.
- Actual Start Date.
- Planned Completion Date.
- Estimated Completion Date.
- Actual Completion Date.
- Planned Duration.
- Actual Duration.

Rules:

1. Original planned completion date must remain preserved.
2. Estimated completion is a separate value.
3. Actual dates must not overwrite planned dates.
4. Calendar days and working days must not be treated as interchangeable.
5. Delay Reports do not automatically update estimated completion.
6. Delay Reports do not automatically establish an overdue condition.

Temporary Phase 1 calendar:

- Monday–Friday: Working Days.
- Saturday–Sunday: Non-working Days.

National and company holidays remain UNKNOWN.

Overdue calculation basis remains UNDECIDED.

### 3.12 Delay Report

Responsible for production delay reporting.

Required information:

- Order Number.
- Affected Production Stage.
- Reason.
- Chronology / Explanation.
- Estimated Additional Duration in Working Days.

Supported statuses:

- OPEN
- RESOLVED

Expected capabilities:

- Create Delay Report.
- Retrieve Delay Report.
- List Delay Reports.
- Update permitted follow-up information.
- Resolve Delay Report.
- Retrieve Delay Report history.

Confirmed responsibilities:

- PPIC creates and manages Delay Reports.
- PPIC may resolve reports without Owner approval.

Email processing must distinguish:

- Dispatch initiation.
- Dispatch attempt.
- Delivery outcome.

Intended recipients:

- PPIC.
- Owner.
- Customer.

Customer email source and mandatory requirements remain UNDECIDED.

Email failure must not invalidate the Delay Report.

Delay Reports are separate from In-App Notifications.

### 3.13 Production Issue Report

Responsible for operational issue reporting.

Required information:

- Related Order.
- Department / Production Stage.
- Issue Type.
- Description.
- Automatically Recorded Timestamp.
- Optional Photo.

Supported statuses:

- OPEN
- IN_PROGRESS
- RESOLVED

Expected capabilities:

- Create issue report.
- Retrieve permitted issue reports.
- List permitted issue reports.
- Update permitted issue information.
- Record resolution.
- Retrieve issue report history.

Confirmed responsibilities:

- Production Staff creates reports.
- PPIC manages all reports.
- Owner has read-only access.
- Admin has no access.
- Production Staff can view their own reports.
- PPIC must provide a resolution note before RESOLVED.

IN_PROGRESS is not confirmed as a mandatory transition before RESOLVED.

Confirmed In-App Notification events:

- New issue report → notify PPIC.
- Issue status changed → notify reporting Production Staff.

### 3.14 Order Change Request

Responsible for controlled changes to protected order specifications.

Expected capabilities:

- Create Change Request.
- Retrieve Change Request.
- List Change Requests.
- Approve Change Request.
- Reject Change Request.
- Retrieve Change Request history.

Confirmed responsibilities:

- ADMIN creates requests.
- OWNER approves or rejects requests.

Rules:

1. After LoA approval, protected changes require a Change Request.
2. Changes are restricted to before Cutting begins.
3. Approved changes must preserve previous order versions.
4. Affected Production Readiness requirements must be revalidated.
5. Affected Functional Layout information must be revalidated.
6. Normal specification changes are prohibited after Cutting starts.

Detailed state transitions remain UNDECIDED.

### 3.15 Packing and Fulfillment

Responsible for tracking fulfillment completion.

Expected capabilities:

- Retrieve fulfillment information.
- Record permitted fulfillment information.
- Retrieve fulfillment history.

The API must distinguish:

- Packing.
- Delivery.
- Customer Pickup.
- Fulfillment Confirmation.
- Order Completion.

PPIC confirms fulfillment completion.

Detailed evidence, operational responsibility, and transitions
remain UNDECIDED.

An order must not become COMPLETED before required fulfillment conditions are satisfied.

### 3.16 Dashboard

Responsible for role-based operational summaries.

Expected capabilities:

- Retrieve Owner dashboard.
- Retrieve Admin dashboard.
- Retrieve PPIC dashboard.
- Retrieve Production Staff dashboard.

Dashboard responses must derive from authoritative records.

Dashboard authorization must follow USER_ROLES.md.

Dashboard calculations must follow approved business definitions.

Do not invent:

- Total Order Value formulas.
- Revenue calculation rules.
- Overdue calculation.
- Overall production progress weighting.
- No-progress thresholds.

### 3.17 Notifications

Confirmed Phase 1 In-App Notification scope:

- Production Issue Reports.

Events:

- New Production Issue Report notifies PPIC.
- Production Issue Report status change notifies reporting Staff.

Expected capabilities:

- Retrieve notifications.
- Retrieve unread count.
- Mark notification as read.
- Mark notification as unread.

Read state:

- READ
- UNREAD

Read state is maintained per user.

Duplicate notifications for the same event and recipient must be prevented.

Delay Report emails are a separate capability.

Do not introduce additional notification events without business approval.

### 3.18 Audit History

Responsible for retrieving permitted historical records.

Expected capabilities:

- Retrieve order-status audit history.
- Retrieve permitted workflow history.
- Retrieve permitted change history.

Confirmed Owner access:

- Read-only order-status audit history.

Additional audit visibility remains subject to approved authorization requirements.

Audit history must not be modified through ordinary resource update endpoints.

---

## 4. Planned Endpoint Inventory

This inventory defines the planned Phase 1 API surface.

It does not represent a finalized implementation contract.

Every endpoint must have an approved detailed contract before implementation.

### 4.1 Infrastructure

| Method | Path      | Purpose        |
| ------ | --------- | -------------- |
| GET    | `/health` | Liveness check |

### 4.2 Customers

| Method | Path                     | Purpose           |
| ------ | ------------------------ | ----------------- |
| POST   | `/api/v1/customers`      | Create customer   |
| GET    | `/api/v1/customers`      | List customers    |
| GET    | `/api/v1/customers/{id}` | Retrieve customer |
| PATCH  | `/api/v1/customers/{id}` | Update customer   |

### 4.3 Orders

| Method | Path                           | Purpose                            |
| ------ | ------------------------------ | ---------------------------------- |
| POST   | `/api/v1/orders`               | Create order                       |
| GET    | `/api/v1/orders`               | List orders                        |
| GET    | `/api/v1/orders/{id}`          | Retrieve order                     |
| PATCH  | `/api/v1/orders/{id}`          | Update permitted order information |
| GET    | `/api/v1/orders/{id}/timeline` | Retrieve order timeline            |
| GET    | `/api/v1/orders/{id}/history`  | Retrieve order history             |

The backend must reject protected specification changes that bypass Order Change Request requirements.

### 4.4 Sampling

| Method | Path                                | Purpose                     |
| ------ | ----------------------------------- | --------------------------- |
| POST   | `/api/v1/orders/{orderId}/sampling` | Create sampling record      |
| GET    | `/api/v1/orders/{orderId}/sampling` | List order sampling records |
| GET    | `/api/v1/sampling/{id}`             | Retrieve sampling details   |

### 4.5 Sample / Look Approval

| Method | Path                                        | Purpose                   |
| ------ | ------------------------------------------- | ------------------------- |
| POST   | `/api/v1/orders/{orderId}/sample-approvals` | Record approval decision  |
| GET    | `/api/v1/orders/{orderId}/sample-approvals` | Retrieve approval history |
| GET    | `/api/v1/sample-approvals/{id}`             | Retrieve approval details |

Decision recording and approval operations remain subject to confirmation of the authorized reviewer.

### 4.6 LoA

| Method | Path                           | Purpose              |
| ------ | ------------------------------ | -------------------- |
| POST   | `/api/v1/orders/{orderId}/loa` | Create LoA record    |
| GET    | `/api/v1/orders/{orderId}/loa` | Retrieve order LoA   |
| GET    | `/api/v1/loa/{id}`             | Retrieve LoA details |
| POST   | `/api/v1/loa/{id}/revisions`   | Record LoA revision  |
| POST   | `/api/v1/loa/{id}/approve`     | Approve LoA          |
| POST   | `/api/v1/loa/{id}/reject`      | Reject LoA           |

Approval and rejection endpoints remain PLANNED and must not be implemented until the authorized approver and required permissions are defined.

### 4.7 Payments

| Method | Path                                       | Purpose                    |
| ------ | ------------------------------------------ | -------------------------- |
| POST   | `/api/v1/orders/{orderId}/payments`        | Record payment             |
| GET    | `/api/v1/orders/{orderId}/payments`        | List order payments        |
| GET    | `/api/v1/payments/{id}`                    | Retrieve payment           |
| POST   | `/api/v1/payments/{id}/corrections`        | Submit payment correction  |
| POST   | `/api/v1/payment-corrections/{id}/approve` | Approve payment correction |
| POST   | `/api/v1/payment-corrections/{id}/reject`  | Reject payment correction  |

Payment correction endpoints must follow the approved correction workflow.

### 4.8 Production Readiness

| Method | Path                                             | Purpose                        |
| ------ | ------------------------------------------------ | ------------------------------ |
| GET    | `/api/v1/orders/{orderId}/readiness`             | Retrieve readiness information |
| POST   | `/api/v1/orders/{orderId}/readiness/evaluations` | Record readiness evaluation    |
| GET    | `/api/v1/orders/{orderId}/readiness/history`     | Retrieve readiness history     |

Waiting / On Hold state-changing endpoints are not included until their authorization and transition rules are approved.

### 4.9 Functional Layout

| Method | Path                              | Purpose                             |
| ------ | --------------------------------- | ----------------------------------- |
| POST   | `/api/v1/orders/{orderId}/layout` | Create Functional Layout            |
| GET    | `/api/v1/orders/{orderId}/layout` | Retrieve Functional Layout          |
| PATCH  | `/api/v1/layouts/{id}`            | Update permitted layout information |
| GET    | `/api/v1/layouts/{id}/history`    | Retrieve layout history             |

### 4.10 Production Tracking

| Method | Path                                                          | Purpose                            |
| ------ | ------------------------------------------------------------- | ---------------------------------- |
| GET    | `/api/v1/orders/{orderId}/production`                         | Retrieve production information    |
| GET    | `/api/v1/orders/{orderId}/production/stages`                  | Retrieve production stages         |
| GET    | `/api/v1/orders/{orderId}/production/progress`                | Retrieve percentage-based progress |
| POST   | `/api/v1/orders/{orderId}/production/stages/{stage}/progress` | Submit stage progress              |
| GET    | `/api/v1/orders/{orderId}/production/progress/history`        | Retrieve progress history          |

The progress endpoint is restricted to:

- CUTTING.
- SEWING.
- FINISHING.

Only PPIC may submit progress.

QC and REWORK must not use percentage-based progress endpoints.

### 4.11 Delay Reports

| Method | Path                                 | Purpose                                |
| ------ | ------------------------------------ | -------------------------------------- |
| POST   | `/api/v1/delay-reports`              | Create Delay Report                    |
| GET    | `/api/v1/delay-reports`              | List permitted reports                 |
| GET    | `/api/v1/delay-reports/{id}`         | Retrieve Delay Report                  |
| PATCH  | `/api/v1/delay-reports/{id}`         | Update permitted follow-up information |
| POST   | `/api/v1/delay-reports/{id}/resolve` | Resolve Delay Report                   |
| GET    | `/api/v1/delay-reports/{id}/history` | Retrieve report history                |

### 4.12 Production Issue Reports

| Method | Path                                 | Purpose                            |
| ------ | ------------------------------------ | ---------------------------------- |
| POST   | `/api/v1/issue-reports`              | Create issue report                |
| GET    | `/api/v1/issue-reports`              | List permitted reports             |
| GET    | `/api/v1/issue-reports/{id}`         | Retrieve issue report              |
| PATCH  | `/api/v1/issue-reports/{id}`         | Update permitted issue information |
| POST   | `/api/v1/issue-reports/{id}/resolve` | Resolve issue report               |
| GET    | `/api/v1/issue-reports/{id}/history` | Retrieve issue history             |

### 4.13 Order Change Requests

| Method | Path                                       | Purpose                    |
| ------ | ------------------------------------------ | -------------------------- |
| POST   | `/api/v1/orders/{orderId}/change-requests` | Create Change Request      |
| GET    | `/api/v1/orders/{orderId}/change-requests` | List order Change Requests |
| GET    | `/api/v1/change-requests/{id}`             | Retrieve Change Request    |
| POST   | `/api/v1/change-requests/{id}/approve`     | Approve Change Request     |
| POST   | `/api/v1/change-requests/{id}/reject`      | Reject Change Request      |

The backend must enforce:

- ADMIN permission to create requests.
- OWNER permission to approve or reject requests.
- Approved timing restrictions.

### 4.14 Packing and Fulfillment

| Method | Path                                   | Purpose                                  |
| ------ | -------------------------------------- | ---------------------------------------- |
| GET    | `/api/v1/orders/{orderId}/fulfillment` | Retrieve fulfillment information         |
| POST   | `/api/v1/orders/{orderId}/fulfillment` | Record permitted fulfillment information |
| PATCH  | `/api/v1/fulfillment/{id}`             | Update permitted fulfillment information |

Detailed operations must be finalized against the approved operational workflow.

### 4.15 Dashboards

| Method | Path                           | Purpose                    |
| ------ | ------------------------------ | -------------------------- |
| GET    | `/api/v1/dashboard/owner`      | Owner dashboard            |
| GET    | `/api/v1/dashboard/admin`      | Admin dashboard            |
| GET    | `/api/v1/dashboard/ppic`       | PPIC dashboard             |
| GET    | `/api/v1/dashboard/production` | Production Staff dashboard |

Every dashboard endpoint must enforce authorization.

### 4.16 Notifications

| Method | Path                                 | Purpose                     |
| ------ | ------------------------------------ | --------------------------- |
| GET    | `/api/v1/notifications`              | Retrieve user notifications |
| GET    | `/api/v1/notifications/unread-count` | Retrieve unread count       |
| POST   | `/api/v1/notifications/{id}/read`    | Mark as read                |
| POST   | `/api/v1/notifications/{id}/unread`  | Mark as unread              |

Notification visibility must be enforced per user.

Only approved Phase 1 events may create In-App Notifications.

### 4.17 Audit History

| Method | Path                                     | Purpose                                       |
| ------ | ---------------------------------------- | --------------------------------------------- |
| GET    | `/api/v1/orders/{orderId}/audit-history` | Retrieve permitted order-status audit history |

Additional audit endpoints require approved capabilities and authorization rules.

---

## 5. Detailed Endpoint Contract Template

Every endpoint must have a detailed contract before implementation.

### 5.1 Endpoint Definition

**Endpoint Name:**

Descriptive name for the operation.

**Purpose:**

Explain the business capability.

**Method:**

HTTP method.

**Path:**

Complete API path.

**Implementation Status:**

- PLANNED
- READY FOR IMPLEMENTATION
- IMPLEMENTED
- TESTED
- DEPRECATED

An endpoint must not be READY FOR IMPLEMENTATION until its business rules, permissions, and technical contract details are approved.

### 5.2 Authentication and Authorization

**Authentication:**

Specify whether authentication is required.

**Required Role / Permission:**

Specify permitted roles and required permissions.

**Resource Access:**

Document resource ownership, department, and assignment restrictions.

Authorization must follow USER_ROLES.md.

### 5.3 Request Contract

**Path Parameters**

| Parameter | Type | Required | Description |
| --------- | ---- | -------- | ----------- |

**Query Parameters**

| Parameter | Type | Required | Description |
| --------- | ---- | -------- | ----------- |

**Request Body**

| Field | Type | Required | Validation | Description |
| ----- | ---- | -------- | ---------- | ----------- |

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

Document applicable responses:

- 400 Bad Request.
- 401 Unauthorized.
- 403 Forbidden.
- 404 Not Found.
- 409 Conflict.
- 422 Unprocessable Content.
- 500 Internal Server Error.

Use the standard error structure defined in:

`docs/engineering/ERROR_HANDLING.md`

Do not define a separate error format per endpoint.

### 5.6 Business Rules Enforced

List applicable rules from:

- BUSINESS_RULES.md
- BUSINESS_WORKFLOW.md
- USER_ROLES.md

Do not introduce new business rules in API documentation.

### 5.7 Workflow Effects

Document:

- Whether the operation changes workflow state.
- Previous state.
- Resulting state.
- Conditions required for transition.
- Invalid transitions that must be rejected.

If no workflow state changes, state that explicitly.

### 5.8 Data and Transaction Effects

Document:

- Records created.
- Records updated.
- Related records affected.
- Transaction boundaries.
- Duplicate submission behavior, if defined.
- Required history preservation.

### 5.9 Audit and Notifications

Document:

- Whether an audit record is required.
- Whether an In-App Notification is created.
- Whether email dispatch is initiated.
- Whether delivery outcomes must be retained.

Do not assume unapproved side effects.

### 5.10 Acceptance Criteria

Each endpoint must have testable acceptance criteria.

Include applicable scenarios:

- Successful operation.
- Invalid request data.
- Unauthorized access.
- Missing resource.
- Invalid workflow transition.
- Business rule violation.
- Relevant conflict conditions.
- Required audit and notification effects.

---

## 6. API Implementation Rules

1. Do not implement endpoints without approved contracts.
2. Do not invent business rules.
3. Do not invent workflow transitions.
4. Do not bypass backend validation.
5. Do not use frontend visibility as authorization.
6. Do not expose database internals as API requirements.
7. Do not create resource entities without domain justification.
8. Do not use generic updates to bypass workflow operations.
9. Do not delete historical records when preservation is required.
10. Do not calculate authoritative business metrics in the frontend.
11. Do not expose unauthorized information.
12. Do not silently change API contracts.
13. Update API documentation when approved contracts change.
14. Add tests for successful operations and relevant failures.
15. Keep API behavior consistent with approved business documents.
16. Preserve unresolved requirements as UNDECIDED.

---

## 7. Open Technical Decisions

| Decision                                | Status    |
| --------------------------------------- | --------- |
| Authentication mechanism                | UNDECIDED |
| Token / session strategy                | UNDECIDED |
| Standard error response schema          | UNDECIDED |
| Pagination convention                   | UNDECIDED |
| Filtering and sorting convention        | UNDECIDED |
| Date and time serialization             | UNDECIDED |
| Resource identifier format              | UNDECIDED |
| File upload and storage mechanism       | UNDECIDED |
| API rate limiting requirements          | UNDECIDED |
| Request idempotency requirements        | UNDECIDED |
| API documentation generation strategy   | UNDECIDED |
| Sample / Look Approval authority        | UNKNOWN   |
| LoA Approval authority                  | UNKNOWN   |
| Detailed Waiting / On Hold transitions  | UNDECIDED |
| Detailed QC / REWORK status operations  | UNDECIDED |
| Fulfillment evidence and responsibility | UNDECIDED |

These decisions must be resolved before implementing the affected capabilities.

---

## 8. Related Documentation

Business:

- `docs/business/PRD.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`

Architecture:

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/architecture/DEPLOYMENT_ARCHITECTURE.md`

Database:

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`

Engineering:

- `docs/engineering/ERROR_HANDLING.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`

---

## 9. Contract Governance

This document is the Phase 1 API design baseline.

Detailed endpoint contracts may be added incrementally.

Any change affecting business behavior must first be reviewed against approved business documentation.

Any change affecting API structure must be documented here.

Backend implementation, automated tests, and frontend integration must follow the same approved API contract.

No implementation may silently introduce a new business capability, workflow transition, permission, or calculation.
