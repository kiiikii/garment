# Glossary

Status: PARTIALLY CONFIRMED

This document defines business, operational, and technical terminology used throughout the Garment ERP project.

Definitions must remain consistent with the PRD, BUSINESS_WORKFLOW, and BUSINESS_RULES documents.

A glossary definition does not independently establish a business rule, workflow transition, permission, calculation, or implementation requirement.

Terms with unresolved meanings must remain explicitly marked.

- **Out of Scope** describes a capability explicitly excluded from the current project scope. It is a scope classification, not a requirement status.

## 1. System & Architecture

| Term             | Meaning                                                                                                                                             |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------------------- |
| ERP              | Enterprise Resource Planning system.                                                                                                                |
| Headless ERP     | An ERP system whose core functionality is exposed through APIs and does not depend on a specific user interface.                                    |
| ERP Core         | The Go application responsible for business logic, rules, workflows, validation, authorization, persistence, notification logic, and API contracts. |
| Modular Monolith | One deployable application organized into independent logical modules rather than separately deployed microservices.                                |
| Monorepo         | A single repository containing multiple applications, such as `apps/api` and `apps/web`.                                                            |
| REST API         | An HTTP-based API used by clients to communicate with the ERP Core.                                                                                 |
| RBAC             | Role-Based Access Control; an authorization model that determines access based on assigned roles and permissions.                                   |
| Authentication   | The process of verifying a user's identity.                                                                                                         |
| Authorization    | The process of determining whether an authenticated user is allowed to perform an action or access a resource.                                      |
| Audit Log        | A historical record of relevant system actions, including who performed an action and when.                                                         |
| Source of Truth  | The authoritative source from which a particular business record or value is obtained.                                                              |

## 2. Production Types & Order

| Term                 | Meaning                                                                                                                                                                                                                                       |
| -------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| CMT                  | Cut, Make, Trim production model. The customer provides the main material; the company provides labor, execution, thread, and supporting accessories.                                                                                         |
| FOB                  | Production model in which the company procures the required materials and performs production. The customer provides a Down Payment (DP) under the agreed payment terms. Whether DP is a mandatory production prerequisite remains UNDECIDED. |
| Fashion Retail       | A future production model; excluded from Phase 1.                                                                                                                                                                                             |
| Order                | A customer production request containing customer information, product references, size quantities, and production type.                                                                                                                      |
| Order Status         | The current business state of an order within its lifecycle. Confirmed workflow states and unresolved transitions are documented in BUSINESS_WORKFLOW and BUSINESS_RULES.                                                                     |
| Order Change Request | A controlled request to modify protected order information after LoA approval, subject to the applicable business restrictions.                                                                                                               |
| Product Reference    | An image used to describe the product or model associated with an order.                                                                                                                                                                      |

## 3. Sampling & Commercial Agreement

| Term                   | Meaning                                                                                                                                                 |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Sampling               | The process of preparing a pattern and producing a sample before mass production.                                                                       |
| Pattern                | The pattern-making activity within Sampling. Internal reference duration: 2 days.                                                                       |
| Sample                 | The sample-production activity within Sampling. Internal reference duration: 2 days.                                                                    |
| Sample / Look Approval | The review and decision process for a produced sample/look. Statuses: PENDING, REVISION, APPROVED, REJECTED.                                            |
| LoA                    | Letter of Agreement; the commercial agreement process covering the MoU/agreement, payment agreement, and payment terms according to company procedures. |
| MoU                    | Memorandum of Understanding; an agreement document associated with the commercial agreement process.                                                    |
| Down Payment (DP)      | An initial payment made by the customer. Its mandatory role as a production prerequisite remains UNDECIDED.                                             |
| Payment Recording      | Recording payments actually received by the company.                                                                                                    |
| Payment Correction     | A controlled process for correcting an existing payment record, subject to Owner approval or rejection.                                                 |

## 4. Production Preparation

