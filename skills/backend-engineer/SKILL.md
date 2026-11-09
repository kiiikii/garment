---
name: backend-engineer
description: Use when teaching, implementing, or reviewing Laravel/PHP backend work in this project, including REST API development, routing, middleware, controllers, request validation, application services, domain logic, database persistence, transactions, configuration, error handling, and backend testing.
---

# Agent Skills & Operational Playbook: Backend Engineer

The learner is developing backend engineering skills using PHP and Laravel.

Act as a senior backend engineer. Teach concepts progressively and guide the learner through implementation.

The objective is to build a reliable, maintainable, testable, and secure Laravel ERP Core while developing the learner's ability to understand and independently implement backend systems.

Combine this skill with `mentor` when the task involves learning challenges, guided implementation, or reviewing learning submissions.

Follow:

- `AGENTS.md`
- `AI_CONTEXT.md`
- Approved system architecture.
- Approved engineering documentation.

Global project instructions take precedence over this skill.

---

# Project Backend Context

## Confirmed Technology

- Language: PHP.
- Framework: Laravel.
- Architecture: Modular Monolith.
- API Style: REST API.
- Database: PostgreSQL.
- Frontend: React + Vite + TypeScript.
- Backend responsibility: Authoritative business logic, validation, authorization, and persistent data operations.

The backend must remain independent of frontend clients.

## Implementation Principles

- Follow the actual Laravel and PHP versions declared by the project.
- Inspect `composer.json` before assuming installed dependencies or available scripts.
- Follow the actual application structure instead of imposing a generic directory structure.
- Use Laravel conventions where appropriate.
- Keep business logic authoritative in the backend.
- Keep business rules separate from HTTP transport concerns.
- Introduce abstractions only when they solve an actual architectural problem.
- Follow the approved API contracts.
- Use PostgreSQL as the authoritative relational database.
- Preserve business history and data integrity according to approved requirements.

Do not assume that every Laravel application requires a Repository Pattern, Action Pattern, Service Layer, or additional architectural package.

The architecture must remain consistent with the approved Modular Monolith design.

---

# SKILL 1: Creating a New REST API Endpoint

## Context & Goal

**Purpose:**

Implement REST API endpoints that follow approved API contracts, business rules, user permissions, and architectural boundaries.

**Target:**

- Actual Laravel application structure.
- API routes.
- Controllers.
- Middleware.
- Form Requests.
- Application services or use cases where required.
- Domain logic.
- Database persistence.
- API documentation.

Primary reference:

`docs/api/API_CONTRACTS.md`

## Step-by-Step Execution

### Step 1: Inspect the Current Project

Before implementation:

1. Inspect the actual repository structure.
2. Identify the current Laravel version.
3. Inspect `composer.json`.
4. Identify the current application architecture.
5. Inspect existing routes and controllers.
6. Review existing validation and authorization patterns.
7. Identify existing services, domain components, and persistence implementations.
8. Check the current release position in `docs/product/RELEASE_PLAN.md`.

Do not assume that the project uses a specific directory structure or additional Laravel packages.

### Step 2: Confirm Business Requirements

Before creating an endpoint, inspect the relevant:

- Business rules.
- Business workflow.
- Feature specification.
- Acceptance criteria.
- User stories.
- User roles and permissions.
- Glossary.
- API contracts.

Confirm:

1. The feature belongs to the approved Phase 1 scope.
2. The responsible user role is known.
3. The expected business workflow is documented.
4. The required business rules are understood.
5. The endpoint does not bypass an approval or authorization requirement.
6. The required behavior is not marked UNKNOWN or UNDECIDED.

If an implementation-critical requirement is unresolved, investigate existing documentation and request clarification when necessary.

Do not invent missing business rules.

### Step 3: Define or Verify the API Contract

Establish the API contract before implementation.

Where applicable, document:

- HTTP method.
- Endpoint path.
- Path parameters.
- Query parameters.
- Request body.
- Required fields.
- Optional fields.
- Validation rules.
- Success response.
- Error responses.
- Authentication requirements.
- Authorization requirements.
- Business workflow restrictions.
- Relevant acceptance criteria.

Follow:

- `docs/api/API_CONTRACTS.md`
- `docs/engineering/ERROR_HANDLING.md`, if available.

Do not independently introduce new response formats or error conventions.

### Step 4: Understand the Laravel Request Lifecycle

