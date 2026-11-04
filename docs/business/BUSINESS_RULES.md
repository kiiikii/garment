# Business Rules

**Product:** Garment ERP Core  
**Scope:** Phase 1 — CMT and FOB Production  
**Document Status:** Draft  
**Related Documents:** `PRD.md`, `BUSINESS_WORKFLOW.md`, `USER_ROLES.md`, `GLOSSARY.md`

This document defines the business rules, constraints, and validation requirements for Phase 1 of the Garment ERP system.

These rules are the business reference for domain modeling, application services, API validation, database design, authorization, and workflow implementation.

The PRD defines product scope and approved product requirements. This document defines the corresponding business constraints. `BUSINESS_WORKFLOW.md` defines the process sequence and workflow gates.

Never invent a business rule to make implementation easier. If a rule is unclear, preserve its status and identify the decision required before implementation.

---

## 1. Requirement Status Definitions

All requirement statuses must follow the PRD.

| Status                  | Meaning                                                      |
| ----------------------- | ------------------------------------------------------------ |
| `CONFIRMED`             | Explicitly stated or approved by the business.               |
| `CONFIRMED — TEMPORARY` | Currently accepted for Phase 1 but subject to later review.  |
| `PROPOSED`              | Suggested behavior that has not been approved.               |
| `UNKNOWN`               | Required information is not currently known.                 |
| `UNDECIDED`             | The business has not selected or approved a rule.            |
| `FUTURE`                | Outside Phase 1 and potentially considered in a later phase. |

Additional terms:

- **Configurable** describes a value or behavior that may be changed through configuration. It is not a requirement status.
- **Alternative options** describe multiple possible interpretations that require a business decision. They are not a requirement status.

Do not treat `PROPOSED`, `UNKNOWN`, `UNDECIDED`, or `FUTURE` items as confirmed requirements.

---

## 2. Production Types

**Status: CONFIRMED**

Phase 1 supports two production types.

| Type | Business Meaning                                                                                                                |
| ---- | ------------------------------------------------------------------------------------------------------------------------------- |
| CMT  | The customer provides the main materials. The company provides production labor, execution, thread, and supporting accessories. |
| FOB  | The customer provides a Down Payment (DP). The company procures the required materials and executes production.                 |

Rules:

1. CMT and FOB are the only active production types in Phase 1.
2. Fashion Retail is excluded from Phase 1.
3. FOB orders may depend on material procurement and material readiness.
4. Readiness requirements must be evaluated according to the production type and the actual requirements of the order.
5. The system must not assume that every material or procurement dependency applies to every CMT or FOB order.
6. Whether DP payment is a mandatory prerequisite for Production Readiness or Mass Production remains `UNDECIDED`.
7. The existence of a DP payment agreement does not by itself establish a confirmed production gate.

Do not implement Fashion Retail as an active production type in Phase 1.

---

## 3. Customer and Order

**Status: CONFIRMED**

### 3.1 Required Order Information

The confirmed order information includes:

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Product Reference Images (supported order data; minimum image requirement remains undecided).
- Size quantities.
- Production Type: CMT or FOB.

The order intake process is performed by Admin.

Do not add other mandatory order fields without a confirmed business requirement.

### 3.2 Product Reference Images

Rules:

1. An order supports multiple product reference images.
2. Images are used as references for the ordered product or model.
3. Whether at least one image is mandatory: `UNDECIDED`.
4. Image storage, file constraints, and accepted formats are technical decisions subject to system requirements.

### 3.3 Size Quantities

An order records quantities per size rather than relying on a single size-and-quantity pair.

Example:

| Size      | Quantity |
| --------- | -------: |
| S         |       20 |
| M         |       40 |
| L         |       30 |
| XL        |       10 |
| **Total** |  **100** |

Rules:

1. Quantity is recorded for each applicable size.
2. Total order quantity is the sum of the recorded size quantities.
3. Whether total quantity is stored, calculated dynamically, or both: `UNDECIDED`.
4. Whether one order may contain multiple distinct product models: `UNDECIDED`.
5. Whether custom sizes are supported: `UNDECIDED`.
6. Quantity must not be negative.
7. Actual-piece, reject-quantity, and rework-quantity tracking are not mandatory Phase 1 requirements.

### 3.4 Order Editing

Rules:

1. Admin may edit order details before LoA is approved.
2. LoA approval locks the agreed order specifications.
3. After LoA approval, changes to locked order details must use an Order Change Request.
4. Normal specification changes are not allowed after Mass Production has started.
5. Mass Production is considered started when Cutting actually begins.

The detailed Order Change Request rules are defined in Section 10.

---

## 4. Sampling

**Status: CONFIRMED**

Sampling consists of pattern preparation followed by sample production.

