<?php
require_once 'includes/auth.php';
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit();
}

$heroSlides = [
    [
        'title' => 'Master Future-Ready Skills',
        'subtitle' => 'Explore premium pathways built for career growth, leadership, and digital transformation.',
        'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1600&q=80',
        'button_text' => 'Browse Courses',
        'button_link' => 'register.php',
    ],
    [
        'title' => 'Launch Your Learning Journey',
        'subtitle' => 'Hands-on courses, live guidance, and real outcomes designed for ambitious learners.',
        'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1600&q=80',
        'button_text' => 'Start Learning',
        'button_link' => 'register.php',
    ],
    [
        'title' => 'Design, Code, Lead',
        'subtitle' => 'Build skills with expert-led content across design, tech, business, and innovation.',
        'image' => 'https://images.unsplash.com/photo-1483058712412-4245e9b90334?auto=format&fit=crop&w=1600&q=80',
        'button_text' => 'Discover Paths',
        'button_link' => 'register.php',
    ],
];

$popularCourses = [
    [
        'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80',
        'title' => 'Product Strategy & Innovation',
        'category' => 'Business',
        'instructor' => 'Ava Moreno',
        'rating' => 4.9,
        'students' => '12.8k',
        'duration' => '8h 30m',
        'price' => 'Free',
        'url' => 'register.php',
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=900&q=80',
        'title' => 'Full-Stack Web Development',
        'category' => 'Programming',
        'instructor' => 'Noah Chen',
        'rating' => 4.8,
        'students' => '9.7k',
        'duration' => '12h 45m',
        'price' => '$49',
        'url' => 'register.php',
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1483442391823-2fa4c7d4f5eb?auto=format&fit=crop&w=900&q=80',
        'title' => 'AI & Machine Learning Essentials',
        'category' => 'AI & Machine Learning',
        'instructor' => 'Mia Patel',
        'rating' => 4.9,
        'students' => '14.3k',
        'duration' => '10h 20m',
        'price' => '$59',
        'url' => 'register.php',
    ],
    [
        'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=900&q=80',
        'title' => 'Design Systems for Product Teams',
        'category' => 'Graphic Design',
        'instructor' => 'Liam Carter',
        'rating' => 4.7,
        'students' => '8.2k',
        'duration' => '7h 15m',
        'price' => 'Free',
        'url' => 'register.php',
    ],
];

$learningPaths = [
    [
        'title' => 'Cybersecurity Career Accelerator',
        'courses' => 7,
        'time' => '18h',
        'progress' => 42,
    ],
    [
        'title' => 'Cloud Engineering Mastery',
        'courses' => 5,
        'time' => '14h',
        'progress' => 28,
    ],
];

$categories = [
    ['icon' => 'fa-code', 'name' => 'Programming', 'count' => '320 courses'],
    ['icon' => 'fa-shield-alt', 'name' => 'Cybersecurity', 'count' => '210 courses'],
    ['icon' => 'fa-network-wired', 'name' => 'Networking', 'count' => '170 courses'],
    ['icon' => 'fa-chart-line', 'name' => 'Data Science', 'count' => '240 courses'],
    ['icon' => 'fa-cloud', 'name' => 'Cloud Computing', 'count' => '198 courses'],
    ['icon' => 'fa-robot', 'name' => 'AI & Machine Learning', 'count' => '150 courses'],
    ['icon' => 'fa-pencil-ruler', 'name' => 'Graphic Design', 'count' => '130 courses'],
    ['icon' => 'fa-briefcase', 'name' => 'Business', 'count' => '280 courses'],
];

$instructors = [
    [
        'photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80',
        'name' => 'Jordan Lee',
        'specialty' => 'Product Leadership',
        'students' => '32k',
        'courses' => '14',
        'bio' => 'Empowers teams with polished strategy and rapid execution skills.',
    ],
    [
        'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=800&q=80',
        'name' => 'Sara Kim',
        'specialty' => 'UX & Design Systems',
        'students' => '27k',
        'courses' => '11',
        'bio' => 'Builds elegant experiences and scalable product design workflows.',
    ],
];

$reviews = [
    [
        'photo' => 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?auto=format&fit=crop&w=800&q=80',
        'name' => 'Aisha R.',
        'role' => 'Marketing Analyst',
        'quote' => 'The learning paths made it easy to focus on what matters most — I completed my first project in weeks.',
        'rating' => 5,
    ],
    [
        'photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80',
        'name' => 'Noah P.',
        'role' => 'Software Engineer',
        'quote' => 'The courses are polished, fast, and the micro interactions make the experience feel premium.',
        'rating' => 5,
    ],
    [
        'photo' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=800&q=80',
        'name' => 'Lena M.',
        'role' => 'Entrepreneur',
        'quote' => 'I locked in new skills and felt guided every step of the way — highly recommended.',
        'rating' => 4,
    ],
];

