# AI Project Context & Global Laws

Read this document before working on the project.

It establishes the system mental model, architectural principles, and global engineering constraints that apply across the repository.

## Document Responsibilities

- `PRD.md`: Product requirements, scope, technical boundaries, MVP requirements, and non-goals.
- `AGENTS.md`: AI behavior, coding standards, mentoring, and development workflow.
- `AI_CONTEXT.md`: System mental model and global engineering principles.
- `docs/business/`: Business rules, workflows, roles, and terminology.
- `docs/product/`: Feature specifications, acceptance criteria, and release roadmap.
- `docs/architecture/`: System architecture, application architecture, and architecture decisions.
- `docs/api/`: API contracts and integration requirements.
- `docs/database/`: Database design, data dictionary, and data integrity requirements.
- `docs/engineering/`: Coding standards, security, testing, error handling, and Git conventions.
- `skills/`: Specialized AI operating procedures.

Do not duplicate detailed requirements from these documents unnecessarily.

---

# 1. Project Overview & Mental Model

## 1.1 What This System Is

Garment ERP is a Headless Production Tracking & Management System for a garment manufacturing company.

The system uses:

- Laravel / PHP as the backend ERP Core.
- PostgreSQL as the persistent data source of truth.
- REST API as the communication contract.
- React + Vite + TypeScript as the frontend client.

The Laravel ERP Core manages orders throughout their lifecycle, from customer and order intake through sampling, approval, LoA, production readiness, PPIC preparation, mass production, and completion.

Phase 1 focuses on production tracking and monitoring, not a complete ERP implementation.

The previous Go backend implementation is discontinued.

Laravel is the only active backend framework.

## 1.2 The Production Control Tower Analogy

Think of the system as a production control tower.

Every order is like an aircraft moving through an operational process.

The control tower records:

- What has happened.
- What is happening now.
- What is waiting.
- What prerequisites are unmet.
- What is progressing according to plan.
- What is delayed.
- What issues require follow-up.
- Who performed an action and when.

The system monitors and coordinates operations.

It does not physically perform production activities.

Phase 1 does not manage full inventory, procurement, material consumption, or costing.

## 1.3 The Core Mental Model

Always distinguish these concepts.

**Order Lifecycle**

The overall business journey of an order, from intake to completion.

**Production Stage**

An operational step within mass production, such as Cutting, Sewing, QC, Rework, and Finishing.

**Current Process**

The stage or activity currently being processed.

**Bottleneck**

A constraint that slows down or prevents progress.

**Physical Production Layout**

The arrangement of production functions and work areas.

It is not the same as the workflow sequence.

**Internal Duration**

The company's operational time allocation for completing work.

**Customer-Facing Timeline**

The target duration communicated to customers.

It is not necessarily identical to internal duration.

**Stage Progress**

The percentage manually submitted by PPIC for a monitored production stage.

**Overall Order Progress**

A calculated summary of the order's progress.

Its formula remains UNDECIDED.

**Delay Report**

An official report documenting a production delay or its expected impact.

**Production Issue Report**

An operational issue submitted by Production Staff for PPIC follow-up.

**Waiting / On Hold**

An order condition caused by unmet prerequisites.

It must not be treated as a normal production stage.

**LoA**

The commercial approval gate.

LoA approval allows the order to proceed to Production Readiness evaluation but does not automatically mean that all production prerequisites are satisfied.

---

# 2. Common AI Misconceptions

Never make the following assumptions.

1. This is not a full ERP. Phase 1 focuses on production tracking and monitoring.

2. Laravel ERP Core is the authority for business data, rules, workflows, and permissions.

3. React + Vite is a client, not the source of truth for business rules.

4. An order can contain multiple sizes and quantities. Never model it as a single `size` and `quantity` pair.

5. Internal duration and customer-facing timeline are different concepts.

6. Sampling has an initial internal reference duration of 4 days:
   - Pattern Preparation: 2 days.
   - Sample Production: 2 days.

   Its customer-facing target is 7 working days.

