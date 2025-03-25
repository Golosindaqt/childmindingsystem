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

$updateQuery = "UPDATE attendance_record SET seen = 'yes' WHERE seen = 'no'";

$updateResult = mysqli_query($conn, $updateQuery);





?>

<!DOCTYPE html>
<html lang="en">

<head>
     <?php include("head.php"); ?>
</head>

<body class="sidebar-expand counter-scroll">

  <?php include("leftsidebar.php"); ?>

   <?php include("header.php"); ?>

    <div class="main">

<div class="main-content teacher-form">
    <div class="row" style="justify-content: center;">

        <div class="col-8 col-xl-12">
            <div class="box" style="overflow-x:auto;">
                <div class="box-body">
                    <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Attendance Record <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>

<?php

include '../../db_conn.php';

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

$attendanceQuery = "
    SELECT *
    FROM attendance_record a 
    JOIN child_record c ON a.child_id = c.child_id 
    WHERE c.child_id IN ($placeholders)
    ORDER BY a.date DESC
";

$stmt = $conn->prepare($attendanceQuery);

if (!$stmt) {
    die("Attendance query preparation failed: " . $conn->error); 
}

$stmt->bind_param(str_repeat('i', count($childIds)), ...$childIds);

$stmt->execute();
$attendanceResult = $stmt->get_result();

?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Session</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if ($attendanceResult->num_rows > 0) {

            while ($row = $attendanceResult->fetch_assoc()) {
                $attendanceId = htmlspecialchars($row['attendance_id']);
                $date = htmlspecialchars($row['date']);
                $session = htmlspecialchars($row['shift']);
                $status = htmlspecialchars($row['status']);
                $childName = htmlspecialchars($row['child_name']);

                 $time_leave = htmlspecialchars($row['time_leave']);
                $time_leave = date("g:i A", strtotime($time_leave));


                $formattedDate = date('M d, Y', strtotime($date));

                echo "<tr>
    <td>$childName</td>
    <td>$session</td>
    <td>$formattedDate</td>
    <td>";

    if ($status == 'leave') {
        echo '<span>' . $status . ' (' . $time_leave . ') </span>';
    } else {
      
        echo $status;
    }

echo "</td>
    </tr>";

            }
        } else {

            echo "";
        }

        $conn->close();
        ?>
    </tbody>
</table>

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
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('enrollments');
    const rows = table.getElementsByTagName('tr');
    let checkedCells = 0;
    let matchingCells = 0;

    const searchWords = filter.split(/\s+/).filter(word => word.length > 0);

    if (searchWords.length === 0) {
        for (let i = 1; i < rows.length; i++) {
            rows[i].style.display = ""; 
        }
        matchingCells = rows.length - 1; 
    } else {

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let match = true;  

            for (let word of searchWords) {
                let wordFoundInRow = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const cellValue = cells[j].textContent || cells[j].innerText;
                        checkedCells++;  

                        if (cellValue.toLowerCase().includes(word)) {
                            wordFoundInRow = true;
                            break;
                        }
                    }
                }

                if (!wordFoundInRow) {
                    match = false;
                    break;
                }
            }

            rows[i].style.display = match ? "" : "none";
            if (match) matchingCells++;  
        }
    }

    console.log(`Checked cells: ${checkedCells}`);
    console.log(`Matching cells: ${matchingCells}`);

    document.getElementById('totalfound').innerText = `${matchingCells} Found`;
}

window.onload = function() {
    filterTable(); 
};

document.getElementById('searchInput').addEventListener('input', filterTable);

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