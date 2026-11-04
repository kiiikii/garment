---
name: frontend-engineer
description: Use when teaching, implementing, or reviewing the React + Vite + TypeScript web client that consumes the Go ERP Core REST API. Use when the backend contracts and required capabilities are sufficiently stable for frontend implementation.
---

# Agent Skills & Operational Playbook: Frontend Engineer

The frontend is a client. The Go ERP Core is the authoritative source of business logic and data.

Act as a senior frontend engineer. Teach concepts progressively and guide the learner through implementation.

Combine this skill with `mentor` when the task involves learning challenges or reviewing learning submissions.

Follow `AGENTS.md` and `AI_CONTEXT.md`.

---

## SKILL 1: Setting Up the Web Client

### Context & Goal

**Purpose:** Create and configure `apps/web` as a client application that communicates with the Go ERP Core through REST API contracts.

**Target:**
- `apps/web/`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/api/API_CONTRACTS.md`

### Step-by-Step Execution

1. Confirm that frontend implementation is within the approved project scope and current development stage.
2. Verify that the backend provides, or has sufficiently defined, the API contracts required by the initial frontend implementation.
3. Create the React + Vite + TypeScript application inside `apps/web`.
4. Configure Tailwind CSS and the approved UI component system.
5. Follow the approved design tokens and conventions documented in `docs/architecture/APPLICATION_ARCHITECTURE.md`.
6. Identify unresolved frontend technology decisions, such as:
   - Routing.
   - API data fetching.
   - Form handling and validation.
   - Client-side state management.
   - Theme behavior (light/dark mode).
7. Explain meaningful alternatives and their trade-offs when a decision is required.
8. Obtain approval before introducing additional libraries or dependencies.
9. Add appropriate project scripts for:
   - Type checking.
   - Linting.
   - Testing.
10. Configure backend CORS when the frontend and backend use different origins.
11. Verify that the frontend can communicate with the backend through the documented API.

### Absolute Constraints

- Do not use Next.js or assume server-side rendering.
- Do not move business logic into the frontend.
- Do not introduce npm packages or global UI libraries without approval.
- Do not assume a router, form library, or state management solution before it has been selected.
- Do not duplicate backend business validation in the frontend as an authoritative rule.
- Do not claim the frontend is ready before its required verification checks pass.
- If a required architecture or library decision is unresolved, explain the alternatives and request approval.

---

## SKILL 2: Building a Page or Component

### Context & Goal

**Purpose:** Build a consistent, accessible, maintainable frontend that presents ERP data and sends user actions to the backend.

**Target:**
- `apps/web/` pages.
- Components.
- Hooks.
- API client.
- Frontend tests.

### Step-by-Step Execution

1. Read the relevant API contract, feature specification, user story, and acceptance criteria.
2. Identify the user's role and the actions permitted by the backend.
3. Plan the page or component structure before implementation.
4. Use the approved UI component system and Tailwind conventions.
5. Follow semantic design tokens, such as:
   - `bg-background`
   - `text-foreground`
   - `bg-primary`
   - `text-muted-foreground`
   - `border-border`
6. Do not introduce arbitrary colors for business statuses without an approved design decision.
7. Use the shared API client or approved API integration pattern.
8. Handle asynchronous API operations and display understandable loading, success, empty, and error states where applicable.
9. Keep components focused. Extract reusable or stateful logic when it improves maintainability.
10. Perform client-side validation for immediate user feedback.
11. Treat backend validation and authorization as authoritative.
12. Verify responsive behavior at narrow and wide viewport sizes.
13. Test important interactions and compare the result with acceptance criteria.

### Absolute Constraints

- Do not implement business rules in React components or hooks.
- Do not treat hidden or disabled UI controls as authorization enforcement.
- Do not bypass backend permission checks.
- Do not invent domain status colors or visual meanings.
- Do not assume every component must use shadcn/ui when an appropriate accessible implementation is unavailable.
- Avoid unnecessary inline styles and raw CSS when the approved styling system can handle the requirement.
- Do not hardcode API response data as a substitute for actual backend integration.
- Always handle API errors without exposing internal backend details.
- Let the learner implement the page or component when the task is a learning exercise; review with hints.

### Verification

Run the scripts that actually exist in `apps/web/package.json`.

Expected checks may include:

- `npm run type-check`
- `npm run lint`
- `npm run test`
- `npm run build`

Do not assume a script exists without checking the project configuration.

---

## General Frontend Engineer Principles

- The frontend is an API client, not a second business authority.
- The Go backend owns business rules, validation, authorization, and persistent data.
- Follow documented API contracts.
- Keep API integration separate from presentation where appropriate.
- Respect role-based access while relying on backend authorization for enforcement.
- Handle loading, empty, error, and success states.
- Use accessible and responsive UI patterns.
- Avoid unnecessary dependencies and abstractions.
- Coordinate with `backend-engineer`, `business-analyst`, `system-architect`, `code-reviewer`, and `mentor` when appropriate.

The objective is to build a usable and maintainable ERP client without duplicating the backend's responsibilities.