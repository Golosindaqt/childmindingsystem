<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['father_name']) && isset($_POST['mother_name'])) {
        $father_name = $_POST['father_name'];
        $mother_name = $_POST['mother_name'];
        $email = $_POST['email'];

        include '../../db_conn.php';

        $stmt = $conn->prepare("SELECT username FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);  
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $username = $row['username'];
        } else {

            $username = null;
        }

        $stmt->close();
        $conn->close();

    } else {
        echo "<script>window.history.back()</script>";
    }
} else {
    echo "<script>window.history.back()</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
    <style>
 .flexdocs { display:flex; justify-content:center;align-content:center; flex-wrap:wrap; }
        .docscon { margin:20px auto; height:350px; width:350px;  }
        .boxcon { margin:auto; height:90%; width:90%; background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat;  }
        .text-below { margin:10px auto; text-align:center; }
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
        <div class="main-content client project">
            <div class="row">
                    <div class="col-4 col-xl-12">
                    <div class="box user-pro-list overflow-hidden mb-30">
                        <div class="box-body">
                            <div class="user-pic text-center">
                                <div class="avatar ">
                                    <img src="../../img/favicon.png" alt="" height="112px" width="112px">
                                </div>
                                <div class="pro-user mt-3">
                                    <h5 class="pro-user-username text-dark mb-2 fs-15 mt-42 color-span"><?php echo $_POST['father_name']; ?></h5>
                                    <h6 class="pro-user-desc text-muted fs-14"><?php echo $_POST['mother_name'] ?></h6>
                                    <h6 class="pro-user-desc text-muted fs-14"><?php echo $_POST['email'] ?></h6>
                                    <span style="background-color: rgba(3, 83, 146, 0.3); color: black; padding: 5px 10px;font-weight: bold; font-size:10px; border-radius: 5px; display: inline-block;"><?php echo $username ?></span>
                                </div>
                            </div>
                        </div>
    <div class="box-footer pt-20">
            <div class="btn-list text-center">
                <!-- <a class="btn btn-primary accept-btn" data-ref="' . htmlspecialchars($data['ref']) . '" data-email="' . htmlspecialchars($data['email']) . '">Message</a> -->

            </div>
          </div>

                    </div>

                    <div class="box left-dot mb-30">
                        <div class="box-header  border-0 pd-0">
                            <div class="box-title fs-20 font-w600">Contact Info</div>
                        </div>
                        <div class="box-body pt-20 user-profile">

<?php
include '../../db_conn.php';

$father_name = '';
$mother_name = '';
$email = '';
$home_phone = '';
$father_work_phone = '';
$mother_work_phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['father_name']) && isset($_POST['mother_name'])) {
        $father_name = $_POST['father_name'];
        $mother_name = $_POST['mother_name'];

        if (!empty($father_name) && !empty($mother_name)) {
            $sql = "SELECT email, home_address, home_phone, father_work_phone, mother_work_phone 
                    FROM parental_information 
                    WHERE father_name = ? AND mother_name = ?";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $father_name, $mother_name);
                $stmt->execute();

                $stmt->bind_result($email, $home_address, $home_phone, $father_work_phone, $mother_work_phone);

                $found_results = false;
                while ($stmt->fetch()) {
                    $found_results = true;
                    echo "<p>Email:</p>";
                    echo "<p>$email</p>";
                    echo "<hr>";
                    echo "<p>Home Address:</p>";
                    echo "<p>$home_address</p>";
                    echo "<hr>";
                    echo "<p>Home Phone: $home_phone</p>";
                    echo "<p>Father's Work Phone: $father_work_phone</p>";
                    echo "<p>Mother's Work Phone: $mother_work_phone</p>";
                    echo "<hr>";
                }

                if (!$found_results) {
                    echo "<script>window.history.back()</script>";
                }

                $stmt->close();
            } else {
                echo "Error preparing SQL query: " . $conn->error;
            }
        } else {
            echo "<script>window.history.back()</script>";
        }
    } else {
        echo "<script>window.history.back()</script>";
    }
}

?>

                        </div>
                    </div>
                </div>
                <div class="col-8 col-xl-12">
                    <div class="box pd-0">

                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="box-body pl-15 pr-15 pb-20 pr-0" style="overflow-y: auto;">
                                        <h5 class="mb-10 mt-0 pt-20 font-w600 fs-18 line-h18">Children Enrollments</h5>

<?php
include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $father_name = $conn->real_escape_string($_POST['father_name']);
    $mother_name = $conn->real_escape_string($_POST['mother_name']);

    $query = "SELECT child_id FROM parental_information WHERE father_name = ? AND mother_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $father_name, $mother_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $child_ids = [];
        while ($row = $result->fetch_assoc()) {
            $child_ids[] = $row['child_id'];
        }

        $placeholders = implode(',', array_fill(0, count($child_ids), '?'));
        $query = "SELECT * FROM enrollment WHERE child_id IN ($placeholders)";
        $stmt = $conn->prepare($query);

        $stmt_params = [];
        foreach ($child_ids as $child_id) {
            $stmt_params[] = $child_id;
        }
        $stmt->bind_param(str_repeat('i', count($child_ids)), ...$stmt_params);
        $stmt->execute();
        $enrollment_result = $stmt->get_result();
    } else {
        $enrollment_result = [];
    }
} else {
    $enrollment_result = [];
}

?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">
    <thead>
        <tr class="top" style="background: #035392;color:white">
            <th class="border-bottom-0 sorting fs-14 font-w500">Reference</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Child Name</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Status</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <!-- <th class="border-bottom-0 sorting_disabled fs-14 font-w500">Action</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        if ($enrollment_result && $enrollment_result->num_rows > 0) {
            while ($enrollment_row = $enrollment_result->fetch_assoc()) {
                $reference = $enrollment_row['ref'];
                $child_name = $enrollment_row['child_name'];
                $status = $enrollment_row['enrollment_status'];
                $date = $enrollment_row['enrollment_date'];
        ?>
            <tr>
                <td>
    <form method="post" action="view_enrollment" style="display: inline;">
        <input type="hidden" name="ref" value="<?php echo urlencode($reference); ?>">
        <a href="javascript:void(0);" onclick="this.closest('form').submit();" class="text-decoration-none">
            <?php echo htmlspecialchars($reference); ?>
        </a>
    </form>
</td>
<td>
    <form method="post" action="view_enrollment" style="display: inline;">
        <input type="hidden" name="ref" value="<?php echo urlencode($reference); ?>">
        <a href="javascript:void(0);" onclick="this.closest('form').submit();" class="text-decoration-none">
            <?php echo htmlspecialchars($child_name); ?>
        </a>
    </form>
</td>
<td>
    <form method="post" action="view_enrollment" style="display: inline;">
        <input type="hidden" name="ref" value="<?php echo urlencode($reference); ?>">
        <a href="javascript:void(0);" onclick="this.closest('form').submit();" class="text-decoration-none">
            <?php echo htmlspecialchars($status); ?>
        </a>
    </form>
</td>
<td>
    <form method="post" action="view_enrollment" style="display: inline;">
        <input type="hidden" name="ref" value="<?php echo urlencode($reference); ?>">
        <a href="javascript:void(0);" onclick="this.closest('form').submit();" class="text-decoration-none">
            <?php echo htmlspecialchars($date); ?>
        </a>
    </form>
</td>

            </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="5">No data found.</td></tr>';
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
        </div>
    </div>
    <div class="overlay"></div>
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