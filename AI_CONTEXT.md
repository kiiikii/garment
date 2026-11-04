# AI Project Context & Global Laws

Read this document before working on the project.

It establishes the system mental model, architectural principles, and global constraints that apply across the repository.

Document responsibilities:

- `PRD.md`: Product requirements, scope, and architecture boundaries.
- `AGENTS.md`: AI behavior, coding standards, mentoring, and development workflow.
- `AI_CONTEXT.md`: System mental model and global engineering principles.
- `docs/business/`: Detailed business rules, workflows, roles, and terminology.
- `docs/product/`: Feature specifications, acceptance criteria, and release roadmap.

Do not duplicate detailed requirements from these documents unnecessarily.

---

## 1. Project Overview & Mental Model

### 1.1 What This System Is

Garment ERP is a Headless Production Tracking & Management System for a garment manufacturing company.

The system uses:

- Go as the backend ERP Core.
- PostgreSQL as the persistent data source of truth.
- REST API as the communication contract.
- React + Vite + TypeScript as the frontend client.

The ERP Core manages orders throughout their lifecycle, from customer and order intake through sampling, approval, LoA, production readiness, PPIC preparation, mass production, and completion.

Phase 1 focuses on production tracking and monitoring, not a complete ERP implementation.

### 1.2 The Production Control Tower Analogy

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

The system monitors and coordinates operations. It does not physically perform production activities.

Phase 1 does not manage full inventory, procurement, material consumption, or costing.

### 1.3 The Core Mental Model

Always distinguish these concepts:

**Order Lifecycle**
The overall business journey of an order, from intake to completion.

**Production Stage**
An operational step within mass production, such as Cutting, Sewing, QC, Rework, and Finishing.

**Current Process**
The stage or activity currently being processed.

**Bottleneck**
A constraint that slows down or prevents progress.

**Physical Production Layout**
The arrangement of production functions and work areas. It is not the same as the workflow sequence.

**Internal Duration**
The company's operational time allocation for completing work.

**Customer-Facing Timeline**
The target duration communicated to customers. It is not necessarily identical to internal duration.

**Stage Progress**
The percentage manually submitted by PPIC for a monitored production stage.

**Overall Order Progress**
A calculated summary of the order's progress. Its formula remains UNDECIDED.

**Delay Report**
An official report documenting a production delay or its expected impact.

**Production Issue Report**
An operational issue submitted by Production Staff for PPIC follow-up.

**Waiting / On Hold**
An order condition caused by unmet prerequisites. It must not be treated as a normal production stage.

**LoA**
The commercial approval gate. LoA approval allows the order to proceed to Production Readiness evaluation but does not automatically mean that all production prerequisites are satisfied.

---

## 2. Common AI Misconceptions

Never make the following assumptions:

1. This is not a full ERP. Phase 1 focuses on production tracking and monitoring.

2. The Go backend is the authority for business data, rules, workflows, and permissions.

3. React + Vite is a client, not the source of truth for business rules.

4. An order can contain multiple sizes and quantities. Never model it as a single `size` and `quantity` pair.

5. Internal duration and customer-facing timeline are different concepts.

6. Sampling has an initial internal reference duration of 4 days (Pattern: 2 days + Sample: 2 days) and a customer-facing target of 7 working days.

7. Mass production has an initial internal reference duration of 9 days (Cutting: 2 days + Sewing: 5 days + Finishing: 2 days) and a customer-facing target of 10–14 working days.

8. Sewing takes 5 internal reference days; it is not the total mass-production duration.

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

20. An approved Order Change Request must trigger revalidation of affected readiness and layout requirements. Order Change Requests are subject to the approved business workflow and scope restrictions.

21. A Delay Report does not automatically mean an order is overdue.

22. Completing Finishing does not automatically mean the order is completed. Packing and handover/pickup confirmation are also required.

23. CMT and FOB are the only production models in Phase 1.

24. Fashion Retail is excluded from Phase 1.

25. The learner is a junior engineer. The project must support learning and understanding, not merely code generation.

For detailed operational decisions, always consult `docs/business/BUSINESS_RULES.md`.

---

## 3. Core Technology & Constraints

### 3.1 Backend

- Language: Go.
- Architecture: Headless ERP, Modular Monolith.
- API: REST API.
- Database: PostgreSQL.
- Database migrations: SQL migrations.
- Development database: `garment_erp`.

Rules:

- Prefer the Go standard library.
- Do not assume an ORM.
- Do not introduce a router or additional dependency without approval.
- Keep business logic independent of HTTP transport.
- Keep database access behind the appropriate application/repository boundaries.
- Do not introduce microservices without documented justification.

### 3.2 Frontend

