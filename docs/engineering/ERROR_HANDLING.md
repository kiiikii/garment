# Error Handling

**Product:** Garment ERP Core
**Backend:** Laravel / PHP
**API:** REST / JSON

This document defines error classification, validation principles, exception handling, and frontend error handling.

The API error response structure must remain consistent with API_CONTRACTS.md.

---

## 1. Error Handling Principles

- Backend validation is authoritative.
- Frontend validation is for user experience.
- Business validation must follow approved Business Rules.
- Workflow transitions must be validated by the backend.
- Errors must be handled at the appropriate application boundary.
- Internal implementation details must not be exposed to API clients.
- Error handling must remain consistent across endpoints.
- Unexpected failures must be traceable through appropriate logging.

Do not introduce validation rules based on assumptions.

Requirements marked UNKNOWN, UNDECIDED, or PROPOSED must not become mandatory business constraints.

---

## 2. HTTP Status Codes

The following status codes define the API error classification.

| Code | Meaning               | Usage                                                                                        |
| ---- | --------------------- | -------------------------------------------------------------------------------------------- |
| 400  | Bad Request           | Malformed JSON or invalid request syntax                                                     |
| 401  | Unauthorized          | Missing or invalid authentication credentials                                                |
| 403  | Forbidden             | Authenticated user is not allowed to perform the operation                                   |
| 404  | Not Found             | Requested resource does not exist or cannot be accessed under the applicable security policy |
| 409  | Conflict              | Operation conflicts with current resource or workflow state                                  |
| 422  | Unprocessable Content | Structurally valid request that fails approved field or business validation                  |
| 500  | Internal Server Error | Unexpected server-side failure                                                               |

### 2.1 HTTP 400

Use 400 when the request cannot be processed because its syntax or structure is malformed.

Examples:

- Malformed JSON.
- Invalid request syntax.
- Invalid request representation that cannot be parsed.

Do not use 400 for ordinary business-rule failures.

### 2.2 HTTP 401

Use 401 when authentication is missing or invalid.

The authentication mechanism remains UNDECIDED.

### 2.3 HTTP 403

Use 403 when the authenticated user lacks permission.

Examples:

- Admin attempts to access Production Issue Reports.
- Production Staff attempts to submit production progress.
- Unauthorized user attempts to approve an Order Change Request.

Authorization must be enforced by the backend.

### 2.4 HTTP 404

Use 404 when the requested resource does not exist.

Where required by the applicable security policy, a resource that must not be disclosed to the requester may also return 404.

### 2.5 HTTP 409

Use 409 when an operation conflicts with the current resource or workflow state.

Examples:

- Attempting a protected specification change through an ordinary order update after LoA approval.
- Attempting a normal specification change after Cutting starts.
- Attempting a workflow transition that is invalid for the current approved state.

### 2.6 HTTP 422

Use 422 when the request is structurally valid but fails field-level or business validation.

Examples:

- Unsupported production type.
- Negative size quantity.
- Invalid approved workflow condition.
- Required approved business condition is not satisfied.

Do not implement unresolved business requirements as mandatory validation rules.

### 2.7 HTTP 500

Use 500 for unexpected server-side failures.

The response must not expose:

- Stack traces.
- SQL statements.
- Database credentials.
- Internal file paths.
- Internal implementation details.
- Sensitive application configuration.

---

## 3. Validation

The backend is authoritative for all business validation.

Frontend validation must only provide early user feedback.

### 3.1 Validation Responsibilities

Backend validation must cover:

- Request structure.
- Field-level constraints.
- Supported enum values.
- Business rules.
- Workflow transition conditions.
- Authorization requirements.
- Resource relationships.
- Data integrity.

Laravel request validation may be used for request structure and field-level validation.

Business-level validation must remain within the appropriate application or domain operation.

### 3.2 Confirmed Constraints

The following constraints are confirmed:

**Production Type**

Only:

- CMT.
- FOB.

are supported in Phase 1.

**Size Quantities**

- Quantities are recorded individually by size.
- Negative quantities are prohibited.
- Total quantity equals the sum of size quantities when calculated.

**Sample / Look Approval**

Supported statuses:

- PENDING.
- REVISION.
- APPROVED.
- REJECTED.

Only APPROVED satisfies the Sample Approval Gate.

**LoA**

Supported statuses:

- PENDING.
- REVISION.
- APPROVED.
- REJECTED.

Only APPROVED satisfies the LoA Gate.

**Production Progress**

