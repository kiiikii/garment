# System Architecture

## Headless ERP

The Go application is the **ERP Core**. It owns business logic, business rules, workflows, validation, authorization, persistence, notification processing, and REST API implementation.

The REST API contracts define the communication boundary between the ERP Core and its clients.

Clients consume the ERP Core through the REST API.

```
                GO ERP CORE
                     |
                 REST API
                     |
          +----------+----------+
          v          v          v
     React SPA     Mobile      Other
      (Vite)       (future)    clients
```

- The ERP Core MUST NOT depend on any client.
- The frontend is a client; the ERP Core is the business authority.
- Headless = how the architecture is separated. Monorepo = how source code is organized.

## Style

Modular Monolith. No microservices unless a real requirement justifies them.

## Technology stack

| Area              | Choice                                                                                                |
| ----------------- | ----------------------------------------------------------------------------------------------------- |
| Backend           | Go, REST API                                                                                          |
| Database          | PostgreSQL, SQL migrations                                                                            |
| Frontend          | React, Vite, TypeScript, shadcn/ui on Tailwind CSS (introduced progressively starting from Sprint 21) |
| Infrastructure    | Docker, Docker Compose                                                                                |
| Deployment Target | Ubuntu VPS, Nginx, HTTPS, CI/CD (planned for later deployment phase)                                  |
| Tools             | Git, GitHub, VS Code, Postman / Bruno, PostgreSQL client, Makefile                                    |
| Development Host  | Fedora Linux (application code stays OS-independent)                                                  |

## Monorepo Layout

The repository uses a monorepo structure.

The initial application layout is:

```
garment-erp/
  apps/
    api/   (Go ERP Core)
    web/   (React + Vite, added in Sprint 21)
  docs/
  docker/
  docker-compose.yml
  Makefile
  README.md
  .gitignore
```
The `docker/` directory contains Docker-related configuration and supporting resources. Application source code remains inside `apps/`.

## Persistent Business State

The backend must not rely on in-memory application state as the authoritative source of business data.

PostgreSQL is the authoritative source of truth for persistent business data.

Application instances must be able to process requests independently without relying on request history stored in a specific application instance.

Temporary in-memory state may be used for non-authoritative purposes when justified.

## Decision records

Architectural decisions and their rationale are maintained as Architecture Decision Records (ADRs) in `decisions/`.

Each ADR records the context, decision, and consequences of an architectural choice.
