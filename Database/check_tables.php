<?php
$con = mysqli_connect("localhost", "root", "", "classic_events");
if ($con) {
    echo "Tables in classic_events database:\n";
    $result = mysqli_query($con, "SHOW TABLES");
    while($row = mysqli_fetch_array($result)) {
        echo "- " . $row[0] . "\n";
    }
    mysqli_close($con);
} else {
    echo "Cannot connect to database\n";
}
?>