| Term                    | Meaning                                                                                                                                                                                                                       |
| ----------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Production Readiness    | The evaluation of whether an order satisfies its applicable material and operational prerequisites before proceeding to PPIC Preparation. LoA approval allows this evaluation but does not automatically establish readiness. |
| Waiting / On Hold       | A conditional workflow state used when one or more required Production Readiness conditions have not been satisfied. It is not a production stage.                                                                            |
| PPIC                    | Production Planning and Inventory Control. In this system, PPIC is responsible for production preparation, readiness follow-up, Functional Layout preparation, progress updates, and operational monitoring.                  |
| Functional Layout       | A functional or process-oriented arrangement of production zones, such as Cutting, Sewing, Overlock, Embroidery, and Finishing. It is not a mass-production stage.                                                            |
| Production Prerequisite | A material or operational condition that must be satisfied before an order can proceed. Specific requirements are defined in BUSINESS_RULES.                                                                                  |

## 5. Mass Production & Fulfillment

| Term            | Meaning                                                                                                                                                                                                                  |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Mass Production | The production phase following preparation and readiness. Its operational sequence includes Cutting, Sewing, QC, possible Rework, and Finishing.                                                                         |
| Cutting         | The process of cutting materials according to the product pattern. Internal reference duration: 2 working days.                                                                                                          |
| Sewing          | The process of assembling garment components through sewing operations. Internal reference duration: 5 working days.                                                                                                     |
| QC              | Quality Control; the inspection process used to evaluate production quality. In the confirmed operational sequence, QC takes place after Sewing and before Finishing. Inspection coverage and duration remain UNDECIDED. |
| Rework          | The process of correcting products that fail QC before they undergo QC again. Detailed measurement and duration remain UNDECIDED.                                                                                        |
| Finishing       | The process of completing garment finishing operations after the applicable QC requirements are satisfied. Internal reference duration: 2 working days.                                                                  |
| Packing         | The fulfillment activity after Finishing. Duration and detailed responsibilities remain UNDECIDED.                                                                                                                       |
| Delivery        | The process of handing the completed order over to the customer. Detailed confirmation requirements remain UNDECIDED.                                                                                                    |
| Customer Pickup | The process in which the customer collects the completed order. Detailed confirmation requirements remain UNDECIDED.                                                                                                     |
| Completed       | The final order status after all required production and fulfillment activities have been completed and confirmed. Completion of Finishing alone does not mean that the order is Completed.                              |

## 6. Timeline & Progress Monitoring

| Term                      | Meaning                                                                                                                                                                                                                                         |
| ------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Internal Duration         | The reference duration allocated by the company for an activity or process. Its calendar basis must follow the applicable business rule and must not be assumed to be working days unless explicitly specified.                                 |
| Customer-facing Timeline  | The duration communicated to the customer as the expected timeframe for an order or production activity.                                                                                                                                        |
| Planned Start Date        | The scheduled date on which an activity or process is expected to start.                                                                                                                                                                        |
| Planned Completion Date   | The originally scheduled date on which an order or process is expected to finish. It must remain distinguishable from the Estimated Completion Date.                                                                                            |
| Actual Start Date         | The recorded date on which an activity or process actually starts.                                                                                                                                                                              |
| Actual Completion Date    | The recorded date on which an activity or process actually finishes.                                                                                                                                                                            |
| Estimated Completion Date | The currently estimated completion date, which may be updated through an applicable authorized business process. Creating or resolving a Delay Report does not automatically change this date.                                                  |
| Planned vs Actual         | A comparison between scheduled values and actual recorded values, such as start dates, completion dates, and durations.                                                                                                                         |
| Progress Update           | A historical record submitted by PPIC describing the reported percentage progress of a percentage-monitored production stage, including its timestamp and submitting user.                                                                      |
| Completion Percentage     | A percentage representing the progress reported by PPIC for a percentage-monitored production stage. In Phase 1, this applies to Cutting, Sewing, and Finishing. It is not calculated from actual produced quantity divided by target quantity. |
| Current Process           | The production activity or stage currently being executed for an order. Exact determination follows BUSINESS_WORKFLOW.                                                                                                                          |
| Bottleneck                | A production process or operational constraint that restricts overall production throughput.                                                                                                                                                    |
| Working Day               | A day counted when calculating operational durations. The temporary Phase 1 calendar uses Monday–Friday, excluding weekends. Holiday rules remain UNDECIDED.                                                                                    |

## 7. Delay & Issue Management

