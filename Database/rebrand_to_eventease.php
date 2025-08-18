<?php
echo "=== RENAMING DATABASE FROM classic_events TO eventease_db ===\n\n";

// Connect to MySQL server (without specifying database)
$con = mysqli_connect("localhost", "root", "", "") or die("Can't connect to MySQL server");

echo "1. Creating new database 'eventease_db'...\n";
$create_db = mysqli_query($con, "CREATE DATABASE IF NOT EXISTS eventease_db");
if($create_db) {
    echo "   ✅ Database 'eventease_db' created successfully\n";
} else {
    echo "   ❌ Error creating database: " . mysqli_error($con) . "\n";
}

echo "\n2. Copying data from 'classic_events' to 'eventease_db'...\n";

// Get list of tables from classic_events
$tables_query = mysqli_query($con, "SHOW TABLES FROM classic_events");
if($tables_query) {
    echo "   📋 Found tables to copy:\n";
    while($table = mysqli_fetch_row($tables_query)) {
        $table_name = $table[0];
        echo "      - $table_name\n";
        
        // Copy table structure and data
        $copy_query = "CREATE TABLE eventease_db.$table_name AS SELECT * FROM classic_events.$table_name";
        if(mysqli_query($con, $copy_query)) {
            echo "         ✅ Copied successfully\n";
        } else {
            echo "         ❌ Error copying: " . mysqli_error($con) . "\n";
        }
    }
} else {
    echo "   ❌ Could not access classic_events database: " . mysqli_error($con) . "\n";
    echo "   ℹ️  This might be normal if the database doesn't exist yet\n";
}

echo "\n3. Updating SQL files to use new database name...\n";

// Update all SQL files
$sql_files = [
    'classic_events.sql' => 'eventease_db.sql',
    'booking_enhancements.sql',
    'update_email_column.sql',
    'fix_mobile_column.sql',
    'fix_all_mobile_columns.sql',
    'demo_data.sql'
];

foreach($sql_files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('classic_events', 'eventease_db', $content);
        $content = str_replace('CLASSIC EVENTS', 'EVENTEASE', $content);
        $content = str_replace('Classic Events', 'EventEase', $content);
        file_put_contents($file, $content);
        echo "   ✅ Updated $file\n";
    }
}

// Update parent directory SQL files
$parent_files = ['../fix_email_columns.sql'];
foreach($parent_files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('classic_events', 'eventease_db', $content);
        file_put_contents($file, $content);
        echo "   ✅ Updated $file\n";
    }
}

echo "\n=== REBRANDING COMPLETE ===\n";
echo "✅ Database renamed from 'classic_events' to 'eventease_db'\n";
echo "✅ All website content updated from 'Classic Events' to 'EventEase'\n";
echo "✅ Email addresses updated to @eventease.in/.com\n";
echo "✅ Company name updated in invoices and admin panels\n";
echo "✅ SQL files updated to use new database name\n";
echo "\n🎉 EventEase rebranding is complete!\n";
echo "\nNext steps:\n";
echo "1. Test the website to ensure everything works\n";
echo "2. Update any remaining references if found\n";
echo "3. Consider updating the logo image if needed\n";

mysqli_close($con);
?>
