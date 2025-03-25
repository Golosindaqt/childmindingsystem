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
            <div class="row">
                <div class="col-12 col-xl-12 col-md-12 col-sm-12">
                    <div class="box card-box">
                        <div class="icon-box bg-color-6 d-block" onclick="window.location.href='all_enrollments'">

                            <div class="content text-center color-6">
                                <h5 class="title-box fs-17 font-w500">Children Enrolled</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalChildren;?></span>
                                </div>
                            </div>
                        </div>
                        <div class="icon-box bg-color-1 d-block"  onclick="window.location.href='pending_enrollments'">

                            <div class="content text-center color-7">
                                <h5 class="title-box fs-17 font-w500">Pending Enrollment</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalPending;?></span>
                                </div>
                            </div>
                        </div>
                        <div class="icon-box bg-color-6 d-block">

                            <div class="content text-center color-6">
                                <h5 class="title-box fs-17 font-w500">Male Children</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalMale;?></span>
                                </div>
                            </div>
                        </div>

                        <div class="icon-box bg-color-1 d-block">

                            <div class="content text-center color-7">
                                <h5 class="title-box fs-17 font-w500">Female Children</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalFemale;?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" style="<?php echo $totalPending == 0 ? 'display: none;' : ''; ?>">
                 <div class="col-12">
                    <div class="box">
                        <div class="box-header" >
                            <div class="me-auto" >
                                <h5 class="card-title mb-6">Pending Enrollments</h5>
                            </div>
                        </div>
                        <div class="box-body pt-20">
                            <div class="themesflat-carousel-box data-effect has-bullets bullet-circle bullet24 clearfix" data-gap="30" data-column="4" data-column2="2" data-column3="1" data-auto="true">
                                <div class="owl-carousel owl-theme" >

                                    <?php
include '../../db_conn.php'; 

$sql = "SELECT e.*, c.*, u.* FROM enrollment e
        INNER JOIN child_record c ON e.child_id = c.child_id
        INNER JOIN user u ON u.user_id = c.user_id
        WHERE e.enrollment_status = 'pending'
        ORDER BY enrollment_id DESC
        ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $dateOfBirth = new DateTime($row['date_of_birth']);
        $formattedDateOfBirth = $dateOfBirth->format('M j, Y');

        echo '<div class="box box-carousel" style="width:100%;min-width:300px">';
        echo '    <div class="card-top">';
        echo '        <div class="sm-f-wrap d-flex">';
        echo '            <h5 class="icon-gold text-white bg-yellow">#</h5>';
        echo '            <a class="h6 t-title"  style="margin-top:0px;margin-bottom:20px">' . htmlspecialchars($row['ref']) . '</a>';
        echo '        </div>';

        echo '    </div>';
        echo '    <div class="card-center">';
        echo '        <div class="font-w400">Name: ' . htmlspecialchars($row['child_name']) . '</div>';
        echo '        <div class="font-w400">Birthday: ' . htmlspecialchars($formattedDateOfBirth) . '</div>';
        echo '        <div class="font-w400">Age: ' . htmlspecialchars($row['child_age']) . '</div>';
        echo '        <div class="font-w400">Gender: ' . htmlspecialchars($row['gender']) . '</div>';
        echo '        <div class="font-w400">Place of Birth: ' . htmlspecialchars($row['place_of_birth']) . '</div>';
        echo '        <br>';
        echo '        <div class="font-w400">' . htmlspecialchars($row['enrollment_date']) . '</div>';
        echo '    </div>';
        echo '</div>';
    }
}

$conn->close();
?>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-12" >
    <div class="card shadow-sm" style="max-height:600px;overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center pt-10">
            <h5 class="card-title mb-0">Notification</h5>
            <div class="card-options">
                <button class="btn btn-link text-decoration-none" style="cursor: pointer;" onclick="window.location.href='notifications'">
                    See more <i class="bx bx-right-arrow-alt"></i>
                </button>
            </div>
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

if ($result && $result->num_rows > 0) {
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
    echo '<p style="padding: 10px">No notifications available.</p>';
}

$conn->close();
?>

    </div>
</div>
         <div class="col-6 col-sm-12">
                         <?php include 'msg-component.php';?>

                </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="overlay"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.accept-btn').click(function() {
        var ref = $(this).data('ref');
        var email = $(this).data('email');

        $.ajax({
            url: 'accept_enrollment.php',
            type: 'POST',
            data: { ref: ref, email: email },
            success: function(response) {
                alert('Enrollment accepted!');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });

    $('.reject-btn').click(function() {
        var ref = $(this).data('ref');
        var email = $(this).data('email');

        $.ajax({
            url: 'reject_enrollment.php', 
            type: 'POST',
            data: { ref: ref, email: email },
            success: function(response) {
                alert('Enrollment rejected!');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});
</script>

    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>

</body>

</html>