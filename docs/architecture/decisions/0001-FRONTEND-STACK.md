# 0001: Frontend stack change

Status: Accepted (formerly CR-001)

- **CURRENT:** Next.js + TypeScript + App Router + Tailwind CSS
- **NEW:** React + Vite + TypeScript + shadcn/ui
- **CHANGE:** initial web client technology only
- **IMPACT:** Sprint 21 for frontend implementation and Sprint 22 for deployment.
- **AFFECTED:** `apps/web/` setup; client-side routing and API client; static SPA build and serving strategy. CORS configuration depends on frontend and API origins.
- **UNAFFECTED:** Go ERP Core, REST API contract, database, business rules, Sprints 0-20, headless architecture, monorepo
- **DEPRECATED:** Next.js, App Router, SSR assumptions
- **UNKNOWN:** router, data-fetching, form and state libraries (decide in Sprint 21)

---

## Context

The initial frontend stack was Next.js.
The project requires a client application that consumes
the Go ERP Core through its REST API.

The frontend does not require server-side rendering
or Next.js-specific server capabilities.

## Decision

Use React + Vite + TypeScript with shadcn/ui
on Tailwind CSS for the initial web client.

The frontend is a static Single Page Application (SPA)
that communicates directly with the Go ERP Core REST API.

## Consequences

- Frontend builds produce static assets.
- Frontend deployment does not require a Node.js SSR server.
- Client-side routing must be configured separately.
- API communication must handle authentication and errors.
- CORS configuration depends on whether frontend and API
  are served from different origins.
- Frontend routing, data-fetching, form, and state
  management libraries remain undecided.