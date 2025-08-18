<?php
$page_title = 'Home';
include_once("includes/header.php");

// Get featured services
$db = DatabaseConfig::getInstance()->getConnection();

$stmt = $db->prepare("
    SELECT ec.*, COUNT(s.id) as service_count 
    FROM event_categories ec 
    LEFT JOIN services s ON ec.id = s.category_id AND s.status = 'active'
    WHERE ec.status = 'active' 
    GROUP BY ec.id 
    ORDER BY service_count DESC 
    LIMIT 6
");
$stmt->execute();
$featured_categories = $stmt->fetchAll();

// Get recent feedback
$stmt = $db->prepare("
    SELECT f.*, u.first_name, u.last_name 
    FROM feedback f 
    JOIN users u ON f.customer_id = u.id 
    WHERE f.status = 'approved' 
    ORDER BY f.created_at DESC 
    LIMIT 3
");
$stmt->execute();
$recent_feedback = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="hero-title fade-in">Making Your Dream Events Come True</h1>
                <p class="hero-subtitle fade-in">Professional event management services with experienced teams for weddings, birthdays, anniversaries, and corporate events.</p>
                <div class="hero-buttons fade-in">
                    <a href="events.php" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-calendar-alt me-2"></i>Explore Events
                    </a>
                    <?php if (!Auth::isLoggedIn()): ?>
                        <a href="register.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    <?php else: ?>
                        <a href="request-service.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Request Service
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="images/hero-illustration.png" alt="Event Management" class="img-fluid slide-in-right" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4>Expert Teams</h4>
                    <p class="text-muted">Professional teams specialized in music, catering, decoration, and event planning.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                    <h4>Custom Solutions</h4>
                    <p class="text-muted">Tailored event solutions to meet your specific requirements and budget.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h4>Quality Service</h4>
                    <p class="text-muted">Committed to delivering exceptional quality and memorable experiences.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Event Categories</h2>
            <p class="text-muted">Choose from our wide range of event management services</p>
        </div>
        
        <div class="row">
            <?php foreach ($featured_categories as $category): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-hover">
                        <img src="images/categories/<?php echo $category['image'] ?: 'default.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($category['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($category['description']); ?></p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><?php echo $category['service_count']; ?> Services</small>
                                    <a href="events.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-1"></i>Explore
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="events.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i>View All Categories
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">Simple steps to get your perfect event</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">1</span>
                    </div>
                    <h5>Choose Services</h5>
                    <p class="text-muted">Select the event type and services you need for your special occasion.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">2</span>
                    </div>
                    <h5>Submit Request</h5>
                    <p class="text-muted">Fill out the request form with your event details and requirements.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">3</span>
                    </div>
                    <h5>Get Approval</h5>
                    <p class="text-muted">Our team reviews your request and assigns the best teams for your event.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-gradient-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-4">4</span>
                    </div>
                    <h5>Enjoy Event</h5>
                    <p class="text-muted">Relax and enjoy your perfectly planned event with our professional teams.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<?php if (!empty($recent_feedback)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">What Our Customers Say</h2>
            <p class="text-muted">Real feedback from our satisfied customers</p>
        </div>
        
        <div class="row">
            <?php foreach ($recent_feedback as $feedback): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-soft">
                        <div class="card-body">
                            <div class="mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="card-text">"<?php echo htmlspecialchars($feedback['comment']); ?>"</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($feedback['first_name'] . ' ' . $feedback['last_name']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Plan Your Perfect Event?</h2>
        <p class="fs-5 mb-4">Join thousands of satisfied customers who trust us with their special moments.</p>
        <?php if (!Auth::isLoggedIn()): ?>
            <a href="register.php" class="btn btn-light btn-lg me-3">
                <i class="fas fa-user-plus me-2"></i>Register Now
            </a>
            <a href="contact.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2"></i>Contact Us
            </a>
        <?php else: ?>
            <a href="request-service.php" class="btn btn-light btn-lg me-3">
                <i class="fas fa-plus me-2"></i>Request Service
            </a>
            <a href="events.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-calendar-alt me-2"></i>Browse Events
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card bg-gradient-primary">
                    <div class="stats-number">500+</div>
                    <div class="stats-label">Events Completed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card bg-gradient-success">
                    <div class="stats-number">100+</div>
                    <div class="stats-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card bg-gradient-warning">
                    <div class="stats-number">25+</div>
                    <div class="stats-label">Expert Teams</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card bg-gradient-danger">
                    <div class="stats-number">5</div>
                    <div class="stats-label">Years Experience</div>
                </div>
            </div>
        </div>
    </div>
</section>
				<div class="servc-icon">
					<a href="birthday.php" class="agile-shape"><span class="glyphicon fa fa-gift" aria-hidden="true"></span>
					<p class="serw3-agiletext">Birthday party</p>
					</a>
				</div>
				<div class="servc-icon">
<?php include_once("includes/footer.php"); ?>