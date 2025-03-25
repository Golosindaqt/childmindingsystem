<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
   <title>USTP - Child Minding and GAD Resource Center</title>
   <link rel="apple-touch-icon" sizes="57x57" href="img/favicon.png">
   <link rel="apple-touch-icon" sizes="72x72" href="img/favicon.png">
   <link rel="apple-touch-icon" sizes="114x114" href="img/favicon.png">
   <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700%7CNunito:400,700,900" rel="stylesheet">
   <link href="fonts/flaticon/flaticon.css" rel="stylesheet" type="text/css">
   <link href="fonts/fontawesome/fontawesome-all.min.css" rel="stylesheet" type="text/css">
   <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">
   <link href="css/plugins.css" rel="stylesheet">
   <link href="css/maincolors.css" rel="stylesheet">
   <link rel="stylesheet" href="vendor/layerslider/css/layerslider.css">


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
<body id="top" style="background: #035392;">
   <div id="page-wrapper">
      <div style="margin-bottom: 150px;">
         <section id="contact-home" class="container" style="padding-top: 100px;">
            <div class="row">
               <div class="col-lg-10 offset-lg-1 text-center">
                  <div class="section-heading text-center">
                     <h2 style="color:white;">Account Recovery</h2>
                     <p class="subtitle">Let's find your account</p>
                  </div>
               </div>
            </div>
            <div class="col-lg-6 mt-0 block-padding notepad pl-5 pr-5" style="margin:auto;padding-bottom: 100px;">
               <form id="recovery_form">
                  <div class="form-group">
                     <label>Email</label>
                     <input type="email" id="email" name="email" class="form-control input-field" required>
                  </div>

                  <div class="form-group" style="display: none;" id="digitcode">
                     <label>Account Recovery - 6-Digit Code</label>
                     <input type="text" id="digitcodeinput" class="form-control input-field">
                  </div>

                  <div class="form-group" style="display: none;" id="username">
                     <label>Username</label>
                     <input style="border: 2px solid #035392" type="text" id="usernameinput"  class="form-control input-field" readonly>
                  </div>

                 

                  <div class="form-group" style="display: none;" id="newpassword">
    <label for="password">New Password</label>
    <div class="input-container">
        <input type="password" name="newpassword" id="newpasswordinput" class="form-control input-field" required>
      
        <i class="fas fa-eye-slash toggle-password margin-icon" id="eyeSlashIcon"></i>
       
        <i class="fas fa-eye toggle-password margin-icon" id="eyeIcon" style="display:none;"></i>
    </div>
</div>

                  <button type="button" id="continue_btn" style="float:right;margin-top: 30px;" class="btn btn-tertiary">Search</button>
               </form>
            </div>
         </section>
      </div>
   </div>

   <script src="vendor/jquery/jquery.min.js"></script>
   <script src="js/custom.js"></script>
   <script src="js/plugins.js"></script>

   <script type="text/javascript">
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

         $('input').on('input', function() {
            $(this).css('border', this.value ? '2px solid #035392' : '');
         });

         var recoverycode;

         $('#continue_btn').click(function() {
            var email = $('#email').val();

            if (email && email.includes('@')) {

               $('#continue_btn').prop('disabled', true).text('Searching...');

               $.ajax({
                  url: 'recovery_sendcode.php', 
                  method: 'POST',
                  data: { email: email },
                  success: function(response) {


                     var data = JSON.parse(response); 
                     if (data.status === 'success') {

                        recoverycode = data.code;

                        localStorage.setItem('recoveryEmail', data.email);

                        document.getElementById('usernameinput').value = data.username;

                        $('#email').val(data.email).prop('readonly', true);

                        $('#digitcode').show();
                        $('#continue_btn').hide();

                        $('#continue_btn').text('Verify Code');

                        alert("A 6-digit recovery code has been sent to your email.");
                     } else {
                        alert(data.message); 

                        $('#continue_btn').prop('disabled', false).text('Search');
                     }
                  },
                  error: function() {
                     alert('An error occurred. Please try again later.');
                  }
               });
            } else {
               alert('Please enter a valid email.');
            }
         });

         $('#digitcodeinput').on('input', function() {
            var digitcodeValue = $(this).val();

            if (digitcodeValue == recoverycode) {

               $('#digitcodeinput').prop('readonly', true);
               $('#newpassword').show();
               $('#username').show();
                $('#continue_btn').show();
               $('#continue_btn').prop('disabled', false).text('Reset Password').off('click').on('click', function() {
                  var newPassword = $('#newpasswordinput').val();
                  if (newPassword) {
                     $.ajax({
                        url: 'recovery_resetpass.php', 
                        method: 'POST',
                        data: { email: localStorage.getItem('recoveryEmail'), password: newPassword },
                        success: function(response) {
                           var data = JSON.parse(response);
                           if (data.status === 'success') {
                              alert('Your password has been successfully reset!');
                              localStorage.clear();
                              window.location.reload();
                           } else {
                              alert(data.message);
                           }
                        },
                        error: function() {
                           alert('An error occurred while resetting your password.');
                        }
                     });
                  } else {
                     alert('Please enter a new password.');
                  }
               });
            } else {

                    $('#continue_btn').hide();
            }
         });
      });
   </script>
</body>
</html>