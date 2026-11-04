# Deployment Architecture

Status: STUB — PLANNED FOR SPRINT 22.

Production deployment and advanced DevOps implementation
must not be introduced before Sprint 22 unless an earlier
sprint has an explicit requirement.

This document defines the planned deployment direction.
Detailed configuration and implementation decisions will
be documented during the deployment sprint.

## Planned Components (Sprint 22)

| Item | Notes |
|---|---|
| Dockerfile | Container image for the Go API. Frontend build and serving strategy will be defined during deployment implementation. |
| Docker Compose | Orchestrates the API, PostgreSQL, and frontend-related services where required. |
| PostgreSQL Container | Uses a persistent volume to preserve database data across container recreation. |
| Environment Configuration | Uses environment variables. Secrets must never be committed to Git. |
| Production Configuration | Must be separated from development configuration. |
| Nginx | Serves the static React SPA build and acts as a reverse proxy for API requests. |
| HTTPS | TLS certificates are configured on the VPS. |
| VPS | Ubuntu VPS is the planned production hosting environment. |
| CI/CD | Planned for a later deployment implementation stage. |

## Deployment Principles

- Development and production environments must remain separate.
- Application configuration must be provided through environment variables
  or other approved configuration mechanisms.
- Secrets must not be stored in source code or committed to Git.
- PostgreSQL data must persist independently of the database container lifecycle.
- Database backup and recovery requirements must be defined before production use.
- The Go API must remain independent of frontend deployment details.
- The React frontend is deployed as a static SPA build.
- Production deployment must not require a Next.js or Node.js SSR server.

## Nginx and Request Routing

Nginx is planned to serve two purposes:

1. Serve the compiled React SPA static files.
2. Reverse-proxy API requests to the Go ERP Core.

The exact routing configuration, domain structure,
and deployment topology will be defined during Sprint 22.

## Network and CORS

CORS configuration depends on the deployed origins
of the frontend and API.

- If the frontend and API are served from the same origin,
  browser CORS configuration may not be necessary.
- If they use different origins, the API must explicitly
  allow the approved frontend origins.

CORS configuration must not be treated as an authorization
or authentication mechanism.

## Development Environment

- The development host is Fedora Linux.
- Application code and architecture must remain
  independent of the development operating system.
- Development configuration must remain separate
  from production configuration.

## Deferred Decisions

The following decisions remain open until deployment planning:

- Frontend build and serving strategy.
- Docker image build strategy.
- Docker Compose service definitions.
- Production environment configuration.
- Domain and routing configuration.
- HTTPS certificate management.
- PostgreSQL backup and recovery strategy.
- Production logging and monitoring.
- CI/CD provider and pipeline.
- VPS sizing and resource allocation.