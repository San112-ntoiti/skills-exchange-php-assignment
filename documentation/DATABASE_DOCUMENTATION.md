# Database Documentation - Skill Exchange

## Member Contributions
- **Hianyu**: Designed and created the `skill_exchange` database schema, tables, indexes, and sample data.

## Database Name: `skill_exchange`

This document provides comprehensive information about the database schema, relationships, queries, and best practices.

---

## Table 1: `users`

### Purpose
Stores user account information and profile data.

### Schema

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### Column Details

| Column | Type | Constraints | Description | Example |
|--------|------|-----------|---|---|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for user | 1, 2, 3 |
| `username` | VARCHAR(100) | NOT NULL, UNIQUE | Display name (unique) | "john_doe" |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Email for login (unique) | "john@example.com" |
| `password` | VARCHAR(255) | NOT NULL | Bcrypt hashed password | "$2y$10$8V6DX..." |
| `bio` | TEXT | NULL | User biography/about | "Web developer and designer" |
| `profile_image` | VARCHAR(255) | NULL | Path to profile picture | "uploads/john_doe.jpg" |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation time | 2024-05-23 10:30:45 |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last profile update | 2024-05-23 14:15:30 |

### Indexes

- **PRIMARY KEY**: `id` - Fast user lookup by ID
- **UNIQUE**: `username` - Prevent duplicate usernames
- **UNIQUE**: `email` - Prevent duplicate emails, enables login

### Relationships

- **One-to-Many**: One user has many skills
- **One-to-Many**: One user sends many requests
- **One-to-Many**: One user receives many requests

### Typical Queries

**Register new user:**
```sql
INSERT INTO users (username, email, password, created_at)
VALUES ('john_doe', 'john@example.com', '$2y$10$...', NOW());
```

**Login user:**
```sql
SELECT id, username, password FROM users WHERE email = 'john@example.com';
```

**Get user profile:**
```sql
SELECT id, username, email, bio FROM users WHERE id = 1;
```

**Update user bio:**
```sql
UPDATE users SET bio = 'Updated bio', updated_at = NOW() WHERE id = 1;
```

---

## Table 2: `skills`

### Purpose
Stores skills offered by users in the marketplace.

### Schema

```sql
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    skill_level ENUM('beginner', 'intermediate', 'advanced') NOT NULL DEFAULT 'beginner',
    keywords VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_skills (user_id),
    INDEX idx_category (category),
    FULLTEXT INDEX ft_title (title)
)
```

### Column Details

| Column | Type | Constraints | Description | Example |
|--------|------|-----------|---|---|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique skill ID | 1, 2, 3 |
| `user_id` | INT | FOREIGN KEY, NOT NULL | Owner of skill | 1 |
| `title` | VARCHAR(150) | NOT NULL | Skill name | "Web Development" |
| `category` | VARCHAR(100) | NOT NULL | Skill category | "Technology" |
| `description` | TEXT | NOT NULL | Detailed description | "Proficient in PHP, JavaScript, MySQL..." |
| `skill_level` | ENUM | NOT NULL, DEFAULT 'beginner' | Proficiency level | "advanced" |
| `keywords` | VARCHAR(255) | NULL | Searchable keywords | "PHP,JavaScript,MySQL" |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation time | 2024-05-23 10:30:45 |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last update time | 2024-05-23 12:00:00 |

### Indexes Explained

1. **PRIMARY KEY (id)**: Speeds up skill lookup by ID
2. **FOREIGN KEY (user_id)**: Maintains referential integrity, enables ON DELETE CASCADE
3. **INDEX idx_user_skills**: Speeds up queries filtering by user_id (e.g., "get all skills of user")
4. **INDEX idx_category**: Speeds up queries filtering by category
5. **FULLTEXT INDEX ft_title**: Enables full-text search on skill titles

### Relationships

- **Many-to-One**: Many skills belong to one user
- **One-to-Many**: One skill has many requests

