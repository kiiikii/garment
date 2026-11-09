# Business Rules

**Product:** Garment ERP Core  
**Scope:** Phase 1 — CMT and FOB Production  
**Document Status:** Draft  
**Related Documents:** `PRD.md`, `BUSINESS_WORKFLOW.md`, `USER_ROLES.md`, `GLOSSARY.md`

This document defines the business constraints, rules, and validation requirements for Phase 1.

- `PRD.md` defines product scope and approved requirements.
- `BUSINESS_WORKFLOW.md` defines process sequences and workflow gates.
- `BUSINESS_RULES.md` defines business constraints and validation requirements.
- `USER_ROLES.md` defines role responsibilities and access permissions.
- `GLOSSARY.md` defines shared business terminology.

Never invent a business rule to simplify implementation. Unresolved requirements must retain their status until explicitly decided by the business.

---

## 1. Requirement Status

All requirement statuses must follow the PRD.

| Status                  | Meaning                                           |
| ----------------------- | ------------------------------------------------- |
| `CONFIRMED`             | Explicitly stated or approved by the business.    |
| `CONFIRMED — TEMPORARY` | Accepted for Phase 1 but subject to review.       |
| `PROPOSED`              | Suggested but not approved.                       |
| `UNKNOWN`               | Required information is not currently known.      |
| `UNDECIDED`             | The business has not selected or approved a rule. |
| `FUTURE`                | Outside Phase 1 and potentially considered later. |

Additional terminology:

- **Configurable** describes a value or behavior that may be changed through configuration. It is not a requirement status.
- **Alternative options** describe possible interpretations that require a business decision.

Do not implement `PROPOSED`, `UNKNOWN`, `UNDECIDED`, or `FUTURE` items as confirmed requirements.

---

## 2. Production Types

**Status: CONFIRMED**

Phase 1 supports only:

| Type | Business Rule                                                                                                           |
| ---- | ----------------------------------------------------------------------------------------------------------------------- |
| CMT  | Customer provides main materials. The company provides production labor, execution, thread, and supporting accessories. |
| FOB  | Customer provides DP. The company procures required materials and executes production.                                  |

Rules:

1. CMT and FOB are the only active production types in Phase 1.
2. Fashion Retail is excluded.
3. Material procurement and readiness may apply to FOB orders.
4. Readiness requirements must reflect the actual requirements of each order and production type.
5. Do not assume every CMT or FOB order has identical material or procurement dependencies.
6. Whether DP is mandatory before Production Readiness or Mass Production remains `UNDECIDED`.
7. A payment agreement or DP record does not automatically establish a production gate.

Do not implement Fashion Retail as an active production type.

---

## 3. Customer and Order

**Status: CONFIRMED**

### 3.1 Order Information

The confirmed order information includes:

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Product Reference Images.
- Size Quantities.
- Production Type: `CMT` or `FOB`.

Admin handles order intake.

Do not introduce additional mandatory order fields without business approval.

### 3.2 Product Reference Images

Rules:

1. An order supports multiple product reference images.
2. Images represent the ordered product or model.
3. Whether at least one image is mandatory remains `UNDECIDED`.
4. Storage strategy, accepted formats, file size, and technical constraints are technical decisions subject to system requirements.

### 3.3 Size Quantities

An order records quantities per size.

Example:

| Size      | Quantity |
| --------- | -------: |
| S         |       20 |
| M         |       40 |
| L         |       30 |
| XL        |       10 |
| **Total** |  **100** |

Rules:

1. Each applicable size has its own quantity.
2. Total order quantity equals the sum of recorded size quantities when calculated.
3. Whether total quantity is stored, calculated dynamically, or both remains `UNDECIDED`.
4. Quantity must not be negative.
5. Multiple distinct product models per order, custom sizes, and zero quantity for an individual size remain `UNDECIDED`.
6. Actual-piece, reject-quantity, and rework-quantity tracking are not mandatory Phase 1 requirements.

### 3.4 Order Editing

1. Before LoA approval, Admin may edit order details within the permitted scope.
2. LoA approval locks the agreed order specifications.
3. After LoA approval, changes to locked details require an Order Change Request.
4. Normal specification changes are prohibited after Mass Production starts.
5. Mass Production starts when Cutting actually begins.

See Section 10 for Order Change Request rules.

