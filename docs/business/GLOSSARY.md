# Glossary

**Status:** PARTIALLY CONFIRMED

This document defines business, operational, and technical terminology
used throughout the Garment ERP project.

Definitions must remain consistent with:

- PRD.md
- BUSINESS_WORKFLOW.md
- BUSINESS_RULES.md
- USER_ROLES.md
- USER_PERSONAS.md

A glossary definition does not independently establish a business rule,
workflow transition, permission, calculation, or implementation requirement.

---

## 1. System & Architecture

| Term                | Meaning                                                                                                                                             |
| ------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------- |
| ERP                 | Enterprise Resource Planning system.                                                                                                                |
| Headless ERP        | An ERP whose core functionality is exposed through APIs and is independent of a specific user interface.                                            |
| ERP Core            | The Laravel / PHP backend responsible for business logic, workflows, validation, authorization, persistence, notifications, and API contracts.      |
| Laravel             | The active backend framework used to implement ERP Core in Phase 1.                                                                                 |
| Previous Go Backend | The previous backend implementation, which has been discontinued. It is not part of the active Phase 1 architecture.                                |
| Modular Monolith    | One deployable application organized into logically separated modules.                                                                              |
| Monorepo            | A single repository containing multiple applications or packages. This definition does not independently confirm the project's repository strategy. |
| REST API            | An HTTP-based interface through which clients communicate with ERP Core.                                                                            |
| Authentication      | Verification of a user's identity.                                                                                                                  |
| Authorization       | Determining whether an authenticated user may perform an action or access a resource.                                                               |
| RBAC                | Role-Based Access Control; an authorization model based on assigned roles and permissions.                                                          |
| Audit Log           | A historical record of relevant system activities, including actor and timestamp.                                                                   |
| Source of Truth     | The authoritative source for a particular business record or value.                                                                                 |

## 2. Production Types & Orders

| Term                    | Meaning                                                                                                                                                                                                                                                                          |
| ----------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| CMT                     | Cut, Make, Trim. The customer supplies main materials; the company provides production execution, labor, thread, and supporting accessories.                                                                                                                                     |
| FOB                     | Production model in which the company procures required materials and performs production. The customer provides a Down Payment (DP) according to the agreed commercial terms. Whether DP receipt is mandatory before Production Readiness or Mass Production remains UNDECIDED. |
| Fashion Retail          | A future production model, excluded from Phase 1.                                                                                                                                                                                                                                |
| Order                   | A customer production request containing customer information, product references, size quantities, and production type.                                                                                                                                                         |
| Order Status            | The current business state of an order, as defined in BUSINESS_WORKFLOW.md.                                                                                                                                                                                                      |
| Order Change Request    | A controlled request to modify protected order information, subject to applicable business restrictions and authorization.                                                                                                                                                       |
| Product Reference Image | An uploaded image representing the product or model associated with an order. Multiple reference images are supported; whether at least one image is mandatory remains UNDECIDED.                                                                                                |
| Ordered Quantity        | The total customer-requested quantity derived from recorded size quantities.                                                                                                                                                                                                     |
| Size Quantity           | The customer-requested quantity recorded for an individual size within an order.                                                                                                                                                                                                 |

## 3. Sampling & Commercial Agreement

| Term                              | Meaning                                                                                                                                       |
| --------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| Sampling                          | Preparation of a pattern and production of a sample before mass production.                                                                   |
| Pattern                           | Pattern-making activity. Internal reference duration: 2 days.                                                                                 |
| Sample                            | Sample-production activity. Internal reference duration: 2 days.                                                                              |
| Internal Sampling Duration        | The combined internal reference duration for Pattern and Sample: 4 days.                                                                      |
| Customer-facing Sampling Timeline | The communicated sampling timeframe: 7 working days.                                                                                          |
| Sample / Look Approval            | Customer review and decision on a produced sample/look. Supported statuses: PENDING, REVISION, APPROVED, REJECTED.                            |
| LoA                               | Letter of Agreement; the commercial agreement covering the MoU/agreement and payment terms.                                                   |
| MoU                               | Memorandum of Understanding; an agreement document associated with the LoA process.                                                           |
| LoA Status                        | The approval state of the commercial agreement. Supported statuses: PENDING, REVISION, APPROVED, REJECTED.                                    |
| Down Payment (DP)                 | An initial payment made by the customer according to agreed payment terms. Its mandatory role as a production prerequisite remains UNDECIDED. |
| Payment Recording                 | Recording payments actually received by the company.                                                                                          |
| Payment Record Correction         | A controlled process for correcting an existing payment record, subject to Owner approval or rejection.                                       |

## 4. Production Readiness & Preparation

