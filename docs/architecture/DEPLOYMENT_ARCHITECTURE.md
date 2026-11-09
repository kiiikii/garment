# Deployment Architecture

**Product:** Garment ERP Core
**Status:** STUB — PLANNED FOR SPRINT 22
**Architecture:** Headless Modular Monolith

This document defines the planned deployment direction for Garment ERP Core.

Production deployment and advanced DevOps implementationmust not be introduced before Sprint 22 unless an earlier sprint has an explicit requirement.

Detailed configuration and implementation decisions will be documented during the deployment sprint.

---

## 1. Deployment Overview

The application consists of:

- Laravel ERP Core backend.
- React + Vite frontend.
- PostgreSQL database.
- Nginx reverse proxy and static file server.

The backend and frontend are deployed as separate application components.

The frontend communicates with the backend through REST API.

The frontend must not access PostgreSQL directly.

### 1.1 Planned Deployment Topology

    USERS
      |
      v
    HTTPS
      |
      v
    NGINX
      |
      +--------------------------+
      |                          |
      v                          v
    REACT SPA                 API REQUESTS
    STATIC FILES                  |
                                 v
                         LARAVEL ERP CORE
                              / PHP
                                 |
                                 v
                             POSTGRESQL

This is the intended logical topology.

The exact container arrangement, network configuration, and PHP application runtime remain subject to deployment implementation.

---

## 2. Planned Components

| Component         | Purpose                                  | Status    |
| ----------------- | ---------------------------------------- | --------- |
| Laravel API       | Backend ERP Core                         | CONFIRMED |
| PHP Runtime       | Executes Laravel application             | REQUIRED  |
| React Build       | Static frontend application              | CONFIRMED |
| Dockerfile        | Builds application container images      | PLANNED   |
| Docker Compose    | Coordinates required services            | PLANNED   |
| PostgreSQL        | Persistent database                      | CONFIRMED |
| PostgreSQL Volume | Preserves database data                  | PLANNED   |
| Nginx             | Serves React SPA and reverse-proxies API | PLANNED   |
| HTTPS             | Secures external communication           | PLANNED   |
| Ubuntu VPS        | Planned production hosting               | PLANNED   |
| CI/CD             | Automated build and deployment           | PLANNED   |

The exact PHP runtime and web server integration must be selected during deployment implementation.

Do not assume a specific PHP process manager or container topology before the deployment design is approved.

---

## 3. Laravel Backend Deployment

The Laravel backend resides in:

`apps/api/`

The backend deployment must provide:

- Compatible PHP runtime.
- Required PHP extensions.
- Composer-managed dependencies.
- Application configuration.
- Database connectivity.
- Writable Laravel runtime directories where required.
- Secure environment configuration.

The exact PHP version, extension list, and runtime configuration remain technical decisions for the deployment sprint.

### 3.1 Backend Configuration

Production configuration must be separate from development configuration.

Configuration must be provided through environment variables or approved configuration mechanisms.

Secrets must never be committed to Git.

Production debugging information must not be exposed to API clients.

### 3.2 Laravel Runtime

The Laravel application must be deployed using an appropriate production runtime.

The exact PHP application serving strategy remains UNDECIDED.

Do not introduce a Node.js SSR server for the backend.

---

## 4. React Frontend Deployment

The frontend resides in:

`apps/web/`

The React application is deployed as a static SPA build.

The planned deployment process:

1. Install frontend dependencies.
2. Build the production frontend.
3. Generate static build assets.
4. Serve the generated assets through Nginx.
5. Configure the frontend API origin.
6. Verify frontend-to-backend communication.

The frontend must not require a Next.js or Node.js SSR server.

The exact frontend build configuration and environment variable strategy will be finalized during deployment implementation.

---

## 5. PostgreSQL Deployment

PostgreSQL is the authoritative persistent database.

Database Name:

`garment_erp`

The production database must use persistent storage.

Database data must survive container recreation.

### 5.1 Database Requirements

- Persistent volume configuration.
- Restricted database access.
- Environment-based credentials.
- Backup strategy.
- Recovery strategy.
- Controlled schema migrations.
- Database integrity.
- Production and development separation.

The exact PostgreSQL version, container configuration, and resource allocation remain UNDECIDED.

### 5.2 Database Backup and Recovery

Backup and recovery requirements must be defined before production use.

The deployment design must document:

- Backup method.
- Backup frequency.
- Backup retention.
- Backup storage location.
- Recovery procedure.
- Recovery verification.

