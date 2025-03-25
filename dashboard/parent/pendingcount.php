<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];

} else {
    echo "<script>window.history.back()</script>";
}

include('../../db_conn.php');


$query = "SELECT COUNT(*) AS pending_count FROM enrollment e
    INNER JOIN 
        parental_information p 
        ON e.child_id = p.child_id
    WHERE 
        e.enrollment_status = 'pending' AND p.email = '$parentEmail'";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unseenCount = $row['pending_count'];
} else {
    $unseenCount = 0;
}

echo json_encode(['unseenCount' => $unseenCount]);
?>
