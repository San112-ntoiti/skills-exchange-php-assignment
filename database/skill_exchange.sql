-- Skill Exchange Database Schema
-- Member 3: Database Setup

CREATE DATABASE IF NOT EXISTS skill_exchange;
USE skill_exchange;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Skills Table
CREATE TABLE IF NOT EXISTS skills (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Requests Table (for skill exchange requests)
CREATE TABLE IF NOT EXISTS requests (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data (Optional)
INSERT INTO users (username, email, password, bio) VALUES 
('john_doe', 'john@example.com', '$2y$10$8V6DXfDWXj.8bV.qxIf1OOYwxX9Z1J8Z6Y6Y6Y6Y6Y6Y6Y', 'Web developer and designer'),
('jane_smith', 'jane@example.com', '$2y$10$8V6DXfDWXj.8bV.qxIf1OOYwxX9Z1J8Z6Y6Y6Y6Y6Y6Y6Y', 'Digital marketer and content creator');

INSERT INTO skills (user_id, title, category, description, skill_level, keywords) VALUES 
(1, 'Web Development', 'Technology', 'Proficient in PHP, JavaScript, and MySQL databases', 'advanced', 'PHP,JavaScript,MySQL'),
(1, 'UI/UX Design', 'Design', 'Creative design skills with attention to user experience', 'intermediate', 'Design,UI,UX'),
(2, 'Content Writing', 'Marketing', 'Professional content writing for blogs and social media', 'advanced', 'Writing,Content,Marketing'),
(2, 'Social Media Management', 'Marketing', 'Experienced in managing multiple social media platforms', 'intermediate', 'Social,Media,Marketing');
