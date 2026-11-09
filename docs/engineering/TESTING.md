# Testing

**Product:** Garment ERP Core
**Status:** ENGINEERING BASELINE — PHASE 1
**Backend:** Laravel / PHP
**Frontend:** React + Vite + TypeScript
**Database:** PostgreSQL

This document defines the testing principles, testing progression, and verification requirements for the Garment ERP application.

---

## 1. Testing Principles

- Tests verify implemented behavior against approved requirements.
- Backend tests must verify business logic independently of frontend behavior.
- Backend is the authoritative enforcement layer for business rules.
- Tests must not introduce business rules based on assumptions.
- Requirements marked UNDECIDED, UNKNOWN, or PROPOSED
  must not become confirmed mandatory constraints.
- Tests must be repeatable.
- Tests must not depend on execution order.
- Tests must not use production data or production credentials.
- Test failures must be investigated.
- Passing tests do not replace manual verification when manual verification is required.

---

## 2. Testing Progression

Testing follows this learning and implementation progression:

1. Unit Tests.
2. Application / Service Tests.
3. Database / Persistence Tests.
4. API Feature Tests.
5. Business Rule Tests.
6. Integration Tests.

This progression is a guide.

It is not a mandatory sequence for every feature.

The required test types depend on the component and behavior being verified.

Laravel testing conventions should be used where appropriate.

---

## 3. Business Rules That Require Tests

The following areas require tests as their implementation progresses:

### 3.1 Customer and Order

- Customer data handling.
- Order creation.
- Order information validation.
- Size-based quantity handling.
- Negative quantity rejection.
- Production type validation.
- CMT and FOB support.
- Protected order specification behavior.
- Order Change Request restrictions.

### 3.2 Sampling and Approval

- Pattern Preparation tracking.
- Sample Production tracking.
- Sampling duration references.
- Sample / Look Approval status handling.
- Sample Approval Gate.
- Revision behavior according to approved transitions.
- Rejection behavior according to approved transitions.

Do not invent revision limits or rejection consequences.

### 3.3 LoA and Payment

- LoA status handling.
- LoA Approval Gate.
- Order specification locking after LoA approval.
- Payment recording.
- Received versus expected payment distinction.
- Payment correction authorization.
- Original payment preservation.
- Correction history.

DP as a production prerequisite remains UNDECIDED.

Do not write mandatory DP production-gate tests until that decision is approved.

### 3.4 Production Readiness

- Readiness evaluation.
- Required prerequisite handling.
- Waiting / On Hold behavior.
- Waiting reason preservation.
- Readiness re-evaluation.
- Prevention of automatic resumption based only on date.

The complete readiness checklist remains UNKNOWN.

Tests must use only approved prerequisites.

### 3.5 Functional Layout

- Functional Layout preparation.
- Layout retrieval.
- Permitted layout updates.
- Layout history, where implemented.
- Separation between Functional Layout and production stages.
- Functional Layout exclusion from Mass Production progress.

### 3.6 Mass Production

- Cutting before Sewing.
- Sewing before QC.
- QC PASS progression to Finishing.
- QC FAIL progression to Rework.
- Rework followed by QC again.
- Repeated QC / Rework cycles according to approved rules.
- Production start when Cutting actually begins.
- Production completion requirements.
- Fulfillment completion requirements.

Do not invent QC duration, Rework duration, inspection coverage, or rework limits.

### 3.7 Production Progress

- PPIC-only progress submission.
- Cutting progress.
- Sewing progress.
- Finishing progress.
- Timestamp preservation.
- Submitting-user preservation.
- Historical progress preservation.
- Unchanged percentage submissions as new records.
- Missing update distinction.
- Prevention of unauthorized Production Staff submissions.

Overall progress formula and weighting remain UNDECIDED.

### 3.8 Timeline and Delay

- Planned versus actual dates.
- Original planned completion preservation.
- Estimated completion distinction.
- Working-day handling.
- Delay Report creation.
- Delay Report resolution.
- Delay Report history.
- Email dispatch initiation.
- Email delivery outcome recording, where implemented.

