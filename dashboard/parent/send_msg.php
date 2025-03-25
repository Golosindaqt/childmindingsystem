<?php

include('../../db_conn.php');

if (isset($_POST['user_id'], $_POST['teacher_id'], $_POST['message'], $_POST['datesent'])) {

    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $datesent = mysqli_real_escape_string($conn, $_POST['datesent']);

    $query = "INSERT INTO notification (user_id, teacher_id, message, datesent, type, `from`) 
              VALUES ('$user_id', '$teacher_id', '$message', '$datesent', 'msg', 'parent')";

    if (mysqli_query($conn, $query)) {
        $updateQuery = "UPDATE notification SET parentseen = 'yes' WHERE user_id = '$user_id' AND teacher_id = '$teacher_id' AND type = 'msg' AND parentseen = 'no'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Notification sent and all seen statuses updated successfully.";
        } else {
            echo "Error updating seen status: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting notification: " . mysqli_error($conn);
    }
} else {
    echo "All fields are required.";
}
?>
