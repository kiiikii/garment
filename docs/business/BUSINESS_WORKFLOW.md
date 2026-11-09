# BUSINESS WORKFLOW

# GARMENT ERP CORE

**Product:** Garment ERP Core
**Scope:** Phase 1 — CMT & FOB Production
**Version:** 1.0
**Status:** FINAL — PHASE 1 BASELINE
**Related Documents:** `PRD.md`, `BUSINESS_RULES.md`, `USER_ROLES.md`, `GLOSSARY.md`

---

# 1. DOCUMENT PURPOSE

This document defines the confirmed Phase 1 business processes, workflow sequences, approval gates, and operational responsibilities.

Documentation authority:

- `PRD.md` defines product requirements and scope.
- `BUSINESS_WORKFLOW.md` defines business process sequences and workflow progression.
- `BUSINESS_RULES.md` defines business constraints, validations, and operational rules.
- `USER_ROLES.md` defines permissions and role responsibilities.
- `GLOSSARY.md` defines business terminology.

This document describes WHAT happens in the business process, not HOW it is technically implemented.

Unconfirmed transitions and exceptional behavior must not be assumed.

---

# 2. MAIN ORDER WORKFLOW

**Status: CONFIRMED**

The main Phase 1 workflow is:

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
      |                           |
      |                           v
      |                   READINESS RE-EVALUATION
      |                           |
      +<--------------------------+
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
    CUTTING → SEWING → QC
                         |
                    +----+----+
                    |         |
                   PASS      FAIL
                    |         |
                    v         v
                FINISHING   REWORK
                    ^         |
                    |         v
                    +-------- QC
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

Core principles:

1. Sample Approval and LoA Approval are separate business gates.
2. LoA approval does not automatically establish Production Readiness.
3. Waiting / On Hold is conditional, not mandatory.
4. PPIC Preparation and Functional Layout are not Mass Production stages.
5. Mass Production officially starts when Cutting actually begins.
6. Mass Production completion and Order completion are separate conditions.
7. Fulfillment requirements must be satisfied before an order becomes COMPLETED.

Rejection, cancellation, and exceptional transitions remain subject to approved business decisions.

---

# 3. ORDER INTAKE

**Status: CONFIRMED**

Admin creates an order containing:

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Size Quantities.
- Production Type: CMT or FOB.
- Product Reference Images (supported, mandatory requirement UNDECIDED).

Order quantities are recorded individually by size.

Example:

    S  = 20
    M  = 40
    L  = 30
    XL = 10
    ----------
    Total = 100

Total quantity represents the sum of recorded size quantities when calculated.

Only CMT and FOB are supported in Phase 1.

After registration, the order proceeds to Sampling.

The following remain UNDECIDED:

- Mandatory image requirements.
- Multiple product models.
- Custom sizes.
- Total quantity storage strategy.

---

# 4. SAMPLING & SAMPLE / LOOK APPROVAL

**Status: CONFIRMED**

## 4.1 Sampling Process

Sampling consists of two sequential activities:

    PATTERN PREPARATION
          |
          | 2 internal days
          v
    SAMPLE PRODUCTION
          |
          | 2 internal days
          v
    SAMPLING COMPLETED
          |
          v
    SAMPLE / LOOK APPROVAL

Duration reference:

| Activity               |        Duration |
| ---------------------- | --------------: |
| Pattern Preparation    | 2 internal days |
| Sample Production      | 2 internal days |
| Internal Total         |          4 days |
| Customer-facing Target |  7 working days |

Internal duration and customer-facing target must remain separate.

The reason for their difference remains UNKNOWN.

## 4.2 Approval Workflow

Supported statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

### High-Level Approval Flow

    SAMPLE SUBMITTED
           |
           v
         PENDING
           |
           v
       REVIEW RESULT
           |
      +----+---------+
      |      |       |
      v      v       v

APPROVED REVISION REJECTED
| | |
v v v
PROCEED REVISION CONSEQUENCE
TO LoA HANDLING UNDECIDED
UNDECIDED

Rules:

