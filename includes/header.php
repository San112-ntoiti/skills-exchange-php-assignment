<header class="global-header">
    <div class="container nav-panel">
        <a class="brand" href="index.php" aria-label="Learning Hub home">
            <svg viewBox="0 0 64 64" aria-hidden="true">
                <path d="M32 6L58 18v28L32 58 6 46V18z"></path>
            </svg>
            Learning Hub
        </a>
        <nav class="main-nav" aria-label="Primary navigation">
            <button class="nav-toggle" aria-expanded="false" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
            </button>
            <ul class="nav-list">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#courses">Courses</a></li>
                <li><a href="index.php#paths">Paths</a></li>
                <li><a href="index.php#instructors">Instructors</a></li>
                <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
