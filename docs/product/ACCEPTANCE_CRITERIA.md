# Acceptance Criteria

Status: Draft

Acceptance criteria define explicit, verifiable conditions for Phase 1 features.

Detailed criteria may be refined when implementation begins, but refinement
must follow approved business requirements and must not introduce
unconfirmed business rules.

Unresolved requirements must remain UNDECIDED or UNKNOWN until formally defined.

## Feature 0: Health Check

- With the server running, a GET request to the health endpoint returns HTTP 200 with a JSON body.
- The server shuts down gracefully on interrupt.

## Feature 1: Order Intake

- Order creation validates customer information according to approved Business Rules.
- Size quantities are validated according to approved Business Rules.
- Invalid production type is rejected.
- An order supports size-based quantity records.
- Product reference images can be associated with an order.
- Minimum product reference image requirement: UNDECIDED.
- Zero quantity behavior for individual sizes: UNDECIDED.
- Get and list operations return the persisted order data.
- Total quantity behavior (stored / calculated / both): UNDECIDED.
- Image storage strategy: UNDECIDED.

## Feature 2: Sampling

- Planned start and end values are recorded separately from actual start and end values.
- Internal sampling duration and customer-facing timeline are distinct values.
- Sampling delay can be derived by comparing planned and actual dates.
- Customer-facing sampling timeline calculations follow the applicable working-day rules.
- Internal sampling duration uses the confirmed Pattern and Sample duration references.

## Feature 3: Sample / Look Approval

- Valid state transitions succeed according to the approved transition table.
- Invalid state transitions are rejected.
- State transition conflicts use the API error classification defined in API_CONTRACTS.md.
- The transition table must be approved before implementation.

## Feature 4: LoA

- An order cannot pass the production gate while its LoA is not APPROVED.
- LoA administration and LoA approval authorization must follow the approved role permissions.
- Admin LoA administration does not automatically grant LoA approval authority.
- LoA REVISION and REJECTED behavior follows the approved business workflow.
- LoA approval and payment requirements follow approved Business Rules.

## Feature 5: Production Readiness and Waiting

- Production readiness is evaluated separately from Sample / Look Approval and LoA approval.
- A Waiting / On Hold record must retain its recorded reason.
- Waiting reason definitions and configuration behavior: UNDECIDED.
- Waiting duration can be measured.
- Readiness checklist: UNKNOWN.
- Resume and release conditions: UNDECIDED.

## Feature 6: PPIC and Layout

- Functional layout zones and workflow steps are modeled as separate concepts.
- Production release requires PPIC preparation to be completed.
- Preparation completion requirements: UNDECIDED.
- Layout approval requirements: UNDECIDED.

## Feature 7: Mass Production and Fulfillment

- Production follows the confirmed operational sequence:
  Cutting -> Sewing -> QC.
- QC PASS allows the order to proceed to Finishing.
- QC FAIL requires Rework followed by another QC check.
- Finishing follows a successful QC result.
- Packing follows Finishing.
- Fulfillment proceeds through Delivery or Customer Pickup.
- Planned and actual timeline values are recorded separately
  for stages with defined timeline requirements.
- Current production stage and operational state are visible.
- Percentage-based progress tracking applies only to:
  - Cutting
  - Sewing
  - Finishing
- QC and Rework are monitored through operational statuses.
- QC and Rework duration measurement: UNDECIDED.
- Packing duration: UNKNOWN.
- Fulfillment evidence requirements: UNDECIDED.

## Feature 8: Production Monitoring

- Planned and actual timeline values are recorded separately
  for supported timeline records.
- Reported stage progress is based on PPIC-submitted
  progress records.
- Percentage-based progress applies only to Cutting,
  Sewing, and Finishing.
- Progress history is retained as historical records.
- Duration variance is derived from authoritative
  planned and actual timeline values.
- Derived monitoring values must not replace
  authoritative source records.
- Current production stage and production bottleneck
  are treated as separate concepts.
- QC and Rework are monitored through operational statuses.

**Open decisions:**
- Overall order progress calculation: UNDECIDED.
- Stage weighting: UNDECIDED.
- Planned progress schedule: UNDECIDED.
- Bottleneck calculation: UNDECIDED.
- Actual-piece production quantity tracking: NOT CONFIRMED FOR PHASE 1.
- Reject and rework quantity tracking: NOT CONFIRMED FOR PHASE 1.

## Feature 9: Notifications

Confirmed Phase 1 In-App Notification criteria:

- A new Production Issue Report triggers an In-App Notification for PPIC.
- A Production Issue Report status change triggers an In-App Notification for the reporting Production Staff.
- Notification recipients follow the confirmed role and notification rules.
- Notification records must be associated with their triggering business events.

Additional notification requirements:

- Delay Report notifications use a separate email mechanism and are not part of the Phase 1 In-App Notification scope.
- Sampling notifications intended for Owner are not confirmed Phase 1 requirements.
- Scheduled DEADLINE_WARNING notifications are not confirmed Phase 1 requirements.

**Open decisions:**
- Additional notification events: UNDECIDED.
- Additional notification recipients: UNDECIDED.
- Deduplication behavior: UNDECIDED.
- Notification delivery and retry behavior: UNDECIDED.

## Feature 10: Authentication, RBAC and Audit Log

- Unauthenticated requests to protected endpoints receive HTTP 401.
- Authenticated users without the required permission receive HTTP 403.
- Protected business actions must produce audit records according to approved audit rules.
- Order status changes must be traceable through order status audit history.
- Owner has read-only access to order status audit history.
- Access to the complete application audit log follows separately approved permissions.
- Backend authorization is enforced independently of frontend visibility.

## Feature 11: Web Client

- The web client consumes business data through the backend API.
- User actions submit requests through the API.
- The UI displays loading, success, empty, and error states where applicable.
- Backend validation and authorization remain authoritative.
- The UI does not independently determine or override business workflow transitions.
