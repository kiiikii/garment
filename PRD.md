# PRODUCT REQUIREMENTS DOCUMENT

# GARMENT ERP CORE

**Product:** Headless Production Tracking & Management System
**Version:** 1.0
**Status:** FINAL — PHASE 1 BASELINE
**Scope:** CMT & FOB Production
**Primary User:** Owner / Management
**Supporting Users:** Admin, PPIC, Production Staff
**Language:** English

---

# 1. DOCUMENT PURPOSE

This document defines the product scope, functional requirements, technical direction, system boundaries, and acceptance criteria for Garment ERP Core Phase 1.

The system centralizes garment order management, production monitoring, operational reporting, and fulfillment tracking.

## 1.1 Documentation Authority

| Document             | Responsibility                                           |
| -------------------- | -------------------------------------------------------- |
| PRD.md               | Product requirements, scope, and acceptance criteria     |
| BUSINESS_RULES.md    | Business constraints, validations, and operational rules |
| BUSINESS_WORKFLOW.md | Process sequences, workflow gates, and transitions       |
| USER_ROLES.md        | Role permissions and access control                      |
| GLOSSARY.md          | Business terminology                                     |

Documentation principles:

- PRD defines WHAT the system must provide.
- BUSINESS_RULES defines WHICH business constraints must be enforced.
- BUSINESS_WORKFLOW defines HOW business processes progress.
- USER_ROLES defines WHO can access and perform each action.
- GLOSSARY establishes consistent business terminology.

Detailed rules must not be unnecessarily duplicated across documents. Conflicts must be resolved before implementation.

Unresolved decisions must retain their original status and must never become assumed requirements.

## 1.2 Requirement Status

| Status                | Meaning                             |
| --------------------- | ----------------------------------- |
| CONFIRMED             | Approved requirement                |
| CONFIRMED — TEMPORARY | Temporary Phase 1 decision          |
| PROPOSED              | Requires approval                   |
| UNKNOWN               | Information is not yet known        |
| UNDECIDED             | Business decision has not been made |
| FUTURE                | Outside Phase 1 scope               |

---

# 2. PRODUCT OVERVIEW

## 2.1 Vision

Garment ERP Core is a headless production management system where the ERP Core serves as the single authority for business data, business rules, and workflow enforcement.

It manages garment orders from registration through sampling, commercial approval, production preparation, mass production, and fulfillment.

The system must provide:

- Centralized order and customer information.
- Controlled production workflows.
- Operational visibility.
- Production timeline monitoring.
- Historical traceability.
- Role-based access and dashboards.

## 2.2 Target Users

| Role               | Primary Responsibilities                                                                                                                           |
| ------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------- |
| OWNER / MANAGEMENT | Business monitoring, payment correction approval, Order Change Request approval, audit history                                                     |
| ADMIN              | Customer and order management, sampling and LoA administration, payment recording, Change Request creation                                         |
| PPIC               | Production Readiness, Waiting / On Hold, preparation, Functional Layout, progress submissions, delay and issue follow-up, fulfillment confirmation |
| PRODUCTION STAFF   | Assigned production information, work instructions, Functional Layout access, operational issue reporting                                          |

Customers have no login accounts in Phase 1. They may receive Delay Report emails according to approved notification configuration.

## 2.3 Phase 1 Objectives

1. Centralize customer and order management.
2. Support CMT and FOB production.
3. Manage Sampling and Sample / Look Approval.
4. Manage LoA and payment recording.
5. Enforce Production Readiness.
6. Support Waiting / On Hold.
7. Manage PPIC Preparation and Functional Layout.
8. Monitor Mass Production and percentage-based progress.
9. Track production timelines and delays.
10. Support Production Issue Reports.
11. Protect approved order specifications.
12. Provide role-based dashboards and notifications.
13. Support controlled payment corrections.
14. Support Packing and Delivery / Customer Pickup.
15. Preserve business history and audit records.

---

# 3. SYSTEM ARCHITECTURE