### Typical Queries

**Add new skill:**
```sql
INSERT INTO skills (user_id, title, category, description, skill_level, keywords)
VALUES (1, 'Web Development', 'Technology', 'Description...', 'advanced', 'PHP,JavaScript');
```

**Get all skills of user:**
```sql
SELECT * FROM skills WHERE user_id = 1 ORDER BY created_at DESC;
```

**Full-text search:**
```sql
SELECT s.*, u.username FROM skills s
JOIN users u ON s.user_id = u.id
WHERE MATCH(s.title) AGAINST('web development' IN BOOLEAN MODE)
ORDER BY s.created_at DESC;
```

**Category filter:**
```sql
SELECT * FROM skills WHERE category = 'Technology' ORDER BY created_at DESC;
```

**Update skill:**
```sql
UPDATE skills 
SET title = 'New Title', description = 'New desc', updated_at = NOW()
WHERE id = 1 AND user_id = 1;
```

**Delete skill (cascades to requests):**
```sql
DELETE FROM skills WHERE id = 1 AND user_id = 1;
-- This automatically deletes all requests related to this skill
```

---

## Table 3: `requests`

### Purpose
Tracks skill exchange requests between users.

### Schema

```sql
CREATE TABLE requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    skill_id INT NOT NULL,
    desired_skill VARCHAR(150) NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    INDEX idx_to_user (to_user_id),
    INDEX idx_status (status)
)
```

### Column Details

| Column | Type | Constraints | Description | Example |
|--------|------|-----------|---|---|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique request ID | 1, 2, 3 |
| `from_user_id` | INT | FOREIGN KEY, NOT NULL | User requesting skill | 2 |
| `to_user_id` | INT | FOREIGN KEY, NOT NULL | User offering skill | 1 |
| `skill_id` | INT | FOREIGN KEY, NOT NULL | Skill being requested | 5 |
| `desired_skill` | VARCHAR(150) | NOT NULL | Skill offered in return | "Content Writing" |
| `message` | TEXT | NULL | Additional message | "I'm interested in learning web dev" |
| `status` | ENUM | NOT NULL, DEFAULT 'pending' | Request state | "pending", "accepted" |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Request creation time | 2024-05-23 10:30:45 |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Last status update | 2024-05-23 11:30:00 |

### Status Values

- **pending**: Request sent, awaiting response
- **accepted**: Both parties agreed to exchange
- **rejected**: One party declined the request
- **completed**: Skill exchange was completed

### Indexes Explained

1. **PRIMARY KEY (id)**: Speeds up request lookup by ID
2. **FOREIGN KEY (from_user_id)**: Maintains referential integrity, enables CASCADE delete
3. **FOREIGN KEY (to_user_id)**: Maintains referential integrity, enables CASCADE delete
4. **FOREIGN KEY (skill_id)**: Maintains referential integrity, enables CASCADE delete
5. **INDEX idx_to_user**: Speeds up queries like "get all requests for user" or "get incoming requests"
6. **INDEX idx_status**: Speeds up queries filtering by status (e.g., "get pending requests")

### Relationships

- **Many-to-One (from_user_id)**: Many requests from one user
- **Many-to-One (to_user_id)**: Many requests to one user
- **Many-to-One (skill_id)**: Many requests for one skill

### Typical Queries

**Create request:**
```sql
INSERT INTO requests (from_user_id, to_user_id, skill_id, desired_skill, message)
VALUES (2, 1, 5, 'Content Writing', 'I would love to learn web development');
```

**Get pending requests for user:**
```sql
SELECT r.*, s.title, u.username FROM requests r
JOIN skills s ON r.skill_id = s.id
JOIN users u ON r.from_user_id = u.id
WHERE r.to_user_id = 1 AND r.status = 'pending'
ORDER BY r.created_at DESC;
```

**Update request status:**
```sql
UPDATE requests SET status = 'accepted', updated_at = NOW()
WHERE id = 1 AND to_user_id = 1;
```

