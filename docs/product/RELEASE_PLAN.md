# Release Plan (Backend-First Roadmap)

Status: Draft

Current Position: TO BE CONFIRMED

This roadmap defines the proposed implementation sequence for Garment ERP Core Phase 1.

The backend technology is Laravel / PHP.

Sprint assignments remain PROPOSED until formally confirmed.

Sprint assignments may be refined when implementation requirements become clearer. Changes must preserve approved business requirements, workflow dependencies, and system architecture.

---

## Sprint Roadmap

| Sprint | Focus                                                                                                                                                               |
| ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 0      | Laravel backend bootstrap, project structure, environment configuration, application setup, API routing, health check, graceful shutdown considerations, Git commit |
| 1      | HTTP and REST fundamentals, API request/response handling, first business endpoint                                                                                  |
| 2      | PostgreSQL setup, database migrations, Customer / Order persistence foundation                                                                                      |
| 3      | Order domain, validation, and business logic                                                                                                                        |
| 4      | Order size quantities                                                                                                                                               |
| 5      | Product reference images and image storage integration                                                                                                              |
| 6      | Backend architecture refinement, Laravel application boundaries, request validation, dependency injection, logging, and maintainable service boundaries             |
| 7      | Sampling                                                                                                                                                            |
| 8      | Sample / Look Approval                                                                                                                                              |
| 9      | Letter of Agreement (LoA), payment recording, payment correction workflow, and Order Change Request                                                                 |
| 10     | Production Readiness and Waiting / On Hold                                                                                                                          |
| 11     | PPIC Preparation and Functional Layout                                                                                                                              |
| 12     | Mass Production, QC, Rework, Finishing, Packing, Delivery / Customer Pickup, and Production Issue Reports                                                           |
| 13     | Production monitoring, planned vs actual timelines, progress, variance, and delay visibility                                                                        |
| 14     | In-App Notifications, Delay Report email integration, and notification rules                                                                                        |
| 15     | Authentication                                                                                                                                                      |
| 16     | Role-Based Access Control (RBAC)                                                                                                                                    |
| 17     | Audit logging                                                                                                                                                       |
| 18     | Backend testing hardening, regression tests, integration tests, and workflow verification                                                                           |
| 19     | Security hardening, security review, CORS configuration and verification, rate limiting, secret management, and authentication security                             |
| 20     | Observability refinement, structured logs, operational diagnostics, and monitoring where applicable                                                                 |
| 21     | Frontend integration (React + Vite + TypeScript; shadcn/ui remains PROPOSED)                                                                                        |
| 22     | Deployment and production environment setup                                                                                                                         |
| 23     | Incident simulation and operational verification                                                                                                                    |

Sprint mapping is a proposed implementation sequence, not an approval of unresolved business requirements.

---

## 1. Implementation Rules

- Follow the approved sprint order and technical dependencies.
- Do not pull later feature implementation forward without a documented reason.
- Foundational quality and security practices must be applied from the beginning.
- Testing must be added alongside implementation of each feature.
- Sprint 18 is dedicated to backend testing hardening, not the beginning of testing.
- Sprint 19 is dedicated to security hardening, not the beginning of security implementation.
- Frontend integration begins after the ERP Core has sufficient API and workflow stability.
- Each meaningful milestone produces a meaningful Git commit.
- Do not build the complete database schema upfront.
- Grow the database schema incrementally as features are implemented.
- Update relevant documentation when approved requirements or implementation decisions change.
- Do not implement PROPOSED, UNKNOWN, or UNDECIDED requirements as confirmed business rules.
- Do not introduce unnecessary backend abstractions or architectural complexity.
- Maintain consistency with the approved Laravel modular monolith architecture.

---

## 2. Backend Implementation Direction

The backend uses Laravel / PHP as the confirmed application technology.

Laravel ERP Core is responsible for:

- Business logic and validation.
- Workflow and business-rule enforcement.
- Authentication and authorization.
- Database persistence.
- Audit history.
- Notification triggers.
- API response handling.

Backend implementation must follow the approved system architecture.

Technical implementation decisions must not silently introduce new business requirements.

### 2.1 Database Implementation

