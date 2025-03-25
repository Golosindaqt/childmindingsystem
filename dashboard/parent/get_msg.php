<?php
include('../../db_conn.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $sql = "
        SELECT user.user_id, user.role_id, user.username, user.email, notification.*
        FROM user
        INNER JOIN notification ON user.user_id = notification.user_id
        WHERE user.email = ? AND type = 'msg'
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user_data = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(['success' => true, 'data' => $user_data]);
            } else {
                echo json_encode(['error' => 'No user found with this email']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Failed to execute SQL statement']);
        }
    } else {
        echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['error' => 'Email parameter missing']);
}

$conn->close();
?>