1. Only APPROVED satisfies the Sample Approval Gate.
2. REVISION does not satisfy the approval gate.
3. REJECTED does not automatically mean permanent order cancellation.
4. Rejected samples cannot proceed to LoA automatically.
5. Revision and resubmission must follow approved business rules.
6. Detailed revision transitions must not be assumed.
7. Approval decisions must remain traceable.

Approval responsibility, rejection consequences, detailed transitions, and resubmission limits remain UNDECIDED.

### High-Level Approval Behavior

| Status   | Meaning                                                                                   |
| -------- | ----------------------------------------------------------------------------------------- |
| PENDING  | Awaiting review and approval decision.                                                    |
| REVISION | Revision has been requested. Detailed transition and resubmission rules remain UNDECIDED. |
| APPROVED | Sample / Look Approval has been granted.                                                  |
| REJECTED | Sample has been rejected. Consequences remain UNDECIDED.                                  |

          SAMPLE SUBMITTED
                |
                v
             PENDING
                |
                v
          REVIEW RESULT
                |
      +--------------+----------------------+
      |              |                      |
      v              v                      v
    REVISION      REJECTED               APPROVED
      |              |                      |
      v              v                      v
    RESUBMIT CONSEQUENCE UNDECIDED    PROCEED TO LoA

Rules:

1. Only APPROVED satisfies the Sample Approval Gate.
2. REVISION does not satisfy the approval gate.
3. REJECTED does not automatically mean permanent order cancellation.
4. Rejected samples cannot proceed to LoA automatically.
5. Revision and resubmission must follow approved business rules.
6. Detailed revision transitions must not be assumed.

Approval responsibility, rejection consequences, detailed transitions, and resubmission limits remain UNDECIDED.

---

# 5. LoA & PAYMENT WORKFLOW

**Status: CONFIRMED**

## 5.1 LoA Process

LoA represents the commercial agreement before production and includes:

- Letter of Agreement / MoU.
- Payment Agreement.
- Payment Terms.

Supported statuses:

- PENDING
- REVISION
- APPROVED
- REJECTED

### High-Level LoA Workflow

    SAMPLE APPROVED
          |
          v
     LoA PREPARATION
          |
          v
        PENDING
          |
          v
      REVIEW RESULT
          |
     +----+---------+
     |      |       |
     v      v       v

APPROVED REVISION REJECTED
| | |
v v v
LOCK REVISION CONSEQUENCE
ORDER HANDLING UNDECIDED
SPECS UNDECIDED
|
v
PRODUCTION READINESS

Rules:

1. Sample approval allows progression to LoA.
2. Only APPROVED satisfies the LoA Gate.
3. LoA approval is required before Production Readiness evaluation.
4. Approved LoA locks protected order specifications.
5. LoA approval does not automatically establish Production Readiness.
6. LoA approval does not automatically authorize Mass Production.
7. LoA rejection does not automatically mean permanent cancellation.
8. Changes to protected specifications require an Order Change Request.
9. Revision and rejection handling must follow approved business rules.

LoA authorization responsibility, rejection consequences, revision handling, and detailed approval transitions remain UNDECIDED.

## 5.2 Payment Recording

Payment recording is associated with the commercial agreement.

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
          PAYMENT SAVED
                |
                v
          OWNER MONITORS
         RECEIVED PAYMENTS

Rules:

1. Admin records DP and subsequent payments actually received.
2. Expected payments must not be represented as received payments.
3. Owner monitors received payment information.
4. Payment corrections require Owner approval or rejection.
5. Original payment records and correction history must remain traceable.
6. Duplicate transactions must not be counted more than once.
7. Payment recording does not automatically establish Production Readiness.
8. Full accounting and financial reconciliation are outside Phase 1.

Detailed payment correction, cancellation, and payment status transitions remain governed by `BUSINESS_RULES.md`.

---

# 6. PRODUCTION READINESS & WAITING

**Status: CONFIRMED**

Sample Approval and LoA Approval are necessary but insufficient conditions for Production Readiness.

