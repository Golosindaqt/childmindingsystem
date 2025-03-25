<?php

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $teacherId = $_POST['teacher_id'];    
$childId = $_POST['child_id'];        
$type = $_POST['type'];               
$date = $_POST['date'];               
$time = $_POST['time'];               
$description = $_POST['description'];
$location = $_POST['location'];

    $sql = "INSERT INTO incident_report (teacher_id, location, child_id, type, `date`, `time`, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("isissss", $teacherId, $location, $childId, $type, $date, $time, $description);

        if ($stmt->execute()) {

            echo "Added successfully!";
        } else {

            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {

        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>