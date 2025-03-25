<?php

include('../../db_conn.php');

if (isset($_POST['countprof']) && isset($_POST['counthaper']) && isset($_POST['countallchild'])) {

    $countprof = $_POST['countprof'];
    $counthaper = $_POST['counthaper'];
    $countallchild = $_POST['countallchild'];

    $sql = "UPDATE counter SET countprof = ?, counthaper = ?, countallchild = ? "; 

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('iii', $countprof, $counthaper, $countallchild);

        if ($stmt->execute()) {
            echo "Counter values updated successfully!";
        } else {
            echo "Error updating counter values.";
        }

        $stmt->close();
    } else {
        echo "Error in preparing SQL statement.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>