Explain how an incoming request travels through Laravel.

Introduce relevant components progressively:

1. HTTP Entry Point.
2. API Route.
3. Middleware.
4. Authentication.
5. Authorization.
6. Form Request Validation.
7. Controller.
8. Application Service / Use Case, when required.
9. Domain Logic, when required.
10. Database Persistence.
11. API Resource / Response Serialization.

Not every endpoint needs every component.

Use the simplest structure that preserves the approved architecture and responsibilities.

### Step 5: Implement the Endpoint

Depending on the endpoint, implementation may include:

**Routing**

- Register the appropriate API route.
- Use consistent route naming.
- Apply the appropriate middleware.
- Follow existing route organization.

**Middleware**

- Enforce request-level concerns.
- Apply authentication and other approved cross-cutting requirements.
- Avoid placing feature-specific business logic in middleware.

**Form Request Validation**

- Validate incoming request data.
- Define appropriate validation rules.
- Separate validation from controller responsibilities when appropriate.
- Keep business authorization requirements consistent with the approved role definitions.

**Controller**

- Receive and coordinate HTTP requests.
- Invoke the appropriate application operation.
- Return the agreed API response.
- Avoid placing extensive business workflows inside controllers.

**Application Service / Use Case**

Introduce this layer when an operation requires meaningful application coordination.

Responsibilities may include:

- Coordinating business operations.
- Invoking domain behavior.
- Coordinating persistence.
- Managing relevant transaction boundaries.
- Returning application results.

**Domain Logic**

- Enforce business invariants.
- Protect valid business state transitions.
- Represent domain-specific decisions.
- Avoid depending on HTTP transport details.

**Database Persistence**

- Use the approved persistence strategy.
- Use Eloquent or Query Builder where appropriate.
- Apply parameterized database operations.
- Preserve database integrity.
- Use transactions when multiple operations must succeed or fail together.

**API Response**

- Follow documented response conventions.
- Use Laravel API Resources where appropriate.
- Avoid exposing internal implementation details.
- Return appropriate HTTP status codes.
- Maintain consistency with the API contract.

### Step 6: Implement Authorization

Authorization must be enforced by the backend.

Use the project's approved authorization approach.

Where appropriate, Laravel mechanisms may include:

- Middleware.
- Gates.
- Policies.
- Explicit authorization checks.

Verify:

- The authenticated user.
- The user's role.
- The user's permission.
- The affected business resource.
- The requested operation.
- The applicable business workflow restrictions.

Frontend visibility is not authorization.

Never assume that hiding a button prevents unauthorized API access.

### Step 7: Implement Error Handling

Follow the approved error handling and API response conventions.

Handle relevant situations such as:

- Invalid input.
- Unauthenticated requests.
- Unauthorized operations.
- Missing resources.
- Invalid workflow transitions.
- Business rule violations.
- Database failures.
- Unexpected application errors.

Rules:

1. Do not expose sensitive internal exceptions to API clients.
2. Preserve useful diagnostic information in server-side logs.
3. Return appropriate HTTP status codes.
4. Keep error responses consistent.
5. Do not silently ignore failures.
6. Do not convert unexpected failures into successful responses.

### Step 8: Verify the Implementation

Inspect the available project scripts and tooling first.

Use applicable Laravel commands, such as:

- `php artisan route:list`
- `php artisan test`
- `php artisan test --filter=TestName`
- `php artisan migrate:status`

Use additional checks only when configured or installed.

Examples:

- `composer validate`
- `composer test`
- `vendor/bin/pint --test`
- `vendor/bin/phpstan analyse`

Do not assume these scripts or tools exist without checking the project configuration.

### Step 9: Test the Endpoint Manually

Use an appropriate HTTP client:

- curl.
- Postman.
- Bruno.

Verify:

- Successful request.
- Invalid request.
- Unauthenticated request.
- Unauthorized request.
- Missing resource.
- Invalid business transition.
- Expected response structure.
- Database persistence.
- Historical data preservation where applicable.

Compare observed behavior with:

- API Contracts.
- Acceptance Criteria.
- Approved Business Rules.

### Step 10: Review and Commit

Before completing the implementation:

1. Review all modified files.
2. Check for unnecessary dependencies.
3. Check authorization enforcement.
4. Check business validation.
5. Check transaction handling.
6. Check error handling.
7. Check test coverage.
8. Confirm no unrelated functionality was changed.
9. Update documentation where required.
10. Create a meaningful Git commit when appropriate.

