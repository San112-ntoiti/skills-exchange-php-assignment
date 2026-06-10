# Quick Start Guide - Skill Exchange Application

## 5-Minute Setup

### What You Need
- PHP 7.4+
- MySQL 5.7+
- A web browser

### Step 1: Create Database (2 minutes)

Open MySQL client and run:

```sql
-- Copy the entire content of database/skill_exchange.sql
-- Paste and execute it in your MySQL client
-- OR run from command line:
mysql -u root -p < database/skill_exchange.sql
```

### Step 2: Update Database Credentials (1 minute)

Edit `includes/db.php`:

```php
define('DB_HOST', 'localhost');     // Change if needed
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASSWORD', '');          // Your MySQL password
define('DB_NAME', 'skill_exchange');
```

### Step 3: Start Server (1 minute)

**Using PHP Built-in Server:**
```bash
cd /path/to/assign
php -S localhost:8000
```

**Or with Apache/XAMPP/WAMP** - place in htdocs/www folder

### Step 4: Test It (1 minute)

1. Go to `http://localhost:8000`
2. Click "Create one" to register
3. Use test credentials:
   - Email: test@example.com
   - Password: password123
4. Login and explore!

---

## What You Can Do

### New User
1. Register with email and password
2. Navigate to Dashboard
3. Add your first skill
4. Search for skills from others
5. Request a skill exchange

### Search
1. Use search bar on dashboard
2. Find skills by title or category
3. View who offers the skill
4. Send request to exchange

### Manage Skills
1. View your skills on dashboard
2. Click Edit to update skill details
3. Click Delete to remove skill
4. Add keywords to improve discoverability

---

## Sample Test Data

The database includes 2 test users with 4 skills:

**User 1**: john_doe  
- Skills: Web Development, UI/UX Design

**User 2**: jane_smith  
- Skills: Content Writing, Social Media Management

**Note**: Sample password hashes are included but can't be used directly. Register new accounts for testing.

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Connection failed" | Check database credentials in db.php |
| "Access Denied" | Verify MySQL username/password |
| "Port 8000 in use" | Use different port: `php -S localhost:8080` |
| Can't see styles | Clear browser cache or force refresh (Ctrl+F5) |
| Can't edit skills | Make sure you're logged in as the skill owner |

---

## Project Structure

```
assign/
├── Database Files
│   └── /database/skill_exchange.sql
├── Backend Code
│   ├── /includes/db.php (database functions)
│   ├── /includes/auth.php (authentication)
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── add_skill.php
│   ├── edit_skill.php
│   ├── delete_skill.php
│   ├── request.php
│   ├── search.php
│   └── logout.php
├── Documentation
│   ├── BACKEND_DOCUMENTATION.md (detailed technical docs)
│   ├── PROJECT_REPORT.md (full project overview)
│   └── QUICK_START_GUIDE.md (this file)
└── Other Components
    ├── /css/style.css (Mike)
    ├── /js/validation.js (Hianyu)
    └── index.php (Mike)
```

---

## Key Technologies

| Component | Technology |
|-----------|-----------|
| Backend | PHP 7.4+ |
| Database | MySQL 5.7+ |
| Frontend | HTML5, CSS3, JavaScript |
| Framework | Bootstrap 5 |
| Icons | Font Awesome 6 |

---

## Member Contributions

- **Mike**: Frontend/UI design, homepage, navigation, Bootstrap styling, shared header/footer.
- **Newton**: Backend logic, user authentication, session management, skill CRUD operations, request workflows.
- **Hianyu**: Database schema design, SQL import data, AJAX search endpoint, client-side validation, JavaScript support.

---

## Common Tasks

### Register New Account
1. Go to `/register.php`
2. Enter username, email, password
3. Click Register
4. Login with new credentials

### Add Skill
1. Click "Add Skill" button
2. Fill in title (min 3 chars)
3. Select category
4. Write description (min 10 chars)
5. Choose skill level
6. Add optional keywords
7. Submit

### Edit Skill
1. Go to Dashboard
2. Click Edit on your skill
3. Make changes
4. Click Update

### Delete Skill
1. Go to Dashboard
2. Click Delete on your skill
3. Confirm deletion

### Search Skills
1. Use search bar on Dashboard
2. Type skill name (e.g., "web development")
3. View results
4. Click "Request Skill" to exchange

### Request Skill Exchange
1. Find skill on Dashboard search
2. Click "Request Skill"
3. Enter skill you can offer
4. Add optional message
5. Submit request

---

## Important Files for Understanding

| File | Purpose | Read First |
|------|---------|----------|
| `database/skill_exchange.sql` | Database schema | Start here to understand data structure |
| `includes/db.php` | Database operations | Understand how data is accessed |
| `includes/auth.php` | Authentication logic | See how users are validated |
| `login.php` | Login page | Entry point for users |
| `dashboard.php` | Main interface | Core application features |
| `documentation/PROJECT_REPORT.md` | Full documentation | Complete project details |

---

## Performance Tips

For better performance:

1. **Clear browser cache**: `Ctrl+Shift+Delete`
2. **Restart PHP server**: Stop and restart `php -S`
3. **Check database connection**: Verify no connection errors
4. **Check for errors**: Look at browser console (`F12`)
5. **Test one feature at a time**: Helps isolate issues

---

## Next Steps

After getting the application running:

1. **Explore the code**: Read through files to understand implementation
2. **Add test data**: Create multiple test accounts and skills
3. **Test features**: Try all functionality (login, add, edit, search, delete, request)
4. **Review documentation**: Read PROJECT_REPORT.md for detailed explanations
5. **Extend features**: Add new functionality as needed

---

## Support

For issues:

1. Check the Troubleshooting section above
2. Review BACKEND_DOCUMENTATION.md for technical details
3. Check browser console for JavaScript errors (F12)
4. Verify database is running and accessible
5. Check file permissions on project folder

---

**For complete documentation, see: PROJECT_REPORT.md and BACKEND_DOCUMENTATION.md**

Happy exploring! 🚀
