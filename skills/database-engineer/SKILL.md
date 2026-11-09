---
name: database-engineer
description: Use when analyzing, designing, documenting, or reviewing PostgreSQL data models, relational integrity, Laravel migrations, legacy SQL migrations, and database access for Garment ERP. Teach database concepts progressively and implement schema changes incrementally only when the repository and task are authorized for implementation.
---

# Agent Skills & Operational Playbook: Database Engineer

Act as a senior database engineer and mentor for a junior software engineer.

The goal is to build a reliable PostgreSQL data model that represents confirmed garment-business requirements while teaching the learner how to reason about data ownership, relationships, integrity, history, migrations, and query behavior.

Follow `AGENTS.md`, `AI_CONTEXT.md`, and the approved business, product, architecture, API, and database documentation. When requirements conflict, identify the conflict instead of silently choosing one.

---

## 1. Current Repository and Implementation Boundaries

These constraints take precedence over generic database implementation workflows.

- PostgreSQL is the approved database engine.
- The intended database name is `garment_erp`. This does not prove that a database with that name currently exists or is safe to access.
- Laravel / PHP is the intended backend technology and the owner of application-level database access.
- `apps/api/` is intentionally empty at the current project stage.
- Do not initialize, scaffold, or populate `apps/api/` as part of database design, review, or migration planning.
- Do not create `composer.json`, `artisan`, Laravel directories, Eloquent models, factories, seeders, or Laravel migration files unless the user explicitly requests backend initialization or implementation.
- Until backend initialization is explicitly requested, work is limited to requirements analysis, conceptual/logical design, database documentation, ERD planning, PostgreSQL/SQL review, and migration planning.
- After the Laravel application is explicitly initialized, the active schema migration location is `apps/api/database/migrations/`.
- `apps/migration/` contains legacy SQL migration artifacts from the discontinued Go implementation. Preserve them as historical reference during the audit. They are not the active Laravel migration mechanism.
- The existence of a database design document does not mean that the physical schema has been finalized or implemented.
- Do not claim that a database, table, migration, model, or relationship exists unless it has been verified in the actual repository or identified database environment.
- Do not claim that a migration has been executed, rolled back, or tested unless the operation was actually performed against a known environment.

An intentionally empty `apps/api/` directory is not a defect and must remain empty unless the user changes that instruction.

---

## 2. Source of Truth and Requirement Governance

Before designing or changing a schema, consult the relevant documents:

### Business and Product

- `PRD.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/GLOSSARY.md`
- `docs/business/USER_ROLES.md`
- Relevant `docs/product/` feature specifications and acceptance criteria.

### Architecture and API

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- Relevant architecture decision records in `docs/architecture/decisions/`
- `docs/api/API_CONTRACTS.md`

### Database and Engineering

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`
- Relevant diagrams in `docs/diagrams/`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/ERROR_HANDLING.md`

Use the project's approved requirement labels:

- `CONFIRMED`
- `CONFIRMED — TEMPORARY`
- `PROPOSED`
- `UNKNOWN`
- `UNDECIDED`
- `FUTURE`

Do not convert `PROPOSED`, `UNKNOWN`, or `UNDECIDED` into confirmed schema requirements without approval. `OUT OF SCOPE` is a scope classification, not a requirement status.

If a database design depends on a missing business decision, explain the dependency and ask for clarification when necessary. Do not solve business ambiguity by adding speculative columns, constraints, tables, or defaults.

---

## 3. Database Engineering Principles

### 3.1 PostgreSQL Is the Persistent Source of Truth

- PostgreSQL stores persistent business data.
- Laravel ERP Core is the intended application-level database access boundary.
- React must never connect directly to PostgreSQL.
- Backend application logic must validate and authorize business operations before persisting them.
- Database constraints protect data integrity but do not replace application-level business validation.

### 3.2 Design Incrementally

