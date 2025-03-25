<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['teacher_logged_email'])) {

    $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];

}

include('../../db_conn.php');


$query = "SELECT COUNT(*) AS unseen_count FROM notification WHERE type = 'msg' AND teacher_id = '$teacherUserId' AND teacherseen = 'no'";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unseenCount = $row['unseen_count'];
} else {
    $unseenCount = 0;
}

echo json_encode(['unseenCount' => $unseenCount]);
?>
