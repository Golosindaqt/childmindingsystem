<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

include('../../db_conn.php');

$query = "
   SELECT *
   FROM parental_information
   WHERE email = '$parentEmail'
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {

    $parentData = mysqli_fetch_assoc($result);
}

mysqli_close($conn);

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
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <!-- Start of Form -->
                    <form id="parentForm" action="update_parent.php" method="POST">
                        <!-- Parent ID (hidden) -->
                        <input name="parent_id" value="<?php echo htmlspecialchars($parentData['parent_id']); ?>" hidden>

                        <!-- Father's Information -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Father's Name</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="father_name"
                                           value="<?php echo htmlspecialchars($parentData['father_name']); ?>"
                                           placeholder="Enter father's name">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Father's Home Address</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="father_home_address"
                                           value="<?php echo htmlspecialchars($parentData['father_home_address']); ?>"
                                           placeholder="Enter father's home address">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Father's Phone Number</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="father_work_phone"
                                           value="<?php echo htmlspecialchars($parentData['father_work_phone']); ?>"
                                           placeholder="Enter father's phone number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Father's Employment</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="father_employment"
                                           value="<?php echo htmlspecialchars($parentData['father_employment']); ?>"
                                           placeholder="Enter father's employment">
                                </div>
                            </div>
                        </div>

                        <!-- Mother's Information -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Mother's Name</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="mother_name"
                                           value="<?php echo htmlspecialchars($parentData['mother_name']); ?>"
                                           placeholder="Enter mother's name">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Mother's Home Address</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="mother_home_address"
                                           value="<?php echo htmlspecialchars($parentData['mother_home_address']); ?>"
                                           placeholder="Enter mother's home address">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Mother's Phone Number</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="mother_work_phone"
                                           value="<?php echo htmlspecialchars($parentData['mother_work_phone']); ?>"
                                           placeholder="Enter mother's phone number">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Mother's Employment</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="mother_employment"
                                           value="<?php echo htmlspecialchars($parentData['mother_employment']); ?>"
                                           placeholder="Enter mother's employment">
                                </div>
                            </div>
                        </div>

                        <!-- Notice Section -->
                        <p class="notice" style="font-size: 14px; color: #555; margin-top: 20px; padding: 10px; border: 1px solid #f0f0f0; background-color: #f9f9f9; border-radius: 5px;">
    <strong style="color: #EF5741">Notice:</strong> The information you provide above pertains to the child's parent, not yourself. This data, such as the parent's name, contact details, and employment, may be shared across other pages on this platform (e.g., teacher and parent messages) to improve user experience.
</p>

                        <!-- Submit and Close Buttons -->
                        <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
                            <a href="changepass" class="btn btn-danger btn-lg" style="font-size: 12px;padding: 10px;">Change Password</a>
                            <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px; margin-top: 0px; margin-left: 20px;padding: 10px;">Save Changes</button>
                        </div>
                    </form>
                    <!-- End of Form -->
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
        $('#parentForm').submit(function(e) {
            e.preventDefault();  

            var formData = $(this).serialize();  

            $.ajax({
                url: $(this).attr('action'),  
                type: 'POST',
                data: formData,
                success: function(response) {

                    alert('Changes saved successfully!');
                    console.log(response);  
                },
                error: function(xhr, status, error) {

                    alert('An error occurred while saving changes!');
                    console.log(xhr.responseText);  
                }
            });
        });
    });
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