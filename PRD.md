# Product Requirements Document: Garment ERP Core

**Product:** Headless Production Tracking & Management System  
**Version:** 0.4 (Draft — Phase 1)  
**Status:** For Stakeholder Review  
**Language:** English  
**Primary User:** Owner / Management  
**Supporting Users:** Admin, PPIC, Production Staff

> This document summarizes the product vision, architectural boundaries, MVP scope, UI/UX guidelines, and explicit exclusions for Phase 1.
>
> Detailed operational rules, workflow transitions, role permissions, and technical specifications belong in the supporting documents.
>
> Requirement status:
>
> - `CONFIRMED` — agreed requirement.
> - `CONFIRMED — TEMPORARY` — temporary Phase 1 decision.
> - `PROPOSED` — proposed design requiring review.
> - `UNKNOWN` — information not yet known.
> - `UNDECIDED` — decision has not yet been made.
> - `FUTURE` — outside Phase 1 scope.

This version is intended to remain consistent with:

- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`

These documents define the operational rules and workflows in greater detail. If a referenced file does not yet exist, it must be created and reviewed before being treated as an authoritative specification.

---

## Supporting Documents

| Topic                                                              | Document                                                      |
| ------------------------------------------------------------------ | ------------------------------------------------------------- |
| Business workflow                                                  | `docs/business/BUSINESS_WORKFLOW.md`                          |
| Business rules and open decisions                                  | `docs/business/BUSINESS_RULES.md`                             |
| Roles and permissions                                              | `docs/business/USER_ROLES.md`                                 |
| User personas and terminology                                      | `docs/business/USER_PERSONAS.md`, `docs/business/GLOSSARY.md` |
| Feature specifications, user stories, acceptance criteria, roadmap | `docs/product/`                                               |
| REST API contracts                                                 | `docs/api/API_CONTRACTS.md`                                   |
| System and application architecture                                | `docs/architecture/`                                          |
| Database design and data dictionary                                | `docs/database/`                                              |
| Engineering standards and implementation guidance                  | `docs/engineering/`                                           |
| AI coding instructions and project context                         | `AGENTS.md`, `AI_CONTEXT.md`                                  |
| Product requirements                                               | `PRD.md`                                                      |
| Architecture decisions and decision changes                        | `docs/architecture/decisions/`                                |

> The references above represent the intended documentation structure. A file reference does not imply that its contents are already finalized.

---

## 1. Core Vision & The One Rule

### 1.1 Target Users

**Primary Target User — Owner / Management**

The Owner needs a reliable overview of order status, production progress, delays, operational issues, and payment conditions to monitor and control business operations.

**Supporting Users**

- **Admin:** manages customer and order records, LoA administration, and payment recording.
- **PPIC:** manages production readiness, Waiting / On Hold follow-up, functional layout preparation, production progress updates, Delay Reports, and Production Issue Report follow-up.
- **Production Staff:** views production information permitted by the approved department and order visibility rules, and reports operational issues.

**Customers**

Customers do not have login accounts in Phase 1. Customers may receive Delay Report emails, subject to the confirmed customer contact and email requirements.

### 1.2 Core Problem

The company does not yet have a centralized system that enables relevant stakeholders to consistently identify order status and production progress, detect and trace delays, and coordinate work across production departments in a structured manner.

### 1.3 The Big Idea

Garment ERP is a headless production management system with the ERP Core as the authority for business data and rules.

It manages an order's lifecycle from intake through fulfillment confirmation, enforces workflows and validations based on business rules, and provides operational visibility according to each user's responsibilities.

### 1.4 Product Value

- **Production Visibility:** authorized users can view order status, progress, issues, history, and estimated completion according to their roles.
- **Production Control:** status transitions, order changes, payment records, readiness checks, and production follow-up must comply with business rules and permissions.
- **Operational Traceability:** important business actions and changes must retain relevant historical records.
- **Controlled Fulfillment:** orders cannot be marked completed merely because mass production has finished.

### 1.5 Phase 1 Goals

1. Centralize customer, order, and product-reference data.
2. Support two production types: `CMT` and `FOB`.
3. Manage sampling, sample/look approval, LoA, production readiness, Waiting / On Hold, PPIC preparation, functional layout, and mass production.
4. Track production progress through percentage updates submitted by PPIC for confirmed monitored stages.
5. Preserve progress-update history, workflow status history, and relevant audit records.
6. Document delays through Delay Reports and operational issues through Production Issue Reports.
7. Provide role-based dashboards and monitoring for the four internal roles.
8. Protect order integrity after LoA approval through the Order Change Request process.
9. Record payments actually received and support controlled payment-record corrections.
10. Provide In-App Notifications for Production Issue Reports and email notifications for Delay Reports.
11. Support order tracking through Packing and Delivery / Customer Pickup confirmation.

---

## 2. Technical Stack & Architecture Boundary

### 2.1 Technical Stack

| Area                      | Technology / Decision      | Status                                                                                 |
| ------------------------- | -------------------------- | -------------------------------------------------------------------------------------- |
| Backend / ERP Core        | Go                         | `CONFIRMED`                                                                            |
| Frontend                  | React + Vite + TypeScript  | `CONFIRMED`                                                                            |
| Styling                   | Tailwind CSS               | `CONFIRMED`                                                                            |
| UI Components             | shadcn/ui                  | `PROPOSED` — subject to stack compatibility and component requirements                 |
| Database                  | PostgreSQL                 | `CONFIRMED`                                                                            |
| Development database name | `garment_erp`              | `CONFIRMED`                                                                            |
| API                       | REST API                   | `CONFIRMED`                                                                            |
| Architecture              | Headless, Modular Monolith | `CONFIRMED`                                                                            |
| Frontend state management | Not selected               | `UNDECIDED`                                                                            |
| Deployment / hosting      | Not selected               | `UNDECIDED` — requires a proof of concept for Go runtime and hosting lifecycle         |
| Development environment   | Fedora Linux, VS Code      | `CONFIRMED` as the development environment; the application must remain OS-independent |

### 2.2 Architecture Boundary

- The Go ERP Core owns and enforces domain logic, business rules, workflows, validation, authentication, authorization/RBAC, persistence, audit history, and notification triggers.
- React + Vite is a client that communicates with the ERP Core through the REST API.
- The frontend is not the source of truth for business rules or business data.
- All business validation and permission checks must be enforced by the backend. Frontend validation may improve UX but does not replace backend validation.
- PostgreSQL is the persistent data source of truth.
- Phase 1 uses a Modular Monolith. Microservices must not be introduced unless there is a genuine need supported by a documented architectural rationale.
- The backend must not depend on a particular frontend. Future clients may consume the same API.
- Backend-first remains the implementation direction: establish the core domain, workflows, and API contracts first. Frontend development may begin once the core API contracts and workflows are sufficiently stable.
- Sprint numbers and the detailed implementation sequence are not locked by this PRD.

### 2.3 Backend Request Flow

`HTTP Request → HTTP Server → Middleware Chain → Router → Handler / Transport → Application Service / Use Case → Domain Logic → Repository Interface → Repository Implementation → PostgreSQL → HTTP Response`

Detailed package boundaries and code structure will be defined in architecture and engineering documents, not as low-level implementation decisions in this PRD.

---

## 3. Core Features & User Flows (MVP Scope)

The following summarizes the Phase 1 scope. Detailed workflows, business rules, permissions, and acceptance criteria must be specified in the supporting documents.

### 3.1 Authentication & Role-Based Access Control

**Description**

Internal users sign in with one of the following roles:

- `OWNER`
- `ADMIN`
- `PPIC`
- `PRODUCTION_STAFF`

**User Flow**

Login → backend authenticates the user → permissions are checked for each request → the user sees the dashboard and data permitted for their role.

**Acceptance Criteria**

- Unauthenticated users cannot access protected features.
- The backend rejects unauthorized actions even when requests are sent directly without using the UI.
- Data visibility and available actions are restricted according to the user's role and defined access scope.
- Backend authorization is authoritative; hiding frontend actions alone does not constitute access control.

Detailed role permissions and data visibility rules are defined in `docs/business/USER_ROLES.md`.

### 3.2 Customer & Order Intake

**Description**

Admin creates and manages customer records and new orders.

**Minimum Order Data**

- Customer name.
- Customer phone number.
- Customer origin / address.
- Product/model reference images, if available.
- Quantity distribution by size, such as S, M, L, and XL.
- Production type: `CMT` or `FOB`.

The minimum number of product reference images and the handling of zero quantity for an individual size remain `UNDECIDED`.

**User Flow**

Admin creates or selects a customer → enters order details and size quantities → adds product references if available → saves the order → the order enters the initial process.

**Acceptance Criteria**

- An order may have associated product/model reference images.
- Product reference images are supported, but a minimum image requirement has not been established.
- Quantity information is recorded by size.
- The storage and calculation strategy for total order quantity remains `UNDECIDED`.
- Only `CMT` and `FOB` are accepted as production types in Phase 1.
- Admin may directly edit order details before LoA reaches `APPROVED`.
- Once LoA reaches `APPROVED`, direct editing of protected order details is locked.
- Changes after LoA approval must follow the Order Change Request process, subject to its Phase 1 restrictions.

The detailed Order data structure and validation rules belong in the business and database specifications.

### 3.3 Sampling & Sample / Look Approval

**Description**

The system records the sampling process and sample/look decisions.

**User Flow**

Order enters Sampling → pattern-making and sample production take place → the sample/look is submitted for review → a decision is recorded.

**Sample / Look Approval Statuses**

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

**Reference Duration**

- Pattern-making: 2 internal days.
- Sample production: 2 internal days.
- Total internal reference duration: 4 days.
- Customer-facing target: 7 working days.

These are initial reference durations, not automatic guarantees.

The applicable calendar basis for internal duration calculations remains subject to the approved business rules.

**Acceptance Criteria**

- The system records sampling and approval statuses and relevant dates.
- `REVISION` and `REJECTED` must not be treated as approval.
- Only `APPROVED` satisfies the sample/look approval requirement.
- The operational consequences of revision, rejection, and resubmission follow the Business Rules document.
- Sampling notifications to Owner are not part of the confirmed Phase 1 notification scope unless separately approved.

### 3.4 LoA & Payment Recording

**Description**

LoA is the commercial agreement process and covers administrative/commercial agreements, including an LoA/MoU and payment agreement according to company procedures.

Admin manages LoA administration and records payments actually received.

**LoA Statuses**

- `PENDING`
- `REVISION`
- `APPROVED`
- `REJECTED`

**User Flow**

Sample/look is approved → LoA is processed → LoA status is recorded → once LoA reaches `APPROVED`, the order can proceed to Production Readiness evaluation.

Admin records payments actually received → Owner can view payment-related dashboard metrics.

**Acceptance Criteria**

- The system records the LoA status and relevant approval information.
- Only `APPROVED` satisfies the LoA approval requirement.
- LoA approval does not automatically mean that the order is production-ready.
- Admin can record the down payment (DP) and subsequent payments actually received.
- Payment-record corrections must be submitted for Owner approval or rejection.
- The original payment record and correction history remain traceable.
- The system must not count the same transaction more than once in payment metrics.
- Payment recording does not automatically establish Production Readiness or another production gate unless a specific payment prerequisite is confirmed in the Business Rules.

**Important Boundary**

The role of DP as a prerequisite for Production Readiness, PPIC Preparation, or Mass Production remains `UNDECIDED`. The system must not enforce an assumed DP gate based solely on the FOB production model.

### 3.5 Production Readiness, Waiting / On Hold, PPIC & Functional Layout

**Description**

After LoA approval, PPIC evaluates Production Readiness according to the order's actual material and operational requirements.

An order may enter Waiting / On Hold when required prerequisites are not yet satisfied.

Once the applicable prerequisites are met, PPIC prepares the Functional Layout for production.

**User Flow**

LoA `APPROVED` → Production Readiness evaluation → if prerequisites are unmet, the order enters Waiting / On Hold with a recorded reason → prerequisites are fulfilled → readiness is re-evaluated → PPIC prepares the Functional Layout → the order may proceed toward Mass Production when all required conditions are satisfied.

**Acceptance Criteria**

- Production Readiness is evaluated separately from LoA approval.
- Readiness requirements are determined by the applicable order conditions; the system must not assume every order has identical material or purchasing requirements.
- Waiting / On Hold reasons are recorded.
- Waiting / On Hold is a conditional operational status, not a production stage.
- An order must be re-evaluated before proceeding from Waiting / On Hold.
- The system must reject transitions when confirmed prerequisites remain unmet and identify the unmet requirements.
- Functional Layout represents the arrangement of production work areas/functions, such as Cutting, Sewing, Overlock, Embroidery, and Finishing.
- Functional Layout is not a production stage and is not counted as mass-production progress.
- Functional Layout preparation must not be treated as a separate approval gate unless that requirement is formally confirmed.
- An approved Order Change Request made before Mass Production starts triggers revalidation of affected readiness and layout requirements.

The complete readiness checklist, responsible parties, and any additional layout approval requirements remain subject to the Business Rules and Open Decisions.

### 3.6 Mass Production & Production Monitoring

**Description**

The system monitors the mass-production process and records production progress updates submitted by PPIC.

**Operational Sequence**

`CUTTING → SEWING → QC → (PASS → FINISHING | FAIL → REWORK → QC AGAIN) → PACKING → DELIVERY / CUSTOMER PICKUP → COMPLETED`

**Reference Duration**

| Stage     | Internal Reference Duration |
| --------- | --------------------------: |
| Cutting   |                      2 days |
| Sewing    |                      5 days |
| Finishing |                      2 days |
| **Total** |                  **9 days** |

The customer-facing target for mass production is 10–14 working days.

QC and Rework do not yet have separately agreed duration allocations. Their treatment within internal and customer-facing estimates remains subject to clarification.

Packing and Delivery / Customer Pickup are fulfillment activities after Finishing. Their durations are not included in the 9-day internal mass-production reference.

**Monitoring Rules**

- PPIC is responsible for entering production progress updates.
- Production Staff do not enter production progress percentages in Phase 1.
- Cutting, Sewing, and Finishing are confirmed percentage-monitored stages.
- QC and Rework are monitored operational stages, but their specific progress-measurement methods remain `UNDECIDED`.
- The system preserves every submitted progress update, including its timestamp and submitting user.
- The system distinguishes between no progress update being submitted and an update being submitted with no percentage change.
- Progress is recorded through percentages, not actual per-piece quantity tracking, in Phase 1.
- The overall order progress formula is `UNDECIDED`; the system must not assume a simple average.
- QC inspection coverage and Rework handling must follow confirmed business rules.

**Acceptance Criteria**

- PPIC can submit progress updates for confirmed percentage-monitored stages under its responsibility.
- Previous progress updates are retained and are not overwritten by the latest value.
- The system does not assume percentage-entry mechanics for QC and Rework until those rules are defined.
- An order cannot be marked `COMPLETED` merely because Finishing is complete.
- Required Packing and Delivery / Customer Pickup activities must be completed before the order becomes `COMPLETED`.
- Completion must be confirmed by an authorized user according to the approved fulfillment responsibility rules.
- The responsible role and required confirmation evidence remain `UNDECIDED`.

### 3.7 Delay Report

**Description**

PPIC creates an official report when a production delay needs to be documented and followed up.

**Required Fields**

- Order number.
- Affected production stage.
- Reason.
- Chronology / explanation.
- Estimated additional duration in working days.

**User Flow**

PPIC creates a Delay Report → report is saved with status `OPEN` → email dispatch is initiated for the configured recipients → PPIC follows up → PPIC changes the status to `RESOLVED` → the system records the status change and initiates the corresponding email dispatch.

**Acceptance Criteria**

- Report statuses include `OPEN` and `RESOLVED`.
- PPIC may create reports and change their status; Owner approval is not required for these status changes.
- The system stores status history and email-dispatch/delivery information.
- Email failure does not delete or invalidate a saved report.
- Email dispatch attempts and delivery outcomes must be traceable.
- Intended email recipients may include PPIC, Owner, and Customer.
- The final recipient mapping must follow the approved Delay Report notification requirements.
- Customer email availability and whether email is mandatory in customer records remain `UNDECIDED`.
- Additional duration is calculated in working days.
- The temporary Phase 1 calendar is Monday–Friday; Saturday and Sunday are excluded.
- National and company holidays have not yet been defined.
- The original `planned_completion_date` is preserved.
- `estimated_completion_date` may change according to applicable delay-handling rules.
- `actual_completion_date` is recorded when the order is completed.
- Having a Delay Report does not automatically mean that an order is overdue.
- Delay aggregation, overlapping reports, and overdue calculation follow the Business Rules document and its Open Decisions.

### 3.8 Production Issue Report

**Description**

Production Staff report operational issues to PPIC.

This report is distinct from the formal Delay Report.

**Report Data**

- Related order.
- Department / stage.
- Issue type.
- Description.
- Automatically recorded timestamp.
- Optional photo attachment.

**Statuses**

- `OPEN`
- `IN_PROGRESS`
- `RESOLVED`

**User Flow**

Production Staff submits a report → PPIC receives an In-App Notification → PPIC follows up and updates the report status as applicable → the reporting Production Staff member receives a notification when the status changes.

**Acceptance Criteria**

- Production Staff can only view reports they created.
- PPIC can view all reports and manage their follow-up.
- Owner can view all reports in read-only mode.
- Admin has no access to this feature in Phase 1.
- PPIC must enter a resolution note before changing the status to `RESOLVED`.
- The status and resolution note are visible to the reporter and Owner.
- An In-App Notification is sent to PPIC when a new report is created.
- An In-App Notification is sent to the reporting user whenever the report status changes.
- Notification read status is maintained separately for each user using `READ` and `UNREAD`.
- The detailed transition rules, including whether `IN_PROGRESS` is mandatory before `RESOLVED`, remain subject to the Business Rules document.

### 3.9 Order Change Request

**Description**

A controlled process for changing protected order details after LoA reaches `APPROVED`.

**User Flow**

Customer requests a change → Admin creates a Change Request with the reason and proposed changes → Owner approves or rejects it → if approved, the changes are applied while the previous order version remains stored → affected Production Readiness and Functional Layout requirements are revalidated.

**Acceptance Criteria**

- Before LoA reaches `APPROVED`, Admin may directly edit order details.
- After LoA reaches `APPROVED` and before Mass Production starts, protected order changes must go through a Change Request and Owner approval.
- Changes are applied only after Owner approval.
- Version history and approval records are retained.
- After approved changes are applied, affected Production Readiness and Functional Layout requirements are rechecked.
- Normal specification changes are not permitted after Mass Production has started.
- For this rule, Mass Production starts when Cutting actually begins.
- A Change Request must not bypass the Mass Production start restriction.

Detailed request statuses, cancellation rules, and handling of requests affecting orders already being prepared for production remain subject to the Business Rules document.

### 3.10 Dashboards

Dashboard data must be derived from authoritative business records.

Dashboards are read models for operational visibility. They must not independently invent or override order statuses, production progress, delay conditions, payment totals, or audit history.

**Owner Dashboard**

- Total Order Value for the selected period, subject to the confirmed order-value source and definition.
- Revenue Received based on payments actually received during the selected period.
- Number of new orders created during the selected period.
- Order Terlambat (overdue orders) as a current-state indicator.
- `OPEN` Delay Reports as a current-state indicator.
- Production progress summary and active-order details grouped by customer.
- Read-only access to all Production Issue Reports.
- Read-only access to order-status audit history.
- Consumer growth is excluded from Phase 1.

**Important Data Boundary**

Total Order Value and Revenue Received are different metrics.

- Revenue Received is based on payment records actually received.
- Total Order Value must use a separately defined order-value source and calculation.
- Total Order Value must not be derived from received payments.
- The source, definition, and currency of Total Order Value remain `UNDECIDED`.

**Admin Dashboard**

- Incoming orders.
- Sampling and sample/look approval statuses.
- LoA status.
- Payment recording and monitoring.
- Customer and order data.
- No access to Production Issue Reports.

**PPIC Dashboard**

- Orders awaiting Production Readiness checks.
- Orders in Waiting / On Hold.
- Orders requiring Functional Layout preparation or currently undergoing preparation.
- Cutting, Sewing, QC, Rework, and Finishing operational monitoring.
- Orders that have not received a progress update.
- `OPEN` Delay Reports.
- All Production Issue Reports and their follow-up.

QC and Rework may appear as operational monitoring information, but their percentage-progress presentation must follow the confirmed measurement rules.

**Production Staff Dashboard**

- Only orders currently `IN_PROGRESS` within the relevant department, subject to approved department and order visibility rules.
- Relevant product details, Functional Layout, target completion, and work instructions.
- Ability to create Production Issue Reports and view their own reports.
- No production progress percentage entry in Phase 1.

The overall order progress formula, dashboard aggregation rules, date basis, and default reporting periods remain subject to the Business Rules and Open Decisions.

### 3.11 In-App Notifications

- In-App Notifications in Phase 1 are used for Production Issue Reports.
- PPIC receives a notification when Production Staff submits a new report.
- The reporting Production Staff member receives a notification whenever the report status changes.
- `READ` / `UNREAD` status is maintained separately for each user.
- Delay Report emails are a separate notification flow from Production Issue Report In-App Notifications.
- Other notification types must not be assumed to be active in Phase 1 unless explicitly confirmed.

---

## 4. UI/UX & Design Guidelines

### 4.1 Design Direction

- The application is a React SPA with role-based navigation and dashboards.
- The interface must support operational work: order tables, filters, forms, statuses, timelines, progress indicators, order details, reports, and notifications.
- Important information such as current status, delays, unmet prerequisites, and required actions must be clearly visible.
- Features and actions unavailable to a role must not be presented as usable functions.
- The backend must still enforce all permissions independently of frontend visibility.

### 4.2 Theme & Components

- shadcn/ui and Tailwind CSS are the proposed frontend component direction.
- The proposed OKLCH theme — lime primary, olive-tinted neutrals, emerald chart ramp, and `0.45rem` radius — has `PROPOSED` status and is not yet the finalized design system.
- Use semantic design tokens instead of hard-coded colors.
- Light/dark mode and the default theme remain `UNDECIDED`.
- Required components include data tables, filters, forms, confirmation dialogs, status badges, timelines, progress bars, notification lists, and attachment previews.

### 4.3 Responsive & Accessibility

- The layout must be usable on desktop and tablets relevant to operations.
- Phase 1 prioritizes the desktop experience.
- A native mobile application is out of scope.
- Contrast, form labels, basic keyboard navigation, and validation feedback must be considered.

### 4.4 Frontend Structure

The final structure will be defined in `docs/architecture/APPLICATION_ARCHITECTURE.md`.

The following is a suggested starting structure:

```text
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
```

---

## 5. Explicit Non-Goals (Crucial)

The following will not be built in Phase 1 unless a scope change is explicitly approved:

- Do not build a full Inventory Management module.
- Do not build Stock Movement, Stock Opname, Stock Variance, Material Consumption, HPP/Costing, Leftover Goods, or Bonus Goods.
- Do not build Full Procurement Management.
- Do not build Fashion Retail.
- Do not build a Customer Portal or customer login accounts.
- Do not build a native mobile client.
- Do not introduce microservices without a genuine need and a documented architectural decision.
- Do not move business logic or business truth into the frontend.
- Do not allow Production Staff to change production progress percentages in Phase 1; progress entry remains PPIC's responsibility.
- Do not allow direct editing of protected order details after LoA reaches `APPROVED`; use the Order Change Request process within its defined limits.
- Do not allow specification changes through the normal workflow after Mass Production has started.
- Do not bypass prerequisite validation during order status transitions.
- Do not assume Functional Layout has a separate approval gate unless explicitly confirmed.
- Do not assume DP is a production prerequisite without an approved business rule.
- Do not assume QC/Rework percentage-progress mechanics before they are defined.
- Do not assume that a Delay Report automatically means an order is overdue.
- Do not derive Total Order Value from payments actually received.
- Do not grant order-status audit-history access to roles other than Owner.
- Do not allow Production Staff to access issue reports submitted by other staff members.
- Do not grant Admin access to Production Issue Reports in Phase 1.
- Do not treat Packing, Delivery, or Customer Pickup as part of the 9-day internal mass-production duration reference.
- Do not invent unconfirmed business rules, statuses, durations, progress formulas, or calendar policies. Mark them as `UNKNOWN`, `UNDECIDED`, or `PROPOSED` and request stakeholder validation.
- Do not build full accounting, invoicing, or financial reporting modules. Phase 1 only records payments actually received and provides basic payment monitoring.

---

## 6. Open Decisions

The following items must be addressed in the relevant business or technical documents before implementing features that depend on them.

| No. | Open Decision                                                                                                                                            | Status      |
| --: | -------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------- |
|   1 | QC policy: sampling inspection or 100% inspection.                                                                                                       | `UNDECIDED` |
|   2 | QC/Rework progress measurement method.                                                                                                                   | `UNDECIDED` |
|   3 | Overall order progress formula and stage weighting.                                                                                                      | `UNDECIDED` |
|   4 | Working calendar: national and company holidays beyond the temporary Monday–Friday calendar.                                                             | `UNDECIDED` |
|   5 | Delay calculation: aggregation of multiple reports, overlapping reports, and estimated completion date calculation.                                      | `UNDECIDED` |
|   6 | Overdue definition: whether the indicator uses the original planned completion date or current estimated completion date.                                | `UNDECIDED` |
|   7 | QC/Rework duration allocation relative to the 9-day internal and 10–14-day customer-facing targets.                                                      | `UNDECIDED` |
|   8 | Sampling and LoA rejection consequences, including cancellation and resubmission rules.                                                                  | `UNDECIDED` |
|   9 | Payment correction and cancellation workflow, including transaction handling after Owner approval.                                                       | `UNDECIDED` |
|  10 | Dashboard period semantics: default period and date basis for New Orders, Total Order Value, Revenue Received, and progress metrics.                     | `UNDECIDED` |
|  11 | Production Staff department assignment and order visibility rules.                                                                                       | `UNDECIDED` |
|  12 | Hosting/deployment target and Go runtime proof of concept.                                                                                               | `UNDECIDED` |
|  13 | Design system: theme, palette, and display mode approval.                                                                                                | `UNDECIDED` |
|  14 | Production Readiness: complete checklist and responsible party for each prerequisite.                                                                    | `UNDECIDED` |
|  15 | Waiting / On Hold: allowed reasons, resume conditions, and repeated waiting-period support.                                                              | `UNDECIDED` |
|  16 | Fulfillment confirmation: required evidence and responsible user for confirming Delivery or Customer Pickup.                                             | `UNDECIDED` |
|  17 | Audit History: complete list of audited actions, retention policy, and visibility beyond explicitly defined Owner access.                                | `UNDECIDED` |
|  18 | Production Issue Report: issue categories and detailed resolution/follow-up transitions.                                                                 | `UNDECIDED` |
|  19 | Notification recipients and event mapping beyond the explicitly defined Production Issue Report and Delay Report flows.                                  | `UNDECIDED` |
|  20 | Order Change Request: detailed status transitions, cancellation rules, and requests affecting orders already being prepared for production.              | `UNDECIDED` |
|  21 | Customer email: source of the customer's email address and whether email is mandatory for customer-facing Delay Report notifications.                    | `UNDECIDED` |
|  22 | Total Order Value: source, definition, calculation, and currency.                                                                                        | `UNDECIDED` |
|  23 | FOB payment prerequisite: whether received DP or a minimum payment amount is required before Production Readiness, PPIC Preparation, or Mass Production. | `UNDECIDED` |
|  24 | Functional Layout: whether a separate review or approval is required before Mass Production.                                                             | `UNDECIDED` |
|  25 | Workflow responsibility: detailed role assignments for Sampling, Sample/Look Approval, LoA authorization, Packing, and Delivery / Customer Pickup.       | `UNDECIDED` |
|  26 | Customer information snapshot strategy when an order is created.                                                                                         | `UNDECIDED` |
|  27 | Order total quantity storage and calculation strategy.                                                                                                   | `UNDECIDED` |
|  28 | Minimum product reference image requirement.                                                                                                             | `UNDECIDED` |

### Approval Note

This PRD remains **Draft v0.4** until stakeholders approve its contents and Phase 1 scope.

Open Decisions must be resolved in the appropriate supporting documents before dependent functionality is implemented.

Requirement changes after approval must be recorded as a PRD revision or an explicitly approved scope change.