---

## 4. Sampling and Sample / Look Approval

**Status: CONFIRMED**

### 4.1 Sampling Duration

| Step                       |  Internal Duration |
| -------------------------- | -----------------: |
| Pattern Preparation        |             2 days |
| Sample Production          |             2 days |
| **Internal Total**         |         **4 days** |
| **Customer-facing Target** | **7 working days** |

Rules:

1. Internal sampling duration is 4 days; the customer-facing target is 7 working days.
2. These are separate measurements and must not be substituted for one another.
3. Planned and actual sampling dates must be distinguishable.
4. Sampling occurs before Sample / Look Approval and may take place before LoA approval.
5. The reason for the difference between the internal duration and customer-facing target is `UNKNOWN`.

### 4.2 Sample / Look Approval

Supported states:

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

Rules:

1. Sample / Look Approval must be recorded for the order.
2. Only `APPROVED` passes the Sample Approval Gate.
3. `REVISION` does not mean approval.
4. `REJECTED` does not automatically cancel the order.
5. A rejected sample must not advance to LoA approval.
6. Do not introduce additional approval states without business justification.

Rejection consequences, resubmission limits, revision transitions, and approval responsibility remain `UNDECIDED`.

---

## 5. Letter of Agreement (LoA)

**Status: CONFIRMED**

LoA is the commercial gate before Production Readiness evaluation.

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

1. Sampling may take place before LoA approval.
2. Only `APPROVED` satisfies the LoA gate.
3. Production Readiness must not be considered satisfied while LoA is not approved.
4. Mass Production must not begin while LoA is not approved.
5. LoA approval locks the agreed order specifications.
6. Changes to locked specifications require an Order Change Request.
7. LoA approval permits Production Readiness evaluation but does not establish readiness.
8. LoA approval does not independently authorize Mass Production.
9. A LoA that is not approved does not automatically mean permanent order cancellation.
10. Normal specification changes are prohibited after Cutting starts.

Detailed rejection consequences and approval transitions remain `UNDECIDED`.

---

## 6. Payment

**Status: CONFIRMED — TEMPORARY for the defined Phase 1 scope**

Phase 1 supports payment recording and monitoring, not full accounting.

Rules:

1. Payment agreement and terms are part of LoA.
2. Admin records DP and subsequent payments actually received.
3. Payment records represent received amounts, not expected payments.
4. Owner uses payment information for monitoring.
5. Payment corrections require Owner approval or rejection.
6. Original payment information and correction history must remain traceable.
7. Duplicate transactions must not be counted more than once in payment metrics.
8. Corrections must not silently overwrite original payment records.
9. Payment recording does not replace accounting or financial reconciliation.
10. DP does not automatically satisfy Production Readiness.

The following remain `UNDECIDED`:

- Payment methods and reference requirements.
- Partial payment rules beyond recording received amounts.
- Payment status model.
- Payment cancellation workflow.
- Correction request fields and detailed approval transitions.
- Whether rejected corrections may be resubmitted.
- Whether DP is a prerequisite for Production Readiness or Mass Production.

Full accounting, accounts receivable, and financial reconciliation are out of scope.

---

## 7. Production Readiness and Waiting / On Hold

**Status: CONFIRMED**

### 7.1 Production Readiness

Sample approval and LoA approval are necessary but insufficient conditions for Production Readiness.

Rules:

1. Production Readiness is evaluated separately after Sample / Look Approval and LoA are approved.
2. An order must satisfy its applicable readiness conditions before PPIC Preparation.
3. Readiness requirements depend on the actual order and production type.
4. Potential dependencies include:
   - Main material availability.
   - Fabric availability.
   - Material purchasing.
   - Operational requirements.
   - Other business-approved prerequisites.
5. Not every dependency necessarily applies to every order.
6. The system must distinguish readiness status from the reason an order is waiting.
7. PPIC Preparation is not a readiness checklist item unless explicitly approved.
8. LoA approval or DP payment alone must not automatically mark an order ready.

The complete Production Readiness checklist is `UNKNOWN`.

Do not invent a universal checklist or assume every order requires procurement.

### 7.2 Waiting / On Hold

An order may enter Waiting / On Hold when one or more applicable readiness conditions are not satisfied.

Rules:

