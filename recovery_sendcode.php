<?php

include 'db_conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format';
        exit;
    }

    $query = "SELECT username, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
          $stmt->bind_result($username, $password);
          $stmt->fetch();

        $code = rand(100000, 999999);

        try {
            $mail = new PHPMailer(true);
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
            $mail->Subject = 'Account Recovery - 6-Digit Code';
            $mail->Body = "
                <h2>Account Recovery</h2>
                <p>Your 6-digit recovery code is: <strong>$code</strong></p>
                <p>Please use this code to reset your password.</p>";

            if ($mail->send()) {

    echo json_encode(['status' => 'success', 'code' => $code, 'email' => $email, 'username' => $username]);
}
 else {

                echo json_encode(['status' => 'error', 'message' => 'Error sending email']);
            }
        } catch (Exception $e) {

            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $mail->ErrorInfo]);
        }
    } else {

        echo json_encode(['status' => 'error', 'message' => 'Email not found']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No email provided']);
}

$conn->close();
?>