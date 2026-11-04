
# Business Workflow

**Product:** Garment ERP Core  
**Scope:** Phase 1 — CMT and FOB Production  
**Document Status:** Draft  
**Related Documents:** `PRD.md`, `BUSINESS_RULES.md`, `USER_ROLES.md`, `GLOSSARY.md`

This document describes the Phase 1 business process for garment order handling, sampling, commercial approval, production preparation, mass production, fulfillment, and monitoring.

It defines the high-level process sequence, business gates, and confirmed workflow responsibilities.

Detailed business constraints are maintained in `BUSINESS_RULES.md`.

Do not assume state transitions or exception-handling behavior that has not been confirmed by the business.

---

## 1. Main Order Workflow

**Status: CONFIRMED**

The main Phase 1 order workflow is:

```text
ORDER
  |
  v
SAMPLING
  |
  v
SAMPLE / LOOK APPROVAL
  |
  v
LoA
  |
  v
PRODUCTION READINESS
  |
  +---- NOT READY ----> WAITING / ON HOLD
  |                         |
  |                         v
  +<--------------- READINESS RE-EVALUATION
  |
  v
PPIC PREPARATION
  |
  v
FUNCTIONAL LAYOUT
  |
  v
MASS PRODUCTION
  |
  v
PACKING
  |
  v
DELIVERY / CUSTOMER PICKUP
  |
  v
COMPLETED
```

Notes:

1. Waiting / On Hold is conditional, not mandatory for every order.
2. An order may proceed to PPIC Preparation only after required readiness conditions are satisfied.
3. Sample approval and LoA approval are separate business gates.
4. LoA approval does not automatically mean production is ready.
5. Mass Production must follow its defined process sequence.
6. Mass Production completion and Order completion are different conditions.
7. Required fulfillment processes must be completed before the order reaches `COMPLETED`.

The detailed transitions for rejection, cancellation, and exceptional cases remain subject to the decisions listed in Section 16.

---

## 2. Stage Summary

| Stage | Responsible Role | What Happens | Internal Duration | Customer-facing Target |
|---|---|---|---:|---:|
| Order | Admin | Customer, product references, size quantities, and production type are recorded. | N/A | N/A |
| Sampling | Admin / relevant production personnel | Pattern preparation followed by sample production. | 4 days | 7 working days |
| Sample / Look Approval | Authorized reviewer | Sample is reviewed and its approval status is recorded. | N/A | N/A |
| LoA | Admin / authorized parties | Commercial agreement, MoU, payment agreement, and payment terms are processed. | N/A | N/A |
| Production Readiness | PPIC | Required material and operational conditions are evaluated. | UNKNOWN | N/A |
| Waiting / On Hold | PPIC | Order waits because one or more readiness conditions are not satisfied. | UNKNOWN | UNKNOWN |
| PPIC Preparation | PPIC | Production preparation is performed. | UNKNOWN | N/A |
| Functional Layout | PPIC | Functional production zones and work arrangements are prepared. | UNKNOWN | N/A |
| Mass Production | PPIC / Production Staff | Cutting, Sewing, QC, Rework if needed, and Finishing. | 9-day baseline | 10–14 working days |
| Packing | Relevant production personnel | Finished products are prepared for fulfillment. | UNKNOWN | UNKNOWN |
| Delivery / Customer Pickup | Relevant responsible personnel | Products are delivered or collected by the customer. | UNKNOWN | UNKNOWN |
| Completed | PPIC confirms completion | Required production and fulfillment processes are confirmed complete. | N/A | N/A |

Internal durations and customer-facing targets are different measurements.

The 9-day Mass Production baseline includes Cutting, Sewing, and Finishing only. QC and Rework are not included in that calculation.

---

## 3. Order Intake Workflow

**Status: CONFIRMED**

Admin creates an order using the confirmed order information:

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Product Reference Images.
- Size quantities.
- Production Type: CMT or FOB.

Order quantity is represented through size-based quantities.

Example:

```text
S  = 20
M  = 40
L  = 30
XL = 10
-------------
Total = 100
```