## 6.1 Readiness Workflow

    SAMPLE APPROVED
          |
          v
      LoA APPROVED
          |
          v
    PRODUCTION READINESS CHECK
          |
      +---+----------------+
      |                    |
      v                    v
    READY               NOT READY
      |                    |
      v                    v
    PPIC PREPARATION   WAITING / ON HOLD
                           |
                           v
                  READINESS RE-EVALUATION
                           |
                      +----+----+
                      |         |
                      v         v
                 NOT READY    READY
                      |         |
                      v         v
                   WAITING   PPIC PREPARATION

Potential readiness dependencies:

- Main material availability.
- Fabric availability.
- Material purchasing.
- Operational requirements.
- Other approved prerequisites.

Rules:

1. PPIC evaluates Production Readiness.
2. Requirements depend on the order and production type.
3. Unsatisfied prerequisites may place an order in Waiting / On Hold.
4. Waiting reasons must be recorded.
5. Readiness must be re-evaluated before resuming.
6. Orders cannot enter PPIC Preparation while required conditions remain unmet.
7. Waiting is not limited to material shortages.
8. Orders must not resume automatically based only on a date.

Important distinctions:

| Term                 | Meaning                                         |
| -------------------- | ----------------------------------------------- |
| Production Readiness | Evaluation of required production conditions    |
| Waiting / On Hold    | Temporary state caused by unmet conditions      |
| PPIC Preparation     | Preparation after readiness is satisfied        |
| Functional Layout    | Arrangement of functional production work areas |

The complete readiness checklist, waiting categories, and resume conditions remain UNKNOWN / UNDECIDED.

---

# 7. PPIC PREPARATION & FUNCTIONAL LAYOUT

**Status: CONFIRMED**

After Production Readiness is satisfied:

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

Rules:

1. PPIC performs production preparation.
2. PPIC Preparation occurs after Production Readiness.
3. PPIC prepares the Functional Layout.
4. Functional Layout represents physical production work-area arrangements.
5. Layout examples include Cutting, Sewing, Overlock, Embroidery, and Finishing.
6. Functional Layout is not a production process stage.
7. Functional Layout must not contribute to Mass Production progress.
8. Functional Layout must not be confused with the production workflow.

Unresolved decisions:

- Additional PPIC responsibilities.
- PPIC Preparation duration.
- Functional Layout approval requirement.
- Layout data structure.

A separate Functional Layout approval gate must not be assumed.

---

# 8. MASS PRODUCTION

**Status: CONFIRMED**

## 8.1 Production Sequence

The confirmed production sequence is:

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

The Rework → QC cycle may repeat according to inspection results.

## 8.2 Duration Reference

| Process                |  Internal Duration |
| ---------------------- | -----------------: |
| Cutting                |             2 days |
| Sewing                 |             5 days |
| Finishing              |             2 days |
| Internal Baseline      |             9 days |
| Customer-facing Target | 10–14 working days |

The 9-day baseline includes Cutting, Sewing, and Finishing only.

QC and Rework are excluded.

Their durations remain UNKNOWN.

Do not include additional QC or Rework durations in the baseline without an approved business decision.

## 8.3 QC & Rework

Rules:

1. QC occurs after Sewing and before Finishing.
2. QC PASS allows progression to Finishing.
3. QC FAIL requires Rework.
4. Rework is followed by another QC inspection.
5. The Rework → QC cycle may repeat according to inspection results.
6. Finishing must not begin until the applicable QC inspection has resulted in PASS.
7. QC duration is UNKNOWN.
8. Rework duration is UNKNOWN.
9. QC inspection coverage is UNDECIDED.
10. Rework limits and escalation are UNDECIDED.
11. QC and Rework percentage-progress measurement is UNDECIDED.

QC and Rework must not receive invented progress mechanics, fixed durations, or unapproved inspection rules.

## 8.4 Stage Progress

PPIC manually submits percentage-based progress for:

- Cutting.
- Sewing.
- Finishing.

Rules:

