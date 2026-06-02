<?php
/**
 * Logout Page
 * Member 2: Session Management - Logout
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Logout user
logout_user();

// Redirect to login page
header('Location: login.php');
exit();
?>
