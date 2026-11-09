# Database Design

**Product:** Garment ERP Core
**Status:** APPROVED DESIGN GOVERNANCE BASELINE — PHASE 1
**Database:** PostgreSQL
**Database Name:** `garment_erp`
**Backend:** Laravel / PHP

This document establishes the approved principles and governance for Phase 1 database design.

It does not represent a finalized physical database schema.

The actual schema will be approved incrementally through entity design reviews and version-controlled migrations.

---

## 1. Database Design Approach

The database schema is designed incrementally.

Each entity must be analyzed, documented, and reviewed before its database implementation.

Do not design the entire database schema upfront.

The development order represents the intended capability analysis and dependency sequence.

It does not prescribe a one-to-one mapping between business capabilities and physical database tables.

Multiple capabilities may share a table.

A capability may require multiple tables when justified by its lifecycle and integrity requirements.

The database must support approved business workflows without introducing unapproved business rules or unnecessary entities.

### 1.1 Database Platform

- Database: PostgreSQL.
- Database Name: `garment_erp`.
- Database access owner: Laravel ERP Core.

### 1.2 Database Ownership

Laravel ERP Core is the only application component responsible for application-level database access.

The React + Vite frontend must not access PostgreSQL directly.

All application database operations must pass through backend application logic.

The backend is responsible for enforcing business rules before persisting business changes.

---

## 2. Database Design Principles

### 2.1 Incremental Schema Design

- Design the database incrementally.
- Start with foundational entities required by the implementation roadmap.
- Add entities only when required by approved capabilities.
- Review existing relationships before introducing new entities.
- Avoid speculative tables and columns.
- Update the ERD whenever the schema changes.
- Do not create tables solely because an API resource exists.

### 2.2 Business Domain Alignment

The database schema must follow:

- `docs/business/PRD.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`
- `docs/api/API_CONTRACTS.md`

Business rules must not be invented during database implementation.

Unresolved business decisions must remain explicitly UNKNOWN or UNDECIDED.

### 2.3 Source of Truth

Every persisted business attribute must have a clearly defined source of truth.

Examples:

- Customer master information belongs to Customer records.
- Order-specific information belongs to Order records.
- Customer snapshot strategy at order creation remains UNDECIDED.
- Sampling activity information belongs to sampling records.
- Sample / Look Approval decisions belong to approval records.
- Payment records represent received payments.
- Production progress records represent PPIC-submitted progress.
- Audit records preserve relevant historical actions.

Do not duplicate authoritative data across unrelated entities without a documented reason.

### 2.4 Data Integrity

The database must enforce appropriate integrity constraints.

Depending on the entity, these may include:

- Primary keys.
- Foreign keys.
- Unique constraints.
- NOT NULL constraints.
- CHECK constraints.
- Appropriate indexes.
- Transactional consistency.

Database constraints must support business integrity.

They must not replace backend business validation.

Do not create constraints for unresolved business rules.

### 2.5 Historical Data

Business records that require historical traceability must preserve their relevant history.

Examples:

- Order specification changes.
- Sampling revisions and decisions.
- LoA revisions and decisions.
- Payment corrections.
- Production progress updates.
- Delay Report history.
- Production Issue Report history.
- Workflow state changes.

Do not overwrite historical information when business rules require the previous version or event to remain traceable.

Business history and audit history are separate concerns.

**Business history** preserves the lifecycle and previous versions of business records.

**Audit history** records relevant user actions and system events according to approved audit requirements.

A business operation may require both forms of records.

Do not assume a general audit log replaces domain-specific business history or versioning.

Historical requirements do not automatically require a separate history table for every entity.

The implementation may use version records, event records, or another justified persistence strategy.

---

## 3. Entity Design Requirements

Every proposed entity must be documented before implementation.

### 3.1 Entity Definition

For every entity, document:

**Purpose**

Why does this entity exist?

**Data Ownership**

What information does this entity own?

**Attributes**

For every field, define:

- Name.
- Data type.
- Required or optional.
- Nullable or non-nullable.
- Default value, if applicable.
- Validation constraints.
- Description.
- Data origin, where applicable.

**Relationships**

Document:

- Relationship type.
- Foreign key ownership.
- Cardinality.
- Optionality.
- Deletion behavior.

**Source of Truth**

Explain why this entity is authoritative for its data.

**Constraints**

Document database-level integrity requirements.

**Lifecycle**

Explain how records are created, updated, transitioned, archived, or retained.

**Historical Requirements**

Identify whether the entity requires versioning, event history, or audit records.

