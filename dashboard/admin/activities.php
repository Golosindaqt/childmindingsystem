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
                <div class="col-4 col-xl-12">
                    <div class="box">
                        <div class="box-body">
                            <h6>Create Activity</h6>

                            <form id="ActivityForm" autocomplete="off" enctype="multipart/form-data" method="POST" style="margin-top: 20px;">
                         
                                <input name="teacher_id" value="<?= htmlspecialchars($teacherId); ?>" hidden>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-24">
                                        <div class="form-group">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 mb-24">
                                        <div class="form-group">
                                            <label class="form-label">Month</label>
                                            <input type="month" name="month" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 mb-24">
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" required name="description" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
                                    <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px; margin-top: 0px; margin-left: 20px; padding: 10px;">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-8 col-xl-12">
                    <div class="box" style="overflow-x:auto;">
                        <div class="box-body">
                            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px;">
                                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Activity Records <br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                            </div>

                            <?php
                            include '../../db_conn.php';

                            $sql = "SELECT * FROM activity_report ORDER BY date DESC";
                            $result = $conn->query($sql);
                            ?>

                            <table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
                                <thead>
                                    <tr class="top" style="background: #035392;color:white">
                                        <th class="border-bottom-0 sorting fs-14 font-w500" >Title</th>
                                        <th class="border-bottom-0 sorting fs-14 font-w500" >Month</th>
                                        <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $activityid = htmlspecialchars($row['activity_id']);
                                            $date = htmlspecialchars($row['date']);
                                            $title = htmlspecialchars($row['title']);
                                            $description = htmlspecialchars($row['description']);
                                            $formattedDate = date('F Y', strtotime($date));

                                            echo "<tr>
                                                    <td>$title</td>
                                                    <td>$formattedDate</td>
                                                    <td>

                                                    <form action='view_activities.php' method='POST'>
                        <input type='hidden' name='title' value='$title'>
                        <input type='hidden' name='activityid' value='$activityid'>
                        <input type='hidden' name='date' value='$formattedDate'>
                        <input type='hidden' name='description' value='$description'>
                        <button type='submit' class='btn btn-danger btn-lg mb-5' style='font-size: 12px;padding: 10px;background:#035392'>
                            &nbsp;<i class='bx bx-right-arrow-alt'></i> View &nbsp
                        </button>
                    </form>
                                                    <button class='btn btn-danger btn-lg delete-btn' data-id='$activityid' style='font-size: 12px;  padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button>

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
