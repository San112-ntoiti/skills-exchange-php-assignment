<?php
/**
 * Add New Skill Page
 * Member 2: CRUD Operations - Create
 * Member 3: Validation
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require user to be logged in
require_login();

$user_id = get_current_user_id();
$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $skill_level = isset($_POST['skill_level']) ? $_POST['skill_level'] : '';
    $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
    
    // Validate input (Member 3)
    $errors = validate_skill_input($title, $category, $description, $skill_level);
    
    if (empty($errors)) {
        // Sanitize inputs
        $title = sanitize_input($title, $conn);
        $category = sanitize_input($category, $conn);
        $description = sanitize_input($description, $conn);
        $skill_level = sanitize_input($skill_level, $conn);
        $keywords = sanitize_input($keywords, $conn);
        
        // Insert skill into database
        $insert_query = "INSERT INTO skills (user_id, title, category, description, skill_level, keywords, created_at) 
                         VALUES ($user_id, '$title', '$category', '$description', '$skill_level', '$keywords', NOW())";
        
        if ($conn->query($insert_query) === TRUE) {
            $message = 'Skill added successfully!';
            $message_type = 'success';
            
            // Clear form
            $title = '';
            $category = '';
            $description = '';
            $skill_level = 'beginner';
            $keywords = '';
        } else {
            $message = 'Error adding skill: ' . $conn->error;
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
    <title>Add Skill - Learning Hub</title>
    <meta name="description" content="Add a new skill offering to your Learning Hub profile." />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/site.js"></script>
    <style>
        @media (max-width: 768px) {
            .auth-page {
                padding: 2rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <?php include 'includes/header.php'; ?>
        <main class="request-wrapper reveal">
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
    <div class="page-shell">
        <?php include 'includes/header.php'; ?>
        <main class="request-wrapper reveal">
            <section class="auth-card">
                <h2 class="auth-title"><i class="fas fa-plus"></i> Add New Skill</h2>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?>" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="addSkillForm">
                    <div class="form-group">
                        <label for="title">Skill Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
                        <small class="form-text">Minimum 3 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="Technology" <?php echo isset($category) && $category == 'Technology' ? 'selected' : ''; ?>>Technology</option>
                            <option value="Business" <?php echo isset($category) && $category == 'Business' ? 'selected' : ''; ?>>Business</option>
                            <option value="Design" <?php echo isset($category) && $category == 'Design' ? 'selected' : ''; ?>>Design</option>
                            <option value="Marketing" <?php echo isset($category) && $category == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                            <option value="Languages" <?php echo isset($category) && $category == 'Languages' ? 'selected' : ''; ?>>Languages</option>
                            <option value="Personal" <?php echo isset($category) && $category == 'Personal' ? 'selected' : ''; ?>>Personal</option>
                            <option value="Other" <?php echo isset($category) && $category == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                        <small class="form-text">Minimum 10 characters. Describe your skill in detail.</small>
                    </div>

                    <div class="form-group">
                        <label for="skill_level">Skill Level</label>
                        <select class="form-select" id="skill_level" name="skill_level" required>
                            <option value="beginner" <?php echo isset($skill_level) && $skill_level == 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                            <option value="intermediate" <?php echo isset($skill_level) && $skill_level == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="advanced" <?php echo isset($skill_level) && $skill_level == 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="keywords">Keywords (Optional)</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" 
                               value="<?php echo isset($keywords) ? htmlspecialchars($keywords) : ''; ?>" 
                               placeholder="e.g., PHP, MySQL, Web Development">
                        <small class="form-text">Comma-separated keywords to help others find your skill</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-check"></i> Add Skill
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

