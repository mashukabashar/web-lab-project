<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Event Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <?php 
    $css_path = 'css/modern-style.css';
    if (!file_exists($css_path)) {
        $css_path = '../css/modern-style.css';
    }
    ?>
    <link href="<?php echo $css_path; ?>" rel="stylesheet">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link href="<?php echo $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php
    require_once __DIR__ . '/../Database/compatibility.php';
    
    // Get current user info if logged in
    $currentUser = null;
    $notifications = [];
    $unreadCount = 0;
    
    if (LegacyAuth::isLoggedIn()) {
        $currentUser = [
            'id' => LegacyAuth::getUserId(),
            'email' => LegacyAuth::getUserEmail(),
            'first_name' => explode(' ', LegacyAuth::getUserName())[0],
            'last_name' => explode(' ', LegacyAuth::getUserName(), 2)[1] ?? '',
            'role' => LegacyAuth::getUserRole()
        ];
        
        // Set notifications to empty for legacy system
        $notifications = [];
        $unreadCount = 0;
    }
    ?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <?php 
            $base_path = '';
            if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || 
                strpos($_SERVER['REQUEST_URI'], '/customer/') !== false) {
                $base_path = '../';
            }
            ?>
            <a class="navbar-brand fw-bold" href="<?php echo $base_path; ?>index.php">
                <i class="fas fa-calendar-alt me-2"></i>
                Event Management
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-calendar-check me-1"></i>Events
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>wedding.php">
                                <i class="fas fa-heart me-2"></i>Weddings
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>birthday.php">
                                <i class="fas fa-birthday-cake me-2"></i>Birthday Parties
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>anniversary.php">
                                <i class="fas fa-glass-cheers me-2"></i>Anniversaries
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>other_events.php">
                                <i class="fas fa-star me-2"></i>Other Events
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>gallery.php">
                                <i class="fas fa-images me-2"></i>View Gallery
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>gallery.php">
                            <i class="fas fa-images me-1"></i>Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>about.php">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>contact.php">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (LegacyAuth::isLoggedIn()): ?>
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?php echo $unreadCount; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <?php if (empty($notifications)): ?>
                                    <li><span class="dropdown-item-text">No notifications</span></li>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <li>
                                            <a class="dropdown-item <?php echo !$notification['is_read'] ? 'fw-bold' : ''; ?>" 
                                               href="#" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                                                <div class="notification-item">
                                                    <div class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></div>
                                                    <div class="notification-text"><?php echo htmlspecialchars($notification['message']); ?></div>
                                                    <small class="text-muted"><?php echo date('M j, g:i A', strtotime($notification['created_at'])); ?></small>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="notifications.php">View All</a></li>
                            </ul>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($currentUser['first_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($currentUser['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo $base_path; ?>admin/dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                    </a></li>
                                <?php elseif ($currentUser['role'] === 'customer'): ?>
                                    <li><a class="dropdown-item" href="<?php echo $base_path; ?>customer/dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo $base_path; ?>request-service.php">
                                        <i class="fas fa-plus me-2"></i>New Request
                                    </a></li>
                                <?php elseif ($currentUser['role'] === 'event_team'): ?>
                                    <li><a class="dropdown-item" href="<?php echo $base_path; ?>team/dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>Team Dashboard
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo $base_path; ?>logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>register.php">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
