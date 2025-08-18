<?php
include('connect.php');

echo "=== ADDING SAMPLE DETAILED EVENT DATA ===\n\n";

// Sample detailed wedding data
$wedding_updates = [
    1 => [
        'team_name' => 'Royal Wedding Specialists',
        'details' => 'A luxurious yellow rajwadi theme wedding setup with traditional decorations, royal seating arrangements, and premium floral designs.',
        'features' => 'Traditional Rajasthani decor, Gold-plated accessories, Premium flower arrangements, Royal seating, Professional lighting, Traditional music setup',
        'duration' => '6-8 hours',
        'guest_capacity' => 500,
        'venue_type' => 'Banquet Hall / Outdoor Garden',
        'decorations_included' => 'Mandap decoration, Stage setup, Entrance gate, Flower petals, Traditional drapes',
        'catering_type' => 'Multi-cuisine buffet',
        'photography' => 1,
        'music_dj' => 1
    ],
    5 => [
        'team_name' => 'Snow White Events',
        'details' => 'Elegant snow white themed wedding with pristine white decorations, crystal accessories, and sophisticated floral arrangements.',
        'features' => 'Pure white theme, Crystal decorations, White roses and lilies, Elegant draping, LED lighting, Piano music',
        'duration' => '6-8 hours', 
        'guest_capacity' => 300,
        'venue_type' => 'Indoor Banquet / Garden',
        'decorations_included' => 'White mandap, Crystal chandeliers, White carpet, Flower arches, Table centerpieces',
        'catering_type' => 'Continental & Indian buffet',
        'photography' => 1,
        'music_dj' => 1
    ]
];

// Sample detailed birthday data
$birthday_updates = [
    1 => [
        'team_name' => 'Pink Paradise Party Planners',
        'details' => 'Adorable baby pink balloon birthday theme perfect for little princesses with soft pink decorations and cute balloon arrangements.',
        'features' => 'Baby pink balloons, Unicorn decorations, Princess crown accessories, Pink table setup, Fairy lights, Photo booth',
        'age_group' => '1-8 years',
        'theme_style' => 'Princess/Unicorn Theme',
        'guest_capacity' => 50,
        'decorations_included' => 'Pink balloon arch, Princess banner, Table decorations, Chair covers, Backdrop',
        'entertainment' => 'Magic show, Face painting, Games, Music',
        'cake_included' => 1,
        'games_activities' => 'Musical chairs, Treasure hunt, Pin the tail, Balloon popping games'
    ],
    2 => [
        'team_name' => 'Minion Madness Events',
        'details' => 'Fun-filled minion themed birthday party with yellow and blue decorations, minion characters, and exciting activities.',
        'features' => 'Minion balloons, Character cutouts, Yellow-blue theme, Minion games, Photo props, Themed tableware',
        'age_group' => '3-12 years',
        'theme_style' => 'Minion/Cartoon Theme',
        'guest_capacity' => 80,
        'decorations_included' => 'Minion balloons, Character banners, Themed table setup, Wall decorations',
        'entertainment' => 'Minion character appearance, Games, Dance, Music',
        'cake_included' => 1,
        'games_activities' => 'Minion treasure hunt, Banana race, Character quiz, Dance competition'
    ]
];

// Sample detailed anniversary data
$anniversary_updates = [
    1 => [
        'team_name' => 'Romantic Moments Team',
        'details' => 'Beautiful balloon decoration anniversary setup with heart-shaped arrangements and romantic ambiance.',
        'features' => 'Heart balloons, Romantic lighting, Red roses, Candle arrangements, Photo memories display, Soft music',
        'anniversary_year' => '1st-25th Anniversary',
        'romantic_theme' => 'Classic Romantic',
        'guest_capacity' => 100,
        'decorations_included' => 'Heart balloon arch, Rose petals, Candles, Photo frames, Romantic backdrop',
        'dining_style' => 'Candlelight dinner setup',
        'music_entertainment' => 'Live acoustic music, Romantic playlist',
        'special_surprises' => 'Memory lane photo display, Surprise video messages, Anniversary cake'
    ],
    2 => [
        'team_name' => 'Elegant Table Decor Specialists',
        'details' => 'Sophisticated table decoration anniversary setup with elegant centerpieces and classy arrangements.',
        'features' => 'Elegant table settings, Crystal centerpieces, Fresh flowers, Ambient lighting, Premium linens',
        'anniversary_year' => '5th-50th Anniversary',
        'romantic_theme' => 'Elegant & Classic',
        'guest_capacity' => 150,
        'decorations_included' => 'Table centerpieces, Floral arrangements, Elegant draping, Mood lighting',
        'dining_style' => 'Formal dining setup',
        'music_entertainment' => 'Instrumental music, Classical playlist',
        'special_surprises' => 'Anniversary toast, Special menu, Couple photo session'
    ]
];

