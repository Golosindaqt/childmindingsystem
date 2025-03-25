<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

include('../../db_conn.php');

$query = "
    SELECT c.*, e.*
    FROM child_record c
    INNER JOIN enrollment e ON e.child_id = c.child_id
    WHERE e.enrollment_status = 'accepted' AND c.user_id = $parentUserId;
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {

    $children = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {

    $children = [];
}



$defaultDate = date('Y-m-d');  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {

    $selectedDate = $_POST['date'];

    echo "The selected date is: " . htmlspecialchars($selectedDate);

    $dateTime = new DateTime($selectedDate);

    echo "<br>Formatted Date: " . $dateTime->format('l, F j, Y');
}






        $sqlsession = "SELECT * FROM session";

        $resultsession = $conn->query($sqlsession);

        if ($resultsession->num_rows > 0) {

            while ($row = $resultsession->fetch_assoc()) {
                $morning_slots = htmlspecialchars($row['morning_slots']); 
                $afternoon_slots = htmlspecialchars($row['afternoon_slots']); 

            }
        } else {

            echo "";
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

    document.getElementById('dateInput').value = datePart;
    document.getElementById('timeInput').value = timePart;

}

setInterval(updateDateTime, 100);
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
                    <h6>Set an Appointment</h6>

                   <form id="AppointmentForm" action="setappointment.php" method="POST" style="margin-top: 20px;">
    <!-- Hidden Fields -->
    <input name="user_id" value="<?php echo htmlspecialchars($parentUserId); ?>" hidden>
    <input class="form-control" name="session_id" value="1" hidden>
    <input class="form-control" id="timeInput" hidden>
    <input class="form-control" id="ref" name="ref" hidden>

    <div class="row">
        <div class="col-md-12 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Child Name</label>
                <select class="form-control" name="child_id" required>
                    <option hidden selected value="">...</option>
                    <?php
foreach ($children as $child) {
    // Check if the current child is the selected one (replace $selectedChildId with your actual selected child ID)
    $selected = ($child['child_id'] == $selectedChildId) ? 'selected' : ''; 
    echo "<option value=\"" . htmlspecialchars($child['child_id']) . "\" $selected>" . htmlspecialchars($child['child_name']) . "</option>";
}
?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Session</label>
                <select class="form-control" id="Session" name="session_time" required>
                    <option hidden selected value="">...</option>
                    <option value="Morning - (9:00 - 11:30 AM)">Morning - (9:00 - 11:30 AM)</option>
                    <option value="Afternoon - (1:00 - 4:30 PM)">Afternoon - (1:00 - 4:30 PM)</option>
                </select>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Date</label>
                <input class="form-control" id="dateInput" readonly name="appointment_date">
            </div>
        </div>
    </div>

    <div >
        <div style="font-size: 15px; font-weight: bold;color: black;">Available Morning Slots: <?php echo $morning_slots; ?></div>

        <div style="font-size: 15px; font-weight: bold;color: black;">Availabe Afternoon Slots: <?php echo $afternoon_slots; ?></div>
    </div>

    <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px;margin-top: 0px;margin-left: 20px;padding: 10px;">Submit</button>
    </div>

</form>
                    <!-- End of Form -->
                </div>
            </div>
        </div>

        <div class="col-8 col-xl-12">
            <div class="box" style="overflow-x:auto;">
                <div class="box-body">
                    <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Appointment Record <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>
<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Reference</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Time</th>
            <th class="border-bottom-0 sorting fs-14 font-w500"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        include '../../db_conn.php';

        $sql = "SELECT a.*, cr.*
                FROM appointment a
                INNER JOIN child_record cr ON cr.child_id = a.child_id
                WHERE a.user_id = $parentUserId";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointment_id = htmlspecialchars($row['appointment_id']); 
                $ref = htmlspecialchars($row['ref']); 
                $child_name = htmlspecialchars($row['child_name']); 
                $appointment_date = htmlspecialchars($row['appointment_date']); 
                $session_time = htmlspecialchars($row['session_time']); 

                echo "<tr class='appointment-row' style='cursor:pointer' data-appointment-id='$appointment_id'>
                         <td>$ref</td>
                         <td>$child_name</td>
                         <td>$appointment_date</td>
                         <td>$session_time</td>
                         <td> <button type='submit' class='btn btn-danger btn-lg mb-5' style='font-size: 12px;padding: 10px;background:#035392'>
                            &nbsp;<i class='bx bx-right-arrow-alt'></i> View &nbsp
                        </button></td>
                      </tr>";
            }
        } else {
            echo "";
        }

        $conn->close();
        ?>
    </tbody>
