# User Stories — Garment ERP Core

Status: Draft
Scope: Phase 1 MVP

## 1. Purpose

This document defines user stories for internal users interacting with Garment ERP Core.

User stories describe user needs and expected outcomes.

They must follow approved requirements documented in:

- `docs/business/PRD.md`
- `docs/business/USER_PERSONAS.md`
- `docs/business/USER_ROLES.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/GLOSSARY.md`

Technical implementation details are defined in the engineering documentation.

Acceptance conditions are defined in `ACCEPTANCE_CRITERIA.md`.

## 2. User Story Format

All user stories follow this format:

As a [role/persona],
I want [capability],
so that [expected outcome].

Stories must:

1. Represent an actual Phase 1 business requirement.
2. Follow approved role responsibilities.
3. Respect backend authorization.
4. Follow approved business workflow and validation rules.
5. Avoid introducing unconfirmed business requirements.
6. Preserve UNKNOWN and UNDECIDED requirements until formally defined.

## 3. User Roles

The confirmed Phase 1 internal roles are:

- OWNER
- ADMIN
- PPIC
- PRODUCTION_STAFF

Customers do not have login accounts in Phase 1.

Where the responsible role or permission has not been confirmed, the story must retain an explicit unresolved designation.

---

# 4. Order Intake and Customer Management

## US-001: Create an Order

As an ADMIN,
I want to create a customer order,
so that the order can be registered and tracked in the system.

## US-002: Record Customer Information

As an ADMIN,
I want to record customer name, phone number, and origin/address,
so that customer information is available for order management.

## US-003: Attach Product Reference Images

As an ADMIN,
I want to attach available product reference images to an order,
so that the intended product model can be identified.

Minimum image requirement and storage strategy remain UNDECIDED.

## US-004: Record Size Quantities

As an ADMIN,
I want to record order quantities by size,
so that the production team can understand the required quantity distribution.

## US-005: Select Production Type

As an ADMIN,
I want to select CMT or FOB as the production type,
so that the order follows the appropriate production process.

Only CMT and FOB are supported in Phase 1.

---

# 5. Sampling and Sample / Look Approval

## US-006: Track Sampling Progress

As an authorized internal user,
I want to track Pattern Preparation and Sample Production progress,
so that sampling activities and their durations are visible.

Confirmed internal duration references:

- Pattern Preparation: 2 days.
- Sample Production: 2 days.
- Internal total: 4 days.
- Customer-facing target: 7 working days.

The responsible role and detailed permissions remain UNDECIDED.

## US-007: Record Sample / Look Approval

As an authorized internal user,
I want to record the Sample / Look Approval decision,
so that the order can proceed according to its approval state.

Supported approval states:

- PENDING
- REVISION
- APPROVED
- REJECTED

The authorized role and detailed transition permissions must follow approved USER_ROLES.md and BUSINESS_RULES.md.

---

# 6. Letter of Agreement and Payment

## US-008: Administer Letter of Agreement

As an ADMIN,
I want to create and administer a Letter of Agreement,
so that commercial agreement information can be recorded and monitored.

Admin handles LoA administration.

## US-009: Record LoA Review Decision

As an authorized internal user,
I want to record an LoA review decision,
so that the order follows the approved commercial process.

Supported states:

- PENDING
- REVISION
- APPROVED
- REJECTED

The role authorized to approve or reject an LoA remains UNKNOWN.

An unapproved LoA must not be treated as an approved production gate.

## US-010: Record Received Payments

As an ADMIN,
I want to record DP and subsequent payments actually received,
so that payment information is available for monitoring.

Payment records must represent received amounts, not expected payments.

## US-011: Monitor Payment Information

As an OWNER,
I want to view recorded payment information,
so that I can monitor payments associated with customer orders.

Payment recording does not represent full accounting or financial reconciliation.

## US-012: Review Payment Corrections

As an OWNER,
I want to approve or reject payment correction requests,
so that payment records remain controlled and traceable.

Original payment information and correction history must remain traceable.

The role authorized to submit payment correction requests and detailed correction transitions remain UNDECIDED.

---

# 7. Production Readiness and Waiting / On Hold

## US-013: Evaluate Production Readiness

As a PPIC,
I want to evaluate whether an order satisfies its applicable Production Readiness requirements,
so that production preparation does not proceed before the order is ready.

Production Readiness is evaluated separately from Sample / Look Approval and LoA approval.

The readiness checklist remains UNKNOWN.

## US-014: Place an Order on Waiting / On Hold

As a PPIC,
I want to place an order in Waiting / On Hold with a recorded reason,
so that production blockers are visible and traceable.

Waiting / On Hold is conditional and is not mandatory for every order.

Waiting reason definitions remain UNDECIDED.

## US-015: Re-evaluate a Waiting Order

As a PPIC,
I want to re-evaluate an order in Waiting / On Hold,
so that I can determine whether its applicable readiness conditions have been satisfied.

## US-016: Resume a Waiting Order

As a PPIC,
I want to resume a waiting order when its applicable readiness conditions are satisfied,
so that the order can continue through the production workflow.

Detailed resume and release conditions remain subject to approved Business Rules.

