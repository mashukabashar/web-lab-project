<?php
$page_title = 'Login';

require_once 'Database/compatibility.php';

// Redirect if already logged in
if (LegacyAuth::isLoggedIn()) {
    $role = LegacyAuth::getUserRole();
    switch ($role) {
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        case 'customer':
            header("Location: customer/dashboard.php");
            break;
        case 'event_team':
            header("Location: team/dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $user = LegacyAuth::login($email, $password);
        if ($user) {
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'customer':
                    header("Location: customer/dashboard.php");
                    break;
                case 'event_team':
                    header("Location: team/dashboard.php");
                    break;
                default:
                    header("Location: index.php");
            }
            exit();
        } else {
            $error_message = 'Invalid email or password.';
        }
    }
}

include_once("includes/header.php");
?>

<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side - Login Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="w-100" style="max-width: 400px;">
                <div class="text-center mb-5">
                    <h1 class="fw-bold text-primary mb-2">Welcome Back</h1>
                    <p class="text-muted">Sign in to your account to continue</p>
                </div>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>

                    <div class="text-center">
                        <a href="forgot-password.php" class="text-decoration-none">Forgot your password?</a>
                    </div>
                </form>

                <div class="text-center mt-4 pt-4 border-top">
                    <p class="text-muted">Don't have an account?</p>
                    <a href="register.php" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </a>
                </div>

                <!-- Demo Login Credentials -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-2">Demo Credentials:</h6>
                    <small class="d-block">Admin: admin@eventmanagement.com / admin123</small>
                    <small class="d-block">Customer: customer@example.com / customer123</small>
                    <small class="d-block">Team: team@example.com / team123</small>
                </div>
            </div>
        </div>

        <!-- Right Side - Image/Content -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-gradient-primary">
            <div class="text-center text-white">
                <img src="images/login-illustration.svg" alt="Login" class="img-fluid mb-4" style="max-height: 300px;">
                <h2 class="fw-bold mb-3">Plan Your Perfect Event</h2>
                <p class="fs-5 mb-4">Join our platform to access professional event management services</p>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="mb-2">
                            <i class="fas fa-users fa-3x opacity-75"></i>
                        </div>
                        <h5>Expert Teams</h5>
                        <small class="opacity-75">Professional event specialists</small>
                    </div>
                    <div class="col-4">
                        <div class="mb-2">
                            <i class="fas fa-calendar-check fa-3x opacity-75"></i>
                        </div>
                        <h5>Easy Booking</h5>
                        <small class="opacity-75">Simple request process</small>
                    </div>
                    <div class="col-4">
                        <div class="mb-2">
                            <i class="fas fa-star fa-3x opacity-75"></i>
                        </div>
                        <h5>Quality Service</h5>
                        <small class="opacity-75">Guaranteed satisfaction</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
</script>

<?php include_once("includes/footer.php"); ?>