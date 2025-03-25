<?php

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $activityid = htmlspecialchars($_POST['activityid']);

    // Check how many images are already associated with this activity
    $sql_check = "SELECT COUNT(*) AS img_count FROM activity_img WHERE activity_imgid = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $activityid);
        $stmt_check->execute();
        $stmt_check->bind_result($img_count);
        $stmt_check->fetch();
        $stmt_check->close();

        // If 3 or more images are already uploaded for this activity, prevent further upload
        if ($img_count >= 3) {
            echo "You can only upload up to 3 images per activity.";
            exit;
        }
    } else {
        echo "Error checking existing images.";
        exit;
    }

    // Handle file upload
    if (isset($_FILES['activity_img']) && $_FILES['activity_img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['activity_img']['tmp_name'];
        $fileName = $_FILES['activity_img']['name'];
        $fileSize = $_FILES['activity_img']['size'];
        $fileType = $_FILES['activity_img']['type'];

        $uploadDir = 'gallery/';
        $newFileName = uniqid() . '-' . basename($fileName);
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $fileImgsrc = $newFileName;
        } else {
            echo "Error uploading the file.";
            exit;
        }

    } else {
        $fileImgsrc = NULL;
    }

    // Insert the new image into the database
    $sql = "INSERT INTO activity_img (activity_imgid, activity_imgsrc)
            VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $activityid, $fileImgsrc);

        if ($stmt->execute()) {
            echo "Image uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
    }
}

$conn->close();
?>
