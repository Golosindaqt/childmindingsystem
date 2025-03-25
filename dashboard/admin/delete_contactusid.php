<?php

include '../../db_conn.php';

if (isset($_POST['contactusid'])) {
    $contactusid = $_POST['contactusid'];

    $sql = "DELETE FROM contact_form WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $contactusid); 

        if ($stmt->execute()) {
            echo 'success'; 
        } else {
            echo 'failure'; 
        }

        $stmt->close();
    } else {
        echo 'failure'; 
    }

    $conn->close();
} else {
    echo 'failure'; 
}
?>