**Get requests sent by user:**
```sql
SELECT r.*, s.title, u.username FROM requests r
JOIN skills s ON r.skill_id = s.id
JOIN users u ON r.to_user_id = u.id
WHERE r.from_user_id = 2
ORDER BY r.created_at DESC;
```

**Check for duplicate request:**
```sql
SELECT id FROM requests 
WHERE from_user_id = 2 
AND to_user_id = 1 
AND skill_id = 5 
AND status = 'pending';
```

---

## Data Relationships (Entity-Relationship Diagram)

```
┌──────────────┐
│    USERS     │
├──────────────┤
│ id (PK)      │
│ username     │
│ email        │
│ password     │
│ bio          │
│ profile_img  │
│ created_at   │
│ updated_at   │
└──────┬───────┘
       │ (1)
       │ (one user)
       │
    (many)
       │
       ├─────────────────────┐
       │                     │
       ▼ (1)            (1)  ▼
┌──────────────┐    ┌──────────────┐
│   SKILLS     │    │  REQUESTS    │
├──────────────┤    ├──────────────┤
│ id (PK)      │    │ id (PK)      │
│ user_id (FK) │    │ from_user... │(FK→USERS)
│ title        │    │ to_user...   │(FK→USERS)
│ category     │    │ skill_id     │(FK→SKILLS)
│ description  │    │ desired_skill│
│ skill_level  │    │ message      │
│ keywords     │    │ status       │
│ created_at   │    │ created_at   │
│ updated_at   │    │ updated_at   │
└──────────────┘    └──────────────┘
       ▲ (1)              (many) │
       │                         │
       └─────────────────────────┘
       (one skill in many requests)
```

---

## Sample Data Queries

### Query 1: Find all skills available in Technology category
```sql
SELECT s.id, s.title, s.skill_level, u.username
FROM skills s
JOIN users u ON s.user_id = u.id
WHERE s.category = 'Technology'
ORDER BY s.created_at DESC;
```

### Query 2: Search for "PHP" skill
```sql
SELECT s.*, u.username, u.bio
FROM skills s
JOIN users u ON s.user_id = u.id
WHERE MATCH(s.title) AGAINST('PHP' IN BOOLEAN MODE)
   OR s.keywords LIKE '%PHP%'
ORDER BY MATCH(s.title) AGAINST('PHP' IN BOOLEAN MODE) DESC;
```

### Query 3: Get all incoming requests for user ID 1
```sql
SELECT r.id, r.desired_skill, r.message, r.status,
       u.username AS from_user,
       s.title AS skill_offered,
       s.skill_level
FROM requests r
JOIN users u ON r.from_user_id = u.id
JOIN skills s ON r.skill_id = s.id
WHERE r.to_user_id = 1
ORDER BY r.created_at DESC;
```

### Query 4: Get pending requests with skill exchange potential
```sql
SELECT r.id,
       u1.username AS requester,
       u2.username AS skill_owner,
       s.title AS skill_requested,
       r.desired_skill AS offered_skill,
       r.status
FROM requests r
JOIN users u1 ON r.from_user_id = u1.id
JOIN users u2 ON r.to_user_id = u2.id
JOIN skills s ON r.skill_id = s.id
WHERE r.status = 'pending'
ORDER BY r.created_at DESC;
```

### Query 5: Count skills per user
```sql
SELECT u.username, COUNT(s.id) AS skill_count
FROM users u
LEFT JOIN skills s ON u.id = s.user_id
GROUP BY u.id
ORDER BY skill_count DESC;
```

### Query 6: Get user's request history
```sql
SELECT 
    CASE 
        WHEN from_user_id = 1 THEN 'Sent'
        WHEN to_user_id = 1 THEN 'Received'
    END AS direction,
    r.status,
    COUNT(*) AS count
FROM requests r
WHERE from_user_id = 1 OR to_user_id = 1
GROUP BY direction, r.status;
```

