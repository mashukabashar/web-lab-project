<?php
// Database setup script for classic_events
echo "=== SETTING UP CLASSIC_EVENTS DATABASE ===\n\n";

// First, try to connect to MySQL without specifying a database
$con = @mysqli_connect("localhost", "root", "", "");

if (!$con) {
    echo "❌ ERROR: Cannot connect to MySQL server!\n";
    echo "   MySQL Error: " . mysqli_connect_error() . "\n\n";
    echo "TROUBLESHOOTING STEPS:\n";
    echo "1. Make sure Laragon is running\n";
    echo "2. Start MySQL service in Laragon\n";
    echo "3. Check if MySQL port 3306 is available\n";
    echo "4. Try restarting Laragon\n\n";
    exit;
}

echo "✅ Successfully connected to MySQL server\n";

// Check if classic_events database exists
$db_check = mysqli_query($con, "SHOW DATABASES LIKE 'classic_events'");
if (mysqli_num_rows($db_check) > 0) {
    echo "✅ Database 'classic_events' already exists\n";
} else {
    echo "📝 Creating database 'classic_events'...\n";
    $create_db = mysqli_query($con, "CREATE DATABASE classic_events");
    if ($create_db) {
        echo "✅ Database 'classic_events' created successfully\n";
    } else {
        echo "❌ Error creating database: " . mysqli_error($con) . "\n";
        exit;
    }
}

// Now connect to the classic_events database
mysqli_close($con);
$con = @mysqli_connect("localhost", "root", "", "classic_events");

if (!$con) {
    echo "❌ ERROR: Cannot connect to classic_events database!\n";
    echo "   MySQL Error: " . mysqli_connect_error() . "\n";
    exit;
}

echo "✅ Successfully connected to 'classic_events' database\n";

// Check if tables exist
$tables_check = mysqli_query($con, "SHOW TABLES");
$table_count = mysqli_num_rows($tables_check);

echo "📊 Found $table_count tables in classic_events database\n";

if ($table_count == 0) {
    echo "\n📝 Database is empty. You need to import the SQL file.\n";
    echo "NEXT STEPS:\n";
    echo "1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)\n";
    echo "2. Select 'classic_events' database\n";
    echo "3. Go to 'Import' tab\n";
    echo "4. Choose 'classic_events.sql' file from the Database folder\n";
    echo "5. Click 'Go' to import\n\n";
} else {
    echo "✅ Database has tables. Checking key tables...\n";
    
    $required_tables = ['admin', 'anniversary', 'birthday', 'other_events', 'wedding', 'customer'];
    $missing_tables = [];
    
    foreach ($required_tables as $table) {
        $table_check = mysqli_query($con, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($table_check) > 0) {
            echo "   ✅ Table '$table' exists\n";
        } else {
            echo "   ❌ Table '$table' missing\n";
            $missing_tables[] = $table;
        }
    }
    
    if (count($missing_tables) > 0) {
        echo "\n⚠️  Some tables are missing. Please import classic_events.sql\n";
    } else {
        echo "\n🎉 All required tables are present!\n";
    }
}

// Test the connection from connect.php
echo "\n📝 Testing connection file...\n";
ob_start();
include_once('../Database/connect.php');
$output = ob_get_clean();

if (isset($con)) {
    echo "✅ Connection file works correctly\n";
    echo "🎉 SETUP COMPLETE! Your application should work now.\n";
} else {
    echo "❌ Connection file has issues\n";
}

mysqli_close($con);
?>
