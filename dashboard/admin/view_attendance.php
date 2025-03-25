<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if session variables are set
if (isset($_SESSION['teacher_logged_email'])) {
    $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];
}

$date = ""; // Initialize $date to avoid undefined variable warnings
$formattedDate = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['date'])) {
        $date = htmlspecialchars($_POST['date']);
        $formattedDate = date("F Y", strtotime($date));
    }
}

include '../../db_conn.php';

$totalRows = 0; // Initialize $totalRows

if (!empty($date)) {
    // Query to get the total rows
    $countQuery = "SELECT COUNT(*) AS total_rows 
                   FROM attendance_record a 
                   JOIN child_record c 
                   ON a.child_id = c.child_id 
                   WHERE DATE_FORMAT(a.date, '%Y-%m') = '$date'";
    
    $countResult = $conn->query($countQuery);

    if ($countResult && $countResult->num_rows > 0) {
        $countRow = $countResult->fetch_assoc();
        $totalRows = $countRow['total_rows'];
    }

    // Query to fetch attendance data
    $sql = "SELECT a.date, c.child_name 
            FROM attendance_record a 
            JOIN child_record c 
            ON a.child_id = c.child_id 
            WHERE DATE_FORMAT(a.date, '%Y-%m') = '$date' 
            ORDER BY a.date DESC";

    $result = $conn->query($sql);

    $attendanceData = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $childName = htmlspecialchars($row['child_name']);
            $date = htmlspecialchars($row['date']);
            $week = ceil(date('j', strtotime($date)) / 7);

            if (!isset($attendanceData[$childName])) {
                $attendanceData[$childName] = [
                    'Week 1' => 0,
                    'Week 2' => 0,
                    'Week 3' => 0,
                    'Week 4' => 0,
                    'Week 5' => 0,
                ];
            }

            if ($week >= 1 && $week <= 5) {
                $attendanceData[$childName]["Week $week"]++;
            }
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();

// Debugging: Uncomment to see the results
// echo "Total Rows: $totalRows";
// echo '<pre>' . print_r($attendanceData, true) . '</pre>';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px;">
                            <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">
                                Date: <span style="font-size: 15px;color: #035392"><?php echo $formattedDate; ?></span>
                                <br><span style="font-size: 15px;color: #035392" id="totalfound"><?php echo $totalRows; ?> Found</span>
                                <br>
                                <button type="button" class="btn btn-primary btn-lg" id="download-pdf-btn" style="font-size: 12px; margin-top: 0px; margin-left: 0px; padding: 10">
    <i class='bx bx-download'></i> Download
</button>

                            </h5>
                        </div>

                        <div id="attendance_table">
                            <img src="header_monthly.png" style="width:100%; height: 200px;"> 
                            <table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" role="grid">
                                <thead>
                                    <tr class="top" style="background: #035392; color: white;">
                                        <th style="font-weight: 600;">Name</th>
                                        <th style="font-weight: 600;">Week 1<br>M T W T F S S</th>
                                        <th style="font-weight: 600;">Week 2<br>M T W T F S S</th>
                                        <th style="font-weight: 600;">Week 3<br>M T W T F S S</th>
                                        <th style="font-weight: 600;">Week 4<br>M T W T F S S</th>
                                        <th style="font-weight: 600;">Week 5<br>M T W T F S S</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($attendanceData)) {
                                        foreach ($attendanceData as $name => $weeks) {
                                            echo "<tr>";
                                            echo "<td>$name</td>";
                                            echo "<td>{$weeks['Week 1']}</td>";
                                            echo "<td>{$weeks['Week 2']}</td>";
                                            echo "<td>{$weeks['Week 3']}</td>";
                                            echo "<td>{$weeks['Week 4']}</td>";
                                            echo "<td>{$weeks['Week 5']}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No attendance records found</td></tr>";
                                    }
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <script type="text/javascript">
      document.getElementById('download-pdf-btn').addEventListener('click', function () {
    const element = document.getElementById('attendance_table');

    const options = {
        margin: 0.5,
        filename: '<?php echo $formattedDate; ?> - Attendance Report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
    };

    html2pdf().set(options).from(element).save();
});

  </script>
</body>
</html>
