<?php
include('db_conn.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        echo 'Both fields are required.';
        exit;
    }

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['role_id'] == 2) {
                $_SESSION['parent_logged_user_id'] = $user['user_id'];
                $_SESSION['parent_logged_username'] = $user['username'];
                $_SESSION['parent_logged_email'] = $user['email'];

                echo 'success';
            } else {
                echo '';
            }
        } else {
            echo 'Invalid username or password. <br> <a href="recovery.php" target="_blank">Forgot password?</a>';
        }
    } else {
        echo 'Invalid username or password. <br> <a href="recovery.php" target="_blank">Forgot password?</a>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method.';
}
?>
