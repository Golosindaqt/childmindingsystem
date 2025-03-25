<?php
include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $teacherId = htmlspecialchars($_POST['teacher_id']);
    $title = htmlspecialchars($_POST['title']);
    $date = htmlspecialchars($_POST['month']);
    $description = htmlspecialchars($_POST['description']);

    $checkSql = "SELECT 1 FROM activity_report WHERE teacher_id = ? AND date = ? LIMIT 1";

    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("is", $teacherId, $date);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "Date already exists.";
        } else {
            $sql = "INSERT INTO activity_report (teacher_id, title, date, description) VALUES (?, ?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("isss", $teacherId, $title, $date, $description);

                if ($stmt->execute()) {
                    echo "Activity submitted successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }

        $checkStmt->close();
    } else {
        echo "Error preparing check statement: " . $conn->error;
    }

    $conn->close();
}
?>
