# 0002: Theme decision

Status: Accepted (formerly CR-002)

- **CURRENT:** Theme/Colors UNDECIDED
- **NEW:** shadcn/ui OKLCH theme (lime primary, olive-tinted neutrals, emerald chart ramp, radius 0.45rem) with light and dark tokens. Full tokens: `docs/architecture/APPLICATION_ARCHITECTURE.md`
- **CHANGE:** design tokens decided; light/dark default and toggle still open
- **IMPACT:** Sprint 21 only
- **AFFECTED:** `apps/web` global stylesheet and shadcn/ui setup
- **UNAFFECTED:** Go ERP Core, API, database, business rules, Sprints 0-20
- **DEPRECATED:** none
- **UNKNOWN:**
  - Default theme mode (light or dark).
  - Theme toggle behavior.
  - Domain-specific status color mappings.
  - Semantic color mappings for business states.

Theme tokens must not introduce, define, or change
business statuses.

The current theme defines general semantic tokens
and chart colors. It does not establish a complete
color mapping for ERP-specific business states.

---

## Context

The frontend requires a consistent visual design system
for application components and interfaces.

Theme and color decisions were previously undecided.

## Decision

Use shadcn/ui-compatible OKLCH design tokens.

The selected theme includes:

- Lime primary color.
- Olive-tinted neutral colors.
- Emerald chart color ramp.
- Border radius of `0.45rem`.
- Light and dark theme token definitions.

Full token definitions are maintained in
`APPLICATION_ARCHITECTURE.md`.

## Consequences

- Frontend components must use semantic theme tokens.
- Hard-coded colors should not replace existing semantic tokens.
- Light and dark token definitions are available.
- Default mode and theme toggle behavior remain undecided.
- Domain-specific business status colors require
  separate design decisions.

