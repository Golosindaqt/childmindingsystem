<?php

require_once '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $teacher_id = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
    $teacher_user_id = isset($_POST['teacher_user_id']) ? (int)$_POST['teacher_user_id'] : 0;

    $fullname = isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname'], ENT_QUOTES) : '';
    $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
    $place_of_birth = isset($_POST['place_of_birth']) ? htmlspecialchars($_POST['place_of_birth'], ENT_QUOTES) : '';
    $civil_status = isset($_POST['civil_status']) ? $_POST['civil_status'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : '';
    $contact = isset($_POST['contact']) ? htmlspecialchars($_POST['contact'], ENT_QUOTES) : '';
    $email_address = isset($_POST['email_address']) ? htmlspecialchars($_POST['email_address'], ENT_QUOTES) : '';

    if (empty($fullname) || empty($email_address) || empty($teacher_id)) {
        echo "Error: Full Name, Email Address, and Teacher ID are required fields.";
        exit;
    }

    $conn->begin_transaction();

    try {

        $sql_teacher = "UPDATE teacher 
                        SET fullname = ?, date_of_birth = ?, place_of_birth = ?, civil_status = ?, 
                            gender = ?, address = ?, contact = ?, email_address = ?
                        WHERE teacher_id = ?";

        if ($stmt_teacher = $conn->prepare($sql_teacher)) {

            $stmt_teacher->bind_param("sssssssss", $fullname, $date_of_birth, $place_of_birth, $civil_status, 
                                      $gender, $address, $contact, $email_address, $teacher_id);

            if (!$stmt_teacher->execute()) {
                throw new Exception("Error: Could not execute the teacher update query.");
            }

            $stmt_teacher->close();
        } else {
            throw new Exception("Error: Could not prepare the teacher update query.");
        }

        $sql_user = "UPDATE user 
                     SET email = ? 
                     WHERE user_id = ?";

        if ($stmt_user = $conn->prepare($sql_user)) {

            $stmt_user->bind_param("si", $email_address, $teacher_user_id);

            if (!$stmt_user->execute()) {
                throw new Exception("Error: Could not execute the user update query.");
            }

            $stmt_user->close();
        } else {
            throw new Exception("Error: Could not prepare the user update query.");
        }

        $conn->commit();
        echo "Teacher and user data updated successfully!";

    } catch (Exception $e) {

        $conn->rollback();
        echo $e->getMessage();
    } finally {

        $conn->close();
    }
}
?>