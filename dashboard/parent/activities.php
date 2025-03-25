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

         <div class="col-12 col-xl-12">
           <div class="box" style="overflow-x:auto;">
    <div class="box-body">
        <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
            <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Activity Gallery<br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
            <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
        </div>

        <?php

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        include '../../db_conn.php';

        $sql = "SELECT * FROM activity_report WHERE visibility = 'parents' OR visibility = 'both'"; 
        $result = $conn->query($sql);

        $activities = []; 

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
        }

        $conn->close();
        ?>

        <div class="card-body pb-0 pt-3" id="reportsContainer" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;padding: 0px;">
            <?php foreach ($activities as $activity): ?>
                <div class="card activity-card" style="min-width: 32%; cursor: pointer; height: auto; box-sizing: border-box; margin-bottom: 10px;"
                 data-activity-id="<?= htmlspecialchars($activity['activity_id']) ?>" data-title="<?= strtolower(htmlspecialchars($activity['title'])) ?>" data-visibility="<?= strtolower(htmlspecialchars($activity['visibility'])) ?>" data-date="<?= strtolower(htmlspecialchars($activity['date'])) ?>" data-description="<?= strtolower(htmlspecialchars($activity['description'])) ?>">

                                        <div class="card-body">
                                            <h5 style="text-align: center;"><?= htmlspecialchars($activity['title']) ?></h5>
                     
                        <img src="../admin/gallery/<?= htmlspecialchars($activity['fileImgsrc']) ?>" style="width:100%; max-height: 200px; object-fit: cover;margin-bottom: 15px;">
                        <p><strong>Visibility:</strong> <?= htmlspecialchars($activity['visibility']) ?></p>
                        <p><strong>Date:</strong> <?= date('M j, Y', strtotime($activity['date'])) ?></p>

                        <p><strong>Description:</strong> <?= htmlspecialchars($activity['description']) ?></p>
                    </div> 
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
        </div>
        </div>

    </div>

</div>

    </div>
   

    <div class="overlay"></div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase(); 
    const reports = document.querySelectorAll('.activity-card'); 
    let totalFound = 0; 

    reports.forEach(report => {
        const title = report.getAttribute('data-title');
        const visibility = report.getAttribute('data-visibility');
        const date = report.getAttribute('data-date');
        const description = report.getAttribute('data-description');

        if (title.includes(searchInput) || visibility.includes(searchInput) || date.includes(searchInput) || description.includes(searchInput)) {
            report.style.display = ''; 
            totalFound++; 
        } else {
            report.style.display = 'none'; 
        }
    });

    document.getElementById('totalfound').textContent = `${totalFound} Found`;
}

filterTable();

const visibilitySelect = document.getElementById('visibilitySelect');
    const visibilityNotice = document.getElementById('visibilityNotice');

    visibilitySelect.addEventListener('change', function() {
        const selectedValue = visibilitySelect.value;

        if (selectedValue === 'public') {
            visibilityNotice.innerHTML = `
                <strong style="color: #EF5741">Notice:</strong> This content will be visible to everyone on the landing page in gallery section.
            `;
        } else if (selectedValue === 'parents') {
            visibilityNotice.innerHTML = `
                <strong style="color: #EF5741">Notice:</strong> This content will only be visible to all user parents, so they can stay informed about what their child is doing.
            `;
        } else if (selectedValue === 'both') {
            visibilityNotice.innerHTML = `
                <strong style="color: #EF5741">Notice:</strong> This content will be visible to both the public and parents.
            `;
        }
    });

    visibilitySelect.dispatchEvent(new Event('change'));
    $(document).ready(function() {

    $(".delete-btn").click(function() {
        var incident_id = $(this).data("id"); 

        if (confirm("Are you sure you want to delete this record?")) {

            $.ajax({
                url: 'delete_incident.php', 
                type: 'POST',
                data: { incident_id: incident_id },
                success: function(response) {

                    if (response === 'success') {
                        alert("Deleted successfully!");

                        $("button[data-id='" + incident_id + "']").closest('tr').remove();
                        window.location.reload();
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
    $('#ActivityForm').submit(function(e) {
        e.preventDefault();  

        var formData = new FormData(this);  

        $.ajax({
            url: $(this).attr('action'),  
            type: 'POST',
            data: formData,
            contentType: false,  
            processData: false,  
            success: function(response) {

                alert('Submitted successfully!');
                console.log(response); 

            },
            error: function(xhr, status, error) {

                alert('An error occurred while saving changes!');
                console.log(xhr.responseText);  
            }
        });
    });
});

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
</script>

  <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>
</body>