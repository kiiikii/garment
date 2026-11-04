# Gemini Project Instructions

You are working on the Garment ERP Core project.

## 1. Mandatory Project Context

Before performing any development task, read:

- `AGENTS.md`
- `AI_CONTEXT.md`
- `PRD.md`

These files define the project's development rules, engineering principles,
product requirements, scope, and constraints.

Do not contradict them.

## 2. Task-Specific Documentation

Read only the documentation relevant to the current task.

For backend tasks, inspect relevant documents in:

- `docs/business/`
- `docs/product/`
- `docs/api/`
- `docs/architecture/`
- `docs/database/`
- `docs/engineering/`
- `docs/diagrams/`

Do not assume every document is relevant to every task.

## 3. Skills

Before performing a specialized task, read its corresponding skill:

- Backend implementation: `skills/backend-engineer/SKILL.md`
- Database design and implementation: `skills/database-engineer/SKILL.md`
- Business requirements: `skills/business-analyst/SKILL.md`
- Architecture: `skills/system-architect/SKILL.md`
- Code review: `skills/code-reviewer/SKILL.md`
- Debugging: `skills/debugger/SKILL.md`
- Learning and mentoring: `skills/mentor/SKILL.md`

For frontend tasks, use `skills/frontend-engineer/SKILL.md`
when frontend implementation is within scope.

Combine skills when a task crosses multiple responsibilities.

## 4. Development Rules

- Follow the current development roadmap and sprint.
- Do not skip prerequisites.
- Do not implement features outside the approved scope.
- Do not invent business rules.
- Preserve unresolved decisions as UNKNOWN or UNDECIDED.
- Do not introduce dependencies or architectural changes without approval.
- Inspect the existing code before modifying it.
- Prefer small, incremental, testable changes.

## 5. Backend-First Development

The Go ERP Core is the business authority.

The initial development focus is the backend under `apps/api/`.

Do not start frontend implementation before its prerequisites and approved
development stage.

The frontend must consume the backend REST API rather than duplicate
business logic.

## 6. Working Agreement

Before implementing a task:

1. Identify the current sprint and task.
2. Read the applicable skill and documentation.
3. Explain the objective and relevant concepts.
4. Present an implementation plan when the task is complex.
5. Wait for approval when a decision or action requires human approval.

During implementation:

- Follow the applicable skill.
- Explain important decisions.
- Avoid unnecessary changes outside the task scope.
- Run relevant verification commands.

After implementation:

- Report changed files.
- Explain what was implemented.
- Report verification results honestly.
- Identify unresolved issues.

Do not automatically rewrite entire files when a targeted change is sufficient.