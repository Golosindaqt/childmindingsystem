<?php

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

$totalPending = getTotalRows($conn, 'enrollment', '', "enrollment_status = 'pending'");
$totalMale = getTotalRows($conn, 'child_record', 'INNER JOIN enrollment ON child_record.child_id = enrollment.child_id', "child_record.gender = 'Male' AND enrollment.enrollment_status = 'accepted'");
$totalFemale = getTotalRows($conn, 'child_record', 'INNER JOIN enrollment ON child_record.child_id = enrollment.child_id', "child_record.gender = 'Female' AND enrollment.enrollment_status = 'accepted'");

$totalChildren = $totalMale + $totalFemale;

$male_monthly_totals = array_fill(0, 12, 0);
$female_monthly_totals = array_fill(0, 12, 0);

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






$children_query = "
    SELECT c.*, e.*
    FROM child_record c
    INNER JOIN enrollment e ON e.child_id = c.child_id
    WHERE e.enrollment_status = 'accepted';
";

$result = mysqli_query($conn, $children_query);

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
                <div class="col-4 col-xl-12" style="display:none;">
            <div class="box">
                <div class="box-body">
                    <h6>Today's Session</h6>

           

<form id="sessionForm" style="margin-top: 20px;">
    <!-- Hidden input for session_id -->
    <input name="session_id" value="1" hidden>

    <div class="row">
    <!-- Available Slots -->
    <div class="col-md-6 col-sm-12 mb-24">
        <div class="form-group">
            <label class="form-label">Morning Slots</label>
            <input class="form-control" 
                   name="morning_slots" 
                   value="<?php echo htmlspecialchars($morning_slots); ?>" 
                   type="number" 
                   min="1" 
                   step="1" 
                   required 
                   id="morning_slots" 
                   onkeydown="return event.keyCode !== 189;">
        </div>
    </div>

    <div class="col-md-6 col-sm-12 mb-24">
        <div class="form-group">
            <label class="form-label">Afternoon Slots</label>
            <input class="form-control" 
                   name="afternoon_slots" 
                   value="<?php echo htmlspecialchars($afternoon_slots); ?>" 
                   type="number" 
                   min="1" 
                   step="1" 
                   required 
                   id="afternoon_slots" 
                   onkeydown="return event.keyCode !== 189;">
        </div>
    </div>
</div>
    <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px;margin-top: 0px;margin-left: 20px;padding: 10px;">Update Session</button>
    </div>
</form>

                    <!-- End of Form -->
                </div>
            </div>
        </div>







                <div class="col-12 col-xl-12 col-md-12 col-sm-12">
                    <div class="box card-box" style="min-height: 200px;">
                        <div class="icon-box bg-color-6 d-block" style="justify-content: center;align-content: center;align-items: center;" onclick="window.location.href='all_enrollments'">

                            <div class="content text-center color-6">
                                <h5 class="title-box fs-17 font-w500">Children Enrolled</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalChildren;?></span>
                                </div>
                            </div>
                        </div>
                        <div class="icon-box bg-color-1 d-block" style="justify-content: center;align-content: center;align-items: center;"  onclick="window.location.href='pending_enrollments'">

                            <div class="content text-center color-7">
                                <h5 class="title-box fs-17 font-w500">Pending Enrollment</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalPending;?></span>
                                </div>
                            </div>
                        </div>
                        <div class="icon-box bg-color-6 d-block" style="justify-content: center;align-content: center;align-items: center;">

                            <div class="content text-center color-6">
                                <h5 class="title-box fs-17 font-w500">Male Children</h5>
                                <div class="themesflat-counter fs-18 font-wb">
                                    <span class="number"><?php echo $totalMale;?></span>
                                </div>
                            </div>
                        </div>

                        <div class="icon-box bg-color-1 d-block" style="justify-content: center;align-content: center;align-items: center;">

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


 <div class="row"  style="background: transparent;margin:0px;padding: 0px; <?php echo $totalPending == 0 ? 'display: none;' : ''; ?>">
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

        echo '<div class="box box-carousel"  style="width:100%;min-width:300px">';
        echo '    <div class="card-top">';
        echo '        <div class="sm-f-wrap d-flex">';
        echo '            <h5 class="icon-gold text-white bg-yellow">#</h5>';
    echo '
    <form method="post" action="view_enrollment" id="refForm">
        <input type="hidden" class="" name="ref" value="' . htmlspecialchars($row['ref']) . '">
    </form>
    <a href="javascript:void(0);" onclick="document.getElementById(\'refForm\').submit();" class="h6 t-title" style="margin-top:0px;margin-bottom:20px;color:black;">
        ' . htmlspecialchars($row['ref']) . '
    </a>
';

        echo '        </div>';
        echo '        <div class="dropdown" style="margin-top:7px">';
        echo '            <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">';
        echo '                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
        echo '                    <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>';
        echo '                    <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>';
        echo '                    <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>';
        echo '                </svg>';
        echo '            </a>';
        echo '            <div class="dropdown-menu" style="position: absolute;right: 0px;cursor:pointer">';
     echo '
    <form action="view_enrollment" method="POST" id="refForm' . htmlspecialchars($row['ref']) . '" style="display:inline;">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <a class="dropdown-item" href="javascript:void(0);" onclick="document.getElementById(\'refForm' . htmlspecialchars($row['ref']) . '\').submit();">
            <i class="bx bx-right-arrow-alt"></i> View
        </a>
    </form>
