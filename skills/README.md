# Skills Registry

This directory contains specialized AI skills used throughout the Garment ERP project.

Each skill folder contains one `SKILL.md` file defining the operating procedures, responsibilities, constraints, and collaboration rules for a specific engineering or business role.

Before performing a complex task, identify and load the relevant skill.

Global project constraints and behavior are defined in:

- `AGENTS.md`
- `AI_CONTEXT.md`

These global documents take precedence over role-specific skill instructions.

---

## 1. Skill Registry

| Skill               | Use It When                                                                                                                                           |
| ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| `mentor`            | Creating learning challenges, reviewing learning submissions, providing hints, conducting understanding checks, and guiding technical interviews.     |
| `backend-engineer`  | Laravel/PHP backend implementation, REST API, routing, middleware, controllers, application services, domain logic, persistence, and backend testing. |
| `business-analyst`  | Analyzing business requirements, garment manufacturing workflows, business rules, requirement changes, and future scope.                              |
| `database-engineer` | PostgreSQL database design, entities, relationships, constraints, indexes, Laravel migrations, and database queries.                                  |
| `code-reviewer`     | Reviewing PHP, SQL, TypeScript, and other project code for correctness, security, maintainability, and architectural consistency.                     |
| `debugger`          | Investigating bugs, failing tests, runtime errors, database problems, API failures, and controlled incident simulations.                              |
| `frontend-engineer` | React, Vite, TypeScript, UI components, REST API integration, and frontend testing.                                                                   |
| `system-architect`  | Architecture explanations, system boundaries, technology decisions, infrastructure evaluation, and architecture decision records.                     |

---

## 2. Typical Skill Combinations

Skills may be combined when a task crosses multiple responsibilities.

### Learning and Implementation

- Backend learning challenge: `mentor` + `backend-engineer`
- Backend implementation review: `mentor` + `code-reviewer` + `backend-engineer`
- Frontend learning challenge: `mentor` + `frontend-engineer`
- Database learning challenge: `mentor` + `database-engineer`

### Business and Product Development

- New feature analysis: `business-analyst` + `mentor`
- New feature implementation: `business-analyst` + relevant implementation skills
- Business requirement change: `business-analyst` + `system-architect` when architectural impact exists
- Acceptance criteria review: `business-analyst` + `code-reviewer` when implementation is involved

### Database and Backend

- New database entity: `business-analyst` + `database-engineer`
- Database migration: `database-engineer` + `backend-engineer`
- Backend persistence implementation: `backend-engineer` + `database-engineer`
- Database-related bug: `debugger` + `database-engineer`

### Frontend and Integration

- Frontend implementation: `frontend-engineer`
- Frontend and backend integration: `frontend-engineer` + `backend-engineer`
- API contract change: `business-analyst` + `backend-engineer` + `frontend-engineer` when applicable
- Frontend review: `frontend-engineer` + `code-reviewer`

### Architecture and Troubleshooting

- Architecture review: `system-architect` + relevant engineering skills
- Backend bug: `debugger` + `backend-engineer`
- Frontend bug: `debugger` + `frontend-engineer`
- Full-stack incident: `debugger` + relevant engineering skills
- Learning-oriented debugging: `debugger` + `mentor`

These combinations are recommendations, not mandatory requirements for every task.

---

## 3. Project Technology Context

All skills must remain consistent with the approved system architecture.

### Backend

- Language: PHP
- Framework: Laravel
- Architecture: Modular Monolith
- API Style: REST API
- Responsibility: Authoritative business logic, validation, authorization, and persistent data operations.

### Database

- Database Engine: PostgreSQL
- Schema Evolution: Version-controlled database migrations.
- Data Integrity: Database constraints and backend validation.

### Frontend

- Framework: React
- Build Tool: Vite
- Language: TypeScript
- Styling: Tailwind CSS
- UI Component System: Follow the approved project decision.

### System Architecture

- Headless ERP Core.
- Frontend communicates with backend through documented API contracts.
- Backend remains independent of frontend clients.
- Business rules must not be duplicated as authoritative logic in the frontend.
- Modular Monolith is the approved architectural direction.

Technology changes require consistency with the approved architecture documentation.

---

## 4. Shared Rules

Every skill must follow these rules.

### Business Requirements

- Never invent business requirements or business rules.
- Use approved business documentation as the source of requirements.
- Preserve unresolved decisions using `UNKNOWN`, `UNDECIDED`, or the applicable approved status.
- Never convert a proposal into an approved requirement without authorization.
- Distinguish business requirements from technical implementation decisions.
- Do not introduce functionality outside the approved Phase 1 scope.

### Engineering

- Follow the approved architecture.
- Keep implementation consistent with Laravel/PHP and PostgreSQL.
- Follow documented REST API contracts.
- Respect backend authorization.
- Keep frontend responsibilities separate from backend business logic.
- Do not introduce dependencies without required approval.
- Avoid unnecessary abstractions and premature complexity.
- Preserve data integrity and historical business records according to approved requirements.

### Development Workflow

- Follow the development sequence in `docs/product/RELEASE_PLAN.md`.
- Check the actual implementation progress before assigning work to a sprint.
- Do not assume that a feature is implemented simply because it appears in the roadmap.
- Implement incrementally.
- Test meaningful changes.
- Preserve existing functionality when modifying code.
- Use meaningful Git commits.
- Update relevant documentation when approved decisions or implementation details change.

### Learning Workflow

- Follow the teaching behavior defined in `AGENTS.md`.
- Explain unfamiliar technical and manufacturing concepts progressively.
- Do not unnecessarily reveal complete solutions before the learner has attempted a challenge.
- Respect explicit requests for direct explanations, documentation reviews, or complete implementations.
- Evaluate understanding rather than relying only on successful execution.

### Documentation Governance

The following documentation must remain consistent:

- Business requirements and workflows.
- Product specifications and user stories.
- System architecture.
- Application architecture.
- API contracts.
- Database design.
- Data dictionary.
- Engineering standards.
- Security requirements.
- Testing requirements.

If documentation conflicts, identify the conflict before implementing behavior based on assumptions.

---

## 5. Skill Loading Rules

1. Identify the primary responsibility involved in the task.
2. Load the corresponding `SKILL.md`.
3. Load additional skills only when their responsibilities are relevant.
4. Follow the operating procedure and constraints defined by each loaded skill.
5. Follow `AGENTS.md` and `AI_CONTEXT.md` at all times.
6. When two skill instructions conflict, follow the global project constraints and report the conflict.
7. If the relevant requirement is unresolved, inspect existing documentation before requesting clarification.

---

## 6. Skill Maintenance

Every skill must be reviewed when:

- Backend or frontend technology changes.
- System architecture changes.
- Development workflow changes.
- Business responsibilities change.
- Product scope changes.
- Engineering standards change.
- Global AI instructions change.

When modifying a skill:

1. Preserve valid existing procedures.
2. Remove obsolete instructions.
3. Update affected technical terminology.
4. Verify referenced file paths.
5. Verify cross-skill references.
6. Preserve unresolved decisions.
7. Ensure consistency with the current project architecture.

A skill must never override an approved business requirement or global project constraint.

---

## 7. Final Objective

The Skills system exists to guide the development of Garment ERP Core while helping the learner develop practical engineering judgment.

The objective is to produce software that is:

- Correct according to approved business requirements.
- Secure.
- Maintainable.
- Testable.
- Understandable.
- Consistent with the approved architecture.
- Appropriate for the actual project scope.

Skills should support independent learning and disciplined engineering, not introduce unnecessary complexity.
