<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'])) {

    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
} 

$defaultDate = date('Y-m-d');  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {

    $selectedDate = $_POST['date'];

    echo "The selected date is: " . htmlspecialchars($selectedDate);

    $dateTime = new DateTime($selectedDate);

    echo "<br>Formatted Date: " . $dateTime->format('l, F j, Y');
}


include '../../db_conn.php';

$sql = "SELECT COUNT(*) AS row_count FROM contact_form";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalRows = $row['row_count'];
} else {
    echo "Error: " . mysqli_error($conn);
}


$updateQuery = "UPDATE contact_form SET seen = 'yes' WHERE seen = 'no'";

$updateResult = mysqli_query($conn, $updateQuery);


mysqli_close($conn);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>

.card {

    width: 30%;    
}

@media (max-width: 768px) {
    .card {
       width: 100%; 
    }
}

</style>
     <?php include("head.php"); ?>
</head>

<body class="sidebar-expand counter-scroll">

  <?php include("leftsidebar.php"); ?>

   <?php include("header.php"); ?>

    <div class="main">

<div class="main-content teacher-form">
    <div class="row" style="justify-content: center;">

<div class="col-12 col-xl-12">
    <div class="box" style="overflow-x:auto;">
        <div class="box-body">
            <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;">
                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Contact Us<br>
                    <span style="font-size: 15px;color: #035392" ><?php echo $totalRows; ?> Found</span>
                </h5>
            </div>

           <?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../../db_conn.php';

$sql = "SELECT * FROM contact_form ORDER BY created_at DESC";

$result = $conn->query($sql);

$contacts = [];

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $dateFormatted = date('M j, Y', strtotime($row['created_at']));

        $contacts[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'subject' => $row['subject'],
            'message' => $row['message'],
            'created_at' => $row['created_at'],
            'phone' => $row['phone']
        ];
    }
}

$conn->close();
?>


<div class="card-body pb-0 pt-3" id="contactsContainer" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; padding: 0px;">
    <?php foreach ($contacts as $contact): ?>
        <div class="card contact-card" style="min-width: 32%; height: auto; box-sizing: border-box; margin-bottom: 10px;">

           
            <div class="card-body">
                <p style="color:#222943">Subject: <span style="color:grey"><?= htmlspecialchars($contact['subject']) ?></span></p>
                <p style="color:#222943">Message: </p>
                <p style="color:#222943"><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                <br>
                <br>
                <p style="color:#222943">Name: <span style="color:grey"><?= htmlspecialchars($contact['name']) ?></span></p>
                <p style="color:#222943">Email: <span style="color:grey"><?= htmlspecialchars($contact['email']) ?></span></p>
                <p style="color:#222943">Phone: <span style="color:grey"><?= $contact['phone'] ?></span></p>
                <p style="color:#222943">Submitted on: <span style="color:grey"><?= $contact['created_at'] ?></span></p>
               

            </div> 
              <button class="btn btn-danger delete-btn" data-id="<?= htmlspecialchars($contact['id']) ?>" style="font-size: 12px; width: 100px; margin: auto;margin-bottom:20px; " >
                    <i class='bx bx-trash'></i> Delete
                </button>
        </div> 
    <?php endforeach; ?>
</div> 

        </div>
    </div>
</div>

    </div>

</div>

    </div>

    <div class="overlay"></div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    $(document).ready(function() {

    $(".delete-btn").click(function() {
        var contactusid = $(this).data("id"); 


        if (confirm("Are you sure you want to delete this record?")) {

            $.ajax({
                url: 'delete_contactusid.php', 
                type: 'POST',
                data: { contactusid: contactusid },
                success: function(response) {

                    if (response === 'success') {
                        alert("Deleted successfully!");
                        window.location.reload();

                        $("button[data-id='" + contactusid + "']").closest('tr').remove();
                    } else {
                        alert("Failed to delete record.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });
});

    $(document).ready(function() {
        $('#attendanceForm').submit(function(e) {
            e.preventDefault();  

            var formData = $(this).serialize();  

            $.ajax({
                url: $(this).attr('action'),  
                type: 'POST',
                data: formData,
                success: function(response) {

                    alert('Submited successfully!');
                    console.log(response); 
                    window.location.reload() 
                },
                error: function(xhr, status, error) {

                    alert('An error occurred while saving changes!');
                    console.log(xhr.responseText);  
                }
            });
        });
    });

  function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const reports = document.querySelectorAll('.report-card');
    let foundCount = 0;

    reports.forEach(report => {

        const childName = report.querySelector('.card-body p:nth-child(1)').textContent.toLowerCase(); 
        const childAge = report.querySelector('.card-body p:nth-child(2)').textContent.toLowerCase(); 
        const observationDate = report.querySelector('.card-body p:nth-child(3)').textContent.toLowerCase(); 
        const location = report.querySelector('.card-body p:nth-child(4)').textContent.toLowerCase(); 
        const description = report.querySelector('.card-body p:nth-child(6)').textContent.toLowerCase(); 
        const reportType = report.querySelector('.card-header .card-title').textContent.toLowerCase(); 

        if (
            childName.includes(searchInput) ||
            childAge.includes(searchInput) ||
            observationDate.includes(searchInput) ||
            location.includes(searchInput) ||
            description.includes(searchInput) ||
            reportType.includes(searchInput)
        ) {
            report.style.display = '';  
            foundCount++;
        } else {
            report.style.display = 'none';  
        }
    });

    document.getElementById('totalfound').textContent = `${foundCount} Found`;
}
filterTable();
</script>

  <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>
</body>

</html>