### 3.2 Entity vs Attribute vs Workflow

Do not automatically create a database table for every business term, API resource, workflow stage, or role.

Evaluate whether a concept should be represented as:

- Standalone entity.
- Attribute of an existing entity.
- Enum or constrained value.
- Relationship.
- Historical record.
- Derived value.
- Workflow state.

Examples:

- CMT and FOB are production types.
- PPIC is an operational role, not automatically an entity.
- Production stages are workflow concepts.
- Timeline information does not automatically require a Timeline table.
- An API resource does not automatically require a matching table.
- Functional Layout does not automatically require a separate table.

Document the reasoning before introducing a new entity.

### 3.3 Capability and Entity Boundaries

An API resource does not require a dedicated database table.

For example:

`/api/v1/orders/{orderId}/production`

may represent a production capability associated with an Order without requiring a separate `production_orders` table.

The physical entity boundary must be determined during database design.

---

## 4. Entity Development Order

The following order defines the intended implementation progression.

It is a dependency-oriented development guide, not a requirement to create every item as a separate table.

### 4.1 Foundation

1. Customer.
2. Order.
3. Order Product Image / Reference.
4. Order Size Quantity.

### 4.2 Order Workflow

5. Sampling.
6. Sampling Revision / History.
7. Sample / Look Approval.
8. LoA.
9. LoA Revision / History.
10. Payment.
11. Payment Correction.

### 4.3 Production Preparation

12. Production Readiness.
13. Waiting / On Hold History.
14. Functional Layout.

### 4.4 Production Execution

15. Production Tracking.
16. Production Stage Progress.
17. QC / Rework Records.
18. Timeline and Duration Records, if required.
19. Packing and Fulfillment.

### 4.5 Operational Monitoring

20. Delay Report.
21. Delay Report History and Email Dispatch Records.
22. Production Issue Report.
23. Production Issue Report History.
24. Order Change Request.
25. Notification.
26. Audit History.

### 4.6 Reporting

27. Dashboard data aggregation, if required.
28. Supporting indexes and query optimizations.

The actual entity boundaries and relationships must be determined during each capability's design.

Do not create separate entities merely because they appear in this development order.

---

## 5. Data Modeling Rules

### 5.1 Customer and Order

- Customer information must have a clear ownership model.
- Order records must reference appropriate customer information.
- Order specifications must remain traceable.
- Orders must support CMT and FOB.
- Order size quantities must support multiple sizes and quantities.
- Do not represent an order using only one size and one quantity.
- Do not assume all orders share identical size options.

Order specification versioning must preserve the distinction between the currently approved specification and previous approved specifications.

Order Change Request records must remain traceable to resulting approved specification changes.

Do not assume audit logs alone satisfy versioning requirements.

The physical versioning strategy remains UNDECIDED until entity design is approved.

### 5.2 Size Quantities

Confirmed requirements:

- Quantities are recorded individually by size.
- Negative quantities are prohibited.
- Total quantity equals the sum of recorded size quantities when calculated.

The following remain UNDECIDED:

- Whether total quantity is stored.
- Whether total quantity is calculated dynamically.
- Whether both storage and calculation are used.
- Custom sizes.
- Multiple distinct product models per order.
- Zero quantity handling for individual sizes.

Do not introduce additional quantity constraints without business approval.

### 5.3 Product Images and References

- Image modeling must be explicitly designed.
- Do not assume an `image_url` column directly on Order.
- Do not assume a Product, SKU, or Design entity without justification.
- Do not store binary image data in PostgreSQL without approval.
- File metadata and physical file storage must have clearly defined responsibilities.

The following remain UNDECIDED:

- Mandatory image count.
- Accepted formats.
- File size constraints.
- Storage provider.
- Retention strategy.

### 5.4 Production Types

Phase 1 supports only:

- CMT.
- FOB.

Fashion Retail is excluded from Phase 1.

Determine whether production type should be represented by an enum, constrained field, or reference entity during Order database design.

### 5.5 Workflow and Status

- Persisted statuses must follow approved business vocabulary.
- State transitions must be validated by backend logic.
- Do not allow arbitrary status changes through generic updates.
- Preserve required workflow history.
- Do not invent additional statuses.

Sampling activities and Sample / Look Approval must remain distinct data responsibilities.

Supported Sample / Look Approval statuses:

- PENDING.
- REVISION.
- APPROVED.
- REJECTED.

Supported LoA statuses:

- PENDING.
- REVISION.
- APPROVED.
- REJECTED.