1. Waiting / On Hold is conditional, not mandatory for every order.
2. Material or fabric unavailability is a known example, not the only possible reason.
3. The system must record the waiting reason.
4. Readiness status, waiting reason, and current workflow state must remain distinguishable.
5. Readiness must be re-evaluated before the order proceeds to PPIC Preparation.
6. Resolving one waiting reason does not automatically establish full readiness.
7. The system must not resume an order solely because a date has been reached.
8. Waiting / On Hold is not a production process stage.

The complete waiting reason list and resume conditions remain `UNDECIDED`.

Waiting reason categories may be configurable after business approval. Do not hard-code a single reason such as `FABRIC_SHORTAGE`.

---

## 8. PPIC Preparation and Functional Layout

**Status: CONFIRMED**

Rules:

1. PPIC is responsible for production preparation.
2. PPIC Preparation occurs after Production Readiness is satisfied.
3. PPIC Preparation leads to Functional Layout preparation.
4. Functional Layout describes the physical arrangement of production work areas or functional zones.
5. Example zones include Cutting, Sewing, Overlock, Embroidery, and Finishing.
6. Functional Layout is not a production process stage.
7. Functional Layout does not replace the production workflow or determine stage progress.
8. Production workflow describes process sequence; Functional Layout describes work-area arrangement.

Additional PPIC responsibilities are `UNKNOWN`.

Whether Functional Layout requires separate approval remains `UNDECIDED`.

---

## 9. Mass Production and Fulfillment

**Status: CONFIRMED**

### 9.1 Production Sequence

The confirmed process is:

`CUTTING → SEWING → QC`

- QC `PASS` → `FINISHING`
- QC `FAIL` → `REWORK` → QC again

After successful production:

`FINISHING → PACKING → DELIVERY / CUSTOMER PICKUP → FULFILLMENT CONFIRMED → COMPLETED`

Rules:

1. Cutting precedes Sewing.
2. Sewing precedes QC.
3. QC occurs after Sewing and before Finishing.
4. QC `PASS` allows the process to proceed to Finishing.
5. QC `FAIL` requires Rework, followed by another QC inspection.
6. Rework and QC may repeat according to inspection results.
7. Finishing follows successful QC.
8. Packing follows Finishing.
9. Delivery or Customer Pickup follows Packing.
10. Finishing completion alone does not mean the order is completed.
11. An order becomes `COMPLETED` only after required production and fulfillment conditions are satisfied.
12. PPIC confirms that the required fulfillment completion condition has been met before the order becomes `COMPLETED`.

### 9.2 Internal Duration Baseline

| Process                    |      Internal Duration |
| -------------------------- | ---------------------: |
| Cutting                    |                 2 days |
| Sewing                     |                 5 days |
| Finishing                  |                 2 days |
| **Internal Baseline**      |             **9 days** |
| **Customer-facing Target** | **10–14 working days** |

Rules:

1. The 9-day internal baseline consists only of Cutting, Sewing, and Finishing.
2. QC and Rework are excluded from the 9-day baseline.
3. Do not add QC or Rework duration without a new business decision.
4. The customer-facing target is 10–14 working days.
5. Internal baseline and customer-facing target are separate measurements.
6. Sewing duration must not be treated as the total Mass Production duration.
7. Whether QC and Rework are included in the customer-facing target remains `UNDECIDED`.

### 9.3 QC, Rework, and Fulfillment Constraints

| Item                                 | Status      |
| ------------------------------------ | ----------- |
| QC after Sewing and before Finishing | `CONFIRMED` |
| Rework after QC failure              | `CONFIRMED` |
| QC duration                          | `UNKNOWN`   |
| Rework duration                      | `UNKNOWN`   |
| QC inspection coverage               | `UNDECIDED` |
| Rework limit and escalation policy   | `UNDECIDED` |
| QC progress measurement              | `UNDECIDED` |
| Rework progress measurement          | `UNDECIDED` |
| Packing duration                     | `UNKNOWN`   |
| Delivery / Customer Pickup duration  | `UNKNOWN`   |
| Fulfillment completion evidence      | `UNDECIDED` |
| Packing and delivery responsibility  | `UNDECIDED` |

Do not assume inspection coverage, fixed QC/Rework durations, rework limits, or escalation policies.

Do not mark an order `COMPLETED` merely because Finishing reaches 100%.

---