| Step                       |  Internal Duration |
| -------------------------- | -----------------: |
| Pattern                    |             2 days |
| Sample                     |             2 days |
| **Internal Total**         |         **4 days** |
| **Customer-facing Target** | **7 working days** |

Rules:

1. Pattern preparation has an internal duration reference of 2 days.
2. Sample production has an internal duration reference of 2 days.
3. The combined internal sampling duration is 4 days.
4. The customer-facing sampling target is 7 working days.
5. Internal duration and customer-facing target are different measurements.
6. The 4-day internal duration must not be substituted for the 7-working-day customer-facing target.
7. The system must preserve planned and actual sampling dates separately.

The reason for the difference between the internal duration and customer-facing target is `UNKNOWN`.

The applicable work calendar and holiday treatment are defined in Section 13.

---

## 5. Sample / Look Approval

**Status: CONFIRMED**

Sample / Look Approval is a business gate before the subsequent commercial and production stages.

Supported states:

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

Rules:

1. Sample / Look Approval must be recorded for the order.
2. Only an approved sample may pass the Sample Approval Gate.
3. `REVISION` does not mean approval.
4. `REJECTED` does not automatically mean permanent order cancellation.
5. A rejected sample must not automatically advance to LoA approval.
6. No additional approval states may be introduced without business justification.

The detailed consequences of rejection, resubmission limits, and revision transitions remain `UNDECIDED`.

---

## 6. Letter of Agreement (LoA)

**Status: CONFIRMED**

LoA represents the commercial agreement before production.

It includes:

- Letter of Agreement / MoU.
- Payment Agreement.
- Payment Terms.

Supported states:

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

Rules:

1. LoA is a commercial gate before production readiness evaluation.
2. Production must not proceed while LoA is not approved.
3. A LoA that is not approved does not automatically mean the order is permanently cancelled.
4. LoA approval locks the agreed order details.
5. Changes to locked order details must go through an Order Change Request.
6. Normal specification changes are not allowed after Cutting has started.
7. LoA approval does not automatically mean that production is ready.
8. LoA approval does not automatically mean that Mass Production may start.

The detailed LoA rejection consequences and approval transition rules remain `UNDECIDED` where not explicitly defined by the business.

---

## 7. Payment

**Status: CONFIRMED — TEMPORARY for the defined Phase 1 scope**

Payment recording is included in Phase 1. Full accounting functionality is out of scope.

Rules:

1. Payment agreement and payment terms are part of the LoA.
2. Admin records DP and subsequent payments actually received.
3. Payment records must represent received payments, not merely expected payments.
4. Owner uses payment information for monitoring.
5. Payment recording is not a replacement for a full accounting system.
6. A correction to an existing payment record requires Owner approval or rejection.
7. Original payment information and correction history must remain traceable.
8. A duplicate transaction must not be counted more than once in payment metrics.
9. A correction must not silently overwrite the original payment record.

The following details remain `UNDECIDED`:

- Payment methods.
- Payment reference requirements.
- Partial payment rules beyond recording received amounts.
- Payment cancellation workflow.
- Exact correction request fields.
- Owner approval and rejection transition details.
- Whether a rejected correction may be resubmitted.
- Payment status model.

Do not implement full accounting, accounts receivable, or financial reconciliation as part of Phase 1.

---

## 8. Production Readiness

**Status: CONFIRMED**

Sample approval and LoA approval are necessary but insufficient conditions for production readiness.

Rules:

1. Production readiness must be evaluated separately after Sample / Look Approval and LoA are approved.
2. An order must not enter PPIC Preparation until the required readiness conditions are satisfied.
3. Potential readiness dependencies include:
   - Main material availability.
   - Fabric availability.
   - Material purchasing.
   - Operational requirements.
   - Other business-approved prerequisites.
4. Not every potential dependency necessarily applies to every production type or order.
5. Readiness evaluation must use requirements applicable to the specific order.
6. The system must preserve the distinction between readiness status and the reason an order is waiting.
7. PPIC Preparation is not itself a readiness checklist item unless the business explicitly approves that relationship.

The complete production readiness checklist is `UNKNOWN`.

Do not invent a universal readiness checklist or assume that every order requires procurement.

---

## 9. Waiting / On Hold

**Status: CONFIRMED**

An order may enter Waiting / On Hold when one or more production readiness conditions are not satisfied.

Rules:

1. Waiting / On Hold is conditional, not mandatory for every order.
2. Material or fabric unavailability is a known example.
3. Waiting / On Hold is not limited to material shortages.
4. The system must record the reason an order is waiting.
5. The system must distinguish:
   - Production readiness.
   - Waiting reason.
   - Current workflow state.
6. An order must undergo readiness re-evaluation before proceeding to PPIC Preparation.
7. The system must not automatically resume an order solely because a date has been reached.
8. Waiting / On Hold must not be treated as a production process stage.