Overdue calculation and delay aggregation remain UNDECIDED.

### 3.9 Production Issue Reports

- Issue Report creation.
- Issue Report status handling.
- PPIC follow-up.
- Resolution note requirement.
- Own-report visibility for Production Staff.
- Owner read-only access.
- Admin access restriction.
- In-App Notification triggering.
- Per-user read state.

### 3.10 Order Change Requests

- Admin request creation.
- Owner approval.
- Owner rejection.
- Protected specification handling.
- Previous order version preservation.
- Readiness revalidation.
- Functional Layout revalidation.
- Restriction after Cutting starts.

Detailed Change Request transitions remain UNDECIDED.

### 3.11 Fulfillment

- Packing tracking.
- Delivery / Customer Pickup tracking.
- Fulfillment confirmation.
- Prevention of premature COMPLETED status.

Detailed evidence and operational responsibilities must follow approved business decisions.

---

## 4. Backend Testing

Backend tests must verify relevant behavior across:

- Business logic.
- Application services and use cases.
- Request validation.
- Authorization.
- Database persistence.
- Database transactions.
- API responses.
- Workflow transitions.
- Error handling.
- Audit history.
- Notification behavior.

Tests must verify both expected behavior and relevant failure conditions.

### 4.1 Laravel Testing

Backend testing uses Laravel's testing facilities.

Test execution command:

Run from:

`apps/api/`

```bash
php artisan test
```

This command must execute the configured backend test suite.

The actual test runner and additional testing packages must follow the Laravel application configuration.

Do not assume a separate testing framework has been installed unless it is present in the project.

### 4.2 Backend Verification

Relevant verification may include:

- PHP syntax checks.
- Laravel application tests.
- API feature tests.
- Database integration tests.
- Authorization tests.
- Workflow tests.

Additional tooling must not be treated as mandatory unless it has been installed and configured.

If a verification command or tool is unavailable, report it as unavailable.

### 4.3 Database Testing

Database-related tests must:

- Use a dedicated test database or approved test environment.
- Avoid production data.
- Avoid production credentials.
- Verify relevant database constraints.
- Verify transactional behavior.
- Verify required historical data preservation.
- Avoid dependence on test execution order.

The test database must not point to production.

### 4.4 API Feature Testing

API tests should cover:

- Request validation.
- Success responses.
- Error responses.
- Authentication.
- Authorization.
- Resource visibility.
- Workflow transitions.
- Business rule enforcement.
- Historical record preservation.
- Relevant notification effects.

Tests must follow API_CONTRACTS.md.

---

## 5. Frontend Testing

Frontend tests should cover:

- Component rendering.
- User interactions.
- Form validation.
- Loading states.
- Empty states.
- Error states.
- API request handling.
- API response presentation.
- Role-based UI behavior.
- Navigation behavior.

Frontend tests must not replace backend validation or authorization tests.

### 5.1 Frontend Commands

Run from:

`apps/web/`

When the corresponding scripts are available:

```bash
npm run type-check
npm run lint
npm run test
```

If a command or script does not exist, report it as unavailable.

Do not invent commands or assume a testing framework has already been configured.

---

## 6. Integration Testing

Integration tests verify that application components work together correctly.

Relevant integration areas include:

- Laravel API and PostgreSQL.
- API request validation and persistence.
- Authorization and resource visibility.
- Workflow operations and database transactions.
- Notification creation.
- Delay Report email dispatch initiation.
- React frontend and Laravel API communication.

Integration tests must use controlled test environments.

External email delivery must not be assumed successful merely because dispatch was initiated.

The exact email testing mechanism remains subject to notification implementation.

---

## 7. Security and Authorization Testing

Tests must verify confirmed access boundaries.

Examples:

**OWNER**

- Can approve or reject Payment Corrections.
- Can approve or reject Order Change Requests.
- Has read-only Production Issue Report access.
- Has read-only order-status audit history access.

**ADMIN**

