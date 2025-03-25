<?php

include('db_conn.php'); 

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['found' => true, 'email' => $email]);
    } else {
        echo json_encode(['found' => false]);
    }

    $stmt->close();
}

$conn->close();
?>