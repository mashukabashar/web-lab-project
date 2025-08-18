<?php
include('Database/connect.php');

echo "=== WEDDING IMAGES CHECK ===\n";

$result = mysqli_query($con, 'SELECT id, img, nm FROM wedding');
while($row = mysqli_fetch_assoc($result)) {
    $img_path = 'images/' . $row['img'];
    if(!file_exists($img_path)) {
        echo "❌ Missing image: {$img_path} (ID: {$row['id']}, Name: {$row['nm']})\n";
    } else {
        echo "✅ Found: {$img_path}\n";
    }
}
?>