The complete list of waiting reasons and the detailed resume conditions are `UNDECIDED`.

Waiting reasons may be made configurable after the business approves the available categories.

Do not hard-code `ON_HOLD = FABRIC_SHORTAGE`.

---

## 10. Order Change Request

**Status: CONFIRMED for the high-level process**

Order Change Request protects agreed order specifications after LoA approval.

Rules:

1. Before LoA approval, Admin may edit order details directly within the permitted order-editing scope.
2. After LoA approval, changes to locked order details require an Order Change Request.
3. Admin creates the Change Request with the reason and proposed changes.
4. Owner approves or rejects the request.
5. Approved changes are applied while preserving the previous order version.
6. An approved change before Mass Production starts triggers revalidation of affected production readiness requirements and Functional Layout.
7. Revalidation must identify which previously satisfied requirements are affected by the approved change.
8. Normal specification changes are not allowed after Mass Production starts.
9. Mass Production starts when Cutting actually begins.
10. Change Request decisions and order versions must remain traceable.

The following details remain `UNDECIDED`:

- Complete Change Request status model.
- Whether multiple pending requests may exist for one order.
- Whether a request can be withdrawn.
- Whether Owner may request revision instead of approving or rejecting.
- Exact fields that may be changed.
- Whether non-specification administrative corrections follow the same process.
- Detailed approval and rejection transitions.

Do not provide a normal order-update endpoint that bypasses the Change Request requirement after LoA approval.

---

## 11. PPIC Preparation and Functional Layout

**Status: CONFIRMED**

Rules:

1. PPIC is responsible for production preparation.
2. PPIC Preparation occurs after production readiness has been satisfied.
3. PPIC Preparation leads to Functional Layout preparation.
4. Functional Layout represents a functional or process-oriented production arrangement.
5. Examples of functional zones include:
   - Cutting.
   - Sewing.
   - Overlock.
   - Embroidery.
   - Finishing.
6. A Functional Layout is not a production process state.
7. A Functional Layout is not a substitute for the production workflow.
8. The production workflow describes the sequence of processes.
9. The Functional Layout describes the arrangement of work areas or functional zones.

Additional PPIC responsibilities are `UNKNOWN`.

Whether Functional Layout requires a separate approval is `UNDECIDED`.

Do not treat layout zones as production stages or use them to calculate stage progress.

---

## 12. Mass Production

**Status: CONFIRMED**

The Mass Production process sequence is:

`CUTTING → SEWING → QC → (PASS → FINISHING | FAIL → REWORK → QC AGAIN)`

Rules:

1. Cutting precedes Sewing.
2. Sewing precedes QC.
3. QC occurs after Sewing and before Finishing.
4. A successful QC inspection allows the process to proceed to Finishing.
5. A failed QC inspection may require Rework.
6. Rework is followed by another QC inspection.
7. The process may repeat through Rework and QC according to the actual inspection result.
8. Finishing follows successful QC.
9. Packing follows Finishing.
10. Delivery or Customer Pickup follows Packing.
11. Completing Finishing alone does not mean the order is completed.

### 12.1 Internal Duration Baseline

| Process                    |      Internal Duration |
| -------------------------- | ---------------------: |
| Cutting                    |                 2 days |
| Sewing                     |                 5 days |
| Finishing                  |                 2 days |
| **Internal Baseline**      |             **9 days** |
| **Customer-facing Target** | **10–14 working days** |

Rules:

1. The current internal duration baseline is 9 days.
2. The 9-day baseline consists only of Cutting, Sewing, and Finishing.
3. QC and Rework are not included in the current 9-day calculation.
4. QC and Rework durations must not be added to the baseline without a new business decision.
5. The customer-facing target is 10–14 working days.
6. The internal baseline and customer-facing target are different measurements.
7. Sewing duration must not be treated as the total Mass Production duration.

### 12.2 QC and Rework

| Item                        | Rule                                            |
| --------------------------- | ----------------------------------------------- |
| QC position                 | After Sewing and before Finishing — `CONFIRMED` |
| Rework after QC failure     | `CONFIRMED`                                     |
| QC duration                 | `UNKNOWN`                                       |
| Rework duration             | `UNKNOWN`                                       |
| QC inspection coverage      | `UNDECIDED`                                     |
| Rework limit                | `UNDECIDED`                                     |
| Rework escalation policy    | `UNDECIDED`                                     |
| QC progress measurement     | `UNDECIDED`                                     |
| Rework progress measurement | `UNDECIDED`                                     |

Do not assume 100% inspection, sampling inspection, a fixed number of rework cycles, or a fixed QC duration.

### 12.3 Packing and Fulfillment