PostgreSQL is the confirmed database engine.

Database implementation must:

- Grow incrementally with feature development.
- Use version-controlled database migrations.
- Preserve existing business data when schema changes are introduced.
- Maintain referential integrity.
- Follow approved entity ownership and relationships.
- Use parameterized database operations.
- Avoid speculative tables and columns.

The database access approach and detailed persistence strategy must follow approved engineering decisions.

Do not assume an ORM, query builder, or custom database abstraction without evaluating it against the project architecture.

### 2.2 Backend Quality

Throughout backend implementation:

- Follow consistent PHP coding standards.
- Apply Laravel conventions where appropriate.
- Use static analysis where applicable.
- Validate incoming requests.
- Enforce authorization on protected business operations.
- Handle database transactions where required.
- Prevent unauthorized workflow transitions.
- Maintain consistent API error handling.
- Keep sensitive configuration outside source control.

---

## 3. Frontend Integration

The frontend is introduced after the backend has sufficient stability.

Frontend integration requires:

- Stable API contracts for implemented features.
- Verified backend business rules and validation.
- Authentication and authorization integration.
- CORS configuration appropriate for the deployment environment.
- Consistent API error handling.
- Verified workflow transitions.
- Role-based access integration.

The frontend remains an API client and must not replace backend business logic or authorization.

Confirmed frontend stack:

- React.
- Vite.
- TypeScript.
- Tailwind CSS.

shadcn/ui remains PROPOSED until formally confirmed.

---

## 4. Quality and Security Across Sprints

The following practices apply throughout implementation:

- Run relevant tests when implementing or modifying features.
- Apply consistent PHP formatting and static analysis.
- Use parameterized database operations.
- Keep secrets out of source control.
- Enforce backend validation and authorization.
- Avoid exposing internal implementation details through API errors.
- Record relevant business actions according to approved audit requirements.
- Review regressions when modifying existing behavior.
- Preserve historical business records where required.
- Validate business workflow transitions.
- Apply appropriate transaction handling.
- Review data integrity when modifying database structures.

Security hardening in Sprint 19 does not mean security implementation starts at Sprint 19.

Security practices must be applied from the beginning.

---

## 5. Feature Dependencies

The implementation sequence must preserve the following major dependencies:

1. Order Intake must be available before order-dependent workflows.
2. Sampling precedes Sample / Look Approval.
3. LoA approval is required before Production Readiness can be satisfied.
4. Production Readiness must be satisfied before PPIC Preparation.
5. PPIC Preparation leads to Functional Layout preparation.
6. Functional Layout preparation precedes Mass Production release.
7. Mass Production begins when Cutting actually starts.
8. QC and Rework follow the confirmed production workflow.
9. Fulfillment conditions must be satisfied before an order becomes COMPLETED.
10. Payment correction approval follows the authorized Owner approval process.
11. Order Change Request approval must preserve previous order specifications and trigger applicable revalidation.
12. Production Issue Reports must follow the approved reporting and notification workflow.
13. Authentication and RBAC must protect all applicable business operations.
14. Audit records must follow approved audit requirements.

Detailed business rules remain governed by the business documentation.

---

## 6. Out of Scope for Phase 1

The following remain outside Phase 1 scope:

- Inventory management.
- Stock Movement.
- Stock Opname.
- Stock Variance.
- Material Consumption.
- HPP and Costing.
- Leftover Goods.
- Bonus Goods.
- Full Procurement.
- Fashion Retail.
- Mobile client.
- Microservices architecture.
- Full accounting.
- Financial reconciliation.

---

## 7. Roadmap Governance

This roadmap is a proposed implementation plan.

Sprint assignments may change when:

- Technical dependencies require adjustment.
- Approved business requirements change.
- Architecture decisions are updated.
- Implementation reveals necessary sequencing changes.

Any roadmap change must preserve:

- Approved PRD scope.
- Business Rules.
- Business Workflow.
- User Roles and Permissions.
- System Architecture.
- API Contracts.
- Database Design.

Do not use roadmap sequencing to resolve an UNKNOWN or UNDECIDED business requirement.

The current sprint position must be updated based on actual implementation progress, not assumed from the previous Go-based roadmap.