## 10. Order Change Request

**Status: CONFIRMED for the high-level process**

Rules:

1. Before LoA approval, Admin may edit order details within the permitted scope.
2. After LoA approval, changes to locked specifications require an Order Change Request.
3. Admin submits the request with a reason and proposed changes.
4. Owner approves or rejects the request.
5. Approved changes are applied while preserving the previous order version.
6. Approved changes before Mass Production starts trigger revalidation of affected Production Readiness requirements and Functional Layout.
7. Revalidation must identify previously satisfied requirements affected by the change.
8. Normal specification changes are prohibited after Mass Production starts.
9. Mass Production starts when Cutting actually begins.
10. Decisions, changes, and order versions must remain traceable.
11. An approved change must not bypass applicable readiness or layout revalidation.

The following remain `UNDECIDED`:

- Complete Change Request status model.
- Multiple pending requests for one order.
- Request withdrawal and revision handling.
- Whether Owner may request revision.
- Exact fields eligible for change.
- Administrative corrections outside specification changes.
- Detailed approval and rejection transitions.
- Handling requests during PPIC Preparation or Functional Layout preparation.
- Order cancellation while a request is pending.

Do not expose a normal order-update operation that bypasses Change Request requirements after LoA approval.

---

## 11. Timeline and Duration

**Status: CONFIRMED for required tracking capabilities**

The system must distinguish and track:

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

1. Planned and actual dates must remain separate.
2. Planned and actual durations must remain separate.
3. Internal duration and customer-facing target must remain separate.
4. Actual dates must not overwrite planned dates.
5. The original planned completion date must be preserved.
6. A Delay Report must not silently overwrite the original planned completion date.
7. Estimated completion may change only through an authorized business process.
8. Actual completion date is recorded when the order is completed.
9. Calendar days and working days must not be treated as interchangeable.
10. Deadline, overdue, and delay calculations must follow approved rules.

### 11.1 Working-Day Calendar

**Status: CONFIRMED — TEMPORARY**

Temporary calendar assumption:

- Monday–Friday: working days.
- Saturday–Sunday: non-working days.

National holidays, company holidays, exceptional working days, and permanent calendar configuration remain `UNKNOWN`.

Do not treat this temporary assumption as a permanent holiday policy.

### 11.2 Delay and Overdue

Rules:

1. A Delay Report does not automatically make an order `OVERDUE`.
2. Overdue status depends on an approved deadline and overdue calculation rule.
3. Original planned completion and estimated completion must remain distinguishable.
4. Multiple Delay Report aggregation and overlap handling remain `UNDECIDED`.
5. Whether overdue is calculated against the original planned date or adjusted estimated date remains `UNDECIDED`.

---

## 12. Production Tracking and Progress

### 12.1 Stage Progress

**Status: CONFIRMED**

PPIC manually submits percentage-based progress for:

- Cutting.
- Sewing.
- Finishing.

Rules:

1. PPIC is responsible for stage progress submissions.
2. Production Staff must not submit progress percentages in Phase 1.
3. Every submission preserves its timestamp and submitting user.
4. Historical progress updates must not be overwritten.
5. A new submission must be distinguishable from the absence of an update.
6. An unchanged percentage submitted again must still be recorded as a new update.
7. Percentage values are not restricted to fixed increments.
8. QC and Rework are operationally monitored, but their percentage measurement remains `UNDECIDED`.
9. PPIC progress records are the authoritative source for percentage-based stage progress.

### 12.2 Overall Progress

Rules:

1. Overall order progress is derived from stage progress records.
2. Overall progress formula and stage weighting remain `UNDECIDED`.
3. Do not assume an arithmetic average or invent stage weights.
4. Functional Layout completion must not be treated as Mass Production progress.
5. QC and Rework must not receive percentage-progress mechanics until approved.

### 12.3 Planned vs Actual Progress

The system must distinguish planned and actual dates, durations, and PPIC-submitted stage progress.

A planned progress schedule and its comparison with reported progress remain `UNDECIDED`.

### 12.4 Production Quantity

1. Order size quantities define the ordered quantity.
2. Actual-piece production tracking is outside Phase 1.
3. Target vs actual quantity, reject quantity, and rework quantity are not confirmed Phase 1 requirements.
4. These quantities must not be mandatory progress inputs.
5. Future quantity-based tracking requires a separate business decision.