</table>

<form id="appointment-form" method="POST" action="viewappointment.php" style="display:none;">
    <input type="hidden" name="appointment_id" id="appointment_id">
    <input type="hidden" name="Reference" id="Reference">
    <input type="hidden" name="child_name" id="child_name">
    <input type="hidden" name="appointment_date" id="appointment_date">
    <input type="hidden" name="session_time" id="session_time">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.appointment-row');
        rows.forEach(function(row) {
            row.addEventListener('click', function() {
                const appointmentId = row.getAttribute('data-appointment-id');
                const Reference = row.cells[0].innerText;
                const childName = row.cells[1].innerText;
                const appointmentDate = row.cells[2].innerText;
                const sessionTime = row.cells[3].innerText;

                console.log('Appointment ID:', appointmentId);
                console.log('Referenceerence:', Reference);
                console.log('Child Name:', childName);
                console.log('Appointment Date:', appointmentDate);
                console.log('Session Time:', sessionTime);

                document.getElementById('appointment_id').value = appointmentId;
                document.getElementById('Reference').value = Reference;
                document.getElementById('child_name').value = childName;
                document.getElementById('appointment_date').value = appointmentDate;
                document.getElementById('session_time').value = sessionTime;

                document.getElementById('appointment-form').submit();
            });
        });
    });
</script>


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

function getref() {

    const timeInput = document.getElementById('timeInput').value.trim();

    const cleanedValue = timeInput.replace(/[a-zA-Z:]/g, '');

    document.getElementById('ref').value = cleanedValue;
}

setInterval(getref, 100);

function adjustSessionOptions() {
    const timeInput = document.getElementById('timeInput').value.trim();  
    const sessionSelect = document.getElementById('Session');
    const morningOption = sessionSelect.querySelector('option[value="Morning - (9:00 - 11:30 AM)"]');
    const afternoonOption = sessionSelect.querySelector('option[value="Afternoon - (1:00 - 4:30 PM)"]');

    if (!timeInput) {
        return; 
    }

    // Helper function to parse time string to Date object
    function parseTime(timeStr) {
        const timeRegex = /(\d{1,2}):(\d{2}):(\d{2})\s*(AM|PM)/i;
        const matches = timeStr.match(timeRegex);

        if (matches) {
            const [, hour, minute, second, period] = matches;
            let hours = parseInt(hour);
            if (period.toUpperCase() === 'PM' && hours < 12) {
                hours += 12; 
            } else if (period.toUpperCase() === 'AM' && hours === 12) {
                hours = 0; 
            }
            return new Date(1970, 0, 1, hours, parseInt(minute), parseInt(second));
        } else {
            return null; 
        }
    }

    const inputTime = parseTime(timeInput);

    // Define session end times for comparison
    const morningEnd = new Date('1970-01-01T11:30:00');   
    const afternoonStart = new Date('1970-01-01T16:30:00'); 
    const eveningEnd = new Date('1970-01-01T23:59:00');    

    // Hide the morning option if the current time is past the morning session
    if (inputTime >= morningEnd) {
        if (morningOption) {
            morningOption.style.display = 'none'; // Hide the option
        }
    } else {
        if (morningOption) {
            morningOption.style.display = ''; // Ensure it's visible
        }
    }

    // Hide the afternoon option if the current time is past the afternoon session
    if (inputTime >= afternoonStart) {
        if (afternoonOption) {
            afternoonOption.style.display = 'none'; // Hide the option
        }
    } else {
        if (afternoonOption) {
            afternoonOption.style.display = ''; // Ensure it's visible
        }
    }
}


setInterval(adjustSessionOptions, 100);

$(document).ready(function() {
    $('#AppointmentForm').on('submit', function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'setappointment.php', 
            data: formData,
            success: function(response) {
                alert(response); 

                $('#AppointmentForm')[0].reset();

                  window.location.reload();
            },
            error: function() {
                alert('Error occurred while submitting the form.');
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