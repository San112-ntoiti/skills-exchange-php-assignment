# Skill Exchange Web Application - Backend & Database Documentation

## Project Overview

**Skill Exchange** is a dynamic web application that enables users to discover, share, and exchange skills with other community members. It facilitates peer-to-peer learning by allowing users to post their skills and request to learn from others.

---

## Member Contributions
- **Mike**: Frontend pages and user interface components.
- **Newton**: Backend authentication, session management, skill CRUD, request handling.
- **Hianyu**: Database integration, search endpoint, JavaScript validation and AJAX.

---

## Distinctiveness and Complexity

### Project Uniqueness
The Skill Exchange application stands out as a **peer-to-peer skill marketplace** that promotes community learning and collaboration. Unlike traditional learning platforms that position instructors as authorities, this application democratizes knowledge sharing by treating all users equally as both teachers and learners.

### Key Distinctive Features:
1. **Bidirectional Skill Exchange**: Users can both offer and request skills, creating a two-way learning relationship
2. **Advanced Search Functionality**: Full-text search with filtering by category and skill level
3. **Request Management System**: Tracks pending, accepted, and completed skill exchanges
4. **Dynamic User Profiles**: Each user maintains their own portfolio of skills with varying expertise levels
5. **Real-time Notification System**: Users receive requests and can manage their exchanges

### Complexity Involved:
1. **Database Relations**: Complex relationships between users, skills, and requests with proper foreign keys
2. **Session Management**: Secure session handling with password hashing (bcrypt)
3. **Form Validation**: Both server-side and client-side validation for data integrity
4. **CRUD Operations**: Full Create, Read, Update, Delete functionality for skills
5. **Search Algorithm**: Implements FULLTEXT search for efficient skill discovery
6. **Authorization Checks**: Ensures users can only edit/delete their own skills
7. **Data Sanitization**: SQL injection prevention through input sanitization

---

## Design Approach

### Architecture Pattern: Model-View-Controller (MVC)
We followed a simplified MVC pattern to separate concerns:
- **Model**: Database operations and data validation (`db.php`, `skill_exchange.sql`)
- **View**: HTML forms and display pages (`.php` files with embedded HTML)
- **Controller**: Business logic in authentication and routing (`auth.php`)

### Why This Approach:
1. **Maintainability**: Clear separation of concerns makes code easier to understand and modify
2. **Reusability**: Helper functions in `db.php` and `auth.php` can be used across multiple pages
3. **Security**: Centralized input validation and sanitization
4. **Scalability**: Easy to add new features without disrupting existing code

### Key Design Decisions:
1. **Session-Based Authentication**: Uses PHP sessions rather than tokens for simplicity
2. **Server-Side Form Validation**: Primary validation happens server-side for security
3. **Client-Side Validation**: JavaScript validates input before submission for UX
4. **Database Normalization**: Tables are normalized to 3NF to prevent data redundancy
5. **Bootstrap Framework**: Ensures responsive design without custom media queries

---

## Database Schema Documentation

### Database Name: `skill_exchange`

#### Table 1: `users`
Stores user account information and profiles.

| Column | Type | Constraints | Description |
|--------|------|-----------|---|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| username | VARCHAR(100) | NOT NULL, UNIQUE | User's display name |
| email | VARCHAR(100) | NOT NULL, UNIQUE | User's email (login credential) |
| password | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| bio | TEXT | NULL | User's profile biography |
| profile_image | VARCHAR(255) | NULL | Path to profile image |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation time |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last profile update time |

**Indexes**: 
- Primary key on `id`
- Unique constraint on `username` and `email` for quick lookups

---

#### Table 2: `skills`
Stores skills offered by users.

| Column | Type | Constraints | Description |
|--------|------|-----------|---|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique skill identifier |
| user_id | INT | FOREIGN KEY (users.id) | Owner of the skill |
| title | VARCHAR(150) | NOT NULL | Skill name/title |
| category | VARCHAR(100) | NOT NULL | Skill category (Technology, Design, etc.) |
| description | TEXT | NOT NULL | Detailed skill description |
| skill_level | ENUM('beginner', 'intermediate', 'advanced') | NOT NULL | Proficiency level |
| keywords | VARCHAR(255) | NULL | Searchable keywords |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Skill creation time |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last skill update time |

