<?php
$page_title = 'Request Service';
require_once 'Database/compatibility.php';

// Check if user is logged in
LegacyAuth::requireLogin();

$customer_id = LegacyAuth::getUserId();

$error_message = '';
$success_message = '';

// Define event categories for legacy system
$event_categories = [
    ['id' => 1, 'name' => 'Wedding', 'slug' => 'wedding'],
    ['id' => 2, 'name' => 'Birthday', 'slug' => 'birthday'],
    ['id' => 3, 'name' => 'Anniversary', 'slug' => 'anniversary'],
    ['id' => 4, 'name' => 'Other Events', 'slug' => 'other']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_category_id = $_POST['event_category_id'] ?? '';
    $event_title = trim($_POST['event_title'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $guest_count = $_POST['guest_count'] ?? '';
    $special_requirements = trim($_POST['special_requirements'] ?? '');
    
    // Validation
    if (empty($event_category_id) || empty($event_title) || empty($event_date)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (strtotime($event_date) < strtotime('+7 days')) {
        $error_message = 'Event date must be at least 7 days from now.';
    } else {
        // Find category slug for legacy table
        $categorySlug = 'other';
        foreach ($event_categories as $category) {
            if ($category['id'] == $event_category_id) {
                $categorySlug = $category['slug'];
                break;
            }
        }
        
        // Create service request in legacy system
        $description = $event_title . "\n" . $special_requirements;
        
        $request_id = LegacyServiceRequests::createRequest(
            $customer_id, 
            $categorySlug, 
            $event_date, 
            $venue, 
            $guest_count, 
            $description
        );
        
        if ($request_id) {
            $success_message = 'Your service request has been submitted successfully! We will contact you soon.';
            // Clear form
            $event_category_id = $event_title = $event_date = $event_time = $venue = $guest_count = $special_requirements = '';
        } else {
            $error_message = 'Failed to submit your request. Please try again.';
        }
    }
}

include_once("includes/header.php");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary">Request Event Service</h1>
                <p class="text-muted">Tell us about your event and we'll help make it perfect</p>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Event Details
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <!-- Event Category -->
                            <div class="col-md-6 mb-4">
                                <label for="event_category_id" class="form-label fw-bold">
                                    Event Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="event_category_id" name="event_category_id" required>
                                    <option value="">Select Event Type</option>
                                    <?php foreach ($event_categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" 
                                                <?php echo ($event_category_id == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Event Title -->
                            <div class="col-md-6 mb-4">
                                <label for="event_title" class="form-label fw-bold">
                                    Event Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="event_title" name="event_title" 
                                       value="<?php echo htmlspecialchars($event_title ?? ''); ?>" 
                                       placeholder="e.g., John & Jane Wedding" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Event Date -->
                            <div class="col-md-6 mb-4">
                                <label for="event_date" class="form-label fw-bold">
                                    Event Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="event_date" name="event_date" 
                                       value="<?php echo htmlspecialchars($event_date ?? ''); ?>" 
                                       min="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                            </div>

                            <!-- Event Time -->
                            <div class="col-md-6 mb-4">
                                <label for="event_time" class="form-label fw-bold">Event Time</label>
                                <input type="time" class="form-control" id="event_time" name="event_time" 
                                       value="<?php echo htmlspecialchars($event_time ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Venue -->
                            <div class="col-md-6 mb-4">
                                <label for="venue" class="form-label fw-bold">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue" 
                                       value="<?php echo htmlspecialchars($venue ?? ''); ?>" 
                                       placeholder="Event venue or location">
                            </div>

                            <!-- Guest Count -->
                            <div class="col-md-6 mb-4">
                                <label for="guest_count" class="form-label fw-bold">Expected Guests</label>
                                <input type="number" class="form-control" id="guest_count" name="guest_count" 
                                       value="<?php echo htmlspecialchars($guest_count ?? ''); ?>" 
                                       min="1" placeholder="Number of guests">
                            </div>
                        </div>

                        <!-- Special Requirements -->
                        <div class="mb-4">
                            <label for="special_requirements" class="form-label fw-bold">Special Requirements</label>
                            <textarea class="form-control" id="special_requirements" name="special_requirements" 
                                      rows="4" placeholder="Any special requirements, dietary restrictions, theme preferences, etc."><?php echo htmlspecialchars($special_requirements ?? ''); ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>

<script>
// Form validation
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
})();
</script>