// Sample detailed other events data
$otherevent_updates = [
    1 => [
        'team_name' => 'Club DJ Masters',
        'details' => 'High-energy DJ party setup for club-style entertainment with professional sound systems and lighting.',
        'features' => 'Professional DJ setup, High-quality sound system, LED lighting, Dance floor, Smoke machines, Laser lights',
        'event_type' => 'DJ Party/Club Event',
        'duration' => '4-6 hours',
        'guest_capacity' => 200,
        'venue_requirements' => 'Indoor venue with power supply, dance floor space',
        'equipment_included' => 'DJ console, Speakers, Microphones, Lighting equipment, Fog machines',
        'staff_provided' => 'Professional DJ, Sound technician, Lighting operator',
        'special_services' => 'Custom playlist, Live mixing, Interactive entertainment'
    ],
    2 => [
        'team_name' => 'Wedding Entertainment Pros',
        'details' => 'Complete wedding entertainment package with music, dance, and celebration activities.',
        'features' => 'Live music, Dance performances, Entertainment shows, Interactive games, Professional MC',
        'event_type' => 'Wedding Entertainment',
        'duration' => '3-5 hours',
        'guest_capacity' => 300,
        'venue_requirements' => 'Stage area, sound system setup, performance space',
        'equipment_included' => 'Sound system, Microphones, Stage setup, Performance equipment',
        'staff_provided' => 'MC, Musicians, Dancers, Entertainment coordinators',
        'special_services' => 'Custom choreography, Traditional performances, Interactive entertainment'
    ]
];

// Update wedding records
foreach($wedding_updates as $id => $data) {
    $sets = [];
    foreach($data as $field => $value) {
        if(is_string($value)) {
            $sets[] = "$field = '" . mysqli_real_escape_string($con, $value) . "'";
        } else {
            $sets[] = "$field = $value";
        }
    }
    $sql = "UPDATE wedding SET " . implode(', ', $sets) . " WHERE id = $id";
    if(mysqli_query($con, $sql)) {
        echo "✅ Updated wedding record ID: $id\n";
    } else {
        echo "❌ Failed to update wedding record ID: $id - " . mysqli_error($con) . "\n";
    }
}

// Update birthday records
foreach($birthday_updates as $id => $data) {
    $sets = [];
    foreach($data as $field => $value) {
        if(is_string($value)) {
            $sets[] = "$field = '" . mysqli_real_escape_string($con, $value) . "'";
        } else {
            $sets[] = "$field = $value";
        }
    }
    $sql = "UPDATE birthday SET " . implode(', ', $sets) . " WHERE id = $id";
    if(mysqli_query($con, $sql)) {
        echo "✅ Updated birthday record ID: $id\n";
    } else {
        echo "❌ Failed to update birthday record ID: $id - " . mysqli_error($con) . "\n";
    }
}

// Update anniversary records
foreach($anniversary_updates as $id => $data) {
    $sets = [];
    foreach($data as $field => $value) {
        if(is_string($value)) {
            $sets[] = "$field = '" . mysqli_real_escape_string($con, $value) . "'";
        } else {
            $sets[] = "$field = $value";
        }
    }
    $sql = "UPDATE anniversary SET " . implode(', ', $sets) . " WHERE id = $id";
    if(mysqli_query($con, $sql)) {
        echo "✅ Updated anniversary record ID: $id\n";
    } else {
        echo "❌ Failed to update anniversary record ID: $id - " . mysqli_error($con) . "\n";
    }
}

// Update other event records
foreach($otherevent_updates as $id => $data) {
    $sets = [];
    foreach($data as $field => $value) {
        if(is_string($value)) {
            $sets[] = "$field = '" . mysqli_real_escape_string($con, $value) . "'";
        } else {
            $sets[] = "$field = $value";
        }
    }
    $sql = "UPDATE otherevent SET " . implode(', ', $sets) . " WHERE id = $id";
    if(mysqli_query($con, $sql)) {
        echo "✅ Updated other event record ID: $id\n";
    } else {
        echo "❌ Failed to update other event record ID: $id - " . mysqli_error($con) . "\n";
    }
}

echo "\n🎉 Sample detailed event data added successfully!\n";
echo "\nNext step: Create enhanced display pages to show all these details to customers.\n";
?>
