# FocusFlow

FocusFlow is a PHP/MySQL web application that helps individuals and teams plan, track, and analyze work. It includes personal task management, team collaboration, calendar views, analytics, notifications, and role-based admin/moderator tooling.

## Tech stack
- PHP 7.4+ (compatible with PHP 8.x)
- MySQL/MariaDB
- Vanilla JavaScript, HTML, CSS
- Chart.js (via CDN)
- Google Fonts & Material Icons (via CDN)

## Prerequisites
- Web server (Apache recommended). On Windows, XAMPP is fine.
- PHP 7.4+ and MySQL
- A virtual host or folder accessible at a base path consistent with the code (default used in code: `/RWD_assignment/FocusFlow`)

## Quick start
1. Clone or copy FocusFlow into your web root, ideally at: `<web-root>/RWD_assignment/FocusFlow`.
2. Create a database (default name used in project: `assignment`).
3. Import `assignment.sql` into that database.
4. Configure database credentials in these files:
   - RegisterLayout/conn.php (primary)
   - ModeratorPage/conn.php (secondary; keep in sync with primary)
5. Start Apache/MySQL and open:
   - Landing site: Landing_Page/Homepage.php
   - App login: RegisterLayout/Login.php

If you choose a different base path, update hard-coded paths pointing to `/RWD_assignment/FocusFlow` in JS/PHP files or configure your web server to map the project to that route.

## Configuration
- Timezone: App sets `Asia/Kuala_Lumpur` in several backends.
- Base path: Some scripts reference absolute paths: `/RWD_assignment/FocusFlow/...` (note case sensitivity differs in a few files). Keep the folder name and route consistent or refactor those paths.
- File uploads: Saved to DB (`files` table) with a max size check (40MB in Community upload). Review before production.

## Core features
- Task Management (personal)
  - Create groups and tasks, set deadlines via relative timers.
  - Update status (Incomplete/Complete/Timeout) and description in-place.
  - Drag-and-drop UI helpers for tasks.
  - Backend: RegisterLayout/Todo/TodoBackend.php (JSON API)
- Team Collaboration
  - Teams, group tasks assignment, status updates, and file sharing per team.
  - Community pages for tasks and files; DM/communication pages available.
  - Backend: RegisterLayout/Communication/Community/CommunityBackend.php
- Calendar
  - Multi-view calendar with tasks by date; view task lists by day.
  - Calendar.php + CalendarBackend.php; mini calendar widget on Homepage.
- Analytics
  - Completion rates, category distribution, best/worst hours, task history, quick-add suggested tasks.
  - Frontend: RegisterLayout/Analytic.php + Analytic/Analytic.js
  - Backend: RegisterLayout/AnalyticBackend.php
- Notifications & Activity
  - System notifications for moderator-assigned tasks.
  - User activity pings (Admin dashboard updates via get_user_statuses/update_activity).
- File Management
  - Upload, preview, download team files; moderator file management.
  - Backends under Community (RegisterLayout/Communication/Community) and ModeratorPage/UploadedFileManagement.
- Admin/Moderator tooling
  - Admin dashboards: survey responses, user status/activity, moderation controls.
  - Moderator dashboards: task management (create, update, delete), team info, and uploaded files.

## User roles and access
- User: Personal tasks, calendar, analytics, teams & community features.
- Moderator: Everything a User can do plus team task assignment and file management.
- Admin: Global dashboards, survey insights, user activity/status, staff management.

Demo credentials (change for production):
- Admin: Admin / Xp8@rTq9!
- Moderator: Moderator / Xp8@rTq9!
- User: John Doe / Xp8@rTq9!

## Primary pages (by area)
- Landing: Landing_Page/Homepage.php, Features.php, Plans.php, GetHelp.php
- App (RegisterLayout):
  - Login.php, Signup.php, Account.php
  - Homepage.php (dashboard + mini calendar + group tasks)
  - Todo.php (task/groups UI; uses Todo/Todo.js)
  - Calendar.php
  - Analytic.php
  - Communication.php, CommunityPage.php, CommunityDMPage.php
  - Goal.php

## Important backends and APIs
- RegisterLayout/Todo/TodoBackend.php (JSON)
  - type=create_group | create_task | fetch_group_and_task | delete_group | update_task_status | update_task_description | FinalDate
- RegisterLayout/AnalyticBackend.php (application/x-www-form-urlencoded)
  - action=fetch (history) | add (quick add suggested task)
- RegisterLayout/Communication/Community/CommunityBackend.php
  - File upload, AddTask, DeleteTask, task status updates
- ModeratorPage/TaskManagement/TaskManagementBackend.php (form-data)
  - action=fetch_user | createTask | getDetails
- AdminPage/AdminDashboard
  - get_user_statuses.php, update_activity.php, get_stats.php, user_actions.php, check_suspension.php

Note: Many client calls use absolute paths under `/RWD_assignment/FocusFlow/...`. Ensure correct hosting path or refactor to a config constant.

