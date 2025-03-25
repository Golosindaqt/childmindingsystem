<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

include '../../db_conn.php';

function getTotalRows($conn, $table, $join = '', $condition = '') {
    $query = "SELECT COUNT(*) AS total FROM $table" . ($join ? " $join" : "") . ($condition ? " WHERE $condition" : "");
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        return 0; 
    }
}

$totalPending = getTotalRows($conn, 'enrollment', 'INNER JOIN parental_information ON parental_information.child_id = enrollment.child_id', "enrollment.enrollment_status = 'pending' AND parental_information.email = '$parentEmail'");

$totalMale = getTotalRows($conn, 'child_record', 'INNER JOIN enrollment ON child_record.child_id = enrollment.child_id INNER JOIN parental_information ON parental_information.child_id = enrollment.child_id', "child_record.gender = 'Male' AND enrollment.enrollment_status = 'accepted' AND parental_information.email = '$parentEmail'");
$totalFemale = getTotalRows($conn, 'child_record', 'INNER JOIN enrollment ON child_record.child_id = enrollment.child_id INNER JOIN parental_information ON parental_information.child_id = enrollment.child_id', "child_record.gender = 'Female' AND enrollment.enrollment_status = 'accepted' AND parental_information.email = '$parentEmail'");

$totalChildren = $totalMale + $totalFemale;

$male_monthly_totals = array_fill(0, 12, 0);
$female_monthly_totals = array_fill(0, 12, 0);

function getMonthlyTotals($conn, $gender, &$monthly_totals) {
    $sql = "SELECT e.enrollment_date
            FROM enrollment e
            JOIN child_record c ON e.child_id = c.child_id
            WHERE e.enrollment_date IS NOT NULL 
              AND c.gender = ? 
              AND e.enrollment_status = 'accepted'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $gender);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date = DateTime::createFromFormat('M j, Y \a\t h:i:s A', $row['enrollment_date']);
            if ($date) {
                $monthIndex = (int)$date->format('n');
                $monthly_totals[$monthIndex - 1]++;
            }
        }
    }

    $stmt->close();
}

getMonthlyTotals($conn, 'Male', $male_monthly_totals);
getMonthlyTotals($conn, 'Female', $female_monthly_totals);

echo "<script>
        localStorage.setItem('maleMonthlyTotals', JSON.stringify(" . json_encode($male_monthly_totals) . "));
        localStorage.setItem('femaleMonthlyTotals', JSON.stringify(" . json_encode($female_monthly_totals) . "));
      </script>";


      $updateQuery = "UPDATE notification SET seen = 'yes' WHERE seen = 'no'";

$updateResult = mysqli_query($conn, $updateQuery);


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
        <div class="card-header d-flex justify-content-between align-items-center pt-10">
            <h5 class="card-title mb-0" style="color:#222943">Notifications</h5>

        </div>
        <?php

include '../../db_conn.php';

$sql = "SELECT n.*, u.username
FROM notification n
INNER JOIN user u ON u.user_id = n.user_id
WHERE u.user_id = $parentUserId AND n.type = 'notif'
ORDER BY n.notification_id DESC
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="card-body pb-0 pt-3">';

    while ($row = $result->fetch_assoc()) {

        $status = $row['status'];
        $message = htmlspecialchars($row['message']);
        $user_id = htmlspecialchars($row['user_id']);
        $datesent = htmlspecialchars($row['datesent']);
        $username = htmlspecialchars($row['username']);
         $date = new DateTime($datesent);
        $datesent = $date->format('F j, Y');

        if ($status == 'success') {
            echo '<div class="alert alert-success d-flex align-items-center bg-primary" style="color: white" role="alert">
                    <div class="me-3">
                        <i class="bx bx-check-circle fs-3"></i>
                    </div>
                    <div>
                        <p style="color:white;font-size:10px">' . $datesent . '</p>

                         ' . $message . '
                    </div>
                  </div>';
        } elseif ($status == 'warning') {
            echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
                    <div class="me-3">
                        <i class="bx bxs-alarm-exclamation fs-3"></i>
                    </div>
                    <div>
                    <p style="color:black;font-size:10px">' . $datesent . '</p>
                        ' . $message . '
                    </div>
                  </div>';
        } elseif ($status == 'danger') {
            echo '<div class="alert alert-danger align-items-center" role="alert">
                        <img src="notice.jpg" style="width:100%; height:150px" >

                        <p style="margin-top:10px">From: Childminding Center
                        <br>Date: ' . $datesent . '</p></p>
                        Announcement: ' . $message . '
                   
                  </div>';
        }
    }

    echo '</div>'; 
} else {
    echo '<p style="padding:10px">No notifications available.</p>';
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