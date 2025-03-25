<?php

include '../../db_conn.php';

if (isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']);  

    $sql = "DELETE FROM notification WHERE notification_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("i", $notification_id);

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