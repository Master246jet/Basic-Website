# Campus Hub - Student News Platform

*A full-stack web application built from scratch during a 7-day learning sprint to master backend development, frontend architecture, and web security.*

---

## 🎯 My Learning Journey

This project was built step-by-step over 7 days, paralleling my progression through core web development concepts:

- **Days 1-2:** Foundation - Setting up the project, designing database schemas, and creating RESTful API endpoints
- **Days 3-4:** Core Features - Implementing secure authentication and connecting the frontend to the backend
- **Days 5-6:** Advanced Features - Building pagination, search functionality, and comprehensive input validation
- **Day 7:** Polish - Adding professional touches, thorough documentation, and security hardening

By the end, I understood how all layers (frontend, backend, database) work together in a real application.

---

## ✨ Key Features Built

### User Authentication & Authorization
- Secure registration and login with bcrypt password hashing
- Session-based authentication using PHP cookies
- Role-based access control (Admin vs Student)
- Protected routes that redirect unauthenticated users

### News Management System
- **Create:** Publish news articles (authenticated users only)
- **Read:** Browse all news with pagination (3 items per page)
- **Update:** Edit your own news (admins can edit any)
- **Delete:** Remove your own news with confirmation (admins can delete any)
- **Search:** Real-time search by title, filters results instantly

### Security & Validation
- **SQL Injection Prevention:** Prepared statements with PDO
- **XSS Prevention:** HTML entity escaping when displaying user content
- **Input Validation:** Dual-layer (frontend for UX, backend for security)
- **Password Security:** bcrypt hashing with automatic salting
- **Authorization Checks:** Users can only modify their own content

---

## 🏗️ Architecture

```
Frontend (HTML/CSS/JavaScript)
    ↓ (fetch API + Session Cookies)
Backend (PHP API Layer)
    ↓ (Prepared Statements)
Database (MySQL)
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript (ES6+) |
| Backend | PHP 7+ with PDO |
| Database | MySQL |
| Security | bcrypt, Sessions, Prepared Statements |

---

## 📁 Project Structure

```
Basic-Website/
├── README.md
├── db/
│   └── init.sql
└── campus-hub/
    ├── frontend/
    │   ├── login.html
    │   ├── register.html
    │   ├── dashboard.html
    │   └── style.css
    └── backend/
        ├── config/db.php
        ├── auth/
        │   ├── register.php
        │   ├── login.php
        │   ├── check.php
        │   ├── logout.php
        │   └── auth_utils.php
        └── api/news.php
```

---

## 🚀 Quick Start

### Setup
1. Start Apache and MySQL in XAMPP
2. Import database: `mysql -u root < db/init.sql`
3. Verify `campus-hub/backend/config/db.php` has correct credentials
4. Open `http://localhost/Basic-Website/campus-hub/frontend/login.html`
5. Register a new account

---

## 💡 What I Learned

### Backend Development
✅ RESTful API design patterns
✅ Secure password hashing with bcrypt
✅ Session management
✅ Input validation and sanitization
✅ Prepared statements for SQL safety
✅ Error handling and HTTP status codes

### Frontend Development
✅ Async/await patterns
✅ DOM manipulation and dynamic rendering
✅ Form validation
✅ Real-time search and filtering
✅ Pagination logic
✅ XSS prevention

### Security
✅ SQL injection prevention
✅ XSS attack prevention
✅ Password security best practices
✅ Authentication vs Authorization
✅ Input/output validation

---

## 🔐 Security Highlights

**SQL Injection Prevention:**
```php
// ✅ SAFE - Prepared statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
```

**XSS Prevention:**
```javascript
// escapeHtml() converts <script> to &lt;script&gt;
// Browser displays as text, not executable code
```

**Password Security:**
```php
// bcrypt hashing with automatic salt
$hash = password_hash($password, PASSWORD_DEFAULT);
password_verify($input, $hash);
```

---

## 🧪 Testing

### Core Functionality ✓
- Register, login, logout
- Create, read, update, delete news
- Search and pagination
- Permission checks (can't edit others' news)

### Security ✓
- Input validation works
- SQL injection handled gracefully
- XSS payload stored as text
- Session security maintained

---

## 📈 Stats

- **Build time:** 7 days
- **Code:** ~2500 lines (including comments)
- **Features:** 10+
- **Security measures:** 6+

---

## 🚀 Next Steps

1. Email verification
2. Password reset
3. Admin dashboard
4. Comments on news
5. User profiles
6. Deploy to production
7. Add HTTPS
8. Rate limiting
9. Monitoring
10. Unit tests

---

## 🤔 Reflection

Web development isn't magic - it's:
1. Take user input (frontend)
2. Validate it (frontend + backend)
3. Process safely (prepared statements)
4. Store securely (hashing)
5. Display safely (escaping)

Every security measure exists because someone learned it the hard way. I learned it right.

---

**Built with ❤️ as a 7-day learning project** 🎯