### 12.5 Current Process and Bottleneck

- **Current Process:** production process currently being executed.
- **Bottleneck:** process or constraint restricting production throughput.

These terms are not interchangeable. A bottleneck must not be inferred solely from the current process.

---

## 13. Delay Report

**Status: CONFIRMED for the Phase 1 reporting workflow**

A Delay Report records a reported delay or condition that may affect the production timeline.

Required information:

- Related order number.
- Affected production stage.
- Reason.
- Chronology or explanation.
- Estimated additional duration in working days.

Rules:

1. PPIC creates and manages Delay Reports.
2. A new report starts with status `OPEN`.
3. PPIC follows up and may change the status to `RESOLVED`.
4. Creating and resolving a report initiates email dispatch for configured recipients.
5. Intended recipients are PPIC, Owner, and Customer.
6. Customer email availability and whether it is mandatory remain `UNDECIDED`.
7. Delay Report status history must be retained.
8. Email dispatch attempts and delivery outcomes must be traceable.
9. Email delivery failure must not delete or invalidate the report.
10. PPIC does not require Owner approval to create or update a Delay Report.
11. Estimated additional duration is recorded in working days.
12. A Delay Report does not automatically update estimated completion or set an order to `OVERDUE`.
13. A Delay Report is distinct from a Production Issue Report.

Supported statuses:

- `OPEN`
- `RESOLVED`

Do not introduce additional statuses without approval.

### 13.1 Delay Calculation

1. Original planned completion date must be preserved.
2. Estimated completion may be updated only through a separately authorized process.
3. Creating or resolving a Delay Report does not automatically update estimated completion.
4. Actual completion date is recorded when the order is completed.
5. Multiple report aggregation, overlapping delays, and their effect on estimated completion remain `UNDECIDED`.
6. The authoritative basis for overdue calculation remains `UNDECIDED`.

A Delay Report is a formal delay record, not an automatic deadline adjustment.

---

## 14. Production Issue Report

**Status: CONFIRMED for the Phase 1 workflow**

A Production Issue Report records an operational issue encountered during production.

### 14.1 Report Information

The report includes:

- Related order.
- Department or production stage.
- Issue type.
- Description.
- Automatically recorded timestamp.
- Optional photo.

Issue categories remain `UNDECIDED`.

### 14.2 Workflow and Status

Workflow:

1. Production Staff submits an issue report.
2. PPIC receives an In-App Notification.
3. PPIC follows up and updates the report.
4. The reporting Staff member receives an In-App Notification when its status changes.
5. PPIC must provide a resolution note before setting status to `RESOLVED`.

Supported statuses:

- `OPEN`
- `IN_PROGRESS`
- `RESOLVED`

Rules:

1. Do not introduce additional statuses without approval.
2. Detailed transition rules remain `UNDECIDED`.
3. `IN_PROGRESS` is not assumed to be a mandatory intermediate state before `RESOLVED`.
4. Resolution notes and status changes must be visible to the reporting Staff member and Owner.
5. A Production Issue Report does not automatically create a Delay Report.
6. An operational issue does not automatically mean an order is delayed.

### 14.3 Access Rules

| Role             | Access                                    |
| ---------------- | ----------------------------------------- |
| OWNER            | Read-only access to all reports.          |
| ADMIN            | No access in Phase 1.                     |
| PPIC             | View and manage all reports.              |
| PRODUCTION_STAFF | Create reports and view own reports only. |

Production Staff must not view reports submitted by other Staff members.

### 14.4 Notifications

1. PPIC is notified when a new report is submitted.
2. The reporting Staff member is notified when report status changes.
3. Notification read state is maintained per user as `READ` / `UNREAD`.
4. Notification history must be retained according to the approved persistence design.

Issue escalation rules and additional categories remain `UNDECIDED`.

---

## 15. Notifications

**Status: CONFIRMED for the limited Phase 1 notification scope**

### 15.1 In-App Notifications

Confirmed events:

| Business Event                         | Recipient              |
| -------------------------------------- | ---------------------- |
| Production Issue Report created        | PPIC                   |
| Production Issue Report status changed | Reporting Staff member |

Rules:

1. Notification read state is per user.
2. Duplicate notifications for the same business event and recipient must be prevented.
3. Notifications must not be generated merely because a stage percentage is below 100%.
4. Recipients must follow approved event and role permissions.

