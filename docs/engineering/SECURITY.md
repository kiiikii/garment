
# Security Guidelines

Status: Draft

Security requirements must be considered throughout the development
of Garment ERP Core.

Security implementation is introduced progressively through
the development sprints. However, fundamental security principles
must be followed from the beginning of the project.

This document defines security requirements and implementation
expectations for the backend, frontend, database, API,
authentication, authorization, and supporting infrastructure.

Detailed API error conventions are defined in ERROR_HANDLING.md
and API_CONTRACTS.md.

## 1. General Security Principles

- Apply secure-by-default principles.
- Follow the principle of least privilege.
- Treat all client input as untrusted.
- Enforce security rules on the backend.
- Never rely on frontend validation or UI restrictions as
  security enforcement.
- Never expose internal implementation details to API clients.
- Security-sensitive operations must be validated and authorized
  before execution.
- Do not introduce security mechanisms based on unconfirmed
  technical or business assumptions.
- Security requirements must remain consistent with approved
  Business Rules and PRD requirements.

## 2. Authentication

- Authentication must be handled by the backend.
- Passwords must never be stored in plaintext.
- Passwords must use an appropriate password hashing algorithm.
- Authentication credentials must never be exposed through
  API responses, application logs, or frontend code.
- Authentication failures must not disclose sensitive
  account or implementation information.
- Authentication mechanisms must be designed to prevent
  unauthorized access to protected resources.

The password hashing algorithm, token/session strategy,
expiration policy, and credential recovery flow remain
subject to technical design decisions.

## 3. Authorization and RBAC

- Authorization must be enforced by the backend.
- Access must follow the principle of least privilege.
- Role-based access control must be enforced consistently
  across protected API endpoints.
- Users must not gain access to resources or operations
  outside their assigned permissions.
- Resource ownership and data visibility restrictions
  must be enforced by the backend.
- Never trust client-provided roles, permissions,
  ownership information, or authorization claims without
  server-side verification.
- Unauthorized operations must be rejected even when
  requests are sent directly without using the frontend.

Authorization must remain consistent with USER_ROLES.md
and the approved business workflows.

## 4. API Security and Input Validation

- All incoming API data must be treated as untrusted.
- Validate request structure, data types, and approved
  business constraints on the backend.
- Reject malformed, unexpected, or invalid input.
- Do not introduce mandatory business validation based
  on assumptions.
- Prevent SQL injection through parameterized queries
  or appropriately safe database access mechanisms.
- Apply appropriate request size limits.
- Validate resource access before returning protected data.
- Sensitive operations must require appropriate authentication
  and authorization.
- API error responses must follow ERROR_HANDLING.md
  and API_CONTRACTS.md.
- Never expose stack traces, SQL errors, internal paths,
  secrets, or diagnostic information to API clients.

## 5. Database Security

- Database credentials must never be hardcoded or committed
  to version control.
- Database access must use appropriately restricted credentials.
- Database queries must use parameterized statements.
- Database access must remain within the backend application
  boundary.
- The frontend must never connect directly to PostgreSQL.
- Sensitive information must not be unnecessarily stored
  or exposed.
- Access to database backups must be restricted.
- Production database access must be limited to authorized
  services and personnel.

## 6. Secrets and Configuration Management

- Never commit passwords, API keys, tokens, database credentials,
  private keys, or other secrets.
- Sensitive configuration must be provided through environment
  variables or an appropriate secrets management mechanism.
- Environment files containing secrets must be excluded
  from version control.
- Example configuration files must not contain real credentials.
- Never expose server-side secrets through React environment
  variables, frontend bundles, or API responses.
- Rotate compromised credentials and revoke affected tokens
  when necessary.

The production secrets management mechanism remains
subject to deployment decisions.

## 7. Frontend and Browser Security

- React + Vite is an API client and must not be treated
  as a security enforcement layer.
- Configure CORS with explicitly permitted origins.
- Do not use unrestricted wildcard origins in production.
- Apply appropriate protection against XSS.
- Apply CSRF protection when the selected authentication
  mechanism and browser request model require it.
