<?php

session_start();

include '../../db_conn.php';

if (isset($_POST['activityid'])) {
    $activityId = $_POST['activityid'];

    $sql = "DELETE FROM activity_report WHERE activity_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("i", $activityId);

        if ($stmt->execute()) {
            echo 'success'; 
        } else {
            echo 'error'; 
        }

        $stmt->close();
    } else {
        echo 'error'; 
    }

    $conn->close();
} else {
    echo 'error'; 
}


?>

