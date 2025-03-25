<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("head.php"); ?>

    <style>

        .admin-message {
    display: none; 
}

.dropdown-menu a:hover { background:#035392;color:white }

@media (max-width: 768px) { 
    .admin-message {
        display: block; 
    }
}
</style>
</head>

<body class="sidebar-expand">

       <?php include("leftsidebar.php"); ?>

   <?php include("header.php"); ?>

    <div class="main">

        <div class="main-content dashboard">

            <div class="row" style="justify-content: center;">
                <div class="col-6 col-sm-12">
    <div class="card shadow-sm">

        <?php

include '../../db_conn.php';

if (isset($_POST['incident_id'])) {

    $incident_id = $_POST['incident_id'];

    $sql = "SELECT ir.*, c.* 
            FROM incident_report ir
            INNER JOIN child_record c ON c.child_id = ir.child_id 
            WHERE incident_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $incident_id); 

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="card-body pb-0 pt-3">';

            while ($row = $result->fetch_assoc()) {

                $child_name = htmlspecialchars($row['child_name']);
                $type = htmlspecialchars($row['type']);
                $date = htmlspecialchars($row['date']);
                $time = htmlspecialchars($row['time']);
                $child_age = htmlspecialchars($row['child_age']);
                $location = htmlspecialchars($row['location']);
                $description = htmlspecialchars($row['description']);

$dateFormatted = date('M j, Y', strtotime($date));

$timeFormatted = date('g:i A', strtotime($time));

                echo '<div class="card-header d-flex justify-content-center align-items-center pt-10" style="border:none; border-radius:5px;background:#EF5741">';
echo '<h5 class="card-title mb-0" style="text-align:center;color:white">' . $type . '</h5>';
echo '</div>';

                echo '<br>';

              echo '<p style="color:#222943">Child Name: <span style="color:grey">' . $child_name . '</span></p>';
echo '<p style="color:#222943">Age: <span style="color:grey">' . $child_age . '</span></p>';
echo '<p style="color:#222943">Observation Date: <span style="color:grey">' . $dateFormatted . '</span> at <span style="color:grey">' . $timeFormatted . '</span></p>';
echo '<p style="color:#222943">Location: <span style="color:grey">' . $location . '</span></p>';

   echo '<br>';
echo '<p style="color:#222943">Description: </p>';
echo '<p style="color:#222943">' . $description . '</p>';

            }

            echo '</div>'; 
        } 

        $stmt->close();
    } 
} 

$conn->close();
?>

    </div>
</div>

        </div>
    </div>

    <div class="overlay"></div>

    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>

</body>

</html>