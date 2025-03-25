<?php 
include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $teacherId = $_POST['teacher_id'];
    $datesent = $_POST['datesent'];
    $type = $_POST['type'];
    $from = $_POST['from'];
    $userId = $_POST['user_id'];
    $status = $_POST['status'];
    $message = $_POST['message'];

    $insertnotif = "INSERT INTO notification (user_id, teacher_id, message, datesent, status, type, `from`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($userId == 'allusers') {
        $userQuery = "SELECT user_id FROM user WHERE user_id != 1";  
        if ($userResult = $conn->query($userQuery)) {
            while ($user = $userResult->fetch_assoc()) {
                $currentUserId = $user['user_id'];
                if ($stmt = $conn->prepare($insertnotif)) {
                    $stmt->bind_param("issssss", $currentUserId, $teacherId, $message, $datesent, $status, $type, $from);
                    if (!$stmt->execute()) {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            }
            $userResult->free();
        } else {
            echo "Error fetching users: " . $conn->error;
        }
    } else {
        if ($stmt = $conn->prepare($insertnotif)) {
            $stmt->bind_param("issssss", $userId, $teacherId, $message, $datesent, $status, $type, $from);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
}
?>