### 15.2 Delay Report Email

Delay Report emails are separate from In-App Notifications.

| Event                              | Intended Recipients   |
| ---------------------------------- | --------------------- |
| Delay Report created (`OPEN`)      | PPIC, Owner, Customer |
| Delay Report resolved (`RESOLVED`) | PPIC, Owner, Customer |

Rules:

1. Each event initiates email dispatch for configured recipients.
2. Exact recipient mapping and per-recipient dispatch conditions remain `UNDECIDED`.
3. Customer email availability and whether it is mandatory remain `UNDECIDED`.
4. Dispatch attempts and delivery outcomes must be traceable.
5. Dispatch initiation must not be treated as successful delivery.
6. Email delivery failure must not invalidate the business report.

### 15.3 Candidate Notification Events

The following are candidates, not confirmed active Phase 1 notifications:

- `SAMPLE_APPROVED`
- `LOA_APPROVED`
- `PRODUCTION_WAITING`
- `PRODUCTION_READY`
- `PRODUCTION_STARTED`
- `PRODUCTION_COMPLETED`
- `PRODUCTION_SHORTAGE`
- `PRODUCTION_DELAYED`
- `DEADLINE_WARNING`

Their recipients, channels, triggers, and duplicate-prevention rules remain `UNDECIDED`.

Sampling notifications are not part of the confirmed active In-App Notification scope. Whether they are enabled and their recipients and delivery behavior remain `UNDECIDED`.

Do not activate candidate events without explicit approval.

---

## 16. Validation and Access Control

**Status: CONFIRMED for backend authority and explicitly established constraints**

### 16.1 Validation Authority

1. Backend is authoritative for business validation.
2. Frontend validation improves user experience but does not replace backend validation.
3. Workflow gates and unauthorized actions must be enforced by the backend.
4. Validation must not introduce unapproved business constraints.

### 16.2 Confirmed Order Validation

- Customer Name must not be empty.
- Customer Phone Number is required.
- Customer Origin / Address is required.
- Production Type must be `CMT` or `FOB`.
- Size quantity must not be negative.
- Calculated total quantity must equal the sum of recorded size quantities.

Unresolved validation rules:

| Rule                                 | Status                         |
| ------------------------------------ | ------------------------------ |
| Phone number format                  | `UNDECIDED`                    |
| Minimum one reference image          | `UNDECIDED`                    |
| Maximum image count and file size    | `UNDECIDED` / technical policy |
| Multiple product models per order    | `UNDECIDED`                    |
| Custom sizes                         | `UNDECIDED`                    |
| Zero quantity for an individual size | `UNDECIDED`                    |
| Minimum total order quantity         | `UNDECIDED`                    |

### 16.3 Roles

Phase 1 roles:

- `OWNER`
- `ADMIN`
- `PPIC`
- `PRODUCTION_STAFF`

Rules:

1. Access follows role-based permissions.
2. Backend authorization is mandatory; frontend visibility is not authorization.
3. Customers do not have login access in Phase 1.
4. Each role may perform only explicitly permitted actions.
5. Unspecified permissions remain `UNDECIDED`.

### 16.4 Confirmed Access Constraints

| Capability                           | OWNER     | ADMIN | PPIC        | PRODUCTION_STAFF |
| ------------------------------------ | --------- | ----- | ----------- | ---------------- |
| Owner dashboard                      | Yes       | No    | No          | No               |
| Customer and order intake            | No        | Yes   | No          | No               |
| Record received payments             | No        | Yes   | No          | No               |
| Approve/reject payment corrections   | Yes       | No    | No          | No               |
| Approve/reject Order Change Requests | Yes       | No    | No          | No               |
| Manage Production Readiness          | No        | No    | Yes         | No               |
| Prepare Functional Layout            | No        | No    | Yes         | No               |
| Submit stage progress                | No        | No    | Yes         | No               |
| Create/manage Delay Reports          | No        | No    | Yes         | No               |
| View all Production Issue Reports    | Read-only | No    | Yes         | No               |
| Create Production Issue Reports      | No        | No    | No          | Yes              |
| View own Production Issue Reports    | No        | No    | All reports | Yes              |
| View order-status audit history      | Yes       | No    | No          | No               |