| Term                          | Meaning                                                                                                                                                                 |
| ----------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Delay                         | A condition in which an order or activity experiences a delay relative to its applicable timeline. A Delay Report does not automatically mean that an order is overdue. |
| Delay Report                  | A formal report created by PPIC to document and follow up a production delay.                                                                                           |
| Overdue                       | An order that meets the confirmed overdue calculation criteria. The applicable date basis remains UNDECIDED.                                                            |
| Estimated Additional Duration | The additional working-day duration recorded in a Delay Report.                                                                                                         |
| Production Issue Report       | An operational issue report submitted by Production Staff and followed up by PPIC.                                                                                      |
| Issue Status                  | The current status of a Production Issue Report: OPEN, IN_PROGRESS, or RESOLVED.                                                                                        |
| In-App Notification           | A notification delivered inside the application to an authorized user.                                                                                                  |
| READ / UNREAD                 | The per-user read state of an In-App Notification.                                                                                                                      |

## 8. Quantity & Costing Terminology

The following terms are recognized business terminology but do not represent
confirmed Phase 1 functionality.

| Term                 | Meaning                                                                            | Phase 1 Status |
| -------------------- | ---------------------------------------------------------------------------------- | -------------- |
| Target Quantity      | The planned number of units to be produced.                                        | FUTURE         |
| Actual Quantity      | The number of units actually produced.                                             | FUTURE         |
| Variance             | The difference between actual quantity and target quantity.                        | FUTURE         |
| SHORTAGE             | A condition where actual quantity is below target quantity.                        | FUTURE         |
| Completed Quantity   | The number of units that have reached the defined production completion condition. | FUTURE         |
| OVERPRODUCTION       | A condition where actual quantity exceeds target quantity.                         | FUTURE         |
| Reject Quantity      | The number of units rejected during quality inspection.                            | FUTURE         |
| Rework Quantity      | The number of units requiring rework.                                              | FUTURE         |
| Ordered Quantity     | The quantity requested by the customer, derived from the recorded size quantities. | CONFIRMED      |
| HPP                  | Harga Pokok Produksi; production cost or cost of goods produced.                   | OUT OF SCOPE   |
| Stock Opname         | Physical stock counting and reconciliation.                                        | OUT OF SCOPE   |
| Material Consumption | The quantity of materials consumed during production.                              | OUT OF SCOPE   |
| Leftover Goods       | Remaining materials or goods after production.                                     | OUT OF SCOPE   |

No quantity-based completion formula, shortage calculation, overproduction
calculation, or costing formula is confirmed for Phase 1.

## 9. Business & Documentation Terminology

| Term                  | Meaning                                                                                                       |
| --------------------- | ------------------------------------------------------------------------------------------------------------- |
| SOP                   | Standard Operating Procedure; documented instructions describing how a business process is performed.         |
| Business Workflow     | The documented sequence of business activities, statuses, and transitions.                                    |
| Business Rule         | A constraint, validation, condition, or calculation that governs business operations.                         |
| Primary Persona       | The single selected user persona representing the system's primary user needs. Selection remains UNDECIDED.   |
| User Role             | An assigned system role used to determine access and authorization.                                           |
| Permission            | An explicitly defined authorization to perform an action or access a resource.                                |
| UNKNOWN               | A term or requirement whose information is not currently known.                                               |
| UNDECIDED             | A requirement or business decision that has not yet been finalized.                                           |
| PROPOSED              | A suggested requirement or design that requires validation and approval.                                      |
| CONFIRMED             | A requirement or decision explicitly approved by the business.                                                |
| CONFIRMED — TEMPORARY | A confirmed decision that applies temporarily and may be revised.                                             |
| FUTURE                | A capability or requirement intentionally excluded from Phase 1 and potentially considered for a later phase. |
| OUT OF SCOPE          | A capability explicitly excluded from the current project scope.                                              |

---

## Terminology Rules

1. Use the same term consistently across PRD, BUSINESS_WORKFLOW, BUSINESS_RULES, API contracts, database documentation, and implementation.
2. Do not introduce alternative names for the same business concept without documenting the distinction.
3. A glossary definition does not authorize implementing a feature that is marked FUTURE or OUT OF SCOPE.
4. Do not infer a business rule from a term's definition alone.
5. When a term has multiple possible interpretations, preserve the ambiguity as UNKNOWN or UNDECIDED until stakeholders clarify it.
6. Update this glossary when an approved business decision changes an existing definition.
