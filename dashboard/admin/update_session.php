<?php

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $session_id = $_POST['session_id'];  
    $morning_slots = $_POST['morning_slots'];  
    $afternoon_slots = $_POST['afternoon_slots'];  

    if (is_numeric($morning_slots) && is_numeric($afternoon_slots)) {

        $query = "UPDATE session SET morning_slots = ?, afternoon_slots = ? WHERE session_id = ?";
        $stmt = $conn->prepare($query);

        $stmt->bind_param("iii", $morning_slots, $afternoon_slots, $session_id);

        if ($stmt->execute()) {
            echo 'Session updated successfully!';
        } else {
            echo 'Error updating session: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Please enter valid numeric values for the slots.';
    }
}

$conn->close();
?>