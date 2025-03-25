<?php

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $teacherId = $_POST['teacher_id'];
    $childId = $_POST['child_id'];
    $date = $_POST['date'];
    $session = $_POST['Session'];
    $status = $_POST['status'];
    $time_leave = $_POST['time_leave'];

    

    $sql = "INSERT INTO attendance_record (teacher_id, date, shift, child_id, status, time_leave) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("ississ", $teacherId, $date, $session, $childId, $status, $time_leave);

        if ($stmt->execute()) {

            echo "Attendance recorded successfully!";
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