- Do not expose authentication credentials or server-side
  secrets in frontend source code.
- Authentication token storage and browser session handling
  must follow the approved authentication design.
- Frontend access restrictions must never replace backend
  authorization.

## 8. File Upload Security

- Validate uploaded file types and sizes on the backend.
- Do not trust file extensions or client-provided MIME types alone.
- Generate safe server-side filenames.
- Prevent uploaded files from being executed as application code.
- Restrict access to uploaded files according to application
  permissions.
- Do not allow unauthorized users to access private attachments.
- Apply appropriate validation to product reference images
  and Production Issue Report attachments.

Storage location, supported formats, file size limits,
and file-serving mechanisms remain subject to technical design.

## 9. Audit Logging and Application Logging

### 9.1 Business Audit Logs

- Security-relevant and important business operations
  must retain appropriate audit records.
- Audit records should identify the actor, action,
  affected resource, and timestamp.
- Important status changes, approvals, and protected
  data modifications must remain traceable.
- Ordinary application operations must not allow unauthorized
  modification or deletion of audit records.
- Access to audit records must follow approved role permissions.
- Audit logs must not contain plaintext passwords,
  authentication tokens, or other sensitive credentials.

Audit requirements must remain consistent with the
business audit history requirements.

### 9.2 Application Logs

- Unexpected server-side failures should be recorded
  with sufficient diagnostic context.
- Application logs must not expose secrets, passwords,
  authentication tokens, or unnecessary sensitive information.
- Diagnostic information must remain restricted to
  authorized personnel.
- Client-facing error responses must not expose internal
  diagnostic details.
- Logging conventions must remain consistent with
  ERROR_HANDLING.md.

## 10. Rate Limiting and Abuse Prevention

- Authentication endpoints must be protected against
  brute-force attempts.
- Apply rate limiting to sensitive and abuse-prone
  API endpoints.
- Request limits should be configurable where appropriate.
- Rejected requests must not disclose sensitive
  internal information.
- Rate-limiting behavior must not bypass authentication
  or authorization requirements.

Specific thresholds and implementation mechanisms
remain subject to technical design.

## 11. Transport and Deployment Security

- Production API communication must use HTTPS.
- Production secrets must not be included in deployment
  artifacts or source control.
- Production database access must not be unnecessarily
  exposed to public networks.
- Production services must run with appropriately
  restricted permissions.
- Development and production configurations must
  remain separated.
- Deployment must not disable security controls merely
  to simplify configuration.

Detailed infrastructure and deployment security requirements
belong in the relevant architecture and deployment documents.

## 12. Security Testing

- Security-sensitive functionality must include
  appropriate tests.
- Test authentication and authorization boundaries.
- Test unauthorized resource access.
- Test role and data ownership restrictions.
- Test invalid and malicious request inputs.
- Test SQL injection prevention.
- Test file upload validation and access restrictions.
- Test that sensitive information is not exposed
  through API responses or application logs.
- Security-sensitive changes must be reviewed before merging.

## 13. Implementation Roadmap

| Topic | Sprint |
|---|---|
| Password hashing, registration, login, token/session | 15 |
| Authorization and RBAC | 16 |
| Audit log security considerations | 17 |
| Authentication and authorization security review | 19 |
| SQL injection prevention | 19 |
| CORS configuration for the React SPA | 19 |
| Rate limiting | 19 |
| Secret management | 19 |
| Token security | 19 |
| Error disclosure | 19 |

Sprint assignments are implementation targets, not permission
to ignore applicable security requirements in earlier sprints.

## 14. Non-Negotiable Rules

- Never commit secrets.
- Never store plaintext passwords.
- Never trust frontend-only authorization.
- Never expose internal implementation details through API responses.
- Never bypass backend validation or authorization.
- Never allow unauthorized access to protected resources.
- Never log sensitive authentication credentials.
- Never allow ordinary application operations to bypass
  required audit traceability.
- Never introduce unconfirmed security or business constraints.
- Never disable security controls merely to make development
  or testing easier.