The total quantity is the sum of the size quantities.

The following decisions remain unresolved:

- Whether at least one reference image is mandatory.
- Whether an order may contain multiple product models.
- Whether custom sizes are supported.
- Whether total quantity is stored, calculated, or both.

After order intake, the order enters Sampling.

---

## 4. Sampling Workflow

**Status: CONFIRMED**

Sampling consists of two internal activities:

```text
PATTERN
  |
  | 2 internal days
  v
SAMPLE PRODUCTION
  |
  | 2 internal days
  v
SAMPLING COMPLETED
```

Duration reference:

| Measurement | Duration |
|---|---:|
| Pattern | 2 internal days |
| Sample production | 2 internal days |
| Internal total | 4 days |
| Customer-facing target | 7 working days |

Rules:

1. Pattern preparation precedes sample production.
2. The internal duration reference is 4 days.
3. The customer-facing target is 7 working days.
4. The two measurements must remain separate.
5. Sampling completion leads to Sample / Look Approval.

Sampling notifications are a candidate notification requirement
intended for Owner only if enabled.

They are not part of the confirmed Phase 1 In-App Notification scope.
Their channel, activation, configuration, and delivery behavior
remain subject to explicit approval.

The reason for the difference between the internal duration and customer-facing target is `UNKNOWN`.

---

## 5. Sample / Look Approval Workflow

**Status: CONFIRMED for supported states and approval gate**

Supported states:

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

High-level workflow:

```text
SAMPLE / LOOK SUBMITTED
          |
          v
        PENDING
          |
     REVIEW RESULT
          |
    +-----+------+---------+
    |            |         |
    v            v         v
 REVISION     APPROVED   REJECTED
    |            |         |
    v            v         v
 SAMPLE       PROCEED    CONSEQUENCE
 REVISION     TO LoA     UNDECIDED
    |
    v
 RESUBMISSION
```

Rules:

1. Only `APPROVED` satisfies the Sample Approval Gate.
2. `REVISION` does not satisfy the gate.
3. `REJECTED` does not automatically mean permanent order cancellation.
4. A rejected sample must not automatically proceed to LoA.
5. Revision and resubmission behavior must not be expanded beyond approved business decisions.

The detailed transition table, rejection consequences, and resubmission limits remain `UNDECIDED`.

---

## 6. LoA Workflow

**Status: CONFIRMED for the commercial gate**

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

High-level workflow:

```text
SAMPLE APPROVED
      |
      v
LoA PREPARATION
      |
      v
    PENDING
      |
  REVIEW RESULT
      |
 +----+------+---------+
 |           |         |
 v           v         v
REVISION   APPROVED  REJECTED
 |           |         |
 v           v         v
REVISE    PROTECTED  CONSEQUENCE
LoA       ORDER      UNDECIDED
          DETAILS    
          LOCKED
             |
             v
     PRODUCTION READINESS
```

Rules:

1. Sample approval allows progression to the LoA stage.
2. LoA approval is required before production readiness evaluation.
3. LoA approval locks protected order details against direct editing. Other editable fields, if any, must follow explicitly defined permissions.
4. LoA approval does not automatically make an order production-ready.
5. LoA rejection does not automatically mean permanent order cancellation.
6. Changes to locked order details require an Order Change Request.
7. Normal specification changes are not allowed after Cutting starts.

The detailed LoA rejection consequences and transition rules remain `UNDECIDED`.

---

## 7. Payment Recording Workflow

**Status: CONFIRMED for the Phase 1 recording scope**

Payment information is associated with the commercial agreement.

High-level process:

```text
PAYMENT TERMS DEFINED IN LoA
             |
             v
       PAYMENT RECEIVED
             |
             v
       ADMIN RECORDS
          PAYMENT
             |
             v
     PAYMENT RECORD SAVED
             |
             v
      OWNER MONITORS
     RECEIVED PAYMENTS
```

Rules:

1. Admin records DP and subsequent payments actually received.
2. Recording a payment must not imply that an expected payment has been received.
3. Owner monitors received payment information.
4. Payment correction requires Owner approval or rejection.
5. Original payment data and correction history must remain traceable.
6. Duplicate transactions must not be counted more than once in payment metrics.
7. Full accounting is outside Phase 1.

