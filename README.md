# Basic Website
For 2025-2026 Spring Holiday

## Description
Campus Hub is a website platform for managing student-related services & announcements with role-based access for students, instructors and admins. (Like Blackboard.com, but from scratch)

## Tech Stack
- Backend: PHP (PDO)
- Database: MySQL / MariaDB
- Frontend: HTML, CSS, JavaScript
- Auth: PHP Sessions

## Core Functions
- User Registration
- User Login
- Role-based Access (Admin / Student)
- Authentication using sessions or JWT
- CRUD module (Campus News or Services)
- Pagination for listings

## Project Status
Ongoing

## Quick setup (local XAMPP)

1. Start Apache and MySQL in XAMPP.
2. Import the database schema: open a terminal or phpMyAdmin and run:

```sql
-- from repository root
SOURCE db/init.sql;
```

3. Ensure the DB credentials in `Basic-Website/campus-hub/backend/config/db.php` match your local MySQL (defaults use `root` with empty password).
4. Place the project in your Apache `htdocs` (already in `c:/xampp/htdocs/Basic-Website` for this workspace).

## API Endpoints

All backend endpoints return JSON and use PHP sessions for auth. Use `credentials: include` in fetch or include cookies in curl when testing.

- Register: `POST /Basic-Website/campus-hub/backend/auth/register.php`
	- Body (JSON): `{ "email": "you@example.com", "password": "password123", "role": "user" }`
	- Success: 201 Created `{ "success": true, "message": "User registered" }`

- Login: `POST /Basic-Website/campus-hub/backend/auth/login.php`
	- Body (JSON): `{ "email": "you@example.com", "password": "password123" }`
	- Success: 200 OK `{ "success": true, "message": "Login successful" }` and a session cookie is set.

- Logout: `POST /Basic-Website/campus-hub/backend/auth/logout.php`
	- Destroys session.

- News API: `Basic-Website/campus-hub/backend/api/news.php`
	- `GET` list: `GET /Basic-Website/campus-hub/backend/api/news.php?limit=20&offset=0` — returns `{ "success": true, "data": [ ... ] }`
	- `GET` single: `GET /Basic-WEbsite/campus-hub/backend/api/news.php?id=123`
	- `POST` create: (requires logged-in session)
		- Body (JSON): `{ "title": "Hello", "content": "World" }`
	- `PUT/PATCH` update: (author or admin)
		- Provide `id` either as query `?id=123` or in request body; body fields: `title`, `content`.
	- `DELETE` delete: (author or admin) `DELETE /Basic-Website/campus-hub/backend/api/news.php?id=123`

## Quick curl examples

Register:
```bash
curl -X POST -H "Content-Type: application/json" \
	-d '{"email":"test@example.com","password":"pass12345"}' \
	http://localhost/Basic-Website/campus-hub/backend/auth/register.php
```

Login (save cookies):
```bash
curl -c cookies.txt -X POST -H "Content-Type: application/json" \
	-d '{"email":"test@example.com","password":"pass12345"}' \
	http://localhost/Basic-Wesbite/campus-hub/backend/auth/login.php
```

Create news (use saved cookies):
```bash
curl -b cookies.txt -X POST -H "Content-Type: application/json" \
	-d '{"title":"Announcement","content":"Welcome back"}' \
	http://localhost/Basic-Website/campus-hub/backend/api/news.php
```

Fetch news:
```bash
curl http://localhost/Basic-Website/campus-hub/backend/api/news.php
```

## Notes & next steps
- Consider adding simple input sanitization on the frontend and stronger CSRF protections for production.
- Optionally add a Postman collection in `tools/` for automated testing.