---

# 8. PPIC Preparation and Functional Layout

## US-017: Prepare Production

As a PPIC,
I want to prepare an order for production after Production Readiness is satisfied,
so that production requirements are organized before mass production begins.

## US-018: Prepare Functional Layout

As a PPIC,
I want to prepare a functional production layout,
so that production work areas and functions can be organized.

Functional Layout represents the physical arrangement of production work areas or functional zones.

It is not a separate production workflow stage.

Detailed layout data and approval requirements remain UNDECIDED.

---

# 9. Mass Production and Fulfillment

## US-019: Track Production Stages

As a PPIC,
I want to track Cutting, Sewing, QC, Rework, Finishing, Packing, and fulfillment stages,
so that the production process is visible.

Confirmed production sequence:

1. Cutting.
2. Sewing.
3. QC.
4. QC decision:
   - PASS: proceed to Finishing.
   - FAIL: proceed to Rework and undergo QC again.
5. Finishing.
6. Packing.
7. Delivery or Customer Pickup.
8. Fulfillment Confirmed.
9. Completed.

## US-020: Record Production Progress

As a PPIC,
I want to submit progress percentages for supported production stages,
so that production progress can be monitored.

Percentage-based progress reporting applies only to:

- Cutting.
- Sewing.
- Finishing.

Progress history must be retained.

## US-021: Track QC and Rework

As a PPIC,
I want to track QC results and the Rework process,
so that failed quality checks can be followed up before production continues.

QC and Rework are monitored through operational statuses.

They do not use percentage-based progress reporting in Phase 1.

Detailed QC and Rework recording requirements remain UNDECIDED.

## US-022: Track Order Fulfillment

As an authorized internal user,
I want to record Packing and Delivery / Customer Pickup progress,
so that order fulfillment can be monitored through completion.

The responsible roles and detailed permissions remain UNDECIDED.

## US-023: Confirm Fulfillment Completion

As a PPIC,
I want to confirm that the required fulfillment completion conditions have been met,
so that an order can be marked COMPLETED only after production and fulfillment requirements are satisfied.

Finishing completion alone does not mean the order is completed.

PPIC confirms the required fulfillment completion condition before the order becomes COMPLETED.

Detailed fulfillment evidence requirements remain UNDECIDED.

---

# 10. Production Monitoring and Delay Management

## US-024: View Production Timeline

As an OWNER,
I want to view planned and actual production timelines,
so that I can identify delays and monitor order progress.

Planned and actual timeline values must remain distinguishable.

## US-025: Monitor Production Progress

As an OWNER,
I want to view production progress by order and stage,
so that I can understand the current production situation.

Progress visibility must be based on authoritative PPIC-submitted progress records.

Overall order progress calculation and stage weighting remain UNDECIDED.

## US-026: Identify Production Delays

As an OWNER,
I want to identify orders or production stages that are delayed,
so that operational issues can be addressed.

Delay calculations must follow approved business rules.

A Delay Report does not automatically change the original planned completion date or estimated completion date.

## US-027: Create and Manage Delay Reports

As a PPIC,
I want to create and manage Delay Reports,
so that production delays can be formally documented and communicated.

Confirmed capabilities:

- Record the delay reason.
- Record estimated additional duration in working days.
- Maintain OPEN and RESOLVED statuses.
- Preserve the original planned completion date.
- Trigger the applicable Delay Report email mechanism.

Creating or resolving a Delay Report does not automatically update estimated completion.

Delay Report and Production Issue Report are separate business records.

---

# 11. Production Issue Reports

## US-028: Submit a Production Issue Report

As a PRODUCTION_STAFF,
I want to submit a Production Issue Report containing the related order, department or production stage, issue type, and description,
so that operational problems can be reported and followed up.

Report information includes:

- Related order.
- Department or production stage.
- Issue type.
- Description.
- Automatically recorded timestamp.
- Optional photo.

Issue categories remain UNDECIDED.

## US-029: Receive New Production Issue Reports

As a PPIC,
I want to receive an In-App Notification when a new Production Issue Report is submitted,
so that I can follow up on operational problems.

## US-030: Follow Up on Production Issue Reports

As a PPIC,
I want to review and update Production Issue Reports,
so that operational issues can be followed up until resolution.

Supported statuses:

- OPEN
- IN_PROGRESS
- RESOLVED

PPIC must provide a resolution note before setting a report to RESOLVED.

Detailed status transition rules remain UNDECIDED.

## US-031: View Production Issue Report Updates

As a PRODUCTION_STAFF,
I want to view the status and resolution information of my submitted issue reports,
so that I can follow up on reported operational problems.

Production Staff can only view their own reports.

## US-032: Receive Production Issue Report Updates

As a PRODUCTION_STAFF,
I want to receive an In-App Notification when the status of my submitted Production Issue Report changes,
so that I know when action has been taken on my report.

## US-033: Monitor Production Issue Reports

As an OWNER,
I want to view all Production Issue Reports in read-only mode,
so that I can monitor operational issues without modifying their records.

Owner access must remain read-only.

Admin cannot access Production Issue Reports.