1. Only PPIC submits production progress percentages.
2. Production Staff report operational issues, not percentage progress.
3. Every submission preserves timestamp and submitting user.
4. Historical progress submissions must not be overwritten.
5. Unchanged percentages submitted as new updates must still be recorded.
6. Missing updates must be distinguishable from unchanged submissions.
7. Overall order progress is derived from applicable stage progress records.
8. Overall progress formula and stage weighting remain UNDECIDED.

## 8.5 Production Start

Mass Production officially starts when Cutting actually begins.

PPIC Preparation, Functional Layout, and Production Readiness evaluation do not constitute Mass Production start.

---

# 9. PACKING & FULFILLMENT

**Status: CONFIRMED for process sequence**

After Finishing:

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

Rules:

1. Packing follows Finishing.
2. Delivery or Customer Pickup follows Packing.
3. Finishing completion alone does not mean the order is completed.
4. Required fulfillment processes must be completed before COMPLETED.
5. PPIC confirms fulfillment completion before the order reaches COMPLETED.
6. Physical execution and recording responsibilities remain subject to business confirmation.

Unresolved decisions:

- Packing duration.
- Delivery / Customer Pickup duration.
- Required fulfillment evidence.
- Detailed fulfillment transitions.
- Personnel responsible for execution and recording.

---

# 10. ORDER CHANGE REQUEST

**Status: CONFIRMED for high-level approval and locking rules**

After LoA approval, protected order specifications become locked.

    LoA APPROVED
          |
          v
    ORDER SPECIFICATIONS LOCKED
          |
          v
      CHANGE REQUIRED?
          |
       +--+--+
       |     |
       v     v
       NO   YES
       |     |
       v     v
    CONTINUE ADMIN SUBMITS
    WORKFLOW CHANGE REQUEST
                 |
                 v
             OWNER REVIEWS
                 |
             +---+---+
             |       |
             v       v
          APPROVE  REJECT
             |       |
             v       v
         APPLY     KEEP CURRENT
         CHANGE    ORDER VERSION
             |
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

Rules:

1. Before LoA approval, Admin may edit permitted order details directly.
2. After LoA approval, protected changes require an Order Change Request.
3. Admin submits a reason and proposed changes.
4. Owner approves or rejects.
5. Approved changes are applied while preserving previous order versions.
6. Affected Production Readiness requirements must be revalidated.
7. Affected Functional Layout information must be revalidated.
8. Normal specification changes are prohibited after Cutting starts.
9. Change Requests must not bypass the Mass Production start restriction.

Detailed state transitions, concurrent requests, withdrawal, revision, eligible fields, and exceptional cases remain UNDECIDED.

---

# 11. DELAY REPORT

**Status: CONFIRMED**

A Delay Report records a reported delay or condition affecting the production timeline.

Required information:

- Order Number.
- Affected Production Stage.
- Reason.
- Chronology / Explanation.
- Estimated Additional Duration in Working Days.

Supported statuses:

- OPEN
- RESOLVED

## 11.1 Workflow

    DELAY REPORT CREATED
            |
            v
           OPEN
            |
            v
      EMAIL DISPATCH
            |
            v
       PPIC FOLLOWS UP
            |
            v
         RESOLVED
            |
            v
    RESOLUTION EMAIL DISPATCH

Rules:

1. PPIC creates and manages Delay Reports.
2. New reports start as OPEN.
3. PPIC may resolve reports without Owner approval.
4. Creation initiates email dispatch.
5. Resolution initiates resolution email dispatch.
6. Intended recipients are PPIC, Owner, and Customer.
7. Customer email source and mandatory requirements remain UNDECIDED.
8. Dispatch attempts and delivery outcomes must be traceable.
9. Successful delivery must not be assumed from dispatch initiation.
10. Email failure must not invalidate or delete the report.
11. Status history must be preserved.
12. Delay Reports do not automatically establish an overdue condition.
13. Delay Reports do not automatically change the original planned completion date.

## 11.2 Timeline Relationship

The system distinguishes:

- Original Planned Completion Date.
- Estimated Completion Date.
- Actual Completion Date.

The original planned completion date must remain preserved.