Detailed payment status transitions, cancellation, and correction request behavior remain `UNDECIDED`.

---

## 8. Production Readiness and Waiting Workflow

**Status: CONFIRMED for the readiness gate and conditional Waiting flow**

Sample approval and LoA approval are necessary but insufficient conditions for production readiness.

```text
SAMPLE APPROVED
       |
       v
LOA APPROVED
       |
       v
PRODUCTION READINESS CHECK
       |
       +---- CONDITIONS SATISFIED
       |              |
       |              v
       |       PPIC PREPARATION
       |
       +---- CONDITIONS NOT SATISFIED
                      |
                      v
                WAITING / ON HOLD
                      |
                      v
             READINESS RE-EVALUATION
                      |
             +--------+--------+
             |                 |
             v                 v
       STILL NOT READY      READY
             |                 |
             v                 v
        REMAIN WAITING    PPIC PREPARATION
```

Potential readiness dependencies:

- Main material availability.
- Fabric availability.
- Material purchasing.
- Operational requirements.
- Other business-approved prerequisites.

Rules:

1. PPIC evaluates production readiness.
2. Readiness requirements depend on the order and production type.
3. If required conditions are not satisfied, the order may enter Waiting / On Hold.
4. The waiting reason must be recorded.
5. Readiness must be re-evaluated before the order proceeds.
6. The order must not proceed to PPIC Preparation while required readiness conditions remain unsatisfied.
7. Waiting / On Hold is not limited to fabric shortages.
8. An order must not automatically resume because a date has been reached.

Important distinctions:

- **Production Readiness** evaluates whether required conditions are satisfied.
- **Waiting / On Hold** represents the order waiting because one or more conditions are not satisfied.
- **PPIC Preparation** prepares production requirements after readiness is satisfied.
- **Functional Layout** arranges functional production work areas.

The complete readiness checklist and waiting reason list remain unresolved.

---

## 9. PPIC Preparation and Functional Layout

**Status: CONFIRMED for sequence and responsibility**

After production readiness is satisfied:

```text
PRODUCTION READY
       |
       v
PPIC PREPARATION
       |
       v
FUNCTIONAL LAYOUT
       |
       v
READY FOR MASS PRODUCTION
```

Rules:

1. PPIC is responsible for production preparation.
2. PPIC Preparation occurs after readiness is satisfied.
3. PPIC Preparation leads to Functional Layout preparation.
4. Functional Layout represents functional production zones or work arrangements.
5. Layout examples include Cutting, Sewing, Overlock, Embroidery, and Finishing.
6. Functional Layout is not a production process state.
7. Functional Layout must not be used as a substitute for the production workflow.

The following decisions remain `UNDECIDED` or `UNKNOWN`:

- Additional PPIC responsibilities.
- PPIC Preparation duration.
- Functional Layout approval requirement.
- Exact layout data and structure.

Do not assume that Functional Layout requires a separate approval.

---

## 10. Mass Production Workflow

**Status: CONFIRMED**

The confirmed Mass Production process sequence is:

```text
CUTTING
   |
   v
SEWING
   |
   v
QC
   |
   +---- PASS ----> FINISHING
   |
   +---- FAIL ----> REWORK
                       |
                       v
                       QC
```

The Rework → QC cycle may repeat according to inspection results. A rework limit or escalation policy has not been approved.

### 10.1 Process Duration

| Process | Internal Duration |
|---|---:|
| Cutting | 2 days |
| Sewing | 5 days |
| Finishing | 2 days |
| **Internal baseline** | **9 days** |
| **Customer-facing target** | **10–14 working days** |

The 9-day baseline consists only of:

```text
CUTTING  (2 days)
    +
SEWING  (5 days)
    +
FINISHING (2 days)
    =
9 DAYS
```

QC and Rework are not included in the baseline.

Do not add QC or Rework duration to the 9-day baseline without an approved business decision.

