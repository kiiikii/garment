# Database Dictionary

Status: BASELINE APPROVED — PHASE 1

This document defines the meaning, ownership, relationships, and source-of-truth responsibilities of data stored in the Garment ERP database.

It serves as the business-level reference for database design.

The actual database structure, including tables, columns, data types, constraints, indexes, and relationships, must remain consistent with this document and the approved business requirements.

Database definitions must be documented incrementally as entities and tables are designed.

Do not create database structures based on assumptions or undocumented business requirements.

This baseline approves the data documentation standards and source-of-truth responsibilities for Phase 1.

It does not mean that the physical database schema, entity boundaries, or all table definitions have been finalized.

Physical schema decisions must be approved incrementally before implementation.

---

## 1. Database Conventions

### 1.1 Database Engine

PostgreSQL.

Database name:

`garment_erp`

### 1.2 Naming Conventions

Database objects must follow consistent naming conventions.

- Table names use `snake_case`.
- Column names use `snake_case`.
- Primary keys must be explicitly identified.
- Foreign keys must follow consistent naming conventions.
- Boolean fields must have clear and unambiguous names.
- Timestamp fields must use consistent naming.
- Enum and status values must follow the approved business vocabulary.

### 1.3 Data Type Definitions

Every column must document its PostgreSQL data type.

Common data types may include:

- `UUID`
- `BIGINT`
- `VARCHAR`
- `TEXT`
- `INTEGER`
- `NUMERIC`
- `BOOLEAN`
- `DATE`
- `TIMESTAMPTZ`
- `JSONB`

Use appropriate precision for financial and percentage values.

Do not use floating-point types for financial amounts.

### 1.4 Constraints

Every table definition must document applicable constraints:

- PRIMARY KEY
- FOREIGN KEY
- UNIQUE
- NOT NULL
- CHECK
- DEFAULT

Constraints must enforce data integrity without replacing business-level validation in the backend.

### 1.5 Timestamps

Timestamp fields must consistently represent the intended business event or database operation.

Where timestamps represent actual moments in time, use timezone-aware storage.

Do not confuse:

- Planned dates.
- Actual dates.
- Estimated dates.
- Record creation timestamps.
- Record update timestamps.
- Business event timestamps.

These values represent different responsibilities and must not be treated as interchangeable.

---

## 2. Table Documentation Template

Every database table must use the following documentation structure.

### 2.1 Table Definition

#### `table_name`

**Purpose:**

Describe the business responsibility of this table.

| Column | Type | Null | Default | Constraints | Description              |
| ------ | ---- | ---- | ------- | ----------- | ------------------------ |
| `id`   | UUID | NO   | —       | PRIMARY KEY | Unique record identifier |

The example above is illustrative only.

Actual columns must be defined according to the approved domain model.

**Owns:**

Describe the information for which this table is authoritative.

**Relationships:**

Document relationships with other entities.

Include:

- Related table.
- Relationship type (one-to-one, one-to-many, many-to-many).
- Foreign key ownership.
- Required or optional relationship.

**Source of Truth For:**

Identify which business information is authoritative in this table.

**Lifecycle:**

Describe:

- How the record is created.
- How the record can be updated.
- Whether it can change status.
- Whether historical versions must be preserved.
- Whether deletion is permitted.
- When the record is considered completed or inactive.

**Business Rules:**

List the relevant business rules from:

- `BUSINESS_RULES.md`
- `BUSINESS_WORKFLOW.md`
- `USER_ROLES.md`

**Data Integrity Rules:**

Document important database-level integrity requirements.

**Indexes:**

Document indexes required for relevant queries and relationships.

**Implementation Status:**

Use one of:

- PROPOSED
- DESIGNED
- IMPLEMENTED
- VERIFIED
- DEPRECATED

---

## 3. Entity Ownership and Source of Truth

Each business entity must have a clearly defined source of truth.

A value must not have multiple competing authoritative sources.

Examples:

