<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$teacherEmail = $teacherUserId = $teacherId = $teacherUsername = "";

if (isset($_SESSION['teacher_logged_email'])) {
    $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title']) && isset($_POST['date']) && isset($_POST['description'])) {
        $title = htmlspecialchars($_POST['title']);
        $date = htmlspecialchars($_POST['date']);
        $activityid = htmlspecialchars($_POST['activityid']);
        
        $description = htmlspecialchars($_POST['description']);
         $date = date('F Y', strtotime($date));
       
    } 
} else {
    echo "Invalid request.";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("head.php"); ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
         .image-container { border:2px solid transparent; transition:.3s }
        .image-container:hover { 
        border:2px solid #035392;
        box-shadow:0px 0px 10px grey }

    </style>
</head>

<body class="sidebar-expand counter-scroll">

    <?php include("leftsidebar.php"); ?>
    <?php include("header.php"); ?>

    <div class="main">
        <div class="main-content teacher-form">
            <div class="row">
               
                <div class="col-12 col-xl-12">
                    <div class="box" style="overflow-x:auto;">
                        <div class="box-body" id="activitiesimgs">
                            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px;">
                                <img src="header_activity.png" style="width:100%; height: 200px;">  
                                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Detailed Report: <span style="font-size: 15px;color: #035392"><?php echo $title; ?></span> <br>
                                Month: <span style="font-size: 15px;color: #035392" id="totalfound"><?php echo $date; ?></span><br>
                                 Description: <span style="font-size: 15px;color: #035392"><?php echo $description; ?></span> <br> 
                                 <span class="gr-btn mt-15" style="display: flex;justify-content: flex-start;">
    <button type="button" class="btn btn-primary btn-lg" id="download-pdf-btn" style="font-size: 12px;margin-top: 0px;margin-left: 0px;padding:10">
        <i class='bx bx-download'></i> Download
    </button>
</span></h5>
                                <div style="float:right;align-self: right;">
    <button class='btn btn-danger btn-lg' style='font-size: 12px; height: 50px; padding: 10px;background:#EF5741' onclick="window.location.href='activities'">
        <i class='bx bx-left-arrow-alt'></i> Back
    </button>

    <input type="hidden" value="<?php echo $activityid; ?>" name="activityid">
    <button class='btn btn-danger btn-lg' style='font-size: 12px; height: 50px; padding: 10px;background:#035392' onclick="triggerUpload()">
        <i class='bx bx-cloud-upload'></i> Upload Image
    </button>
    
   <input type="file" id="imageInput" style="display:none;" accept="image/*" onchange="uploadImage()">

</div>
                              
                            </div>


                           <div class="d-flex " style="flex-wrap: wrap;" >
  <?php
    include('../../db_conn.php');
    $query = "SELECT * FROM activity_img WHERE activity_imgid = $activityid";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagePath = 'gallery/' . $row['activity_imgsrc'];
            $imageSrc = $row['activity_imgsrc'];
            $imageId = $row['activity_imgid'];
            $openImg = 'gallery/' . $row['activity_imgsrc'];
    ?>
            <div class="d-flex flex-column align-items-center m-5">
                <a href="<?php echo $openImg; ?>" target="_blank">
                    <div class="image-container" style="width: 250px; height: 250px; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; margin-bottom: 10px;">
                        <img src="<?php echo $imagePath; ?>" alt="Image" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                </a>
                <button class="btn btn-danger" style="font-size: 12px;" onclick="deleteImage('<?php echo $imageSrc; ?>')">
                    <i class='bx bx-trash'></i> Delete
                </button>
            </div>

            <script type="text/javascript">
                function deleteImage(imageId) {
                    if (confirm('Are you sure you want to delete this image?')) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'delete_activityimg.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                alert('Deleted successfully!');
                                location.reload();
                            } else {
                                alert('Error deleting image.');
                            }
                        };
                        xhr.send('activity_imgsrc=' + imageId);
                    }
                }
            </script>
    <?php
        }
    } else {
        echo "<p>No images available.</p>";
    }
    $conn->close();
?>



</div>

                        </div>

                    </div>


                </div>


            </div>

        </div>

    </div>

    <div class="overlay"></div>

    <script type="text/javascript">

        document.getElementById('download-pdf-btn').addEventListener('click', function () {
    const element = document.getElementById('activitiesimgs');

    const allButtons = document.querySelectorAll('button');
    allButtons.forEach(button => {
        button.style.display = 'none'; 
    });

    element.style.margin = '0';
    element.style.padding = '0';

    const options = {
        margin: 0,
        filename: '<?php echo $title; ?> <?php echo $date; ?> - Activity Report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 2,
            useCORS: true,
            letterRendering: true,
        },
        jsPDF: {
            unit: 'in',
            format: 'letter',
            orientation: 'landscape',
            compress: true
        }
    };

    html2pdf().set(options).from(element).save().then(function () {
        allButtons.forEach(button => {
            button.style.display = '';
        });

        element.style.margin = '';
        element.style.padding = '';
    });
});



         function triggerUpload() {
        document.getElementById('imageInput').click();
    }

    function uploadImage() {
    const fileInput = document.getElementById('imageInput');
    const file = fileInput.files[0];
    const activityid = document.querySelector('input[name="activityid"]').value;
    
    if (!file) {
        alert('Please select an image to upload.');
        return;
    }

    const formData = new FormData();
    formData.append('activityid', activityid);
    formData.append('activity_img', file);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'insert_activityimg.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Alert the response from the PHP file
            alert(xhr.responseText);
            location.reload();
        } else {
            alert('Image upload failed.');
        }
    };

    xhr.send(formData);
}



 
    </script>

    <!-- SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   

    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>
</body>

</html>