### 10.2 QC and Rework

Rules:

1. QC occurs after Sewing and before Finishing.
2. A QC pass allows progression to Finishing.
3. A QC failure may require Rework.
4. Rework is followed by another QC inspection.
5. QC duration is `UNKNOWN`.
6. Rework duration is `UNKNOWN`.
7. QC inspection coverage is `UNDECIDED`.
8. Rework limit and escalation policy are `UNDECIDED`.
9. QC and Rework progress measurement is `UNDECIDED`.

### 10.3 Stage Progress

PPIC manually submits percentage-based progress for confirmed
percentage-monitored production stages.

Confirmed percentage-monitored stages:

- Cutting.
- Sewing.
- Finishing.

QC and Rework are operationally monitored stages.
Their percentage-progress measurement method remains `UNDECIDED`.

Rules:

1. PPIC is responsible for submitting production progress updates.
2. Production Staff do not submit progress percentages in Phase 1.
3. Each update preserves its timestamp and submitting user.
4. Historical progress updates must not be overwritten.
5. An unchanged percentage submitted as a new update must still be recorded.
6. QC and Rework must not be assigned percentage-progress mechanics
   until their measurement method is approved.
7. Overall order progress is derived from applicable stage progress records.
8. The overall order progress formula and stage weighting are `UNDECIDED`.

### 10.4 Production Start

Mass Production is considered started when Cutting actually begins.

The start of PPIC Preparation, Functional Layout, or readiness evaluation does not by itself mean Mass Production has started.

---

## 11. Packing and Fulfillment Workflow

**Status: CONFIRMED for process sequence**

After Finishing:

```text
FINISHING COMPLETED
        |
        v
      PACKING
        |
        v
DELIVERY / CUSTOMER PICKUP
        |
        v
FULFILLMENT CONFIRMED
        |
        v
     COMPLETED
```

Rules:

1. Packing follows Finishing.
2. Delivery or Customer Pickup follows Packing.
3. Finishing completion alone does not mean the order is completed.
4. Required fulfillment processes must be completed before the order reaches `COMPLETED`.
5. PPIC confirms that the required fulfillment process has been completed before the order reaches COMPLETED. The personnel responsible for physically performing and recording, Packing, Delivery, or Customer Pickup remain subject to business confirmation.

The following details remain unresolved:

- Packing duration.
- Delivery / Customer Pickup duration.
- Required delivery or pickup evidence.
- Detailed fulfillment state transitions.
- Responsibility for recording delivery or pickup evidence.

Do not mark an order `COMPLETED` merely because Mass Production or Finishing has completed.

---

## 12. Order Change Request Workflow

**Status: CONFIRMED for the high-level approval and locking rules**

Order details become locked after LoA approval.

```text
LOA APPROVED
      |
      v
ORDER DETAILS LOCKED
      |
      v
CHANGE REQUIRED?
      |
 +----+----------------------+
 |                           |
 v                           v
NO                          YES
 |                           |
 v                           v
CONTINUE WORKFLOW     ORDER CHANGE REQUEST
                             |
                             v
                      ADMIN SUBMITS
                       CHANGE REQUEST
                             |
                             v
                       OWNER REVIEWS
                             |
                       +-----+-----+
                       |           |
                       v           v
                    APPROVE      REJECT
                       |           |
                       v           v
                 APPLY CHANGE   KEEP CURRENT
                       |         ORDER VERSION
                       v
                PRESERVE PREVIOUS
                    VERSION
                       |
                       v
             REVALIDATE AFFECTED
             READINESS / LAYOUT
                       |
                       v
                CONTINUE WORKFLOW
```

Rules:

1. Before LoA approval, Admin may edit order details directly within the permitted scope.
2. After LoA approval, changes to locked details require an Order Change Request.
3. Admin submits the request with a reason and proposed changes.
4. Owner approves or rejects the request.
5. Approved changes are applied while preserving the previous order version.
6. Affected production readiness requirements must be revalidated.
7. Affected Functional Layout information must be revalidated.
8. Normal specification changes are not allowed after Cutting has started.
9. Mass Production starts when Cutting actually begins.

