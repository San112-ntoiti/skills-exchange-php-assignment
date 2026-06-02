<?php
/**
 * Skill Request Page
 * Member 2: CRUD Operations - Create Request
 * Member 3: Validation & Search functionality
 */

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
    <title>Request Skill - Skill Exchange</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            margin-left: 15px;
        }
        
        .request-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 30px auto;
        }
        
        .skill-info {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .skill-info h5 {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .form-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }
        
        .alert {
            margin-bottom: 20px;
        }
        
        .form-text {
            color: #999;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .request-container {
                padding: 20px;
                margin: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-handshake"></i> Skill Exchange
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="request-container">
        <h2 class="form-title">
            <i class="fas fa-handshake"></i> Request Skill Exchange
        </h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Skill Information -->
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

        <!-- Request Form -->
        <form method="POST" id="requestForm">
            <input type="hidden" name="skill_id" value="<?php echo $skill_id; ?>">
            <input type="hidden" name="to_user_id" value="<?php echo $to_user_id; ?>">

            <div class="form-group mb-3">
                <label for="desired_skill">What Skill Can You Offer?</label>
                <input type="text" class="form-control" id="desired_skill" name="desired_skill" required>
                <small class="form-text">The skill you can offer in exchange (minimum 3 characters)</small>
            </div>

            <div class="form-group mb-3">
                <label for="message">Message (Optional)</label>
                <textarea class="form-control" id="message" name="message" rows="4" 
                          placeholder="Add a message to explain your request..."></textarea>
                <small class="form-text">Let them know more about your skills or learning goals</small>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-send"></i> Send Request
            </button>
        </form>

        <a href="dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>

