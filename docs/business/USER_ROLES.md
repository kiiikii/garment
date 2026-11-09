# User Roles

**Status:** PARTIALLY CONFIRMED

PRD v1.0 defines four internal role identifiers for Phase 1:

- `OWNER`
- `ADMIN`
- `PPIC`
- `PRODUCTION_STAFF`

These identifiers are confirmed.

Responsibilities and access boundaries are defined below
only where supported by confirmed business decisions.

A role must not automatically receive every permission associated
with its general business responsibilities.

---

## 1. Confirmed Roles and Responsibilities

| Role | Responsibilities |
|---|---|
| OWNER | Management oversight, approval of Order Change Requests, approval of Payment Record Corrections, business monitoring, and access to order-status audit history. |
| ADMIN | Customer and Order administration, sampling and LoA administration, payment recording, and creation of Order Change Requests. |
| PPIC | Production Readiness and Waiting follow-up, Functional Layout preparation, production progress submission, Delay Report management, Production Issue Report follow-up, operational monitoring, and fulfillment confirmation. |
| PRODUCTION_STAFF | Views relevant in-progress work within their department and creates Production Issue Reports. |

Important distinctions:

1. Administrative responsibility does not automatically grant approval authority.
2. ADMIN may administer LoA-related information, but LoA approval authority remains UNDECIDED.
3. Sampling execution and Sample / Look Approval authority remain UNDECIDED.
4. PPIC confirms fulfillment completion, but detailed physical execution responsibilities remain UNDECIDED.
5. These responsibilities do not constitute a complete permission matrix.

## 2. Business Responsibility Areas

The following are operational areas, not additional system roles:

- Order intake / sales
- Customer administration
- Sampling
- Sample / Look Approval
- LoA administration and authorization
- Payment recording
- Payment correction approval
- PPIC
- Production
- Packing
- Delivery / Customer Pickup
- Management

One person may perform multiple business responsibilities.

Business positions and operational responsibilities do not
automatically require separate system roles.

Additional system roles must not be introduced without
explicit business confirmation.

## 3. Confirmed Access Boundaries

The following table records confirmed Phase 1 access boundaries.

| Feature | Access Boundary |
|---|---|
| Owner Dashboard | OWNER only. |
| Customer and Order Administration | ADMIN manages customer and order records. |
| Payment Recording | ADMIN records payments actually received. |
| Payment Record Correction | OWNER approves or rejects correction requests. |
| Order Change Request | ADMIN creates requests; OWNER approves or rejects them. |
| Production Readiness | PPIC evaluates readiness and manages Waiting follow-up. |
| Functional Layout | PPIC prepares the production layout. |
| Production Progress | PPIC submits percentage progress updates. PRODUCTION_STAFF cannot submit percentage progress. |
| Delay Report | PPIC creates and manages Delay Reports. |
| Production Issue Report | PRODUCTION_STAFF creates and views their own reports; PPIC manages all reports; OWNER has read-only access; ADMIN has no access. |
| Fulfillment | PPIC is responsible for confirming fulfillment completion. |
| Order Status Audit History | OWNER has read-only access. |
| Production Work Visibility | PRODUCTION_STAFF views relevant IN_PROGRESS orders within their department, subject to approved assignment and department rules. |

### 3.1 Production Issue Report Access

The confirmed access matrix is:

| Capability | OWNER | ADMIN | PPIC | PRODUCTION_STAFF |
|---|---|---|---|---|
| View all reports | Read-only | No | Yes | No |
| Create reports | No | No | No | Yes |
| Manage all reports | No | No | Yes | No |
| View own reports | No | No | All reports | Yes |
| Update report status | No | No | Yes | No |
| Resolve reports | No | No | Yes | No |

Additional constraints:

1. OWNER has read-only access to all Production Issue Reports.
2. ADMIN has no Production Issue Report access in Phase 1.
3. PPIC can view and manage all Production Issue Reports.
4. PRODUCTION_STAFF can only view reports submitted by themselves.
5. PPIC must provide a resolution note before setting a report to RESOLVED.
6. Production Staff cannot manage report status or resolution.

### 3.2 Production Staff Work Visibility

Production Staff may access:

- Orders currently IN_PROGRESS within the relevant department.
- Relevant product details.
- Relevant Functional Layout information.
- Target completion information.
- Work instructions.
- Production Issue Report creation.
- Their own Production Issue Reports.

Access is subject to approved order assignment and department rules.

Staff assignment and department-level visibility details remain UNDECIDED.

## 4. Unconfirmed Decisions

The following permissions and responsibilities must remain unresolved
until explicitly approved.

| Item | Status |
|---|---|
| Complete permission matrix | UNDECIDED |
| Department-level data visibility rules | UNDECIDED |
| Sampling execution responsibility | UNDECIDED |
| Sample / Look Approval authority | UNDECIDED |
| LoA Approval authority | UNDECIDED |
| Order Hold / Resume authority | UNDECIDED |
| Future target and actual production quantity recording permissions | UNDECIDED |
| Detailed Packing and Delivery execution permissions | UNDECIDED |
| Detailed Packing and Delivery confirmation permissions | UNDECIDED |
| Additional notification recipients and access | UNDECIDED |
| Audit log access beyond confirmed boundaries | UNDECIDED |
| Production Staff assignment rules | UNDECIDED |
| Additional role responsibilities | UNDECIDED |

Unconfirmed permissions must not be implemented based on assumptions.

## 5. Authorization Principles

1. Authentication identifies the user; authorization determines permitted actions.
2. Authorization must be enforced by the Laravel backend for every protected operation.
3. Frontend visibility and filtering are not security boundaries.
4. Data access must follow confirmed role and department restrictions.
5. Job titles and business responsibilities do not automatically grant permissions.
6. Unauthorized operations must be rejected, including direct REST API requests.
7. Role-based access must be consistent across application endpoints.
8. Backend authorization must remain authoritative even when frontend controls are bypassed.
9. New roles and permissions require business confirmation.
10. Unconfirmed permissions must not be granted by default.

## 6. Authentication, RBAC, and Audit Logging

These are separate system concerns:

1. **Authentication:** verifies the identity of the user.
2. **RBAC:** controls access based on assigned roles and confirmed permissions.
3. **Audit Logging:** records relevant actions, including the actor and timestamp.

Authentication does not imply authorization.

Audit logging does not automatically grant access to recorded activities.

The active backend implementation uses Laravel / PHP.

Laravel backend authorization is responsible for enforcing
the confirmed access boundaries.

The React frontend must not be treated as an authorization authority.

Implementation sequencing is defined by the engineering roadmap.

## 7. Expected HTTP Behavior

| Condition | HTTP Status |
|---|---|
| User is not authenticated | `401 Unauthorized` |
| User is authenticated but lacks permission | `403 Forbidden` |

Protected API operations must consistently enforce these responses.

Detailed error response structures and conventions
are defined in:

`docs/api/API_CONTRACTS.md`

## 8. Documentation Governance

1. Role identifiers must remain consistent across PRD, USER_ROLES, API contracts, and implementation.
2. Permission changes must be supported by approved business decisions.
3. Changes affecting workflow authorization must also be synchronized with BUSINESS_WORKFLOW.md.
4. Business constraints and validation requirements remain governed by BUSINESS_RULES.md.
5. Persona descriptions must not independently establish role permissions.
6. Any change to the role structure must be documented and approved before implementation.
7. Unresolved permissions must retain their UNDECIDED status.