# Campus Hub - Student News Platform

*A full-stack web application built over 7 days with heavy reliance on AI assistance - a case study in learning through AI collaboration and recognizing where skill gaps lie.*

---

## 🎯 My Learning Journey (The Honest Version)

I started this project uncertain and increasingly relied on AI to do the actual work. Over 7 days:

- **Days 1-2:** Set up project structure, but AI designed the database schema and wrote most API code
- **Days 3-4:** Attempted authentication, but AI implemented session handling and password hashing
- **Days 5-6:** AI added pagination and search features; I mostly watched and tested
- **Day 7:** Used AI to write comprehensive documentation about code I didn't fully create

**The Reality:** I didn't master backend development, frontend architecture, or web security. I *observed* them being implemented. When I tried coding features myself, I got stuck frequently and turned to AI. By the end, I had a working application but limited independent capability to build similar projects from scratch.

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

## 💡 What I Actually Learned (If Anything)

### Knowledge Gained
✅ Basic understanding that prepared statements prevent SQL injection (but couldn't write one from scratch)
✅ Aware bcrypt exists and is better than plaintext passwords (but couldn't implement it)
✅ Understand pagination exists and how to use it
✅ Know sessions store user state (but implementation details are fuzzy)
✅ Recognize XSS is risky (but wouldn't catch it in a code review)
✅ How to optimize queries or handle database errors
✅ How to debug authentication issues independently

### What I Don't Actually Know
❌ How to structure a PDO connection without help
❌ How password hashing actually works mathematically
❌ Frontend state management beyond copy-pasting
❌ How to architect an app from requirements

### The Uncomfortable Truth
This project looks professional because AI wrote most of it. If given the task again with no AI help, I could probably:
- Create the database schema (this part made sense)
- Write basic HTML forms
- Maybe figure out some PHP syntax
- Build the authentication system
- Debug issues that arise

But I couldn't:
- Create secure APIs
- Implement complex features

**This is valuable to know about myself.** I can recognize good code when I see it, but I can't independently create it yet.

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

---

## 🤔 Reflection

This project taught me something more valuable than code: **the difference between understanding something and being able to do it.**

I can look at prepared statements and understand why they matter. But I couldn't write one before having the problem solved for me. I recognize that bcrypt is better than MD5. But I couldn't implement it if I needed to.

**The uncomfortable realization:**
- Having a working app ≠ Being able to build apps
- Copying code ≠ Understanding code  
- Following along ≠ Learning

**What this actually was:**
- A demonstration of what's possible with modern AI tools
- A portfolio piece that looks good but doesn't reflect my actual skill level
- A reality check on where I need to focus next

**What I should do next:**
- Pick ONE feature and build it myself, no AI help, until I actually understand it
- Read other people's code without asking AI to explain every line
- Struggle with problems before asking for solutions
- Write code that fails, debug it, understand why

**Why am I being honest?**
Claims of expertise I don't have would set me up for failure in real projects. Better to know my actual capabilities now and build from there than pretend I've mastered full-stack development in a week.

---

**Built with AI assistance during a 7-day sprint** - A learning experience about learning itself.
