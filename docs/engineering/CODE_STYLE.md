# Code Style

## Go (Backend)

- Format Go code with `gofmt`.
- Check code with `go vet ./...`.
- Use idiomatic Go naming conventions.
- Keep functions and packages focused on clear responsibilities.
- Never ignore errors. Wrap errors with meaningful context
  (see `ERROR_HANDLING.md`).
- Handlers must not access PostgreSQL directly.
- Business rules and workflows belong in the Service / Use Case layer.
- Introduce architectural layers progressively when required
  by a sprint.
- Follow the approved application flow:
  Router -> Middleware -> Handler -> Service -> Repository -> PostgreSQL.
- Do not add external Go dependencies without explicit approval.
- Prefer the Go standard library when it is sufficient.
- Internal packages may be introduced when justified
  by application responsibilities and dependency boundaries.
- Keep application code OS-independent.
  Development uses Fedora Linux, but application behavior
  must not depend on the development operating system.

## React / TypeScript (Frontend, Sprint 21+)

- Use React functional components.
- Keep components focused on a clear responsibility.
- Extract reusable logic and UI when doing so improves
  clarity, maintainability, or testability.
- Avoid oversized components with unrelated responsibilities.
- Do not split components solely to satisfy an arbitrary
  line-count limit.
- Prefer shadcn/ui components and Tailwind utility classes.
- Use semantic theme tokens instead of hard-coded colors.
- Additional CSS is allowed when Tailwind utilities and
  existing component APIs are insufficient.
- Do not introduce a separate styling framework without approval.
- Do not add npm packages or global UI libraries without
  explicit approval.
- Frontend validation is for user experience only.
  The backend remains authoritative for business validation.

## Naming and No-Invention

- Use clear and consistent naming conventions.
- Follow idiomatic conventions for each programming language.
- Do not add business fields, states, durations, or validation
  rules that have not been confirmed.
- Use the labels UNKNOWN, OPTIONS, UNDECIDED, and CONFIGURABLE
  in documentation when requirements are unresolved.
- Do not convert an UNDECIDED business rule into an
  implementation assumption.
- If implementation depends on an unresolved requirement,
  request clarification instead of inventing behavior.
- Keep technical implementation decisions separate from
  business decisions.

## General Maintainability

- Prefer readable and maintainable code over unnecessary abstraction.
- Avoid duplicated business logic across different layers.
- Do not introduce abstractions without a clear requirement.
- Keep implementation consistent with the approved
  architecture and engineering standards.
- Update relevant documentation when an approved technical
  decision changes.
