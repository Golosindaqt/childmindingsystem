<?php

include '../../db_conn.php';

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    $query = "DELETE FROM appointment WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        echo 'success'; 
    } else {
        echo 'Error deleting appointment: ' . $stmt->error; 
    }

    $stmt->close();
}

$conn->close();
?>