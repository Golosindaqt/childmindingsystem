<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Reference'])) {
        $appointment_id = $_POST['appointment_id'];
        $Reference = $_POST['Reference'];
        $child_name = $_POST['child_name'];
        $appointment_date = $_POST['appointment_date'];
        $session_time = $_POST['session_time'];
        
        $message = "
        <p>Dear Parent,</p>
        <p>Here are the details of your child's upcoming appointment:</p>
        <ul>
            <li><strong>Reference Number:</strong> " . htmlspecialchars($Reference) . "</li>
            <li><strong>Child's Name:</strong> " . htmlspecialchars($child_name) . "</li>
            <li><strong>Appointment Date:</strong> " . htmlspecialchars($appointment_date) . "</li>
            <li><strong>Session Time:</strong> " . htmlspecialchars($session_time) . "</li>
        </ul>
        <p>Please make sure to attend the appointment on time. Please note that, for most appointments, it is important that your child be present so that our professionals can provide the necessary care or services. If you have any questions or need to reschedule, don't hesitate to contact us.</p>
        <p>Thank you for trusting us.</p>
        <p>Best regards,</p>
        <p>The Child Minding and GAD Resource Center Team</p>
        ";
    } else {
        echo "<script>window.history.back();</script>";
    }
} else {
    echo "<script>window.history.back();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
   <title>USTP - Child Minding and GAD Resource Center</title>
   <link rel="apple-touch-icon" sizes="57x57" href="../../img/favicon.png">
   <link rel="apple-touch-icon" sizes="72x72" href="../../img/favicon.png">
   <link rel="apple-touch-icon" sizes="114x114" href="../../img/favicon.png">
   <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png">
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700%7CNunito:400,700,900" rel="stylesheet">
   <link href="../../fonts/flaticon/flaticon.css" rel="stylesheet" type="text/css">
   <link href="../../fonts/fontawesome/fontawesome-all.min.css" rel="stylesheet" type="text/css">
   <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <link href="../../css/style.css" rel="stylesheet">
   <link href="../../css/plugins.css" rel="stylesheet">
   <link href="../../css/maincolors.css" rel="stylesheet">
   <link rel="stylesheet" href="../../vendor/layerslider/css/layerslider.css">

   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body id="top" style="background:#035392">
   <div id="page-wrapper">
      <section id="contact-home" class="container">
         <div class="row" style="justify-content: center;margin: auto;width: 100%;" >
            <div class="block-padding force notepad pl-5 pr-5" style="margin-top: -20px; max-width: 500px; width: 100%; margin: 0px;" id="notepad">
               <div class="row">
                  <div class="col-lg-12" >
                     <?php
                     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['Reference'])) {
                            echo $message;
                        } else {
                            echo "<p>No appointment details found.</p>";
                        }
                     }
                     ?>
                  </div>
                  <div class="ornament-stars mt-8" data-aos="zoom-out"></div>
               </div>

              <div style="display: flex;justify-content: space-between; flex-direction: column; margin: auto;">
                <button class="btn btn-secondary" id="download-pdf-btn" style="margin: 20px 20px; padding: 5px 20px;background: #035392;">
                     Download
                  </button>
                 <a href="appointments" class="btn btn-secondary" style="margin: auto; padding: 5px 20px;">
                     Close
                  </a>
              </div>
            </div>
         </div>
      </section>
   </div>

   <script type="text/javascript">
       document.getElementById('download-pdf-btn').addEventListener('click', function () {
            const downloadBtn = document.getElementById('download-pdf-btn');
            const closeBtn = document.querySelector('a[href="appointments"]');
            downloadBtn.style.display = 'none';
            closeBtn.style.display = 'none';

            const referenceNumber = "<?php echo htmlspecialchars($Reference); ?>";

            const element = document.getElementById('notepad');

            const options = {
                margin: 0.5,
                filename: `Appointment_${referenceNumber}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 3,
                    useCORS: true,
                    letterRendering: true,
                    width: element.scrollWidth,
                    height: element.scrollHeight,
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait',
                }
            };

            html2pdf().set(options).from(element).save().then(() => {
                downloadBtn.style.display = 'inline-block';
                closeBtn.style.display = 'inline-block';
            });
        });
   </script>
</body>
</html>
