<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; 
include 'db_conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fields = [
         'medical_condition',
        'child_name', 'address', 'home_phone', 'gender', 'date_of_birth', 'child_age', 
        'place_of_birth', 'allergies', 'father_name', 'father_address', 
        'father_employment', 'father_work_phone', 'mother_name', 'mother_address', 
        'mother_employment', 'mother_work_phone', 'child_living_arrangements', 
        'child_legal_guardians', 'released_name1', 'released_address1', 'released_number1', 
        'released_relationtochild1', 'released_relationtoparent1', 'released_status', 
        'released_name2', 'released_address2', 'released_number2', 'released_relationtochild2', 
        'released_relationtoparent2', 'released_other', 'emergencyname_1', 'emergencynum_1', 
        'emergencyname_2', 'emergencynum_2', 'emergencyname_3', 'emergencynum_3', 
        'emergencyschool', 'emergencymid_parent', 'emergencymid_parentdate', 'emergencymid_facilityadmin', 
        'emergencymid_facilityadmindate', 'parental_agreement_facility_name', 'parental_agreement_child_name', 
        'parental_agreement_days_of_week', 'parental_agreement_start_time', 'parental_agreement_end_time', 
        'parental_agreement_start_month', 'parental_agreement_end_month', 'parental_agreement_parent', 
        'parental_agreement_parentdate', 'parental_agreement_facilityadmin', 'parental_agreement_facilityadmindate', 
        'username', 'password', 'email', 'currentDate', 'referenceInput'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = mysqli_real_escape_string($conn, $_POST[$field] ?? '');
    }

    $uploadDir = 'uploads/';
    $uploadedFiles = [
        '2x2' => handleFileUpload($_FILES['upload_2x2'] ?? null, $uploadDir),
        'birth' => handleFileUpload($_FILES['upload_birth'] ?? null, $uploadDir),
        'parentID' => handleFileUpload($_FILES['upload_parentID'] ?? null, $uploadDir),
        'cor' => handleFileUpload($_FILES['upload_cor'] ?? null, $uploadDir),
    ];

      $referenceInput = $data['referenceInput'];
    $queryref = "SELECT COUNT(*) FROM enrollment WHERE ref = '$referenceInput'";
    $resultref = mysqli_query($conn, $queryref);
    if ($resultref) {
        $count = mysqli_fetch_array($resultref)[0];
        if ($count > 0) {
            echo '<script>
                alert("Your enrollment application is already exists. Kindly make a new one.");
                window.history.back(); 
            </script>';
            exit;
        }
    } 

    $email = $data['email'];

    $sql_check_email = "SELECT 1 FROM user WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql_check_email);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $resultemail = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultemail) === 0) {

        $role_id = 2;
        $sql_insert_user = "INSERT INTO user (role_id, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql_insert_user);
        mysqli_stmt_bind_param($stmt, 'isss', $role_id, $data['username'], $data['password'], $data['email']);
        if (mysqli_stmt_execute($stmt)) {

            $user_id = mysqli_insert_id($conn);
     
        } else {
            echo "Error inserting user: " . mysqli_error($conn);
        }
    } else {

        $sql_get_user_id = "SELECT user_id FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql_get_user_id);
        mysqli_stmt_bind_param($stmt, 's', $data['email']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['user_id'];
    }

    $sql_child = "INSERT INTO child_record (child_name, child_age, gender, date_of_birth, address, place_of_birth, allergies, user_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql_child);
    mysqli_stmt_bind_param($stmt, 'sssssssi', $data['child_name'], $data['child_age'], $data['gender'], 
                           $data['date_of_birth'], $data['address'], $data['place_of_birth'], $data['allergies'], $user_id);
    if ($stmt->execute()) {
        $child_id = $conn->insert_id; 

         $sql_parental = "INSERT INTO parental_information (
                child_id, email, home_address, home_phone, father_name, father_home_address,
                father_employment, father_work_phone, mother_name, mother_home_address,
                mother_employment, mother_work_phone, child_living_arrangements, 
                child_legal_guardians, released_name1, released_address1, released_number1, 
                released_relationtochild1, released_relationtoparent1, released_status, 
                released_name2, released_address2, released_number2, released_relationtochild2, 
                released_relationtoparent2, released_other, emergencyname_1, 
                emergencyname_2, emergencyname_3, emergencynum_1, emergencynum_2, 
                emergencynum_3, emergencyschool, emergencymid_parent, 
                emergencymid_parentdate, emergencymid_facilityadmin, 
                emergencymid_facilityadmindate, parental_agreement_facility_name, 
                parental_agreement_child_name, parental_agreement_days_of_week, 
                parental_agreement_start_time, parental_agreement_end_time, 
                parental_agreement_start_month, parental_agreement_end_month, 
                parental_agreement_parent, parental_agreement_parentdate, 
                parental_agreement_facilityadmin, parental_agreement_facilityadmindate, 
                  medical_condition,
                upload_2x2, upload_birth, upload_parentID, upload_cor
            ) VALUES (
                $child_id, '{$data['email']}', '{$data['address']}', '{$data['home_phone']}', '{$data['father_name']}', '{$data['father_address']}',
                '{$data['father_employment']}', '{$data['father_work_phone']}', '{$data['mother_name']}', '{$data['mother_address']}',
                '{$data['mother_employment']}', '{$data['mother_work_phone']}', '{$data['child_living_arrangements']}', 
                '{$data['child_legal_guardians']}', '{$data['released_name1']}', '{$data['released_address1']}', '{$data['released_number1']}', 
                '{$data['released_relationtochild1']}', '{$data['released_relationtoparent1']}', '{$data['released_status']}', 
                '{$data['released_name2']}', '{$data['released_address2']}', '{$data['released_number2']}', '{$data['released_relationtochild2']}', 
                '{$data['released_relationtoparent2']}', '{$data['released_other']}', '{$data['emergencyname_1']}', 
                '{$data['emergencyname_2']}', '{$data['emergencyname_3']}', '{$data['emergencynum_1']}', '{$data['emergencynum_2']}', 
                '{$data['emergencynum_3']}', '{$data['emergencyschool']}', '{$data['emergencymid_parent']}', 
                '{$data['emergencymid_parentdate']}', '{$data['emergencymid_facilityadmin']}', '{$data['emergencymid_facilityadmindate']}', 
                '{$data['parental_agreement_facility_name']}', '{$data['parental_agreement_child_name']}', '{$data['parental_agreement_days_of_week']}', 
                '{$data['parental_agreement_start_time']}', '{$data['parental_agreement_end_time']}', 
                '{$data['parental_agreement_start_month']}', '{$data['parental_agreement_end_month']}', 
                '{$data['parental_agreement_parent']}', '{$data['parental_agreement_parentdate']}', 
                '{$data['parental_agreement_facilityadmin']}', '{$data['parental_agreement_facilityadmindate']}', 
                '{$data['medical_condition']}',  
                '{$uploadedFiles['2x2']}', '{$uploadedFiles['birth']}', '{$uploadedFiles['parentID']}', '{$uploadedFiles['cor']}'
            )";

        if ($conn->query($sql_parental) === TRUE) {






 $sql_get_user_id = "SELECT user_id FROM user WHERE email = ?";
    if ($stmt_get_user_id = $conn->prepare($sql_get_user_id)) {
        $stmt_get_user_id->bind_param('s', $data['email']);
        $stmt_get_user_id->execute();
        $stmt_get_user_id->store_result();

        if ($stmt_get_user_id->num_rows > 0) {
            $stmt_get_user_id->bind_result($userId);
            $stmt_get_user_id->fetch();
        } else {
            echo "Error: User not found with the provided email.";
            exit;  
        }
        $stmt_get_user_id->close();
    } else {
        echo "Error: Could not prepare query to get user_id.";
        exit; 
    }

    $datesent = date('Y-m-d');
    $type = 'notif';
    $from = 'teacher';
    $status = "warning";
   $message = "We are pleased to inform you that your application for enrollment at the USTP Child Minding Center, with reference number " . htmlspecialchars($referenceInput) . ", is currently being reviewed. You will be notified once the processing is complete.";


 
    $stmt_notification = $conn->prepare("INSERT INTO notification (user_id, message, datesent, status, type, `from`) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt_notification) {
        $stmt_notification->bind_param('isssss', $userId, $message, $datesent, $status, $type, $from);
        $stmt_notification->execute() ? print("") : print("Error: " . $conn->error);
        $stmt_notification->close();
    } else {
        echo "Error: Could not prepare notification query.";
    }














    $enrollment_status = "pending";
    $currentDate = $conn->real_escape_string($data['currentDate']);  
    $child_name = $conn->real_escape_string($data['child_name']);
    $referenceInput = $conn->real_escape_string($data['referenceInput']);

    $sql_enrollment = "INSERT INTO enrollment (user_id, enrollment_status, enrollment_date, child_name, child_id, ref) 
    VALUES ($user_id, '$enrollment_status', '$currentDate', '$child_name', $child_id, '$referenceInput')";

    if ($conn->query($sql_enrollment) === TRUE) {

          $mail = new PHPMailer(true);
    try {
         $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '20morbius22@gmail.com'; 
    $mail->Password = 'kwsh bgll quam spli'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
        $mail->setFrom('20morbius22@gmail.com', 'USTP CHILD MINDING CENTER'); 
        $mail->addAddress($data['email']); 
        $mail->isHTML(true);
        $mail->Subject = 'USTP CHILD MINDING CENTER';
       $mail->Body    = '
        <html>
        <head>

        </head>
        <body style="color:black">
        <h1>Thank you for submitting your enrollment application!</h1>
        <p>Dear Parent,</p>

        <p>We are currently reviewing your application. Please check your email shortly for updates on your enrollment status. Thank you.</p>
        <p>If you have any questions or need assistance, please do not hesitate to contact us.</p>
        <p>Thank you for choosing the USTP Child Minding Center!</p>
        <p>Best regards,</p>
        <p>The USTP Child Minding Center Team</p>
    </body>
        </html>';

        $mail->addCustomHeader('X-Priority', '1 (Highest)');
        $mail->addCustomHeader('X-Mailer', 'PHPMailer 6.5');

        $mail->send();

    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
    } else {
        echo "Error in enrollment: " . $conn->error;
    }
} else {
    echo "Error in parental information: " . $conn->error;
}
    } else {
        echo "Error in child record: " . mysqli_error($conn);
    }
}

