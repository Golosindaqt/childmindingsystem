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
    WHERE e.enrollment_status = 'accepted'
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
                    <h6>Submit a Report</h6>

                    <!-- Start of Form -->
                    <form id="ReportForm" action="insert_report.php" method="POST" style="margin-top: 20px;">
    <!-- Hidden notification_id (for database) -->

    <!-- Hidden teacher_id -->
    <input name="teacher_id" value="<?php echo htmlspecialchars($teacherId); ?>" hidden>

    <div class="row">
        <!-- Child Name Dropdown -->
        <div class="col-md-6 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Child Name</label>
                <select class="form-control" name="child_id" required>
                    <option hidden selected value="">...</option>
                    <?php

                    foreach ($children as $child) {

                        echo "<option value=\"" . htmlspecialchars($child['child_id']) . "\" $selected>" . htmlspecialchars($child['child_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Type</label>
        <input type="text" name="type" placeholder="Anecdotal Report" class="form-control" required>
    </div>
</div>

            <div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>
</div>

<div class="col-md-6 col-sm-12 mb-24">
    <div class="form-group">
        <label class="form-label">Time</label>
        <input type="time" name="time" class="form-control" required>
    </div>
</div>

    <div class="col-md-12 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Location</label>
                 <input type="text" name="location" class="form-control" required placeholder="Childminding Center">
            </div>
        </div>

        <!-- Message Field (Long Text) -->
        <div class="col-md-12 col-sm-12 mb-24">
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" required name="description" rows="4"></textarea>
            </div>
        </div>

    </div>

    <!-- Submit Button -->
    <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px; margin-top: 0px; margin-left: 20px; padding: 10px;">
            Submit
        </button>
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
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Reports Record <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>

<?php

include '../../db_conn.php';

$sql = "SELECT ir.*, c.* 
FROM incident_report ir
INNER JOIN child_record c ON c.child_id = ir.child_id";

$result = $conn->query($sql);
?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="reports" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>

            <th class="border-bottom-0 sorting fs-14 font-w500">Type</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Time</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
               $incident_id = htmlspecialchars($row['incident_id']); 
$date = htmlspecialchars($row['date']);
$type = htmlspecialchars($row['type']);
$time = htmlspecialchars($row['time']);
$childName = htmlspecialchars($row['child_name']);

$formattedDate = date('M d, Y', strtotime($date));

$formattedTime = date('g:i A', strtotime($time)); 

                echo "<tr>
                        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$childName</button>
            </form>
        </td>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$type</button>
            </form>
        </td>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$formattedDate</button>
            </form>
        </td>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$formattedTime</button>
            </form>
        </td>
                        <td>

            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' class='btn btn-danger btn-lg ' style='font-size: 12px;padding: 10px 15px; margin-bottom:3px;background:#035392'>
                   <i class='bx bx-right-arrow-alt'></i> View
                </button>
            </form>

        <button class='btn btn-danger btn-lg delete-btn' data-id='$incident_id' style='font-size: 12px;padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button></td>
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

    </div>
    <!-- END MAIN CONTENT -->

    <div class="overlay"></div>

    <!-- SCRIPT -->
    <!-- APEX CHART -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

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
        $('#ReportForm').submit(function(e) {
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
    const table = document.getElementById('reports');
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

</html>