| Term                    | Meaning                                                                                                                                                                                                                                                          |
| ----------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Production Readiness    | Evaluation of whether an order satisfies its applicable material and operational prerequisites before production preparation proceeds.                                                                                                                           |
| Waiting / On Hold       | A conditional order state used when one or more required readiness conditions remain unsatisfied. It is not a production stage.                                                                                                                                  |
| PPIC                    | Production Planning and Inventory Control. Responsible for readiness follow-up, Functional Layout preparation, production progress submission, Delay Report management, Production Issue Report follow-up, operational monitoring, and fulfillment confirmation. |
| Functional Layout       | A functional arrangement of production zones, such as Cutting, Sewing, Overlock, Embroidery, and Finishing. It is not a production workflow or production process stage.                                                                                         |
| Production Prerequisite | A material or operational condition required before an order can proceed. Detailed requirements are defined in BUSINESS_RULES.md.                                                                                                                                |
| PPIC Preparation        | Production preparation performed by PPIC after Production Readiness has been satisfied.                                                                                                                                                                          |
| Readiness Re-evaluation | Reassessment of applicable production prerequisites before an order proceeds from Waiting / On Hold.                                                                                                                                                             |

## 5. Mass Production & Fulfillment

| Term                                | Meaning                                                                                                                                                                              |
| ----------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Mass Production                     | The production phase following readiness and preparation. Officially starts when Cutting actually begins.                                                                            |
| Cutting                             | Cutting materials according to the product pattern. Internal reference duration: 2 days.                                                                                             |
| Sewing                              | Assembling garment components through sewing operations. Internal reference duration: 5 days.                                                                                        |
| QC                                  | Quality Control. Inspection performed after Sewing and before Finishing. PASS allows progression to Finishing; FAIL requires Rework followed by another QC inspection.               |
| Rework                              | Corrective production work performed following a QC failure, followed by another QC inspection.                                                                                      |
| Finishing                           | Completing garment finishing operations. Internal reference duration: 2 days.                                                                                                        |
| Internal Mass Production Duration   | The combined internal reference duration for Cutting, Sewing, and Finishing: 9 days. QC and Rework are excluded from this baseline.                                                  |
| Customer-facing Production Timeline | The communicated mass-production timeframe: 10–14 working days.                                                                                                                      |
| Packing                             | Preparing completed production output for delivery or customer pickup. Duration and detailed requirements remain UNDECIDED.                                                          |
| Delivery                            | Handing the completed order over to the customer. Confirmation requirements remain UNDECIDED.                                                                                        |
| Customer Pickup                     | Collection of the completed order by the customer. Confirmation requirements remain UNDECIDED.                                                                                       |
| Fulfillment Confirmation            | Confirmation that required fulfillment activities, including Packing and Delivery / Customer Pickup, have been completed. PPIC is responsible for confirming fulfillment completion. |
| Completed                           | Final order status after all required production and fulfillment activities have been completed and confirmed. Finishing completion alone does not establish order completion.       |

Internal reference durations and customer-facing timelines are separate measurements.

Internal duration values must not automatically be interpreted as working days unless explicitly established by the applicable business rules.

## 6. Timeline & Progress Monitoring

| Term                      | Meaning                                                                                                                                                                        |
| ------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Internal Duration         | The reference duration allocated internally for an activity or process.                                                                                                        |
| Customer-facing Timeline  | The expected timeframe communicated to the customer.                                                                                                                           |
| Planned Start Date        | The scheduled start date of an activity or process.                                                                                                                            |
| Planned Completion Date   | The originally scheduled completion date.                                                                                                                                      |
| Actual Start Date         | The recorded date an activity or process actually starts.                                                                                                                      |
| Actual Completion Date    | The recorded date an activity or process actually finishes.                                                                                                                    |
| Estimated Completion Date | The currently estimated completion date, subject to applicable authorized updates.                                                                                             |
| Planned Duration          | The duration allocated to an activity or process in its original plan.                                                                                                         |
| Actual Duration           | The recorded duration of actual execution, distinct from planned duration. Detailed calculation rules must follow approved timeline requirements.                              |
| Planned vs Actual         | Comparison between scheduled and recorded execution values, including dates and durations.                                                                                     |
| Progress Update           | A historical percentage-progress record submitted by PPIC, including timestamp and submitting user.                                                                            |
| Completion Percentage     | The reported progress percentage for a monitored production stage: Cutting, Sewing, or Finishing. It is not derived from actual quantity divided by target quantity.           |
| Current Process           | The production activity currently being executed, according to BUSINESS_WORKFLOW.md.                                                                                           |
| Current Workflow State    | The current business state of an order within its overall workflow.                                                                                                            |
| Bottleneck                | A process or operational constraint that limits overall production throughput. It must not be inferred solely from the Current Process.                                        |
| Working Day               | A day counted for operational duration calculations. The temporary Phase 1 calendar uses Monday–Friday, excluding weekends. National and company holiday rules remain UNKNOWN. |

## 7. Delay, Issue & Notifications

