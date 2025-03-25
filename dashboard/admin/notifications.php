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

$query = "
    SELECT * FROM user WHERE role_id = 2 AND username IS NOT NULL AND username != ''
";


$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {

    $userdata = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {

    $userdata = [];
}

$defaultDate = date('Y-m-d');  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {

    $selectedDate = $_POST['date'];

    echo "The selected date is: " . htmlspecialchars($selectedDate);

    $dateTime = new DateTime($selectedDate);

    echo "<br>Formatted Date: " . $dateTime->format('l, F j, Y');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

     <script>
  function updateDateTime() {
            const now = new Date();
            const xx = 0 * 60 * 60 * 1000;
            const adjustedTime = new Date(now.getTime() + xx);
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            const datePart = adjustedTime.toLocaleDateString('en-PH', options);
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const timePart = adjustedTime.toLocaleTimeString('en-PH', timeOptions);
            const formattedDate = `${datePart} at ${timePart}`;
            document.getElementById('dateInput').value = formattedDate;

        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
     <?php include("head.php"); ?>

</head>

<body class="sidebar-expand counter-scroll">

  <?php include("leftsidebar.php"); ?>

   <?php include("header.php"); ?>

    <div class="main">

<div class="main-content teacher-form">
    <div class="row">
        <div class="col-4 col-xl-12">
            <div class="box">
                <div class="box-body">
                    <h6>Custom Notification</h6>

                    <form id="notifForm" action="insert_notif.php" method="POST" style="margin-top: 20px;">
 

    <input name="teacher_id" value="<?php echo htmlspecialchars($teacherId); ?>" hidden>
            <input type="date" name="datesent" value="<?php echo date('Y-m-d'); ?>" hidden>
            <input type="text" name="type" value="notif" hidden>
            <input type="text" name="from" value="teacher" hidden>

    <div class="row">
       
        <div class="col-md-6 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Username</label>
                <select class="form-control" name="user_id" required>
                    <option hidden selected value="">...</option>
                    <option value="allusers">All Users</option>
                    <?php

                    foreach ($userdata as $user) {

                        echo "<option value=\"" . htmlspecialchars($user['user_id']) . "\" $selected>" . htmlspecialchars($user['username']) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control selectpicker" name="status" required>
          
           
            <option hidden selected  value="danger">
                Need Attention
            </option>
        </select>
    </div>
</div>

        <!-- Message Field (Long Text) -->
        <div class="col-md-12 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea class="form-control" name="message" rows="4" required></textarea>
            </div>
        </div>

    </div>

    <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px; margin-top: 0px; margin-left: 20px; padding: 10px;">
            Submit
        </button>
    </div>
</form>

               
                </div>
            </div>
        </div>

<div class="col-8 col-xl-12">
    <div class="box" style="overflow-x:auto;">
        <div class="box-body">
            <div class="card shadow-sm">

                <div class="card-header justify-content-between align-items-center pt-10">
                    <div style="width:100%;display:flex;justify-content: space-between;flex-wrap: wrap;">
                        <h5 class="card-title pt-10">All Notifications <br>
                            <span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span>
                        </h5>
                        <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" 
                               style="width: 200px; height: 50px;margin-bottom: 10px;margin-top: 10px;" onkeyup="filterTable()">
                    </div>

                    <p class="notice" id="notice" style="font-size: 14px; color: #555; margin-top: 20px; padding: 10px; border: 1px solid #f0f0f0; background-color: #f9f9f9; border-radius: 5px; cursor: pointer;" onclick="deleteNotice(this)">
                        <strong style="color: #EF5741">Notice:</strong>
                        Click the notification to delete it. Once removed, it cannot be recovered.
                    </p>
                </div>

                <div class="card-body pb-0 pt-3" id="notificationsContainer">
                    <?php

include "../../db_conn.php";

$sql = "SELECT n.*, u.username
        FROM notification n
        INNER JOIN user u ON u.user_id = n.user_id
        WHERE type = 'notif'
        ORDER BY n.notification_id DESC;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $total_notifications = $result->num_rows;
    echo '<script>
            document.getElementById("totalfound").textContent = "' . $total_notifications . ' Found";
          </script>';

    while ($row = $result->fetch_assoc()) {
        $status = $row["status"];
        $message = htmlspecialchars($row["message"]);
        $user_id = htmlspecialchars($row["user_id"]);
        $datesent = htmlspecialchars($row["datesent"]);
        $username = htmlspecialchars($row["username"]);
        $notification_id = htmlspecialchars($row["notification_id"]);

        $date = new DateTime($datesent);
        $datesent = $date->format("F j, Y");

        // Custom template for 'danger' status
        if ($status == "danger") {
            echo '
            <div class="alert alert-danger align-items-center" role="alert">
                <img src="notice.jpg" style="width:100%; height:150px;">
                <p style="margin-top:10px">From: Childminding Center <br>Date: ' . $datesent . '</p>
                Announcement: ' . $message . '
            </div>';
        } else {
            // For other statuses (success, warning, info)
            $alertClass = getAlertClass($status);
            $iconClass = getIconClass($status);
            $statusColor = ($status == "warning" || $status == "danger") ? "black" : "white";

            echo '
            <div class="alert ' . $alertClass . ' notification-item" 
                 data-message="' . strtolower($message) . '" 
                 data-datesent="' . strtolower($datesent) . '" 
                 data-username="' . strtolower($username) . '" 
                 style="cursor:pointer" 
                 role="alert" 
                 data-id="' . $notification_id . '" 
                 onclick="deleteNotification(' . $notification_id . ')">
                 
                <div class="me-3" style="display: flex;">
                    <i class="bx ' . $iconClass . ' fs-3 ' . ($status == "success" ? "text-white" : "") . '"></i>
                    <p style="color:black; font-size:10px; padding-left: 5px; color:' . $statusColor . ';">
                        ' . $datesent . ' <br> To: ' . $username . '
                    </p>
                </div>
                
                <p style="color:' . $statusColor . ';">
                    ' . $message . '
                </p>
            </div>';
        }
    }
} else {
    echo "<p>No notifications available.</p>";
}

$conn->close();

function getAlertClass($status)
{
    switch ($status) {
        case "success":
            return "alert-success bg-primary";
        case "warning":
            return "alert-warning";
        case "danger":
            return "alert-danger";
        default:
            return "alert-info";
    }
}

function getIconClass($status)
{
    switch ($status) {
        case "success":
            return "bx-check-circle";
        case "warning":
            return "bxs-alarm-exclamation";
        case "danger":
            return "bx-x-circle";
        default:
            return "bx-info-circle";
    }
}
?>

                </div>

            </div>
        </div>
    </div>
</div>

        </div>

    </div>

</div>

    </div>
    <!-- END MAIN CONTENT -->

    <div class="overlay"></div>

    <!-- SCRIPT -->
    <!-- APEX CHART -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {

    $(".delete-btn").click(function() {
        var attendanceId = $(this).data("id"); 

        if (confirm("Are you sure you want to delete this record?")) {

            $.ajax({
                url: 'delete_attendance.php', 
                type: 'POST',
                data: { attendance_id: attendanceId },
                success: function(response) {

                    if (response === 'success') {
                        alert("Deleted successfully!");

                        $("button[data-id='" + attendanceId + "']").closest('tr').remove();
                    } else {
                        alert("Failed to delete record.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });
});

    $(document).ready(function() {
        $('#notifForm').submit(function(e) {
            e.preventDefault();  

            var formData = $(this).serialize();  

            $.ajax({
                url: $(this).attr('action'),  
                type: 'POST',
                data: formData,
                success: function(response) {
                  

                    alert('Submited successfully!');
                    console.log(response); 
                    window.location.reload() 
                },
                error: function(xhr, status, error) {

                    alert('An error occurred while saving changes!');
                    console.log(xhr.responseText);  
                }
            });
        });
    });
function filterTable() {
    const searchInput = document.getElementById("searchInput").value.toLowerCase();
    const notifications = document.querySelectorAll(".notification-item");

    let count = 0;

    notifications.forEach(function(notification) {
        const message = notification.getAttribute('data-message');
        const datesent = notification.getAttribute('data-datesent');
        const username = notification.getAttribute('data-username');

        if (message.includes(searchInput) || datesent.includes(searchInput) || username.includes(searchInput)) {
            notification.style.display = "";  
            count++;
        } else {
            notification.style.display = "none";  
        }
    });

    document.getElementById("totalfound").textContent = `${count} Found`;
}

function deleteNotification(notificationId) {

        if (confirm("Are you sure you want to delete this notification?")) {

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_notif.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {

                    if (xhr.responseText == "success") {

                        var notification = document.querySelector('[data-id="'+ notificationId +'"]');
                        if (notification) {
                            notification.remove();
                        }
                    } else {
                        alert("Error: Could not delete the notification.");
                    }
                }
            };
            xhr.send("notification_id=" + notificationId);  
        }
    }

    function checkTotalFound() {
    const totalFoundElement = document.getElementById("totalfound");
    const noticeElement = document.getElementById("notice");

    const totalFoundValue = parseInt(totalFoundElement.textContent.replace(" Found", ""), 10);

    if (totalFoundValue === 0) {
        noticeElement.style.display = "none";
    } else {

        noticeElement.style.display = "block";
    }
}

setInterval(checkTotalFound, 10);

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