| Item                                       | Rule        |
| ------------------------------------------ | ----------- |
| Packing follows Finishing                  | `CONFIRMED` |
| Delivery / Customer Pickup follows Packing | `CONFIRMED` |
| Packing duration                           | `UNKNOWN`   |
| Delivery / Customer Pickup duration        | `UNKNOWN`   |
| Required completion evidence               | `UNDECIDED` |

Mass Production completion and Order completion are different conditions.

An order may become `COMPLETED` only after the required production and fulfillment processes have been completed.

PPIC must confirm that the required fulfillment completion condition has been satisfied before the order becomes `COMPLETED`.

The personnel responsible for executing and recording Packing, Delivery, and Customer Pickup remain `UNDECIDED`.

The required fulfillment completion evidence also remains `UNDECIDED`.

Do not mark an order `COMPLETED` merely because Finishing reaches 100%.

---

## 13. Timeline and Duration

**Status: CONFIRMED for the required tracking capabilities**

The system must support:

- Planned Start Date.
- Planned Completion Date.
- Actual Start Date.
- Actual Completion Date.
- Planned Duration.
- Actual Duration.
- Current Process.
- Current Workflow State.
- Waiting / On Hold State.
- Delay Information.
- Customer-facing Deadline.

Rules:

1. Planned and actual dates must remain distinguishable.
2. Planned and actual durations must remain distinguishable.
3. Internal duration and customer-facing target must remain distinguishable.
4. A planned date must not be overwritten by an actual date.
5. A reported delay must not silently overwrite the original planned completion date.
6. The system must preserve the original planned completion date.
7. An estimated completion date may change when an approved business process updates the estimate.
8. Actual completion date must be recorded when the order is completed.
9. Calendar days and working days must not be treated as interchangeable.
10. Deadline, overdue, and delay aggregation calculations must follow approved rules.

### 13.1 Working-Day Calendar

The temporary calendar assumption is:

- Monday to Friday are working days.
- Saturday and Sunday are excluded.

**Status: CONFIRMED — TEMPORARY**

National holidays, company holidays, exceptional working days, and calendar configuration are `UNKNOWN`.

Do not implement a permanent holiday policy based only on the temporary weekday assumption.

### 13.2 Delay and Overdue

Rules:

1. A Delay Report does not automatically make an order overdue.
2. Overdue status must be based on an approved deadline and overdue calculation rule.
3. The original planned completion date must remain available.
4. An estimated completion date may be updated separately.
5. Multiple Delay Report aggregation is `UNDECIDED`.
6. Overdue calculation based on original planned date versus adjusted estimated date is `UNDECIDED`.

---

## 14. Production Tracking and Progress Monitoring

### 14.1 Stage Progress Tracking

**Status: CONFIRMED**

PPIC manually submits percentage-based progress for confirmed percentage-monitored production stages.

Confirmed percentage-monitored stages:

- Cutting.
- Sewing.
- Finishing.

QC and Rework are operationally monitored stages.
Their percentage-progress measurement method remains `UNDECIDED`.

Rules:

1. PPIC manually submits stage progress.
2. Production Staff do not submit stage progress percentages in Phase 1.
3. Every progress update must preserve its timestamp and submitting user.
4. Historical progress updates must not be overwritten.
5. A new update must be distinguishable from the absence of an update.
6. An update with an unchanged percentage must still be recorded
   as a submitted update.
7. Stage progress accepts percentage values; fixed increments
   are not required.
8. QC and Rework must not be assigned percentage-progress mechanics
   until their measurement method is approved.
9. PPIC is responsible for progress submissions.
10. The system must retain previous progress updates for monitoring
    and audit purposes.

### 14.2 Overall Order Progress

Rules:

1. Overall order progress is derived from stage progress records.
2. The overall order progress formula is `UNDECIDED`.
3. Stage weighting is `UNDECIDED`.
4. Do not assume that overall order progress is the arithmetic average of all stage percentages.
5. Do not invent stage weights.
6. Do not treat Functional Layout completion as a Mass Production progress percentage.

### 14.3 Planned vs Actual Monitoring

The system must distinguish:

- Planned Start Date vs Actual Start Date.
- Planned Completion Date vs Actual Completion Date.
- Planned Duration vs Actual Duration.
- Reported Stage Progress based on PPIC-submitted progress records.

A planned progress schedule and its comparison with reported progress are `UNDECIDED`.

Detailed calculation and aggregation rules are `UNDECIDED`.

### 14.4 Production Quantity

Rules:

1. Size quantities recorded in the order define the ordered quantity.
2. Phase 1 does not include actual-piece production tracking.
3. Target vs actual quantity comparison is not a confirmed Phase 1 feature.
4. Reject-quantity tracking is not a confirmed Phase 1 requirement.
5. Rework-quantity tracking is not a confirmed Phase 1 requirement.
6. These quantities must not be mandatory production progress inputs.
7. Future quantity-based tracking requires a separate business decision.

