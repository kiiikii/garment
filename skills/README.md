# Skills

Each folder holds one `SKILL.md`: a step-by-step operating procedure for one role. Before a complex task, find the matching skill and follow it exactly. Global laws are in `AI_CONTEXT.md`; teaching behavior is in `agent.md`.

| Skill               | Use it when                                                                                                                   |
| ------------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| `mentor`            | Creating challenges, reviewing submissions, hints, understanding checks, interviews. Load first for any teaching interaction. |
| `backend-engineer`  | Go API, handlers, routing, service/repository layers, Go tests (Sprint 0+)                                                    |
| `business-analyst`  | Before implementing a capability; requirement changes; future scope impact analysis                                           |
| `database-engineer` | Tables, relationships, constraints, migrations, queries (Sprint 2+)                                                           |
| `code-reviewer`     | The learner submits code for review                                                                                           |
| `debugger`          | Bugs, failing tests, incident simulation (Sprint 23)                                                                          |
| `frontend-engineer` | React + Vite + shadcn/ui client (Sprint 21+ only)                                                                             |
| `system-architect`  | Architecture explanations, decisions, technology choices                                                                      |

## Typical combinations

- Learner submits a handler: `mentor` + `code-reviewer` + `backend-engineer`
- New feature starts: `business-analyst` -> `database-engineer` / `backend-engineer` (with `mentor`)
- Learner reports a bug: `debugger` + `mentor`
- Requirement changes: `business-analyst` + `system-architect`

# Skills

Each folder contains one `SKILL.md`, which defines a step-by-step
operating procedure for a specific role.

Before performing a complex task, identify and load the relevant skill.

Global engineering principles are defined in `AI_CONTEXT.md`.
Global AI behavior, coding standards, and development workflow
are defined in `AGENTS.md`.

## Skill Registry

| Skill | Use it when |
|---|---|
| `mentor` | Creating challenges, reviewing learning submissions, providing hints, conducting understanding checks, and guiding interviews. Load first for teaching interactions. |
| `backend` | Go API, handlers, routing, application services, repositories, domain logic, and Go tests. |
| `business` | Analyzing business requirements, workflows, business rules, and requirement changes before implementation. |
| `database` | Database schema, relationships, constraints, SQL queries, and migrations. |
| `code-reviewer` | Reviewing submitted code for correctness, maintainability, security, and consistency. |
| `debugger` | Investigating bugs, failing tests, runtime errors, and troubleshooting. |
| `frontend` | React, Vite, TypeScript, UI components, and REST API integration. |
| `system-architect` | Architecture explanations, system boundaries, technical decisions, and technology evaluation. |

## Typical Skill Combinations

- Learner submits a backend handler: `mentor` + `code-reviewer` + `backend`
- New feature implementation: `business` → relevant implementation skills + `mentor` when learning guidance is needed
- Database design: `business` + `database`
- Learner reports a bug: `debugger` + `mentor`
- Requirement changes: `business` + `system-architect`
- Frontend implementation: `frontend` + `business` when business requirements need clarification
- Architecture review: `system-architect` + relevant engineering skills

## Shared Rules

- Never invent business requirements or rules.
- Preserve unresolved decisions as `UNKNOWN` or `UNDECIDED`.
- Follow the approved requirement statuses and classifications.
- Follow the development and release order in `docs/product/RELEASE_PLAN.md`.
- Do not introduce dependencies without approval.
- Respect the global instructions in `AGENTS.md` and `AI_CONTEXT.md`.
- Do not let role-specific instructions override global project constraints.
- If a decision affecting implementation correctness is unresolved, investigate existing documentation first and request stakeholder clarification when necessary.
