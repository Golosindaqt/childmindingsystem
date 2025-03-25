<?php

require_once '../../db_conn.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $teacher_user_id = isset($_POST['teacher_user_id']) ? (int)$_POST['teacher_user_id'] : 0;
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($teacher_user_id)) {
        echo "Error: Teacher ID and Username are required.";
        exit;
    }

    $sql = "UPDATE user SET username = ?";

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
    }

    $sql .= " WHERE user_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        if (!empty($password)) {
            $stmt->bind_param("ssi", $username, $hashedPassword, $teacher_user_id);
        } else {

            $stmt->bind_param("si", $username, $teacher_user_id);
        }

        if ($stmt->execute()) {
            echo "Updated successfully!";
        } else {
            echo "Error: Could not execute the query.";
        }

        $stmt->close();
    } else {
        echo "Error: Could not prepare the query.";
    }

    $conn->close();
}
?>