$conn->close();

function handleFileUpload($file, $uploadDir) {
    if ($file && $file['error'] == 0) {

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $newFileName = uniqid('', true) . '.' . $fileExtension;

        $targetFile = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $newFileName; 
        } else {
            return null; 
        }
    }
    return null; 
}






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
   </head>



   <body id="top" style="background:#035392">

      <div id="page-wrapper">
       <section id="contact-home" class="container" >
    <div class="row">
        <div class="  block-padding force notepad pl-5 pr-5" style="margin-top: -20px;max-width: 500px; width: 100%;margin: auto;">
            <div class="row">
                <div class="col-lg-12" >
                    <h4>Thank you for enrolling at USTP Child Minding Center!</h4>
                    <p>We have received your enrollment application. Please note that your enrollment is subject to confirmation. 
                        <br>You will receive an EMAIL to your gmail <strong>( <?php echo $data['email']; ?> )</strong> confirming your enrollment status shortly.</p>
                    <p>If you have any urgent questions or need further assistance, please contact us at <strong><?php echo $contact; ?></strong> or email us at <a href="mailto:<?php echo $email_address; ?>"><?php echo $email_address; ?></a>.</p>
                    <p>Thank you again for choosing USTP Child Minding Center. We look forward to welcoming you and your child!</p>
                </div>
                <div class="ornament-stars mt-8" data-aos="zoom-out"></div>
                <a href="index" class="btn btn-secondary" style="margin: auto; padding: 5px 20px;">
                                Close
                            </a>
            </div>
        </div>
    </div>
</section>
      </div>
    
  </body>
</html>