Do not treat a persistent Docker volume as a replacement for database backups.

---

## 6. Docker and Docker Compose

Docker and Docker Compose are planned for deployment implementation.

Expected responsibilities:

**Dockerfile**

- Define application container build instructions.
- Provide the required runtime environment.
- Avoid embedding secrets.

**Docker Compose**

- Coordinate required application services.
- Define service configuration.
- Configure persistent database storage.
- Configure service communication.
- Separate environment-specific configuration.

The final service definitions remain subject to deployment architecture review.

Do not assume that every planned component must run in a separate container.

---

## 7. Nginx and Request Routing

Nginx is planned to serve two primary purposes:

1. Serve the compiled React SPA static files.
2. Reverse-proxy API requests to Laravel ERP Core.

The exact routing configuration, domain structure, and upstream configuration will be defined during Sprint 22.

The deployment must distinguish:

- Frontend static asset requests.
- API requests.
- Infrastructure health checks.

The SPA fallback configuration must not interfere with API routing.

---

## 8. Network and CORS

CORS configuration depends on the deployed frontend and backend origins.

If the frontend and API are served from the same origin, browser CORS configuration may not be necessary.

If they use different origins, the API must explicitly allow the approved frontend origins.

CORS must not be treated as authentication or authorization.

Database access must remain restricted to approved application infrastructure.

The exact network topology remains UNDECIDED.

---

## 9. Environment Configuration

Development and production environments must remain separate.

Application configuration must not be hard-coded.

Secrets must never be stored in source code or committed to Git.

Production configuration must include the required Laravel, frontend, and database settings.

Configuration management details remain subject to the deployment implementation plan.

---

## 10. Development Environment

- Development operating system: Fedora Linux.
- IDE: Visual Studio Code.
- Backend: Laravel / PHP.
- Frontend: React + Vite + TypeScript.
- Database: PostgreSQL.

Application code must remain independent of the development operating system.

Production infrastructure must not introduce development-only dependencies.

---

## 11. CI/CD

CI/CD is planned for a later deployment implementation stage.

The pipeline may cover:

- Code checkout.
- Dependency installation.
- Backend tests.
- Frontend verification.
- Build process.
- Deployment preparation.
- Deployment execution.

The exact CI/CD provider, pipeline stages, deployment approval process, and rollback mechanism remain UNDECIDED.

Do not assume a specific CI/CD platform.

---

## 12. Logging and Monitoring

Production logging and monitoring must be designed before production use.

The deployment plan must consider:

- Laravel application logs.
- Web server logs.
- Database logs.
- Application health checks.
- Error visibility.
- Resource monitoring.

The logging and monitoring tools remain UNDECIDED.

Sensitive information must not be written to logs.

---

## 13. Deferred Decisions

| Decision                             | Status    |
| ------------------------------------ | --------- |
| PHP version                          | UNDECIDED |
| Required PHP extensions              | UNDECIDED |
| Laravel runtime serving strategy     | UNDECIDED |
| Docker image build strategy          | UNDECIDED |
| Docker Compose service definitions   | UNDECIDED |
| PostgreSQL version                   | UNDECIDED |
| Production environment configuration | UNDECIDED |
| Domain and routing configuration     | UNDECIDED |
| HTTPS certificate management         | UNDECIDED |
| PostgreSQL backup and recovery       | UNDECIDED |
| Production logging and monitoring    | UNDECIDED |
| CI/CD provider and pipeline          | UNDECIDED |
| VPS sizing and resource allocation   | UNDECIDED |
| Queue worker deployment, if required | UNDECIDED |
| Scheduler deployment, if required    | UNDECIDED |
| Deployment rollback strategy         | UNDECIDED |

Do not convert these decisions into production implementation assumptions.

---

## 14. Deployment Governance

1. Production deployment follows the approved architecture.
2. Development and production environments remain separate.
3. Secrets must never be committed to Git.
4. PostgreSQL data must use persistent storage.
5. Database backup and recovery must be defined.
6. Laravel must run using an appropriate production runtime.
7. React must be deployed as a static SPA build.
8. Nginx is the planned static server and reverse proxy.
9. Deployment configuration must remain version-controlled.
10. Production changes must follow an approved deployment process.
11. Deployment architecture must remain consistent with SYSTEM_ARCHITECTURE.md.

---

## 15. Related Documentation

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/api/API_CONTRACTS.md`
- `docs/database/DATABASE_DESIGN.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/TESTING.md`
- `docs/engineering/GIT_WORKFLOW.md`
