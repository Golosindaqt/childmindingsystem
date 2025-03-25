<?php
include '../../db_conn.php';

function getTotalRows($conn, $tableName, $joinQuery = null, $condition = null) {
    $query = "SELECT COUNT(*) AS total FROM " . $tableName;

    if ($joinQuery) {
        $query .= " " . $joinQuery;
    }

    if ($condition) {
        $query .= " WHERE " . $condition;
    }

    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $row = $result->fetch_assoc();
    return $row['total'];
}

$joinQuery = "INNER JOIN parental_information p ON e.child_id = p.child_id";
$condition = "enrollment_status = 'accepted'";

$totalfounddata = getTotalRows($conn, 'enrollment e', $joinQuery, $condition);
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
                 <div class="col-12 col-md-12">
                    <div class="box ">
                        <div class="box-header pt-0">
                            <div class="" style="display: flex;flex-wrap:wrap;justify-content: space-between;width: 100%;">
                                <h4 class="card-title mb-0 fs-22">Children Enrolled&nbsp;<br><span style="font-size: 15px;color: #035392"><?php echo $totalfounddata; ?> Found</span></h4>
                                   <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                            </div>
                        </div>
                        <div class="box-body pb-0 table-responsive activity mt-18">

                          <?php
include '../../db_conn.php'; 
$query = "
    SELECT 
        e.*,  
        cr.child_name, 
        cr.child_age, 
        cr.gender, 
        cr.date_of_birth, 
        cr.address, 
        cr.place_of_birth  
    FROM 
        enrollment e
    INNER JOIN 
        child_record cr 
        ON e.child_id = cr.child_id
    WHERE 
        e.enrollment_status = 'accepted'
    ORDER BY 
        e.enrollment_id DESC
";

$result = mysqli_query($conn, $query);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">';
        echo '<thead>';
        echo '<tr class="top" style="background: #035392;color:white">';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Age</th>';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Gender</th>';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Date of Birth</th>';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Address</th>';
        echo '<th class="border-bottom-0 sorting_disabled fs-14 font-w500">Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {

            $formatted_date_of_birth = date("M j, Y", strtotime($row['date_of_birth']));

            echo '<tr>';

echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['child_name']) . '</button></td>
      </form>';

echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['child_age']) . '</button></td>
      </form>';

echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['gender']) . '</button></td>
      </form>';

echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . $formatted_date_of_birth . '</button></td>
      </form>';

echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['address']) . '</button></td>
      </form>';

            echo '<td>';
            echo '<div class="dropdown">';
            echo '<a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">';
            echo '<i class="bx bx-dots-horizontal-rounded"></i>';
            echo '</a>';
            echo '<div class="dropdown-menu" style="position: absolute; right: 0px; cursor: pointer;">';
            echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <button type="submit" class="dropdown-item btn btn-link text-decoration-none" style="color:black">
          <i class="bx bx-right-arrow-alt"></i> View
        </button>
      </form>';

            echo '</div>'; 
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No data found.</p>';
    }
} else {
    echo 'Error: ' . mysqli_error($conn);
}
mysqli_close($conn); 
?>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="overlay"></div>
    <script>
        $(document).ready(function() {

    $('.reject-btn').click(function(event) {
        event.preventDefault(); 
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

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('enrollments');
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let match = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                const cellValue = cells[j].textContent || cells[j].innerText;
                if (cellValue.toLowerCase().includes(filter)) {
                    match = true;
                    break; 
                }
            }
        }
        rows[i].style.display = match ? "" : "none";
    }
}
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