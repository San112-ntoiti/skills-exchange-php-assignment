<?php
/**
 * Delete Skill Page
 * Member 2: CRUD Operations - Delete
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require user to be logged in
require_login();

$user_id = get_current_user_id();

// Get skill ID from URL
$skill_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$skill_id) {
    header('Location: dashboard.php');
    exit();
}

// Get skill details to verify ownership
$skill = get_skill_by_id($skill_id, $conn);

if (!$skill || $skill['user_id'] != $user_id) {
    header('Location: dashboard.php');
    exit();
}

// Delete the skill
$delete_query = "DELETE FROM skills WHERE id = $skill_id AND user_id = $user_id";

if ($conn->query($delete_query) === TRUE) {
    $conn->close();
    header('Location: dashboard.php?message=Skill deleted successfully');
    exit();
} else {
    $conn->close();
    header('Location: dashboard.php?error=Error deleting skill');
    exit();
}
?>