Only PPIC may submit percentage-based progress for:

- CUTTING.
- SEWING.
- FINISHING.

**Production Issue Reports**

Supported statuses:

- OPEN.
- IN_PROGRESS.
- RESOLVED.

PPIC must provide a resolution note before RESOLVED.

**Delay Reports**

Supported statuses:

- OPEN.
- RESOLVED.

New reports start as OPEN.

### 3.3 Workflow Validation

The backend must enforce:

- Sample Approval Gate.
- LoA Gate.
- Production Readiness.
- Waiting / On Hold conditions.
- Production stage sequence.
- QC PASS and FAIL progression.
- Rework followed by QC again.
- Production completion requirements.
- Fulfillment confirmation.
- Order Change Request restrictions.
- Payment correction authorization.

Do not implement unsupported transitions.

### 3.4 Unresolved Validation Requirements

The following remain UNKNOWN or UNDECIDED:

- Customer name requiredness and exact validation behavior.
- Customer phone number format.
- Minimum order quantity.
- Zero quantity handling for individual sizes.
- Custom size handling.
- Multiple product models per order.
- Minimum product image requirement.
- Complete Production Readiness checklist.
- Waiting reason categories.
- Sample / Look Approval authority.
- LoA Approval authority.
- Detailed Payment Correction transitions.
- DP as a production prerequisite.
- QC inspection coverage.
- Rework limits.
- Fulfillment evidence requirements.
- Overdue calculation.
- Overall production progress formula.
- Detailed department and assignment restrictions.

These requirements must be clarified before implementation of affected validation rules.

---

## 4. Laravel Exception Handling

Laravel exceptions must be handled consistently at the application boundary.

The backend must distinguish:

- Request parsing failures.
- Field validation failures.
- Authentication failures.
- Authorization failures.
- Missing resources.
- Workflow conflicts.
- Unexpected application exceptions.

### 4.1 Exception Handling Rules

- Do not expose raw exceptions to API clients.
- Do not expose stack traces in production responses.
- Do not expose SQL errors.
- Do not silently ignore failures.
- Preserve useful diagnostic context.
- Return the approved API error structure.
- Log unexpected failures appropriately.

### 4.2 Error Classification

Application exceptions must map to the appropriate HTTP status according to API_CONTRACTS.md.

The same error condition must not produce inconsistent classifications across endpoints.

The detailed exception-to-response mapping must be verified during API implementation.

---

## 5. API Error Response

The API error response structure, field names, and metadata conventions are defined in:

`docs/api/API_CONTRACTS.md`

This document defines error classification and handling behavior.

It must not introduce a separate API error format.

The following details remain UNDECIDED until finalized in the API design process:

- Error response field names.
- Error code naming.
- Validation error structure.
- Error metadata.
- Request correlation identifier.
- Error response localization.

Every endpoint must use the approved common error structure.

---

## 6. Logging

Unexpected failures must be logged with sufficient diagnostic context.

Logging must not expose:

- Passwords.
- Authentication tokens.
- Database credentials.
- Private secrets.
- Sensitive customer information beyond approved logging needs.

Logging configuration and tooling remain subject to the engineering and deployment implementation plan.

Do not assume a specific logging provider.

---

## 7. Frontend Error Handling

The React frontend must:

- Handle rejected API requests.
- Handle unexpected network failures.
- Display user-friendly error messages.
- Use API error responses according to API_CONTRACTS.md.
- Handle loading, success, empty, and error states.
- Avoid exposing internal server errors to users.
- Avoid silently swallowing failed requests.
- Preserve relevant form input when an operation fails, where appropriate.

Frontend validation must never replace backend validation.

The frontend must not independently determine whether a business operation is authorized.

---

## 8. Error Handling Verification

Tests must cover relevant error conditions.

Examples:

- Malformed request.
- Invalid field values.
- Missing authentication.
- Insufficient permissions.
- Missing resources.
- Invalid workflow transitions.
- Protected order update attempts.
- Unexpected server failures.

Tests must verify both:

- Correct HTTP status classification.
- Consistent API error response structure.

Do not create tests for unresolved business rules as mandatory behavior.

---

## 9. Related Documentation

- `docs/api/API_CONTRACTS.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`

---

## 10. Error Handling Governance

This document defines the error-handling baseline for Garment ERP Core Phase 1.

All backend and frontend error behavior must remain consistent with the approved API contracts.

No implementation may expose internal diagnostic information or silently bypass business validation.