## 3.1 Confirmed Technical Stack

| Layer                     | Technology                | Status    |
| ------------------------- | ------------------------- | --------- |
| Backend                   | Laravel / PHP             | CONFIRMED |
| Frontend                  | React + Vite + TypeScript | CONFIRMED |
| Styling                   | Tailwind CSS              | CONFIRMED |
| UI Components             | shadcn/ui                 | PROPOSED  |
| Database                  | PostgreSQL                | CONFIRMED |
| Database Name             | garment_erp               | CONFIRMED |
| Communication             | REST API                  | CONFIRMED |
| Architecture              | Headless Modular Monolith | CONFIRMED |
| Frontend State Management | Not selected              | UNDECIDED |
| Deployment / Hosting      | Not selected              | UNDECIDED |

Development environment:

- Fedora Linux.
- Visual Studio Code.

Application code must remain OS-independent.

## 3.2 Architecture Responsibilities

**Laravel ERP Core:**

- Business logic and validation.
- Workflow and business-rule enforcement.
- Authentication and RBAC.
- Database persistence.
- Audit history.
- Notification triggers.

**React Frontend:**

- User interface and interaction.
- Data presentation.
- Form handling.
- Client-side validation for usability.

**PostgreSQL:**

- Persistent source of truth for business data.

## 3.3 Architectural Principles

1. Backend is the single authority for business logic and validation.
2. Frontend must never independently determine business truth.
3. All protected operations require backend authorization.
4. Workflow transitions must be validated by the backend.
5. Historical business records must remain traceable.
6. Backend must not depend on a specific frontend.
7. Laravel is the only active backend framework.
8. The previous Go implementation is discontinued.
9. Backend-first development remains the project direction.
10. Microservices must not be introduced without documented architectural justification.

---

# 4. FUNCTIONAL REQUIREMENTS

## 4.1 Authentication & Authorization

Phase 1 supports four internal roles:

- OWNER
- ADMIN
- PPIC
- PRODUCTION_STAFF

### Access Matrix

| Capability                     | OWNER     | ADMIN | PPIC | PRODUCTION_STAFF |
| ------------------------------ | --------- | ----- | ---- | ---------------- |
| Owner Dashboard                | Yes       | No    | No   | No               |
| Customer Management            | No        | Yes   | No   | No               |
| Order Intake                   | No        | Yes   | No   | No               |
| Payment Recording              | No        | Yes   | No   | No               |
| Payment Correction Approval    | Yes       | No    | No   | No               |
| Order Change Approval          | Yes       | No    | No   | No               |
| Production Readiness           | No        | No    | Yes  | No               |
| Functional Layout              | No        | No    | Yes  | No               |
| Production Progress            | No        | No    | Yes  | No               |
| Delay Reports                  | No        | No    | Yes  | No               |
| All Production Issue Reports   | Read-only | No    | Yes  | No               |
| Create Production Issue Report | No        | No    | No   | Yes              |
| View Own Issue Reports         | No        | No    | Yes  | Yes              |
| Order-Status Audit History     | Yes       | No    | No   | No               |

Additional restrictions:

- Customers cannot log in.
- Admin cannot access Production Issue Reports.
- Production Staff can only view their own issue reports.
- Production Staff order visibility is restricted by department and assignment.
- Backend authorization is mandatory.

Detailed permissions belong in `USER_ROLES.md`.

---

## 4.2 Customer & Order Management

Admin manages customer and order records.

### Required Order Information

- Customer Name.
- Customer Phone Number.
- Customer Origin / Address.
- Size Quantities.
- Production Type: CMT or FOB.

Product reference images are supported but are not confirmed as mandatory.

### Production Types

**CMT (Cut, Make, Trim):**

- Customer provides main materials.
- Company provides labor and production execution.
- Company provides thread and supporting accessories.

**FOB (Free On Board):**

- Customer provides DP according to the commercial agreement.
- Company procures required materials.
- Company executes production.

