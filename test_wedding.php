<?php
include('Database/connect.php');

echo "<h2>Wedding System Test</h2>";

// Test 1: Check wedding table data
echo "<h3>Test 1: Wedding Data</h3>";
$result = mysqli_query($con, "SELECT id, nm, price FROM wedding LIMIT 3");
if($result && mysqli_num_rows($result) > 0) {
    echo "✅ Wedding data found:<br>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']} - Name: {$row['nm']} - Price: ৳" . number_format($row['price']) . "<br>";
        
        // Test event_details link
        echo "<a href='event_details.php?type=wedding&id={$row['id']}' target='_blank'>Test Details Link</a><br>";
        
        // Test book_wed link  
        echo "<a href='book_wed.php?id={$row['id']}' target='_blank'>Test Booking Link</a><br><br>";
    }
} else {
    echo "❌ No wedding data found<br>";
}

// Test 2: Check temp table structure
echo "<h3>Test 2: Temp Table Structure</h3>";
$result = mysqli_query($con, "DESCRIBE temp");
if($result) {
    echo "✅ Temp table structure:<br>";
    while($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . " - " . $row['Type'] . "<br>";
    }
} else {
    echo "❌ Temp table not found: " . mysqli_error($con) . "<br>";
}

// Test 3: Check if session is working
echo "<h3>Test 3: Session Test</h3>";
session_start();
if(isset($_SESSION['user_id'])) {
    echo "✅ User logged in - ID: " . $_SESSION['user_id'] . "<br>";
} else {
    echo "❌ No user session found. <a href='login.php'>Login required</a><br>";
}

echo "<br><a href='gallery.php'>← Back to Gallery</a>";
?>
