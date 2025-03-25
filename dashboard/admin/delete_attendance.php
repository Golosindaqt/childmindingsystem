<?php

include '../../db_conn.php';

if (isset($_POST['attendance_id'])) {
    $attendanceId = $_POST['attendance_id'];

    $sql = "DELETE FROM attendance_record WHERE attendance_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $attendanceId); 

        if ($stmt->execute()) {
            echo 'success'; 
        } else {
            echo 'failure'; 
        }

        $stmt->close();
    } else {
        echo 'failure'; 
    }

    $conn->close();
} else {
    echo 'failure'; 
}
?>