<?php

include('../../db_conn.php');


$query = "SELECT COUNT(*) AS unseen_count FROM enrollment WHERE enrollment_status = 'pending'";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $unseenCount = $row['unseen_count'];
} else {
    $unseenCount = 0;
}

echo json_encode(['unseenCount' => $unseenCount]);
?>
