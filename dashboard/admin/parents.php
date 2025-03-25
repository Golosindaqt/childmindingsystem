<?php
include '../../db_conn.php';

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

$sql = "
    SELECT u.*, p.*
    FROM user u
    INNER JOIN parental_information p ON u.email = p.email
    WHERE u.role_id = 2 AND (p.father_name LIKE '%$searchTerm%' OR p.mother_name LIKE '%$searchTerm%' OR u.email LIKE '%$searchTerm%') AND u.username IS NOT NULL 
          AND u.username <> ''
    GROUP BY p.father_name, p.mother_name;

";

$result = $conn->query($sql);
$output = "";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "
        <div class='col-3 col-md-6 col-sm-12 mb-25'>
            <div class='box client'>
                <div class='dropdown'>
                    <a href='javascript:void(0);' class='btn-link' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </a>
                    <div class='dropdown-menu dropdown-menu-right'>
                        <form method='POST' action='view_parent.php'>
                            <input type='hidden' name='email' value='{$row['email']}'>
                            <input type='hidden' name='father_name' value='{$row['father_name']}'>
                            <input type='hidden' name='mother_name' value='{$row['mother_name']}'>
                            <button type='submit' class='dropdown-item'><i class='bx bx-right-arrow-alt'></i> View</button>
                        </form>
                    </div>
                </div>
                <div class='box-body pt-5 pb-0'>

                    <form method='POST' action='view_parent.php' id='viewParentForm_{$row['email']}'>
                        <input type='hidden' name='email' value='{$row['email']}'>
                        <input type='hidden' name='father_name' value='{$row['father_name']}'>
                        <input type='hidden' name='mother_name' value='{$row['mother_name']}'>
                        <!-- Fixing the onclick function -->
                        <h5 class='mt-0' style='cursor:pointer' onclick='submitForm(\"viewParentForm_{$row['email']}\")'>{$row['mother_name']}</h5>
                    </form>

                    <p class='fs-14 font-w400 font-main'>{$row['father_name']}</p>
                    <ul class='info'>
                        <li class='fs-14'><i class='bx bxs-phone' style='color: #035392'></i>{$row['mother_work_phone']} | {$row['father_work_phone']}</li>
                        <li class='fs-14'><i class='bx bxs-envelope' style='color: #035392'></i>{$row['email']}</li>
                        <li class='fs-14'><i class='bx bxs-user' style='color: #035392'></i>{$row['username']}</li>
                    </ul>
                    <div class='group-btn d-flex justify-content-between'>

                   <a class='bg-btn-sec' style='background: #E4A40F;color:white' href='javascript:void(0);' onclick='submitForm(\"viewParentForm_{$row['email']}\")'>View Profile</a>

                    </div>
                </div>
            </div>
        </div>";
    }
} else {
    $output = "<p style='text-align: center'>No data found.</p>";
}

mysqli_close($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo $output;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
    <style type="text/css">
        .dropdown-menu a:hover { background:#035392;color:white }
    </style>
</head>
<body class="sidebar-expand counter-scroll">
    <?php include("leftsidebar.php"); ?>
    <?php include("header.php"); ?>

    <div class="main">
        <div class="main-content project">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header pt-0">
                            <div class="" style="display: flex;flex-wrap:wrap;justify-content: space-between;width: 100%;">
                                <h4 class="card-title mb-0 fs-22">All&nbsp;Parents<br><span style="font-size: 15px;color: #035392" id="recordCount"></span></h4>
                                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;">
                            </div>
                        </div>
                    </div>
                </div> 

                <div id="searchResults" class="" style="justify-content: center;display: flex;flex-wrap: wrap;"></div>
            </div>
        </div>
    </div>

    <div class="overlay"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function submitForm(formId) {
            document.getElementById(formId).submit();
        }

        function fetchResults(searchTerm = '') {
            $.ajax({
                url: '',
                type: 'POST',
                data: { searchTerm: searchTerm },
                success: function(response) {
                    $('#searchResults').html(response);
                    const recordCount = $('#searchResults .col-3').length;
                    $('#recordCount').text(recordCount + ' Found');
                },
                error: function() {
                    alert('An error occurred while fetching results.');
                }
            });
        }

        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val();
            fetchResults(searchTerm);
        });

        $(document).ready(function() {
            fetchResults();
        });
    </script>

    <script src="../libs/jquery/jquery.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>

</body>
</html>