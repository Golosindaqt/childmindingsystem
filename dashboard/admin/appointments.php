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

$session_id = 1; 

$sql = "SELECT morning_slots, afternoon_slots FROM session WHERE session_id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $session_id);

$stmt->execute();

$stmt->bind_result($morning_slots, $afternoon_slots);

if ($stmt->fetch()) {

    $stmt->close();
} else {

    echo "Session not found.";
    exit;
}

$defaultDate = date('Y-m-d');  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {

    $selectedDate = $_POST['date'];

    echo "The selected date is: " . htmlspecialchars($selectedDate);

    $dateTime = new DateTime($selectedDate);

    echo "<br>Formatted Date: " . $dateTime->format('l, F j, Y');
}


$updateQuery = "UPDATE appointment SET seen = 'yes' WHERE seen = 'no'";

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
    <div class="row">


        <div class="col-12 col-xl-12">
            <div class="box" style="overflow-x:auto;">
                <div class="box-body">
                    <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">All Appointments<br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Reference</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Time</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php

        include '../../db_conn.php';

        $sql = "SELECT a.*, cr.*
                FROM appointment a
                INNER JOIN child_record cr ON cr.child_id = a.child_id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $appointment_id = htmlspecialchars($row['appointment_id']); 
                $ref = htmlspecialchars($row['ref']); 
                $child_name = htmlspecialchars($row['child_name']); 
                $appointment_date = htmlspecialchars($row['appointment_date']); 
                $session_time = htmlspecialchars($row['session_time']); 

                echo "<tr id='appointment-row-$appointment_id'>
                         <td>$ref</td>
                         <td>$child_name</td>
                         <td>$appointment_date</td>
                         <td>$session_time</td>
                         <td><button class='btn btn-danger btn-lg delete-btn' data-id='$appointment_id' style='font-size: 12px;padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button></td>
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

    $('#sessionForm').on('submit', function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'update_session.php', 
            data: formData,
            success: function(response) {
                alert(response); 
            },
            error: function() {
                alert('An error occurred while updating the session.');
            }
        });
    });
});

    $(document).ready(function() {

    $('.delete-btn').on('click', function() {
        var appointmentId = $(this).data('id'); 

        if (confirm("Are you sure you want to delete this appointment?")) {

            $.ajax({
                type: 'POST',
                url: 'delete_appointment.php', 
                data: { appointment_id: appointmentId }, 
                success: function(response) {
                    if (response == 'success') {

                        $('#appointment-row-' + appointmentId).remove();
                        alert('Appointment deleted successfully!');
                    } else {
                        alert('Error: ' + response);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the appointment.');
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

document.getElementById("morning_slots").addEventListener("keydown", function(event) {
    if (event.key === "-" || event.keyCode === 189) {
        event.preventDefault();
    }
});

document.getElementById("afternoon_slots").addEventListener("keydown", function(event) {
    if (event.key === "-" || event.keyCode === 189) {
        event.preventDefault();
    }
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