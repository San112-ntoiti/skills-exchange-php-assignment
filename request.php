<?php

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require user to be logged in
require_login();

$from_user_id = get_current_user_id();
$message = '';
$message_type = '';

// Get parameters from URL or POST
$skill_id = isset($_GET['skill_id']) ? intval($_GET['skill_id']) : (isset($_POST['skill_id']) ? intval($_POST['skill_id']) : null);
$to_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : (isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : null);

if (!$skill_id || !$to_user_id) {
    header('Location: dashboard.php');
    exit();
}

// Get skill and target user information
$skill = get_skill_by_id($skill_id, $conn);
$target_user_result = get_user_by_id($to_user_id, $conn);
$target_user = $target_user_result->fetch_assoc();

if (!$skill || !$target_user || $to_user_id == $from_user_id) {
    header('Location: dashboard.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $desired_skill = isset($_POST['desired_skill']) ? $_POST['desired_skill'] : '';
    $message_text = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Validate input (Member 3)
    $errors = validate_request_input($to_user_id, $skill_id, $desired_skill);
    
    if (empty($errors)) {
        // Check if request already exists
        if (request_exists($from_user_id, $to_user_id, $skill_id, $conn)) {
            $message = 'You already have a pending request for this skill exchange.';
            $message_type = 'error';
        } else {
            // Sanitize inputs
            $desired_skill = sanitize_input($desired_skill, $conn);
            $message_text = sanitize_input($message_text, $conn);
            
            // Insert request into database
            $insert_query = "INSERT INTO requests (from_user_id, to_user_id, skill_id, desired_skill, message, created_at) 
                             VALUES ($from_user_id, $to_user_id, $skill_id, '$desired_skill', '$message_text', NOW())";
            
            if ($conn->query($insert_query) === TRUE) {
                $message = 'Request sent successfully! Waiting for response.';
                $message_type = 'success';
            } else {
                $message = 'Error sending request: ' . $conn->error;
                $message_type = 'error';
            }
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Skill - Learning Hub</title>
    <meta name="description" content="Send a request for a skill exchange on Learning Hub." />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/site.js"></script>
</head>
<body>
    <div class="page-shell">
        <?php include 'includes/header.php'; ?>
        <main class="request-wrapper reveal">
            <section class="auth-card">
                <h2 class="auth-title"><i class="fas fa-handshake"></i> Request Skill Exchange</h2>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?>" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="skill-info">
                    <h5>Skill Being Offered</h5>
                    <div class="info-row">
                        <span class="info-label">Title:</span>
                        <span class="info-value"><?php echo htmlspecialchars($skill['title']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Category:</span>
                        <span class="info-value"><?php echo htmlspecialchars($skill['category']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Offered by:</span>
                        <span class="info-value"><?php echo htmlspecialchars($target_user['username']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Level:</span>
                        <span class="info-value"><?php echo ucfirst($skill['skill_level']); ?></span>
                    </div>
                </div>

                <form method="POST" id="requestForm">
                    <input type="hidden" name="skill_id" value="<?php echo $skill_id; ?>">
                    <input type="hidden" name="to_user_id" value="<?php echo $to_user_id; ?>">

                    <div class="form-group">
                        <label for="desired_skill">What Skill Can You Offer?</label>
                        <input type="text" class="form-control" id="desired_skill" name="desired_skill" required>
                        <small class="form-text">The skill you can offer in exchange (minimum 3 characters)</small>
                    </div>

                    <div class="form-group">
                        <label for="message">Message (Optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Add a message to explain your request..."></textarea>
                        <small class="form-text">Let them know more about your skills or learning goals</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-send"></i> Send Request
                    </button>
                </form>

                <a href="dashboard.php" class="btn btn-outline btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </section>
            <?php include 'includes/footer.php'; ?>
        </main>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>
