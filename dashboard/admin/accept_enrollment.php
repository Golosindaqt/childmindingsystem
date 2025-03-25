<?php
include '../../db_conn.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ref = $_POST['ref'];

    $email = $_POST['email'];



  $sql_get_user_id = "SELECT user_id FROM user WHERE email = ?";
if ($stmt_get_user_id = $conn->prepare($sql_get_user_id)) {
    $stmt_get_user_id->bind_param('s', $email);
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
$status = "success";
$message = "We are pleased to inform you that your enrollment at the USTP Child Minding Center with reference number " . htmlspecialchars($ref) . " has been successfully processed.";

$stmt_notification = $conn->prepare("INSERT INTO notification (user_id, message, datesent, status, type, `from`) VALUES (?, ?, ?, ?, ?, ?)");
if ($stmt_notification) {
    $stmt_notification->bind_param('isssss', $userId, $message, $datesent, $status, $type, $from);
    if ($stmt_notification->execute()) {
        echo "Notification inserted!";
    } else {
        echo "Error inserting notification: " . $conn->error;
    }
    $stmt_notification->close();
} else {
    echo "Error: Could not prepare notification query.";
    exit;
}



















    $sql_enrollment = "UPDATE enrollment SET enrollment_status = 'accepted' WHERE ref = ?";
    if ($stmt_enrollment = $conn->prepare($sql_enrollment)) {
        $stmt_enrollment->bind_param('s', $ref);
        $stmt_enrollment->execute(); 
        $stmt_enrollment->close();
    } else {
        echo "Error: Could not prepare enrollment query.";
    }

    $sql_check_user = "SELECT username, password FROM user WHERE email = ? AND username <> '' AND password <> ''";
    if ($stmt_check_user = $conn->prepare($sql_check_user)) {
        $stmt_check_user->bind_param('s', $email);
        $stmt_check_user->execute();
        $stmt_check_user->store_result();

        if ($stmt_check_user->num_rows == 0) {

            $sql_user = "UPDATE user SET username = ?, password = ? WHERE email = ?";
            if ($stmt_user = $conn->prepare($sql_user)) {
                $password = password_hash($ref, PASSWORD_DEFAULT); 
                $stmt_user->bind_param('sss', $ref, $password, $email);
                $stmt_user->execute(); 
                $stmt_user->close();
            } else {
                echo "Error: Could not prepare user query.";
            }

            $account_info = '
                <p>Your new account username is: <strong>' . htmlspecialchars($ref) . '</strong> and password is: <strong>' . htmlspecialchars($ref) . '</strong>. Please use this to log in to your account.</p>
                <p>For security reasons, we strongly recommend that you change your password upon your first login.</p>
            ';
        } else {

            $account_info = '';
        }
        $stmt_check_user->close();
    } else {
        echo "Error: Could not prepare check user query.";
    }

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
        $mail->addAddress($email);  

        $mail->isHTML(true);
        $mail->Subject = 'USTP CHILD MINDING CENTER - Enrollment Confirmation';
        $mail->Body    = '
        <html>
        <head>

        </head>
        <body style="color:black">
            <h1 style="color:black">Congratulations! Your Enrollment Has Been Accepted!</h1>
            <p style="color:black">Dear Parent,</p>
            <p style="color:black">We are pleased to inform you that your enrollment at the <strong>USTP Child Minding Center</strong> has been successfully processed.</p>
            ' . $account_info . '
            <p style="color:black">If you have any questions or need assistance, please don\'t hesitate to contact our support team at <a href="mailto:support@ustp.com">support@ustp.com</a>.</p>
            <p style="color:black">Thank you for choosing the USTP Child Minding Center!</p>
            <p style="color:black">Best regards,</p>
            <p style="color:black">The USTP Child Minding Center Team</p>
        </body>
        </html>';

        $mail->addCustomHeader('X-Priority', '1 (Highest)');
        $mail->addCustomHeader('X-Mailer', 'PHPMailer 6.5');

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$conn->close();
?>