Estimated completion may be updated separately.

The following remain UNDECIDED:

- Multiple Delay Report aggregation.
- Overlapping delay handling.
- Authoritative overdue calculation basis.

---

# 12. PRODUCTION ISSUE REPORT

**Status: CONFIRMED**

A Production Issue Report records operational issues encountered during production.

## 12.1 Report Information

- Related Order.
- Department / Production Stage.
- Issue Type.
- Description.
- Automatically Recorded Timestamp.
- Optional Photo.

## 12.2 Workflow

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
    NOTIFY PPIC
           |
           v
     PPIC FOLLOWS UP
           |
           v
     STATUS UPDATED
           |
           v
    RESOLUTION NOTE
           |
           v
        RESOLVED
           |
           v
    NOTIFY REPORTING STAFF

Supported statuses:

- OPEN
- IN_PROGRESS
- RESOLVED

Rules:

1. Production Staff creates reports.
2. PPIC receives an In-App Notification for new reports.
3. PPIC manages and follows up on all reports.
4. PPIC must provide a resolution note before RESOLVED.
5. Reporting Staff receives notification when report status changes.
6. Status and resolution history must be preserved.
7. Production Staff can only view their own reports.
8. IN_PROGRESS is not confirmed as a mandatory transition before RESOLVED.
9. No additional statuses may be introduced without approval.

## 12.3 Role Access

| Role             | Permission                                       |
| ---------------- | ------------------------------------------------ |
| OWNER            | Read-only access to all reports                  |
| ADMIN            | No access                                        |
| PPIC             | View, follow up, update, and resolve all reports |
| PRODUCTION_STAFF | Create and view own reports                      |

Issue categories and escalation rules remain UNDECIDED.

## 12.4 Distinction from Delay Report

| Production Issue Report          | Delay Report                       |
| -------------------------------- | ---------------------------------- |
| Records operational issues       | Records reported production delays |
| Created by Production Staff      | Created by PPIC                    |
| Followed up by PPIC              | Followed up by PPIC                |
| Uses OPEN, IN_PROGRESS, RESOLVED | Uses OPEN, RESOLVED                |

An operational issue does not automatically create a Delay Report or establish an overdue condition.

The relationship between an issue and a production delay must be evaluated rather than assumed.

---

# 13. NOTIFICATION WORKFLOW

**Status: CONFIRMED for limited Phase 1 scope**

## 13.1 In-App Notifications

In-App Notifications are limited to Production Issue Reports.

| Event                       | Recipient       |
| --------------------------- | --------------- |
| New Production Issue Report | PPIC            |
| Issue Report Status Changed | Reporting Staff |

Requirements:

1. Read state uses READ and UNREAD.
2. Read state is maintained separately per user.
3. Duplicate notifications for the same event and recipient must be prevented.

## 13.2 Delay Report Email

| Event                 | Intended Recipients   |
| --------------------- | --------------------- |
| Delay Report Created  | PPIC, Owner, Customer |
| Delay Report Resolved | PPIC, Owner, Customer |

Email dispatch attempts and delivery outcomes must be traceable.

Email failure must not invalidate the underlying business record.

## 13.3 Candidate Events

The following are not automatically active Phase 1 notifications:

- SAMPLE_APPROVED
- LOA_APPROVED
- PRODUCTION_WAITING
- PRODUCTION_READY
- PRODUCTION_STARTED
- PRODUCTION_COMPLETED
- PRODUCTION_SHORTAGE
- PRODUCTION_DELAYED
- DEADLINE_WARNING

Their recipients, channels, triggers, and duplicate-prevention rules remain UNDECIDED unless explicitly approved.

Sampling notifications are not part of the confirmed active Phase 1 In-App Notification scope.

Whether sampling notifications are enabled, their recipients, and their delivery behavior remain UNDECIDED.

Do not assume Owner-only recipients or activate sampling notifications without explicit business approval.

---

The following gates control progression through the business process.