The detailed Change Request state machine and exceptional cases remain `UNDECIDED`.

---

## 13. Delay Report Workflow

**Status: CONFIRMED for the Phase 1 reporting process**

A Delay Report records a reported delay or a condition that may affect the production timeline.

Required information:

- Order number.
- Affected stage.
- Reason.
- Chronology or explanation.
- Estimated additional duration in working days.

Workflow:

```
DELAY REPORT CREATED
        |
        v
       OPEN
        |
        v
EMAIL DISPATCH INITIATED
        |
        v
PPIC FOLLOWS UP
        |
        v
      RESOLVED
        |
        v
RESOLUTION EMAIL DISPATCH INITIATED
```

Rules:

1. PPIC creates the Delay Report.
2. The initial status is `OPEN`.
3. Intended email recipients include PPIC, Owner, and Customer, subject to confirmed recipient configuration and customer email availability.
4. Customer email source and whether it is mandatory remain UNDECIDED.
5. Email dispatch attempts and delivery outcomes must be recorded.
6. Successful email delivery must not be assumed merely because a dispatch attempt was initiated.
7. Status history must be preserved.
8. Email delivery information must be retained.
9. Email delivery failure must not delete the report.
10. A Delay Report does not automatically make an order overdue.
11. A Delay Report does not automatically change the original planned completion date.

### 13.1 Deadline and Estimate

The system must distinguish:

- Original planned completion date.
- Estimated completion date.
- Actual completion date.

Rules:

1. Original planned completion date must be preserved.
2. Estimated completion date may be updated separately.
3. Actual completion date is recorded when the order is completed.
4. Multiple Delay Report aggregation is `UNDECIDED`.
5. Overlapping Delay Report handling is `UNDECIDED`.
6. The authoritative overdue calculation basis is `UNDECIDED`.

Do not infer overdue status solely from the existence of a Delay Report.

---

## 14. Production Issue Report Workflow

**Status: CONFIRMED for the Phase 1 reporting process**

A Production Issue Report records an operational issue encountered during production.

### 14.1 Submission and Follow-up

```text
OPERATIONAL ISSUE
       |
       v
PRODUCTION STAFF
SUBMITS ISSUE REPORT
       |
       v
      OPEN
       |
       v
IN-APP NOTIFICATION
       TO PPIC
       |
       v
PPIC FOLLOWS UP
       |
       v
STATUS TRANSITION
ACCORDING TO APPROVED RULES
       |
       v
PPIC RECORDS
RESOLUTION NOTE
       |
       v
     RESOLVED
       |
       v
IN-APP NOTIFICATION
TO REPORTING STAFF
```

The exact transition rules between statuses must follow the approved status model. No additional statuses may be introduced without approval.

The transition requirements between OPEN, IN_PROGRESS, and RESOLVED remain UNDECIDED.

The system must not assume that IN_PROGRESS is mandatory before RESOLVED.

### 14.2 Report Information

The report includes:

- Related order.
- Department or production stage.
- Issue type.
- Description.
- Automatically recorded timestamp.
- Optional photo.

Issue categories remain `UNDECIDED`.

### 14.3 Statuses

- `OPEN`
- `IN_PROGRESS`
- `RESOLVED`

Rules:

1. Production Staff submits the report.
2. PPIC receives an In-App Notification.
3. PPIC follows up and updates the status.
4. PPIC must provide a resolution note before setting the status to `RESOLVED`.
5. The reporting Staff member receives an In-App Notification when the status changes.
6. Status and resolution information must be visible to the reporting Staff member and Owner.

### 14.4 Role Access

| Role | Workflow Permission |
|---|---|
| OWNER | Read-only access to all Production Issue Reports. |
| ADMIN | No access in Phase 1. |
| PPIC | View all reports, follow up, update status, and resolve reports. |
| PRODUCTION_STAFF | Create reports and view own reports only. |

Production Staff must not view reports submitted by other Staff members.

### 14.5 Distinction from Delay Report

A Production Issue Report and a Delay Report are separate records and processes.