7. Mass production has an initial internal reference duration of 9 days:
   - Cutting: 2 days.
   - Sewing: 5 days.
   - Finishing: 2 days.

   Its customer-facing target is 10–14 working days.

8. Sewing takes 5 internal reference days. It is not the total mass-production duration.

9. QC and Rework do not yet have separately confirmed duration allocations.

10. Physical production layout is not a production stage.

11. Current process and bottleneck are different concepts.

12. PPIC manually submits percentage-based progress updates only for Cutting, Sewing, and Finishing.

13. QC and Rework are monitored through operational statuses, not percentage-based progress updates.

14. Progress updates are historical records. A submitted value must not be silently recalculated or overwritten.

15. Overall order progress must not use an assumed arithmetic average or any other unapproved formula.

16. Reject and Rework percentages must not be invented without an approved measurement method.

17. A sample/look that is not approved does not automatically mean the order is cancelled.

18. Sample/look approval does not automatically mean production is ready.

19. LoA approval does not automatically mean all production prerequisites are satisfied.

20. An approved Order Change Request must trigger revalidation of affected readiness and layout requirements.

21. A Delay Report does not automatically mean an order is overdue.

22. Completing Finishing does not automatically mean the order is completed. Packing and handover/pickup confirmation are also required.

23. CMT and FOB are the only production models in Phase 1.

24. Fashion Retail is excluded from Phase 1.

25. The learner is a junior engineer. The project must support learning and understanding, not merely code generation.

26. Laravel migrations are the active database schema migration mechanism.

27. Existing `.up.sql` and `.down.sql` files under `apps/migration/` are legacy migration artifacts from the previous implementation. They must not automatically be treated as active Laravel migrations.

28. The existence of a Laravel-oriented document does not prove that the Laravel application has already been initialized or implemented.

29. Do not assume PHP, Laravel, Composer dependencies, or application scripts exist until the actual repository has been inspected.

30. Do not assume a Repository Pattern, Service Layer, Action Pattern, or additional architecture package is mandatory for every Laravel feature.

For detailed operational decisions, always consult:

`docs/business/BUSINESS_RULES.md`

---

# 3. Core Technology & Constraints

## 3.1 Backend

- Language: PHP.
- Framework: Laravel.
- Architecture: Headless ERP, Modular Monolith.
- API: REST API.
- Database: PostgreSQL.
- Database name: `garment_erp`.
- Database migrations: Laravel migrations.
- Dependency management: Composer.

Laravel is the only active backend framework.

The previous Go implementation is discontinued.

### Backend Rules

- Follow the actual PHP and Laravel versions declared by the application.
- Inspect `composer.json` before assuming installed dependencies.
- Inspect the actual Laravel application structure before implementation.
- Prefer Laravel-native capabilities when appropriate.
- Keep business logic independent of HTTP transport.
- Keep controllers focused on HTTP responsibilities.
- Use Form Requests where appropriate.
- Introduce application services or use cases when meaningful coordination is required.
- Keep persistence responsibilities within the approved application boundaries.
- Use Eloquent, Query Builder, or parameterized SQL according to the approved database access strategy.
- Do not assume an ORM or Repository Pattern is mandatory.
- Do not introduce additional frameworks or architectural packages without approval.
- Do not introduce microservices without documented justification.

### Laravel Request Lifecycle

Understand the logical flow:

HTTP Request
→ Laravel Application
→ API Router
→ Middleware
→ Form Request Validation
→ Controller
→ Application Service / Use Case (when required)
→ Business Logic
→ Persistence
→ PostgreSQL
→ API Resource / Response
→ HTTP Response

Not every endpoint requires every layer.

Keep implementation practical and consistent with the approved application architecture.

## 3.2 Frontend

- Framework: React.
- Build tool: Vite.
- Language: TypeScript.
- Styling: Tailwind CSS.
- UI components: shadcn/ui (PROPOSED, subject to compatibility and approval).
- Application type: Single Page Application (SPA).

Rules:

