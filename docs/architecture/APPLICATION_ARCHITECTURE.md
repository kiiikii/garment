# Application Architecture

## Backend request flow (introduced progressively)

```
HTTP Request
     |
     v
HTTP Server
     |
     v
Middleware Chain
     |
     v
Router
     |
     v
Handler
     |
     v
Service / Use Case
     |
     v
Repository Interface
     |
     v
Repository Implementation
     |
     v
PostgreSQL
     |
     v
HTTP Response
```

Middleware wraps the HTTP request handling process and may execute before and after the handler chain.

The repository interface defines the persistence contract used by the application layer. Its implementation handles database access.

Do not require every layer before the first endpoint. Introduce each layer when a sprint needs it.

| Layer                     | Responsibility                                                               |
| ------------------------- | ---------------------------------------------------------------------------- |
| Router                    | Maps HTTP methods and paths to handlers                                      |
| Middleware                | Handles cross-cutting HTTP concerns                                          |
| Handler                   | Parses HTTP input, invokes application operations, and writes HTTP responses |
| Service / Use Case        | Coordinates business operations and enforces business rules                  |
| Repository Interface      | Defines persistence contracts required by application operations             |
| Repository Implementation | Executes database queries and persistence operations                         |
| PostgreSQL                | Stores persistent business data                                              |

Handlers do not access PostgreSQL directly. Business rules live in the Service, never in the frontend.

### Layer Boundaries

- Router must not contain business logic.
- Middleware must not implement domain-specific workflows.
- Handler must not contain business rules or access PostgreSQL directly.
- Service / Use Case owns business operations and workflow execution.
- Repository owns database queries and persistence operations.
- Frontend must not be treated as an authoritative source for business validation or workflow decisions.
- Domain and application logic must not depend on HTTP-specific or database-driver-specific implementation details.
- Infrastructure implementations must satisfy the interfaces required by the application layer.

Business rules must be enforced by the backend regardless of the client initiating the request.

## Backend layout (grown progressively)

Entry point under `apps/api/cmd/`, business code under `apps/api/internal/`. Exact package structure is decided in Sprint 0.5 and Sprint 6.

Internal packages must remain independent of frontend implementation details.

Package boundaries should follow business capabilities and dependency responsibilities rather than creating a separate package for every database table or API endpoint.

## Frontend (Sprint 21+)

- React + Vite + TypeScript + shadcn/ui on Tailwind CSS.
- Static SPA build; The browser communicates with the backend through the REST API. Cross-Origin Resource Sharing (CORS) must be configured according to the actual frontend and backend origins.
- Suggested folders: `pages/`, `components/`, `hooks/`, `lib/` (API client).
- UNDECIDED: router, data-fetching, forms, state management, light/dark default and toggle.
- Frontend does UX validation only; the backend is authoritative.

### Frontend Responsibilities

The frontend is responsible for:

- User interface and user interaction.
- Client-side navigation.
- Form input and UX validation.
- API communication.
- Displaying business states returned by the backend.
- Presenting errors and operation results.

The frontend API client is responsible for:
- Centralizing API communication.
- Handling HTTP responses and network failures.
- Applying the configured authentication mechanism.
- Providing consistent request and response handling.

The frontend must not independently determine:

- Workflow transitions.
- Approval decisions.
- Production readiness.
- Production progress calculations.
- Authorization decisions.
- Business-critical status changes.

These decisions must be provided or enforced by the ERP Core.

## Theme tokens (shadcn/ui, decision CR-002)

Lime primary, olive-tinted neutrals, emerald chart ramp, radius `0.45rem`. Use semantic tokens, never hard-coded colors. Domain-specific status color mappings are UNDECIDED.

Visual status tokens must not introduce or define new business statuses.

```css
:root {
	--background: oklch(1 0 0);
	--foreground: oklch(0.153 0.006 107.1);
	--card: oklch(1 0 0);
	--card-foreground: oklch(0.153 0.006 107.1);
	--popover: oklch(1 0 0);
	--popover-foreground: oklch(0.153 0.006 107.1);
	--primary: oklch(0.841 0.238 128.85);
	--primary-foreground: oklch(0.405 0.101 131.063);
	--secondary: oklch(0.967 0.001 286.375);
	--secondary-foreground: oklch(0.21 0.006 285.885);
	--muted: oklch(0.966 0.005 106.5);
	--muted-foreground: oklch(0.58 0.031 107.3);
	--accent: oklch(0.966 0.005 106.5);
	--accent-foreground: oklch(0.228 0.013 107.4);
	--destructive: oklch(0.577 0.245 27.325);
	--border: oklch(0.93 0.007 106.5);
	--input: oklch(0.93 0.007 106.5);
	--ring: oklch(0.737 0.021 106.9);
	--chart-1: oklch(0.845 0.143 164.978);
	--chart-2: oklch(0.696 0.17 162.48);
	--chart-3: oklch(0.596 0.145 163.225);
	--chart-4: oklch(0.508 0.118 165.612);
	--chart-5: oklch(0.432 0.095 166.913);
	--radius: 0.45rem;
	--sidebar: oklch(0.988 0.003 106.5);
	--sidebar-foreground: oklch(0.153 0.006 107.1);
	--sidebar-primary: oklch(0.648 0.2 131.684);
	--sidebar-primary-foreground: oklch(0.986 0.031 120.757);
	--sidebar-accent: oklch(0.966 0.005 106.5);
	--sidebar-accent-foreground: oklch(0.228 0.013 107.4);
	--sidebar-border: oklch(0.93 0.007 106.5);
	--sidebar-ring: oklch(0.737 0.021 106.9);
}

.dark {
	--background: oklch(0.153 0.006 107.1);
	--foreground: oklch(0.988 0.003 106.5);
	--card: oklch(0.228 0.013 107.4);
	--card-foreground: oklch(0.988 0.003 106.5);
	--popover: oklch(0.228 0.013 107.4);
	--popover-foreground: oklch(0.988 0.003 106.5);
	--primary: oklch(0.768 0.233 130.85);
	--primary-foreground: oklch(0.405 0.101 131.063);
	--secondary: oklch(0.274 0.006 286.033);
	--secondary-foreground: oklch(0.985 0 0);
	--muted: oklch(0.286 0.016 107.4);
	--muted-foreground: oklch(0.737 0.021 106.9);
	--accent: oklch(0.286 0.016 107.4);
	--accent-foreground: oklch(0.988 0.003 106.5);
	--destructive: oklch(0.704 0.191 22.216);
	--border: oklch(1 0 0 / 10%);
	--input: oklch(1 0 0 / 15%);
	--ring: oklch(0.58 0.031 107.3);
	--chart-1: oklch(0.845 0.143 164.978);
	--chart-2: oklch(0.696 0.17 162.48);
	--chart-3: oklch(0.596 0.145 163.225);
	--chart-4: oklch(0.508 0.118 165.612);
	--chart-5: oklch(0.432 0.095 166.913);
	--sidebar: oklch(0.228 0.013 107.4);
	--sidebar-foreground: oklch(0.988 0.003 106.5);
	--sidebar-primary: oklch(0.768 0.233 130.85);
	--sidebar-primary-foreground: oklch(0.274 0.072 132.109);
	--sidebar-accent: oklch(0.286 0.016 107.4);
	--sidebar-accent-foreground: oklch(0.988 0.003 106.5);
	--sidebar-border: oklch(1 0 0 / 10%);
	--sidebar-ring: oklch(0.58 0.031 107.3);
}
```
