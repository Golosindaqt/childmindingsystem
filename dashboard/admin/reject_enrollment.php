<?php
include '../../db_conn.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ref = $_POST['ref'];
    $email = $_POST['email'];
    $remarks = $_POST['remarks'];

    $sql = "UPDATE enrollment SET enrollment_status = 'rejected', remarks = ? WHERE ref = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $remarks, $ref);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {

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
        $status = "danger";
        $message = "We regret to inform you that your enrollment with reference number " . htmlspecialchars($ref) . " has been rejected. 

        Reason for Rejection: " . htmlspecialchars($remarks);

        $stmt_notification = $conn->prepare("INSERT INTO notification (user_id, message, datesent, status, type, `from`) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt_notification) {
            $stmt_notification->bind_param('isssss', $userId, $message, $datesent, $status, $type, $from);
            $stmt_notification->execute() ? print("Notification inserted!") : print("Error: " . $conn->error);
            $stmt_notification->close();
        } else {
            echo "Error: Could not prepare notification query.";
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
            $mail->Subject = 'USTP CHILD MINDING CENTER - Enrollment Rejected';
            $mail->Body = '
            <html>
            <head></head>
            <body  style="color:black">
                <h1>Your Enrollment Has Been Rejected</h1>
                <p>We regret to inform you that your enrollment with reference number <strong>' . htmlspecialchars($ref) . '</strong> has been rejected.</p>
            
                <p><strong>Reason for Rejection:</strong> ' . nl2br(htmlspecialchars($remarks)) . '</p>
            </body>
            </html>';
            $mail->send();
            echo "Enrollment rejected and email sent.";
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No enrollment found with that reference.";
    }
    $stmt->close();
}
$conn->close();
?>