- The frontend communicates through the REST API.
- Do not duplicate authoritative business rules in React.
- Use semantic design tokens.
- Frontend state management remains UNDECIDED.
- Do not introduce additional packages without explicit approval.
- Do not assume authentication or state-management libraries have been selected.

Frontend implementation timing must follow the approved release plan, not an assumed sprint number.

## 3.3 State & Persistence

- PostgreSQL is the persistent source of truth.
- The backend must not depend on frontend session state for business correctness.
- The backend must validate all state-changing operations.
- Historical business events must remain traceable.
- Do not overwrite historical progress, payment, approval, or audit records.
- Do not use temporary in-memory application state as authoritative business storage.

## 3.4 Development Environment

- Development OS: Fedora Linux.
- Editor: Visual Studio Code.
- Use `dnf` for Fedora system dependencies.
- PHP dependencies are managed through Composer.
- Frontend dependencies are managed through npm.

Keep application code OS-independent.

Do not assume production deployment must use Fedora.

Hosting and deployment remain UNDECIDED until validated.

## 3.5 Version and Dependency Constraints

The following must be verified from the actual repository:

- PHP version.
- Laravel version.
- Composer version.
- PostgreSQL compatibility.
- Node.js version.
- npm version.
- React version.
- Vite version.
- TypeScript version.
- Installed frontend packages.

Do not invent version numbers.

Do not install packages simply because they are commonly used in other Laravel projects.

Follow the actual dependency configuration.

---

# 4. Codebase Mental Map

The following describes the intended repository organization.

It is not a requirement to create every directory immediately.

## 4.1 Intended Monorepo Structure

```text
garment-erp/
├── AGENTS.md
├── AI_CONTEXT.md
├── PRD.md
├── GEMINI.md
├── README.md
│
├── apps/
│   ├── api/
│   │   ├── app/
│   │   ├── bootstrap/
│   │   ├── config/
│   │   ├── database/
│   │   │   ├── migrations/
│   │   │   ├── factories/
│   │   │   └── seeders/
│   │   ├── public/
│   │   ├── resources/
│   │   ├── routes/
│   │   ├── storage/
│   │   ├── tests/
│   │   ├── vendor/
│   │   ├── artisan
│   │   ├── composer.json
│   │   └── composer.lock
│   │
│   ├── web/
│   │   ├── public/
│   │   ├── src/
│   │   ├── package.json
│   │   ├── package-lock.json
│   │   └── vite.config.ts
│   │
│   └── migration/
│       └── Legacy SQL migration artifacts
│
├── docs/
│   ├── business/
│   ├── product/
│   ├── api/
│   ├── architecture/
│   │   └── decisions/
│   ├── database/
│   ├── engineering/
│   └── diagrams/
│
├── skills/
│   ├── mentor/
│   ├── business-analyst/
│   ├── system-architect/
│   ├── backend-engineer/
│   ├── frontend-engineer/
│   ├── database-engineer/
│   ├── debugger/
│   └── code-reviewer/
│
├── docker/
├── docker-compose.yml
├── Makefile
└── .gitignore
```

This is an intended logical layout.

Some files and directories may not exist yet.

Do not create them merely to make the structure look complete.

## 4.2 Laravel Application Responsibilities

**`apps/api/app/`**

Contains Laravel application code.

Depending on the approved implementation, relevant responsibilities may include:

- Models.
- HTTP Controllers.
- Middleware.
- Form Requests.
- API Resources.
- Application Services.
- Domain components.
- Providers.

Do not force every logical responsibility into a custom directory.

Follow Laravel conventions and the approved application architecture.

**`apps/api/bootstrap/`**

Contains Laravel application bootstrap configuration.

**`apps/api/config/`**

Contains Laravel configuration files.

**`apps/api/database/migrations/`**

Contains version-controlled Laravel database migrations.

This is the active migration location.

**`apps/api/database/factories/`**

Contains model factories when required for testing or development.

**`apps/api/database/seeders/`**

Contains approved database seeders.

