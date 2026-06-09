<?php
/**
 * Database Connection Configuration
 * Member 3: Database Setup
 * 
 * This file handles all database connections and queries for the Skill Exchange application
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'Kagombe');
define('DB_PASSWORD', 'ICS_2203');
define('DB_NAME', 'skill_exchange');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Function to sanitize input and prevent SQL injection
function sanitize_input($data, $conn) {
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

// Function to check if user exists by email
function user_exists($email, $conn) {
    $email = sanitize_input($email, $conn);
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Function to get user by ID
function get_user_by_id($user_id, $conn) {
    $user_id = intval($user_id);
    $query = "SELECT id, username, email, bio FROM users WHERE id = $user_id";
    return $conn->query($query);
}

// Function to get all skills of a user
function get_user_skills($user_id, $conn) {
    $user_id = intval($user_id);
    $query = "SELECT * FROM skills WHERE user_id = $user_id ORDER BY created_at DESC";
    return $conn->query($query);
}

// Function to get skill by ID
function get_skill_by_id($skill_id, $conn) {
    $skill_id = intval($skill_id);
    $query = "SELECT * FROM skills WHERE id = $skill_id";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

// Function to search skills
function search_skills($search_term, $conn) {
    $search_term = sanitize_input($search_term, $conn);
    $query = "SELECT s.*, u.username FROM skills s 
              JOIN users u ON s.user_id = u.id 
              WHERE s.title LIKE '%$search_term%' 
                OR s.category LIKE '%$search_term%'
                OR s.description LIKE '%$search_term%'
                OR s.keywords LIKE '%$search_term%'
                OR u.username LIKE '%$search_term%'
              ORDER BY s.created_at DESC";
    return $conn->query($query);
}

// Function to get all users for skill exchange
function get_all_users($current_user_id, $conn) {
    $current_user_id = intval($current_user_id);
    $query = "SELECT id, username, bio FROM users WHERE id != $current_user_id";
    return $conn->query($query);
}

// Function to check if request already exists
function request_exists($from_user_id, $to_user_id, $skill_id, $conn) {
    $from_user_id = intval($from_user_id);
    $to_user_id = intval($to_user_id);
    $skill_id = intval($skill_id);
    $query = "SELECT id FROM requests WHERE from_user_id = $from_user_id 
              AND to_user_id = $to_user_id AND skill_id = $skill_id AND status = 'pending'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}
?>