| Gate                  | Requirement                                                                                                               | Allows                          |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------- | ------------------------------- |
| Sample Approval       | Sample / Look Approval is APPROVED                                                                                        | Progression to LoA              |
| LoA                   | LoA is APPROVED                                                                                                           | Production Readiness evaluation |
| Production Readiness  | Required conditions are satisfied                                                                                         | PPIC Preparation                |
| Mass Production Start | Cutting actually begins                                                                                                   | Production officially starts    |
| QC                    | QC inspection results in PASS                                                                                             | Progression to Finishing        |
| Production Completion | Cutting and Sewing are complete, applicable QC inspection has passed after any required Rework, and Finishing is complete | Progression to Packing          |
| Fulfillment           | Packing and Delivery / Customer Pickup are completed and fulfillment is confirmed                                         | Order may become COMPLETED      |

These gates define business requirements, not the complete technical state-transition implementation.

---

# 15. TIMELINE & PROGRESS TRACKING

**Status: CONFIRMED for tracking requirements**

The system must distinguish:

| Measurement           | Distinction                                 |
| --------------------- | ------------------------------------------- |
| Start Date            | Planned vs Actual                           |
| Completion Date       | Original Planned vs Estimated vs Actual     |
| Duration              | Planned vs Actual                           |
| Target                | Internal Duration vs Customer-facing Target |
| Production Status     | Current Process vs Bottleneck               |
| Operational Reporting | Production Delay vs Production Issue        |

## 15.1 Progress Tracking

1. PPIC manually submits percentage-based stage progress.
2. Each submission preserves timestamp and submitting user.
3. Historical submissions remain available.
4. Production Staff submit operational issues, not progress percentages.
5. Overall order progress derives from applicable stage progress records.
6. Overall progress formula remains UNDECIDED.
7. Stage weighting remains UNDECIDED.

## 15.2 Production Quantity

1. Order size quantities define ordered quantity.
2. Actual-piece production tracking is outside Phase 1.
3. Target versus actual production quantity is not confirmed.
4. Reject and Rework quantity tracking are not confirmed.
5. These quantities must not become mandatory progress inputs.

## 15.3 Working Calendar

**Status: CONFIRMED — TEMPORARY**

- Monday–Friday: Working Days.
- Saturday–Sunday: Non-working Days.
- National and company holidays: UNKNOWN.

The temporary calendar must not be treated as a permanent holiday policy.

---

# 16. DASHBOARD & AUDIT FLOW

## 16.1 Dashboard Data Flow

    AUTHORITATIVE BUSINESS RECORDS
                |
                v
       PRODUCTION TRACKING DATA
                |
                v
        DASHBOARD AGGREGATION
                |
                v
         ROLE-BASED DASHBOARD

Dashboard information must derive from authoritative business records and respect RBAC.

### Owner

- Total Order Value.
- Revenue Received.
- New Orders.
- Current overdue orders.
- OPEN Delay Reports.
- Production progress summary.
- Active orders grouped by customer.
- Read-only Production Issue Reports.
- Order-status audit history.

Total Order Value and Revenue Received must use separate authoritative data sources.

Total Order Value must never be derived from received payments.

Its definition, calculation, currency, and date basis remain UNDECIDED.

### Admin

- Incoming Orders.
- Sampling and Approval Status.
- LoA Status.
- Payment Recording.
- Customer Data.
- Order Data.

Admin has no Production Issue Report access.

### PPIC

- Orders awaiting Production Readiness.
- Waiting / On Hold orders.
- PPIC Preparation.
- Functional Layout preparation.
- Cutting, Sewing, and Finishing progress.
- QC and Rework operational status.
- Orders with no progress update.
- OPEN Delay Reports.
- Production Issue Reports and follow-up.

QC and Rework must not display invented percentage progress.

### Production Staff

- Orders currently IN_PROGRESS within the relevant department, subject to approved order assignment and department visibility rules.
- Relevant product details.
- Functional Layout.
- Target completion information.
- Work instructions.
- Production Issue Report creation.
- Own Production Issue Reports.

Production Staff cannot submit progress percentages.

Staff assignment and department visibility details remain UNDECIDED.

