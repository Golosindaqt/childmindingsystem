<?php
$logfile = '/home/your_username/cron_log.txt';
$log = fopen($logfile, 'a');
fwrite($log, "Cron job run at " . date('Y-m-d H:i:s') . "\n");

$servername = "localhost";
$username = "u865964754_childcare";
$password = "U865964754_childcare+";
$dbname = "u865964754_childcare";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    fwrite($log, "Connection failed: " . $conn->connect_error . "\n");
    fclose($log);
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE session SET morning_slots = 10, afternoon_slots = 10";

if ($conn->query($sql) === TRUE) {
    fwrite($log, "Slots updated successfully\n");
} else {
    fwrite($log, "Error updating slots: " . $conn->error . "\n");
}

$conn->close();
fclose($log);
?>