Additional Staff visibility constraints:

- Production Staff may access orders currently `IN_PROGRESS` within the relevant department.
- Staff visibility is subject to approved order assignment and department rules.
- Staff assignment and department visibility details remain `UNDECIDED`.

The access table records established constraints; it does not grant unlisted permissions.

---

## 17. Dashboard and Monitoring

**Status: CONFIRMED for the dashboard scope defined by the PRD**

Dashboard metrics must use authoritative business records and must not create independent business truth.

### 17.1 Owner Dashboard

Includes:

- Total Order Value for the selected period.
- Revenue Received for the selected period.
- New Orders count based on order creation date.
- Current overdue orders.
- Current `OPEN` Delay Reports.
- Production progress summary.
- Active-order details grouped by customer.
- Read-only access to all Production Issue Reports.
- Order-status audit history.

Financial rules:

1. Total Order Value and Revenue Received are separate metrics.
2. Revenue Received uses payments actually received within the selected period.
3. Total Order Value must not be derived from received payment records.
4. Total Order Value source, date basis, definition, calculation, and currency remain `UNDECIDED`.
5. Financial period semantics and unspecified aggregation details remain `UNDECIDED`.

Consumer growth metrics are excluded.

### 17.2 Admin Dashboard

Includes:

- Incoming orders.
- Sampling and approval status.
- LoA status.
- Payment recording and monitoring.
- Customer data.
- Order data.

Admin has no access to Production Issue Reports in Phase 1.

### 17.3 PPIC Dashboard

Includes:

- Orders awaiting Production Readiness.
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

The no-progress-update threshold remains `UNDECIDED`.

### 17.4 Production Staff Dashboard

Includes only:

- Orders currently `IN_PROGRESS` within the relevant department.
- Product details.
- Relevant Functional Layout information.
- Target completion information.
- Work instructions.
- Production Issue Report creation.
- The Staff member's own Production Issue Reports.

Production Staff must not submit progress percentages.

### 17.5 Dashboard Calculation Rules

1. Order data comes from authoritative Order records.
2. Stage progress comes from PPIC-submitted progress records.
3. Timeline data distinguishes planned and actual values.
4. Production status reflects the current business workflow state.
5. Overall progress formula and stage weighting remain `UNDECIDED`.
6. Dashboard period and aggregation details remain `UNDECIDED` unless explicitly defined above.
7. A Production Issue Report does not automatically mean an order is delayed.
8. A Delay Report does not automatically mean an order is overdue.

---

## 18. Audit History

**Status: CONFIRMED for audit principles; detailed coverage remains partially undecided**

Important business actions must preserve traceability.

Each audit record must identify:

- Actor.
- Timestamp.
- Action.
- Affected business entity.
- Relevant change information, where applicable.

Rules:

1. Historical progress updates must not be silently overwritten.
2. Audit history supplements current business records; it does not replace them.
3. Normal business operations must not silently modify or delete audit records.
4. Audit access follows role-based permissions.

Candidate audited activities:

- Order creation and updates.
- Order Change Requests and decisions.
- Sample / Look Approval changes.
- LoA status changes.
- Production Readiness and Waiting / On Hold changes.
- PPIC progress submissions.
- Production workflow changes.
- Payment recording and corrections.
- Important administrative actions.

Access:

1. Owner can view order-status change history.
2. Other roles must not receive order-status audit access in Phase 1.
3. Access to other audit categories remains `UNDECIDED` unless explicitly approved.

The complete audit action list and retention policy remain `UNDECIDED`.

---

## 19. Out of Scope for Phase 1

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
- Customer Portal and Customer Login.
- Native Mobile Application.
- Full Accounting.

Excluding Full Procurement Management does not exclude order-specific material purchasing required for FOB Production Readiness.

Phase 1 may track required material readiness without implementing a complete procurement module.

Excluded capabilities must not drive current implementation. Before introducing one in a future phase, perform a Domain Impact Analysis.

---

## 20. Open Business Decisions

The following decisions must remain unresolved until explicitly approved by the business.