**Indexes**:
- Primary key on `id`
- Foreign key on `user_id` with CASCADE delete
- Regular index on `user_id` for fast filtering
- Regular index on `category` for category-based search
- FULLTEXT index on `title` for full-text search

---

#### Table 3: `requests`
Tracks skill exchange requests between users.

| Column | Type | Constraints | Description |
|--------|------|-----------|---|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique request identifier |
| from_user_id | INT | FOREIGN KEY (users.id) | User requesting the skill |
| to_user_id | INT | FOREIGN KEY (users.id) | User offering the skill |
| skill_id | INT | FOREIGN KEY (skills.id) | Skill being requested |
| desired_skill | VARCHAR(150) | NOT NULL | Skill the requester offers |
| message | TEXT | NULL | Additional request message |
| status | ENUM('pending', 'accepted', 'rejected', 'completed') | DEFAULT 'pending' | Request status |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Request creation time |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last status update |

**Indexes**:
- Primary key on `id`
- Foreign keys on `from_user_id`, `to_user_id`, `skill_id` with CASCADE delete
- Regular index on `to_user_id` for filtering requests for a user
- Regular index on `status` for status-based filtering

---

## File Structure and Contents

### Core PHP Files

#### `/includes/db.php`
**Purpose**: Database connection and helper functions  
**Responsibility**: Hianyu (Database Setup)

**Functions**:
- `sanitize_input($data, $conn)` - Prevents SQL injection by escaping inputs
- `user_exists($email, $conn)` - Checks if email is already registered
- `get_user_by_id($user_id, $conn)` - Retrieves user profile data
- `get_user_skills($user_id, $conn)` - Fetches all skills of a user
- `get_skill_by_id($skill_id, $conn)` - Retrieves specific skill details
- `search_skills($search_term, $conn)` - Performs full-text search on skills
- `get_all_users($current_user_id, $conn)` - Lists other users for networking
- `request_exists($from_user_id, $to_user_id, $skill_id, $conn)` - Checks for duplicate requests

---

#### `/includes/auth.php`
**Purpose**: Authentication and validation functions  
**Responsibility**: Newton (Login System & Sessions)

**Functions**:
- `register_user($username, $email, $password, $conn)` - Creates new user account
- `login_user($email, $password, $conn)` - Authenticates user login
- `is_logged_in()` - Checks if current session is authenticated
- `get_current_user_id()` - Returns logged-in user's ID
- `logout_user()` - Destroys user session
- `require_login()` - Redirects to login if not authenticated
- `validate_skill_input($title, $category, $description, $skill_level)` - Validates skill form data
- `validate_request_input($to_user_id, $skill_id, $desired_skill)` - Validates request form data

---

### Frontend Pages

#### `login.php`
**Purpose**: User login interface  
**Responsibility**: Newton  

**Features**:
- Email and password form fields
- Session validation to redirect logged-in users
- Error messaging for invalid credentials
- Link to registration page
- Responsive Bootstrap design
- Form submission via POST

---

#### `register.php`
**Purpose**: User registration interface  
**Responsibility**: Newton

**Features**:
- Username, email, and password input fields
- Password confirmation validation
- Client and server-side validation
- Bcrypt password hashing
- Duplicate email checking
- Success/error messaging
- Link to login page
- Mobile-responsive design

---

#### `dashboard.php`
**Purpose**: Main user interface and hub  
**Responsibility**: Newton (Display) & Hianyu (Search)

**Features**:
- Navigation bar with logout
- Search skills by title or category (FULLTEXT search)
- Display user's own skills
- Edit and delete buttons for own skills
- Request skill button for other users' skills
- Skill level badges (Beginner/Intermediate/Advanced)
- Empty state messaging
- Responsive grid layout

---

#### `add_skill.php`
**Purpose**: Form to create new skills  
**Responsibility**: Newton (CRUD Create) & Hianyu (Validation)

**Features**:
- Skill title input (min 3 chars)
- Category dropdown (Technology, Business, Design, Marketing, Languages, Personal, Other)
- Description textarea (min 10 chars)
- Skill level selector
- Optional keywords field
- Server-side validation
- Success/error messaging
- Back to dashboard link
- Responsive form layout

