<?php

include('db_conn.php');

$email = $_POST['email'];
$newpassword = $_POST['password'];

if (empty($email) || empty($newpassword)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and new password are required.']);
    exit();
}

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

$newpassword_hashed = password_hash($newpassword, PASSWORD_BCRYPT);

$query = "UPDATE user SET password = ? WHERE email = ?";

if ($stmt = $conn->prepare($query)) {

    $stmt->bind_param("ss", $newpassword_hashed, $email);
    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Password successfully reset.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email not found in the database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating password. Please try again later.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed.']);
}

$conn->close();
?>