<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];

}

include('../../db_conn.php');


$query = "SELECT COUNT(*) AS unseen_count FROM notification WHERE type = 'msg' AND user_id = '$parentUserId' AND parentseen = 'no'";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unseenCount = $row['unseen_count'];
} else {
    $unseenCount = 0;
}

echo json_encode(['unseenCount' => $unseenCount]);
?>
