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
    SELECT c.*, e.*
    FROM child_record c
    INNER JOIN enrollment e ON e.child_id = c.child_id
    WHERE e.enrollment_status = 'accepted';
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
        <div class="col-4 col-xl-12">
            <div class="box">
                <div class="box-body">
                    <h6>Mark Attendance</h6>

                    <!-- Start of Form -->
                    <form id="attendanceForm" action="insert_attendance.php" method="POST" style="margin-top: 20px;">
                        <!-- First Row: Full Name -->

                         <input name="teacher_id" value="<?php echo htmlspecialchars($teacherId); ?>" hidden>

                        <div class="row">
                            <!-- Full Name -->
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
                           <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Session</label>
         <select class="form-control" id="Session" name="Session" required>
             <option hidden selected value="" >...</option>
    <option value="Morning">Morning</option>
    <option value="Afternoon">Afternoon</option>
    <option value="Both">Both</option>
</select>
    </div>
</div>

                            <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option hidden selected value="">...</option>
            <option value="present">Present</option>
            <!-- <option value="leave">Leave</option> -->
            <!-- <option value="absent">Absent</option> -->
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

        <div class="col-8 col-xl-12">
            <div class="box" style="overflow-x:auto;">
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