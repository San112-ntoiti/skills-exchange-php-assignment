<?php

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require user to be logged in
require_login();

$user_id = get_current_user_id();
$message = '';
$message_type = '';

// Get skill ID from URL
$skill_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$skill_id) {
    header('Location: dashboard.php');
    exit();
}

// Get skill details
$skill = get_skill_by_id($skill_id, $conn);

if (!$skill || $skill['user_id'] != $user_id) {
    header('Location: dashboard.php');
    exit();
}

// Initialize form variables
$title = $skill['title'];
$category = $skill['category'];
$description = $skill['description'];
$skill_level = $skill['skill_level'];
$keywords = $skill['keywords'];
$skill_type = isset($skill['skill_type']) ? $skill['skill_type'] : 'teach';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $skill_level = isset($_POST['skill_level']) ? $_POST['skill_level'] : '';
    $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
    $skill_type = isset($_POST['skill_type']) ? $_POST['skill_type'] : 'teach';
    
    // Validate input (Member 3)
    $errors = validate_skill_input($title, $category, $description, $skill_level);
    
    if (empty($errors)) {
        // Sanitize inputs
        $title = sanitize_input($title, $conn);
        $category = sanitize_input($category, $conn);
        $description = sanitize_input($description, $conn);
        $skill_level = sanitize_input($skill_level, $conn);
        $keywords = sanitize_input($keywords, $conn);
        $skill_type = ($skill_type === 'learn') ? 'learn' : 'teach';
        
        // Ensure column exists
        $conn->query("ALTER TABLE skills ADD COLUMN IF NOT EXISTS skill_type ENUM('teach','learn') NOT NULL DEFAULT 'teach'");
        
        // Update skill in database
        $update_query = "UPDATE skills SET title = '$title', category = '$category', 
                        description = '$description', skill_level = '$skill_level', 
                        keywords = '$keywords', skill_type = '$skill_type', updated_at = NOW() 
                        WHERE id = $skill_id AND user_id = $user_id";
        
        if ($conn->query($update_query) === TRUE) {
            $message = 'Skill updated successfully!';
            $message_type = 'success';
        } else {
            $message = 'Error updating skill: ' . $conn->error;
            $message_type = 'error';
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
    <title>Edit Skill - Skill Exchange</title>
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
        
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 30px auto;
        }
        
        .form-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        .form-control:focus, .form-select:focus {
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
            .form-container {
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

    <div class="form-container">
        <h2 class="form-title">
            <i class="fas fa-edit"></i> Edit Skill
        </h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="editSkillForm">
            <div class="form-group mb-3">
                <label for="title">Skill Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?php echo htmlspecialchars($title); ?>" required>
                <small class="form-text">Minimum 3 characters</small>
            </div>

            <div class="form-group mb-3">
                <label for="category">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="Technology" <?php echo $category == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                    <option value="Business" <?php echo $category == 'Business' ? 'selected' : ''; ?>>Business</option>
                    <option value="Design" <?php echo $category == 'Design' ? 'selected' : ''; ?>>Design</option>
                    <option value="Marketing" <?php echo $category == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                    <option value="Languages" <?php echo $category == 'Languages' ? 'selected' : ''; ?>>Languages</option>
                    <option value="Personal" <?php echo $category == 'Personal' ? 'selected' : ''; ?>>Personal</option>
                    <option value="Other" <?php echo $category == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
                <small class="form-text">Minimum 10 characters. Describe your skill in detail.</small>
            </div>

            <div class="form-group mb-3">
                <label for="skill_level">Skill Level</label>
                <select class="form-select" id="skill_level" name="skill_level" required>
                    <option value="beginner" <?php echo $skill_level == 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                    <option value="intermediate" <?php echo $skill_level == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="advanced" <?php echo $skill_level == 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Skill Role</label>
                <div style="display:flex;gap:12px;align-items:center;margin-top:8px;">
                    <label style="display:inline-flex;gap:8px;align-items:center;"><input type="radio" name="skill_type" value="teach" <?php echo (!isset($skill_type) || $skill_type == 'teach') ? 'checked' : ''; ?>> I can teach / commit</label>
                    <label style="display:inline-flex;gap:8px;align-items:center;"><input type="radio" name="skill_type" value="learn" <?php echo (isset($skill_type) && $skill_type == 'learn') ? 'checked' : ''; ?>> I want to learn / ask</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="keywords">Keywords (Optional)</label>
                <input type="text" class="form-control" id="keywords" name="keywords" 
                       value="<?php echo htmlspecialchars($keywords); ?>" 
                       placeholder="e.g., PHP, MySQL, Web Development">
                <small class="form-text">Comma-separated keywords to help others find your skill</small>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Update Skill
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