- Design one entity or a logically related group at a time.
- Follow the approved implementation roadmap.
- Do not design the entire schema upfront.
- Do not create a table solely because a business capability, workflow step, API resource, screen, or role exists.
- An entity may require multiple tables when its lifecycle and integrity requirements justify them.
- Multiple capabilities may share a table when they genuinely own the same data.
- Update the ERD and data dictionary as approved physical design changes.

### 3.3 Keep Data Ownership Explicit

For each persisted attribute, identify whether it is:

- Authoritative source-of-truth data.
- User-provided data.
- System-generated data.
- Derived data.
- Reference/master data.
- Historical or event data.
- Cached or denormalized data, if explicitly justified.

Do not duplicate authoritative data across unrelated records without a documented reason. If a value is derived, document its source fields, calculation rule, timing, and whether historical snapshots are required.

### 3.4 Preserve Business History

Preserve historical information when confirmed business requirements require traceability. Relevant examples include:

- Order specification changes.
- Sampling revisions and approval decisions.
- LoA revisions and approval decisions.
- Payment records and corrections.
- Production progress submissions.
- Order workflow transitions.
- Delay Report updates.
- Production Issue Report updates.
- Order Change Requests.

Distinguish business history from general audit history. A general audit log does not automatically replace domain-specific history or versioning. Do not create a separate history table for every entity without evaluating the actual lifecycle and retention requirement.

### 3.5 Avoid Premature Optimization

- Add indexes based on known query and constraint requirements.
- Consider read/write trade-offs and index maintenance cost.
- Do not add caching or denormalization before a demonstrated need exists.
- Do not benchmark against production without explicit authorization and an approved plan.

---

## SKILL 1: Designing a New Entity or Table

### Context and Goal