$stats = [
    ['label' => 'Courses Published', 'value' => '1,250+'],
    ['label' => 'Active Learners', 'value' => '46,500+'],
    ['label' => 'Expert Instructors', 'value' => '320+'],
    ['label' => 'Learning Paths', 'value' => '24'],
];

function renderCourseCarousel($anchor, $title, $courses) {
    ?>
    <section id="<?php echo htmlspecialchars($anchor); ?>" class="section reveal">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2><?php echo htmlspecialchars($title); ?></h2>
                    <p>Browse top-rated learning experiences designed for fast growth and real outcomes.</p>
                </div>
            </div>
            <div class="course-carousel">
                <button class="carousel-arrow prev" aria-label="Previous courses"><i class="fas fa-chevron-left"></i></button>
                <div class="carousel-track">
                    <?php foreach ($courses as $course): ?>
                        <article class="course-card">
                            <div class="course-image" style="background-image:url('<?php echo htmlspecialchars($course['image']); ?>');" aria-hidden="true"></div>
                            <div class="course-body">
                                <span class="course-badge"><?php echo htmlspecialchars($course['category']); ?></span>
                                <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                                <div class="course-meta">
                                    <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($course['instructor']); ?></span>
                                    <span><i class="fas fa-star"></i> <?php echo htmlspecialchars($course['rating']); ?></span>
                                </div>
                                <div class="course-footer">
                                    <span class="course-price"><?php echo htmlspecialchars($course['price']); ?></span>
                                    <a href="<?php echo htmlspecialchars($course['url']); ?>" class="btn btn-primary" role="button">Enroll</a>
                                </div>
                                <div class="course-meta">
                                    <span><i class="fas fa-users"></i> <?php echo htmlspecialchars($course['students']); ?></span>
                                    <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($course['duration']); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-arrow next" aria-label="Next courses"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Hub - Home</title>
    <meta name="description" content="Premium learning experiences for future-ready skills and career growth." />
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
        <main>
            <section class="hero-section reveal">
                <div class="hero-slider" role="region" aria-label="Featured learning experiences carousel">
                    <?php foreach ($heroSlides as $index => $slide): ?>
                        <article class="hero-slide<?php echo $index === 0 ? ' active' : ''; ?>">
                            <div class="hero-image" aria-hidden="true">
                                <img src="<?php echo htmlspecialchars($slide['image']); ?>" alt="" onerror="this.onerror=null;this.src='assets/placeholder.svg';">
                            </div>
                            <div class="container hero-content">
                                <div class="hero-copy">
                                    <span class="eyebrow">Premium Learning</span>
                                    <h1><?php echo htmlspecialchars($slide['title']); ?></h1>
                                    <p><?php echo htmlspecialchars($slide['subtitle']); ?></p>
                                    <div class="hero-actions">
                                        <a href="<?php echo htmlspecialchars($slide['button_link']); ?>" class="btn btn-primary"><?php echo htmlspecialchars($slide['button_text']); ?></a>
                                        <a href="login.php" class="btn btn-outline">Start Learning</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <button class="slider-arrow prev" aria-label="Previous slide"><i class="fas fa-chevron-left"></i></button>
                    <button class="slider-arrow next" aria-label="Next slide"><i class="fas fa-chevron-right"></i></button>
                    <div class="slider-dots">
                        <?php foreach ($heroSlides as $index => $slide): ?>
                            <button class="slider-dot<?php echo $index === 0 ? ' active' : ''; ?>" aria-label="Slide <?php echo $index + 1; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <?php renderCourseCarousel('courses', 'Popular Courses', $popularCourses); ?>

            <section id="paths" class="section reveal-left">
                <div class="container">
                    <div class="section-header">
                        <div>
                            <h2>Featured Learning Paths</h2>
                            <p>Structured programs curated to help you advance quickly with confidence.</p>
                        </div>
                    </div>
                    <div class="learning-grid">
                        <?php foreach ($learningPaths as $path): ?>
                            <article class="learning-card">
                                <h3><?php echo htmlspecialchars($path['title']); ?></h3>
                                <div class="path-meta">
                                    <span><strong><?php echo htmlspecialchars($path['courses']); ?></strong> Courses</span>
                                    <span><strong><?php echo htmlspecialchars($path['time']); ?></strong> Completion</span>
                                </div>
                                <div class="progress-bar" aria-hidden="true">
                                    <div class="progress-fill" style="width: <?php echo intval($path['progress']); ?>%;"></div>
                                </div>
                                <span><?php echo intval($path['progress']); ?>% complete</span>
                                <a href="register.php" class="btn btn-primary">Start Path</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="section reveal-right">
                <div class="container">
                    <div class="section-header">
                        <div>
                            <h2>Top Categories</h2>
                            <p>Explore the skill categories that match your ambition and career focus.</p>
                        </div>
                    </div>
                    <div class="category-scroll">
                        <?php foreach ($categories as $category): ?>
                            <article class="category-card">
                                <div class="category-icon" aria-hidden="true"><i class="fas <?php echo htmlspecialchars($category['icon']); ?>"></i></div>
                                <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                                <p><?php echo htmlspecialchars($category['count']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section id="instructors" class="section reveal">
                <div class="container">
                    <div class="section-header">
                        <div>
                            <h2>Instructor Spotlight</h2>
                            <p>Learn from leaders with deep industry experience and engaging teaching style.</p>
                        </div>
                    </div>
                    <div class="instructor-grid">
                        <?php foreach ($instructors as $teacher): ?>
                            <article class="instructor-card">
                                <img src="<?php echo htmlspecialchars($teacher['photo']); ?>" alt="Photo of <?php echo htmlspecialchars($teacher['name']); ?>" onerror="this.onerror=null;this.src='assets/placeholder.svg';">
                                <div>
                                    <h3><?php echo htmlspecialchars($teacher['name']); ?></h3>
                                    <p class="instructor-bio"><?php echo htmlspecialchars($teacher['bio']); ?></p>
                                    <div class="instructor-stats">
                                        <span><?php echo htmlspecialchars($teacher['specialty']); ?></span>
                                        <span><?php echo htmlspecialchars($teacher['students']); ?> learners</span>
                                        <span><?php echo htmlspecialchars($teacher['courses']); ?> courses</span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="section reveal-left">
                <div class="container split-banner">
                    <article class="promo-card">
                            <img src="https://images.unsplash.com/photo-1498079022511-d15614cb1c02?auto=format&fit=crop&w=1200&q=80" alt="Network engineer learning experience" onerror="this.onerror=null;this.src='assets/placeholder.svg';">
                    </article>
                    <article class="promo-card promo-content">
                        <span class="eyebrow">Career Focus</span>
                        <h3>Become Network Engineer Ready</h3>
                        <p>Develop practical hands-on skills, real projects, and mentorship designed for fast-track success.</p>
                        <a href="register.php" class="btn btn-primary">Explore Network Training</a>
                    </article>
                </div>
            </section>

            <section class="section reveal-right">
                <div class="container split-banner">
                    <article class="promo-card promo-content">
                        <span class="eyebrow">Launch Program</span>
                        <h3>Launch Your Cybersecurity Career</h3>
                        <p>Access curated curriculum that covers modern security principles, tools, and career-ready skills.</p>
                        <a href="register.php" class="btn btn-primary">View Cybersecurity Paths</a>
                    </article>
                    <article class="promo-card">
                        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80" alt="Cybersecurity learning environment" onerror="this.onerror=null;this.src='assets/placeholder.svg';">
                    </article>
                </div>
            </section>

            <?php renderCourseCarousel('recent', 'Recently Added Courses', $popularCourses); ?>

            <section id="testimonials" class="section reveal">
                <div class="container">
                    <div class="section-header">
                        <div>
                            <h2>Student Testimonials</h2>
                            <p>Hear from learners who accelerated their careers with guided learning paths.</p>
                        </div>
                    </div>
                    <div class="testimonial-slider">
                        <button class="testimonial-arrow prev" aria-label="Previous testimonial"><i class="fas fa-chevron-left"></i></button>
                        <button class="testimonial-arrow next" aria-label="Next testimonial"><i class="fas fa-chevron-right"></i></button>
                        <div class="testimonial-track">
                            <?php foreach ($reviews as $review): ?>
                                <article class="testimonial-card">
                                    <p>“<?php echo htmlspecialchars($review['quote']); ?>”</p>
                                    <div class="testimonial-meta">
                                        <div class="testimonial-avatar">
                                            <img src="<?php echo htmlspecialchars($review['photo']); ?>" alt="Photo of <?php echo htmlspecialchars($review['name']); ?>" onerror="this.onerror=null;this.src='assets/placeholder.svg';">
                                        </div>
                                        <div class="testimonial-author">
                                            <h4><?php echo htmlspecialchars($review['name']); ?></h4>
                                            <small><?php echo htmlspecialchars($review['role']); ?></small>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section reveal-left">
                <div class="container">
                    <div class="section-header">
                        <div>
                            <h2>Learning Statistics</h2>
                            <p>Key metrics that show how our platform empowers learners and instructors.</p>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <?php foreach ($stats as $stat): ?>
                            <article class="stats-card">
                                <h3><?php echo htmlspecialchars($stat['value']); ?></h3>
                                <p><?php echo htmlspecialchars($stat['label']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="section reveal-right">
                <div class="container newsletter-card">
                    <div class="section-header">
                        <div>
                            <h2>Stay Ahead With Learning Updates</h2>
                            <p>Receive curated course launches, expert interviews, and skill recommendations by email.</p>
                        </div>
                    </div>
                    <form action="register.php" method="get" aria-label="Newsletter sign up">
                        <input type="email" name="email" placeholder="Enter your email address" required>
                        <button type="submit" class="btn btn-primary">Join Newsletter</button>
                    </form>
                </div>
            </section>
        </main>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
