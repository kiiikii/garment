# Feature Specifications (Phase 1 MVP)

Status: Draft

Phase 1 focuses on internal production tracking and monitoring.

Detailed business rules are defined in:

- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`

Acceptance criteria are defined in `ACCEPTANCE_CRITERIA.md`.

Sprint mapping is defined in `RELEASE_PLAN.md`.
Sprint assignments remain PROPOSED until formally confirmed.

## Technical Foundation: Health Check

**Description:**
A minimal backend capability used to verify that the API server is running.

**Request flow:**
Client request -> Router -> Handler -> JSON response.

This is a technical foundation, not a business-facing product feature.

## Feature 1: Order Intake

**Description:**
Allow authorized internal users to create, view, and list customer orders.

Order information includes:

- Customer name
- Customer phone number
- Customer origin / address
- Product reference images (minimum image requirement: UNDECIDED).
- Size and quantity distribution
- Production type: CMT or FOB

**User flow:**
Create order -> Enter customer information -> Add size quantities -> Attach reference images -> Save order -> View order -> List orders.

**Open decisions:**

- Total quantity storage and calculation strategy: UNDECIDED.
- Image storage strategy: UNDECIDED.
- Field-specific validation rules: Refer to approved Business Rules.

## Feature 2: Sampling

**Description:**
Track the sampling process and its planned duration.

Confirmed internal duration:

- Pattern: 2 days.
- Sample: 2 days.
- Total: 4 days.

Customer-facing target: 7 working days.

**User flow:**
Start sampling -> Pattern -> Sample -> Complete sampling.

## Feature 3: Sample / Look Approval

**Description:**
Track customer sample/look approval decisions before proceeding to the next business stage.

Approval states:

- PENDING
- REVISION
- APPROVED
- REJECTED

**User flow:**
PENDING -> APPROVED / REVISION / REJECTED.

Detailed transition behavior and the consequences of each decision must follow approved Business Rules.

## Feature 4: Letter of Agreement (LoA)

**Description:**
Track the Letter of Agreement as a commercial approval gate before production readiness.

Approval states:

- PENDING
- REVISION
- APPROVED
- REJECTED

**User flow:**
Create / administer LoA -> Review -> APPROVED / REVISION / REJECTED.

Admin handles LoA administration. 
The role authorized to approve or reject an LoA remains UNKNOWN.

An unapproved LoA must not be treated as an approved production gate.

Detailed payment requirements and rejection behavior follow approved Business Rules.

## Feature 5: Production Readiness and Waiting

**Description:**
Evaluate whether an order is ready to proceed to PPIC preparation.

Production readiness is separate from sample/look approval and LoA approval.

An order that is not ready may enter Waiting / On Hold with a recorded reason.

**User flow:**
Evaluate readiness -> Ready or Waiting(reason) -> Re-evaluate readiness -> Resume / Release when eligible.

**Open decisions:**

- Readiness checklist: UNKNOWN.
- Waiting reason list: UNDECIDED.
- Resume and release conditions: UNDECIDED.

## Feature 6: PPIC Preparation and Functional Layout

**Description:**
Allow PPIC to prepare an order for production and organize its functional production layout.

The layout represents production organization, such as machine and work-function areas.

Layout is a preparation artifact, not a separate business workflow stage.

**User flow:**
Ready order -> PPIC preparation -> Functional layout -> Production release.

Detailed layout data and approval requirements: UNDECIDED.

## Feature 7: Mass Production and Fulfillment

**Description:**
Track mass production from production release through fulfillment.

Confirmed production sequence:

1. Cutting
2. Sewing
3. QC
4. QC decision:
   - PASS -> Finishing
   - FAIL -> Rework -> QC again
5. Packing
6. Delivery or Customer Pickup
7. Completed

Confirmed planned internal durations:
- Cutting: 2 days.
- Sewing: 5 days.
- Finishing: 2 days.
- Total Cutting-to-Finishing duration: 9 days.

Customer-facing target: 10–14 working days.

QC and Rework are operationally monitored but do not use
percentage-based progress reporting in Phase 1.

**Open decisions:**

- QC measurement and recording: UNDECIDED.
- Rework duration and measurement: UNDECIDED.
- Packing duration: UNKNOWN.
- Delivery / Customer Pickup evidence requirements: UNDECIDED.
- Detailed fulfillment transitions: UNDECIDED.

## Feature 8: Production Monitoring

**Description:**
Provide visibility into planned versus actual production timelines, reported stage progress, and production delays.

Confirmed progress reporting:

- PPIC submits progress updates.
- Cutting, Sewing, and Finishing support percentage-based progress.
- Progress history must be retained.

Monitoring capabilities:

- Planned versus actual timeline.
- Stage-level progress.
- Duration variance.
- Current production stage.
- Delay visibility.

**Open decisions:**

- Overall order progress calculation: UNDECIDED.
- Stage weighting: UNDECIDED.
- QC and Rework measurement: UNDECIDED.
- Bottleneck calculation: UNDECIDED.
- Reject and rework metrics: UNDECIDED.

QC and Rework are monitored through operational statuses.
They do not use percentage-based progress reporting in Phase 1.

## Feature 9: Notifications

**Description:**

Provide Phase 1 In-App Notifications for confirmed Production Issue Report events.

Confirmed notification behavior:
- Notify PPIC when a new Production Issue Report is created.
- Notify the reporting Production Staff when the report status changes.

Delay Report notifications use a separate email mechanism and are not part of the Phase 1 In-App Notification scope.

Sampling notifications intended for Owner are a candidate requirement only if explicitly enabled and approved.

**Open decisions:**
- Additional notification events: UNDECIDED.
- Additional notification recipients: UNDECIDED.
- Sampling notification activation and configuration: UNDECIDED.
- Additional notification rules and deduplication behavior: UNDECIDED.

Scheduled deadline warnings, including DEADLINE_WARNING, are not confirmed Phase 1 requirements.

## Feature 10: Authentication, RBAC and Audit Logging

**Description:**
Provide secure internal user authentication, role-based authorization, and audit history.

Confirmed Phase 1 roles:

- OWNER
- ADMIN
- PPIC
- PRODUCTION_STAFF

Customers do not have login access in Phase 1.

**User flow:**
Login -> Authenticated request -> Authorization check -> Authorized action -> Audit entry.

The backend is authoritative for authentication and authorization enforcement.

Passwords must be stored using secure password hashing.

**Open decisions:**

- Authentication mechanism and token/session strategy: UNDECIDED.
- Password hashing algorithm: UNDECIDED.
- Detailed permission matrix: Refer to USER_ROLES.md.

Owner has confirmed read-only access to order status audit history.

Access to the complete application audit log remains subject to the approved audit and permission requirements.

## Feature 11: Web Client and Dashboards

**Description:**
Provide an internal React SPA that consumes the backend REST API.

The web client supports:

- Order creation and order listing.
- Customer and order information.
- Multiple product reference images.
- Size and quantity input.
- Sampling tracking.
- Sample/look approval.
- LoA tracking.
- Production readiness and waiting visibility.
- PPIC preparation and functional layout.
- Production timeline.
- Production progress monitoring.
- Notifications.
- Role-based dashboards.

**User flow:**
Login -> Access authorized dashboard -> Create or view orders -> Track business workflow -> Monitor production progress.

The frontend must not replace backend validation, business rules, or authorization.
