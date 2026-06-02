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
    <title>Dashboard - Skill Exchange</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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
            transition: 0.3s;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .main-container {
            padding: 30px 0;
        }
        
        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-color);
        }
        
        .btn-add-skill {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        
        .btn-add-skill:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .skill-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 5px solid var(--primary-color);
            transition: 0.3s;
        }
        
        .skill-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .skill-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .skill-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .skill-level {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .skill-level.beginner {
            background-color: #d4edda;
            color: #155724;
        }
        
        .skill-level.intermediate {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .skill-level.advanced {
            background-color: #cfe2ff;
            color: #084298;
        }
        
        .skill-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 5px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .btn-edit {
            background-color: #ffc107;
            color: #333;
        }
        
        .btn-edit:hover {
            background-color: #e0a800;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .btn-request {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .search-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-form input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .search-form button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .skill-actions {
                flex-direction: column;
            }
            
            .btn-small {
                width: 100%;
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
                        <a class="nav-link" href="add_skill.php">
                            <i class="fas fa-plus"></i> Add Skill
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>