| Business Information                   | Authoritative Source                            |
| -------------------------------------- | ----------------------------------------------- |
| Customer information                   | Customer record                                 |
| Order information                      | Order record                                    |
| Production type                        | Order record                                    |
| Ordered size quantities                | Order size details                              |
| Sampling activities and progress       | Sampling activity records                       |
| Pattern Preparation information        | Sampling activity records                       |
| Sample Production information          | Sampling activity records                       |
| Sampling planned and actual dates      | Sampling activity records                       |
| Sample / Look Approval decisions       | Sample / Look Approval records                  |
| LoA decisions                          | LoA records                                     |
| Received payments                      | Payment records                                 |
| Payment corrections                    | Payment correction records                      |
| Production readiness                   | Readiness evaluation records                    |
| Functional production layout           | Layout records                                  |
| Stage progress submissions             | Production progress records                     |
| Delay information                      | Delay Report records                            |
| Production issues                      | Production Issue Report records                 |
| Current approved order specifications  | Current approved Order specification            |
| Previous approved order specifications | Preserved approved Order specification versions |
| Order specification change decisions   | Order Change Request records                    |
| Fulfillment information                | Fulfillment records                             |

This table defines conceptual ownership.

The final table structure and normalization must be determined during database design.

Sampling activities and Sample / Look Approval decisions represent separate business responsibilities.

Sampling activity records describe the execution of Pattern Preparation and Sample Production.

Sample / Look Approval records describe the approval decision and its supported states.

Do not combine these responsibilities into a single conceptual source of truth merely because they belong to the same overall workflow.

---

## 4. Source-of-Truth Data

Source-of-truth data represents authoritative business information recorded by an authorized operation.

Examples include:

- Customer information.
- Order specifications.
- Production type.
- Ordered size quantities.
- Sampling activities.
- Pattern Preparation records.
- Sample Production records.
- Sampling planned and actual dates.
- Sample / Look Approval decisions.
- LoA decisions.
- Received payment transactions.
- Production readiness evaluations.
- Functional layout information.
- PPIC-submitted stage progress.
- Delay Report records.
- Production Issue Report records.
- Order Change Request records.
- Fulfillment records.

Source-of-truth records must not be silently overwritten when business rules require historical traceability.

### 4.1 Production Quantity

Ordered quantity is defined by the size quantities recorded in the Order.

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

Do not introduce quantity-based production tracking without an approved business requirement.

---

## 5. Derived Data

Derived data represents information calculated from authoritative source-of-truth records.

Derived values must not replace or silently modify the records used to calculate them.

### 5.1 Recorded Operational Data

The following values are recorded through authorized business operations and must not be treated as automatically calculated derived values.

| Information                     | Status                     | Rule                             |
| ------------------------------- | -------------------------- | -------------------------------- |
| Cutting completion percentage   | CONFIRMED — PPIC SUBMITTED | PPIC submits percentage progress |
| Sewing completion percentage    | CONFIRMED — PPIC SUBMITTED | PPIC submits percentage progress |
| Finishing completion percentage | CONFIRMED — PPIC SUBMITTED | PPIC submits percentage progress |

Rules:

1. PPIC is responsible for stage progress submissions.
2. Each submission must preserve its timestamp and submitting user.
3. Historical progress submissions must not be overwritten.
4. An unchanged percentage submitted again must still be recorded as a new update.
5. QC and Rework percentage measurement remain `UNDECIDED`.

PPIC progress records are the authoritative source for percentage-based stage progress.

### 5.2 Derived Data

Examples of potentially derived information:

- Production duration.
- Sampling duration.
- Timeline variance.
- Delay duration.
- Aggregated dashboard metrics.
- Financial dashboard summaries.
- Production completion summaries.

| Derived Information                 | Status    | Rule                                                                      |
| ----------------------------------- | --------- | ------------------------------------------------------------------------- |
| Overall production progress         | UNDECIDED | Formula and weighting have not been approved                              |
| QC percentage                       | UNDECIDED | Operational monitoring is required; percentage measurement is not defined |
| Rework percentage                   | UNDECIDED | Percentage measurement is not defined                                     |
| Reject percentage                   | UNDECIDED | Quantity-based reject tracking is not confirmed                           |
| Target vs. actual quantity variance | UNDECIDED | Actual quantity tracking is not confirmed                                 |

### 5.3 Timeline and Duration Data

Timeline-related information must distinguish between authoritative recorded dates and calculated duration values.

The system must preserve the distinction between:

- Planned Start Date.
- Planned Completion Date.
- Actual Start Date.
- Actual Completion Date.
- Estimated Completion Date.
- Planned Duration.
- Actual Duration.
- Internal Duration.
- Customer-facing Target.
- Customer-facing Deadline.

Rules:

1. Planned and actual dates must remain separate.
2. Planned and actual durations must remain separate.
3. Internal duration and customer-facing target must remain separate.
4. Actual dates must not overwrite planned dates.
5. Original planned completion dates must be preserved.
6. A Delay Report must not silently overwrite the original planned completion date.
7. Estimated completion may change only through an authorized business process.
8. Calculated duration values must not replace their underlying date records.
9. Calendar days and working days must not be treated as interchangeable.
10. Timeline calculations must follow approved business rules.

Sampling duration, internal production duration, and customer-facing targets represent different measurements.

Do not combine or substitute these values.

### 5.4 Derived Data Persistence

Do not automatically create database columns for derived calculations.

If a derived value must be persisted for performance or historical snapshot purposes, its following requirements must be documented first:

- Calculation method.
- Authoritative source records.
- Refresh behavior.
- Historical snapshot requirements.
- Data consistency requirements.
- Recalculation behavior.
- Applicable business rules.

The persistence strategy for derived values remains subject to database design approval.

---

## 6. Historical Data and Auditability

The database must support the historical traceability required by approved business rules.

Historical business data may include:

- Previous order specifications.
- Sampling revisions and activities.
- Sample / Look Approval history.
- LoA revisions and decisions.
- Payment transactions and corrections.
- Production progress submissions.
- Timeline changes.
- Delay Report history.
- Production Issue Report status history.
- Order Change Request decisions.
- Production readiness evaluations.
- Waiting / On Hold history.
- Functional layout changes, where historical traceability is required.
- Fulfillment records and relevant changes.
- Workflow state changes.
- Audit records.

### 6.1 Historical Preservation

Where history preservation is required:

- Previous values must remain traceable.
- The system must identify the relevant business record.
- The system must record the responsible user where required.
- The system must preserve relevant timestamps.
- Historical records must not be silently replaced by current values.

Order specification versioning must distinguish between:

- The currently approved order specification.
- Previous approved order specification versions.
- Order Change Request records and their approval decisions.

An Order Change Request record alone does not replace the requirement to preserve approved order specification history.

The physical versioning strategy remains `UNDECIDED` until database design is approved.

### 6.2 Audit Records

Audit records must support the approved audit requirements.

Audit records should identify relevant information such as:

- Actor.
- Action.
- Affected resource.
- Relevant timestamp.

The exact audit event coverage, retention policy, and visibility must follow the relevant business and security decisions.

Ordinary resource update operations must not allow users to modify audit history.

### 6.3 Business History vs. Audit Records

Business history and audit records are related but represent different responsibilities.

**Business History**

Business history preserves domain-specific information required to understand the lifecycle and decisions of business operations.

Examples:

- Sampling revisions and approval decisions.
- LoA decisions.
- Payment corrections.
- Production progress submissions.
- Order specification versions.
- Order Change Request decisions.
- Delay Report history.
- Production Issue Report status history.
- Waiting / On Hold history.
- Fulfillment changes.

**Audit Records**

Audit records preserve traceability of relevant system and business operations.

They identify relevant actors, actions, affected resources, and timestamps.

Rules:

1. Business history must preserve the domain-specific information required by the approved business workflow.
2. Audit records must preserve the relevant operational traceability required by security and audit requirements.
3. Business history does not automatically replace audit records.
4. Audit records do not automatically replace business history.
5. The same business operation may require both a domain-specific historical record and an audit record.
6. The exact overlap and coverage must follow approved business and security requirements.
7. Do not assume that every database read, update, or system event must create a permanent audit record without an approved requirement.
8. Audit records must not be silently modified or deleted through ordinary resource operations.

The physical implementation of business history and audit records remains subject to database design.

---

## 7. Data Integrity and Relationships

Database relationships must reflect the approved domain model.

The database must enforce applicable:

- Referential integrity.
- Required relationships.
- Uniqueness.
- Valid data ranges.
- Valid enumerated values.
- Transaction consistency.