Do not assign these statuses to unrelated business records.

### 5.6 Production Progress

Confirmed percentage-monitored stages:

- CUTTING.
- SEWING.
- FINISHING.

QC and REWORK must be operationally monitored.

Their percentage measurement method remains UNDECIDED.

PPIC is responsible for submitting permitted production progress.

Every percentage-based progress update must preserve:

- Percentage value.
- Submission timestamp.
- Submitting user.
- Related order.
- Production stage.

Historical progress updates must not be overwritten.

Unchanged percentages submitted as new updates must still be recorded as new submissions.

Do not introduce an overall progress formula or stage weighting without an approved business definition.

Actual-piece production tracking is outside Phase 1.

Reject and Rework quantity tracking are not confirmed requirements.

### 5.7 Timeline

Timeline data must distinguish the following values where applicable:

- Planned Start Date.
- Planned Completion Date.
- Actual Start Date.
- Actual Completion Date.
- Estimated Completion Date.
- Planned Duration.
- Actual Duration.

Original planned dates must remain traceable.

The system must not overwrite original planned dates when estimated completion changes.

The detailed persistence and history strategy remains subject to the approved timeline entity design.

Temporary Phase 1 working calendar:

- Monday–Friday: Working Days.
- Saturday–Sunday: Non-working Days.

National and company holidays remain UNKNOWN.

### 5.8 Payments

- Payment records must represent actual received payments.
- Payment corrections must preserve the original record.
- Correction history must remain traceable.
- Duplicate transactions must not be counted more than once.
- Payment recording must remain separate from Production Readiness unless an approved business rule establishes a payment gate.

The mechanism for identifying duplicate transactions remains UNDECIDED.

Payment methods, references, cancellation, and partial payment rules must follow approved requirements.

### 5.9 Production Readiness and Waiting

Production Readiness must be evaluated separately.

Potential dependencies include:

- Main material availability.
- Fabric availability.
- Material purchasing.
- Operational requirements.
- Other approved prerequisites.

The complete readiness checklist remains UNKNOWN.

Waiting / On Hold is a conditional order state.

Waiting reason categories and resume conditions remain UNDECIDED.

Do not create a universal checklist or mandatory waiting category without business approval.

### 5.10 Functional Layout

Functional Layout represents physical production work-area arrangements.

It is not a production process stage.

It must not contribute to Mass Production progress.

The exact layout data structure remains UNDECIDED.

A separate layout approval requirement has not been confirmed.

### 5.11 QC and Rework

Confirmed sequence:

`CUTTING → SEWING → QC`

- QC PASS → FINISHING.
- QC FAIL → REWORK → QC AGAIN.

The following remain unresolved:

- QC duration.
- Rework duration.
- Inspection coverage.
- Rework limits.
- Escalation rules.
- Percentage-progress mechanics.
- Detailed operational statuses.

Do not invent these requirements.

### 5.12 Fulfillment

Confirmed sequence:

`FINISHING → PACKING → DELIVERY / CUSTOMER PICKUP → FULFILLMENT CONFIRMED → COMPLETED`

PPIC confirms fulfillment completion.

The following remain UNDECIDED:

- Packing duration.
- Delivery duration.
- Customer Pickup duration.
- Fulfillment evidence.
- Detailed fulfillment responsibilities.
- Physical execution and confirmation data requirements.

### 5.13 Audit History

- Required business changes must be traceable.
- Audit records must not be editable through ordinary application operations.
- Historical records must follow approved retention rules.
- Audit visibility must follow authorization rules.

Business history and audit history must remain separate concerns.

Owner's confirmed read-only access applies to order-status audit history.

Access to the complete application audit log remains subject to approved authorization requirements.

---

## 6. Database Migrations

### 6.1 Migration Management

Laravel provides a framework-native database migration system.

Laravel database migrations are the default migration approach for the Laravel ERP Core application.

The detailed migration workflow and conventions must be established during the database implementation sprint.

Do not introduce an additional migration framework without documented technical justification.

### 6.2 Migration Requirements

Every migration must:

- Have a clear purpose.
- Be version-controlled.
- Be reproducible.
- Be reviewed before application.
- Preserve existing data where required.
- Include rollback considerations.
- Be tested against the expected database state.

### 6.3 Schema Changes

- Do not silently modify an existing schema.
- Update entity documentation when schema changes.
- Update the ERD when relationships or entities change.
- Review affected backend queries and API contracts.
- Review data migration requirements before removing or changing columns.
- Avoid destructive production migrations without an approved migration plan.

