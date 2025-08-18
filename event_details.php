<?php 
include("header.php");
include("session.php");

$event_type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

include('Database/connect.php');

// Determine which table to query based on event type
$table_map = [
    'wedding' => 'wedding',
    'birthday' => 'birthday', 
    'anniversary' => 'anniversary',
    'other' => 'otherevent'
];

if(!isset($table_map[$event_type])) {
    echo "<script>alert('Invalid event type!'); window.location.assign('gallery.php');</script>";
    exit();
}

$table = $table_map[$event_type];
$list = mysqli_query($con, "SELECT * FROM $table WHERE id = $id");

if(!$list || mysqli_num_rows($list) == 0) {
    echo "<script>alert('Event not found!'); window.location.assign('gallery.php');</script>";
    exit();
}

$event = mysqli_fetch_assoc($list);

// Handle booking submission
if(isset($_POST['submit'])) {
    if(!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login first!'); window.location.assign('login.php');</script>";
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Clear any existing temp data for this user
    mysqli_query($con, "DELETE FROM temp WHERE user_id='$user_id'");
    
    // Insert new item into temp with user identification
    $qr1 = mysqli_query($con, "INSERT INTO temp (id, img, nm, price, user_id, created_at) VALUES('{$event['id']}','{$event['img']}','{$event['nm']}',{$event['price']},'$user_id',NOW())");
    
    if($qr1) {
        echo "<script>alert('Event added to cart!'); window.location.assign('cart.php');</script>";
    } else {
        echo "<script>alert('Failed to add to cart. Please try again.');</script>";
    }
}
?>

<style>
.event-details-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.event-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #b9722d;
    color: white;
    border-radius: 3px;
}

.event-image {
    width: 100%;
    max-width: 600px;
    height: 400px;
    object-fit: cover;
    border-radius: 3px;
    margin-bottom: 20px;
    border: 3px solid #b9722d;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.detail-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 3px;
    border-left: 4px solid #b9722d;
}

.detail-section h3 {
    color: #b9722d;
    margin-bottom: 15px;
    font-size: 1.2em;
    font-family: 'Abel', sans-serif;
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
    position: relative;
    padding-left: 25px;
}

.feature-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #b9722d;
    font-weight: bold;
}

.price-section {
    background: #b9722d;
    color: white;
    padding: 20px;
    border-radius: 3px;
    text-align: center;
    margin-bottom: 20px;
}

.price-amount {
    font-size: 2.5em;
    font-weight: bold;
    margin: 10px 0;
    font-family: 'Abel', sans-serif;
}

.booking-section {
    text-align: center;
    padding: 20px;
}

.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #b9722d;
    text-decoration: none;
    font-weight: bold;
    font-family: 'Abel', sans-serif;
}

.back-link:hover {
    text-decoration: underline;
    color: #b9722d;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .event-details-container {
        margin: 10px;
        padding: 15px;
    }
}
</style>