Do not seed unapproved production business data.

**`apps/api/routes/`**

Contains Laravel route definitions.

API routes must follow the approved REST API contracts.

**`apps/api/tests/`**

Contains Laravel application tests.

**`apps/web/`**

Contains the React + Vite + TypeScript frontend client.

**`apps/migration/`**

Contains legacy SQL migration artifacts from the previous backend implementation.

These files must be reviewed before conversion into Laravel migrations.

Do not treat them as automatically executable Laravel migrations.

## 4.3 Legacy Migration Transition

The previous Go implementation used standalone SQL migration files.

Existing files include:

- `000001_create_customer.up.sql`
- `000002_create_sizes.up.sql`
- `000003_create_orders.up.sql`
- `000004_create_order_sizes.up.sql`
- `000005_create_order_images.up.sql`
- `000006_create_order_status_history.up.sql`

Each has a corresponding rollback file.

These migrations were created using the previous `golang-migrate` workflow.

The Laravel application must use Laravel migrations.

Transition rules:

1. Preserve the original SQL migration files during review.
2. Inspect their schema, constraints, indexes, and seed data.
3. Compare the existing schema with DATABASE_DESIGN.md and DATA_DICTIONARY.md.
4. Identify differences between legacy SQL and the approved database design.
5. Review whether any existing database contains data that must be preserved.
6. Convert approved schema definitions into Laravel migration classes.
7. Verify migration execution and rollback behavior in a controlled development database.
8. Do not execute destructive migrations against an unknown database.
9. Do not assume that the previous database schema is already compatible with the current Laravel design.
10. Do not delete legacy migration files without an approved migration and archival plan.

The legacy migration files are historical implementation artifacts, not the active Laravel migration system.

## 4.4 Repository Rules

- Inspect the actual repository before assuming a path exists.
- Distinguish intended structure from implemented structure.
- Create directories only when required by the current task.
- Respect nested `AGENTS.md` instructions when present.
- Do not reorganize the repository without a clear reason and approval.
- Do not assume a file exists merely because documentation references it.
- Do not claim that a Laravel application is initialized without verifying its actual files.

---

# 5. Absolute Constraints — Global Laws

These principles apply across all project work.

## Law 1: Never Invent Business Requirements

Never invent:

- Business rules.
- Order fields.
- Statuses.
- Durations.
- Validation requirements.
- Approval consequences.
- Department responsibilities.
- Production measurement methods.
- Progress formulas.
- Financial calculations.

Use the requirement status and classification defined in the project's documentation governance.

Common statuses include:

- CONFIRMED
- CONFIRMED — TEMPORARY
- PROPOSED
- UNKNOWN
- UNDECIDED
- FUTURE

Use OUT OF SCOPE as a scope classification, not as a requirement status.

Do not introduce alternative status labels without approval.

Ask for clarification when a missing decision affects implementation correctness.

## Law 2: Preserve Requirement Integrity

Never silently change confirmed requirements.

Identify:

- Existing requirement.
- Proposed change.
- Reason.
- Impact.
- Affected features.
- Required approval.

Record approved changes in the appropriate documentation.

## Law 3: Preserve Data History

Never silently overwrite historical records.

This applies particularly to:

- Stage progress updates.
- Order status transitions.
- Payment records.
- Payment corrections.
- Approval decisions.
- Order Change Requests.
- Production Issue Reports.
- Delay Reports.

Historical changes must remain traceable according to the approved data model.

## Law 4: Backend Owns Business Authority

- Laravel backend validates business operations.
- Laravel backend enforces authorization.
- Laravel backend controls valid state transitions.
- Frontend validation is supplementary.
- Direct API requests must not bypass business rules.
- PostgreSQL maintains persistent business data.

The frontend must never become an alternative business authority.

## Law 5: Respect Architectural Boundaries

- Controllers handle HTTP responsibilities.
- Middleware handles cross-cutting concerns.
- Form Requests handle applicable request validation.
- Application services coordinate operations when needed.
- Domain logic protects relevant business invariants.
- Persistence components manage database interaction.
- Frontend components do not own business logic.