### 6.4 Migration Ownership

Database migrations belong to:

`apps/api/database/migrations/`

The migration history must remain synchronized with the actual PostgreSQL schema.

Manual production schema modification must not replace version-controlled migrations.

---

## 7. Database Access Strategy

Database access must be implemented through Laravel ERP Core.

Potential Laravel database access mechanisms include:

- Eloquent ORM.
- Laravel Query Builder.
- Parameterized SQL through Laravel database facilities.

The final database access strategy remains UNDECIDED.

The selected approach must:

- Support PostgreSQL.
- Support parameterized queries.
- Support database transactions.
- Make query behavior understandable and maintainable.
- Avoid unnecessary abstraction.
- Support Laravel migrations.
- Be compatible with the project's testing strategy.

Do not introduce an ORM, query builder, or database abstraction without evaluating its suitability for the project.

A Repository pattern is optional, not mandatory.

---

## 8. Transactions and Data Consistency

Related business changes must be handled consistently.

Use database transactions when an operation requires multiple related database changes to succeed or fail together.

Examples:

- Creating an order with related records.
- Applying an approved Order Change Request.
- Recording an approved payment correction.
- Executing a workflow transition with multiple
  required persistent changes.

Transaction boundaries must follow actual business operation requirements.

Do not introduce transaction behavior that silently bypasses business authorization or validation.

---

## 9. Indexing and Performance

Indexes must be introduced based on actual query requirements.

Consider:

- Foreign key lookup patterns.
- Frequently filtered fields.
- Sorting requirements.
- Dashboard query patterns.
- Historical record retrieval.
- Unique business identifiers.

Do not create indexes speculatively.

Performance optimization must not compromise data integrity.

Dashboard aggregation must use authoritative business records.

---

## 10. ERD and Database Documentation

ERD files must be stored in:

`docs/diagrams/database/`

The ERD must reflect the actual approved database schema.

Requirements:

- Update the ERD when entities or relationships change.
- Use consistent entity and relationship naming.
- Show primary and foreign key relationships.
- Indicate cardinality.
- Distinguish optional and mandatory relationships.
- Do not include speculative entities as implemented schema.

Database entity documentation must be stored in:

`docs/database/`

The ERD and entity documentation must remain consistent with the actual migrations.

---

## 11. Open Database Design Decisions

| Decision                                              | Status                                       |
| ----------------------------------------------------- | -------------------------------------------- |
| Per-size quantity representation                      | Required capability; detailed design pending |
| Total quantity: stored, calculated, or both           | UNDECIDED                                    |
| Image modeling and storage                            | UNDECIDED                                    |
| Database access strategy                              | UNDECIDED                                    |
| Production Order as separate entity or part of Order  | UNDECIDED                                    |
| Production type representation                        | UNDECIDED                                    |
| Sampling and Sample / Look Approval relationships     | UNDECIDED                                    |
| LoA entity relationship with Order                    | UNDECIDED                                    |
| Timeline persistence and history model                | UNDECIDED                                    |
| QC and REWORK data model                              | UNDECIDED                                    |
| Overall production progress calculation               | UNDECIDED                                    |
| Waiting / On Hold history model                       | UNDECIDED                                    |
| Fulfillment data and evidence requirements            | UNDECIDED                                    |
| Audit history structure and retention                 | UNDECIDED                                    |
| Notification persistence and read-state model         | UNDECIDED                                    |
| Delay Report history and email dispatch records       | UNDECIDED                                    |
| Production Issue Report history model                 | UNDECIDED                                    |
| Order Change Request versioning strategy              | UNDECIDED                                    |
| Payment correction and duplicate transaction handling | UNDECIDED                                    |
| Customer information snapshot at order creation       | UNDECIDED                                    |

Do not resolve these decisions by assumption.

Each decision must be reviewed against approved business requirements before the affected entity is implemented.

---

## 12. Related Documentation

Business:

- `docs/business/PRD.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`

Architecture:

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`

API:

- `docs/api/API_CONTRACTS.md`

Engineering:

- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`

Diagrams:

- `docs/diagrams/database/`

---

## 13. Database Design Governance

This document defines the database design baseline for Garment ERP Core Phase 1.

The actual database schema must grow incrementally through approved entity designs and version-controlled migrations.

No entity, relationship, attribute, constraint, or calculation may be introduced solely because it appears convenient during implementation.

All database changes must remain consistent with:

- Approved business requirements.
- API contracts.
- Application architecture.
- System architecture.
- Approved technical decisions.
