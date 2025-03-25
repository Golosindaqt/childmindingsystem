<?php
include 'db_conn.php';

$sql = "SELECT fullname FROM teacher LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $teacherfullname = $row['fullname'];
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <script>
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
            generateReferenceInput(now);
        }
        function generateReferenceInput(date) {
            const month = date.getMonth() + 1; 
            const day = date.getDate();
            const randomNumber = Math.floor(Math.random() * 5281) + 1; 
            const referenceString = `USTP${month}${day}${randomNumber}CMC`;
            document.getElementById('referenceInput').value = referenceString;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
    <?php include('./components/head.php'); ?>
    <style type="text/css">
       input:not([type="checkbox"]), select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        select { height: 48px !important; }
        .required { color:#E8373D; padding-left:3px }
        h6 { text-align:center; }
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
}
    </style>
</head>
<body id="top">
    <?php include('./components/nav.php'); ?>
    <div id="page-wrapper">
        <section id="contact-home" class="container">
            <form action="confirmation" id="myForm" method="post" enctype="multipart/form-data" style="width: 100%;" autocomplete="off">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 text-center">
                        <div class="section-heading">
                            <h2>Child Enrollment</h2>
                            <p class="subtitle">Quality care and learning.</p>
                        </div>
                    </div>

                     
                    

                    


                    <div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container1">
                        <h4 style="text-align: center; font-weight: 800;">Step 1 out of 6</h4>


                            <div class="form-group row">
                                    <input type="hidden" id="dateInput" name="currentDate" class="form-control" readonly>
                                    <input type="hidden" id="referenceInput" name="referenceInput" class="form-control" readonly>
                                <div class="col-md-4">
                                    <label>Child Name<span class="required">*</span></label>
                                    <input type="text" name="child_name" id="child_name" class="form-control" required >
                                </div>
                                <div class="col-md-4">
                                    <label>Home Address<span class="required">*</span></label>
                                    <input type="text" name="address" class="form-control" required >
                                </div>
                                <div class="col-md-4">
                                    <label>Home Phone Number<span class="required">*</span></label>
                                    <input type="text" name="home_phone" class=" form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Gender<span class="required">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="" selected hidden>Please select..</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Birthdate<span class="required">*</span></label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required onchange="calculateAge()">
                                </div>
                                <div class="col-md-4">
                                    <label>Age<span class="required">*</span></label>
                                    <input type="text" name="child_age" class="form-control" readonly style="background: white;" onclick="alert('Please use the birthdate to calculate the age.')" onfocus="this.blur();">
                                </div>
                                <div class="col-md-12">
                                    <label>Place of Birth<span class="required">*</span></label>
                                    <input type="text" name="place_of_birth" class="form-control" required>
                                </div>

                                 <div class="col-md-6">
                                    <label>Medical Condition<span class="required">*</span></label>
                                    <input type="text" name="medical_condition" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Allergies<span class="required">*</span></label>
                                    <input type="text" name="allergies" class="form-control" required>
                                </div>
                            </div>
                             <div class="form-group row">
                                <div class="col-md-6">
                                    <label>Father's Name<span class="required">*</span></label>
                                    <input type="text" name="father_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Father's Home Address<span class="required">*</span></label>
                                    <input type="text" name="father_address" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Father's Place of Employment<span class="required">*</span></label>
                                    <input type="text" name="father_employment" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Father's Work Phone Number<span class="required">*</span></label>
                                    <input type="text" name="father_work_phone" class=" form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>Mother's Name<span class="required">*</span></label>
    <input type="text" name="mother_name" class="form-control" required>
                                </div>



                                <div class="col-md-6">
                                     <label>Mother's Home Address<span class="required">*</span></label>
    <input type="text" name="mother_address" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                        <label>Mother's Place of Employment<span class="required">*</span></label>
    <input type="text" name="mother_employment" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                     <label>Mother's Work Phone Number<span class="required">*</span></label>
    <input type="text" name="mother_work_phone" class=" form-control" required>
                                </div>
                            </div>
                             <div class="form-group row">
                                 <div class="col-md-6">
                                         <label>Child's Living Arrangements<span class="required">*</span></label>
    <select name="child_living_arrangements" class="form-control" required>
      <option value="" selected hidden>Please select..</option>
        <option value="Both Parents">Both Parents</option>
        <option value="Mother">Mother</option>
        <option value="Father">Father</option>
        <option value="Other">Other</option>
    </select>
 </div>
 <div class="col-md-6">
        <label>Child's Legal Guardian(s)<span class="required">*</span></label>
    <select name="child_legal_guardians" class="form-control" required>
      <option value="" selected hidden>Please select..</option>
        <option value="Both Parents">Both Parents</option>
        <option value="Mother">Mother</option>
        <option value="Father">Father</option>
        <option value="Other">Other</option>
    </select>
 </div>
</div>
                        <div style="display: flex; justify-content: flex-end;">
    <button type="button" class="btn btn-primary" onclick="validateForm('container1')">Continue</button>
</div>
                    </div>
                    <div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container2" style="display: none;">

                        <h4 style="text-align: center; font-weight: 800;">Step 2 out of 6</h4>

                     <h6>The child may be released to the person(s) signing this agreement or to the following:</h6>
                        <div class="form-group row">
    <div class="col-md-6">
        <label>Name<span class="required">*</span></label>
        <input type="text" name="released_name1" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Address<span class="required">*</span></label>
        <input type="text" name="released_address1" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Mobile Number<span class="required">*</span></label>
        <input type="text" name="released_number1" class=" form-control" required>
    </div>
    <div class="col-md-6">
        <label>Relationship to the child<span class="required">*</span></label>
        <select name="released_relationtochild1" class="form-control" required>
           <option value="" selected hidden>Please select..</option>
            <option value="Parent">Parent</option>
            <option value="Step Parent">Step Parent</option>
            <option value="Guardian">Guardian</option>
            <option value="Foster Parent">Foster Parent</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Relationship to the parent(s) or Guardian<span class="required">*</span></label>
        <select name="released_relationtoparent1" class="form-control" required>
            <option value="" selected hidden>Please select..</option>
           <option value="Parent">Parent</option>
    <option value="Guardian">Guardian</option>
    <option value="Stepparent">Stepparent</option>
    <option value="Foster Parent">Foster Parent</option>
    <option value="Other Relative">Other Relative</option>
    <option value="Other">Other</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Employment Status<span class="required">*</span></label>
        <select name="released_status" class="form-control" required>
            <option value="" selected hidden>Please select..</option>
            <option value="Job Order (JO)">Job Order (JO)</option>
            <option value="Contract of Service (COS)">Contract of Service (COS)</option>
            <option value="Regular (Teaching)">Regular (Teaching)</option>
            <option value="Regular (Non-Teaching)">Regular (Non-Teaching)</option>
            <option value="Student">Student</option>
        </select>
    </div>
</div>
<br>
<div class="form-group row">
    <div class="col-md-6">
        <label>Name<span class="required"></span></label>
        <input type="text" name="released_name2" class="form-control" >
    </div>
    <div class="col-md-6">
        <label>Address<span class="required"></span></label>
        <input type="text" name="released_address2" class="form-control" >
    </div>
    <div class="col-md-6">
        <label>Mobile Number<span class="required"></span></label>
        <input type="text" name="released_number2" class=" form-control" >
    </div>
    <div class="col-md-6">
        <label>Relationship to the child<span class="required"></span></label>
        <select name="released_relationtochild2" class="form-control" >
            <option value="" selected hidden>Please select..</option>
            <option value="Parent">Parent</option>
            <option value="Step Parent">Step Parent</option>
            <option value="Guardian">Guardian</option>
            <option value="Foster Parent">Foster Parent</option>

        </select>
    </div>
    <div class="col-md-6">
        <label>Relationship to the parent(s) or Guardian<span class="required"></span></label>
        <select name="released_relationtoparent2" class="form-control" >
         <option value="" selected hidden>Please select..</option>
            <option value="Parent">Parent</option>
    <option value="Guardian">Guardian</option>
    <option value="Stepparent">Stepparent</option>
    <option value="Foster Parent">Foster Parent</option>
    <option value="Other Relative">Other Relative</option>
    <option value="Other">Other</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Other identifying information (if any):</label>
        <input type="text" name="released_other" class="form-control" >
    </div>
</div>
                        <div style="display: flex;justify-content: space-between;">
                            <button type="button" class="btn btn-tertiary" onclick="goBack()">Back</button>
                            <button type="button" class="btn btn-primary" onclick="validateForm('container2')">Continue</button>
                        </div>
                    </div>
                    <div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container3" style="display: none;">
                              <h4 style="text-align: center; font-weight: 800;">Step 3 out of 6</h4>
                     <h6>Persons to contact in the case of emergency when parent or guardian cannot be reached: </h6>
                        <div class="form-group row">
    <div class="col-md-6">
        <label>Emergency Name<span class="required">*</span></label>
        <input type="text" name="emergencyname_1" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Emergency Number<span class="required">*</span></label>
        <input type="text" name="emergencynum_1" class=" form-control" required>
    </div>
    <div class="col-md-6">
        <label>Emergency Name</label>
        <input type="text" name="emergencyname_2" class="form-control" >
    </div>
    <div class="col-md-6">
        <label>Emergency Number</label>
        <input type="text" name="emergencynum_2" class=" form-control" >
    </div>
    <div class="col-md-6">
        <label>Emergency Name</label>
        <input type="text" name="emergencyname_3" class="form-control" >
    </div>
    <div class="col-md-6">
        <label>Emergency Number</label>
        <input type="text" name="emergencynum_3" class=" form-control" >
    </div>
    <div class="col-md-12">
        <label>Name of Public School child attends, if any:</label>
        <input type="text" name="emergencyschool" class="form-control">
    </div>
</div>
                        <div style="display: flex;justify-content: space-between;">
                            <button type="button" class="btn btn-tertiary" onclick="goBack()">Back</button>
                            <button type="button" class="btn btn-primary" onclick="validateForm('container3')">Continue</button>
                        </div>
                    </div>



                    <div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container4" style="display: none;">
                        <h4 style="text-align: center; font-weight: 800;">Step 4 out of 6</h4>
                     <h6>Emergency Medical Authorization </h6>
                     <p>Should <strong id="child_nameOutput"></strong> Date of Birth <strong id="bdateOutput"></strong>
Suffer an injury or illness while in the Child Minding Center and the facility is unable to contact me immediately, it shall be authorized to secure such medical attention and care for the child as may be necessary. I shall assume responsibility for payment for services.</p>
                    <div class="form-group row">
    <div class="col-md-6">
        <label>Parent/Guardian<span class="required">*</span></label>
        <input type="text" name="emergencymid_parent" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Date<span class="required">*</span></label>
        <input type="date" name="emergencymid_parentdate" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly style="border: 2px solid #035392">
    </div>
      <div class="col-md-6">
        <label>Facility Administration/Person-in-Charge<span class="required">*</span></label>
        <input type="text" name="emergencymid_facilityadmin" class="form-control" value="<?php echo htmlspecialchars($teacherfullname); ?>" style="border: 2px solid #035392" readonly>
    </div>
    <div class="col-md-6">
        <label>Date<span class="required">*</span></label>
        <input type="date" name="emergencymid_facilityadmindate" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly style="border: 2px solid #035392">
    </div>
 </div>
                        <div style="display: flex;justify-content: space-between;">
                            <button type="button" class="btn btn-tertiary" onclick="goBack()">Back</button>
                            <button type="button" class="btn btn-primary" onclick="validateForm('container4')">Continue</button>
                        </div>
                    </div>






                   










                    <div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container5"  style="display: none;">
                        <h4 style="text-align: center; font-weight: 800;">Step 5 out of 6</h4>
    <h6>Parental Agreements with Child Minding Facility</h6>
    <div style="width: 100%; margin-top: 20px; display: flex;justify-content: center;flex-wrap: wrap;">The <input type="text" name="parental_agreement_facility_name" style="border: 2px solid #035392; text-align: center; width: 100%; max-width: 300px; margin:0px 10px" placeholder="Facility Name" id="fac1" value="
            <?php echo htmlspecialchars($teacherfullname); ?>" readonly> agree to provide child care for <input type="text" name="parental_agreement_child_name" id="parental_agreement_child_name" readonly style="border: 2px solid #035392; text-align: center; width: 100%; max-width: 300px; margin:0px 10px" placeholder="Child Name" readonly>
    </div>
    <div style="width: 100%; margin-top: 20px; display: flex;justify-content: center;flex-wrap: wrap;"> On <input type="number" name="parental_agreement_days_of_week" style="border: 2px solid #E4A40F; text-align: center; width: 100%; max-width: 300px;margin:0px 10px" placeholder="Days" > times a week
      <input type="text" name="parental_agreement_start_time" style="border: 2px solid #E4A40F; text-align: center;width: 100%; max-width: 150px;margin-right: 10px;margin-left: 10px;" id="parental_agreement_start_time" value="9:00am - 11:30am" readonly> to <input type="text" name="parental_agreement_end_time" style="border: 2px solid #E4A40F; text-align: center;width: 100%; max-width: 150px;margin:0px 0px;margin-left: 10px;" id="parental_agreement_end_time" value="1:00pm - 4:30pm" readonly>.
    </div>
    <div style="width: 100%; margin-top: 20px; display: flex;justify-content: center;flex-wrap: wrap;"> From <input type="month" id="startMonth" name="parental_agreement_start_month" style="border: 2px solid #E4A40F; text-align: center;width: 100%; max-width: 150px;height: 30px; margin:0px 10px" > to <input type="month" id="endMonth" name="parental_agreement_end_month" style="border: 2px solid #E4A40F; text-align: center;width: 100%; max-width: 150px;height: 30px; margin:0px 10px" >
    </div>
    <br>
    <br>
    <p style="font-weight: bold;">My child will not be allowed to enter or leave the facility without being escorted by the parent(s), person authorized by parent(s), or facility personnel. <br>
      <br> I acknowledge it is my responsibility to keep my child’s records current to reflect any significant changes as they occur, e.g., telephones numbers, work location, emergency contacts etc. <br>
      <br> The facility agrees to keep me informed of any incidents, including illnesses, injuries, etc. which include my child. <br>
      <br> When my child is ill, I understand and agree that s/he may not be accepted for care or remain in care. <br>
      <br> I have received a copy and agree to abide by the policies and procedures for <?php echo htmlspecialchars($teacherfullname); ?> <br>
      <br> I understand that the facility will advise me of my child’s progress and issues relating to my child’s care as well as any individual practices concerning my child’s special needs. I also understand that my participation is encourage in facility activities.
    </p>


                    <div class="form-group row">
    <div class="col-md-6">
        <label>Parent/Guardian<span class="required">*</span></label>
        <input type="text" name="parental_agreement_parent" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Date<span class="required">*</span></label>
        <input type="date" name="parental_agreement_parentdate" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly style="border: 2px solid #035392">
    </div>
      <div class="col-md-6">
        <label>Facility Administration/Person-in-Charge<span class="required">*</span></label>
        <input type="text" name="parental_agreement_facilityadmin" class="form-control" value="<?php echo htmlspecialchars($teacherfullname); ?>" style="border: 2px solid #035392" readonly>
    </div>
    <div class="col-md-6">
        <label>Date<span class="required">*</span></label>
        <input type="date" name="parental_agreement_facilityadmindate" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly style="border: 2px solid #035392">
    </div>
 </div>
                        <div style="display: flex;justify-content: space-between;">
                            <button type="button" class="btn btn-tertiary" onclick="goBack()">Back</button>
                            <button type="button" class="btn btn-primary" onclick="validateForm('container5')">Continue</button>
                        </div>
                    </div>


<div class="col-lg-12 block-padding force notepad pl-5 pr-5" id="container6" style="display: none;">
    <h4 style="text-align: center; font-weight: 800;">Step 6 out of 6</h4>
                     <h6>Enrollment Requirements</h6>

                      <p style="font-weight:400;font-size: 20px;margin-top:-13px;text-align: center;">Please make sure your picture is clear, accurate, and not blurred. Any photo that does not meet these standards will be rejected, and you will need to re-enroll.</p>
                    <div class="form-group row">
    <div class="col-md-6">
        <label>Upload 2X2 picture<span class="required">*</span></label>
        <input type="file" name="upload_2x2" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Upload Birth Certificate<span class="required">*</span></label>
        <input type="file" name="upload_birth" class="form-control" required>
    </div>
      <div class="col-md-6">
        <label>Upload Parent ID<span class="required">*</span></label>
        <input type="file" name="upload_parentID" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Upload COR(Certificate of Registration) for student only</label>
        <input type="file" name="upload_cor" class="form-control" >
    </div>
 </div>

<div class="form-group row">
 <div class="col-md-12">
        <label>Active Email<span class="required">*</span></label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
</div>
<div id="displaymsgemail" style="color: red;font-weight: bold;"></div>




<div style="display:flex;">
<input type="checkbox" id="terms" style="margin-right: 10px;" required>
<label for="terms">I acknowledge and accept the <a href="termsandcondition" target="_blank" style="color:#035392;font-weight: bold;">terms and conditions</a> to ensure the well-being of all children.</label>
</div>
                        <div style="display: flex;justify-content: space-between;">
                            <button type="button" class="btn btn-tertiary" onclick="goBack()">Back</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                        </div>
                    </div>
























                </div>
            </form>
        </section>


        <script>

            document.getElementById('myForm').addEventListener('submit', function() {
    document.getElementById('submitBtn').innerText = 'Processing...';
    document.getElementById('submitBtn').disabled = true;
});


          document.addEventListener('DOMContentLoaded', function() {
    var emailField = document.getElementById('email');
    var displayMsgEmail = document.getElementById('displaymsgemail');
    var submitBtn = document.getElementById('submitbtn');

    emailField.addEventListener('input', function() {
        var email = emailField.value;
        
        if (email !== "") {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_email.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.found) {
                            displayMsgEmail.textContent = `${response.email} is already registered. Please use your account to process new enrollment.`;
                            displayMsgEmail.style.borderColor = "red";
                            submitBtn.style.display = 'none';
                        } else {
                            displayMsgEmail.textContent = '';
                            submitBtn.style.display = 'inline-block';
                        }
                    } catch (e) {
                        displayMsgEmail.textContent = '';
                    }
                } else if (xhr.readyState === 4) {
                    displayMsgEmail.textContent = '';
                }
            };
            xhr.send('email=' + encodeURIComponent(email));
        } else {
            displayMsgEmail.textContent = '';
            submitBtn.style.display = 'inline-block';
        }
    });
});



    


        const child_nameinput = document.getElementById('child_name');
        const bdate_input = document.getElementById('date_of_birth');
        const child_nameOutputDiv = document.getElementById('child_nameOutput');
        const bdateOutputDiv = document.getElementById('bdateOutput');
        const parental_agreement_child_name = document.getElementById('parental_agreement_child_name');
        function formatDate(dateString) {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        return '';
    }
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function updateOutputs() {
    const nameValue = child_nameinput.value;
    const bdateValue = bdate_input.value;
    parental_agreement_child_name.value = nameValue;
    child_nameOutputDiv.textContent = `${nameValue}`;
    const formattedBdate = formatDate(bdateValue);
    bdateOutputDiv.textContent = formattedBdate;
}

