# User Stories

Status: Draft

Phase 1 user stories describe the needs of internal users interacting
with the Garment ERP system.

Personas and role definitions are maintained in:
`docs/business/USER_PERSONAS.md`
`docs/business/USER_ROLES.md`

Format:

As a [role/persona], I want [capability], so that [expected outcome].

Stories must follow approved business rules and must not introduce unconfirmed business requirements.

## 1. Order Intake

### US-001: Create an Order

As an ADMIN, I want to create a customer order, so that the order can be registered and tracked in the system.

### US-002: Record Customer Information

As an ADMIN, I want to record customer name, phone number, and origin/address, so that customer information is available for order management.

### US-003: Record Product References

As an ADMIN, I want to attach available product reference images to an order, so that the intended product model can be identified.

### US-004: Record Size Quantities

As an ADMIN, I want to record order quantities by size, so that the production team can understand the required quantity distribution.

### US-005: Select Production Type

As an ADMIN, I want to select CMT or FOB as the production type, so that the order follows the appropriate production process.

## 2. Sampling and Approval

### US-006: Track Sampling Progress

As an authorized internal user, I want to track Pattern and Sample progress, so that sampling progress and duration are visible.

The responsible role and detailed permissions remain UNDECIDED.

### US-007: Record Sample / Look Approval

As an authorized internal user, I want to record the sample/look approval decision, so that the order can proceed according to its approval state.

The authorized role and detailed permissions must follow USER_ROLES.md.

## 3. Letter of Agreement

### US-008: Track LoA Status

As an authorized internal user, I want to track the Letter of Agreement status, so that commercial approval can be monitored before production readiness.

### US-009: Record LoA Decision

As an authorized internal user, I want to record an LoA review decision, so that the order follows the approved commercial process.

Admin handles LoA administration.
The role authorized to approve or reject an LoA remains UNKNOWN.

## 4. Production Readiness and Waiting

### US-010: Evaluate Production Readiness

As a PPIC, I want to review whether an order is ready for production preparation, so that production is not released before its readiness requirements are met.

### US-011: Place an Order on Hold

As an authorized internal user, I want to place an order in Waiting / On Hold with a recorded reason, so that production blockers are visible and traceable.

### US-012: Resume a Waiting Order

As an authorized internal user, I want to resume an order when its readiness conditions are satisfied, so that the order can continue through the production workflow.

Detailed permissions and resume conditions remain subject to approved business rules.

## 5. PPIC Preparation and Layout

### US-013: Prepare Production

As a PPIC, I want to prepare an order for production, so that production requirements are organized before mass production begins.

### US-014: Prepare Functional Layout

As a PPIC, I want to prepare a functional production layout, so that production work areas and functions can be organized.

## 6. Mass Production

### US-015: Track Production Stages

As a PPIC, I want to track Cutting, Sewing, QC, Rework, Finishing, and subsequent fulfillment stages, so that the production process is visible.

Percentage-based progress applies only to Cutting, Sewing, and Finishing in Phase 1.

QC and Rework are monitored through operational statuses.

### US-016: Record Production Progress

As a PPIC, I want to submit progress percentages for supported production stages, so that production progress can be monitored.

Supported percentage-based stages in Phase 1:

- Cutting
- Sewing
- Finishing

### US-017: Track QC and Rework

As a PPIC, I want to track QC results and the Rework process, so that failed quality checks can be followed up before production continues.

Detailed QC and Rework recording requirements remain UNDECIDED.

### US-018: Track Order Fulfillment

As an authorized internal user, I want to record Packing and Delivery / Customer Pickup progress, so that order fulfillment can be monitored through completion.

The responsible roles and detailed permissions remain UNDECIDED.

## 7. Production Monitoring

### US-019: View Production Timeline

As an OWNER, I want to view planned and actual production timelines, so that I can identify delays and monitor order progress.

### US-020: Monitor Production Progress

As an OWNER, I want to view production progress by order and stage, so that I can understand the current production situation.

### US-021: Identify Production Delays

As an OWNER, I want to identify orders or production stages that are delayed, so that operational issues can be addressed.

## 8. Notifications

### US-022: Receive Relevant Notifications

As an internal user, I want to receive notifications relevant to my responsibilities, so that I can respond to important production events.

Notification events and recipients must follow approved notification rules.

## 9. Authentication and Authorization

### US-023: Authenticate an Internal User

As an internal user, I want to log in securely, so that I can access the system using my account.

### US-024: Access Role-Based Features

As an internal user, I want to access only the features permitted by my role, so that system operations follow the defined authorization rules.

### US-025: View Order Status Audit History

As an OWNER, I want to view order status audit history, so that important order status changes can be traced.

The audit history must be maintained by the system. Owner access is read-only.

## 10. Dashboard

### US-026: View Operational Dashboard

As an OWNER, I want to view an overview of orders and production progress, so that I can monitor overall operations.

### US-027: View Assigned Production Information

As a PPIC, I want to view production orders and their progress that are available to my role, so that I can coordinate production activities.

Dashboard visibility must follow approved role permissions.
