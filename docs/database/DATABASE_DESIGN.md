# Database Design

Status: APPROVED DESIGN GOVERNANCE BASELINE — PHASE 1

This document establishes the approved principles and governance for Phase 1 database design.

It does not represent a finalized physical database schema.

The actual schema will be approved incrementally through entity design reviews and version-controlled migrations.

## Database Design Approach

The database schema is designed incrementally, entity by entity, based on approved business requirements and implementation needs.

Do not design the entire database schema upfront.

Each entity must be analyzed, documented, and reviewed before its database implementation.

The development order represents the intended capability analysis and dependency sequence.

It does not prescribe a one-to-one mapping between listed items and physical database tables.

Multiple capabilities may share a table, and a capability may require multiple tables when justified by its data lifecycle and integrity requirements.

The database must support the approved business workflows without introducing unapproved business rules or unnecessary entities.

## Database Platform

- Database: PostgreSQL
- Database Name: `garment_erp`

---

## 1. Database Design Principles

### 1.1 Incremental Schema Design

- Design the database incrementally.
- Start with the foundational entities required by Sprint 2.
- Add entities only when required by an approved capability.
- Review existing relationships before introducing new entities.
- Avoid speculative tables and columns.
- Update the ERD whenever the schema changes.

### 1.2 Business Domain Alignment

