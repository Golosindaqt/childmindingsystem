<?php

include '../../db_conn.php';

if (isset($_POST['incident_id'])) {
    $incident_id = intval($_POST['incident_id']);  

    $sql = "DELETE FROM incident_report WHERE incident_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("i", $incident_id);

        if ($stmt->execute()) {
            echo "success";  
        } else {
            echo "error";  
        }

        $stmt->close();
    } else {
        echo "error";  
    }
} else {
    echo "error";  
}

$conn->close();
?>