';

        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '    <div class="card-center">';
        echo '        <div class="font-w400">Name: ' . htmlspecialchars($row['child_name']) . '</div>';
        echo '        <div class="font-w400">Birthday: ' . htmlspecialchars($formattedDateOfBirth) . '</div>';
        echo '        <div class="font-w400">Age: ' . htmlspecialchars($row['child_age']) . '</div>';
        echo '        <div class="font-w400">Gender: ' . htmlspecialchars($row['gender']) . '</div>';
        echo '        <div class="font-w400">Birthplace: ' . htmlspecialchars($row['place_of_birth']) . '</div>';
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

<div class="col-12">

                            <div class="box f-height">
                                <div class="box-header d-flex justify-content-between mb-wrap">
                                    <h3 class="mt-9 ml-5">Gender Statistics</h3>
                                    <ul class="card-list mb-0">
                                        <li class="custom-label"><span></span>Male</li>
                                        <li class="custom-label"><span></span>Female</li>
                                    </ul>
                                </div>
                                <div class="box-body pt-20">
                                    <div id="customer-chart"></div>
                                </div>
                            </div>

                        </div>

           






<div class="row">
        <div class="col-4 col-xl-12">
            <div class="box">
                <div class="box-body">
                    <h6>Mark Attendance</h6>
                    <form id="attendanceForm" action="insert_attendance.php" method="POST" style="margin-top: 20px;">
               

                         <input name="teacher_id" value="<?php echo htmlspecialchars($teacherId); ?>" hidden>

                        <div class="row">
                          
                           <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Child Name</label>
        <select class="form-control" name="child_id" required>
            <option hidden selected value="" >...</option>
            <?php

            foreach ($children as $child) {

                $selected = ($childData['child_id'] == $child['child_id']) ? 'selected' : ''; 
                echo "<option value=\"" . htmlspecialchars($child['child_id']) . "\" $selected>" . htmlspecialchars($child['child_name']) . "</option>";
            }
            ?>
        </select>
    </div>
</div>

                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Date</label>
                                    <input class="form-control" 
                                           type="date" 
                                           required
                                           name="date" value="<?php echo htmlspecialchars($defaultDate); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Full Name -->
                           <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Session</label>
         <select class="form-control" id="Session" name="Session" required>
             <option hidden selected value="" >...</option>
    <option value="Morning">Morning</option>
    <option value="Afternoon">Afternoon</option>
</select>
    </div>
</div>

                             <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" id="status" name="status" required>
           
            <option  hidden selected value="present">Present</option>
            <!-- <option value="leave">Leave</option>
            <option value="absent">Absent</option> -->
        </select>
    </div>
</div>

<div class="col-md-12 col-sm-12 mb-24" id="timeLeaveContainer" style="display: none;">
    <div class="form-group">
        <label class="form-label">Time Leave</label>
        <input type="time" class="form-control" name="time_leave">
    </div>
</div>
                        </div>

                 
                        <div class="gr-btn mt-15" style="display: flex;justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px;margin-top: 0px;margin-left: 20px;padding: 10px;">Submit</button>
                        </div>
                    </form>
                
                </div>
            </div>
        </div>

        <div class="col-8 col-xl-12" >
            <div class="box" style="overflow-x:auto; max-height: 500px;">
                <div class="box-body">
                    <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Attendance Record <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>

<?php

include '../../db_conn.php';

$sql = "SELECT *
        FROM attendance_record a 
        JOIN child_record c ON a.child_id = c.child_id
        ORDER BY a.date DESC";

$result = $conn->query($sql);
?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>

            <th class="border-bottom-0 sorting fs-14 font-w500">Session</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Status</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $attendanceId = htmlspecialchars($row['attendance_id']); 
                $date = htmlspecialchars($row['date']);
                $session = htmlspecialchars($row['shift']);
                $status = htmlspecialchars($row['status']);
                   $time_leave = htmlspecialchars($row['time_leave']);
                $time_leave = date("g:i A", strtotime($time_leave));
                $childName = htmlspecialchars($row['child_name']);

                $formattedDate = date('M d, Y', strtotime($date));

                echo "<tr>
    <td>$childName</td>
    <td>$session</td>
    <td>$formattedDate</td>
    <td>";
    
    if ($status == 'leave') {
        echo '<span>' . $status . ' (' . $time_leave . ') </span>' ;
    } else {
        echo $status;
    }

echo "</td>
    <td><button class='btn btn-danger btn-lg delete-btn' data-id='$attendanceId' style='font-size: 12px;padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button></td>
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

    <div class="overlay"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

     document.getElementById('status').addEventListener('change', function() {
        var status = this.value;
        var timeLeaveContainer = document.getElementById('timeLeaveContainer');
        
        // If "leave" is selected, show the Time Leave input, otherwise hide it
        if (status === 'leave') {
            timeLeaveContainer.style.display = 'block';
        } else {
            timeLeaveContainer.style.display = 'none';
        }
    });


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