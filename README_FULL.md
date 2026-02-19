# Campus Hub - Student News Platform

A full-stack web application for managing campus news and announcements with role-based access control. Built from scratch during a 2025-2026 Spring Holiday learning project.

**Live Features:** User authentication, CRUD operations, search functionality, pagination, role-based access, and comprehensive security measures.

---

## 📋 Table of Contents
- [Overview & Architecture](#overview--architecture)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)
- [API Endpoints](#api-endpoints)
- [Security Features](#security-features)
- [Learning Outcomes](#learning-outcomes)

---

## Overview & Architecture

### System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     CLIENT SIDE (Frontend)                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  HTML (Login, Register, Dashboard)                   │  │
│  │  CSS (Responsive Design, Styling)                    │  │
│  │  JavaScript (Fetch API, DOM Manipulation)            │  │
│  └────────────────┬──────────────────────────────────────┘  │
│                   │ HTTP/HTTPS + Session Cookies            │
├─────────────────────────────────────────────────────────────┤
│                   SERVER SIDE (Backend)                      │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  PHP API Layer (Authentication & News CRUD)          │  │
│  │  ├─ /backend/auth/register.php (User creation)       │  │
│  │  ├─ /backend/auth/login.php (Session management)     │  │
│  │  ├─ /backend/auth/check.php (Session verification)   │  │
│  │  ├─ /backend/auth/logout.php (Session cleanup)       │  │
│  │  └─ /backend/api/news.php (News CRUD + Search)       │  │
│  └────────────────┬──────────────────────────────────────┘  │
│                   │ Prepared Statements (SQL Safe)          │
├─────────────────────────────────────────────────────────────┤
│                   DATABASE (Persistence)                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  MySQL/MariaDB                                       │  │
│  │  ├─ users (id, email, password_hash, role, ...)      │  │
│  │  └─ news (id, title, content, author_id, created_at) │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### Request Flow

```
1. User Login
   └─> POST /backend/auth/login.php
       └─> Verify credentials (bcrypt)
           └─> Create session
               └─> Return success + session cookie

2. User creates News
   └─> Check authentication (Session valid?)
       └─> Validate input (Title 3-200 chars, Content 5-5000 chars)
           └─> Execute prepared statement (prevent SQL injection)
               └─> Store in database
                   └─> Return success

3. User views News Feed
   └─> GET /backend/api/news.php
       └─> Fetch all news from database
           └─> Search/filter on frontend (client-side)
               └─> Render with pagination (3 items per page)
```

---

## Key Features

### ✅ Authentication & Authorization
- User registration with email & password
- Secure login with bcrypt password hashing
- Session-based authentication using PHP cookies
- Role-based access control (Admin / Student)
- Protected routes (dashboard requires authentication)
- Automatic session check on page load

### ✅ News CRUD Operations
- **Create:** Authenticated users can publish news
- **Read:** Browse news feed with pagination
- **Update:** Edit your own news (admins can edit any)
- **Delete:** Remove your own news with confirmation (admins can delete any)
- **Search:** Real-time search by news title
- **Pagination:** 3 items per page with Previous/Next controls

### ✅ Security
- SQL Injection Prevention (Prepared Statements)
- XSS Protection (HTML entity escaping)
- Password Hashing (bcrypt with PASSWORD_DEFAULT)
- Session Management (Secure cookies with credentials)
- Input Validation (Frontend + Backend dual validation)
- Authorization Checks (Role-based access control)

### ✅ User Experience
- Clean, responsive UI
- Real-time search functionality
- Loading indicators
- Error messages (helpful but not information-leaking)
- Form validation with instant feedback
- Confirmation dialogs for destructive actions

---

## Tech Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Frontend** | HTML5 | Semantic markup |
| | CSS3 | Responsive design |
| | JavaScript (ES6+) | Dynamic interactivity, API calls |
| **Backend** | PHP 7+ | Server-side logic |
| | PDO (PHP Data Objects) | Database abstraction |
| **Database** | MySQL / MariaDB | Data persistence |
| **Security** | bcrypt | Password hashing |
| | Prepared Statements | SQL safety |
| | Sessions | User authentication |

---

## Project Structure

```
Basic-Website/
├── README.md                               # Project overview
├── README_FULL.md                          # Comprehensive documentation
├── Plan.txt                                # Development roadmap
├── db/
│   └── init.sql                            # Database schema
├── campus-hub/
│   ├── frontend/
│   │   ├── login.html                      # Login form with validation
│   │   ├── register.html                   # Registration form
│   │   ├── dashboard.html                  # News feed w/ search & pagination
│   │   └── style.css                       # Styling
│   └── backend/
│       ├── config/
│       │   └── db.php                      # Database connection
│       ├── auth/
│       │   ├── register.php                # User registration
│       │   ├── login.php                   # User authentication
│       │   ├── check.php                   # Session verification
│       │   ├── logout.php                  # Session cleanup
│       │   └── auth_utils.php              # Reusable auth functions
│       └── api/
│           └── news.php                    # News CRUD API
├── DAY1_PROGRESS.md
├── DAY3_PROGRESS.md
├── DAY5_PROGRESS.md
└── DAY6_PROGRESS.md
```

---

## Setup Instructions

### Prerequisites
- XAMPP (Apache + MySQL)
- PHP 7.0+
- MySQL 5.6+ or MariaDB

### Installation

1. **Start Services**
   ```bash
   # Start Apache and MySQL in XAMPP Control Panel
   ```

2. **Import Database Schema**
   ```bash
   mysql -u root < db/init.sql
   ```

3. **Verify Database Connection**
   - Edit `campus-hub/backend/config/db.php`
   - Ensure credentials match your MySQL setup

4. **Access the Application**
   ```
   Login:    http://localhost/Basic-Website/campus-hub/frontend/login.html
   Register: http://localhost/Basic-Website/campus-hub/frontend/register.html
   Dashboard: http://localhost/Basic-Website/campus-hub/frontend/dashboard.html
   ```

5. **Create Test Account**
   - Email: `test@example.com`
   - Password: `password123`
   - Role: `User` or `Admin`

---

## API Endpoints Summary

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/auth/register.php` | No | Create account |
| POST | `/auth/login.php` | No | Authenticate user |
| GET | `/auth/check.php` | No | Verify session |
| POST | `/auth/logout.php` | Yes | End session |
| GET | `/api/news.php` | No | List news |
| GET | `/api/news.php?id=1` | No | Get single news |
| POST | `/api/news.php` | Yes | Create news |
| PUT | `/api/news.php?id=1` | Yes | Update news |
| DELETE | `/api/news.php?id=1` | Yes | Delete news |

See README_FULL.md for detailed request/response examples for each endpoint.

---

## Security Features

### 1. SQL Injection Prevention
- **Prepared Statements** - Data is passed separately from SQL code
- **PDO Binding** - Values are escaped automatically

### 2. XSS Prevention
- **HTML Escaping** - Special characters converted to HTML entities
- **Example:** `<script>` becomes `&lt;script&gt;`

### 3. Password Security
- **bcrypt Hashing** - Industry standard with salt
- **Automatic Upgrades** - PASSWORD_DEFAULT supports algorithm modernization

### 4. Authentication
- **Session-based** - PHP sessions with secure cookies
- **HttpOnly Flag** - Prevents JavaScript access to session cookie

### 5. Authorization
- **Role-based Access** - Admin vs Student permissions
- **Owner Checks** - Users can only modify their own content

### 6. Input Validation
- **Frontend** - Real-time validation, user-friendly
- **Backend** - Strict validation, prevents bypasses

---

## Learning Outcomes

### Backend Development
✅ PDO database connections with prepared statements
✅ Password hashing and verification
✅ Session management
✅ RESTful API design
✅ Error handling with try/catch
✅ JSON processing

### Frontend Development
✅ Async/await patterns
✅ DOM manipulation and dynamic rendering
✅ Form validation
✅ Event delegation
✅ Client-side search and filtering
✅ Pagination logic

### Security
✅ SQL injection prevention
✅ XSS attack prevention
✅ Secure password storage
✅ OWASP best practices
✅ Dual-layer validation

---

## Testing Checklist

- [ ] Register and login successfully
- [ ] Logout clears session
- [ ] Create, read, update, delete news
- [ ] Search news by title
- [ ] Pagination works (3 items per page)
- [ ] Can't edit other users' news
- [ ] Input validation works (too short/long)
- [ ] SQL injection attempt fails gracefully
- [ ] XSS payload stored as text, not executed

---

## Future Enhancements

- [ ] Email verification
- [ ] Password reset
- [ ] Comments on news
- [ ] User profiles
- [ ] Admin moderation panel
- [ ] Notification system
- [ ] Rate limiting
- [ ] HTTPS enforcement
- [ ] Docker support
- [ ] Analytics

---

**Built with ❤️ during Spring Holiday 2025-2026**