<div class="event-details-container">
    <a href="gallery.php" class="back-link">← Back to Gallery</a>
    
    <div class="event-header">
        <h1><?php echo htmlspecialchars($event['nm']); ?></h1>
        <?php if($event['team_name']): ?>
            <p><strong>By: <?php echo htmlspecialchars($event['team_name']); ?></strong></p>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <img src="images/<?php echo htmlspecialchars($event['img']); ?>" alt="<?php echo htmlspecialchars($event['nm']); ?>" class="event-image">
    </div>

    <div class="details-grid">
        <div class="detail-section">
            <h3>📝 Event Details</h3>
            <p><?php echo $event['details'] ? htmlspecialchars($event['details']) : 'Detailed description coming soon...'; ?></p>
        </div>

        <div class="detail-section">
            <h3>🎯 Key Features</h3>
            <?php if($event['features']): ?>
                <ul class="feature-list">
                    <?php 
                    $features = explode(',', $event['features']);
                    foreach($features as $feature): 
                    ?>
                        <li><?php echo trim(htmlspecialchars($feature)); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Feature list will be updated soon...</p>
            <?php endif; ?>
        </div>

        <?php if($event_type == 'wedding'): ?>
            <div class="detail-section">
                <h3>💒 Wedding Specifications</h3>
                <p><strong>Duration:</strong> <?php echo $event['duration'] ?? 'To be confirmed'; ?></p>
                <p><strong>Guest Capacity:</strong> <?php echo $event['guest_capacity'] ? $event['guest_capacity'] . ' guests' : 'Flexible'; ?></p>
                <p><strong>Venue Type:</strong> <?php echo $event['venue_type'] ?? 'Various options'; ?></p>
                <p><strong>Catering:</strong> <?php echo $event['catering_type'] ?? 'Available'; ?></p>
                <p><strong>Photography:</strong> <?php echo $event['photography'] ? '✅ Included' : '❌ Not included'; ?></p>
                <p><strong>Music/DJ:</strong> <?php echo $event['music_dj'] ? '✅ Included' : '❌ Not included'; ?></p>
            </div>
        <?php elseif($event_type == 'birthday'): ?>
            <div class="detail-section">
                <h3>🎂 Birthday Party Specifications</h3>
                <p><strong>Age Group:</strong> <?php echo $event['age_group'] ?? 'All ages'; ?></p>
                <p><strong>Theme Style:</strong> <?php echo $event['theme_style'] ?? 'Custom'; ?></p>
                <p><strong>Guest Capacity:</strong> <?php echo $event['guest_capacity'] ? $event['guest_capacity'] . ' guests' : 'Flexible'; ?></p>
                <p><strong>Entertainment:</strong> <?php echo $event['entertainment'] ?? 'Various options'; ?></p>
                <p><strong>Cake Included:</strong> <?php echo $event['cake_included'] ? '✅ Yes' : '❌ No'; ?></p>
            </div>
        <?php elseif($event_type == 'anniversary'): ?>
            <div class="detail-section">
                <h3>💕 Anniversary Specifications</h3>
                <p><strong>Anniversary Year:</strong> <?php echo $event['anniversary_year'] ?? 'Any year'; ?></p>
                <p><strong>Romantic Theme:</strong> <?php echo $event['romantic_theme'] ?? 'Classic'; ?></p>
                <p><strong>Guest Capacity:</strong> <?php echo $event['guest_capacity'] ? $event['guest_capacity'] . ' guests' : 'Flexible'; ?></p>
                <p><strong>Dining Style:</strong> <?php echo $event['dining_style'] ?? 'Flexible'; ?></p>
                <p><strong>Special Surprises:</strong> <?php echo $event['special_surprises'] ?? 'Available'; ?></p>
            </div>
        <?php elseif($event_type == 'other'): ?>
            <div class="detail-section">
                <h3>🎪 Event Specifications</h3>
                <p><strong>Event Type:</strong> <?php echo $event['event_type'] ?? 'Entertainment'; ?></p>
                <p><strong>Duration:</strong> <?php echo $event['duration'] ?? 'Flexible'; ?></p>
                <p><strong>Guest Capacity:</strong> <?php echo $event['guest_capacity'] ? $event['guest_capacity'] . ' guests' : 'Flexible'; ?></p>
                <p><strong>Equipment:</strong> <?php echo $event['equipment_included'] ?? 'Professional setup'; ?></p>
                <p><strong>Staff:</strong> <?php echo $event['staff_provided'] ?? 'Professional team'; ?></p>
            </div>
        <?php endif; ?>

        <div class="detail-section">
            <h3>🎨 Decorations & Inclusions</h3>
            <?php if($event['decorations_included']): ?>
                <ul class="feature-list">
                    <?php 
                    $decorations = explode(',', $event['decorations_included']);
                    foreach($decorations as $decoration): 
                    ?>
                        <li><?php echo trim(htmlspecialchars($decoration)); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Decoration details will be provided upon booking...</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="price-section">
        <h2>Package Price</h2>
        <div class="price-amount">৳<?php echo number_format($event['price']); ?></div>
        <p>Professional event management with complete setup</p>
    </div>

    <div class="booking-section">
        <form method="post">
            <input type="submit" name="submit" value="ADD TO CART & BOOK NOW" class="btn my" style="font-size: 1.1em; padding: 12px 30px;"/>
        </form>
        <p style="margin-top: 15px; color: #666; font-family: 'Roboto', sans-serif;">
            Secure booking • Professional team • Customer satisfaction guaranteed
        </p>
    </div>
</div>

<?php include("footer.php"); ?>