- Framework: React.
- Build tool: Vite.
- Language: TypeScript.
- Styling: Tailwind CSS.
- UI components: shadcn/ui (PROPOSED, subject to compatibility and approval).

Rules:

- The frontend communicates through the REST API.
- Do not duplicate authoritative business rules in React.
- Use semantic design tokens.
- Frontend state management remains UNDECIDED.
- Do not introduce additional packages without explicit approval.

Frontend implementation timing must follow the approved release plan, not an assumed sprint number.

### 3.3 State & Persistence

- PostgreSQL is the persistent source of truth.
- The backend should not depend on frontend session state for business correctness.
- The backend must validate all state-changing operations.
- Historical business events must remain traceable.
- Do not overwrite historical progress, payment, approval, or audit records.

### 3.4 Development Environment

- Development OS: Fedora Linux.
- Editor: Visual Studio Code.
- Use `dnf` for Fedora system dependencies.
- Keep application code OS-independent.

Do not assume production deployment must use Fedora.

Hosting and deployment remain UNDECIDED until validated.

---

## 4. Codebase Mental Map

The following represents the intended repository structure, not a requirement to create every directory immediately.

```text
garment-erp/
├── AGENTS.md
├── AI_CONTEXT.md
├── PRD.md
├── docs/
│   ├── business/
│   │   ├── BUSINESS_RULES.md
│   │   ├── BUSINESS_WORKFLOW.md
│   │   ├── GLOSSARY.md
│   │   ├── USER_ROLES.md
│   │   └── USER_PERSONAS.md
│   ├── product/
│   ├── api/
│   ├── architecture/
│   │   └── decisions/
│   ├── database/
│   └── engineering/
└── apps/
    ├── api/
    │   ├── cmd/
    │   └── internal/
    └── web/
```

### Directory Responsibilities

**`apps/api/cmd/`**
Application entry point and startup configuration.

**`apps/api/internal/`**
Backend application code, developed progressively according to architectural needs.

Possible areas include:

- Handler / Transport
- Application / Service
- Domain
- Repository

Do not create all packages before they are needed.

**`apps/web/`**
React + Vite frontend client.

**`docs/business/`**
Business processes, rules, roles, and terminology.

**`docs/product/`**
Feature definitions, acceptance criteria, and roadmap.

**`docs/architecture/`**
System architecture and architecture decisions.

**`docs/database/`**
Database design, migrations, and data dictionary.

**`docs/engineering/`**
Engineering standards, security, testing, error handling, and Git practices.

### Repository Rules

- Inspect the actual repository before assuming a path exists.
- Create directories only when required by the current task.
- Respect nested `AGENTS.md` instructions when present.
- Do not reorganize the repository without a clear reason and approval.

---

## 5. Absolute Constraints — Global Laws

These principles apply across all project work.

### Law 1: Never Invent Business Requirements

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

### Law 2: Preserve Requirement Integrity

Never silently change confirmed requirements.

Identify:

- Existing requirement.
- Proposed change.
- Reason.
- Impact.
- Affected features.
- Required approval.

Record approved changes in the appropriate documentation.

### Law 3: Preserve Data History

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

### Law 4: Backend Owns Business Authority

- Backend validates business operations.
- Backend enforces authorization.
- Backend controls valid state transitions.
- Frontend validation is supplementary.
- Direct API requests must not bypass business rules.

### Law 5: Respect Architectural Boundaries

- Handlers do not access PostgreSQL directly.
- Application services coordinate use cases.
- Domain logic protects relevant business invariants.
- Repositories manage persistence.
- Frontend components do not own business logic.

Do not introduce unnecessary abstractions.

### Law 6: Protect the Learning Process

Follow the mentoring approach defined in `AGENTS.md`.

For learning challenges, guide the learner through reasoning, design, implementation, debugging, and verification instead of immediately providing complete solutions.

For direct technical explanations, documentation reviews, and explicit implementation requests, respond directly according to the requested task.

Do not let mentoring rules obstruct legitimate requests for explanations or completed deliverables.

### Law 7: Avoid Unnecessary File Replacement

Prefer targeted modifications and clear diffs.

Do not replace entire files when only a few lines need changing, unless the task explicitly requires a complete rewrite or full-file output.

### Law 8: Never Ignore Errors

Handle Go errors explicitly.

Do not silently discard failures.

Never expose internal implementation details to API clients.

### Law 9: Respect Sprint Boundaries

Follow the latest approved `docs/product/RELEASE_PLAN.md`.

Do not implement later-sprint features prematurely.

Verify actual repository progress before selecting the next task.

### Law 10: Verify Before Claiming Completion

Never claim that code works without appropriate verification.

Run checks according to the type and scope of changes.

---

## 6. Production Tracking Mental Model

### 6.1 Order Lifecycle

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

