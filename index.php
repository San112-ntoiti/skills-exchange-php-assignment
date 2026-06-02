<?php
/**
 * Homepage for Skill Exchange
 * Member 1: Homepage, Navigation, Responsive Design
 */

require_once 'includes/auth.php';
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Exchange - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-handshake"></i> Skill Exchange
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="hero-section text-center">
            <div class="container">
                <h1>Exchange Skills. Learn Together.</h1>
                <p class="lead">Create your profile, share what you know, and request skills from others in your community.</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="login.php" class="btn btn-outline-light btn-lg">Login</a>
                </div>
            </div>
        </div>
    </header>

    <main class="container section-spacing">
        <section class="row align-items-center feature-row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2>Build your learning network</h2>
                <p>Skill Exchange helps users discover expert skills, make requests, and manage learning through a clean, mobile-friendly interface.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Post your skills in seconds</li>
                    <li><i class="fas fa-check-circle"></i> Search by category and expertise level</li>
                    <li><i class="fas fa-check-circle"></i> Send requests to learn from others</li>
                </ul>
            </div>
            <div class="col-lg-7 text-center">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80" alt="Skill exchange" class="img-fluid rounded shadow-sm hero-image">
            </div>
        </section>

        <section class="row text-center stats-row">
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="fas fa-users fa-2x"></i>
                    <h3>Community</h3>
                    <p>Collaborate with learners and teachers in a shared skill marketplace.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="fas fa-lightbulb fa-2x"></i>
                    <h3>Grow</h3>
                    <p>Develop new abilities through meaningful skill exchanges.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <i class="fas fa-mobile-alt fa-2x"></i>
                    <h3>Responsive</h3>
                    <p>Use the platform comfortably from mobile, tablet, or desktop.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer text-center py-4">
        <div class="container">
            <p>Skill Exchange &copy; 2026 | A learning community powered by teamwork and technology.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
