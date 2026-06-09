# RV Packaging App to Laravel Migration Audit

Last updated: 2026-06-06

## Objective

Move ongoing product development from the Avalonia desktop app in `RV-packaging-app` to the new Laravel web app in `rv-webapp`, with MySQL as the source of truth for operational data.

## Source application summary

The source app is a `.NET 8` Avalonia desktop application with MVVM structure. It currently behaves like a rich UI prototype with some real workflow logic, but almost all business state is held in memory inside view models and service objects rather than in a database.

Key characteristics:

- Desktop-first shell with screen-level navigation
- In-memory seeded demo data
- Excel / CSV lead import support
- Lightweight reminder store
- No SQL persistence layer in the source application
- No production authentication or authorization flow yet

## Source module inventory

### 1. Login

Source: `Features/Login`

Current behavior:

- Simple email / password form
- Calls a sign-in action without credential validation

Laravel migration target:

- Replace with real Laravel authentication
- Use `users` as the primary auth and employee table

### 2. Dashboard

Source: `Features/Home`

Current behavior:

- Snapshot metrics
- Lead source distribution
- Top employee widgets
- Weekly completion graph
- Due reminders summary

Laravel migration target:

- Dashboard page backed by SQL aggregates
- Replace hardcoded metrics with queries over leads, projects, follow-ups, and users

### 3. Add Lead

Source: `Features/AddLead`

Current behavior:

- Draft lead creation
- Spreadsheet import parsing through `LeadFileImportService`
- Note drafting
- Document attachment metadata
- Follow-up creation
- Optional conversion into project data

Laravel migration target:

- Lead creation form
- File import workflow
- File upload storage
- Lead notes and documents
- Lead-to-project conversion or promotion flow

### 4. Assign Leads

Source: `Features/AssignLeads`

Current behavior:

- Filtered lead list
- Search by source, status, assignee
- Open lead details
- Add new lead into the queue

Laravel migration target:

- Leads index page
- Server-side filtering and pagination
- Assignee-based workflows

### 5. Lead Details

Source: `Features/LeadDetails`

Current behavior:

- Full lead profile
- Editable customer and requirement fields
- Follow-up list
- Notes
- Documents
- Timeline-like activity items
- Project conversion

Laravel migration target:

- Lead detail page
- Related notes, documents, and follow-ups
- Future activity log or audit table

### 6. Projects

Source: `Features/Projects`

Current behavior:

- Active project list
- Completed project list
- Filtering by source and status
- Project management row view
- Detail drawer behavior

Laravel migration target:

- Projects index with status filters
- Completed project reporting
- Link projects back to their source leads where possible

### 7. Follow Ups / Reminders

Source: `Features/Reminders` and `Features/Reminders/Services/ReminderStore`

Current behavior:

- Two related but separate concepts:
  - `FollowUpRow` for CRM follow-up tasks
  - `ReminderPreview` for due reminders tied to projects
- Search and pagination for follow-ups
- Due-time ordering for reminders

Laravel migration target:

- Unify these into a persistent `follow_ups` model with due dates
- Derive reminder-style views from `follow_ups`

### 8. Employees

Source: `Features/Employees`

Current behavior:

- Static employee roster with role, status, and performance

Laravel migration target:

- Extend `users` table for employee-facing CRM metadata
- Later add reporting and workload summaries

### 9. Settings / Help / Placeholders

Current behavior:

- Placeholder content only

Laravel migration target:

- Defer until core CRM flows are stable

## Domain objects detected in the source app

The current source app implies these durable business entities:

- Users / employees
- Leads
- Projects
- Follow-ups
- Lead notes
- Lead documents

These are the right first tables for the Laravel version.

## Important migration insight

The Avalonia project contains real workflow logic but not a real persistence model. That means we should not try to copy the UI literally first and “figure out the database later”. The Laravel app should establish durable domain models immediately, then rebuild screens on top of those models.

## Laravel baseline architecture

Initial stack:

- Laravel 13
- MySQL
- Eloquent ORM
- Database-backed sessions / queue / cache supported by Laravel defaults
- Blade for the initial migration baseline

Potential later choices:

- Livewire if we want fast server-driven UI
- Inertia if we want SPA-like interactions later

## Initial MySQL schema

### users

Purpose:

- Authentication
- Employee roster
- Ownership / assignment for leads, projects, and follow-ups

Extra fields added beyond default Laravel auth:

- `phone`
- `role`
- `employment_status`
- `joined_at`
- `performance_percent`
- `performance_label`

### leads

Purpose:

- Canonical CRM lead record

Main fields:

- `lead_code`
- customer identity and company fields
- requirement fields
- `source`
- `status`
- `assigned_to_user_id`
- `inquiry_at`

### projects

Purpose:

- Operational projects and order-tracking records

Main fields:

- `project_code`
- `lead_id`
- `owner_id`
- `project_name`
- `customer_name`
- `source`
- `po_status`
- `project_status`
- `value_amount`
- `delivery_date`
- `progress_percent`

### follow_ups

Purpose:

- Scheduled actions tied to a lead, a project, or both

Main fields:

- `lead_id`
- `project_id`
- `assigned_to_user_id`
- `subject`
- `notes`
- `type`
- `status`
- `source_context`
- `due_at`
- `completed_at`

### lead_notes

Purpose:

- Persistent lead commentary and sales notes

### lead_documents

Purpose:

- Uploaded files associated with a lead

## Index plan for query speed

The old app has no SQL layer, so there were no source indexes to audit. The new Laravel schema is being created with first-pass indexes aligned to real screen behavior.

### users

- unique index on `email`
- index on `role`
- index on `employment_status`

### leads

- unique index on `lead_code`
- index on `status`
- index on `source`
- index on `inquiry_at`
- index on `company_name`
- index on `customer_name`
- composite index on `assigned_to_user_id, status, inquiry_at`
- composite index on `source, status`

### projects

- unique index on `project_code`
- index on `lead_id`
- index on `owner_id`
- index on `delivery_date`
- index on `customer_name`
- composite index on `project_status, delivery_date`
- composite index on `owner_id, project_status, delivery_date`
- composite index on `source, po_status, project_status`

### follow_ups

- index on `due_at`
- index on `lead_id`
- index on `project_id`
- index on `assigned_to_user_id`
- composite index on `status, due_at`
- composite index on `assigned_to_user_id, status, due_at`
- composite index on `lead_id, due_at`
- composite index on `project_id, due_at`

### lead_notes

- composite index on `lead_id, created_at`

### lead_documents

- composite index on `lead_id, created_at`

## Source limitations to keep in mind

- Dates are often stored as display strings in the desktop app
- Money values are also often stored as formatted strings
- Some “project” and “lead detail” views share overlapping fields
- The current reminder and follow-up concepts overlap and should be unified

## Recommended migration order

1. Stabilize schema and models in Laravel
2. Build authentication and employee ownership
3. Rebuild lead listing and detail flows
4. Rebuild add/import lead flow
5. Rebuild projects and follow-up screens
6. Rebuild dashboard metrics from real SQL data

## Verified framework target

Laravel current stable release was verified on 2026-06-06 against official Laravel sources:

- Release notes: https://laravel.com/docs/13.x/releases
- Installation docs: https://laravel.com/docs/13.x/installation
