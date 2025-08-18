<?php
require_once 'connect.php';

echo "=== CONVERTING TO BANGLADESHI TAKA (৳) ===\n\n";

// Conversion rate: 1 INR = 1.45 BDT (approximate)
$conversion_rate = 1.45;

echo "1. Converting database prices...\n";
echo "Conversion rate: 1 INR = {$conversion_rate} BDT\n\n";

// Tables with price columns
$price_tables = [
    'wedding' => 'price',
    'birthday' => 'price', 
    'anniversary' => 'price',
    'otherevent' => 'price',
    'booking' => ['price', 'advance_paid', 'remaining_amount']
];

foreach($price_tables as $table => $columns) {
    echo "Converting $table table...\n";
    
    if(is_array($columns)) {
        // Multiple price columns (booking table)
        foreach($columns as $column) {
            $update_query = "UPDATE $table SET $column = ROUND($column * $conversion_rate) WHERE $column IS NOT NULL AND $column > 0";
            if(mysqli_query($con, $update_query)) {
                $affected = mysqli_affected_rows($con);
                echo "  ✅ Updated $affected records in $column column\n";
            } else {
                echo "  ❌ Error updating $column: " . mysqli_error($con) . "\n";
            }
        }
    } else {
        // Single price column
        $update_query = "UPDATE $table SET $columns = ROUND($columns * $conversion_rate) WHERE $columns IS NOT NULL AND $columns > 0";
        if(mysqli_query($con, $update_query)) {
            $affected = mysqli_affected_rows($con);
            echo "  ✅ Updated $affected records in $columns column\n";
        } else {
            echo "  ❌ Error updating $columns: " . mysqli_error($con) . "\n";
        }
    }
}

echo "\n2. Verification - Sample converted prices:\n";

// Show sample converted prices
$tables = ['wedding', 'birthday', 'booking'];
foreach($tables as $table) {
    echo "\n$table prices (now in BDT):\n";
    $result = mysqli_query($con, "SELECT id, nm, price FROM $table LIMIT 3");
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "  ID: {$row['id']}, Name: {$row['nm']}, Price: ৳" . number_format($row['price']) . "\n";
        }
    }
}

echo "\n✅ Database conversion completed!\n";
echo "All prices have been converted from INR (₹) to BDT (৳)\n";
echo "Next: Updating display symbols in all files...\n";
?>