A Production Issue Report does not automatically create a Delay Report or establish that an order is delayed.

---

# 12. Notifications

## US-034: Receive Relevant In-App Notifications

As an internal user,
I want to receive In-App Notifications relevant to my confirmed responsibilities,
so that I can respond to important production events.

Confirmed Phase 1 notification events:

| Business Event                        | Recipient                  |
| ------------------------------------- | -------------------------- |
| New Production Issue Report           | PPIC                       |
| Production Issue Report status change | Reporting Production Staff |

Additional notification events and recipients remain UNDECIDED.

Delay Report notifications use a separate email mechanism and are not part of the Phase 1 In-App Notification scope.

Sampling notifications intended for Owner are not confirmed Phase 1 requirements.

Scheduled DEADLINE_WARNING notifications are not confirmed Phase 1 requirements.

## US-035: Manage Notification Read State

As an internal user receiving notifications,
I want to manage the read state of my notifications,
so that I can distinguish notifications that have already been reviewed from unread notifications.

Notification read state is maintained per user.

Duplicate notifications for the same business event and recipient must be prevented.

---

# 13. Order Change Request

## US-036: Submit an Order Change Request

As an ADMIN,
I want to submit an Order Change Request with a reason and proposed changes,
so that approved order specifications can be changed through a controlled process.

Rules:

- Before LoA approval, Admin may edit permitted order information.
- After LoA approval, protected specification changes require an Order Change Request.
- Normal specification changes are prohibited after Cutting actually begins.

## US-037: Review an Order Change Request

As an OWNER,
I want to approve or reject an Order Change Request,
so that changes to approved order specifications remain under authorized business control.

Approved changes must:

- Preserve previous approved order specifications.
- Trigger revalidation of affected Production Readiness requirements.
- Trigger revalidation of affected Functional Layout information.
- Avoid bypassing applicable production restrictions.

Detailed Change Request status transitions remain UNDECIDED.

---

# 14. Authentication, Authorization, and Audit History

## US-038: Authenticate an Internal User

As an internal user,
I want to log in securely,
so that I can access the system using my account.

Customers do not have login access in Phase 1.

## US-039: Access Role-Based Features

As an internal user,
I want to access only the features permitted by my role,
so that system operations follow the defined authorization rules.

The backend must enforce authorization independently of frontend visibility.

## US-040: View Order Status Audit History

As an OWNER,
I want to view order status audit history,
so that important order status changes can be traced.

Owner access to order status audit history is read-only.

Access to the complete application audit log follows separately approved permissions.

---

# 15. Dashboard

## US-041: View Operational Dashboard

As an OWNER,
I want to view an overview of orders and production progress,
so that I can monitor overall operations.

Dashboard information must follow approved role permissions and available authoritative business data.

Overall order progress calculation remains UNDECIDED.

## US-042: View Production Information

As a PPIC,
I want to view production orders and their available progress information,
so that I can coordinate production activities.

Dashboard visibility must follow approved role permissions.

## US-043: View Assigned Production Information

As a PRODUCTION_STAFF,
I want to view production information assigned to my department and responsibilities,
so that I can understand the work that must be performed.

Production Staff visibility is restricted by department and assignment.

---

# 16. User Story Governance

All user stories must remain consistent with:

- Approved PRD scope.
- BUSINESS_RULES.md.
- BUSINESS_WORKFLOW.md.
- USER_ROLES.md.
- USER_PERSONAS.md.
- GLOSSARY.md.
- ACCEPTANCE_CRITERIA.md.
- FEATURE_SPECIFICATIONS.md.
- API_CONTRACTS.md.
- SYSTEM_ARCHITECTURE.md.

## 16.1 Unresolved Requirements

The following must remain unresolved until formally approved:

- Sampling responsible role and permissions.
- Sample / Look Approval authorization.
- LoA approval authorization.
- Payment correction submission authorization.
- Payment correction detailed workflow.
- Production Readiness checklist.
- Waiting reason definitions.
- Waiting resume and release conditions.
- Functional Layout approval.
- QC and Rework measurement.
- Packing duration.
- Fulfillment evidence requirements.
- Fulfillment detailed transitions and responsibilities.
- Overall production progress calculation.
- Stage weighting.
- Bottleneck calculation.
- Issue categories.
- Production Issue Report detailed transition rules.
- Order Change Request status model.
- Additional notification events and recipients.
- Detailed audit permissions.

Do not convert unresolved requirements into implementation assumptions.

## 16.2 Change Management

Any change to a user story must be reviewed against:

1. The approved business requirements.
2. The responsible role and its permissions.
3. The associated business workflow.
4. The relevant acceptance criteria.
5. The related feature specification.
6. The technical architecture where applicable.

A user story must not independently introduce a new business rule.

## 16.3 Completion Criteria

A user story is considered ready for implementation when:

- Its business purpose is understood.
- Its responsible role is identified or explicitly marked unresolved.
- Its required business rules are documented.
- Its acceptance criteria are defined.
- Its dependencies are understood.
- Its unresolved decisions are explicitly recorded.

A user story is considered complete only after its implementation has been verified against the applicable acceptance criteria.

---
