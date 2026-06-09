<?php
/**
 * Dashboard - Main User Interface
 * Member 2: CRUD Operations - Display user skills and manage area
 * Member 3: Search functionality
 */

require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require user to be logged in
require_login();

$user_id = get_current_user_id();
$search_results = null;
$search_term = '';

// Get user information
$user_result = get_user_by_id($user_id, $conn);
$user = $user_result->fetch_assoc();

// Get user's skills
$skills_result = get_user_skills($user_id, $conn);

// Search functionality (Member 3)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search_term = $_GET['search'];
    if (!empty($search_term)) {
        $search_results = search_skills($search_term, $conn);
    }
}

// Close connection (will reopen in includes if needed)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Learning Hub</title>
    <meta name="description" content="Your Learning Hub dashboard for managing skills, requests, and search." />
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
        <main class="dashboard-wrapper reveal">
            <div class="main-container">
        <div class="container">
            <!-- Search Section -->
            <div class="search-section">
                <h4 class="section-title">Search Skills</h4>
                <form id="dashboardSearchForm" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search skills by title or category..." 
                           value="<?php echo htmlspecialchars($search_term); ?>">
                    <button type="submit">Search</button>
                    <?php if (!empty($search_term)): ?>
                        <a href="dashboard.php" class="btn-small btn-request" style="padding: 10px 25px;">Clear</a>
                    <?php endif; ?>
                </form>

                <div class="search-results" id="searchResultsContainer">
                    <?php if (!is_null($search_results)): ?>
                        <h5>Search Results for "<?php echo htmlspecialchars($search_term); ?>"</h5>
                        <?php if ($search_results->num_rows > 0): ?>
                            <div class="row mt-3">
                                <?php while ($skill = $search_results->fetch_assoc()): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="skill-card">
                                            <div class="skill-title"><?php echo htmlspecialchars($skill['title']); ?></div>
                                            <div class="skill-meta">
                                                <strong>By:</strong> <?php echo htmlspecialchars($skill['username']); ?>
                                            </div>
                                            <div class="skill-meta">
                                                <strong>Category:</strong> <?php echo htmlspecialchars($skill['category']); ?>
                                            </div>
                                            <div>
                                                <span class="skill-level <?php echo strtolower($skill['skill_level']); ?>">
                                                    <?php echo ucfirst($skill['skill_level']); ?>
                                                </span>
                                            </div>
                                            <p class="mt-2"><?php echo htmlspecialchars(substr($skill['description'], 0, 100)); ?>...</p>
                                            <?php if ($skill['user_id'] != $user_id): ?>
                                                <a href="request.php?skill_id=<?php echo $skill['id']; ?>&user_id=<?php echo $skill['user_id']; ?>" 
                                                   class="btn-small btn-request">Request Skill</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>No skills found matching your search.</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p>Start a search to discover skills from other users.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Your Skills Section -->
            <div class="dashboard-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 class="section-title">Your Skills</h3>
                    <a href="add_skill.php" class="btn-add-skill">
                        <i class="fas fa-plus"></i> Add New Skill
                    </a>
                </div>

                <?php if ($skills_result->num_rows > 0): ?>
                    <div class="skills-list">
                        <?php while ($skill = $skills_result->fetch_assoc()): ?>
                            <div class="skill-card">
                                <div class="skill-title"><?php echo htmlspecialchars($skill['title']); ?></div>
                                <div class="skill-meta">
                                    <strong>Category:</strong> <?php echo htmlspecialchars($skill['category']); ?>
                                </div>
                                <div class="skill-meta">
                                    <?php echo htmlspecialchars($skill['description']); ?>
                                </div>
                                <div>
                                    <span class="skill-level <?php echo strtolower($skill['skill_level']); ?>">
                                        <?php echo ucfirst($skill['skill_level']); ?>
                                    </span>
                                </div>
                                <div class="skill-actions">
                                    <a href="edit_skill.php?id=<?php echo $skill['id']; ?>" class="btn-small btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_skill.php?id=<?php echo $skill['id']; ?>" class="btn-small btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this skill?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-star"></i>
                        <p>You haven't added any skills yet.</p>
                        <a href="add_skill.php" class="btn-add-skill" style="display: inline-block; margin-top: 15px;">
                            <i class="fas fa-plus"></i> Add Your First Skill
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

            <?php include 'includes/footer.php'; ?>
        </main>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>