Whether DP is mandatory before Production Readiness or Mass Production remains UNDECIDED.

### Order Requirements

- Quantities are recorded individually by size.
- Total quantity equals the sum of recorded size quantities when calculated.
- Negative quantities are prohibited.
- Only CMT and FOB are supported in Phase 1.
- Admin may edit permitted order information before LoA approval.
- Protected specifications become locked after LoA approval.
- Changes to protected specifications require an Order Change Request.

Minimum order quantity, custom sizes, multiple product models, zero-size quantities, image requirements, quantity storage, and customer snapshot behavior remain governed by `BUSINESS_RULES.md`.

---

## 4.3 Sampling & Sample / Look Approval

Sampling consists of:

1. Pattern Preparation.
2. Sample Production.

### Duration Reference

| Activity               |        Duration |
| ---------------------- | --------------: |
| Pattern Preparation    | 2 internal days |
| Sample Production      | 2 internal days |
| Internal Total         |          4 days |
| Customer-facing Target |  7 working days |

Internal duration and customer-facing target must remain separate.

### Approval Statuses

- PENDING
- REVISION
- APPROVED
- REJECTED

Only APPROVED satisfies the Sample Approval Gate.

REVISION and REJECTED must not be treated as approval.

Rejection consequences, resubmission limits, and approval responsibility are governed by `BUSINESS_RULES.md`.

Sampling notifications are not confirmed active Phase 1 requirements.

---

## 4.4 LoA & Payment Management

LoA represents the commercial agreement before mass production and includes:

- Letter of Agreement / MoU.
- Payment Agreement.
- Payment Terms.

### LoA Statuses

- PENDING
- REVISION
- APPROVED
- REJECTED

Only APPROVED satisfies the LoA Gate.

LoA approval:

- Locks protected order specifications.
- Allows Production Readiness evaluation.
- Does not automatically establish production readiness.
- Does not automatically authorize Mass Production.

### Payment Requirements

Admin records:

- Down Payment (DP).
- Subsequent payments actually received.

The system must:

1. Record actual received payments.
2. Distinguish received payments from expected payments.
3. Prevent duplicate transactions from being counted more than once.
4. Preserve original payment records.
5. Support controlled payment corrections.
6. Require Owner approval or rejection for payment corrections.
7. Preserve payment correction history.
8. Provide payment monitoring for Owner.

Full accounting and financial reconciliation are outside Phase 1.

Payment methods, references, payment statuses, correction transitions, cancellation, partial payments, and DP production prerequisites remain governed by `BUSINESS_RULES.md`.

---

## 4.5 Production Readiness & Waiting / On Hold

After Sample / Look Approval and LoA approval, PPIC evaluates Production Readiness.

Potential readiness dependencies include:

- Main material availability.
- Fabric availability.
- Material purchasing.
- Operational requirements.
- Other approved prerequisites.

### Requirements

- LoA approval does not automatically mean production-ready.
- DP receipt does not automatically satisfy Production Readiness.
- Required readiness conditions must be satisfied before PPIC Preparation.
- Waiting / On Hold is conditional, not mandatory.
- Waiting reasons must be recorded.
- Readiness must be re-evaluated before resuming.
- Orders must not resume automatically based only on a date.

Waiting / On Hold is a workflow state, not a production process stage.

The complete readiness checklist, waiting categories, and resume conditions belong in `BUSINESS_RULES.md` and `BUSINESS_WORKFLOW.md`.

---

## 4.6 PPIC Preparation & Functional Layout

After Production Readiness is satisfied:

1. PPIC performs production preparation.
2. PPIC prepares the Functional Layout.
3. The order proceeds toward Mass Production.

Functional Layout represents physical production work-area arrangements, such as:

- Cutting.
- Sewing.
- Overlock.
- Embroidery.
- Finishing.

Functional Layout is not a production process stage and must not contribute to Mass Production progress.

A separate Functional Layout approval gate has not been confirmed.

---

