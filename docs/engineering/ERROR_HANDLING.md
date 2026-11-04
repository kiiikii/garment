# Error Handling

## HTTP Status Codes

The following status codes define the intended API error
classification. Their final usage conventions must remain
consistent with `docs/api/API_CONTRACTS.md`.

| Code | Meaning               | Use                                                             |
| ---- | --------------------- | --------------------------------------------------------------- |
| 400  | Bad Request           | Malformed JSON or structurally invalid request                  |
| 401  | Unauthorized          | Missing or invalid authentication credentials                   |
| 403  | Forbidden             | Authenticated user is not allowed to perform the operation      |
| 404  | Not Found             | Requested resource does not exist                               |
| 409  | Conflict              | Operation conflicts with the current resource state             |
| 422  | Unprocessable Content | Structurally valid request that fails approved validation rules |
| 500  | Internal Server Error | Unexpected server-side failure                                  |

The distinction between HTTP 400 and 422 must be finalized
in `API_CONTRACTS.md`.

Never expose internal implementation details to API clients,
including stack traces, SQL errors, file paths, or internal
diagnostic information.

## Validation

- The backend is authoritative for all business validation.
- Frontend validation is for user experience only.
- Business validation must follow approved Business Rules.
- Do not introduce validation rules based on assumptions.
- Validation requirements marked UNDECIDED must not be
  implemented as mandatory business constraints.

### Confirmed Constraints

- Production type must be supported by the Phase 1 scope:
  CMT or FOB.

### Unresolved Validation Requirements

- Customer name requiredness and exact validation behavior.
- Customer phone number format and validation rules.
- Quantity minimum and zero-value handling.
- Whether at least one product image is mandatory.
- Other field-specific validation requirements.

Unresolved requirements must be clarified in the relevant
business documentation before implementation.

## Go Error Handling Conventions

- Never ignore errors.
- Wrap errors with meaningful context.
- Use error wrapping with `%w` when the original error
  must remain available for inspection.
- Handle errors at the appropriate application boundary.
- Do not expose internal errors directly to API clients.
- Log unexpected failures with sufficient diagnostic context.
- Do not log secrets, credentials, authentication tokens,
  or other sensitive information.

Example:

`fmt.Errorf("create order: %w", err)`

Use one centralized structured-logging setup when introduced
in the relevant implementation sprint.

Until then, keep logging simple and consistent.

## API Error Response

The API error response structure, field names, and error
classification conventions are defined in
`docs/api/API_CONTRACTS.md`.

This document defines error-handling behavior and implementation
conventions. It must not introduce a separate API error format.

The response structure remains UNDECIDED until finalized
in the designated API design sprint.

## Frontend Error Handling

- Handle rejected API requests and unexpected network failures.
- Display user-friendly error messages.
- Use API error responses according to API_CONTRACTS.md.
- Do not expose internal server errors or technical details
  directly to users.
- Do not silently swallow failed requests.
- Preserve relevant form input when an operation fails,
  where appropriate.
- Frontend validation must never replace backend validation.