## Database overview (from usage in code)
- users: id, name, role/usertype, status
- tasks: personal tasks and groups (groups are rows with only task_title; tasks have task_desc, dates/times, status, category, user_id)
- group_tasks: team_name, task_name, task_description, assigned_by, assigned_to, due_date, status, assigned_at
- team: team_name and memberships
- files: team file uploads (file_name, file_type, file_size, file_data, uploaded_at)
- survey_responses: entries displayed in Admin dashboards
- contactlist and related: used for messaging/contact features

Import the complete schema from `assignment.sql`.

## Project structure
```
FocusFlow/
├─ Landing_Page/                 # Marketing pages
├─ RegisterLayout/               # Auth + main app (dashboard, todo, calendar, analytics, community)
│  ├─ Todo/                      # Todo UI + TodoBackend.php (JSON API)
│  ├─ Calender/                  # Calendar JS helpers
│  ├─ Analytic/                  # Analytics JS
│  ├─ Communication/             # Community/DM and team file pages/backends
│  ├─ css/, img/                 # Assets
│  ├─ conn.php                   # Primary DB connection
├─ ModeratorPage/                # Moderator dashboards and tools
│  ├─ TaskManagement/
│  ├─ TeamManagement/
│  ├─ UploadedFileManagement/
│  ├─ conn.php                   # Secondary DB connection
├─ AdminPage/                    # Admin dashboards (survey, users, activity)
├─ assignment.sql                # DB schema/data dump
└─ README.md
```

## Common paths used in code
- Base: `/RWD_assignment/FocusFlow` (case varies in some files)
- Example fetches:
  - `/RWD_assignment/FocusFlow/RegisterLayout/Todo/TodoBackend.php`
  - `/RWD_assignment/FocusFlow/RegisterLayout/AnalyticBackend.php`
  - `/RWD_assignment/FocusFlow/AdminPage/AdminDashboard/get_user_statuses.php`

## Security and deployment notes
- Replace demo credentials and re-seed users.
- Review file upload size, type checks, and storage (currently stores binary in DB).
- Ensure prepared statements are used consistently (already used in most places).
- Consider moving hard-coded absolute paths to a central config.
- Serve over HTTPS in production.

## Troubleshooting
- Blank/JSON errors on API: check absolute path routing and CORS if using a different host.
- “Invalid request method/type”: verify the request Content-Type and payload shape (JSON vs form-data).
- Mixed-case path issues on Linux: standardize to `/RWD_assignment/FocusFlow` or update references.

## Environment & configuration
- Database settings: set host, user, password, and DB name in `RegisterLayout/conn.php` (and mirror in `ModeratorPage/conn.php`).
- Base URL: many frontend requests assume `/RWD_assignment/FocusFlow`. If you deploy elsewhere, configure your web server to map that route or update paths project-wide.
- Timezone: ensure server timezone matches `Asia/Kuala_Lumpur` or adjust date math in backends.

## Run locally (XAMPP)
1. Copy the folder to `C:/xampp/htdocs/RWD_assignment/FocusFlow`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Import `assignment.sql` into a database named `assignment`.
4. Edit `RegisterLayout/conn.php` with your MySQL credentials.
5. Open http://localhost/RWD_assignment/FocusFlow/RegisterLayout/Login.php

## API quick examples
- Create group (JSON)
  - POST `/RWD_assignment/FocusFlow/RegisterLayout/Todo/TodoBackend.php`
  - Body: `{ "type":"create_group", "group_name":"My Group" }`
  - Response: `{ "status":"success", "data":{ "group_name":"My Group" } }`
- Create task (JSON)
  - Body: `{ "type":"create_task", "category":"My Group", "title":"Task A", "content":"Desc", "timer":{"days":0,"hours":1,"minutes":0,"seconds":0} }`
  - Response: `{ "status":"success", "data":{ "id":<int>, "category":"My Group", "title":"Task A", ... } }`
- Update task description (JSON)
  - Body: `{ "type":"update_task_description", "task_id":123, "description":"New text" }`
  - Response: `{ "status":"success", "data":{ "task_id":123, "description":"New text" } }`

## Known issues
- Absolute paths and case: mixture of `/RWD_assignment` and `/RWD_Assignment` in some files can break on Linux. Normalize to one.
- Files stored in DB: large uploads bloat DB size. Consider filesystem storage + signed download.
- Duplicate element IDs may appear on some pages; validate with browser devtools and fix if customizing forms.

## Roadmap
- Centralize base URL and config constants.
- Move file uploads to filesystem storage with virus/type scanning.
- Add token-based API auth and CSRF hardening for JSON endpoints.
- Add unit/integration tests and CI.

## Contributing
- Fork, create a feature branch, commit with clear messages, open a PR.
- Follow existing coding styles (PHP with prepared statements; vanilla JS; minimal globals).

## Screenshots
- Add screenshots under a `/docs` folder and link them here (e.g., Dashboard, Todo, Calendar, Analytics, Community).