## 4.7 Mass Production & Progress Monitoring

### Production Sequence

Confirmed production and fulfillment flow:

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
                         QC AGAIN

    FINISHING
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

The Rework → QC cycle may repeat according to inspection results.

### Duration Reference

| Stage                  |  Internal Duration |
| ---------------------- | -----------------: |
| Cutting                |             2 days |
| Sewing                 |             5 days |
| Finishing              |             2 days |
| Internal Baseline      |             9 days |
| Customer-facing Target | 10–14 working days |

The 9-day baseline includes Cutting, Sewing, and Finishing only.

QC, Rework, Packing, and Delivery / Customer Pickup are excluded.

QC and Rework durations remain UNKNOWN.

### Progress Monitoring

PPIC submits percentage-based progress for:

- Cutting.
- Sewing.
- Finishing.

QC and Rework are operationally monitored but have no confirmed percentage-progress mechanics.

### Progress Requirements

- Only PPIC can submit production progress percentages.
- Every submission preserves timestamp and submitting user.
- Historical submissions must never be overwritten.
- Unchanged percentages submitted as new updates must still be recorded.
- The system must distinguish missing updates from unchanged submissions.
- Fixed percentage increments are not required.
- Overall order progress is derived from applicable stage progress records.
- Overall progress formula and stage weighting remain UNDECIDED.

### Production Start & Completion

- Mass Production officially starts when Cutting actually begins.
- PPIC Preparation and Functional Layout do not constitute production start.
- Finishing completion does not automatically mean an order is completed.
- All required production and fulfillment conditions must be satisfied before completion.

---

## 4.8 Timeline & Delay Management

The system must distinguish:

| Timeline Attribute        | Description                     |
| ------------------------- | ------------------------------- |
| Planned Start Date        | Original scheduled start        |
| Actual Start Date         | Actual production start         |
| Planned Completion Date   | Original scheduled completion   |
| Estimated Completion Date | Current estimated completion    |
| Actual Completion Date    | Actual completion               |
| Planned Duration          | Scheduled working duration      |
| Actual Duration           | Actual elapsed duration         |
| Current Process           | Current production stage        |
| Current Workflow State    | Current business state          |
| Waiting / On Hold State   | Readiness-related waiting state |
| Customer-facing Deadline  | Customer-facing target          |

Original Planned Completion Date must remain preserved.

### Working Calendar

Temporary Phase 1 calendar:

- Monday–Friday: Working Days.
- Saturday–Sunday: Non-working Days.
- National and company holidays: UNKNOWN.

### Delay Report

PPIC creates formal Delay Reports containing:

- Order Number.
- Affected Production Stage.
- Reason.
- Chronology / Explanation.
- Estimated Additional Duration in Working Days.

Statuses:

- OPEN
- RESOLVED

### Delay Report Requirements

- New reports start as OPEN.
- PPIC manages follow-up and may resolve reports without Owner approval.
- Creating a report initiates email dispatch.
- Resolving a report initiates resolution email dispatch.
- Status history must be preserved.
- Email attempts and delivery outcomes must be traceable.
- Email failure must not invalidate the report.
- A Delay Report must not automatically change the original planned completion date.
- A Delay Report must not automatically update the estimated completion date.
- A Delay Report does not automatically establish an overdue condition.

Intended email recipients:

- PPIC.
- Owner.
- Customer.

Exact recipient mapping and customer email requirements remain UNDECIDED.

Delay aggregation and overdue calculation belong in `BUSINESS_RULES.md`.

---

## 4.9 Production Issue Report

Production Staff can report operational issues encountered during production.

### Report Information

- Related Order.
- Department / Production Stage.
- Issue Type.
- Description.
- Automatically Recorded Timestamp.
- Optional Photo.

### Statuses

- OPEN
- IN_PROGRESS
- RESOLVED

### Workflow

    PRODUCTION STAFF
           |
           v
    CREATE ISSUE REPORT
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

### Requirements