---

## Best Practices

### 1. Always Use Prepared Statements (Recommended Improvement)
Current approach uses `real_escape_string`. Better approach:
```php
$stmt = $conn->prepare("SELECT * FROM skills WHERE id = ?");
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$result = $stmt->get_result();
```

### 2. Add Indexes for Frequently Queried Columns
Current indexes are comprehensive, but additional indexes could be added:
```sql
-- Email index for faster login
CREATE INDEX idx_email ON users(email);

-- Timestamp index for sorting by date
CREATE INDEX idx_created ON skills(created_at DESC);
```

### 3. Use Transactions for Multi-Step Operations
```php
$conn->begin_transaction();
try {
    // Step 1: Update request status
    // Step 2: Create notification
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    throw $e;
}
```

### 4. Archive Old Data
For performance, archive old completed requests:
```sql
CREATE TABLE requests_archived LIKE requests;
INSERT INTO requests_archived 
SELECT * FROM requests 
WHERE status = 'completed' 
AND updated_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

### 5. Regular Backups
```bash
# Backup database
mysqldump -u root -p skill_exchange > backup_skill_exchange.sql

# Restore from backup
mysql -u root -p skill_exchange < backup_skill_exchange.sql
```

---

## Performance Optimization

### Current Optimization Status
✅ **Primary Keys**: All tables have primary keys  
✅ **Foreign Keys**: Proper foreign key constraints  
✅ **Indexes**: Strategic indexes on foreign keys and search columns  
✅ **FULLTEXT**: Full-text search on skill titles  
✅ **Normalization**: Proper 3NF design  

### Potential Improvements

1. **Query Optimization**:
   - Use EXPLAIN to analyze query performance
   - Add SELECT list specificity (don't use *)
   - Consider query caching

2. **Index Optimization**:
   - Monitor unused indexes with `PERFORMANCE_SCHEMA`
   - Add composite indexes for multi-column WHERE clauses

3. **Data Partitioning**:
   - Partition large tables by date for faster queries
   - Archive historical data to separate tables

4. **Connection Pooling**:
   - Implement connection pooling for multiple concurrent users
   - Use persistent connections carefully

---

## Maintenance

### Check Database Integrity
```sql
-- Check for orphaned records
SELECT * FROM requests WHERE skill_id NOT IN (SELECT id FROM skills);

-- Check for duplicate emails
SELECT email, COUNT(*) 
FROM users 
GROUP BY email 
HAVING COUNT(*) > 1;

-- Check for orphaned skills (shouldn't happen with CASCADE)
SELECT * FROM skills WHERE user_id NOT IN (SELECT id FROM users);
```

### Monitor Table Size
```sql
SELECT 
    TABLE_NAME,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size in MB'
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'skill_exchange'
ORDER BY (data_length + index_length) DESC;
```

### Optimize Tables
```sql
-- Optimize table space usage
OPTIMIZE TABLE users;
OPTIMIZE TABLE skills;
OPTIMIZE TABLE requests;

-- Check table for errors
CHECK TABLE users;
```

---

## Backup & Recovery

### Regular Backup Schedule
```bash
# Daily backup script (crontab)
0 2 * * * mysqldump -u root -p'password' skill_exchange > /backups/skill_exchange_$(date +\%Y\%m\%d).sql
```

### Recovery Procedure
1. Stop application
2. Restore backup: `mysql -u root -p skill_exchange < backup.sql`
3. Verify data integrity
4. Restart application

---

## Data Dictionary

### Valid Category Values
- Technology
- Business
- Design
- Marketing
- Languages
- Personal
- Other

### Valid Skill Levels
- beginner
- intermediate
- advanced

### Valid Request Statuses
- pending
- accepted
- rejected
- completed

---

**Database Version**: 1.0  
**Last Updated**: May 23, 2026  
**MySQL Version Tested**: 5.7+, 8.0+  
**InnoDB Storage Engine**: Yes ✅