### 14.5 Current Process and Bottleneck

These are different concepts:

- **Current Process:** the production process currently being executed.
- **Bottleneck:** the process or constraint that restricts production throughput.

Do not use these terms interchangeably.

A bottleneck must not be inferred solely from the current process.

---

## 15. Delay Report

**Status: CONFIRMED for the Phase 1 reporting workflow**

A Delay Report records a reported delay or a condition that may affect the production timeline.

Required information:

- Related order number.
- Affected production stage.
- Reason.
- Chronology or explanation of the delay.
- Estimated additional duration in working days.

Rules:

1. PPIC creates a Delay Report.
2. A newly created Delay Report has status `OPEN`.
3. Creating a Delay Report initiates email dispatch for configured recipients.
4. Intended recipients include PPIC, Owner, and Customer.
5. Customer email availability and the requirement to provide a customer email address remain `UNDECIDED`.
6. PPIC is responsible for following up on the report.
7. PPIC may update the report to `RESOLVED`.
8. Resolving a Delay Report initiates email dispatch for configured recipients.
9. Delay Report status history must be retained.
10. Email dispatch attempts and delivery outcomes must be retained.
11. Email delivery failure must not delete or invalidate the Delay Report.
12. PPIC may create and update Delay Reports without Owner approval.
13. Estimated additional duration is recorded in working days.
14. A Delay Report does not automatically change the order to `OVERDUE`.
15. A Delay Report is distinct from a Production Issue Report.

### 15.1 Delay Report Statuses

Phase 1 statuses:

- `OPEN`
- `RESOLVED`

Do not introduce additional statuses without business approval.

### 15.2 Delay Calculation

Rules:

1. The original planned completion date must be preserved.
2. Estimated completion date may be updated through a separately
   authorized business process.
3. Creating or resolving a Delay Report does not automatically
   update the estimated completion date.
4. Actual completion date is recorded when the order is completed.
5. Multiple Delay Report aggregation is `UNDECIDED`.
6. Overlapping Delay Reports and their effect on estimated completion are `UNDECIDED`.
7. The authoritative basis for overdue calculation is `UNDECIDED`.

A Delay Report is a formal record of a reported delay, not an automatic deadline adjustment.

---

## 16. Production Issue Report

**Status: CONFIRMED for the Phase 1 workflow**

A Production Issue Report records an operational issue encountered during production.

### 16.1 Report Information

The report includes:

- Related order.
- Department or production stage.
- Issue type.
- Description.
- Automatically recorded timestamp.
- Optional photo.

Issue categories are `UNDECIDED`.

### 16.2 Workflow

1. Production Staff submits an issue report.
2. PPIC receives an In-App Notification.
3. PPIC follows up and updates the report status.
4. The reporting Staff member receives an In-App Notification when the status changes.
5. PPIC must provide a resolution note before marking the report `RESOLVED`.

### 16.3 Statuses

Phase 1 statuses:

- `OPEN`
- `IN_PROGRESS`
- `RESOLVED`

Do not introduce additional statuses without business approval.

The detailed status transition rules remain `UNDECIDED`.

In particular, the system must not assume that `IN_PROGRESS` is a mandatory intermediate status before `RESOLVED`.

Status transition validation must follow the approved business rules once the transition model has been confirmed.

### 16.4 Access Rules

| Role             | Access                                                  |
| ---------------- | ------------------------------------------------------- |
| OWNER            | Read-only access to all Production Issue Reports.       |
| ADMIN            | No access to Production Issue Reports in Phase 1.       |
| PPIC             | Access to all reports and responsibility for follow-up. |
| PRODUCTION_STAFF | May create reports and view their own reports only.     |

Rules:

1. Production Staff must not view reports submitted by other Staff members.
2. PPIC may view and manage all Production Issue Reports.
3. Owner has read-only access to all Production Issue Reports.
4. Admin has no Production Issue Report access in Phase 1.
5. Resolution notes and status changes must be visible to the reporting Staff member and Owner.
6. A Production Issue Report does not automatically become a Delay Report.
7. An operational issue does not automatically mean an order is delayed.

### 16.5 Notifications

1. PPIC receives an In-App Notification when a new issue report is submitted.
2. The reporting Staff member receives an In-App Notification when the report status changes.
3. Notifications have per-user `READ` / `UNREAD` state.
4. Notification history must be retained according to the system's notification persistence design.

Issue escalation rules and additional issue categories are `UNDECIDED`.

---

## 17. Notifications

**Status: CONFIRMED for the limited Phase 1 notification scope**

Phase 1 In-App Notifications are limited to Production Issue Reports.

Rules:

