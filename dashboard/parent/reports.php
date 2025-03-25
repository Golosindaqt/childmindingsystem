<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

$defaultDate = date('Y-m-d');  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {

    $selectedDate = $_POST['date'];

    echo "The selected date is: " . htmlspecialchars($selectedDate);

    $dateTime = new DateTime($selectedDate);

    echo "<br>Formatted Date: " . $dateTime->format('l, F j, Y');
}

include('../../db_conn.php');

$updateQuery = "UPDATE incident_report SET seen = 'yes' WHERE seen = 'no'";

$updateResult = mysqli_query($conn, $updateQuery);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>

.card {

    width: 30%;    
}

@media (max-width: 768px) {
    .card {
       width: 100%; 
    }
}

</style>
     <?php include("head.php"); ?>
</head>

<body class="sidebar-expand counter-scroll">

  <?php include("leftsidebar.php"); ?>

   <?php include("header.php"); ?>

    <div class="main">

<div class="main-content teacher-form">
    <div class="row" style="justify-content: center;">

<div class="col-12 col-xl-12">
    <div class="box" style="overflow-x:auto;">
        <div class="box-body">
            <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Reports Record <br>
                    <span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span>
                </h5>
                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
            </div>

            <?php

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            include '../../db_conn.php';

            if (!isset($_SESSION['parent_logged_user_id'])) {
                echo "<p>Error: Parent is not logged in.</p>";
                exit;
            }

            $parentUserId = $_SESSION['parent_logged_user_id'];

            $childIdsQuery = "SELECT child_id FROM child_record WHERE user_id = ?";
            $stmt = $conn->prepare($childIdsQuery);

            if (!$stmt) {
                die("Query preparation failed: " . $conn->error); 
            }

            $stmt->bind_param('s', $parentUserId); 
            $stmt->execute();
            $childIdsResult = $stmt->get_result();

            $childIds = [];
            while ($row = $childIdsResult->fetch_assoc()) {
                $childIds[] = $row['child_id'];
            }

            if (empty($childIds)) {
                echo "<p>No children found for this parent.</p>";
                exit;
            }

            $placeholders = implode(',', array_fill(0, count($childIds), '?'));
            $sql = "
                SELECT ir.*, c.* 
                FROM incident_report ir
                INNER JOIN child_record c ON c.child_id = ir.child_id 
                WHERE c.child_id IN ($placeholders)
                ORDER BY ir.date DESC
            ";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "<p>Error: Unable to prepare the query.</p>";
                exit;
            }

            $types = str_repeat('i', count($childIds));
            $stmt->bind_param($types, ...$childIds);
            $stmt->execute();
            $result = $stmt->get_result();

            $reports = [];
            if ($result->num_rows > 0) {

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

                    $reports[] = [
                        'child_name' => $child_name,
                        'type' => $type,
                        'date' => $dateFormatted,
                        'time' => $timeFormatted,
                        'child_age' => $child_age,
                        'location' => $location,
                        'description' => $description
                    ];
                }
            }

            $stmt->close();

            $conn->close();
            ?>

            <div class="card-body pb-0 pt-3" id="reportsContainer" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;padding: 0px;">
                <?php foreach ($reports as $report): ?>
                   <div class="card report-card" style="min-width: 32% ;  height: auto; box-sizing: border-box; margin-bottom: 10px;">

                        <div class="card-header d-flex justify-content-center align-items-center pt-10" style="border:none; border-radius:5px;background:#EF5741">
                            <h5 class="card-title mb-0" style="text-align:center;color:white"><?= $report['type'] ?></h5>
                        </div>
                        <div class="card-body">
                            <p style="color:#222943">Child Name: <span style="color:grey"><?= $report['child_name'] ?></span></p>
                            <p style="color:#222943">Age: <span style="color:grey"><?= $report['child_age'] ?></span></p>
                            <p style="color:#222943">Observation Date: <span style="color:grey"><?= $report['date'] ?></span> at <span style="color:grey"><?= $report['time'] ?></span></p>
                            <p style="color:#222943">Location: <span style="color:grey"><?= $report['location'] ?></span></p>
                            <br>
                            <p style="color:#222943">Description: </p>
                            <p style="color:#222943"><?= $report['description'] ?></p>
                        </div> <!-- End of card-body -->
                    </div> <!-- End of card -->
                <?php endforeach; ?>
            </div> <!-- End of reports container -->

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
        $('#attendanceForm').submit(function(e) {
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
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const reports = document.querySelectorAll('.report-card');
    let foundCount = 0;

    reports.forEach(report => {

        const childName = report.querySelector('.card-body p:nth-child(1)').textContent.toLowerCase(); 
        const childAge = report.querySelector('.card-body p:nth-child(2)').textContent.toLowerCase(); 
        const observationDate = report.querySelector('.card-body p:nth-child(3)').textContent.toLowerCase(); 
        const location = report.querySelector('.card-body p:nth-child(4)').textContent.toLowerCase(); 
        const description = report.querySelector('.card-body p:nth-child(6)').textContent.toLowerCase(); 
        const reportType = report.querySelector('.card-header .card-title').textContent.toLowerCase(); 

        if (
            childName.includes(searchInput) ||
            childAge.includes(searchInput) ||
            observationDate.includes(searchInput) ||
            location.includes(searchInput) ||
            description.includes(searchInput) ||
            reportType.includes(searchInput)
        ) {
            report.style.display = '';  
            foundCount++;
        } else {
            report.style.display = 'none';  
        }
    });

    document.getElementById('totalfound').textContent = `${foundCount} Found`;
}
filterTable();
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