- Can manage customer and order records.
- Can record payments.
- Can create Order Change Requests.
- Must not access Production Issue Reports.

**PPIC**

- Can evaluate Production Readiness.
- Can submit production progress.
- Can manage Delay Reports.
- Can manage Production Issue Reports.
- Can confirm fulfillment completion.

**PRODUCTION_STAFF**

- Can create Production Issue Reports.
- Can view their own reports.
- Must not submit percentage-based production progress.
- Must not access unrelated production assignments.

Unconfirmed department and assignment restrictions must not be treated as finalized test requirements.

Backend authorization tests must verify direct API access, not only frontend visibility.

---

## 8. Verification Checklist

Before claiming a feature is complete:

1. Review the relevant product requirements.
2. Review approved Business Rules.
3. Review relevant Business Workflow transitions.
4. Review the applicable role permissions.
5. Review the API contract.
6. Identify expected behavior and failure conditions.
7. Add or update relevant tests.
8. Run applicable verification commands.
9. Review test failures.
10. Fix underlying implementation issues.
11. Verify historical data preservation where required.
12. Verify authorization where applicable.
13. Update relevant documentation.
14. Report verification results accurately.

Do not ignore failing tests.

Do not modify tests merely to hide implementation defects.

Do not claim tests passed if they were not executed.

---

## 9. Definition of Done

### 9.1 Normal Backend Feature

- Requirement understood.
- Relevant business rules understood.
- Relevant workflow understood.
- API contract understood, where applicable.
- Implementation works as expected.
- Input validation considered.
- Error handling considered.
- Authorization considered.
- Database integrity considered.
- Historical data requirements considered.
- Relevant tests added or updated.
- Relevant tests pass.
- Code is readable and maintainable.
- Relevant documentation is updated.
- Git commit message is meaningful.

### 9.2 Normal Frontend Feature

- Requirement understood.
- Expected UI behavior understood.
- API contract understood.
- Loading, success, empty, and error states considered.
- Frontend validation provides appropriate feedback.
- Backend remains authoritative for business validation.
- Backend remains authoritative for authorization.
- Relevant tests added or updated where appropriate.
- Type-check passes when configured.
- Lint passes when configured.
- Code is readable and maintainable.
- Relevant documentation is updated.
- Git commit message is meaningful.

### 9.3 Integration Feature

- Relevant backend and frontend contracts are aligned.
- API communication works as expected.
- Error responses are handled consistently.
- Authorization is enforced by the backend.
- Related data changes preserve integrity.
- Relevant integration tests pass.
- Deployment requirements are documented where applicable.

---

## 10. Testing Governance

1. Tests must follow approved requirements.
2. Tests must not invent business behavior.
3. Backend business rules must be tested independently of frontend behavior.
4. Authorization must be tested at the backend boundary.
5. Historical business data must be protected.
6. Tests must use controlled environments.
7. Unresolved business decisions must remain unresolved.
8. Test results must be reported accurately.
9. Additional testing practices may be introduced progressively.
10. Testing complexity must remain appropriate to the implementation stage.

Do not require advanced production testing practices from the first beginner challenge.

Introduce additional testing practices as the project and implementation progress.

---

## 11. Related Documentation

Business:

- `docs/business/PRD.md`
- `docs/business/BUSINESS_RULES.md`
- `docs/business/BUSINESS_WORKFLOW.md`
- `docs/business/USER_ROLES.md`

Architecture:

- `docs/architecture/SYSTEM_ARCHITECTURE.md`
- `docs/architecture/APPLICATION_ARCHITECTURE.md`
- `docs/architecture/DEPLOYMENT_ARCHITECTURE.md`

API:

- `docs/api/API_CONTRACTS.md`

Database:

- `docs/database/DATABASE_DESIGN.md`
- `docs/database/DATA_DICTIONARY.md`

Engineering:

- `docs/engineering/CODE_STYLE.md`
- `docs/engineering/ERROR_HANDLING.md`
- `docs/engineering/SECURITY.md`
- `docs/engineering/GIT_WORKFLOW.md`