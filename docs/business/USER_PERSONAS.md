# User Personas

**Status:** UNDECIDED

This document defines candidate user personas for Garment ERP Core.

The PRD v1.0 identifies OWNER / MANAGEMENT as the Primary User Group.

Supporting user groups are:

- ADMIN
- PPIC
- PRODUCTION_STAFF

However, the exact primary persona has not yet been confirmed
based on actual daily operational responsibilities.

A primary user group does not automatically determine
the primary persona.

The primary persona must represent the user whose daily workflow,
operational problems, and information needs are most central
to the system's primary value.

---

## 1. User Groups Defined by the PRD

The following user groups are defined in PRD v1.0.

| User Group         | Primary Responsibilities                                                                                                                                |
| ------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| OWNER / MANAGEMENT | Business monitoring, payment correction approval, Order Change Request approval, and audit history.                                                     |
| ADMIN              | Customer and order management, sampling and LoA administration, payment recording, and Change Request creation.                                         |
| PPIC               | Production Readiness, Waiting / On Hold, preparation, Functional Layout, progress submissions, delay and issue follow-up, and fulfillment confirmation. |
| PRODUCTION_STAFF   | Assigned production information, work instructions, Functional Layout access, and operational issue reporting.                                          |

These are system user groups and do not automatically represent
individual user personas.

A single user may perform multiple business responsibilities.

## 2. Candidate Personas

The following are candidate personas, not confirmed organizational
positions or final primary personas.

| Candidate           | Why it could be primary                                                                                                                                          |
| ------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Owner / Management  | Identified as the Primary User Group in PRD v1.0. Requires business monitoring, payment oversight, approval of important business changes, and audit visibility. |
| PPIC Staff          | Prepares production and Functional Layout; needs visibility into orders that are ready, waiting, or delayed, and monitors production progress.                   |
| Production Manager  | Needs an overall view of production timelines, planned vs actual progress, production bottlenecks, and production delays.                                        |
| Order Owner (Sales) | Needs accurate order information to respond to customer inquiries about order progress.                                                                          |

Important distinctions:

1. Owner / Management is the primary user group identified by the PRD.
2. The primary persona remains UNDECIDED.
3. PPIC Staff, Production Manager, and Order Owner (Sales) are candidate personas.
4. A candidate persona does not automatically correspond to a system role.
5. A candidate persona does not establish additional system permissions.
6. Production Manager and Order Owner (Sales) are not confirmed as separate Phase 1 system roles.

These candidates must be evaluated against actual business operations.

## 3. Primary Persona Decision

**Status: UNDECIDED**

Select exactly ONE primary persona based on actual business operations.

The selection must consider:

- Daily responsibilities.
- Most frequent operational activities.
- Main problems experienced in the current workflow.
- Information required to perform daily work.
- How the system's primary capabilities address those problems.
- The operational impact of the system on the user's daily work.

Do not select a persona based only on organizational title,
management hierarchy, or assumed system usage.

The Primary User Group defined in PRD.md must remain consistent
unless an approved product requirement change is made.

Other relevant personas may be documented after the primary
persona is confirmed.

## 4. Persona Template

Use the following template when documenting an approved persona.

- **Name / title:**
- **User group:**
- **Daily work:**
- **Main responsibilities:**
- **Biggest pain point:**
- **Information needed:**
- **Most frequent system activities:**
- **What they need from the system:**
- **What success looks like:**

## 5. Documentation Rules

1. Persona definitions must reflect actual business operations.
2. Do not invent organizational responsibilities.
3. Do not assume that one persona equals one system role.
4. Do not derive permissions from persona descriptions.
5. System permissions remain governed by USER_ROLES.md.
6. Product capabilities remain governed by PRD.md.
7. Update this document when the primary persona has been explicitly approved.
