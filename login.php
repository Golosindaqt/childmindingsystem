<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./components/head.php'); ?>

    <style type="text/css">
      

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
<body id="top">
    <?php include('./components/nav.php'); ?>
    <div id="page-wrapper">
        <div style="margin-bottom: 150px;">
            <section id="contact-home" class="container">
                <div class="row">

                    <div class="col-lg-10 offset-lg-1 text-center">
                        <div class="section-heading text-center">
                            <h2>Login</h2>
                            <p class="subtitle">Sign in to your account</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-0 block-padding notepad pl-5 pr-5" style="margin:auto;padding-bottom: 100px;">

                    <form id="login_form">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" id="username" name="username" class="form-control input-field" required>
                        </div>
                     <div class="form-group">
    <label for="password">Password</label>
    <div class="input-container">
        <input type="password" id="password" name="password" class="form-control input-field" required>
 
        <i class="fas fa-eye-slash toggle-password margin-icon" id="eyeSlashIcon"></i>
   
        <i class="fas fa-eye toggle-password margin-icon" id="eyeIcon" style="display:none;"></i>
    </div>
</div>




                        <button type="button" id="login_btn" style="float:right;margin-top: 30px;" class="btn btn-tertiary">Login</button>
                        <div id="contact_results" style="margin-top: 15px;"></div>

                        <div class="ornament-stars" data-aos="zoom-out" style="margin-top:150px"></div>
                    </form>
                </div>

            </section>
        </div>
        <?php include('./components/callout.php'); ?>
    </div>
    <?php include('./components/footer.php'); ?>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/plugins.js"></script>

    <script type="text/javascript">

 document.getElementById('eyeSlashIcon').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');
    const eyeIcon = document.getElementById('eyeIcon');

    passwordField.type = 'text'; 
    eyeSlashIcon.style.display = 'none';  
    eyeIcon.style.display = 'block';  
});

document.getElementById('eyeIcon').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');
    const eyeIcon = document.getElementById('eyeIcon');

    passwordField.type = 'password';  
    eyeSlashIcon.style.display = 'block';  
    eyeIcon.style.display = 'none';  
});


        $(document).ready(function() {
            // Real-time input border styling
            $('input').on('input', function() {
                $(this).css('border', this.value ? '2px solid #035392' : '');
            });

            $('#login_btn').click(function() {
                var username = $('#username').val();
                var password = $('#password').val();

                if (username && password) {
                    $.ajax({
                        url: 'login_process.php',
                        method: 'POST',
                        data: {
                            username: username,
                            password: password
                        },
                        success: function(response) {
                            if (response === 'success') {
                                window.location.href = 'dashboard/parent/appointments';
                            } else {
                                $('#contact_results').html('<p style="color:red;">' + response + '</p>');
                            }
                        },
                        error: function() {
                            $('#contact_results').html('<p style="color:red;">An error occurred. Please try again later.</p>');
                        }
                    });
                } else {
                    $('#contact_results').html('<p style="color:red;">Both fields are required.</p>');
                }
            });
        });
    </script>
</body>
</html>