- Production Staff creates issue reports.
- PPIC receives notification when a new report is created.
- PPIC manages all issue reports.
- Owner has read-only access.
- Admin has no access.
- Production Staff can only view their own reports.
- PPIC must provide a resolution note before RESOLVED.
- Reporting Staff receives notification when report status changes.
- Status history must be preserved.

Production Issue Reports are separate from Delay Reports.

An operational issue does not automatically create a Delay Report or establish an overdue condition.

Detailed transitions, categories, and escalation rules belong in `BUSINESS_RULES.md`.

---

## 4.10 Order Change Request

Order Change Request protects approved order specifications.

### Requirements

1. Before LoA approval, Admin may edit permitted order details directly.
2. After LoA approval, protected order specifications are locked.
3. Admin submits a Change Request with a reason and proposed changes.
4. Owner approves or rejects the request.
5. Changes are applied only after approval.
6. Previous order versions must remain traceable.
7. Approved changes trigger revalidation of affected Production Readiness requirements.
8. Approved changes trigger revalidation of affected Functional Layout information.
9. Normal specification changes are prohibited after Cutting starts.
10. Change Requests must not bypass the Mass Production start restriction.

Detailed state transitions, withdrawal, revision, concurrent requests, eligible fields, and exceptional cases belong in `BUSINESS_RULES.md`.

---

## 4.11 Dashboards

All dashboard information must derive from authoritative business records. Dashboards must not independently create or override business truth.

### Owner Dashboard

- Total Order Value.
- Revenue Received.
- New Orders.
- Current overdue orders.
- OPEN Delay Reports.
- Production progress summary.
- Active orders grouped by customer.
- Read-only Production Issue Reports.
- Order-status audit history.

Important distinctions:

- Total Order Value represents order value.
- Revenue Received represents actual received payments.
- Total Order Value must never be derived from received payments.

Total Order Value source, definition, calculation, currency, and date basis remain UNDECIDED.

### Admin Dashboard

- Incoming Orders.
- Sampling Status.
- Sample / Look Approval.
- LoA Status.
- Payment Recording.
- Customer Data.
- Order Data.

Admin has no Production Issue Report access.

### PPIC Dashboard

- Orders awaiting Production Readiness.
- Waiting / On Hold orders.
- PPIC Preparation.
- Functional Layout preparation.
- Cutting Progress.
- Sewing Progress.
- QC Operational Status.
- Rework Operational Status.
- Finishing Progress.
- Orders with no progress update.
- OPEN Delay Reports.
- All Production Issue Reports and follow-up.

QC and Rework must not display invented percentage progress.

### Production Staff Dashboard

- Assigned orders currently IN_PROGRESS within the relevant department.
- Relevant product details.
- Relevant Functional Layout.
- Target completion information.
- Work instructions.
- Production Issue Report creation.
- Own Production Issue Reports.

Production Staff cannot submit progress percentages.

---

## 4.12 Notifications

### Confirmed In-App Notifications

| Event                       | Recipient       |
| --------------------------- | --------------- |
| New Production Issue Report | PPIC            |
| Issue Report Status Changed | Reporting Staff |

Notification read states:

- READ
- UNREAD

Requirements:

- Read status is maintained separately for each user.
- Duplicate notifications for the same event and recipient must be prevented.

### Confirmed Delay Report Email Events

- Delay Report Created.
- Delay Report Resolved.

Intended recipients:

- PPIC.
- Owner.
- Customer.

Email dispatch attempts and delivery outcomes must be traceable.

### Unconfirmed Notifications

The following are not active Phase 1 notification requirements:

- Sampling.
- LoA.
- Production Readiness.
- Production Start.
- Production Completion.
- Deadline events.

Activation requires explicit approval.

---

## 4.13 Audit History

Important business actions must preserve:

- Actor.
- Timestamp.
- Action.
- Affected Entity.
- Relevant Change Information.

Requirements:

