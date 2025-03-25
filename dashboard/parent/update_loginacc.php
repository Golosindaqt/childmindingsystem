<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db_conn.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $parent_user_id = isset($_POST['parent_user_id']) ? (int)$_POST['parent_user_id'] : 0;
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($parent_user_id)) {
        echo "Error: Teacher ID and Username are required.";
        exit;
    }

    $checkUsernameQuery = "SELECT user_id FROM user WHERE username = ? AND user_id != ?";
    if ($stmt = $conn->prepare($checkUsernameQuery)) {
        $stmt->bind_param("si", $username, $parent_user_id); 
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Error: The username is already taken. Please choose a different username.";
            $stmt->close();
            exit;
        }

        $stmt->close();
    } else {
        echo "Error: Could not prepare the username check query.";
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
            $stmt->bind_param("ssi", $username, $hashedPassword, $parent_user_id);
        } else {

            $stmt->bind_param("si", $username, $parent_user_id);
        }

        if ($stmt->execute()) {
            unset($_SESSION['parent_logged_username']);
            $_SESSION['parent_logged_username'] = $username;

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