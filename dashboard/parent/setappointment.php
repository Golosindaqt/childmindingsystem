<?php

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_POST['user_id'];
    $session_id = $_POST['session_id'];  
    $appointment_date = $_POST['appointment_date'];
    $ref = $_POST['ref'];
    $child_id = $_POST['child_id'];
    $session_time = $_POST['session_time'];

    $check_query = "SELECT COUNT(*) FROM appointment WHERE child_id = ? AND appointment_date = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("is", $child_id, $appointment_date);
    $stmt->execute();
    $stmt->bind_result($existing_appointments);
    $stmt->fetch();
    $stmt->close();

    if ($existing_appointments > 0) {

        echo 'An appointment already exists for this child on the selected date!';
        exit;
    }

    if ($session_time == "Morning - (9:00 - 11:30 AM)") {

        $session_query = "SELECT morning_slots FROM session WHERE session_id = ?";
        $stmt = $conn->prepare($session_query);
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $stmt->bind_result($morning_slots);
        $stmt->fetch();
        $stmt->close();

        if ($morning_slots > 0) {

            $new_morning_slots = $morning_slots - 1;
            $update_slots_query = "UPDATE session SET morning_slots = ? WHERE session_id = ?";
            $stmt = $conn->prepare($update_slots_query);
            $stmt->bind_param("ii", $new_morning_slots, $session_id);
            $stmt->execute();
            $stmt->close();

            $query = "INSERT INTO appointment (user_id, session_id, appointment_date, ref, child_id, session_time) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iissis", $user_id, $session_id, $appointment_date, $ref, $child_id, $session_time);
            if ($stmt->execute()) {
                echo 'Appointment successfully added!';
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'No available morning slots left!';
        }
    } elseif ($session_time == "Afternoon - (1:00 - 4:30 PM)") {

        $session_query = "SELECT afternoon_slots FROM session WHERE session_id = ?";
        $stmt = $conn->prepare($session_query);
        $stmt->bind_param("i", $session_id);
        $stmt->execute();
        $stmt->bind_result($afternoon_slots);
        $stmt->fetch();
        $stmt->close();

        if ($afternoon_slots > 0) {

            $new_afternoon_slots = $afternoon_slots - 1;
            $update_slots_query = "UPDATE session SET afternoon_slots = ? WHERE session_id = ?";
            $stmt = $conn->prepare($update_slots_query);
            $stmt->bind_param("ii", $new_afternoon_slots, $session_id);
            $stmt->execute();
            $stmt->close();

            $query = "INSERT INTO appointment (user_id, session_id, appointment_date, ref, child_id, session_time) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iissis", $user_id, $session_id, $appointment_date, $ref, $child_id, $session_time);
            if ($stmt->execute()) {
                echo 'Appointment successfully added!';
            } else {
                echo 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'No available afternoon slots left!';
        }
    } else {
        echo 'Invalid session time selected!';
    }
}

$conn->close();
?>