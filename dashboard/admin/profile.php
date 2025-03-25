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
    SELECT user.username, user.email, user.password, teacher.*
    FROM user
    INNER JOIN teacher ON teacher.user_id = user.user_id
    WHERE user.user_id = $teacherUserId
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {

    $teacherData = mysqli_fetch_assoc($result);
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
                    
                    <form id="teacherForm" action="update_teacher.php" method="POST">
                        

                        <input name="teacher_id" value="<?php echo htmlspecialchars($teacherData['teacher_id']); ?>" hidden>
                         <input name="teacher_user_id" value="<?php echo htmlspecialchars($teacherUserId); ?>" hidden>

                        <div class="row">
                           
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="fullname"
                                           value="<?php echo htmlspecialchars($teacherData['fullname']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['fullname']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control" 
                                           type="email" 
                                           name="email_address"
                                           value="<?php echo htmlspecialchars($teacherData['email_address']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['email_address']); ?>">
                                </div>
                            </div>
                        </div>

                      
                        <div class="row">
                        
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input class="form-control" 
                                           type="date" 
                                           name="date_of_birth"
                                           value="<?php echo htmlspecialchars($teacherData['date_of_birth']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['date_of_birth']); ?>">
                                </div>
                            </div>
                          
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Place of Birth</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="place_of_birth"
                                           value="<?php echo htmlspecialchars($teacherData['place_of_birth']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['place_of_birth']); ?>">
                                </div>
                            </div>
                        </div>

                       
                        <div class="row">
                       
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Civil Status</label>
                                    <select class="form-control" name="civil_status">
                                        <option value="" disabled <?php echo !$teacherData['civil_status'] ? 'selected' : ''; ?>>Select Civil Status</option>
                                        <option value="single" <?php echo $teacherData['civil_status'] == 'single' ? 'selected' : ''; ?>>Single</option>
                                        <option value="married" <?php echo $teacherData['civil_status'] == 'married' ? 'selected' : ''; ?>>Married</option>
                                        <option value="widowed" <?php echo $teacherData['civil_status'] == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                                    </select>
                                </div>
                            </div>
                         
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="" disabled <?php echo !$teacherData['gender'] ? 'selected' : ''; ?>>Select Gender</option>
                                        <option value="male" <?php echo $teacherData['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="female" <?php echo $teacherData['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    
                        <div class="row">
                    
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="address"
                                           value="<?php echo htmlspecialchars($teacherData['address']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['address']); ?>">
                                </div>
                            </div>
                       
                            <div class="col-md-6 col-sm-12 mb-24">
                                <div class="form-group">
                                    <label class="form-label">Contact Number</label>
                                    <input class="form-control" 
                                           type="text" 
                                           name="contact"
                                           value="<?php echo htmlspecialchars($teacherData['contact']); ?>"
                                           placeholder="<?php echo htmlspecialchars($teacherData['contact']); ?>">
                                </div>
                            </div>
                        </div>


                        <p class="notice" style="font-size: 14px; color: #555; margin-top: 20px; padding: 10px; border: 1px solid #f0f0f0; background-color: #f9f9f9; border-radius: 5px;">
    <strong style="color: #EF5741">Notice:</strong> Your information, such as your name and contact details, may be shared across other pages on this platform (e.g., teacher and parent messages) to improve user experience. 
</p>

                        <div class="gr-btn mt-15" style="display: flex;justify-content: flex-end;">
                            <a href="changepass" class="btn btn-danger btn-lg" style="font-size: 12px;padding: 10px;">Change Password</a>
                            <button type="submit" class="btn btn-primary btn-lg" style="font-size: 12px;margin-top: 0px;margin-left: 20px;padding: 10px;">Save Changes</button>
                        </div>
                    </form>
                   
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
        $('#teacherForm').submit(function(e) {
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