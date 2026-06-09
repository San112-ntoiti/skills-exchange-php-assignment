<?php
/**
 * Add New Skill Page (cleaned)
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
    $skill_type = isset($_POST['skill_type']) ? $_POST['skill_type'] : 'teach';
    
    // Validate input
    $errors = validate_skill_input($title, $category, $description, $skill_level);
    
    if (empty($errors)) {
        // Sanitize inputs
        $title = sanitize_input($title, $conn);
        $category = sanitize_input($category, $conn);
        $description = sanitize_input($description, $conn);
        $skill_level = sanitize_input($skill_level, $conn);
        $keywords = sanitize_input($keywords, $conn);
        $skill_type = ($skill_type === 'learn') ? 'learn' : 'teach';
        
        // Ensure the skills table has the skill_type column (migrate if needed)
        $conn->query("ALTER TABLE skills ADD COLUMN IF NOT EXISTS skill_type ENUM('teach','learn') NOT NULL DEFAULT 'teach'");
        
        // Insert skill into database (including skill_type)
        $insert_query = "INSERT INTO skills (user_id, title, category, description, skill_level, keywords, skill_type, created_at) 
                         VALUES ($user_id, '$title', '$category', '$description', '$skill_level', '$keywords', '$skill_type', NOW())";
        
        if ($conn->query($insert_query) === TRUE) {
            $message = 'Skill added successfully!';
            $message_type = 'success';
            
            // Clear form
            $title = '';
            $category = '';
            $description = '';
            $skill_level = 'beginner';
            $keywords = '';
            $skill_type = 'teach';
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
</head>
<body>
    <div class="page-shell">
        <?php include 'includes/header.php'; ?>
        <main class="section reveal">
            <div class="container">
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
                                <?php
                                $opts = ['Technology','Business','Design','Marketing','Languages','Personal','Other'];
                                foreach ($opts as $opt) {
                                    $sel = (isset($category) && $category == $opt) ? 'selected' : '';
                                    echo "<option value=\"$opt\" $sel>$opt</option>";
                                }
                                ?>
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
                                <?php
                                $levels = ['beginner'=>'Beginner','intermediate'=>'Intermediate','advanced'=>'Advanced'];
                                foreach ($levels as $val => $label) {
                                    $sel = (isset($skill_level) && $skill_level == $val) ? 'selected' : '';
                                    echo "<option value=\"$val\" $sel>$label</option>";
                                }
                                ?>
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
            </div>
        </main>
        <?php include 'includes/footer.php'; ?>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>