- Production Issue Report records an operational issue.
- Delay Report records a reported delay or condition affecting the production timeline.
- An operational issue does not automatically create a Delay Report.
- A Production Issue Report does not automatically make an order delayed or overdue.

The business relationship between an issue and a delay must be evaluated rather than assumed.

---

## 15. Notification Workflow

**Status: CONFIRMED for the limited Phase 1 notification scope**

### 15.1 In-App Notifications

In-App Notifications in Phase 1 are limited to Production Issue Reports.

```text
NEW ISSUE REPORT
       |
       v
NOTIFY PPIC
       |
       v
PPIC UPDATES STATUS
       |
       v
NOTIFY REPORTING STAFF
```

Rules:

1. PPIC receives an In-App Notification when a new Production Issue Report is submitted.
2. Reporting Staff receives an In-App Notification when the report status changes.
3. Notification read state is maintained per user.
4. Duplicate notifications for the same event and recipient must be prevented.

### 15.2 Delay Report Email

Delay Report emails are separate from In-App Notifications.

```text
DELAY REPORT CREATED
       |
       v
EMAIL PPIC, OWNER,
AND CUSTOMER

DELAY REPORT RESOLVED
       |
       v
EMAIL PPIC, OWNER,
AND CUSTOMER
```

Email delivery information must be retained. Email failure must not invalidate the Delay Report.

### 15.3 Other Events

The following are candidate business events, not automatically active Phase 1 notifications:

- `SAMPLE_APPROVED`
- `LOA_APPROVED`
- `PRODUCTION_WAITING`
- `PRODUCTION_READY`
- `PRODUCTION_STARTED`
- `PRODUCTION_COMPLETED`
- `PRODUCTION_SHORTAGE`
- `PRODUCTION_DELAYED`
- `DEADLINE_WARNING`

Their recipients, channels, trigger conditions, and duplicate-prevention behavior remain `UNDECIDED` unless separately approved.

Sampling notifications are intended for Owner only in Phase 1 if enabled.

Do not implement candidate events as active notifications without approval.

---

## 16. Workflow Gates

The following gates control progression through the business process.

| Gate | Requirement | Allows |
|---|---|---|
| Sample Approval Gate | Sample / Look Approval must be `APPROVED`. | Progression to LoA. |
| LoA Gate | LoA must be `APPROVED`. | Production Readiness evaluation. |
| Production Readiness Gate | Required readiness conditions must be satisfied. | PPIC Preparation. |
| Mass Production Start | Cutting actually begins. | Marks Mass Production as started and locks normal specification changes. |
| QC Gate | QC must pass before Finishing. | Progression to Finishing. |
| Production Completion Gate | Cutting, Sewing, QC requirements, Rework if needed, and Finishing must be completed. | Progression to Packing. |
| Fulfillment Gate | Packing and Delivery / Customer Pickup requirements must be completed. | Order may become `COMPLETED`. |

These gates define business requirements, not the complete technical state-transition implementation.

---

## 17. State Machines and Transition Design

Detailed state machines and transition tables must be maintained separately from this high-level workflow.

| Area | Required Design |
|---|---|
| Sample / Look Approval | States are confirmed; detailed transitions and rejection consequences remain undecided. |
| LoA | States are confirmed; detailed transitions and rejection consequences remain undecided. |
| Production Readiness / Waiting | Readiness gate is confirmed; full checklist and resume rules remain unresolved. |
| Order Change Request | High-level approval and locking rules are confirmed; detailed state machine remains undecided. |
| Production Process Sequence | Cutting → Sewing → QC → Rework if failed → QC → Finishing is confirmed. |
| Fulfillment / Order Completion | Sequence is confirmed; detailed evidence and state transitions remain unresolved. |
| Delay Report | `OPEN` and `RESOLVED` are confirmed. |
| Production Issue Report | `OPEN`, `IN_PROGRESS`, and `RESOLVED` are confirmed. |

Do not assume that:

