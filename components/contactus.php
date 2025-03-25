<?php
include 'db_conn.php';



$teacherquery = "SELECT * FROM teacher LIMIT 1";

$teacherresult = $conn->query($teacherquery);

if ($teacherresult->num_rows > 0) {
    $row = $teacherresult->fetch_assoc();
    $address = $row['address'];
    $contact = $row['contact'];
    $email_address = $row['email_address'];
} 



?>

<script type="text/javascript">
     function updateDateTime() {
            const now = new Date();
            const xx = 0 * 60 * 60 * 1000;
            const adjustedTime = new Date(now.getTime() + xx);
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            const datePart = adjustedTime.toLocaleDateString('en-PH', options);
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const timePart = adjustedTime.toLocaleTimeString('en-PH', timeOptions);
            const formattedDate = `${datePart} at ${timePart}`;
            document.getElementById('dateInput').value = formattedDate;
       
        }
    
        setInterval(updateDateTime, 1000);
        updateDateTime();
</script>

<section id="contact-home" class="container">
            <div class="row">
               <div class="col-lg-10 offset-lg-1 text-center">
                  <div class="section-heading text-center">
                     <h2>Contact Us</h2>
                     <p class="subtitle">Get in Touch</p>
                  </div>
                 <div class="contact-info res-margin">
    <div class="row res-margin">
        <div class="col-lg-4">
            <div class="contact-icon bg-secondary text-light">
                <i class="fa fa-envelope top-icon"></i>
                <div class="contact-icon-info">
                    <h5>Write us</h5>
                    <p><a href="mailto:<?php echo $email_address; ?>"><?php echo $email_address; ?></a></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 res-margin">
            <div class="contact-icon bg-secondary text-light">
                <i class="fa fa-map-marker top-icon"></i>
                <div class="contact-icon-info">
                    <h5>Visit us</h5>
                    <p><?php echo $address; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 res-margin">
            <div class="contact-icon bg-secondary text-light">
                <i class="fa fa-phone top-icon"></i>
                <div class="contact-icon-info">
                    <h5>Call us</h5>
                    <p><?php echo $contact; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
               </div>
               <div class="col-lg-12 mt-5 block-padding force notepad pl-5 pr-5" >
                  <div class="row">
                     <div class="col-lg-12">
                        <h4>Send us a message</h4>

                        <form id="contact_form" style="width: 100%;">
                           <div class="form-group">
                              <div class="row">
                                 <div class="col-md-6">
                                    <label>Name<span class="required">*</span></label>
                                    <input type="text" name="name" class="form-control input-field" required=""> 
                                 </div>
                                 <div class="col-md-6">
                                    <label>Email Address <span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control input-field" required=""> 
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <label>Subject</label>
                                    <input type="text" name="subject" class="form-control input-field"> 
                                 </div>
                                  <div class="col-md-6">
                                    <label>Phone Number</label>
                                    <input type="text" name="phonenumber" class="form-control input-field" id="phoneNumber" >
                                 </div>


                                 <div class="col-md-12" style="position: absolute;left: -500000000000px;">
                                    <label>Timestamp</label>
                                    <input type="text" name="created_at" id="dateInput" class="form-control input-field"> 
                                 </div>

                                 

                                 <div class="col-md-12">
                                    <label>Message<span class="required">*</span></label>
                                    <textarea name="message" id="message" class="textarea-field form-control" rows="3"  required=""></textarea>
                                 </div>
                              </div>
                              <button type="submit" id="submit_btn" value="Submit" class="btn btn-tertiary">Send message</button>
                           </div>
                        </form>


                     </div>
                     <div class="ornament-stars mt-8" data-aos="zoom-out"></div>
                  </div>
               </div>
            </div>
         </section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script type="text/javascript">

             document.getElementById('phoneNumber').addEventListener('input', function (e) {
       
        this.value = this.value.replace(/\D/g, '');
    });


          $(document).ready(function() {
   $('#contact_form').on('submit', function(event) {
      event.preventDefault();  // Prevent the default form submission

      var formData = $(this).serialize();  // Serialize the form data

      $.ajax({
         url: 'contact_submit.php',  // Replace with your PHP file's name
         type: 'POST',
         data: formData,
         dataType: 'json',
         success: function(response) {
            if (response.status === 'success') {
               alert(response.message);
               window.location.reload();
            } else {
               alert(response.message);
            }
         },
         error: function() {
            alert('There was an error submitting your message.');
         }
      });
   });
});

         </script>