<?php
include('../../db_conn.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

   $sql = "
    SELECT *
    FROM user
    INNER JOIN notification ON user.user_id = notification.user_id
    WHERE user.email = ? 
      AND notification.type = 'msg'
      AND (notification.message IS NOT NULL AND TRIM(notification.message) != '')
";


    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user_data = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(['success' => true, 'data' => $user_data]);

                 $updateQuery = "
                    UPDATE notification 
                    SET teacherseen = 'yes' 
                    WHERE user_id = (SELECT user_id FROM user WHERE email = ?) 
                      AND type = 'msg' 
                      AND teacherseen = 'no'
                ";

                if ($updateStmt = $conn->prepare($updateQuery)) {
                    $updateStmt->bind_param('s', $email);
                    
                    if ($updateStmt->execute()) {
                    } else {
                    }

                    $updateStmt->close();
                }


            } else {

                echo json_encode(['success' => false, 'message' => 'No messages found']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to execute SQL statement']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email parameter missing']);
}

$conn->close();
?>