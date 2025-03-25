<?php
session_start();

include '../../db_conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activity_imgsrc'])) {
    $imageSrc = $_POST['activity_imgsrc'];
    $imagePath = 'gallery/' . $imageSrc;

    if (file_exists($imagePath)) {
        if (unlink($imagePath)) {
            $query = "DELETE FROM activity_img WHERE activity_imgsrc = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $imageSrc);
            
            if ($stmt->execute()) {
                echo "Image deleted successfully!";
            } else {
                echo "Error deleting image from database.";
            }
            
            $stmt->close();
        } else {
            echo "Error deleting image file.";
        }
    } else {
        echo "Image file not found.";
    }

    $conn->close();
} else {
    echo "No image source provided.";
}
?>
