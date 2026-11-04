# Release Plan (Backend-First Roadmap)

Status: Draft

Current position: Sprint 0, Challenge 0.1
(Go module, API application, HTTP server, basic router, and health endpoint).

This roadmap defines the proposed implementation sequence for Phase 1.

Sprint assignments may be refined when implementation requirements become
clearer. Changes must preserve the approved business workflow and
technical dependencies.

| Sprint | Focus                                                                                                                             |
| ------ | --------------------------------------------------------------------------------------------------------------------------------- |
| 0      | Backend bootstrap (Go module, project structure, configuration, HTTP server, router, health check, graceful shutdown, Git commit) |
| 1      | HTTP and REST fundamentals, first business endpoint                                                                               |
| 2      | PostgreSQL setup, migrations, Customer / Order tables, repository foundation                                                      |
| 3      | Order domain and business logic                                                                                                   |
| 4      | Order size quantities                                                                                                             |
| 5      | Product reference images and image storage integration                                                                            |
| 6      | Backend architecture refinement (DTO, dependency injection, logging, package boundaries)                                          |
| 7      | Sampling                                                                                                                          |
| 8      | Sample / Look approval                                                                                                            |
| 9      | Letter of Agreement (LoA)                                                                                                         |
| 10     | Production readiness and waiting                                                                                                  |
| 11     | PPIC preparation and functional layout                                                                                            |
| 12     | Mass production and fulfillment workflow (Cutting, Sewing, QC, Rework, Finishing, Packing, Delivery / Customer Pickup)            |
| 13     | Production monitoring (planned vs actual, progress, variance, delay visibility)                                                   |
| 14     | Notifications and notification rules                                                                                              |
| 15     | Authentication                                                                                                                    |
| 16     | Role-based access control (RBAC)                                                                                                  |
| 17     | Audit logging                                                                                                                     |
| 18     | Backend testing hardening (regression tests, integration tests, workflow verification)                                            |
| 19     | Security hardening (security review, CORS configuration and verification, rate limiting, secret management, token security)       |
| 20     | Observability refinement (structured logs, operational diagnostics, monitoring where applicable)                                  |
| 21     | Frontend integration (React + Vite + TypeScript; shadcn/ui remains PROPOSED)                                                      |
| 22     | Deployment and production environment setup                                                                                       |
| 23     | Incident simulation and operational verification                                                                                  |

## 1. Implementation Rules

- Follow the approved sprint order and technical dependencies.
- Do not pull later feature implementation forward without a documented reason.
- Foundational quality and security practices must be applied from the beginning.
- Testing must be added alongside the implementation of each feature.
- Sprint 18 is dedicated to backend testing hardening, not the beginning of testing.
- Sprint 19 is dedicated to security hardening, not the beginning of security implementation.
- Frontend integration begins after the ERP Core has sufficient API and workflow stability.
- Each meaningful milestone produces a meaningful Git commit.
- Do not build the complete database schema upfront.
- Grow the database schema incrementally as features are implemented.
- Update relevant documentation when approved requirements or implementation decisions change.

## 2. Frontend Integration

The frontend is introduced after the backend has sufficient stability.

Frontend integration requires:

- Stable API contracts for the implemented features.
- Verified backend business rules and validation.
- Authentication and authorization integration.
- CORS configuration appropriate for the deployment environment.
- Consistent API error handling.

The frontend remains an API client and must not replace backend business logic or authorization.

## 3. Quality and Security Across Sprints

The following practices apply throughout implementation:

- Run relevant tests when implementing or modifying features.
- Apply Go formatting and static analysis.
- Use parameterized database queries.
- Keep secrets out of source control.
- Enforce backend validation and authorization.
- Avoid exposing internal implementation details through API errors.
- Record relevant business actions according to approved audit requirements.
- Review regressions when modifying existing behavior.

## 4. Out of Scope for Phase 1

- Inventory management
- Stock Movement
- Stock Opname
- Stock Variance
- Material Consumption
- HPP and Costing
- Leftover Goods
- Bonus Goods
- Full Procurement
- Fashion Retail
- Mobile client
- Microservices architecture