- Historical records must remain traceable.
- Normal business operations must not silently modify or delete audit history.
- Owner can access order-status audit history.
- Other audit visibility follows approved permissions.

The complete audit action list and retention policy belong in `BUSINESS_RULES.md`.

---

# 5. UI/UX REQUIREMENTS

## 5.1 Design Direction

The application is a role-based React SPA.

Required interface capabilities:

- Operational dashboards.
- Data tables, search, and filters.
- Forms and approval actions.
- Status indicators and progress bars.
- Production timelines.
- Order details and production reports.
- Notification lists.
- Attachment previews.
- Audit history.

The interface must clearly communicate:

- Current status.
- Production progress.
- Delays.
- Waiting reasons.
- Unmet prerequisites.
- Required actions.

## 5.2 Design System

| Area              | Requirement                                    |
| ----------------- | ---------------------------------------------- |
| Styling           | Tailwind CSS                                   |
| UI Components     | shadcn/ui — PROPOSED                           |
| Design Tokens     | Semantic tokens                                |
| Layout            | Responsive                                     |
| Accessibility     | Accessible forms and clear validation feedback |
| Theme             | UNDECIDED                                      |
| Color Palette     | UNDECIDED                                      |
| Light / Dark Mode | UNDECIDED                                      |

## 5.3 Responsive Design

- Desktop-first.
- Tablet support for operational use.
- Native mobile application excluded from Phase 1.
- Basic keyboard accessibility required.

## 5.4 Frontend Structure

Detailed application structure belongs in:

`docs/architecture/APPLICATION_ARCHITECTURE.md`

Suggested structure:

    apps/web/
    └── src/
        ├── app/
        ├── components/
        ├── features/
        ├── hooks/
        ├── lib/
        ├── services/
        ├── types/
        └── assets/

---

# 6. PHASE 1 EXCLUSIONS

The following capabilities are explicitly outside Phase 1.

### Business Modules

- Full Inventory Management.
- Stock Movement.
- Stock Opname.
- Stock Variance.
- Material Consumption Tracking.
- Full Procurement Management.
- HPP / Costing.
- Leftover Goods Management.
- Bonus Goods Management.
- Full Accounting.
- Financial Reconciliation.
- Fashion Retail.
- Customer Portal and Customer Login.

Order-specific material purchasing and readiness tracking for FOB remain within scope.

### Production Features

- Actual-piece production tracking.
- Target versus actual production quantity.
- Reject-quantity tracking.
- Rework-quantity tracking.
- Production Staff percentage-progress entry.
- Unapproved QC / Rework progress mechanics.

### Technical Features

- Native mobile application.
- Microservices without architectural justification.
- Previous Go backend implementation.
- Business logic implemented exclusively in the frontend.

---

# 7. UNRESOLVED DECISIONS

The following decisions must not be assumed or implemented as confirmed requirements without approval.