The database schema must follow:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`
- `docs/api/API_CONTRACTS.md`

Business rules must not be invented during database implementation.

Unresolved business decisions must remain explicitly UNDECIDED.

### 1.3 Source of Truth

Every persisted business attribute must have a clearly defined
source of truth.

Examples:

- Customer master information belongs to Customer records.
- Order-specific information belongs to Order records.
- The need to preserve a customer information snapshot when an order is created remains UNDECIDED.
- Sampling decisions belong to their corresponding sampling and approval records.
- Payment records represent received payments.
- Production progress records represent PPIC-submitted progress.
- Audit records preserve relevant historical actions.

Do not duplicate authoritative data across unrelated entities without a documented reason.

### 1.4 Data Integrity

The database must enforce appropriate integrity constraints.

Depending on the entity, these may include:

- Primary keys.
- Foreign keys.
- Unique constraints.
- NOT NULL constraints.
- CHECK constraints.
- Appropriate indexes.
- Transactional consistency.

Database constraints must support business integrity,
but must not replace backend business validation.

### 1.5 Historical Data

Business records that require historical traceability must preserve their relevant history.

Examples include:

- Order specification changes.
- Sampling revisions and decisions.
- LoA revisions and decisions.
- Payment corrections.
- Production progress updates.
- Delay Report history.
- Production Issue Report history.
- Workflow state changes.

Do not overwrite historical information when the business rules require the previous version or event to remain traceable.

Business history and audit history are separate concerns.

Business history preserves the lifecycle and previous versions of business records.

Audit history records relevant user actions and system events according to approved audit requirements.

A business operation may require both forms of historical records.

Do not assume that a general audit log replaces domain-specific business history or versioning.

Historical requirements do not automatically require a separate history table for every entity.

The implementation may use version records, event records, or another justified persistence strategy based on the entity's lifecycle and integrity requirements.

### 1.6 Database Ownership

The Go ERP Core backend is the only application component
responsible for database access.

The React + Vite frontend must not access PostgreSQL directly.

All database operations must be performed through backend
application logic.

---

## 2. Entity Design Requirements

Every proposed entity must be documented before implementation.

### 2.1 Entity Definition

For every entity, document:

**Purpose**

Why does this entity exist?

**Data Ownership**

What information does this entity own?

**Attributes**

What fields does it contain?

For each field, define:

- Name.
- Data type.
- Required or optional.
- Nullable or non-nullable.
- Default value, if applicable.
- Validation constraints.
- Description.
- Data origin (user-provided, system-generated, or derived), where applicable.

**Relationships**

Document relationships with other entities.

Specify:

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

Explain how records are created, updated, transitioned,
archived, or retained.

**Historical Requirements**

Identify whether the entity requires versioning,
event history, or audit records.

### 2.2 Entity vs Attribute vs Workflow

Do not automatically create a database table for every business term, API resource, workflow stage, or role.

Evaluate whether a concept should be represented as:

- A standalone entity.
- An attribute of an existing entity.
- An enum or constrained value.
- A relationship.
- A historical record.
- A derived or calculated value.
- A workflow state.

Examples:

- CMT and FOB are production types; a separate table is not
  automatically required.
- PPIC is an operational role, not automatically a database entity.
- Production stages are workflow concepts and require a justified
  data model.
- Timeline information does not automatically require a separate
  Timeline table.
- An API resource does not automatically require a matching table.

Document the reasoning before introducing a new entity.

An API resource does not require a dedicated database table.

For example, `/orders/:orderId/production` may represent a production capability associated with an Order without requiring a separate `production_orders` table.

The physical entity boundary must be determined during database design.

---

## 3. Entity Development Order

The following order defines the intended implementation progression.

It is a dependency-oriented development guide, not a requirement
to create every item as a separate database table.

### 3.1 Foundation

1. Customer
2. Order
3. Order Product Image / Reference
4. Order Size Quantity

### 3.2 Order Workflow

5. Sampling
6. Sampling Revision / History
7. Sample / Look Approval
8. LoA
9. LoA Revision / History
10. Payment
11. Payment Correction

### 3.3 Production Preparation

12. Production Readiness
13. Waiting / On Hold History
14. Functional Layout

### 3.4 Production Execution

15. Production Tracking
16. Production Stage Progress
17. QC / Rework Records
18. Timeline and Duration Records, if required
19. Packing and Fulfillment

### 3.5 Operational Monitoring

20. Delay Report
21. Delay Report History and Email Dispatch Records
22. Production Issue Report
23. Production Issue Report History
24. Order Change Request
25. Notification
26. Audit History

### 3.6 Reporting

27. Dashboard data aggregation, if required.
28. Supporting indexes and query optimizations.

The actual entity boundaries and relationships must be determined
during each capability's design.

Do not create a separate entity merely because it appears
as an item in this development order.

---

## 4. Data Modeling Rules

### 4.1 Customer and Order

- Customer information must have a clear ownership model.
- Order records must reference the appropriate customer information.
- Order specifications must remain traceable.
- Order records must support CMT and FOB production types.
- Order size quantities must support multiple sizes and quantities.
- Do not represent an order using only one size and one quantity.
- Do not assume that all orders share identical size options.

Order specification versioning must preserve the distinction between the currently approved specification and previous approved specifications.

Order Change Request records must remain traceable to their resulting approved specification changes.

Do not assume that audit logs alone satisfy order specification versioning requirements.

The physical versioning strategy remains UNDECIDED until the entity design is approved.

### 4.2 Product Images and References

- Image modeling must be explicitly designed.
- Do not assume an `image_url` column directly on Order.
- Do not assume a Product, SKU, or Design entity without
  domain justification.
- Do not store binary image data in PostgreSQL without
  an approved architectural decision.
- File metadata and physical file storage must have clearly
  defined responsibilities.

### 4.3 Production Types

Phase 1 supports:

- CMT
- FOB

Do not introduce Fashion Retail into the Phase 1 schema.

Determine whether production type should be represented by
an enum, constrained field, or reference entity during
the Order database design.

### 4.4 Workflow and Status

- Persisted statuses must follow approved business vocabulary.
- State transitions must be validated by backend business logic.
- Do not allow arbitrary status changes through generic updates.
- Preserve required workflow history.
- Do not invent additional statuses.

### 4.5 Production Progress

Confirmed percentage-monitored stages:

- CUTTING
- SEWING
- FINISHING

QC and REWORK must be operationally monitored.

Their percentage measurement method remains UNDECIDED.

PPIC is responsible for submitting permitted production progress.

Do not introduce an overall progress formula or weighting without an approved business definition.

Each percentage-based progress update must preserve its submission timestamp and submitting user.

Historical progress updates must not be overwritten when a new progress update is submitted.

### 4.6 Timeline

Timeline data must distinguish the following values where applicable to the approved timeline requirements:

- Planned Start Date.
- Planned Completion Date.
- Actual Start Date.
- Actual Completion Date.
- Estimated Completion Date.

Original planned dates must remain traceable when estimated completion dates change.

The system must not overwrite original planned dates when an estimated completion date is updated.

The detailed persistence and history strategy remains subject to the approved timeline entity design.

### 4.7 Payments

- Payment records must represent actual received payments.
- Payment corrections must preserve the original record.
- Duplicate transactions must not be counted more than once.
- Payment recording must remain separate from production readiness unless an approved business rule establishes a payment gate

The mechanism for identifying duplicate payment transactions remains UNDECIDED until the payment data requirements and payment recording process are defined.

### 4.8 Audit History

- Required business changes must be traceable.
- Audit records must not be editable through ordinary application
  operations.
- Historical records must follow approved retention rules.
- Audit visibility must follow authorization rules.

Business history and audit history must be treated as separate data responsibilities.

Audit records do not automatically replace domain-specific revision, status history, or version records.

Owner's confirmed read-only access applies to order status audit history.

Access to the complete application audit log must follow separately approved authorization requirements.

---

## 5. Database Migration Rules

### 5.1 Migration Management

All schema changes must be managed through version-controlled
database migrations.

Do not rely on manually modifying the production database schema.

Migration tool: UNDECIDED (Sprint 2.3).

### 5.2 Migration Requirements

Each migration must:

- Have a clear purpose.
- Be version-controlled.
- Be reproducible.
- Be reviewed before application.
- Preserve existing data where required.
- Include rollback considerations.
- Be tested against the expected database state.

### 5.3 Schema Changes

- Do not silently modify an existing schema.
- Update entity documentation when schema changes.
- Update the ERD when relationships or entities change.
- Review affected backend queries and API contracts.
- Review data migration requirements before removing or changing
  existing columns.

---

## 6. Database Access

Database access approach: UNDECIDED (Sprint 2).

The selected approach must:

- Work with Go and PostgreSQL.
- Support parameterized queries.
- Support database transactions.
- Make query behavior understandable and maintainable.
- Avoid unnecessary abstraction.
- Support migration management.
- Be compatible with the project's testing strategy.

The selected database access approach must be documented and approved before implementing database-dependent capabilities.

Do not introduce an ORM, query builder, or database abstraction without evaluating it against the project's Go and PostgreSQL requirements.

---

## 7. Indexing and Performance

Indexes must be introduced based on actual query requirements.

Consider:

- Foreign key lookup patterns.
- Frequently filtered fields.
- Sorting requirements.
- Dashboard query patterns.
- Historical record retrieval.
- Unique business identifiers.

Do not create indexes speculatively without a query or
integrity requirement.

Performance optimization must not compromise data integrity.

---

## 8. ERD and Database Documentation

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

The ERD and entity documentation must remain consistent
with the actual migrations.

---

## 9. Open Database Design Decisions

| Decision | Status |
|---|---|
| Per-size quantity representation (must not be a single size + quantity) | Sprint 4 |
| Total quantity: stored, calculated, or both | UNDECIDED (Sprint 4) |
| Image modeling and storage | UNDECIDED (Sprint 5) |
| Migration tool | UNDECIDED (Sprint 2.3) |
| Database access approach (plain SQL vs library) | UNDECIDED (Sprint 2) |
| Production Order as a separate entity or part of Order | UNDECIDED |
| Production type representation | UNDECIDED |
| Sampling and Sample / Look Approval entity relationships | UNDECIDED |
| LoA entity relationship with Order | UNDECIDED |
| Timeline persistence and history model | UNDECIDED |
| QC and REWORK data model | UNDECIDED |
| Overall production progress calculation | UNDECIDED |
| Waiting / On Hold history model | UNDECIDED |
| Fulfillment data and evidence requirements | UNDECIDED |
| Audit history structure and retention | UNDECIDED |
| Notification persistence and read-state model | UNDECIDED |
| Delay Report history and email dispatch records | UNDECIDED |
| Production Issue Report history model | UNDECIDED |
| Order Change Request versioning strategy | UNDECIDED |
| Payment correction and duplicate transaction handling | UNDECIDED |
| Customer information snapshot at order creation | UNDECIDED |

Do not resolve these decisions by assumption.

Each decision must be reviewed against the approved business
requirements before the affected entity is implemented.

---

## 10. Related Documentation

Business:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`

API:

- `docs/api/API_CONTRACTS.md`

Architecture:

- `docs/architecture/`

Engineering:

- `docs/engineering/`

Diagrams:

- `docs/diagrams/database/`

---

## 11. Database Design Governance

This document defines the database design baseline for Phase 1.

The actual database schema must grow incrementally through
approved entity designs and version-controlled migrations.

No entity, relationship, attribute, constraint, or calculation
may be introduced solely because it appears convenient
during implementation.

All database changes must remain consistent with approved
business requirements, API contracts, and architecture decisions.