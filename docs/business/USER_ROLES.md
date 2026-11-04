# User Roles

Status: PARTIALLY CONFIRMED

The PRD v0.4 identifies the following internal role identifiers
for Phase 1:

- `OWNER`
- `ADMIN`
- `PPIC`
- `PRODUCTION_STAFF`

These role identifiers are confirmed for Phase 1.

Detailed responsibilities, access boundaries, and permissions
must be validated against actual business operations.

Do not invent additional roles or permissions.

## 1. Confirmed Role Identifiers

| Role             | General Responsibility                                                                                                                                               |
| ---------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| OWNER            | Management visibility, business oversight, and approval responsibilities explicitly assigned by approved business rules.                                             |
| ADMIN            | Customer and order administration, LoA administration, and payment recording.                                                                                        |
| PPIC             | Production readiness, Waiting / On Hold follow-up, Functional Layout preparation, production progress updates, Delay Reports, and Production Issue Report follow-up. |
| PRODUCTION_STAFF | Views relevant departmental work and submits Production Issue Reports.                                                                                               |

These descriptions summarize confirmed Phase 1 responsibilities.

They are not a complete permission matrix.

A responsibility does not automatically grant permission
to perform every action related to that responsibility.

## 2. Candidate Responsibility Areas

The following are business responsibility areas,
not additional confirmed system roles.

- Order intake / sales
- Sampling
- Sample / Look Approval
- LoA administration and authorization
- PPIC
- Production
- Packing
- Delivery / Customer Pickup
- Management

One person may perform multiple business responsibilities.

A responsibility area does not automatically require
a separate system role.

A business position or job title does not automatically
correspond to a system role.

## 3. Confirmed Access Boundaries

The following boundaries are explicitly established
in PRD v0.4.

| Feature                           | Confirmed Access Boundary                                                                                                        |
| --------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| Customer and Order Administration | Admin manages customer and order records.                                                                                        |
| Order Change Request              | Admin creates requests; Owner approves or rejects them.                                                                          |
| Payment Recording                 | Admin records payments actually received.                                                                                        |
| Payment Correction                | Owner approves or rejects payment-record corrections.                                                                            |
| Production Readiness              | PPIC evaluates and manages readiness follow-up.                                                                                  |
| Functional Layout                 | PPIC prepares the layout.                                                                                                        |
| Production Progress               | PPIC submits production progress updates.                                                                                        |
| Delay Report                      | PPIC creates and manages Delay Reports.                                                                                          |
| Production Issue Report           | Production Staff creates and views their own reports; PPIC manages all reports; Owner has read-only access; Admin has no access. |
| Order Status Audit History        | Owner has read-only access.                                                                                                      |
| Production Staff Work Visibility  | Staff can view relevant orders currently in progress within their department.                                                    |

These boundaries must not be interpreted as a complete
authorization specification.

## 4. Open Decisions

| Item                                                    | Status              |
| ------------------------------------------------------- | ------------------- |
| Phase 1 role identifiers                                | CONFIRMED           |
| General responsibilities per role                       | PARTIALLY CONFIRMED |
| Complete permission matrix                              | UNDECIDED           |
| Data visibility and department-level restrictions       | UNDECIDED           |
| Who may approve Sample / Look                           | UNKNOWN             |
| Who may approve LoA                                     | UNKNOWN             |
| Who may put an order on hold / resume it                | UNDECIDED           |
| Who may record target / actual quantities               | UNDECIDED           |
| Who confirms Packing completion                         | UNDECIDED           |
| Who confirms Delivery / Customer Pickup                 | UNDECIDED           |
| Additional notification recipients and permissions      | UNDECIDED           |
| Audit log access beyond explicitly confirmed boundaries | UNDECIDED           |

## 5. Authorization Principles

- Authentication identifies who the user is.
- Authorization determines what the authenticated user may do.
- All authorization checks must be enforced by the backend.
- Frontend visibility is not an authorization mechanism.
- Permissions must follow confirmed business responsibilities.
- A user must not gain permissions merely because their job title appears related to a feature.
- Data visibility restrictions must be enforced by the backend, not only by frontend filtering.
- Unauthorized actions must be rejected even when called directly through the REST API.
- Do not introduce additional roles or permissions without business confirmation.

## 6. Authentication, RBAC, and Audit Logging

Authentication, RBAC, and audit logging are separate concerns.

Their implementation sequence is defined by the engineering roadmap.

1. **Authentication:** Identify and authenticate the user.
2. **RBAC:** Authorize actions based on confirmed roles and permissions.
3. **Audit Logging:** Record relevant actions, including who performed them and when.

Authentication does not imply authorization.

Audit logging records relevant activities but does not automatically
grant users permission to read all audit records.

## 7. Expected HTTP Behavior

| Condition                                | HTTP Status        |
| ---------------------------------------- | ------------------ |
| User is not authenticated                | `401 Unauthorized` |
| User is authenticated but not authorized | `403 Forbidden`    |

The backend must consistently enforce these responses
for protected API operations.

Detailed error response structures and conventions
are defined in `docs/api/API_CONTRACTS.md`.
