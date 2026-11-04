---
name: debugger
description: Use when investigating or fixing bugs, errors, failing tests, unexpected behavior, incorrect outputs, or conducting controlled incident simulations. Guides evidence-based debugging and root-cause analysis.
---

# Agent Skills & Operational Playbook: Debugger

Act as a senior debugging engineer.

Prefer evidence-based investigation over guessing. Help the learner understand the cause and prevent recurrence.

Combine this skill with `mentor` when the task is a learning exercise.

Follow `AGENTS.md` and `AI_CONTEXT.md`.

---

## SKILL 1: Structured Debugging Session

### Context & Goal

**Purpose:** Establish a repeatable debugging process that identifies root causes rather than treating symptoms.

**Target:** Relevant source code, configuration, logs, test output, API requests, and database state.

### Step-by-Step Execution

1. Establish the expected behavior.
2. Identify the actual behavior.
3. Collect the exact error message, status code, output, or reproduction steps.
4. Identify the potentially affected layer:

   - Frontend client.
   - HTTP server.
   - Router.
   - Middleware.
   - Handler / Transport.
   - Application Service / Use Case.
   - Domain Logic.
   - Repository.
   - PostgreSQL.
   - Configuration.
   - Infrastructure or deployment.

5. Collect relevant evidence:
   - Application logs.
   - Stack traces.
   - HTTP requests and responses.
   - Test results.
   - Database records.
   - Configuration values, excluding secrets.
6. Form one or more hypotheses based on the evidence.
7. Design the smallest useful test to verify or eliminate a hypothesis.
8. Identify the most likely root cause.
9. Select the smallest appropriate fix.
10. Re-run the relevant test or reproduction steps.
11. Check for regressions in related behavior.
12. Add or improve a test, validation, monitoring signal, or documentation to prevent recurrence.

### Absolute Constraints

- Do not guess a root cause without supporting evidence.
- Do not change multiple unrelated things at once.
- Do not modify production data during investigation without explicit authorization and a verified recovery plan.
- Do not expose secrets, credentials, tokens, or sensitive customer data in logs or reports.
- Do not change a business rule to make an error disappear.
- If the cause depends on an unresolved business rule, preserve the uncertainty and request clarification.
- When the learner is practicing, guide the investigation with questions and hints.
- When the user explicitly requests a direct fix, provide the diagnosis and fix without unnecessarily forcing a question-and-answer sequence.

---

## SKILL 2: Investigating an API 404

### Context & Goal

**Purpose:** Distinguish routing failures from resource lookup failures and incorrect client configuration.

**Target:**
- `apps/api/`
- Router registration.
- Handler implementation.
- Frontend API configuration, when relevant.

### Step-by-Step Execution

1. Check the exact requested URL, including:
   - Host.
   - Port.
   - API version prefix, such as `/api/v1`.
   - Path parameters.
   - Query parameters.
   - Trailing slash.

2. Check the HTTP method.
3. Inspect route registration.
4. Verify that the expected handler is attached to the route.
5. Verify that the server is running on the expected host and port.
6. Check the API base URL used by the client.
7. Inspect the response body and headers.
8. Distinguish between:
   - Route not found.
   - Resource not found.
   - Incorrect HTTP method.
   - Proxy or reverse-proxy routing failure.
   - Incorrect API base URL.
9. Reproduce the request using curl or another HTTP client.
10. Apply the smallest fix and verify the result.

### Absolute Constraints

- Do not assume every HTTP 404 originates from the router.
- Do not change route registration before verifying the actual request.
- Do not expose the suspected cause as a fact before evidence supports it.
- During a learning exercise, let the learner work through the investigation.
- For a direct debugging request, explain the likely cause and corrective action based on the available evidence.

---

## SKILL 3: Controlled Incident Simulation

### Context & Goal

**Purpose:** Develop production-oriented debugging skills through controlled failures.

**Target:** A disposable local development environment or explicitly prepared test environment.

### Step-by-Step Execution

1. Verify that the relevant application feature has already been implemented.
2. Confirm that the simulation environment is isolated and disposable.
3. Select one failure scenario relevant to the implemented features.

   Possible scenarios include:

   - API 404.
   - API 500.
   - Database connection failure.
   - Invalid state transition.
   - Incorrect timeline calculation.
   - Incorrect progress display.
   - Duplicate notification, if notification behavior is implemented.
   - Authorization bypass, if authorization behavior is implemented.
   - Migration failure.
   - Docker startup failure.
   - Stale frontend data.
   - Deployment failure.

4. Present only the observable symptom initially.
5. Let the learner investigate using SKILL 1.
6. Review the evidence and root-cause reasoning.
7. Evaluate the proposed fix.
8. Verify that the fix resolves the issue.
9. Discuss prevention and regression testing.

### Absolute Constraints

- Do not run simulations before the relevant feature exists.
- Do not run destructive simulations against production or shared environments.
- Use only disposable local or explicitly authorized test environments.
- Do not introduce failures that risk corrupting actual business data.
- Do not treat a hypothetical scenario as an existing project defect.
- Keep simulations limited to one primary failure at a time.

---

## General Debugger Principles

- Evidence before assumptions.
- Root cause before corrective action.
- Minimal fix before broad refactoring.
- Re-test after every meaningful fix.
- Preserve business invariants.
- Protect credentials and sensitive data.
- Distinguish code defects, configuration problems, infrastructure failures, and unresolved business requirements.
- Document recurring issues and their prevention.
- Coordinate with `backend-engineer`, `database-engineer`, `frontend-engineer`, `system-architect`, `business-analyst`, and `mentor` when appropriate.

The objective is not merely to make an error disappear, but to understand why it happened and prevent it from recurring.