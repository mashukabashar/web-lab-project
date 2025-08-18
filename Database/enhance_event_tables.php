<?php
include('connect.php');

echo "=== ENHANCING EVENT TABLES WITH DETAILED INFORMATION ===\n\n";

// Enhanced event tables structure
$enhancements = [
    'wedding' => [
        'team_name' => 'VARCHAR(100) DEFAULT NULL',
        'details' => 'TEXT DEFAULT NULL',
        'features' => 'TEXT DEFAULT NULL',
        'duration' => 'VARCHAR(50) DEFAULT NULL',
        'guest_capacity' => 'INT DEFAULT NULL',
        'venue_type' => 'VARCHAR(100) DEFAULT NULL',
        'decorations_included' => 'TEXT DEFAULT NULL',
        'catering_type' => 'VARCHAR(100) DEFAULT NULL',
        'photography' => 'BOOLEAN DEFAULT FALSE',
        'music_dj' => 'BOOLEAN DEFAULT FALSE',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'birthday' => [
        'team_name' => 'VARCHAR(100) DEFAULT NULL',
        'details' => 'TEXT DEFAULT NULL',
        'features' => 'TEXT DEFAULT NULL',
        'age_group' => 'VARCHAR(50) DEFAULT NULL',
        'theme_style' => 'VARCHAR(100) DEFAULT NULL',
        'guest_capacity' => 'INT DEFAULT NULL',
        'decorations_included' => 'TEXT DEFAULT NULL',
        'entertainment' => 'TEXT DEFAULT NULL',
        'cake_included' => 'BOOLEAN DEFAULT FALSE',
        'games_activities' => 'TEXT DEFAULT NULL',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'anniversary' => [
        'team_name' => 'VARCHAR(100) DEFAULT NULL',
        'details' => 'TEXT DEFAULT NULL',
        'features' => 'TEXT DEFAULT NULL',
        'anniversary_year' => 'VARCHAR(50) DEFAULT NULL',
        'romantic_theme' => 'VARCHAR(100) DEFAULT NULL',
        'guest_capacity' => 'INT DEFAULT NULL',
        'decorations_included' => 'TEXT DEFAULT NULL',
        'dining_style' => 'VARCHAR(100) DEFAULT NULL',
        'music_entertainment' => 'TEXT DEFAULT NULL',
        'special_surprises' => 'TEXT DEFAULT NULL',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'otherevent' => [
        'team_name' => 'VARCHAR(100) DEFAULT NULL',
        'details' => 'TEXT DEFAULT NULL',
        'features' => 'TEXT DEFAULT NULL',
        'event_type' => 'VARCHAR(100) DEFAULT NULL',
        'duration' => 'VARCHAR(50) DEFAULT NULL',
        'guest_capacity' => 'INT DEFAULT NULL',
        'venue_requirements' => 'TEXT DEFAULT NULL',
        'equipment_included' => 'TEXT DEFAULT NULL',
        'staff_provided' => 'TEXT DEFAULT NULL',
        'special_services' => 'TEXT DEFAULT NULL',
        'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ]
];

// Apply enhancements to each table
foreach($enhancements as $table => $columns) {
    echo "Enhancing $table table...\n";
    
    foreach($columns as $column => $definition) {
        $sql = "ALTER TABLE $table ADD COLUMN $column $definition";
        
        // Check if column already exists
        $check = mysqli_query($con, "SHOW COLUMNS FROM $table LIKE '$column'");
        
        if(mysqli_num_rows($check) == 0) {
            if(mysqli_query($con, $sql)) {
                echo "✅ Added column: $column\n";
            } else {
                echo "❌ Failed to add column $column: " . mysqli_error($con) . "\n";
            }
        } else {
            echo "⚠️ Column $column already exists\n";
        }
    }
    echo "\n";
}

echo "=== UPDATED TABLE STRUCTURES ===\n\n";

foreach(array_keys($enhancements) as $table) {
    echo strtoupper($table) . " TABLE STRUCTURE:\n";
    $result = mysqli_query($con, "DESCRIBE $table");
    while($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n";
}

echo "🚀 Event tables enhanced with detailed information!\n";
?>
