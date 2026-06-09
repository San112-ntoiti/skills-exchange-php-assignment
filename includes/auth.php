<?php
session_start();

// Function to register a new user
function register_user($username, $email, $password, $conn) {
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters'];
    }
    
    // Check if email already exists
    $email = sanitize_input($email, $conn);
    $check_query = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Sanitize inputs
    $username = sanitize_input($username, $conn);
    $email = sanitize_input($email, $conn);
    
    // Insert user into database
    $insert_query = "INSERT INTO users (username, email, password, created_at) 
                     VALUES ('$username', '$email', '$hashed_password', NOW())";
    
    if ($conn->query($insert_query) === TRUE) {
        return ['success' => true, 'message' => 'Registration successful. Please login.'];
    } else {
        return ['success' => false, 'message' => 'Error: ' . $conn->error];
    }
}

// Function to login user
function login_user($email, $password, $conn) {
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    $email = sanitize_input($email, $conn);
    
    // Query user by email
    $query = "SELECT id, username, password FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 0) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        
        return ['success' => true, 'message' => 'Login successful'];
    } else {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to get current user ID
function get_current_user_id() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Function to logout user
function logout_user() {
    session_destroy();
    return ['success' => true, 'message' => 'Logged out successfully'];
}

// Function to require login (redirect if not logged in)
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

// Function to validate skill input
function validate_skill_input($title, $category, $description, $skill_level) {
    $errors = [];
    
    if (empty($title) || strlen($title) < 3) {
        $errors[] = 'Title must be at least 3 characters';
    }
    
    if (empty($category)) {
        $errors[] = 'Category is required';
    }
    
    if (empty($description) || strlen($description) < 10) {
        $errors[] = 'Description must be at least 10 characters';
    }
    
    $valid_levels = ['beginner', 'intermediate', 'advanced'];
    if (!in_array($skill_level, $valid_levels)) {
        $errors[] = 'Invalid skill level';
    }
    
    return $errors;
}

// Function to validate request input
function validate_request_input($to_user_id, $skill_id, $desired_skill) {
    $errors = [];
    
    if (empty($to_user_id) || !is_numeric($to_user_id)) {
        $errors[] = 'Invalid user selected';
    }
    
    if (empty($skill_id) || !is_numeric($skill_id)) {
        $errors[] = 'Invalid skill selected';
    }
    
    if (empty($desired_skill) || strlen($desired_skill) < 3) {
        $errors[] = 'Desired skill must be at least 3 characters';
    }
    
    return $errors;
}
?>
