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



<div class="col-12 col-xl-12 col-md-12 col-sm-12" style="display: flex; flex-wrap: nowrap; overflow: auto;">

<?php
include '../../db_conn.php';

$sql = "SELECT 
        YEAR(STR_TO_DATE(e.enrollment_date, '%b %d, %Y at %h:%i:%s %p')) AS enrollment_year,
        MONTH(STR_TO_DATE(e.enrollment_date, '%b %d, %Y at %h:%i:%s %p')) AS enrollment_month,
        COUNT(e.enrollment_id) AS total_enrollments
    FROM 
        enrollment e
    INNER JOIN 
        parental_information p ON e.child_id = p.child_id
    INNER JOIN 
        child_record cr ON cr.child_id = p.child_id
    GROUP BY 
        enrollment_year, enrollment_month
    ORDER BY 
        enrollment_year DESC, enrollment_month DESC;";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
       
        $enrollment_year = $row['enrollment_year'];
        $enrollment_month = $row['enrollment_month'];
        $total_enrollments = $row['total_enrollments'];

        $month_name = date("F", mktime(0, 0, 0, $enrollment_month, 10));
        ?>

        <div class="box card-box" style="min-height: 150px; margin-right: 10px;">
            <div class="icon-box bg-color-6 d-block" style="width: 300px; justify-content: center; align-content: center; align-items: center;">
                <div class="content text-center color-6">
                    <h5 class="title-box fs-17 font-w500"><?php echo $month_name; ?> <?php echo $enrollment_year; ?></h5>
                    <div class="themesflat-counter fs-18 font-wb">
                        <span class="number"><?php echo $total_enrollments; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
</div>




                <div class="col-12 col-md-12">
                    <div class="box ">
                        <div class="box-header pt-0">
                            <div class="" style="display: flex;flex-wrap:wrap;justify-content: space-between;width: 100%;">
                                <h4 class="card-title mb-0 fs-22">All&nbsp;Enrollments<br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h4>
                                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                            </div>
                        </div>
                        <div class="box-body pb-0 table-responsive activity mt-18">
                            <?php
include '../../db_conn.php'; 
$query = "SELECT 
    e.*,  
    p.*,
    cr.*
FROM 
    enrollment e
INNER JOIN 
    parental_information p 
    ON e.child_id = p.child_id
INNER JOIN 
    child_record cr 
    ON cr.child_id = p.child_id
ORDER BY 
    e.enrollment_id DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">';
        echo '<thead>';
        echo '<tr class="top" style="background: #035392;color:white;">';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Reference</th>';
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>';
         echo '<th class="border-bottom-0 sorting fs-14 font-w500">Gender</th>'; 
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Status</th>'; 
        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Date</th>';
        echo '<th class="border-bottom-0 sorting_disabled fs-14 font-w500">Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['ref']) . '</button></td>
      </form>';
echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['child_name']) . '</button></td>
      </form>';
      echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['gender']) . '</button></td>
      </form>';
echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['enrollment_status']) . '</button></td>
      </form>';
echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
        <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['enrollment_date']) . '</button></td>
      </form>';
            echo '<td>';
            echo '<div class="dropdown">';
            echo '<a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">';
            echo '<i class="bx bx-dots-horizontal-rounded"></i>';
            echo '</a>';
            echo '<div class="dropdown-menu" style="position: absolute; right: 0px; cursor: pointer;">';
           echo '<form method="post" action="view_enrollment">
        <input type="hidden" name="ref" value="' . urlencode($row['ref']) . '">
        <a class="dropdown-item" href="javascript:void(0);" onclick="this.closest(\'form\').submit();">
            <i class="bx bx-right-arrow-alt"></i> View
        </a>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    $('.accept-btn').click(function(event) {
        event.preventDefault(); 
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

function filterTable(inputId, tableId, totalFoundId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase().trim();
    const table = document.getElementById(tableId);
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

                        const regex = new RegExp(`\\b${word}\\b`, 'i'); 

                        if (regex.test(cellValue)) {
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

    document.getElementById(totalFoundId).innerText = `${matchingCells} Found`;
}

window.onload = function() {
    filterTable('searchInput', 'enrollments', 'totalfound'); 
};

document.getElementById('searchInput').addEventListener('input', function() {
    filterTable('searchInput', 'enrollments', 'totalfound');
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