## Absolute Constraints

- Do not implement endpoints outside the approved scope.
- Do not introduce Laravel packages without approval.
- Do not invent API contracts.
- Do not expose internal implementation errors.
- Do not bypass backend authorization.
- Do not place authoritative business rules in React.
- Do not use raw SQL with unsafe string interpolation.
- Do not silently discard database errors.
- Do not modify historical business records contrary to approved requirements.
- Do not implement unresolved business decisions as confirmed rules.
- Do not introduce unnecessary architectural abstractions.
- Do not assume a Repository Pattern is mandatory for every feature.

---

# SKILL 2: Introducing Application Services and Persistence Boundaries

## Context & Goal

**Purpose:**

Separate HTTP transport, application coordination, business logic, and persistence responsibilities.

The objective is to maintain understandable and testable backend code without introducing unnecessary architectural complexity.

**Target:**

The actual Laravel application structure.

## Step-by-Step Execution

### Step 1: Inspect Existing Architecture

Before introducing a new layer:

1. Inspect existing controllers.
2. Inspect existing application services.
3. Inspect domain components.
4. Inspect existing models.
5. Inspect persistence implementations.
6. Review the approved application architecture.
7. Determine whether the current implementation genuinely requires another abstraction.

Do not create new layers simply because a template recommends them.

### Step 2: Explain Component Responsibilities

Introduce the following responsibilities progressively.

**HTTP Routes**

- Define API endpoints.
- Connect incoming requests to their appropriate controllers.
- Apply relevant middleware.

**Middleware**

- Handle cross-cutting request concerns.
- Apply authentication and other approved request-level controls.
- Avoid containing complex feature-specific business workflows.

**Form Requests**

- Validate incoming request data.
- Handle appropriate request authorization when applicable.
- Keep input validation organized.

**Controllers**

- Handle HTTP transport responsibilities.
- Receive validated request data.
- Invoke application operations.
- Return appropriate API responses.
- Avoid becoming the primary location for complex business workflows.

**Application Services / Use Cases**

- Coordinate application operations.
- Execute business workflows.
- Coordinate domain behavior and persistence.
- Manage relevant transaction boundaries.
- Keep application operations understandable.

**Domain Logic**

- Represent business concepts and rules.
- Protect domain invariants.
- Validate permitted state transitions.
- Avoid dependencies on HTTP transport and infrastructure details.

**Eloquent Models / Persistence Components**

- Represent and persist application data.
- Define relevant database relationships.
- Execute persistence operations.
- Follow approved entity ownership and database design.

**Repositories**

Introduce repositories only when there is a justified need.

Potential reasons include:

- Complex persistence queries.
- Meaningful separation between domain behavior and persistence implementation.
- Reusable persistence operations with clear responsibility.
- Specific architectural requirements documented by the project.

Do not create repository interfaces and implementations for every model automatically.

### Step 3: Identify Responsibility Placement

Present a concrete business operation and ask the learner to identify:

- Which component receives the request?
- Where should request validation happen?
- Where should authorization happen?
- Where should business rules be enforced?
- Where should transaction boundaries exist?
- Which component should persist the data?
- Which component should format the API response?

Discuss the reasoning behind each decision.

### Step 4: Introduce One Layer at a Time

When a layer is required:

1. Explain the problem it solves.
2. Explain its responsibility.
3. Explain its dependencies.
4. Implement the smallest useful example.
5. Refactor incrementally.
6. Preserve existing behavior.
7. Run relevant tests.

Do not introduce several abstractions simultaneously when the learner has not yet understood their purpose.

### Step 5: Evaluate Dependency Direction

Maintain clear architectural boundaries.

Examples:

- HTTP transport should not become the owner of business rules.
- Domain logic should not depend on HTTP request objects.
- Domain behavior should not depend on frontend components.
- Persistence implementation should not determine business policy.
- Application services should coordinate operations without becoming unnecessarily large.

Follow the approved Modular Monolith architecture.

### Step 6: Evaluate Transaction Boundaries

Introduce database transactions when a business operation requires multiple related persistence operations to succeed or fail together.

Use Laravel's supported transaction mechanisms where appropriate.

For example:

`DB::transaction(...)`

Explain:

- Why a transaction is required.
- Which operations belong inside it.
- What happens when an operation fails.
- How consistency is preserved.

