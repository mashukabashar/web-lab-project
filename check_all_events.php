<?php
include('Database/connect.php');

echo "<h2>Checking All Event Types</h2>";

// Check Birthday table
echo "<h3>Birthday Table:</h3>";
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM birthday");
if($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Records found: " . $row['count'] . "<br>";
    
    if($row['count'] > 0) {
        $sample = mysqli_query($con, "SELECT id, nm, price FROM birthday LIMIT 3");
        echo "Sample records:<br>";
        while($data = mysqli_fetch_assoc($sample)) {
            echo "ID: {$data['id']}, Name: {$data['nm']}, Price: {$data['price']}<br>";
        }
    }
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
}

// Check Anniversary table
echo "<h3>Anniversary Table:</h3>";
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM anniversary");
if($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Records found: " . $row['count'] . "<br>";
    
    if($row['count'] > 0) {
        $sample = mysqli_query($con, "SELECT id, nm, price FROM anniversary LIMIT 3");
        echo "Sample records:<br>";
        while($data = mysqli_fetch_assoc($sample)) {
            echo "ID: {$data['id']}, Name: {$data['nm']}, Price: {$data['price']}<br>";
        }
    }
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
}

// Check Other Events table
echo "<h3>Other Events (Entertainment) Table:</h3>";
$result = mysqli_query($con, "SELECT COUNT(*) as count FROM otherevent");
if($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Records found: " . $row['count'] . "<br>";
    
    if($row['count'] > 0) {
        $sample = mysqli_query($con, "SELECT id, nm, price FROM otherevent LIMIT 3");
        echo "Sample records:<br>";
        while($data = mysqli_fetch_assoc($sample)) {
            echo "ID: {$data['id']}, Name: {$data['nm']}, Price: {$data['price']}<br>";
        }
    }
} else {
    echo "Error: " . mysqli_error($con) . "<br>";
}

// Test the event_details.php links
echo "<h3>Testing DETAILS Links:</h3>";
echo "Birthday details: <a href='event_details.php?type=birthday&id=1' target='_blank'>Test Birthday Details</a><br>";
echo "Anniversary details: <a href='event_details.php?type=anniversary&id=1' target='_blank'>Test Anniversary Details</a><br>";
echo "Entertainment details: <a href='event_details.php?type=other&id=1' target='_blank'>Test Entertainment Details</a><br>";
?>