- `REJECTED` always means `CANCELLED`.
- Waiting automatically resumes when a date is reached.
- `PRODUCTION_COMPLETED` means `ORDER_COMPLETED`.
- A reported delay automatically changes the order to `OVERDUE`.
- A Production Issue Report automatically creates a Delay Report.
- A stage reaching 100% automatically completes the entire order.

Detailed transition tables and exception flows belong in:

```text
docs/diagrams/business/
```

---

## 18. Timeline and Progress Tracking

The system must distinguish:

- Planned Start Date vs Actual Start Date.
- Planned End Date vs Actual End Date.
- Planned Duration vs Actual Duration.
- Internal Duration vs Customer-facing Target.
- Current Process vs Bottleneck.
- Production Delay vs Production Issue.
- Original Planned Completion Date vs Estimated Completion Date vs Actual Completion Date.

### 18.1 Stage Progress

1. PPIC manually submits percentage-based stage progress.
2. Each update preserves its timestamp and submitting user.
3. Historical progress updates must remain available.
4. Production Staff submit operational issue reports, not progress percentages.
5. Overall order progress is derived from stage progress records.
6. The overall order progress formula is `UNDECIDED`.
7. Stage weighting is `UNDECIDED`.

### 18.2 Production Quantity

1. Size quantities recorded in the order define the ordered quantity.
2. Phase 1 does not include actual-piece production tracking.
3. Target vs actual quantity comparison is not a confirmed Phase 1 feature.
4. Reject and Rework quantity tracking are not confirmed Phase 1 requirements.
5. These quantities must not be mandatory production progress inputs.

### 18.3 Work Calendar

The temporary work calendar assumes Monday to Friday as working days and excludes Saturday and Sunday.

**Status: CONFIRMED — TEMPORARY**

National holidays and company holidays are `UNKNOWN`.

Do not treat the temporary calendar as a permanent holiday policy.

---

## 19. Dashboard Workflow

Dashboard data is derived from authoritative business records.

```text
BUSINESS RECORDS
       |
       v
PRODUCTION TRACKING DATA
       |
       v
DASHBOARD AGGREGATION
       |
       v
ROLE-BASED DASHBOARD
```

### 19.1 Owner

Important metric distinctions:

- Total Order Value represents the value of orders.
- Revenue Received represents payments actually received.
- These metrics must use separate authoritative data sources.
- Total Order Value must never be derived from received payments.
- Its source, definition, calculation, and currency remain UNDECIDED.

Owner dashboard displays:

- Total Order Value for the selected period.
- Revenue Received from payments actually received during the period.
- New Orders based on order creation date.
- Current overdue orders.
- Current `OPEN` Delay Reports.
- Production progress summary.
- Active-order details grouped by customer.
- Read-only access to all Production Issue Reports.
- Audit history of order-status changes.

Consumer growth is excluded.

### 19.2 Admin

Admin dashboard displays:

- Incoming orders.
- Sampling and approval status.
- LoA status.
- Payment recording and monitoring.
- Customer and order data.

Admin has no Production Issue Report access in Phase 1.

### 19.3 PPIC

PPIC dashboard displays:

- Orders awaiting readiness.
- Waiting / On Hold orders.
- PPIC Preparation.
- Functional Layout preparation.
- Percentage-based progress for Cutting, Sewing, and Finishing.
- Operational status of QC and Rework.
- Orders with no progress update.
- `OPEN` Delay Reports.
- All Production Issue Reports and follow-up.

### 19.4 Production Staff

Production Staff dashboard displays only:

- Orders currently `IN_PROGRESS` within the relevant department.
- Relevant product details.
- Relevant Functional Layout information.
- Target completion information.
- Work instructions.
- Issue report creation.
- Own Production Issue Reports.

Production Staff cannot submit progress percentages.

### 19.5 Dashboard Constraints

1. Dashboard visibility must respect RBAC.
2. Dashboard metrics must use authoritative business records.
3. Planned and actual values must remain distinguishable.
4. Stage progress must use PPIC-submitted progress records.
5. Overall order progress formula is `UNDECIDED`.
6. Dashboard period and aggregation details remain `UNDECIDED` where not specified.
7. A Production Issue Report does not automatically mean an order is delayed.
8. A Delay Report does not automatically mean an order is overdue.