WAITING / ON HOLD is a conditional order state, not a mandatory stage. An order may enter this state when applicable prerequisites are unmet.

This is a conceptual flow. Detailed transitions and exception handling must follow `BUSINESS_WORKFLOW.md` and `BUSINESS_RULES.md`.

### 6.2 Production Models

Phase 1 supports:

**CMT**
Customer provides the main material. The company provides labor and supporting production execution.

**FOB**
Customer provides a down payment. The company procures materials and executes production.

Do not expand these definitions into unconfirmed financial or procurement workflows.

### 6.3 Progress Monitoring

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

### 6.4 Delay Monitoring

Keep these dates conceptually separate:

- Original planned completion date.
- Current estimated completion date.
- Actual completion date.

Delay Reports provide operational explanations and expected additional duration.

Do not equate the existence of a report with an overdue state.

Consult the approved business rules for delay aggregation and overdue calculations.

---

## 7. Cross-Module Consistency

The following concepts must remain consistent across the system.

### Order and LoA

- LoA approval is the commercial gate.
- Direct order editing is restricted after LoA approval.
- Approved changes must follow the Order Change Request workflow.

### Production Readiness and Layout

- Readiness prerequisites must be satisfied before mass production.
- Waiting / On Hold reasons must be recorded and traceable.
- The approved reason categories and configuration rules remain subject to the business documentation.
- Layout is separate from production progress.
- Approved order changes may require revalidation.

### Mass Production and Completion

- QC failure follows the approved Rework process.
- Rework returns to QC for another inspection.
- Finishing completion is not equivalent to order completion.
- Packing and delivery/customer pickup activities must be completed before the order reaches COMPLETED.
- The responsible roles and required confirmation evidence remain subject to approved business rules.

### Delay and Issue Reporting

- Delay Reports are formal delay documentation.
- Production Issue Reports are operational reports from Production Staff.
- Their permissions, statuses, notifications, and workflows are different.

### Dashboard and Data

Dashboard metrics must use the correct underlying data and date semantics.

Do not invent:

- Revenue calculations.
- Order value formulas.
- Overdue definitions.
- Overall production progress formulas.
- Dashboard reporting periods.

See the relevant product specifications and business rules.

---

## 8. Agent Workflow Checklists

Detailed teaching and coding behavior is defined in `AGENTS.md`.

This section provides a concise global checklist.

### 8.1 Pre-Flight

Before writing or suggesting code:

1. Inspect the actual repository.
2. Identify the current sprint and challenge.
3. Confirm that the task is in scope.
4. Read the relevant business rules.
5. Identify unresolved requirements.
6. Check relevant API and database contracts.
7. Identify affected application layers.
8. Check whether dependencies are required.
9. Explain the concept and implementation plan.
10. Let the learner attempt the task when appropriate.

### 8.2 Post-Flight

After implementation or review:

1. Verify formatting.
2. Run appropriate static checks and tests.
3. Review the implementation like a Pull Request.
4. Identify technical debt.
5. Identify unresolved decisions affected.
6. Summarize changed files.
7. Update relevant documentation when necessary.
8. Confirm the learner understands the implementation.
9. Confirm whether the challenge is complete.

Never report unexecuted checks as passed.

---

## 9. Global Engineering Principles

### Simplicity First

- Prefer the simplest solution that satisfies confirmed requirements.
- Avoid premature abstraction.
- Avoid unnecessary frameworks.
- Avoid speculative future features.
- Keep modules cohesive.

### Maintainability

- Use meaningful naming.
- Keep responsibilities clear.
- Avoid duplicated business logic.
- Make dependencies explicit.
- Preserve readable code and documentation.

### Testability

- Design business logic so it can be tested independently where practical.
- Test important business rules and state transitions.
- Use database integration tests where necessary.
- Test failure paths, not only successful paths.

### Security

- Enforce backend authentication and authorization.
- Validate all incoming requests.
- Use parameterized database queries.
- Protect credentials and sensitive information.
- Avoid exposing internal errors.
- Preserve relevant audit records.

Follow `docs/engineering/SECURITY.md` and `docs/engineering/TESTING.md`.

---

## 10. Final Principle

The objective is to build a reliable garment production tracking system while developing the learner's ability to understand, design, implement, test, debug, and maintain real-world software.

The AI must optimize for both:

1. **System correctness:** The implementation reflects actual business requirements and sound engineering principles.
2. **Learning effectiveness:** The learner understands why the system works and can independently explain and maintain it.

Never sacrifice business correctness for implementation convenience.

Never sacrifice learning unnecessarily for faster code generation.

When information is missing, investigate the relevant documentation and repository first. Ask for stakeholder clarification when a business decision is genuinely unresolved.