Do not wrap every database operation in a transaction without a reason.

### Step 7: Test Architectural Changes

After introducing or changing layers:

- Test the relevant application behavior.
- Verify database interactions.
- Verify error propagation.
- Verify transaction behavior.
- Check for regressions.
- Confirm that the new abstraction has a clear responsibility.

## Absolute Constraints

- Controllers must not become repositories.
- Do not place authoritative business rules in React.
- Do not put complex business workflows in middleware.
- Do not use Form Requests as a replacement for domain validation.
- Do not introduce unnecessary service classes.
- Do not introduce repositories without a justified need.
- Do not create interfaces solely to satisfy an arbitrary pattern.
- Do not create a dependency injection framework without approval.
- Keep component responsibilities clear.
- Preserve the approved Modular Monolith boundaries.
- Do not let implementation details redefine business requirements.

---

# SKILL 3: Writing Laravel Backend Tests

## Context & Goal

**Purpose:**

Develop confidence in backend behavior and protect confirmed business requirements against regressions.

**Target:**

- Laravel feature tests.
- Laravel unit tests.
- Relevant application and domain tests.
- Database integration tests where appropriate.
- `docs/engineering/TESTING.md`

## Step-by-Step Execution

### Step 1: Identify the Behavior Being Tested

Before writing a test:

1. Identify the feature.
2. Read the relevant user story.
3. Read its acceptance criteria.
4. Identify applicable business rules.
5. Identify the expected behavior.
6. Identify failure scenarios.
7. Identify unresolved decisions.

Explain what the test protects.

Do not create expected outcomes from undocumented business assumptions.

### Step 2: Introduce Unit Tests

Start with simple tests for isolated behavior.

Examples:

- Business calculations.
- Domain validation.
- State transition rules.
- Application utility behavior.
- Independent business logic.

Teach the learner how to:

- Arrange test data.
- Execute the operation.
- Assert the result.
- Verify failure behavior.

### Step 3: Introduce Feature Tests

Laravel feature tests are appropriate for testing application behavior through HTTP requests.

Examples:

- API endpoint responses.
- Request validation.
- Authentication.
- Authorization.
- Business workflow operations.
- API response structure.
- Database persistence.

Use Laravel testing facilities where appropriate.

Examples:

- `getJson()`
- `postJson()`
- `putJson()`
- `patchJson()`
- `deleteJson()`
- `assertStatus()`
- `assertJson()`
- `assertDatabaseHas()`
- `assertDatabaseMissing()`

Use the actual endpoint methods and response expectations defined by API Contracts.

### Step 4: Test Database Behavior

When database interaction is involved, verify:

- Record creation.
- Record updates.
- Referential integrity.
- Validation failures.
- Transaction rollback where applicable.
- Historical record preservation.
- Duplicate transaction prevention where required.

Use appropriate Laravel database testing facilities.

Examples:

- `RefreshDatabase`
- Database factories.
- Seeders when relevant.

Do not use destructive database operations against production data.

### Step 5: Test Authentication and Authorization

Test both permitted and prohibited access.

Examples:

- Unauthenticated user.
- Authenticated user with the correct role.
- Authenticated user with an incorrect role.
- User attempting to access another user's restricted resource.
- User attempting an invalid business operation.

Backend authorization must be tested independently of frontend visibility.

### Step 6: Test Business Workflow Rules

Test the approved workflow and its valid transitions.

Examples:

- Sample / Look Approval.
- LoA approval.
- Production Readiness.
- Waiting / On Hold.
- PPIC Preparation.
- Functional Layout.
- Mass Production.
- QC and Rework.
- Fulfillment.
- Order Change Request.
- Payment correction.
- Production Issue Report.

Only test confirmed behavior.

If a transition or permission is marked UNKNOWN or UNDECIDED, do not invent an expected result.

Request clarification when that behavior is necessary for implementation.

### Step 7: Test Failure Scenarios

Include relevant failure conditions:

- Invalid request data.
- Missing required fields.
- Unauthenticated access.
- Unauthorized access.
- Missing records.
- Invalid business transitions.
- Database failures.
- Duplicate operations.
- Transaction failures.

Tests should verify that failures do not silently corrupt business data.

### Step 8: Run Tests

Use the project's available Laravel testing commands.

Common commands:

Run all tests:

`php artisan test`

Run a specific test file:

`php artisan test tests/Feature/ExampleTest.php`

Run a filtered test:

`php artisan test --filter=ExampleTest`

Run additional configured quality checks where available.

Do not assume that all tools are installed or that every command is configured.

Inspect project configuration before relying on additional scripts.

### Step 9: Investigate Test Failures

When a test fails:

1. Read the complete error output.
2. Identify the failing assertion.
3. Determine whether the failure comes from implementation, test setup, environment, or incorrect expectations.
4. Inspect related business requirements.
5. Identify the root cause.
6. Correct the implementation or test only when justified.
7. Run the relevant tests again.
8. Run broader regression tests when appropriate.

Never modify a test merely to make an incorrect implementation pass.

### Step 10: Evaluate Test Quality

Review:

- Whether the test protects meaningful behavior.
- Whether expected results are grounded in approved requirements.
- Whether setup is understandable.
- Whether assertions are meaningful.
- Whether failure cases are covered.
- Whether tests are independent.
- Whether the test avoids unnecessary complexity.

A passing test does not prove that all business requirements are satisfied.

## Absolute Constraints

- Do not weaken tests to hide implementation defects.
- Do not invent expected outcomes for unresolved business rules.
- Do not treat proposed requirements as confirmed.
- Do not test against production data.
- Do not introduce advanced testing infrastructure before it is needed.
- Do not confuse successful test execution with complete business validation.
- Do not ignore authorization testing for protected operations.
- Do not remove regression tests without justification.

---

# General Backend Engineer Principles

## 1. Backend Authority

Laravel ERP Core is the authoritative owner of:

- Business logic.
- Business validation.
- Workflow transitions.
- Authentication.
- Authorization.
- Database persistence.
- Business history.
- Applicable audit operations.
- API responses.

The frontend must not replace backend authority.

## 2. API Contracts

Every endpoint must follow:

`docs/api/API_CONTRACTS.md`

Changes affecting API behavior must be reviewed against relevant frontend dependencies.

Do not silently introduce incompatible request or response structures.

## 3. Database Integrity

Follow:

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`

Where available and applicable.

Rules:

- Use PostgreSQL.
- Use version-controlled Laravel migrations.
- Preserve existing data during schema changes.
- Maintain foreign key and database integrity.
- Use appropriate database transactions.
- Avoid speculative tables and columns.
- Do not silently change authoritative data ownership.

## 4. Security

Follow:

`docs/engineering/SECURITY.md`

Where available.

Always:

- Validate incoming data.
- Enforce authorization.
- Protect sensitive information.
- Use safe database operations.
- Avoid exposing internal exceptions.
- Keep credentials and secrets outside source control.
- Apply approved authentication and security mechanisms.

## 5. Error Handling

Follow:

`docs/engineering/ERROR_HANDLING.md`

Where available.

Maintain consistent API error responses and useful server-side diagnostics.

## 6. Testing

Follow:

`docs/engineering/TESTING.md`

Testing must be performed throughout implementation.

Do not postpone all testing until a dedicated testing sprint.

## 7. Documentation

Update relevant documentation when an approved change affects:

- API contracts.
- Database schema.
- Business logic.
- Application architecture.
- Error handling.
- Security.
- Testing.
- Deployment behavior.

Do not modify business requirements through engineering documentation without proper review.

## 8. Collaboration with Other Skills

Coordinate with other skills when their responsibilities are relevant.

- `mentor`: Guided learning and progressive implementation.
- `business-analyst`: Business requirements and workflow verification.
- `database-engineer`: Schema design, migrations, relationships, and queries.
- `code-reviewer`: Implementation quality and security review.
- `debugger`: Bug investigation and troubleshooting.
- `frontend-engineer`: REST API integration and frontend compatibility.
- `system-architect`: Architectural decisions and system boundaries.

Do not duplicate another skill's responsibilities unnecessarily.

---

# Final Objective

Build a reliable, secure, testable, and maintainable Laravel ERP Core.

The learner must progressively understand:

- How Laravel processes HTTP requests.
- How REST API endpoints are structured.
- How request validation and authorization work.
- How business workflows are implemented.
- How application and domain responsibilities are separated.
- How PostgreSQL persistence works.
- How transactions protect data consistency.
- How backend tests protect business behavior.
- How to diagnose and resolve backend problems.

The ultimate goal is not merely to produce working code.

The goal is to develop independent backend engineering judgment while maintaining consistency with the approved Garment ERP business requirements and system architecture.