Business workflow transitions must be validated by the backend.

Database constraints may provide additional protection, but must not be treated as a replacement for backend workflow logic.

Do not create relationships solely because two tables share similar field names.

The database must preserve the distinction between:

- Business records.
- Historical records.
- Approval decisions.
- Operational submissions.
- Derived information.
- Audit records.

These responsibilities must not be merged without an approved domain design.

---

## 8. Database Design Rules

1. Every table must have a documented business purpose.
2. Every table must have a clearly defined ownership responsibility.
3. Every authoritative business value must have a source of truth.
4. Derived values must not compete with their source records.
5. Relationships must follow the approved domain model.
6. Historical preservation must follow the approved business rules.
7. Financial values must use appropriate numeric precision.
8. Database constraints must protect data integrity.
9. Business validation and workflow enforcement belong to the backend.
10. Do not introduce unapproved production tracking fields.
11. Do not introduce speculative tables or columns.
12. Do not duplicate authoritative data without a documented reason.
13. Database changes must be reflected in the schema documentation.
14. Database migrations must preserve existing business data.
15. Any change affecting business data ownership must be reviewed against the relevant business documentation.
16. Sampling activities and Sample / Look Approval decisions must remain conceptually distinct.
17. Business history and audit records must not be treated as interchangeable.
18. Planned, actual, and estimated timeline information must remain distinguishable.
19. Derived calculations must not overwrite authoritative source records.
20. No database structure may introduce an unapproved business rule or operational tracking requirement.

---

## 9. Open Database Design Decisions

The following decisions must be resolved when designing the affected entities.

| Decision                                                               | Status    |
| ---------------------------------------------------------------------- | --------- |
| Primary key strategy                                                   | UNDECIDED |
| Customer archival and deletion behavior                                | UNDECIDED |
| Customer information snapshot at order creation                        | UNDECIDED |
| Order specification versioning structure                               | UNDECIDED |
| Product image and attachment storage                                   | UNDECIDED |
| Sampling revision storage strategy                                     | UNDECIDED |
| Sample / Look Approval data structure                                  | UNDECIDED |
| LoA document storage and versioning                                    | UNDECIDED |
| Payment correction data structure                                      | UNDECIDED |
| Production readiness evaluation structure                              | UNDECIDED |
| Functional layout data structure                                       | UNDECIDED |
| Production progress history structure                                  | UNDECIDED |
| QC and Rework data measurement                                         | UNDECIDED |
| Actual, reject, and rework quantity tracking                           | UNDECIDED |
| Overall production progress calculation                                | UNDECIDED |
| Timeline snapshot and recalculation strategy                           | UNDECIDED |
| Audit event coverage and retention                                     | UNDECIDED |
| Business history and audit record implementation strategy              | UNDECIDED |
| Fulfillment evidence and record structure                              | UNDECIDED |
| Production type representation (enum, constrained field, or reference) | UNDECIDED |
| Waiting / On Hold history structure                                    | UNDECIDED |
| Notification persistence and read-state model                          | UNDECIDED |
| Delay Report history and email dispatch records                        | UNDECIDED |
| Production Issue Report history model                                  | UNDECIDED |
| Order Change Request versioning strategy                               | UNDECIDED |
| Payment duplicate transaction handling                                 | UNDECIDED |

Do not resolve these decisions through undocumented implementation assumptions.

Each decision must be reviewed against the approved business requirements before the affected entity is implemented.

---

## 10. Related Documentation

Business documentation:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/USER_ROLES.md`
- `docs/business/GLOSSARY.md`

API documentation:

- `docs/api/API_CONTRACTS.md`

Architecture documentation:

- `docs/architecture/`

Engineering documentation:

- `docs/engineering/`

---

## 11. Document Governance

This document defines the approved baseline standards for database documentation in Phase 1.

Table definitions must be added and maintained as the database schema is designed and implemented.

Any change affecting data ownership, relationships, lifecycle, or source-of-truth responsibilities must be reviewed against the approved business documentation.

The database schema, backend implementation, API contracts, and database dictionary must remain consistent.

No implementation may silently introduce new business data, tracking requirements, or calculations.

Changes to unresolved database design decisions must be documented and approved before implementation.