1. PPIC is notified when a new Production Issue Report is submitted.
2. The reporting Staff member is notified when the report status changes.
3. Notification read state is maintained per user.
4. Duplicate notifications for the same business event and recipient must be prevented.
5. Notifications must not be generated merely because a stage progress percentage is below 100%.
6. Notification recipients must be determined by the relevant business event and approved role permissions.

### 17.1 Delay Report Email

Delay Report email notifications are separate from In-App Notifications.

Intended recipients:

- PPIC.
- Owner.
- Customer.

Email events:

- Delay Report created (`OPEN`).
- Delay Report resolved (`RESOLVED`).

Rules:

1. Each event initiates email dispatch for configured recipients.
2. Customer email availability and the requirement to provide a customer email address remain `UNDECIDED`.
3. Email dispatch attempts and delivery outcomes must be retained.
4. Email delivery failure must not remove or invalidate the business report.
5. The system must not assume that dispatch initiation means successful delivery.

### 17.2 Other Notification Events

The following are candidate business events, not confirmed active Phase 1 notification requirements:

- `SAMPLE_APPROVED`
- `LOA_APPROVED`
- `PRODUCTION_WAITING`
- `PRODUCTION_READY`
- `PRODUCTION_STARTED`
- `PRODUCTION_COMPLETED`
- `PRODUCTION_SHORTAGE`
- `PRODUCTION_DELAYED`
- `DEADLINE_WARNING`

Their recipients, delivery channels, triggering conditions, and duplicate-prevention rules are `UNDECIDED` unless explicitly defined elsewhere in this document.

Sampling notifications are intended for OWNER only in Phase 1 if enabled under the approved notification scope. They must not be generalized to other roles.

Do not implement all candidate events as active notifications merely because they appear in a candidate list.

---

## 18. Validation

**Status: CONFIRMED for backend authority; individual constraints retain their stated status**

The backend is authoritative for business validation.

Frontend validation may improve user experience but must not replace backend validation.

### 18.1 Confirmed Validation Rules

- Customer Name must not be empty.
- Customer Phone Number is required.
- Customer Origin / Address is required.
- Production Type must be `CMT` or `FOB` in Phase 1.
- Size quantity must not be negative.
- Total quantity must equal the sum of recorded size quantities when calculated.
- Workflow gates must be enforced by the backend.
- Unauthorized actions must be rejected by the backend.

### 18.2 Unresolved Validation Rules

| Rule                                         | Status                         |
| -------------------------------------------- | ------------------------------ |
| Phone number format                          | `UNDECIDED`                    |
| At least one image required per order        | `UNDECIDED`                    |
| Maximum image count and file size            | `UNDECIDED` / technical policy |
| Multiple product models per order            | `UNDECIDED`                    |
| Custom sizes                                 | `UNDECIDED`                    |
| Zero quantity allowed for an individual size | `UNDECIDED`                    |
| Minimum total order quantity                 | `UNDECIDED`                    |

Do not introduce unapproved validation constraints.

---

## 19. Roles and Access Control

**Status: CONFIRMED for Phase 1 role definitions and explicitly stated access rules**

Phase 1 roles:

- `OWNER`
- `ADMIN`
- `PPIC`
- `PRODUCTION_STAFF`

Rules:

1. Access is controlled by role-based permissions.
2. The backend enforces authorization.
3. Frontend visibility is not a substitute for backend authorization.
4. Customers do not have login access in Phase 1.
5. Each role may only perform actions within its approved scope.
6. Detailed permissions not explicitly established in the PRD remain `UNDECIDED`.

### 19.1 Confirmed Access Constraints

| Capability                           | OWNER     | ADMIN | PPIC | PRODUCTION_STAFF |
| ------------------------------------ | --------- | ----- | ---- | ---------------- |
| View Owner dashboard                 | Yes       | No    | No   | No               |
| Manage customer/order intake         | No        | Yes   | No   | No               |
| Record received payments             | No        | Yes   | No   | No               |
| Approve payment corrections          | Yes       | No    | No   | No               |
| Approve/reject Order Change Requests | Yes       | No    | No   | No               |
| Manage production readiness          | No        | No    | Yes  | No               |
| Prepare Functional Layout            | No        | No    | Yes  | No               |
| Submit stage progress                | No        | No    | Yes  | No               |
| Create Delay Report                  | No        | No    | Yes  | No               |
| Manage Delay Report follow-up        | No        | No    | Yes  | No               |
| View all Production Issue Reports    | Read-only | No    | Yes  | No               |
| Create Production Issue Report       | No        | No    | No   | Yes              |
| View own Production Issue Reports    | No        | No    | Yes  | Yes              |
| View all order-status audit history  | Yes       | No    | No   | No               |

The table above records explicitly established access constraints. It does not grant unlisted permissions.

Production Staff visibility is limited to orders currently `IN_PROGRESS` within the relevant department, subject to the approved assignment and department visibility rules.

