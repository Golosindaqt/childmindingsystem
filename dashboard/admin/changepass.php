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
     <style>
      
      .input-container {
    position: relative;
    width: 100%;
}

.input-field {
    width: 100%;
    padding-right: 40px; 
    padding-left: 10px;
}

.margin-icon {
    position: absolute;
    right: 10px; 
    top: 50%;
    transform: translateY(-50%); 
    cursor: pointer;
    font-size: 18px;
}

   </style>
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
                    <!-- Hidden teacher ID -->
                    <input name="teacher_user_id" value="<?php echo $teacherUserId; ?>" hidden>

                    <!-- Bottom Row: Username and Password -->
                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 col-sm-12 mb-24">
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input class="form-control" 
                                       name="username"
                                       type="text" 
                                       value="<?php echo htmlspecialchars($teacherData['username']); ?>"
                                       placeholder="Username">
                            </div>
                        </div>
                        <!-- New Password -->
                        <div class="col-md-6 col-sm-12 mb-24">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <div class="input-container">
                                <input class="form-control input-field" 
                                       name="password"
                                       type="password" 
                                       id="newpasswordinput" 
                                       value="">
                                       <i class="bx bx-low-vision margin-icon" id="eyeSlashIcon"></i>
                                       <i class="bx bx-show margin-icon" id="eyeIcon" style="display:none;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="notice" style="font-size: 14px; color: #555; margin-top: 20px; padding: 10px; border: 1px solid #f0f0f0; background-color: #f9f9f9; border-radius: 5px;">
    <strong style="color: #EF5741">Notice:</strong> The information you provide here, such as your username and password, will only be used for logging into your account and will not be shared across other pages of the platform.
</p>

                    <!-- Submit Button -->
                    <div class="gr-btn mt-15" style="display: flex; justify-content: flex-end;">
                        <a href="profile" class="btn btn-danger btn-lg" style="font-size: 12px;">My Profile</a>
                        <button type="submit" id="saveChangesBtn" class="btn btn-primary btn-lg" style="font-size: 12px; margin-top: 0px; margin-left: 20px;">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
    <!-- END MAIN CONTENT -->

    <div class="overlay"></div>

<!-- Include jQuery (optional, you can use vanilla JS too) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    
const passwordInput = document.getElementById('newpasswordinput');
const eyeSlashIcon = document.getElementById('eyeSlashIcon');
const eyeIcon = document.getElementById('eyeIcon');

eyeSlashIcon.addEventListener('click', () => {
    passwordInput.type = 'text'; 

    eyeSlashIcon.style.display = 'none';
    eyeIcon.style.display = 'inline-block';
});

eyeIcon.addEventListener('click', () => {

    passwordInput.type = 'password'; 
    eyeIcon.style.display = 'none';
    eyeSlashIcon.style.display = 'inline-block';
});


    $(document).ready(function() {
        $('#saveChangesBtn').click(function(e) {
            e.preventDefault();  

            var formData = {
                teacher_user_id: $("input[name='teacher_user_id']").val(),
                username: $("input[name='username']").val(),
                password: $("input[name='password']").val(),
            };

            $.ajax({
                url: 'update_loginacc.php',  
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response);  
                    console.log(response);  
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while updating the data.');
                    console.log(xhr.responseText);  
                }
            });
        });
    });
</script>
    <!-- SCRIPT -->
    <!-- APEX CHART -->

  <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>
</body>

</html>