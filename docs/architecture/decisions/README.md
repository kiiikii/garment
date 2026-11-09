# Architecture and Requirement Decisions

This directory stores Architecture Decision Records (ADRs)
and significant requirement change records.

Each decision or requirement change must have its own record.

Never silently overwrite an existing requirement or
architectural decision.

Existing records must remain available for historical reference.

## Change Record Format

Use the following format when documenting a significant
requirement change:

CURRENT:
NEW:
CHANGE:
IMPACT:
AFFECTED:
UNAFFECTED:
DEPRECATED:
UNKNOWN:

### Field Definitions

- CURRENT: The currently accepted requirement or decision.
- NEW: The proposed or newly accepted requirement or decision.
- CHANGE: A clear description of what has changed.
- IMPACT: The expected impact on the system and project.
- AFFECTED: Components, documents, modules, or workflows
  affected by the change.
- UNAFFECTED: Components or requirements that remain unchanged.
- DEPRECATED: Previous requirements or decisions that are
  no longer applicable.
- UNKNOWN: Unresolved details or consequences requiring
  further analysis.

A change record must identify whether the change has been
proposed or accepted.

Do not implement a proposed change as an accepted requirement.

## Change Impact Rules

Before implementing a significant requirement change:

1. Identify the current source of truth.
2. Compare the current and new requirements.
3. Identify affected and unaffected components.
4. Evaluate data compatibility and migration requirements.
5. Evaluate API compatibility.
6. Identify affected documentation.
7. Update relevant decisions and implementation plans.
8. Preserve historical decisions and records.

Do not restart, rewrite, or restructure the project unless
the new requirement actually requires it.

Do not introduce unrelated refactoring as part of a
requirement change.

## Domain Impact Analysis

When future scope becomes active, analyze its impact
on the existing system before implementation.

Future scope includes:

- Inventory
- Stock Opname
- HPP
- Costing
- Material Consumption
- Leftover Goods
- Bonus Goods

The analysis must consider:

- Existing entities
- New entities
- Entity relationships
- Source of truth
- Database migrations
- Existing and historical data
- API compatibility
- Frontend impact
- Workflow and business rules
- Authorization and role permissions
- Reports
- Notifications
- Tests
- Deployment and operational impact

Do not randomly add fields to existing entities.

New capabilities must preserve existing business rules,
data integrity, and architectural boundaries.

Not every new capability requires a new entity or table.
Database changes must follow the approved Database Design
and Data Dictionary.

## ADR Status

Use the following statuses:

- Proposed: Decision is under consideration and is not
  yet an implementation requirement.
- Accepted: Decision has been approved and is applicable.
- Deprecated: Decision is no longer recommended or applicable.
- Superseded: Decision has been replaced by another ADR.

Superseded and deprecated records must not be deleted.

They must reference the decision that replaces them
when applicable.

## Index

| ID | Title | Status | Date | Related Documents |
|---|---|---|---|---|
| 0001 | Frontend stack: React + Vite + TypeScript + shadcn/ui | Accepted | See ADR | SYSTEM_ARCHITECTURE.md, APPLICATION_ARCHITECTURE.md |
| 0002 | Theme: shadcn/ui OKLCH tokens | Accepted | See ADR | APPLICATION_ARCHITECTURE.md |
| 0003 | Backend architecture: Laravel 13.x (PHP 8.5.x) | Accepted | See ADR | SYSTEM_ARCHITECTURE.md, APPLICATION_ARCHITECTURE.md, PRD.md |