## 16.2 Audit History

Important business actions must preserve:

- Actor.
- Timestamp.
- Action.
- Affected Entity.
- Relevant Change Information.

Rules:

1. Historical progress submissions must not be overwritten.
2. Audit history must not be silently modified or deleted through normal business operations.
3. Audit access follows RBAC.
4. Owner has access to order-status change history.
5. Other audit visibility remains subject to approved permissions.

The complete audit action list and retention policy remain UNDECIDED.

---

# 17. UNRESOLVED WORKFLOW DECISIONS

The following decisions must remain unresolved until explicitly approved.

| Area                 | Outstanding Decisions                                                              | Status              |
| -------------------- | ---------------------------------------------------------------------------------- | ------------------- |
| Order Intake         | Image requirement, multiple models, custom sizes, quantity storage                 | UNDECIDED           |
| Sampling             | Responsibility, rejection consequences, revision and resubmission limits           | UNDECIDED           |
| LoA                  | Authorization responsibility, rejection consequences, detailed transitions         | UNDECIDED           |
| Payment              | Correction workflow, cancellation, DP prerequisite                                 | UNDECIDED           |
| Production Readiness | Complete checklist and prerequisite responsibilities                               | UNKNOWN / UNDECIDED |
| Waiting              | Reason categories and resume conditions                                            | UNDECIDED           |
| PPIC Preparation     | Duration and additional responsibilities                                           | UNKNOWN             |
| Functional Layout    | Data structure and approval requirement                                            | UNDECIDED           |
| QC / Rework          | Inspection coverage, duration, progress measurement, limits and escalation         | UNKNOWN / UNDECIDED |
| Fulfillment          | Duration, evidence, and execution responsibility                                   | UNKNOWN / UNDECIDED |
| Timeline             | Permanent calendar, holidays, delay aggregation, overdue calculation               | UNKNOWN / UNDECIDED |
| Progress             | Overall formula, weighting, planned versus actual calculation                      | UNDECIDED           |
| Issue Reports        | Categories, escalation, detailed transitions                                       | UNDECIDED           |
| Notifications        | Customer email source, additional events and recipients                            | UNDECIDED           |
| Order Change Request | Detailed state machine, concurrent requests, withdrawal, revision, eligible fields | UNDECIDED           |
| Permissions          | Production Staff assignment and department visibility                              | UNDECIDED           |
| Dashboard            | Reporting periods, aggregation, no-progress threshold                              | UNDECIDED           |
| Audit                | Complete action list, retention, additional visibility                             | UNDECIDED           |
| Commercial           | DP as Production Readiness or Mass Production prerequisite                         | UNDECIDED           |

Unresolved decisions must not be converted into implementation assumptions.

Detailed business constraints and validations belong in `BUSINESS_RULES.md`.

---

# 18. WORKFLOW IMPLEMENTATION PRINCIPLES

This document defines business processes, not technical implementation.

Before implementing any workflow:

1. Refer to the corresponding rules in `BUSINESS_RULES.md`.
2. Verify the requirement status.
3. Identify business gates, states, transitions, and exception paths.
4. Identify unresolved decisions.
5. Define transition tables before implementing state machines.
6. Enforce authorization and workflow validation in the backend.
7. Preserve historical business records and audit information.
8. Synchronize documentation when approved business decisions change.

Never introduce a workflow transition solely because it is convenient to implement.

If an unresolved decision blocks implementation, isolate the affected behavior and request business clarification.

---

# DOCUMENT STATUS

| Attribute   | Value                    |
| ----------- | ------------------------ |
| Document    | BUSINESS_WORKFLOW.md     |
| Version     | 1.0                      |
| Status      | FINAL — PHASE 1 BASELINE |
| Product     | Garment ERP Core         |
| Scope       | CMT & FOB Production     |
| Related PRD | Version 1.0              |

This document defines the approved high-level Phase 1 business workflow.

Detailed validations, operational constraints, and state-transition rules remain governed by `BUSINESS_RULES.md`.

No unresolved business decision may be silently converted into an approved workflow.