How Staff are assigned to departments and orders is `UNDECIDED`.

---

## 20. Dashboard and Monitoring

**Status: CONFIRMED for the dashboard scope defined by the PRD**

Dashboard metrics must use authoritative business records.

### 20.1 Owner Dashboard

The Owner dashboard includes:

- Total Order Value for the selected period.
- Revenue Received from payments actually received during the selected period.
- New Orders count based on order creation date.
- Current overdue orders.
- Current `OPEN` Delay Reports.
- Production progress summary.
- Active-order details grouped by customer.
- Read-only access to all Production Issue Reports.
- Audit history of order-status changes.

Financial Metric Rules:

1. Total Order Value is intended to represent the value of orders
   associated with the selected reporting period.
2. The date basis, source, definition, calculation method, and
   currency of Total Order Value remain `UNDECIDED`.
3. Revenue Received represents payments actually received
   within the selected period.
4. Total Order Value must not be derived from received payment records.
5. Revenue Received must use actual received payment records.
6. The source, definition, calculation method, and currency of Total Order Value remain `UNDECIDED`.
7. The exact period semantics and aggregation details remain `UNDECIDED` where not otherwise defined.

Consumer growth metrics are excluded.

The exact period semantics and aggregation details for financial and production metrics remain `UNDECIDED` where not specified.

### 20.2 Admin Dashboard

The Admin dashboard includes:

- Incoming orders.
- Sampling and approval status.
- LoA status.
- Payment recording and monitoring.
- Customer data.
- Order data.

Admin has no access to Production Issue Reports in Phase 1.

### 20.3 PPIC Dashboard

The PPIC dashboard includes:

- Orders awaiting production readiness.
- Waiting / On Hold orders.
- PPIC Preparation.
- Functional Layout preparation.
- Cutting percentage progress.
- Sewing percentage progress.
- QC operational status.
- Rework operational status.
- Finishing percentage progress.
- Orders with no progress update.
- `OPEN` Delay Reports.
- All Production Issue Reports and follow-up.

The exact threshold for identifying an order as having no progress update is `UNDECIDED`.

### 20.4 Production Staff Dashboard

The Production Staff dashboard includes only:

- Orders currently `IN_PROGRESS` within the relevant department.
- Product details.
- Functional Layout information relevant to the department.
- Target completion information.
- Work instructions.
- Issue report creation.
- The Staff member's own Production Issue Reports.

Production Staff must not submit progress percentages.

### 20.5 Dashboard Calculation Rules

1. Dashboard metrics must use authoritative business records.
2. Stage progress must come from PPIC-submitted progress records.
3. Timeline information must distinguish planned and actual values.
4. Production status must reflect the current business workflow state.
5. Dashboard calculations must not create independent business truth.
6. Overall order progress formula is `UNDECIDED`.
7. Stage progress weighting is `UNDECIDED`.
8. Dashboard period semantics are `UNDECIDED` except where explicitly defined above.
9. A Production Issue Report does not automatically mean an order is delayed.
10. A Delay Report does not automatically mean an order is overdue.

---

## 21. Audit History

**Status: CONFIRMED for audit principles; detailed coverage remains partially undecided**

Important business actions must preserve traceability.

### 21.1 Audit Principles

Each audit record must identify:

- Actor.
- Timestamp.
- Action.
- Affected business entity.
- Relevant change information where applicable.

Rules:

1. Important business actions must preserve audit history.
2. Historical progress updates must not be silently overwritten.
3. Audit history is not a replacement for current business records.
4. Audit records must not be silently modified or deleted through normal business operations.
5. Audit access must follow role-based permissions.

### 21.2 Candidate Audited Activities

- Order creation and updates.
- Order Change Requests and their decisions.
- Sample / Look Approval changes.
- LoA status changes.
- Production Readiness changes.
- Waiting / On Hold changes.
- PPIC stage progress submissions.
- Production workflow changes.
- Payment recording and updates.
- Important administrative actions.

The complete audit action list is `UNDECIDED`.

### 21.3 Audit Access

1. Owner has access to order-status change audit history.
2. Other roles must not be granted access to order-status audit history in Phase 1.
3. Visibility of other audit categories remains `UNDECIDED` unless explicitly defined by an approved permission rule.

Audit retention policy is `UNDECIDED`.

---

## 22. Out of Scope for Phase 1

The following capabilities are excluded:

- Full Inventory Management.
- Stock Movement.
- Stock Opname.
- Stock Variance.
- Material Consumption Tracking.
- HPP / Cost of Goods Sold Calculation.
- Full Costing.
- Leftover Goods Management.
- Bonus Goods Management.
- Full Procurement Management.
- Fashion Retail Production Model.
- Customer Portal.
- Customer Login.
- Native Mobile Application.
- Full Accounting.