| Item                                                                  | Status                  |
| --------------------------------------------------------------------- | ----------------------- |
| Phone number format                                                   | `UNDECIDED`             |
| Minimum reference image requirement                                   | `UNDECIDED`             |
| Multiple product models per order                                     | `UNDECIDED`             |
| Custom sizes                                                          | `UNDECIDED`             |
| Total quantity storage strategy                                       | `UNDECIDED`             |
| Zero quantity per size                                                | `UNDECIDED`             |
| Minimum total order quantity                                          | `UNDECIDED`             |
| Reason for 4-day internal vs 7-working-day sampling target            | `UNKNOWN`               |
| Complete Production Readiness checklist                               | `UNKNOWN`               |
| Complete waiting reason list and resume conditions                    | `UNDECIDED`             |
| Additional PPIC responsibilities                                      | `UNKNOWN`               |
| Functional Layout approval                                            | `UNDECIDED`             |
| QC inspection coverage and duration                                   | `UNDECIDED` / `UNKNOWN` |
| Rework duration, limit, and escalation policy                         | `UNKNOWN` / `UNDECIDED` |
| QC and Rework progress measurement                                    | `UNDECIDED`             |
| QC/Rework inclusion in customer-facing 10–14-day target               | `UNDECIDED`             |
| Packing and Delivery / Customer Pickup duration                       | `UNKNOWN`               |
| Fulfillment completion evidence                                       | `UNDECIDED`             |
| Packing and delivery responsibility                                   | `UNDECIDED`             |
| Permanent work calendar and holiday rules                             | `UNKNOWN`               |
| Overall order progress formula and stage weighting                    | `UNDECIDED`             |
| Planned vs actual progress calculation                                | `UNDECIDED`             |
| Delay aggregation and overlap handling                                | `UNDECIDED`             |
| Overdue calculation basis                                             | `UNDECIDED`             |
| Production Issue categories and escalation rules                      | `UNDECIDED`             |
| Order Change Request state machine and detailed transitions           | `UNDECIDED`             |
| Multiple pending Change Requests, withdrawal, and revision handling   | `UNDECIDED`             |
| Change Request handling during PPIC Preparation or Layout preparation | `UNDECIDED`             |
| Order cancellation while a Change Request is pending                  | `UNDECIDED`             |
| Payment correction workflow and resubmission                          | `UNDECIDED`             |
| Payment cancellation workflow                                         | `UNDECIDED`             |
| Payment methods and reference requirements                            | `UNDECIDED`             |
| Payment status model                                                  | `UNDECIDED`             |
| Whether DP is a production prerequisite                               | `UNDECIDED`             |
| Detailed role permissions not specified above                         | `UNDECIDED`             |
| Production Staff assignment and department visibility                 | `UNDECIDED`             |
| Dashboard period and aggregation details                              | `UNDECIDED`             |
| No-progress-update threshold                                          | `UNDECIDED`             |
| Additional notification events and recipients                         | `UNDECIDED`             |
| Delay Report email recipient mapping and dispatch conditions          | `UNDECIDED`             |
| Customer email source and mandatory requirement                       | `UNDECIDED`             |
| Customer information snapshot strategy at order creation              | `UNDECIDED`             |
| Sampling execution responsibility                                     | `UNDECIDED`             |
| Sample / Look Approval responsibility                                 | `UNDECIDED`             |
| LoA authorization responsibility                                      | `UNDECIDED`             |
| Complete audit action list, retention, and additional visibility      | `UNDECIDED`             |
| Total Order Value source, definition, calculation, and currency       | `UNDECIDED`             |
| Sampling notification activation and delivery behavior                | `UNDECIDED`             |
| Sample rejection consequences and resubmission rules                  | `UNDECIDED`             |
| LoA rejection consequences and detailed approval transitions          | `UNDECIDED`             |
| Reason for 9-day internal vs 10–14-working-day production target      | `UNKNOWN`               |

---

## 21. Implementation Principles

Business rules define business constraints, not implementation shortcuts.

Before implementing a rule:

1. Verify its requirement status.
2. Identify affected domains, workflow stages, and business gates.
3. Identify dependencies and side effects.
4. Confirm unresolved decisions with the business.
5. Update this document when an approved decision changes.
6. Enforce confirmed rules through backend validation and domain behavior.
7. Test confirmed rules, authorization boundaries, and prohibited workflow transitions.

Never silently convert `UNKNOWN`, `UNDECIDED`, or `PROPOSED` requirements into application behavior.

If implementation depends on an unresolved business decision, request clarification or implement only the independent portions.