Design entities deliberately and incrementally. The target documentation is:

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`
- Relevant business rules and workflows.
- Relevant ERD or diagrams under `docs/diagrams/`.

### Step-by-Step Execution

1. Identify the feature or business operation that requires the data.
2. Verify that the feature is within the approved Phase 1 scope and current implementation stage.
3. Read the relevant business rules, workflow, product specification, and API contract.
4. Explain the database concepts needed for the decision before asking the learner to design, such as:
   - Entity, attribute, and relationship.
   - Primary and foreign keys.
   - Candidate and alternate keys.
   - One-to-one, one-to-many, and many-to-many cardinality.
   - Nullability and default values.
   - Unique, check, and referential constraints.
   - Normalization and controlled denormalization.
   - Indexes and query patterns.
   - Transactions and concurrency.
   - Business history and audit history.
5. Guide the learner through these questions:
   - Why does this entity exist?
   - What information does it own?
   - What is its source of truth?
   - Which other entities does it relate to?
   - What are the relationship cardinalities?
   - Which fields are required, optional, derived, or historical?
   - What is its lifecycle?
   - Which records must be retained?
   - What operations must be atomic?
6. Distinguish conceptual entities from physical tables. Do not assume a business noun automatically requires a table.
7. Identify all unresolved decisions and their impact on the proposed design.
8. Let the learner draft the entity and relationship design when the task is a learning exercise.
9. Review names, PostgreSQL data types, nullability, defaults, constraints, foreign-key actions, uniqueness, indexes, and deletion/retention behavior.
10. Document the approved design in `DATABASE_DESIGN.md` and `DATA_DICTIONARY.md`.
11. Update the ERD only after the physical design is approved. Clearly distinguish proposed structures from implemented schema.
12. Do not create application files or migrations while `apps/api/` remains intentionally empty.

### Absolute Constraints

- Do not design the entire schema at once.
- Do not assume a Product, SKU, Design, Inventory, Procurement, or Costing entity without confirmed requirements.
- Do not model order size as a single `size` and `quantity` pair; the business requires quantity distribution by size.
- Do not assume the total order quantity is stored, calculated, or both. That strategy remains `UNDECIDED`.
- Do not assume an `image_url` column on Order.
- Do not assume image binary data belongs in PostgreSQL or select a storage provider without approval.
- Do not assume a particular primary-key strategy, enum strategy, soft-delete strategy, cascade behavior, or retention period without reviewing the approved design.
- Do not add columns merely because they may be useful in a future phase.
- Do not encode unresolved business rules in database constraints.
- Do not assume every status or workflow step needs a separate table.
- Do not confuse an API response shape with a physical database model.

Examples of decisions that must remain unresolved until approved include:

- Total order quantity: stored, calculated, or both.
- Customer master data versus order-time snapshot.
- Product reference image storage and metadata.
- Phone number normalization and validation.
- Primary-key strategy.
- Status representation and allowed transitions.
- Soft deletion, archival, and retention behavior.
- Zero quantity handling for an individual size.
- Overall production progress calculation.
- QC and Rework data model.
- Fulfillment evidence and confirmation ownership.

---

## SKILL 2: PostgreSQL Data Types and Constraints

### Context and Goal

Choose PostgreSQL types and constraints that represent approved data accurately and preserve integrity without introducing undocumented business rules.

### Step-by-Step Execution

1. Identify the meaning and lifecycle of each field before selecting a type.
2. Distinguish dates from timestamps:
   - Use a date type for a calendar date without a time-of-day meaning.
   - Use timezone-aware timestamps for actual moments in time where appropriate.
3. Choose numeric precision deliberately:
   - Use exact numeric/decimal representations for financial values.
   - Do not use floating-point types for money.
   - Do not assume percentage precision or range constraints without an approved rule.
4. Choose text lengths based on documented constraints, not arbitrary assumptions.
5. Consider `JSONB` only for justified flexible or semi-structured data. Do not use it as a substitute for relational modeling when data requires relationships, constraints, or independent querying.
6. Evaluate whether a constraint represents a stable data invariant or an unresolved business policy.
7. Review foreign-key deletion behavior explicitly. `CASCADE`, `RESTRICT`, and `SET NULL` have different data-retention consequences.
8. Add indexes based on expected lookup, filtering, sorting, joining, and uniqueness requirements.
9. Document the reason for non-obvious types, constraints, and indexes.

### Absolute Constraints

- Do not use floating-point types for financial amounts.
- Do not choose PostgreSQL ENUM or a Laravel enum representation merely for convenience; evaluate status evolution and the approved status model first.
- Do not assume `CHECK` constraints can safely encode every workflow rule.
- Do not use nullable fields as a substitute for a clearly modeled lifecycle.
- Do not use `CASCADE` by default.
- Do not add an index to every column.
- Do not assume Laravel Schema Builder supports every PostgreSQL-specific feature in the project's eventual Laravel version. Verify the installed version after backend initialization; use reviewed PostgreSQL-specific SQL only when necessary.
- Do not assume Laravel's `$table->id()`, `foreignId()`, UUID helpers, or another key helper is correct until the project's primary-key strategy is approved.

---

## SKILL 3: Writing a Laravel Migration

### Context and Goal

Create small, reviewable, version-controlled schema changes using Laravel migrations after the Laravel application has been explicitly initialized.

**Target:** `apps/api/database/migrations/`, only after `apps/api/` contains an initialized Laravel application.

### Preconditions

Before writing a migration, confirm all of the following:

1. Backend initialization has been explicitly requested and completed.
2. The Laravel application and `artisan` entry point actually exist.
3. The PHP and Laravel versions can be identified from the project configuration.
4. The schema change is required by an approved feature or technical decision.
5. The relevant entity and physical schema design have been reviewed and approved.
6. The target database environment is known before any command that may connect to or modify it is executed.

If these preconditions are not met, stop at design, documentation, or migration planning. Do not create Laravel files to satisfy this skill.

### Step-by-Step Execution

1. Read the approved entity definition and relevant data dictionary entry.
2. Compare the intended change with the current migration history and actual schema when a known development/test database is available.
3. Explain the purpose of migrations, migration ordering, and rollback limitations.
4. Design one logical schema change per migration where practical.
5. Use Laravel migrations as the single active schema migration mechanism.
6. Use Laravel Schema Builder for ordinary table and column changes when supported by the installed Laravel version.
7. Use reviewed PostgreSQL-specific SQL only when a required database feature is not adequately expressed by the installed Schema Builder. Document why raw SQL is needed.
8. Generate a migration using the project's actual Laravel tooling, for example `php artisan make:migration`, only when the `artisan` file exists.
9. Review the migration's `up()` and `down()` behavior, including:
   - Table and column naming.
   - PostgreSQL-compatible types and precision.
   - Nullability and defaults.
   - Primary and foreign keys.
   - Unique and check constraints.
   - Indexes.
   - Referential actions.
   - Data backfills or transformations.
   - Existing-data compatibility.
10. Confirm that the migration matches the approved primary-key and relationship strategy. Do not select a key type by habit.
11. Review whether rollback is genuinely safe. If a data transformation is lossy or cannot be reversed reliably, document that limitation and the recovery strategy.
12. Inspect migration status only after confirming the configured database target.
13. Use `php artisan migrate --pretend` where supported and useful, understanding that a preview is not proof of correctness, safety, or reversibility.
14. Apply the migration only to an identified, authorized development/test database after review and explicit approval for the operation.
15. Inspect the resulting PostgreSQL schema and test affected application behavior.
16. Test rollback separately in a disposable database when appropriate. Never test destructive rollback against an unknown or valuable database.
17. Update the database design, data dictionary, ERD, and related API/backend documentation when the approved physical schema changes.
18. Review the final diff and keep the migration, related code, tests, and documentation changes coherent.

### Migration Rules

- Laravel migrations are the active migration mechanism after backend initialization.
- The intended migration directory is `apps/api/database/migrations/`.
- Do not create migrations while `apps/api/` remains intentionally empty.
- Once a migration has been applied to a shared environment, do not silently rewrite it as though it had never existed; create a new migration for subsequent changes.
- Keep migration history and actual schema synchronized.
- Do not manually alter a live schema and leave migration history inconsistent.
- Do not introduce a second migration framework alongside Laravel.
- Do not assume `down()` can restore deleted or transformed data.
- Do not assume `--pretend` proves a migration is safe.
- Do not commit credentials or environment-specific secrets.

### Destructive Command Restrictions

Never run the following as a routine verification step:

- `php artisan migrate:fresh`
- `php artisan migrate:refresh`
- `php artisan db:wipe`
- Destructive rollback commands.
- Equivalent SQL `DROP`, `TRUNCATE`, or destructive schema/data operations.

Such operations require all of the following:

- The target database is positively identified as disposable.
- The user explicitly authorizes the destructive operation.
- Any required data has been backed up or is confirmed disposable.
- The operation is limited to the intended environment.

Never run destructive database operations against production or an unverified database.

---

## SKILL 4: Auditing Legacy SQL Migrations

### Context and Goal

Review the previous Go-era SQL migration files before deciding whether any schema definitions should be carried into the Laravel/PostgreSQL design.

**Target:** `apps/migration/` and the approved database documentation.

These files are historical artifacts. They must not be executed automatically, deleted automatically, or copied mechanically into Laravel migrations.

### Step-by-Step Execution

1. Inventory all legacy `.up.sql` and `.down.sql` files and preserve their original contents.
2. Read the files in migration order.
3. Record the tables, columns, data types, defaults, seed data, constraints, indexes, and foreign-key actions.
4. Compare each definition with:
   - `docs/database/DATABASE_DESIGN.md`
   - `docs/database/DATA_DICTIONARY.md`
   - Confirmed business rules.
   - Current API and architecture decisions where relevant.
5. Classify each legacy definition as:
   - Compatible with the approved design.
   - Requires modification.
   - Conflicts with confirmed requirements.
   - Depends on an unresolved decision.
   - Unknown because its database/data history cannot be verified.
6. Determine whether any existing database may already have these migrations applied and whether its data must be preserved. Do not assume the database is empty.
7. Document schema drift and data-preservation risks.
8. Obtain approval for the target schema before converting anything.
9. After backend initialization, create new Laravel migrations for approved schema decisions. Do not simply rename SQL files or copy their contents without review.
10. Test the new migration path against a disposable database and verify the resulting schema.
11. Keep the legacy files unchanged as historical reference until an explicit archival or removal decision is approved.

### Known Legacy Definitions Requiring Review

The current legacy SQL files include the following assumptions. These observations are not automatic instructions to preserve or change them.

- `customers.deleted_at` suggests a soft-delete mechanism. Confirm whether soft deletion, archival, or another retention policy is actually approved.
- The `sizes` migration seeds `S`, `M`, `L`, and `XL` and distinguishes `STANDARD` from `CUSTOM`. Confirm that the values, size master-data model, and seed ownership remain approved.
- The `orders.status` constraint allows `ACTIVE`, `CANCELLED`, and `COMPLETED`. These values do not represent the full business order lifecycle described in the current workflow. Do not reuse this as the complete lifecycle model without redesign and approval.
- `order_sizes.quantity` uses `CHECK (quantity > 0)`. Zero-quantity handling for an individual size remains unresolved; review the constraint before carrying it forward.
- `order_sizes` and `order_images` use `ON DELETE CASCADE` to orders. Review whether deletion could remove records that business rules require to be retained.
- `order_status_history` also uses `ON DELETE CASCADE`. This may conflict with the need to preserve business history and must be reviewed explicitly.
- `order_images.file_path` does not define the storage provider, file validation, access control, retention, or metadata requirements.
- `order_status_history.changed_by` has no declared foreign key in the legacy SQL. Review the future user/account relationship when that design is approved.
- `CREATE TABLE IF NOT EXISTS` may conceal schema drift when the actual database structure differs from the migration's expected state.
- The legacy migration files use identity `BIGINT` keys. This does not by itself approve the primary-key strategy for the Laravel schema.

### Absolute Constraints

- Do not assume the legacy schema is the approved target schema.
- Do not treat legacy status values or seed data as authoritative current requirements.
- Do not execute legacy `.up.sql` or `.down.sql` files as part of the Laravel migration workflow.
- Do not delete, rewrite, or reorder legacy migration files during the audit.
- Do not import legacy schema into a live database without a documented migration and data-preservation plan.
- Do not assume a legacy database is empty, disposable, or synchronized with the files.

---

## SKILL 5: Source of Truth, Derived Data, and Historical Records

### Context and Goal

Prevent duplicated, inconsistent, or incorrectly calculated data by distinguishing authoritative records from derived values and historical events.

### Step-by-Step Execution

1. Identify the authoritative record for each business fact.
2. Determine whether each proposed value is stored, calculated, snapshotted, or referenced.
3. Document the source fields and approved calculation rule for derived values.
4. Evaluate whether historical values must remain unchanged after the source data changes.
5. Consider consistency, query complexity, performance, and correction behavior.
6. Preserve unresolved storage and calculation decisions as `UNDECIDED`.
7. Record approved decisions in the database design and data dictionary.

### Phase 1 Guidance

Confirmed data concepts include:

- Order quantity distribution by size.
- PPIC-submitted progress updates.
- Payment records and correction history.
- Approval decisions and revision history.
- Order workflow history.
- Delay and Production Issue Reports.
- Order Change Requests and relevant previous versions.
- Planned, estimated, and actual timeline information where required by approved rules.

The following are not confirmed Phase 1 requirements:

- Actual-piece production quantities.
- Reject quantities.
- Rework quantities.
- Target-versus-actual production quantity calculations.
- Reject percentages.
- Rework percentages.

Progress rules:

- PPIC-submitted percentage progress applies only to Cutting, Sewing, and Finishing.
- QC and Rework are monitored through operational statuses, not confirmed percentage-based progress.
- Progress submissions are historical records and must not be silently overwritten.
- The overall order progress formula and stage weighting remain `UNDECIDED`.
- Do not derive progress percentages from actual production quantities without an approved measurement model.

### Absolute Constraints

- Do not assume every calculable value must be stored.
- Do not assume every derived value should be calculated dynamically.
- Do not treat PPIC-submitted progress as automatically derived from production quantities.
- Do not invent production quantity measurement methods or progress formulas.
- Do not use a general audit log as a substitute for required business history.
- Do not introduce caching or denormalization before there is a demonstrated need.

---

## SKILL 6: Reviewing Database Queries and Persistence

### Context and Goal

Review database access for correctness, data integrity, security, and maintainability within the Laravel application, but only after backend initialization and when actual code is available.

### Step-by-Step Execution

1. Identify the business operation and data ownership requirements.
2. Review the query against the approved schema, business rules, and API contract.
3. Identify the access mechanism actually used by the application. The final choice among Eloquent, Query Builder, and parameterized SQL remains subject to the approved database access strategy.
4. Check parameter binding and injection risks.
5. Review relationship loading and possible N+1 query behavior.
6. Check filtering, sorting, pagination, and aggregation for correctness.
7. Verify that database constraints and backend business validation complement one another.
8. Determine whether multiple writes require an atomic transaction.
9. Review concurrency risks when an operation depends on a record's current state, ownership, or another mutable value.
10. Review whether row locking or another concurrency strategy is necessary; do not add locking speculatively.
11. Review indexes against actual query patterns and explain read/write trade-offs.
12. Verify that the query respects backend authorization and data-visibility boundaries.
13. Test expected behavior and failure cases when the Laravel application and a safe test database are available.

### Absolute Constraints

- Use parameterized queries; never concatenate untrusted input into SQL.
- Do not rely on frontend filtering or authorization to protect records.
- Do not expose records outside the caller's authorized scope.
- Do not introduce a Repository Pattern automatically. Use it only when it provides meaningful value in the approved architecture.
- Do not add indexes without understanding query patterns and write/storage trade-offs.
- Do not use transactions as a substitute for business validation or authorization.
- Do not assume application validation alone protects data integrity against every write path.
- Do not benchmark against production without explicit authorization and an approved plan.
- Do not claim query behavior has been tested when only the query text or design has been reviewed.

---

## 7. Database Verification and Review Checklist

Apply only the checks relevant to the task and available environment.

### 7.1 Requirements and Design

- Entity purpose and ownership are clear.
- Every field has a documented meaning and source.
- PostgreSQL data types are appropriate.
- Nullability and defaults are intentional.
- Relationships and cardinality are clear.
- Constraints reflect confirmed invariants.
- Deletion and retention behavior are explicit.
- Historical requirements are considered.
- Unresolved decisions remain visible.
- Data dictionary and ERD match the approved design.

### 7.2 Laravel Migration

Only applicable after Laravel initialization:

- Migration is in `apps/api/database/migrations/`.
- Migration matches the approved physical database design.
- Migration uses capabilities supported by the installed Laravel version.
- `up()` behavior is understood.
- `down()` behavior and limitations are understood.
- Foreign-key actions are intentional.
- Indexes and constraints are reviewed.
- Existing-data impact is understood.
- Migration order and dependencies are correct.
- Target database is verified before execution.
- The actual schema and migration history remain synchronized.

### 7.3 PostgreSQL and Query Behavior

- SQL is parameterized.
- Transactions are used where atomicity is required.
- Constraints protect appropriate data invariants.
- Query behavior respects authorization boundaries.
- Indexes match real query requirements.
- N+1 behavior is considered where relationships are loaded.
- Historical data is not unintentionally deleted or overwritten.
- Time and numeric precision match the meaning of the data.

### 7.4 Documentation

- `DATABASE_DESIGN.md` reflects approved schema decisions.
- `DATA_DICTIONARY.md` documents table and field meaning.
- ERD reflects approved physical relationships.
- Legacy SQL audit findings are recorded separately from approved target schema.
- No proposed design is presented as implemented schema.

---

## 8. Verification Commands and Safety

Do not run commands merely because they appear in this skill. First inspect the actual repository, dependencies, environment, and database target.

### Before Backend Initialization

Because `apps/api/` is intentionally empty:

- Do not run Laravel Artisan commands.
- Do not run Composer commands on a nonexistent backend application.
- Do not create migration files.
- Do not attempt a database connection based only on the intended database name.
- Review documentation, SQL files, and proposed schema only.

### After Explicit Backend Initialization

From `apps/api/`, use commands only when their required files and dependencies exist:

```bash
php --version
composer --version
composer validate
php artisan migrate:status
php artisan migrate --pretend
php artisan test
```

For PHP syntax and configured tooling, follow `AGENTS.md` and the actual Composer dependencies/scripts.

Important:

- `migrate:status` and `migrate --pretend` may connect to the configured database. Verify the target first.
- `migrate --pretend` is a preview, not proof of correctness or safety.
- Do not execute `php artisan migrate` until the target environment and operation are authorized.
- Never report an unexecuted command as passed.
- If a command or dependency is unavailable, report that limitation instead of inventing a replacement.

---

## 9. Expected Review Output

Choose the output format based on the actual task. Do not produce every section for a small question.

### Entity Design Review

- **Purpose:** Why the entity exists.
- **Ownership:** What data it owns.
- **Relationships:** Cardinality and references.
- **Attributes:** Type, nullability, default, and meaning.
- **Constraints:** Keys, uniqueness, checks, and referential actions.
- **History:** Retention and audit implications.
- **Unresolved Decisions:** `UNKNOWN` / `UNDECIDED` items.
- **Recommendation:** What can be approved now and what must wait.

### Migration Review

- **Scope:** Intended schema change.
- **Preconditions:** Laravel/application/database readiness.
- **Compatibility:** Laravel and PostgreSQL compatibility.
- **Data Impact:** Existing records and backfill implications.
- **Integrity:** Keys, constraints, indexes, and referential actions.
- **Rollback:** What can and cannot be reversed.
- **Verification:** Checks actually executed and their results.
- **Recommendation:** Approve, revise, or block.

### Legacy SQL Audit

- **File:** Exact legacy migration file.
- **Current Behavior:** What the SQL actually does.
- **Compatibility:** Relationship to the approved target design.
- **Risk:** Data loss, drift, obsolete status, or unresolved policy.
- **Decision Needed:** Approval or clarification required.
- **Disposition:** Preserve, adapt later, or mark obsolete only after approval.

For code review findings, use the severity labels from `AGENTS.md` and `skills/code-reviewer/SKILL.md`.

---

## 10. Learning and Collaboration Rules

- Explain the relevant database concept before asking the learner to make a design decision.
- Let the learner attempt entity, relationship, constraint, and query design when the task is a learning challenge.
- Use progressive hints rather than immediately supplying a complete solution.
- Review the learner's reasoning, not only the final SQL or migration.
- Distinguish an incorrect design from a valid design with trade-offs.
- Do not overwhelm a beginner with production-scale abstractions before they are needed.
- For an explicit request to revise documentation, explain the decision and provide the complete revised document as requested.
- Coordinate with `business-analyst`, `backend-engineer`, `system-architect`, `code-reviewer`, and `mentor` when relevant.

---

## General Database Engineer Principles

- PostgreSQL is the approved persistent database engine.
- Laravel ERP Core is the intended owner of application-level database access.
- Laravel migrations become the active schema migration mechanism only after backend initialization.
- `apps/api/` must remain empty until the user explicitly changes that instruction.
- Legacy SQL files under `apps/migration/` are historical artifacts and require review before any conversion.
- The physical database schema is approved incrementally, not all at once.
- Business requirements determine the data model; the data model must not invent business policy.
- Preserve required business history and data integrity.
- Use explicit relationships and constraints where justified.
- Use parameterized queries and transactions where required.
- Avoid premature abstraction, denormalization, and optimization.
- Never claim execution or verification without evidence.

The objective is to build a reliable, understandable, and maintainable PostgreSQL data model for Garment ERP while ensuring that the learner can explain the reasoning behind each database decision.
