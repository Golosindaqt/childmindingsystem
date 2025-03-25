<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$teacherEmail = $teacherUserId = $teacherId = $teacherUsername = "";

if (isset($_SESSION['teacher_logged_email'])) {
    $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];
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
            

<div class="col-12 col-xl-12 col-md-12 col-sm-12" style="display: flex; flex-wrap: nowrap;overflow: auto;">
<?php
include '../../db_conn.php';


$sql = "SELECT DATE_FORMAT(date, '%Y') AS year, DATE_FORMAT(date, '%m') AS month, COUNT(DISTINCT child_id) AS numchildren
        FROM attendance_record
        GROUP BY year, month
        ORDER BY year DESC, month DESC";

$result = mysqli_query($conn, $sql);

$totalcount = 0;
$yearData = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $year = $row['year'];
        $numchildren = $row['numchildren'];

        if (!isset($yearData[$year])) {
            $yearData[$year] = 0;  
        }
        
        $yearData[$year] += $numchildren;
    }

    foreach ($yearData as $year => $totalYearCount) {
        ?>
        <div class="box card-box" style="min-height: 150px; margin-right: 10px;">
            <div class="icon-box bg-color-6 d-block" style="width: 300px; justify-content: center;align-content: center;align-items: center;">
                <div class="content text-center color-6">
                    <h5 class="title-box fs-17 font-w500">Year: <?php echo $year; ?></h5>
                    <div class="themesflat-counter fs-18 font-wb">
                        <span class="number"><?php echo $totalYearCount; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $totalcount += $totalYearCount;
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
</div>




                <div class="col-12 col-xl-12">
                    <div class="box" style="overflow-x:auto;">
                        <div class="box-body">
                            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px;">
                                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Monthly Attendance <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                            </div>

                           <?php
include '../../db_conn.php';

$sql = "SELECT date, DATE_FORMAT(date, '%Y-%m') AS month, COUNT(DISTINCT child_id) AS numchildren
        FROM attendance_record
        GROUP BY month
        ORDER BY month DESC";

$result = $conn->query($sql);
?>
 <img src="header_monthly.png" style="width:100%; height: 200px;">  
<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Month</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Children</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $month = htmlspecialchars($row['month']);
                $numchildren = $row['numchildren'];
                $date = $row['date'];

                

                $formatted_date = date('F Y', strtotime($month));

                echo "<tr>
                        <td>$formatted_date</td>
                        <td>$numchildren</td>
                        <td>
                            <form action='view_attendance.php' method='POST'>
                                <input type='hidden' name='date' value='$month'>
                                <button type='submit' class='btn btn-danger btn-lg mb-5' style='font-size: 12px;padding: 10px;background:#035392'>
                                    &nbsp;<i class='bx bx-right-arrow-alt'></i> View &nbsp
                                </button>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "";
        }
        $conn->close();
        ?>
    </tbody>
</table>


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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            $(".delete-btn").click(function () {
                var activityid = $(this).data("id");

                if (confirm("Are you sure you want to delete this activity month? Note: All images will be deleted of this selected month.")) {

                    $.ajax({
                        url: 'delete_activity.php',
                        type: 'POST',
                        data: { activityid: activityid },
                        success: function (response) {
                            alert("Deleted successfully!");
                            window.location.reload();
                        },
                        error: function () {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }
            });

            $("#ActivityForm").on("submit", function (event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "insert_activityreport.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert(response);
                        window.location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert("Error occurred: " + error);
                    }
                });
            });

        });

        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('enrollments');
            const rows = table.getElementsByTagName('tr');
            let matchingCells = 0;

            const searchWords = filter.split(/\s+/).filter(word => word.length > 0);

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = true;

                for (let word of searchWords) {
                    let wordFoundInRow = false;

                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j]) {
                            const cellValue = cells[j].textContent || cells[j].innerText;

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

            document.getElementById('totalfound').innerText = `${matchingCells} Found`;
        }

        window.onload = function () {
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
