<?php

require_once '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
    $father_name = isset($_POST['father_name']) ? htmlspecialchars($_POST['father_name'], ENT_QUOTES) : '';
    $father_home_address = isset($_POST['father_home_address']) ? htmlspecialchars($_POST['father_home_address'], ENT_QUOTES) : '';
    $father_work_phone = isset($_POST['father_work_phone']) ? htmlspecialchars($_POST['father_work_phone'], ENT_QUOTES) : '';
    $father_employment = isset($_POST['father_employment']) ? htmlspecialchars($_POST['father_employment'], ENT_QUOTES) : '';
    $mother_name = isset($_POST['mother_name']) ? htmlspecialchars($_POST['mother_name'], ENT_QUOTES) : '';
    $mother_home_address = isset($_POST['mother_home_address']) ? htmlspecialchars($_POST['mother_home_address'], ENT_QUOTES) : '';
    $mother_work_phone = isset($_POST['mother_work_phone']) ? htmlspecialchars($_POST['mother_work_phone'], ENT_QUOTES) : '';
    $mother_employment = isset($_POST['mother_employment']) ? htmlspecialchars($_POST['mother_employment'], ENT_QUOTES) : '';

    if (empty($father_name) || empty($father_home_address) || empty($father_work_phone) || empty($father_employment) ||
        empty($mother_name) || empty($mother_home_address) || empty($mother_work_phone) || empty($mother_employment)) {
        echo "Error: All fields are required.";
        exit;
    }

    $sql = "UPDATE parental_information 
            SET father_name = ?, father_home_address = ?, father_work_phone = ?, father_employment = ?, 
                mother_name = ?, mother_home_address = ?, mother_work_phone = ?, mother_employment = ?
            WHERE parent_id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('ssssssssi', 
            $father_name, 
            $father_home_address, 
            $father_work_phone, 
            $father_employment, 
            $mother_name, 
            $mother_home_address, 
            $mother_work_phone, 
            $mother_employment, 
            $parent_id
        );

        if ($stmt->execute()) {
            echo "Changes saved successfully!";
        } else {
            echo "Error: Could not execute the query.";
        }

        $stmt->close();
    } else {
        echo "Error: Could not prepare the query.";
    }

    $conn->close();
}
?>