Keep architectural layers practical.

Do not require a Repository Pattern or Service Layer for every operation.

Avoid premature abstractions.

## Law 6: Protect the Learning Process

Follow the mentoring approach defined in `AGENTS.md`.

For learning challenges, guide the learner through reasoning, design, implementation, debugging, and verification instead of immediately providing complete solutions.

For direct technical explanations, documentation reviews, and explicit implementation requests, respond directly according to the requested task.

Do not let mentoring rules obstruct legitimate requests for explanations or completed deliverables.

## Law 7: Avoid Unnecessary File Replacement

Prefer targeted modifications and clear diffs.

Do not replace entire files when only a few lines need changing, unless the task explicitly requires a complete rewrite or full-file output.

Preserve unrelated existing functionality.

## Law 8: Never Ignore Errors

Handle PHP exceptions and application failures explicitly.

Do not silently discard failures.

Never expose internal implementation details to API clients.

Use Laravel's logging and exception handling capabilities according to the approved engineering standards.

## Law 9: Respect Sprint Boundaries

Follow the latest approved:

`docs/product/RELEASE_PLAN.md`

Do not implement later-sprint features prematurely.

Verify actual repository progress before selecting the next task.

Historical Go implementation milestones must not automatically be treated as completed Laravel milestones.

## Law 10: Verify Before Claiming Completion

Never claim that code works without appropriate verification.

Run checks according to the type and scope of changes.

Distinguish:

- Verified.
- Not verified.
- Failed.
- Blocked by environment.
- Not applicable.

Never report unexecuted checks as passed.

---

# 6. Production Tracking Mental Model

## 6.1 Order Lifecycle

The high-level business flow is:

ORDER
→ SAMPLING
→ SAMPLE / LOOK APPROVAL
→ LoA
→ PRODUCTION READINESS
→ WAITING / ON HOLD (when necessary)
→ PPIC PREPARATION
→ FUNCTIONAL LAYOUT
→ MASS PRODUCTION
→ PACKING
→ DELIVERY / CUSTOMER PICKUP
→ COMPLETED

WAITING / ON HOLD is a conditional order state, not a mandatory stage.

An order may enter this state when applicable prerequisites are unmet.

This is a conceptual flow.

Detailed transitions and exception handling must follow:

- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/BUSINESS_RULES.md`

## 6.2 Production Models

Phase 1 supports:

**CMT**

Customer provides the main material.

The company provides labor and supporting production execution.

**FOB**

Customer provides a down payment.

The company procures materials and executes production.

Do not expand these definitions into unconfirmed financial or procurement workflows.

## 6.3 Progress Monitoring

Stage-level progress is entered by PPIC.

Percentage-based progress monitoring applies only to:

- Cutting
- Sewing
- Finishing

QC and Rework use operational status tracking instead.

The system must preserve:

- Submitted percentage.
- Related order and production stage.
- Timestamp.
- Submitting user.

Distinguish:

- No progress update exists.
- A progress update exists with no percentage change.
- Progress has increased.
- Progress has decreased, if the approved rules permit it.

Never assume progress is monotonically increasing.

The overall order progress formula is UNDECIDED.

Do not derive an overall percentage until the calculation method is approved.

## 6.4 Delay Monitoring

Keep these dates conceptually separate:

- Original planned completion date.
- Current estimated completion date.
- Actual completion date.

Delay Reports provide operational explanations and expected additional duration.

Do not equate the existence of a report with an overdue state.

Consult the approved business rules for delay aggregation and overdue calculations.

---

# 7. Cross-Module Consistency

The following concepts must remain consistent across the system.

## Order and LoA

- LoA approval is the commercial gate.
- Direct order editing is restricted after LoA approval.
- Approved changes must follow the Order Change Request workflow.

## Production Readiness and Layout

- Readiness prerequisites must be satisfied before mass production.
- Waiting / On Hold reasons must be recorded and traceable.
- The approved reason categories and configuration rules remain subject to the business documentation.
- Layout is separate from production progress.
- Approved order changes may require revalidation.

## Mass Production and Completion

- QC failure follows the approved Rework process.
- Rework returns to QC for another inspection.
- Finishing completion is not equivalent to order completion.
- Packing and delivery/customer pickup activities must be completed before the order reaches COMPLETED.
- The responsible roles and required confirmation evidence remain subject to approved business rules.

## Delay and Issue Reporting

- Delay Reports are formal delay documentation.
- Production Issue Reports are operational reports from Production Staff.
- Their permissions, statuses, notifications, and workflows are different.

## Dashboard and Data

Dashboard metrics must use the correct underlying data and date semantics.

Do not invent:

- Revenue calculations.
- Order value formulas.
- Overdue definitions.
- Overall production progress formulas.
- Dashboard reporting periods.

See the relevant product specifications and business rules.

---

# 8. Agent Workflow Checklists

Detailed teaching and coding behavior is defined in:

`AGENTS.md`

This section provides a concise global checklist.

## 8.1 Pre-Flight

Before writing or suggesting code:

1. Inspect the actual repository.
2. Identify the current sprint and challenge.
3. Confirm that the task is in scope.
4. Read the relevant business rules.
5. Identify unresolved requirements.
6. Check relevant API and database contracts.
7. Identify affected Laravel application layers.
8. Check whether dependencies are required.
9. Inspect Composer and npm configuration when relevant.
10. Explain the concept and implementation plan.
11. Let the learner attempt the task when appropriate.

## 8.2 Post-Flight

After implementation or review:

1. Verify PHP syntax where relevant.
2. Run appropriate Laravel formatting and static checks when configured.
3. Run relevant Laravel tests.
4. Verify database behavior when applicable.
5. Review the implementation like a Pull Request.
6. Identify technical debt.
7. Identify unresolved decisions affected.
8. Summarize changed files.
9. Update relevant documentation when necessary.
10. Confirm the learner understands the implementation.
11. Confirm whether the challenge is complete.

Never report unexecuted checks as passed.

---

# 9. Global Engineering Principles

## 9.1 Simplicity First

- Prefer the simplest solution that satisfies confirmed requirements.
- Avoid premature abstraction.
- Avoid unnecessary frameworks.
- Avoid speculative future features.
- Keep modules cohesive.
- Prefer existing PHP and Laravel capabilities when sufficient.
- Do not add packages merely to follow common industry conventions.

## 9.2 Maintainability

- Use meaningful naming.
- Keep responsibilities clear.
- Avoid duplicated business logic.
- Make dependencies explicit.
- Preserve readable code and documentation.
- Follow the actual application architecture.
- Keep backend business behavior independent of frontend presentation.

## 9.3 Testability

- Design business logic so it can be tested independently where practical.
- Test important business rules and state transitions.
- Use database integration tests where necessary.
- Test failure paths, not only successful paths.
- Verify authorization boundaries.
- Preserve regression coverage when modifying existing functionality.

## 9.4 Security

- Enforce backend authentication and authorization.
- Validate all incoming requests.
- Use safe parameterized database operations.
- Protect credentials and sensitive information.
- Avoid exposing internal errors.
- Preserve relevant audit records.
- Apply appropriate file-upload and data-access controls.

Follow:

- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/ERROR_HANDLING.md`

---

# 10. Final Principle

The objective is to build a reliable garment production tracking system while developing the learner's ability to understand, design, implement, test, debug, and maintain real-world software.

The AI must optimize for both:

1. **System correctness:** The implementation reflects actual business requirements and sound engineering principles.
2. **Learning effectiveness:** The learner understands why the system works and can independently explain and maintain it.

Never sacrifice business correctness for implementation convenience.

Never sacrifice learning unnecessarily for faster code generation.

When information is missing, investigate the relevant documentation and repository first.

Ask for stakeholder clarification when a business decision is genuinely unresolved.
