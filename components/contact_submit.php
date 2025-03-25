<?php

include('db_conn.php');

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$phonenumber = $_POST['phonenumber'];
$created_at = $_POST['created_at'];

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Name, email, and message are required fields.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO contact_form (name, email, subject, message, created_at, phone) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $name, $email, $subject, $message, $created_at, $phonenumber);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'There was an error submitting your message.']);
}

$stmt->close();
$conn->close();


?>