---

## 20. Audit History Workflow

Important business actions must preserve traceability.

```text
BUSINESS ACTION
       |
       v
ACTOR + TIMESTAMP
       |
       v
ACTION + AFFECTED ENTITY
       |
       v
AUDIT HISTORY
```

Rules:

1. Audit records identify the actor and timestamp.
2. Audit records identify the action and affected business entity.
3. Relevant change information must be retained where applicable.
4. Historical progress updates must not be overwritten.
5. Audit records must not be silently modified or deleted through normal business operations.
6. Audit access must follow RBAC.
7. Owner has access to order-status change audit history.
8. Audit visibility for other categories remains subject to approved permissions.

The complete audit action list and retention policy remain `UNDECIDED`.

---

## 21. Open Workflow Decisions

| Item | Status |
|---|---|
| Sample rejection consequences | `UNDECIDED` |
| Sample revision and resubmission limits | `UNDECIDED` |
| LoA rejection consequences | `UNDECIDED` |
| Complete production readiness checklist | `UNKNOWN` |
| Waiting reason categories | `UNDECIDED` |
| Waiting resume conditions | `UNDECIDED` |
| PPIC Preparation duration | `UNKNOWN` |
| Functional Layout approval requirement | `UNDECIDED` |
| QC inspection coverage | `UNDECIDED` |
| QC duration | `UNKNOWN` |
| Rework duration | `UNKNOWN` |
| Rework limit and escalation policy | `UNDECIDED` |
| QC and Rework progress measurement | `UNDECIDED` |
| Packing duration | `UNKNOWN` |
| Delivery / Customer Pickup duration | `UNKNOWN` |
| Delivery / Customer Pickup completion evidence | `UNDECIDED` |
| Permanent work calendar and holiday rules | `UNKNOWN` |
| Overall order progress formula | `UNDECIDED` |
| Stage progress weighting | `UNDECIDED` |
| Planned vs actual progress calculation | `UNDECIDED` |
| Order Change Request detailed state machine | `UNDECIDED` |
| Payment correction detailed workflow | `UNDECIDED` |
| Payment cancellation workflow | `UNDECIDED` |
| Delay aggregation and overlap handling | `UNDECIDED` |
| Overdue calculation basis | `UNDECIDED` |
| Production Issue categories | `UNDECIDED` |
| Production Issue escalation rules | `UNDECIDED` |
| Production Staff assignment and department visibility | `UNDECIDED` |
| Dashboard period and aggregation details | `UNDECIDED` |
| No-progress-update threshold | `UNDECIDED` |
| Additional notification events and recipients | `UNDECIDED` |
| Complete audit action list | `UNDECIDED` |
| Audit retention policy | `UNDECIDED` |
| Audit visibility beyond order-status history | `UNDECIDED` |
| DP requirement as a prerequisite for Production Readiness or Mass Production | `UNDECIDED` |
| Customer email source and mandatory requirement | `UNDECIDED` |
| Actual responsibility for Sampling and Sample / Look Approval | `UNDECIDED` |
| LoA authorization responsibility | `UNDECIDED` |
| Packing execution responsibility | `UNDECIDED` |
| Delivery / Customer Pickup execution and evidence responsibility | `UNDECIDED` |
| Total Order Value source, definition, calculation, and currency | `UNDECIDED` |

---

## 22. Workflow Implementation Principles

This document describes the business process, not the technical implementation.

Before implementing a workflow:

1. Read the corresponding rules in `BUSINESS_RULES.md`.
2. Verify the requirement status of every relevant rule.
3. Identify business gates, states, transitions, and exception paths.
4. Identify unresolved business decisions.
5. Define a transition table before implementing a state machine.
6. Ensure backend authorization and validation enforce the workflow.
7. Preserve historical business records and audit information.
8. Update this document when the business approves a change.

Never introduce a workflow transition solely because it is convenient to implement.

If an unresolved decision blocks implementation, do not silently invent a transition. Isolate the affected behavior and request business clarification.