---

#### `edit_skill.php`
**Purpose**: Form to modify existing skills  
**Responsibility**: Newton (CRUD Update) & Hianyu (Validation)

**Features**:
- Pre-populated form with current skill data
- All validation same as add_skill.php
- Ownership verification (only can edit own skills)
- Update confirmation messaging
- Same responsive design as add_skill.php
- Back to dashboard option

---

#### `delete_skill.php`
**Purpose**: Deletes user's skill from database  
**Responsibility**: Newton (CRUD Delete)

**Features**:
- Verifies user ownership before deletion
- Removes skill and cascades to delete related requests
- Redirects back to dashboard with confirmation
- Simple no-UI endpoint (redirect-based)

---

#### `request.php`
**Purpose**: Interface to request skill exchange  
**Responsibility**: Newton (CRUD Create) & Hianyu (Validation)

**Features**:
- Displays skill information (read-only)
- Shows target user who offers the skill
- Form to specify desired skill (min 3 chars)
- Optional message field
- Duplicate request prevention
- Input validation
- Bootstrap styling with skill info panel
- Back to dashboard link

---

#### `logout.php`
**Purpose**: Ends user session  
**Responsibility**: Newton (Session Management)

**Features**:
- Destroys PHP session
- Clears all session variables
- Redirects to login page
- Single-purpose endpoint

---

### Database File

#### `/database/skill_exchange.sql`
**Purpose**: Database schema and initial setup  
**Responsibility**: Hianyu (Database Setup)

**Contents**:
- CREATE DATABASE statement
- CREATE TABLE for users
- CREATE TABLE for skills
- CREATE TABLE for requests
- Foreign key constraints
- Indexes and FULLTEXT indexes
- Sample data for testing

---

## How to Run Your Application

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB database server
- Web server (Apache/Nginx)
- Modern web browser

### Step-by-Step Setup

#### 1. Database Setup
```sql
-- In your MySQL client, run:
source /path/to/database/skill_exchange.sql;
```

Or copy-paste the contents of `skill_exchange.sql` into MySQL Workbench or PHPMyAdmin.

#### 2. Configure Database Connection
Edit `includes/db.php`:
```php
define('DB_HOST', 'localhost');    // Your database host
define('DB_USER', 'root');         // Your database user
define('DB_PASSWORD', '');         // Your database password
define('DB_NAME', 'skill_exchange');
```

#### 3. Local Development Setup
**Option A: Using PHP Built-in Server**
```bash
cd /path/to/project
php -S localhost:8000
```
Then open: `http://localhost:8000`

**Option B: Using Apache with XAMPP/WAMP**
1. Place project folder in `htdocs` (XAMPP) or `www` (WAMP)
2. Start Apache and MySQL services
3. Access via: `http://localhost/your_project_folder`

#### 4. Initial Testing
1. Navigate to `http://localhost:8000/register.php`
2. Create a test account
3. Log in with your credentials
4. Add a test skill
5. Try searching for skills
6. Test the edit/delete functionality

### Application Flow

```
┌─────────────────────────────────────────────────────────────┐
│  Unauthenticated User                                       │
├─────────────────────────────────────────────────────────────┤
│  ↓                                                           │
│  login.php ──→ (valid credentials) ──→ session set          │
│  register.php ──→ (new account) ──→ redirects to login      │
└──────────────────────────────────────┬──────────────────────┘
                                       ↓
┌─────────────────────────────────────────────────────────────┐
│  Authenticated User (Session Active)                         │
├─────────────────────────────────────────────────────────────┤
│  dashboard.php ──→ Main Hub                                  │
│  ├─→ View own skills                                         │
│  ├─→ Search for skills (search_skills)                      │
│  ├─→ Edit own skills (edit_skill.php)                       │
│  ├─→ Delete own skills (delete_skill.php)                   │
│  ├─→ Add new skills (add_skill.php)                         │
│  └─→ Request skill exchange (request.php)                   │
│  logout.php ──→ Session destroyed, redirects to login       │
└─────────────────────────────────────────────────────────────┘
```

---

## Additional Information

