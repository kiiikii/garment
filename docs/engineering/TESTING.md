# Testing

## 1. Testing Principles

- Tests verify implemented behavior against approved requirements and business rules.
- Backend tests must verify business logic independently of frontend behavior.
- The backend is the authoritative enforcement layer for business rules.
- Tests must not introduce business rules based on assumptions.
- Requirements marked UNDECIDED, UNKNOWN, or PROPOSED must not be treated as confirmed mandatory constraints.
- Tests must be repeatable and must not depend on execution order.
- Tests must not use production data or production credentials.
- A passing test suite does not replace manual verification when manual verification is required.

## 2. Testing Progression

Testing follows the learning progression:

1. Unit tests
2. Service tests
3. Repository tests
4. API tests
5. Business rule tests
6. Integration tests

This progression is a learning guide, not a mandatory sequence for every feature.

The required test types depend on the component being implemented and the behavior being verified.

## 3. Business Rules That Require Tests

The following areas will require tests as their implementation progresses:

- Order data validation
- Size and quantity handling
- Production type validation (CMT / FOB)
- Sampling duration calculations
- Approval state transitions
- LoA approval gate
- Production readiness
- Waiting and hold behavior
- Production sequence
- Timeline calculations
- Planned versus actual duration
- Notification triggering rules

Testing must follow the latest approved requirements in the business documentation.

Unresolved business decisions must remain untested as mandatory constraints until they are formally defined.

## 4. Backend Testing

Backend tests should cover relevant behavior across the following areas:

- Domain logic and business rules
- Application services and use cases
- Repository behavior and database interactions
- API request validation and response behavior
- Authentication and authorization
- Error handling
- Workflow and state transitions

Tests must verify both expected behavior and relevant failure conditions.

### Commands

Run from `apps/api`:

\`\`\`bash
gofmt -l .
go vet ./...
go build ./...
go test ./...
\`\`\`

- `gofmt -l .` must produce no output.
- `go vet ./...` must complete without reported issues.
- `go build ./...` must complete successfully.
- `go test ./...` must pass.

## 5. Frontend Testing

Frontend tests should cover relevant behavior across:

- Component rendering
- User interactions
- Form validation and error states
- API request handling
- Loading, empty, and failure states
- Role-based UI behavior

Frontend tests must not be treated as a replacement for backend validation or authorization.

### Commands

Run from `apps/web`, when the corresponding scripts are available:

\`\`\`bash
npm run type-check
npm run lint
npm run test
\`\`\`

If a command or script does not exist yet, report it as unavailable.

Do not invent commands or assume that a test framework has already been configured.

## 6. Verification Checklist

Before claiming a feature is complete:

1. Review the relevant requirements and approved business rules.
2. Identify the behavior and failure conditions that need verification.
3. Add or update tests where appropriate.
4. Run the relevant backend or frontend verification commands.
5. Review test failures and fix the underlying issue.
6. Do not ignore failing tests or modify tests merely to hide implementation defects.
7. Report verification results accurately.

## 7. Definition of Done

### Normal Backend Feature

- Requirement understood.
- Relevant business rules understood.
- API contract understood, if applicable.
- Implementation works as expected.
- Input validation considered.
- Error handling considered.
- Authorization considered, where applicable.
- Relevant tests added or updated.
- Relevant tests pass.
- Code is readable and maintainable.
- Git commit message is meaningful.

### Normal Frontend Feature

- Requirement and expected UI behavior understood.
- API contract understood.
- Loading, success, empty, and error states considered where applicable.
- Frontend validation provides appropriate user feedback.
- Backend remains authoritative for business validation and authorization.
- Relevant tests added or updated where appropriate.
- Type-check and lint pass when configured.
- Code is readable and maintainable.
- Git commit message is meaningful.

Do not require advanced production testing practices from the first beginner challenge.
Introduce additional testing practices as the project and its implementation progress.