Excluding Full Procurement Management does not exclude order-specific material purchasing required to satisfy FOB production readiness.

Phase 1 may track the readiness of required materials without implementing a complete procurement management module.

These areas may influence future domain evolution, but they must not drive current implementation.

When an excluded capability becomes active, perform a Domain Impact Analysis before extending the system.

---

## 23. Open Business Decisions

The following items must remain unresolved until the business makes an explicit decision.

| Item                                                                                      | Status      |
| ----------------------------------------------------------------------------------------- | ----------- |
| Phone number format                                                                       | `UNDECIDED` |
| Whether at least one image is required per order                                          | `UNDECIDED` |
| Multiple distinct product models per order                                                | `UNDECIDED` |
| Custom sizes                                                                              | `UNDECIDED` |
| Whether total quantity is stored, calculated, or both                                     | `UNDECIDED` |
| Zero quantity for an individual size                                                      | `UNDECIDED` |
| Reason for sampling 4-day internal duration vs 7-working-day target                       | `UNKNOWN`   |
| Reason for mass production 9-day internal baseline vs 10–14-working-day target            | `UNKNOWN`   |
| Complete production readiness checklist                                                   | `UNKNOWN`   |
| Complete waiting reason list                                                              | `UNDECIDED` |
| Waiting resume conditions                                                                 | `UNDECIDED` |
| Additional PPIC responsibilities                                                          | `UNKNOWN`   |
| Functional Layout approval requirement                                                    | `UNDECIDED` |
| QC inspection coverage                                                                    | `UNDECIDED` |
| QC duration                                                                               | `UNKNOWN`   |
| Rework duration                                                                           | `UNKNOWN`   |
| Rework limit and escalation policy                                                        | `UNDECIDED` |
| QC and Rework progress measurement                                                        | `UNDECIDED` |
| Packing duration                                                                          | `UNKNOWN`   |
| Delivery / Customer Pickup duration                                                       | `UNKNOWN`   |
| Delivery / Customer Pickup completion evidence                                            | `UNDECIDED` |
| Permanent work calendar and holiday rules                                                 | `UNKNOWN`   |
| Overall order progress formula                                                            | `UNDECIDED` |
| Stage progress weighting                                                                  | `UNDECIDED` |
| Planned vs actual progress calculation                                                    | `UNDECIDED` |
| Delay aggregation and overlap handling                                                    | `UNDECIDED` |
| Overdue calculation basis                                                                 | `UNDECIDED` |
| Production Issue categories                                                               | `UNDECIDED` |
| Production Issue escalation rules                                                         | `UNDECIDED` |
| Order Change Request detailed state machine                                               | `UNDECIDED` |
| Payment correction detailed workflow                                                      | `UNDECIDED` |
| Payment cancellation workflow                                                             | `UNDECIDED` |
| Detailed role permissions not specified above                                             | `UNDECIDED` |
| Production Staff assignment and department visibility                                     | `UNDECIDED` |
| Dashboard period and aggregation details                                                  | `UNDECIDED` |
| No-progress-update threshold                                                              | `UNDECIDED` |
| Additional notification events and recipients                                             | `UNDECIDED` |
| Complete audit action list                                                                | `UNDECIDED` |
| Audit retention policy                                                                    | `UNDECIDED` |
| Audit visibility beyond order-status history                                              | `UNDECIDED` |
| Whether DP is a prerequisite for Production Readiness or Mass Production                  | `UNDECIDED` |
| Customer email source and whether customer email is mandatory                             | `UNDECIDED` |
| Responsibility for Sampling execution                                                     | `UNDECIDED` |
| Responsibility for Sample / Look Approval                                                 | `UNDECIDED` |
| Responsibility for LoA authorization                                                      | `UNDECIDED` |
| Responsibility for Packing execution and recording                                        | `UNDECIDED` |
| Responsibility for Delivery / Customer Pickup execution and recording                     | `UNDECIDED` |
| Total Order Value source, definition, calculation, and currency                           | `UNDECIDED` |
| Whether Sampling notifications are enabled, including configuration and delivery behavior | `UNDECIDED` |

---

## 24. Implementation Principles

Business rules define business constraints, not implementation shortcuts.

Before implementing a business rule:

1. Verify its requirement status.
2. Identify the affected domain and workflow.
3. Identify dependencies and side effects.
4. Confirm unresolved decisions with the business.
5. Update this document when a business decision changes.
6. Ensure API validation and domain behavior enforce the approved rule.
7. Ensure tests cover confirmed rules and prohibited transitions.

Never silently convert an `UNKNOWN`, `UNDECIDED`, or `PROPOSED` requirement into application behavior.

When a technical implementation requires an unresolved business decision, stop and request clarification or implement only the portions that do not depend on that decision.