### Security Features
1. **Password Hashing**: Uses bcrypt (`PASSWORD_BCRYPT`) for secure password storage
2. **SQL Injection Prevention**: Input sanitization with `real_escape_string()`
3. **Session Security**: Uses PHP's built-in session management
4. **Authorization**: Server-side checks ensure users can only modify their own data
5. **HTML Escaping**: Output escaped with `htmlspecialchars()` to prevent XSS

### Error Handling
- Database connection errors are caught and displayed
- Form validation provides user-friendly error messages
- Ownership verification prevents unauthorized access
- Redirect-based error handling for critical operations

### Performance Optimizations
1. **Indexes**: Strategic indexing on foreign keys and search fields
2. **FULLTEXT Search**: Efficient full-text search on skill titles
3. **Lazy Loading**: User data and skills loaded on-demand
4. **Connection Management**: Proper connection closing to prevent leaks

### Mobile Responsiveness
- Bootstrap 5 responsive grid system
- Media queries for smaller screens
- Touch-friendly button sizes
- Flexible layouts that adapt to viewport

### Validation Rules
**Skills**:
- Title: 3-150 characters
- Description: 10+ characters
- Category: Must be from predefined list
- Skill Level: beginner, intermediate, or advanced

**Users**:
- Username: 3-100 characters, unique
- Email: Valid email format, unique
- Password: Minimum 6 characters, bcrypt hashed

**Requests**:
- Desired Skill: 3+ characters
- No duplicate pending requests for same skill from same user

---

## Technology Stack

| Component | Technology |
|-----------|-----------|
| Backend | PHP 7.4+ |
| Database | MySQL 5.7+ / MariaDB 10.2+ |
| Frontend Framework | Bootstrap 5.1.3 |
| Icons | Font Awesome 6.0 |
| Form Validation | JavaScript (ES6) |
| Server | Apache/Nginx |

---

## Future Enhancement Possibilities

1. **AJAX Implementation**: Real-time search without page reload
2. **Email Notifications**: Notify users when they receive requests
3. **Messaging System**: Chat between users for negotiation
4. **Rating System**: Rate skill exchanges and user reviews
5. **Skill Categories**: More granular categorization
6. **API Layer**: RESTful API for mobile apps
7. **Advanced Analytics**: Track skill exchange statistics
8. **Two-Factor Authentication**: Enhanced security
9. **Social Features**: Follow users, skill recommendations
10. **Skill Verification**: Community ratings for skill credibility

---

## Troubleshooting Guide

### Problem: "Connection failed"
**Solution**: Check database credentials in `db.php` match your MySQL setup

### Problem: "Email already registered"
**Solution**: Use a different email or clear sample data from database

### Problem: Can't edit/delete skills
**Solution**: Ensure you're logged in as the skill owner

### Problem: Search returns no results
**Solution**: Make sure FULLTEXT index is created on skills table

### Problem: Session issues on localhost
**Solution**: Check PHP session.save_path is writable

---

## Member Responsibilities Summary

### Newton - Backend/PHP
✅ **Completed**:
- Login system with session management
- User registration with validation
- CRUD operations for skills (Create, Read, Update, Delete)
- Logout functionality
- Dashboard display
- Request management form

**Files Created**:
- `login.php`
- `register.php`
- `dashboard.php`
- `add_skill.php`
- `edit_skill.php`
- `delete_skill.php`
- `request.php`
- `logout.php`
- `includes/auth.php`

### Hianyu - Database & JavaScript
✅ **Completed**:
- Database schema design with 3 tables
- Proper indexing for performance
- Input validation functions
- Search functionality with FULLTEXT
- AJAX-ready architecture
- Sample database data

**Files Created**:
- `database/skill_exchange.sql`
- `includes/db.php`
- `search.php`
- `js/validation.js`
- Database helper functions
- Validation functions

---

## Conclusion

The Skill Exchange application successfully demonstrates:
- Full-stack web development using PHP and MySQL
- Proper database design with relationships and constraints
- Secure authentication and session management
- Complete CRUD operations
- Advanced search functionality
- Responsive mobile-friendly design
- Input validation and security best practices

This application provides a solid foundation for peer-to-peer skill sharing and can be extended with additional features as needed.

---

**Documentation Version**: 1.0  
**Last Updated**: May 23, 2026  
**Project Status**: Complete ✅