| Term                          | Meaning                                                                                                                                                                 |
| ----------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Delay                         | A condition in which an activity or order experiences a delay relative to its applicable timeline.                                                                      |
| Delay Report                  | A formal report created and managed by PPIC to document and follow up reported production delays. Supported statuses: OPEN and RESOLVED.                                |
| Overdue                       | An order that satisfies the approved overdue calculation criteria. Its calculation basis remains UNDECIDED.                                                             |
| Estimated Additional Duration | The additional duration recorded in a Delay Report, expressed in working days.                                                                                          |
| Production Issue Report       | An operational issue report submitted by Production Staff and followed up by PPIC.                                                                                      |
| Issue Status                  | The status of a Production Issue Report: OPEN, IN_PROGRESS, or RESOLVED.                                                                                                |
| In-App Notification           | A notification delivered within the application to an authorized recipient.                                                                                             |
| READ / UNREAD                 | The per-user read state of an In-App Notification.                                                                                                                      |
| Delay Report Email            | An email dispatch initiated when a Delay Report is created or resolved. Intended recipients are PPIC, Owner, and Customer, subject to approved recipient configuration. |
| Notification Dispatch         | The initiation of a notification delivery attempt. Dispatch initiation does not guarantee successful delivery.                                                          |

## 8. Quantity & Costing

| Term                         | Meaning                                                                                        | Phase 1 Status                                |
| ---------------------------- | ---------------------------------------------------------------------------------------------- | --------------------------------------------- |
| Ordered Quantity             | The total customer-requested quantity derived from recorded size quantities.                   | CONFIRMED                                     |
| Target Quantity              | The planned number of production units. Distinct from the customer-requested Ordered Quantity. | OUT OF SCOPE for production quantity tracking |
| Actual Quantity              | The number of units actually produced.                                                         | OUT OF SCOPE for actual-piece tracking        |
| Variance                     | The difference between actual and target production quantities.                                | UNDECIDED                                     |
| Production Quantity Shortage | A condition where actual production quantity is below target quantity.                         | UNDECIDED                                     |
| Completed Quantity           | Units satisfying a defined production completion condition.                                    | UNDECIDED                                     |
| OVERPRODUCTION               | A condition where actual production quantity exceeds target quantity.                          | UNDECIDED                                     |
| Reject Quantity              | Units rejected during quality inspection.                                                      | OUT OF SCOPE for Phase 1 tracking             |
| Rework Quantity              | Units requiring corrective production work.                                                    | OUT OF SCOPE for Phase 1 tracking             |
| HPP                          | Harga Pokok Produksi; production cost or cost of goods produced.                               | OUT OF SCOPE                                  |
| Stock Opname                 | Physical stock counting and reconciliation.                                                    | OUT OF SCOPE                                  |
| Material Consumption         | Quantity of materials consumed during production.                                              | OUT OF SCOPE                                  |
| Leftover Goods               | Remaining materials or goods after production.                                                 | OUT OF SCOPE                                  |

No quantity-based production completion formula, variance calculation,
or costing formula is established unless explicitly confirmed
in BUSINESS_RULES.md.

Ordered Quantity must not be confused with Actual Quantity or Target Quantity.

## 9. Business & Documentation Terminology

| Term                  | Meaning                                                                                               |
| --------------------- | ----------------------------------------------------------------------------------------------------- |
| SOP                   | Standard Operating Procedure; documented instructions describing how an operation is performed.       |
| Business Workflow     | The documented sequence of business activities, states, and transitions.                              |
| Business Rule         | A constraint, validation, condition, or calculation governing business operations.                    |
| User Persona          | A representation of a user group based on actual responsibilities, needs, problems, and system usage. |
| User Role             | An assigned system role used to determine access and authorization.                                   |
| Permission            | An explicitly defined authorization to perform an action or access a resource.                        |
| UNKNOWN               | Information that is currently not known.                                                              |
| UNDECIDED             | A decision that has not yet been finalized.                                                           |
| PROPOSED              | A suggested requirement or design awaiting validation and approval.                                   |
| CONFIRMED             | A requirement or decision explicitly approved by the business.                                        |
| CONFIRMED — TEMPORARY | A confirmed decision that applies temporarily and may be revised.                                     |
| FUTURE                | A capability intentionally deferred for potential implementation in a later phase.                    |
| OUT OF SCOPE          | A capability explicitly excluded from the current project scope.                                      |

---

## Terminology Rules

1. Use consistent terminology across PRD, BUSINESS_WORKFLOW, BUSINESS_RULES, USER_ROLES, USER_PERSONAS, API contracts, and implementation.
2. Do not use different names for the same business concept without documenting their distinction.
3. FUTURE and OUT OF SCOPE capabilities must not be treated as Phase 1 implementation requirements.
4. Glossary definitions do not independently authorize business rules, workflow transitions, or permissions.
5. Preserve unresolved meanings as UNKNOWN or UNDECIDED until business confirmation.
6. Distinguish Ordered Quantity from Target Quantity and Actual Quantity.
7. Distinguish internal reference duration from customer-facing timelines.
8. Distinguish Current Process from Bottleneck.
9. Laravel / PHP is the active backend framework for Phase 1. The previous Go implementation is discontinued.
10. Update this document whenever an approved business decision changes an existing definition.
