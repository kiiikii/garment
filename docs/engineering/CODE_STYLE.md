# Code Style

**Product:** Garment ERP Core
**Status:** ENGINEERING BASELINE — PHASE 1

This document defines coding conventions for the Laravel backend and React frontend.

All code must follow the approved application and system architecture.

---

## 1. PHP / Laravel Backend

Backend code resides in:

`apps/api/`

Laravel / PHP is the only active backend implementation.

### 1.1 General PHP Conventions

- Follow established PHP coding conventions.
- Use consistent formatting.
- Use clear and descriptive names.
- Keep classes focused on clear responsibilities.
- Keep methods focused on a single responsibility.
- Avoid unnecessarily complex functions.
- Avoid duplicated business logic.
- Use appropriate type declarations where applicable.
- Handle errors explicitly.
- Do not silently ignore failures.

### 1.2 Laravel Conventions

- Follow Laravel application conventions.
- Use Laravel routing conventions.
- Keep controllers focused on HTTP responsibilities.
- Use Form Requests where appropriate for request validation.
- Keep business operations outside oversized controllers.
- Use application services or use cases when business coordination requires them.
- Keep persistence logic within the approved persistence approach.
- Use Laravel migrations for version-controlled schema changes.
- Keep API responses consistent with API_CONTRACTS.md.

### 1.3 Layer Responsibilities

Follow the approved application architecture:

Router
-> Middleware
-> Form Request
-> Controller
-> Application Service / Use Case
-> Business Logic
-> Persistence
-> PostgreSQL

Rules:

- Router must not contain business logic.
- Middleware must not implement domain workflows.
- Controllers must not contain complex business rules.
- Form Requests must not replace business validation.
- Application services must coordinate approved business operations.
- Persistence must not independently determine workflow decisions.
- Frontend must not become the authority for business validation.

Introduce layers progressively.

Do not require a separate class for every endpoint.

Do not require a Repository pattern for every entity.

### 1.4 Naming Conventions

Use consistent PHP naming conventions.

General guidance:

- Class names: PascalCase.
- Method names: camelCase.
- Variable names: camelCase.
- Constants: clear uppercase naming where appropriate.
- Namespaces: consistent with the application structure.

Database naming conventions must follow the approved database design.

API resource naming must follow API_CONTRACTS.md.

### 1.5 Dependencies

- Do not add Composer packages without explicit technical justification.
- Prefer Laravel and PHP capabilities when sufficient.
- Avoid unnecessary dependencies.
- Keep dependencies aligned with the approved architecture.
- Do not introduce another backend framework.

### 1.6 Error Handling

- Never silently ignore errors.
- Handle exceptions at the appropriate application boundary.
- Preserve useful diagnostic context.
- Do not expose internal exceptions to API clients.
- Follow ERROR_HANDLING.md.

### 1.7 Database Interaction

- Use the approved Laravel database access strategy.
- Use parameterized database operations.
- Preserve database integrity.
- Use transactions when related business changes must succeed or fail together.
- Avoid unnecessary database queries.
- Do not bypass backend business rules through direct persistence operations.

The final ORM or query strategy remains UNDECIDED.

---

## 2. React / TypeScript Frontend

Frontend code resides in:

`apps/web/`

Confirmed frontend stack:

- React.
- Vite.
- TypeScript.
- Tailwind CSS.

shadcn/ui remains PROPOSED.

### 2.1 React Conventions

- Use React functional components.
- Keep components focused on a clear responsibility.
- Extract reusable logic when it improves maintainability.
- Avoid oversized components with unrelated responsibilities.
- Avoid unnecessary component abstraction.
- Use appropriate React patterns.
- Keep UI and business authority separate.

Do not split components solely to satisfy an arbitrary line-count limit.

### 2.2 TypeScript Conventions

- Prefer explicit and understandable types.
- Avoid unnecessary use of `any`.
- Keep API response types consistent with approved contracts.
- Avoid duplicating API definitions across unrelated components.
- Use clear type and interface names.
- Keep shared types organized.

### 2.3 Styling

- Prefer Tailwind utility classes.
- Prefer shadcn/ui components if the library is approved.
- Use semantic theme tokens.
- Avoid unnecessary hard-coded colors.
- Additional CSS is allowed when existing utilities
  and component APIs are insufficient.
- Do not introduce another styling framework without approval.

### 2.4 Frontend Architecture

- Keep components focused.
- Separate reusable UI from feature-specific UI.
- Centralize API communication.
- Handle loading, empty, success, and error states.
- Keep frontend validation focused on user experience.
- Do not implement authoritative business rules in the frontend.

### 2.5 Frontend Dependencies

- Do not add npm packages without explicit approval.
- Avoid unnecessary global UI libraries.
- Use the existing frontend stack whenever sufficient.
- Do not introduce a separate state management library
  until the project decision is finalized.

Frontend state management remains UNDECIDED.

---

## 3. Naming and Terminology

Use consistent naming across:

- Backend classes.
- Frontend components.
- API resources.
- Database entities.
- Business documentation.

Business terminology must follow:

`docs/business/GLOSSARY.md`

Use approved business terms.

Examples:

- Sample / Look Approval.
- LoA.
- Production Readiness.
- Waiting / On Hold.
- Functional Layout.
- Mass Production.
- Production Issue Report.
- Delay Report.
- Order Change Request.
- Fulfillment.

Do not use different names for the same business concept without documenting the distinction.

---

## 4. No-Invention Rules

Do not add business fields, states, durations, permissions, or validation rules that have not been confirmed.

Requirements must preserve their documented status:

- CONFIRMED.
- CONFIRMED — TEMPORARY.
- PROPOSED.
- UNKNOWN.
- UNDECIDED.
- FUTURE.
- OUT OF SCOPE.

Rules:

1. Do not convert UNDECIDED business rules into implementation assumptions.
2. Do not implement PROPOSED requirements as confirmed capabilities.
3. Do not invent workflow transitions.
4. Do not invent approval authorities.
5. Do not invent progress formulas.
6. Do not invent production duration rules.
7. Do not invent notification recipients.
8. Do not introduce permissions based only on job titles.
9. Do not introduce new business statuses for UI convenience.
10. Request clarification when implementation depends on an unresolved decision.

Keep technical decisions separate from business decisions.

---

## 5. General Maintainability

- Prefer readable code over unnecessary abstraction.
- Avoid duplicated business logic.
- Keep module responsibilities clear.
- Avoid unnecessary coupling.
- Keep dependencies explicit.
- Write code that can be tested.
- Follow approved architecture.
- Update relevant documentation when approved technical decisions change.

Do not optimize prematurely.

Do not introduce complex architectural patterns without a clear requirement.

---

## 6. Operating System Independence

Development environment:

- Fedora Linux.
- Visual Studio Code.

Application code must remain OS-independent.

Do not introduce assumptions that make application behavior dependent on the development operating system.

Environment-specific configuration must be separated from application logic.

---

## 7. Code Review Expectations

Before merging code, review:

- Consistency with approved requirements.
- Consistency with application architecture.
- Naming and readability.
- Business logic placement.
- Request validation.
- Authorization.
- Error handling.
- Database integrity.
- Historical data preservation.
- Test coverage where applicable.
- Unnecessary dependencies.
- Documentation impact.

Code review must identify business-rule changes that have not been approved.

---

## 8. Related Documentation

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/api/API_CONTRACTS.md`
- `docs/database/DATABASE_DESIGN.md`
- `docs/engineering/ERROR_HANDLING.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/GIT_WORKFLOW.md`
- `docs/business/GLOSSARY.md`