setInterval(updateOutputs, 1000);
         function restrictInput() {
            const inputs = document.querySelectorAll('.');
            inputs.forEach(input => {
                input.addEventListener('input', (event) => {
                    input.value = input.value.replace(/[^0-9]/g, '');
                });
            });
        }
        document.addEventListener('DOMContentLoaded', restrictInput);
         const startMonth = document.getElementById('startMonth');
    const endMonth = document.getElementById('endMonth');
    startMonth.addEventListener('change', validateMonths);
    endMonth.addEventListener('change', validateMonths);
    function validateMonths() {
        if (startMonth.value && endMonth.value) {
            if (startMonth.value > endMonth.value) {
                alert('The start month must be before or the same as the end month.');
                endMonth.value = ''; 
            }
        }
    }
        function checkTime(expectedPeriod) {
    const timeInput = expectedPeriod === 'AM' ? document.getElementById('parental_agreement_start_time') : document.getElementById('parental_agreement_end_time');
    const timeValue = timeInput.value;

    if (timeValue) {
        const [hours, minutes] = timeValue.split(':').map(Number);
        const isAM = hours < 12;
        const isValidPeriod = (expectedPeriod === 'AM' && isAM) || (expectedPeriod === 'PM' && !isAM);

        if (!isValidPeriod) {
            alert(`Please select a time in the ${expectedPeriod}.`);
            timeInput.value = ""; 
            return;
        }

        const totalMinutes = hours * 60 + minutes;

        if (expectedPeriod === 'AM') {
            if (totalMinutes < (9 * 60) || totalMinutes > (11 * 60 + 30)) {
                alert("For AM, please select a time between 9:00 AM and 11:30 AM.");
                timeInput.value = "";
            }
        } else if (expectedPeriod === 'PM') {
            if (totalMinutes < (13 * 60) || totalMinutes > (16 * 60 + 30)) {
                alert("For PM, please select a time between 1:00 PM and 4:30 PM.");
                timeInput.value = "";
            }
        }
    }
}


         function getVal(inputId, divId) {
            const inputValue = document.getElementById(inputId).value;
            document.getElementById(divId).innerText = inputValue;
        }
         function calculateAge() {
    const birthdateInput = document.querySelector('input[name="date_of_birth"]');
    const ageInput = document.querySelector('input[name="child_age"]');
    const birthdate = new Date(birthdateInput.value);
    const today = new Date();
    if (birthdateInput.value) {
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        if (age < 3) {
            alert("The age must be at least 3 years old.");
            ageInput.value = ''; 
            birthdateInput.value = ''; 
        } else {
            ageInput.value = age + ' years old';
        }
    } else {
        ageInput.value = ''; 
    }
}
            function checkInputs() {
                const containers = document.querySelectorAll('.form-container');
                containers.forEach(container => {
                    const inputs = container.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        if (input.value) {
                            input.style.border = '2px solid #035392';
                        } else {
                            input.style.border = '';
                        }
                    });
                });
            }
            setInterval(checkInputs, 10);
            document.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        this.style.border = '2px solid #035392';
                    } else {
                        this.style.border = '';
                    }
                });
            });
            function validateForm(containerId) {
                const container = document.getElementById(containerId);
                const inputs = container.querySelectorAll('input[required], select[required]');
                let isValid = true;
                inputs.forEach(input => {
                    if (!input.value) {
                        isValid = false;
                        input.style.border = '2px solid #E8373D';
                    } else {
                        input.style.border = '2px solid #035392';
                    }
                });
                if (!isValid) {
                    alert('Please fill in all required fields.');
                } else {
                    container.style.display = 'none';
                    const nextContainer = container.nextElementSibling;
                    if (nextContainer) {
                        nextContainer.style.display = 'block';
                    }
                }
                 smoothScrollToTop(2000);
            }
            function smoothScrollToTop(duration) {
    const start = window.scrollY || window.pageYOffset;
    const startTime = performance.now();
    function scroll(currentTime) {
        const timeElapsed = currentTime - startTime;
        const progress = Math.min(timeElapsed / duration, 1); 
        const easeInOut = progress < 0.5 ? 4 * progress * progress * progress : 1 - Math.pow(-2 * progress + 2, 3) / 2; 
        window.scrollTo(0, start * (1 - easeInOut));
        if (timeElapsed < duration) {
            requestAnimationFrame(scroll);
        }
    }
    requestAnimationFrame(scroll);
}
            function goBack() {
                const currentContainer = document.querySelector('.block-padding:not([style*="display: none"])');
                if (currentContainer) {
                    currentContainer.style.display = 'none';
                    const previousContainer = currentContainer.previousElementSibling;
                    if (previousContainer) {
                        previousContainer.style.display = 'block';
                    }
                }
            }
        </script>
    </div>
    <?php include('./components/footer.php'); ?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/counter.js"></script>
    <script src="vendor/layerslider/js/greensock.js"></script>
    <script src="vendor/layerslider/js/layerslider.transitions.js"></script>
    <script src="vendor/layerslider/js/layerslider.kreaturamedia.jquery.js"></script>
    <script src="vendor/layerslider/js/layerslider.load.js"></script>
</body>
</html>