| Area                 | Outstanding Decisions                                                                                                                 | Status              |
| -------------------- | ------------------------------------------------------------------------------------------------------------------------------------- | ------------------- |
| Customer & Order     | Phone format, image requirements, multiple models, custom sizes, zero quantity, minimum quantity, quantity storage, customer snapshot | UNDECIDED           |
| Sampling             | Execution responsibility, approval responsibility, rejection consequences, revision and resubmission, duration clarification          | UNKNOWN / UNDECIDED |
| LoA                  | Authorization responsibility, rejection consequences, detailed transitions                                                            | UNDECIDED           |
| Payment              | Methods, references, payment status, correction workflow, cancellation, partial payments, DP prerequisite                             | UNDECIDED           |
| Production Readiness | Complete checklist, prerequisite responsibility, material requirements                                                                | UNKNOWN / UNDECIDED |
| Waiting / On Hold    | Reason categories, resume conditions, repeated waiting periods                                                                        | UNDECIDED           |
| PPIC Preparation     | Duration and additional responsibilities                                                                                              | UNKNOWN             |
| Functional Layout    | Data structure and separate approval requirement                                                                                      | UNDECIDED           |
| QC / Rework          | Inspection coverage, duration, progress measurement, rework limits and escalation                                                     | UNKNOWN             |
| Fulfillment          | Packing and delivery duration, responsible personnel, completion evidence                                                             | UNKNOWN / UNDECIDED |
| Timeline             | Permanent calendar, holidays, delay aggregation, overlapping delays, estimated completion, overdue calculation                        | UNKNOWN / UNDECIDED |
| Progress             | Overall progress formula, weighting, planned versus actual progress                                                                   | UNDECIDED           |
| Issue Reports        | Categories, transitions, escalation, mandatory IN_PROGRESS transition                                                                 | UNDECIDED           |
| Notifications        | Customer email source, recipient mapping, additional events                                                                           | UNDECIDED           |
| Order Change Request | State machine, concurrent requests, withdrawal, revision, eligible fields, exceptional cases                                          | UNDECIDED           |
| User Permissions     | Production Staff department assignment, order visibility, remaining permissions                                                       | UNDECIDED           |
| Dashboards           | Reporting period, date basis, aggregation, no-progress threshold, Total Order Value definition                                        | UNDECIDED           |
| Audit                | Complete action list, retention, additional visibility                                                                                | UNDECIDED           |
| Technical            | Hosting, frontend state management, final component library and design system                                                         | UNDECIDED           |

Authoritative details remain in their respective supporting documents.

Unresolved decisions must not be converted into implementation assumptions.

---

# 8. ACCEPTANCE & DOCUMENT GOVERNANCE

## 8.1 Phase 1 Acceptance Criteria

Phase 1 is accepted when:

1. All four internal roles access only their authorized features.
2. Order intake supports CMT and FOB.
3. Sampling and commercial approval are traceable.
4. LoA approval protects order specifications.
5. Production Readiness is independently enforced.
6. Waiting / On Hold requires readiness re-evaluation.
7. PPIC Preparation and Functional Layout remain separate from production stages.
8. Mass Production follows the confirmed production sequence.
9. PPIC controls percentage-based progress submissions.
10. Production history preserves all progress submissions.
11. Delay Reports and Production Issue Reports remain separate.
12. Order Change Requests require Owner approval.
13. Payment corrections require Owner approval.
14. Fulfillment conditions must be satisfied before order completion.
15. Important business actions preserve historical traceability.
16. Backend enforces all business rules, workflow transitions, and permissions.

## 8.2 Supporting Documentation

The documentation structure is:

    docs/
    ├── business/
    │   ├── BUSINESS_RULES.md
    │   ├── BUSINESS_WORKFLOW.md
    │   ├── USER_ROLES.md
    │   └── GLOSSARY.md
    │
    ├── product/
    │   └── PRD.md
    │
    ├── architecture/
    ├── api/
    ├── database/
    └── engineering/

Changes affecting multiple documents must be synchronized.

## 8.3 Change Control

This PRD represents the FINAL Phase 1 product baseline.

Any change after finalization requires:

1. A documented change request.
2. Identification of affected requirements.
3. Impact analysis.
4. Approval by the responsible project authority.
5. Revision of affected supporting documents.
6. Version and change-history updates.

The approved baseline must never be modified silently.

## 8.4 Final Document Status

| Attribute    | Value                     |
| ------------ | ------------------------- |
| Document     | PRD.md                    |
| Version      | 1.0                       |
| Status       | FINAL — PHASE 1 BASELINE  |
| Product      | Garment ERP Core          |
| Scope        | CMT & FOB Production      |
| Architecture | Headless Modular Monolith |
| Backend      | Laravel / PHP             |
| Frontend     | React + Vite + TypeScript |
| Database     | PostgreSQL                |

This PRD defines the approved product scope and implementation boundaries for Phase 1.

Detailed business rules, workflow transitions, and role permissions remain governed by their respective supporting documents.

No unresolved business decision may be silently converted into an implementation requirement.

---
