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

// Define basic services for legacy system
$all_services = [
    ['id' => 1, 'name' => 'Event Planning', 'category_name' => 'Wedding', 'team_category_name' => 'Planning'],
    ['id' => 2, 'name' => 'Catering', 'category_name' => 'Wedding', 'team_category_name' => 'Catering'],
    ['id' => 3, 'name' => 'Photography', 'category_name' => 'Wedding', 'team_category_name' => 'Photography'],
    ['id' => 4, 'name' => 'Decoration', 'category_name' => 'Wedding', 'team_category_name' => 'Decoration'],
    ['id' => 5, 'name' => 'Party Planning', 'category_name' => 'Birthday', 'team_category_name' => 'Planning'],
    ['id' => 6, 'name' => 'Celebration Setup', 'category_name' => 'Anniversary', 'team_category_name' => 'Planning'],
    ['id' => 7, 'name' => 'Event Coordination', 'category_name' => 'Other Events', 'team_category_name' => 'Planning']
];

// Group services by category (for legacy system, just organize by name)
$services_by_category = [];
foreach ($all_services as $service) {
    $categoryName = $service['category_name'];
    $services_by_category[$categoryName][] = $service;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_category_id = $_POST['event_category_id'] ?? '';
    $event_title = trim($_POST['event_title'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $guest_count = $_POST['guest_count'] ?? '';
    $special_requirements = trim($_POST['special_requirements'] ?? '');
    $selected_services = $_POST['services'] ?? [];
    
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
        if (!empty($selected_services)) {
            $description .= "\nSelected Services: " . implode(', ', $selected_services);
        }
        
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
            $selected_services = [];
        } else {
            $error_message = 'Failed to submit your request. Please try again.';
        }
            $stmt = $db->prepare("
                INSERT INTO service_requests 
                (customer_id, event_category_id, event_title, event_date, event_time, venue, guest_count, special_requirements, total_amount) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $customer_id, $event_category_id, $event_title, $event_date, 
                $event_time ?: null, $venue, $guest_count ?: null, $special_requirements, $total_amount
            ]);
            
            $request_id = $db->lastInsertId();
            
            // Add requested services
            foreach ($service_details as $detail) {
                $stmt = $db->prepare("
                    INSERT INTO request_services (request_id, service_id, quantity, unit_price, total_price) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $request_id, $detail['service_id'], $detail['quantity'], 
                    $detail['unit_price'], $detail['total_price']
                ]);
            }
            
            // Create notification for admin
            $stmt = $db->prepare("SELECT id FROM users WHERE role = 'admin'");
            $stmt->execute();
            $admins = $stmt->fetchAll();
            
            foreach ($admins as $admin) {
                NotificationManager::create(
                    $admin['id'],
                    'New Service Request',
                    "A new service request for '{$event_title}' has been submitted by " . $_SESSION['user_name'],
                    'info'
                );
            }
            
            $db->commit();
            
            $success_message = 'Your service request has been submitted successfully! You will receive a notification once it is reviewed.';
            
            // Clear form
            $event_category_id = $event_title = $event_date = $event_time = $venue = $guest_count = $special_requirements = '';
            $selected_services = [];
            
        } catch (Exception $e) {
            $db->rollBack();
            $error_message = 'Failed to submit request. Please try again.';
            error_log($e->getMessage());
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
                <p class="text-muted">Tell us about your event and select the services you need</p>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="needs-validation" novalidate id="serviceRequestForm">
                <div class="row">
                    <!-- Event Details -->
                    <div class="col-lg-6">
                        <div class="card shadow-soft mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Event Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="event_category_id" class="form-label">Event Type *</label>
                                    <select class="form-select" id="event_category_id" name="event_category_id" required>
                                        <option value="">Select event type</option>
                                        <?php foreach ($event_categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo (isset($event_category_id) && $event_category_id == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Please select an event type.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="event_title" class="form-label">Event Title *</label>
                                    <input type="text" class="form-control" id="event_title" name="event_title" 
                                           value="<?php echo htmlspecialchars($event_title ?? ''); ?>" 
                                           placeholder="e.g., John & Jane's Wedding" required>
                                    <div class="invalid-feedback">Please enter an event title.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="event_date" class="form-label">Event Date *</label>
                                        <input type="date" class="form-control" id="event_date" name="event_date" 
                                               value="<?php echo htmlspecialchars($event_date ?? ''); ?>" 
                                               min="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                                        <div class="invalid-feedback">Please select an event date (minimum 7 days from today).</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <input type="time" class="form-control" id="event_time" name="event_time" 
                                               value="<?php echo htmlspecialchars($event_time ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="venue" class="form-label">Venue</label>
                                    <textarea class="form-control" id="venue" name="venue" rows="2" 
                                              placeholder="Event venue address"><?php echo htmlspecialchars($venue ?? ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="guest_count" class="form-label">Expected Number of Guests</label>
                                    <input type="number" class="form-control" id="guest_count" name="guest_count" 
                                           value="<?php echo htmlspecialchars($guest_count ?? ''); ?>" min="1">
                                </div>

                                <div class="mb-0">
                                    <label for="special_requirements" class="form-label">Special Requirements</label>
                                    <textarea class="form-control" id="special_requirements" name="special_requirements" rows="3" 
                                              placeholder="Any special requirements or notes for your event"><?php echo htmlspecialchars($special_requirements ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Selection -->
                    <div class="col-lg-6">
                        <div class="card shadow-soft mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs me-2"></i>
                                    Select Services *
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="services-container">
                                    <p class="text-muted">Please select an event type first to see available services.</p>
                                </div>
                                
                                <div class="mt-3 p-3 bg-light rounded" id="total-section" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Estimated Total:</strong>
                                        <strong class="text-primary fs-5" id="total-amount">$0.00</strong>
                                    </div>
                                    <small class="text-muted">Final amount may vary based on admin review</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-paper-plane me-2"></i>Submit Request
                    </button>
                    <a href="customer/dashboard.php" class="btn btn-outline-secondary btn-lg px-5 ms-3">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Services data
const servicesByCategory = <?php echo json_encode($services_by_category); ?>;

// Event category change handler
document.getElementById('event_category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const container = document.getElementById('services-container');
    
    if (!categoryId) {
        container.innerHTML = '<p class="text-muted">Please select an event type first to see available services.</p>';
        document.getElementById('total-section').style.display = 'none';
        return;
    }
    
    const services = servicesByCategory[categoryId] || [];
    
    if (services.length === 0) {
        container.innerHTML = '<p class="text-muted">No services available for this event type.</p>';
        document.getElementById('total-section').style.display = 'none';
        return;
    }
    
    // Group services by team category
    const servicesByTeamCategory = {};
    services.forEach(service => {
        if (!servicesByTeamCategory[service.team_category_name]) {
            servicesByTeamCategory[service.team_category_name] = [];
        }
        servicesByTeamCategory[service.team_category_name].push(service);
    });
    
    let html = '';
    Object.keys(servicesByTeamCategory).forEach(teamCategory => {
        html += `<h6 class="mt-3 mb-2 text-primary">${teamCategory}</h6>`;
        servicesByTeamCategory[teamCategory].forEach(service => {
            html += `
                <div class="service-item border rounded p-3 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input service-checkbox" 
                               id="service_${service.id}" name="services[]" value="${service.id}">
                        <label class="form-check-label w-100" for="service_${service.id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${service.name}</strong>
                                    <p class="mb-1 text-muted small">${service.description || ''}</p>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-primary">$${parseFloat(service.price).toFixed(2)}</div>
                                </div>
                            </div>
                            <div class="quantity-section mt-2" style="display: none;">
                                <label class="form-label small">Quantity:</label>
                                <input type="number" class="form-control form-control-sm quantity-input" 
                                       name="quantity_${service.id}" value="1" min="1" style="width: 80px;">
                            </div>
                        </label>
                    </div>
                </div>
            `;
        });
    });
    
    container.innerHTML = html;
    document.getElementById('total-section').style.display = 'block';
    
    // Add event listeners for service checkboxes
    document.querySelectorAll('.service-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const quantitySection = this.closest('.service-item').querySelector('.quantity-section');
            if (this.checked) {
                quantitySection.style.display = 'block';
            } else {
                quantitySection.style.display = 'none';
            }
            updateTotal();
        });
    });
    
    // Add event listeners for quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', updateTotal);
    });
    
    updateTotal();
});

function updateTotal() {
    let total = 0;
    
    document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
        const serviceId = checkbox.value;
        const serviceItem = checkbox.closest('.service-item');
        const priceText = serviceItem.querySelector('.text-primary').textContent;
        const price = parseFloat(priceText.replace('$', ''));
        const quantityInput = serviceItem.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 1;
        
        total += price * quantity;
    });
    
    document.getElementById('total-amount').textContent = '$' + total.toFixed(2);
}

// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        const form = document.getElementById('serviceRequestForm');
        form.addEventListener('submit', function(event) {
            // Check if at least one service is selected
            const selectedServices = document.querySelectorAll('.service-checkbox:checked');
            if (selectedServices.length === 0) {
                event.preventDefault();
                event.stopPropagation();
                alert('Please select at least one service for your event.');
                return false;
            }
            
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }, false);
})();
</script>

<?php include_once("includes/footer.php"); ?>
