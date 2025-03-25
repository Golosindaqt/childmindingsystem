<?php
include '../../db_conn.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ref'])) {

    $ref = htmlspecialchars($_POST['ref']);
   $sql = "SELECT e.*, c.*, p.* 
        FROM enrollment e
        INNER JOIN child_record c ON e.child_id = c.child_id
        INNER JOIN parental_information p ON e.child_id = p.child_id
        WHERE e.ref = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $ref);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            echo '<script>window.history.back()</script>';
        }
        $stmt->close();
    } else {
        echo '<p>Error preparing the SQL statement.</p>';
    }
} else {
    echo '<p>No reference provided.</p>';
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
    <style>
 .flexdocs { display:flex; justify-content:center;align-content:center; flex-wrap:wrap; }
        .docscon { margin:20px auto; height:350px; width:350px;  }
        .boxcon { margin:auto; height:90%; width:90%; background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat;  }
        .text-below { margin:10px auto; text-align:center; }
        .admin-message {
    display: none; 
}
.dropdown-menu a:hover { background:#035392;color:white }
@media (max-width: 768px) { 
    .admin-message {
        display: block; 
    }
}
</style>
</head>
<body class="sidebar-expand">
        <?php include("leftsidebar.php"); ?>
         <?php include("header.php"); ?>
    <div class="main">
        <div class="main-content client project">
            <div class="row">
                    <div class="col-4 col-xl-12">
                   








                    <div class="box left-dot mb-30">
                        <div class="box-header  border-0 pd-0">
                            <div class="box-title fs-20 font-w600">Child Info</div>
                        </div>
                        <div class="box-body pt-20 user-profile">
                            <div class="table-responsive">
                                <table class="table mb-0 mw-100 color-span">
                                    <tbody>
                                         <tr>
        <td class="py-2 px-0"> <span class="w-50">Child Living Arrangements</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['child_living_arrangements']); ?></span> </td>
    </tr>
     <tr>
        <td class="py-2 px-0"> <span class="w-50">Child Legal Guardians</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['child_legal_guardians']); ?></span> </td>
    </tr>
      <tr>
        <td class="py-2 px-0"> <span class="w-50">Medical Condition</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['medical_condition']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Allergies</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['allergies']); ?></span> </td>
    </tr>
            <tr>
        <td class="py-2 px-0"> <span class="w-50">Name</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['child_name']); ?></span> </td>
    </tr>
     <tr>
        <td class="py-2 px-0"> <span class="w-50">Date of Birth</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars(date('M j, Y', strtotime($data['date_of_birth']))); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Age</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo $data['child_age']; ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Gender</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['gender']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Address</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['address']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Place of Birth</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['place_of_birth']); ?></span> </td>
    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box left-dot mb-30">
                        <div class="box-header  border-0 pd-0">
                            <div class="box-title fs-20 font-w600">Parents Info</div>
                        </div>
                        <div class="box-body pt-20 user-profile">
                            <div class="table-responsive">
                                <table class="table mb-0 mw-100 color-span">
                                    <tbody>
           <tr>
        <td class="py-2 px-0"> <span class="w-50">Father's Name</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['father_name']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Father's Home Address</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['father_home_address']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Father's Employment</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['father_employment']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Father's Work Phone</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['father_work_phone']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Mother's Name</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['mother_name']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Mother's Home Address</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['mother_home_address']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Mother's Employment</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['mother_employment']); ?></span> </td>
    </tr>
    <tr>
        <td class="py-2 px-0"> <span class="w-50">Mother's Work Phone</span> </td>
        <td>:</td>
        <td class="py-2 px-0"> <span class=""><?php echo htmlspecialchars($data['mother_work_phone']); ?></span> </td>
    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 col-xl-12">
                    <div class="box pd-0">
                        <div class="tab-menu-heading hremp-tabs p-0 ">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs w-100 d-flex justify-content-between">
                                    <li><a href="#tab2" data-bs-toggle="tab" class="active" >Appointments</a></li>
                                    <li><a href="#tab3"  data-bs-toggle="tab">Attendance</a></li>
                                    <li><a href="#tab4"  data-bs-toggle="tab">Reports</a></li>
                                    <li ><a href="#tab5" data-bs-toggle="tab">Release</a></li>
                                    <li><a href="#tab6" data-bs-toggle="tab">Emergency</a></li>
                                    <li><a href="#tab7" data-bs-toggle="tab">Medical Auth.</a></li>
                                    <li><a href="#tab8" data-bs-toggle="tab">Agreements</a></li>
                                    <li><a href="#tab9" data-bs-toggle="tab">Documents</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                            <div class="tab-content">

    <div class="tab-pane active" id="tab2">
        <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">
            <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px; margin-top: 20px;">
                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Appointment Record<br><span style="font-size: 15px; color: #035392" id="totalfound2">0 Found</span></h5>
                <input type="text" id="searchInput2" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable('searchInput2', 'appointment', 'totalfound2')">
            </div>

            <?php

            include '../../db_conn.php';

            $sql = "SELECT a.*, c.*
                    FROM appointment a
                    JOIN child_record c ON a.child_id = c.child_id
                    WHERE a.child_id = {$data['child_id']}
                    ORDER BY a.appointment_date DESC";

            $result = $conn->query($sql);
            ?>

            <table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="appointment" role="grid">
                <thead>
                    <tr class="top" style="background: #FFF8E5;color:black">
                        <th class="border-bottom-0 sorting fs-14 font-w500">Reference</th>
                        <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
                        <th class="border-bottom-0 sorting fs-14 font-w500">Time</th>
                        <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
                    </tr>
                </thead>
                <tbody >
                    <?php

                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            $appointment_id = htmlspecialchars($row['appointment_id']);
                            $appointment_date = htmlspecialchars($row['appointment_date']);
                            $ref = htmlspecialchars($row['ref']);
                            $session_time = htmlspecialchars($row['session_time']);

                            $formattedDate = date('M d, Y', strtotime($appointment_date));

                            echo "<tr>
        <td>$ref</td>
        <td>$formattedDate</td>
        <td>$session_time</td>
        <td><button class='btn btn-danger btn-lg delete-appointment' data-id='$appointment_id' style='font-size: 12px;padding: 10px;background:#EF5741'>
            <i class='bx bx-trash'></i> Delete
        </button></td>
      </tr>";
                        }
                    } else {

                        echo "";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>

        </div>
    </div>

                                <div class="tab-pane" id="tab3">
                                    <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">

                                       <div style="display: flex; justify-content: space-between;flex-wrap: wrap;margin-bottom: 20px;margin-top: 20px;">
                    <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Attendance Record<br><span style="font-size: 15px;color: #035392" id="totalfound">0 Found</span></h5>
                    <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                      </div>
                                        <?php

include '../../db_conn.php';

$sql = "SELECT a.*, c.*
        FROM attendance_record a 
        JOIN child_record c ON a.child_id = c.child_id
        WHERE a.child_id = {$data['child_id']}
        ORDER BY a.date DESC";

$result = $conn->query($sql);
?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="attendance" role="grid">
    <thead>
        <tr class="top" style="background: #FFF8E5;color:black">
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Session</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Status</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $attendanceId = htmlspecialchars($row['attendance_id']); 
                $date = htmlspecialchars($row['date']);
                $session = htmlspecialchars($row['shift']);
                $status = htmlspecialchars($row['status']);

                $formattedDate = date('M d, Y', strtotime($date));

                echo "<tr>
                        <td>$formattedDate</td>
                        <td>$session</td>
                        <td>$status</td>
                        <td><button class='btn btn-danger btn-lg delete-attendance' data-id='$attendanceId' style='font-size: 12px;padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button></td>
                      </tr>";
            }
        } else {

            echo "";
        }

        $conn->close();
        ?>
    </tbody>
</table>

                                    </div>
                                </div>

 <div class="tab-pane" id="tab4">
                                    <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">
                                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-bottom: 20px; margin-top: 20px;">
                <h5 class="mb-10 mt-0 font-w600 fs-18 line-h18">Reports Record<br><span style="font-size: 15px; color: #035392" id="totalfound3">0 Found</span></h5>
                <input type="text" id="searchInput3" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable('searchInput3', 'reports', 'totalfound3')">
            </div>
<?php

include '../../db_conn.php';

$sql = "SELECT ir.*, c.* 
FROM incident_report ir
INNER JOIN child_record c ON c.child_id = ir.child_id
WHERE c.child_id = {$data['child_id']}";

$result = $conn->query($sql);
?>

<table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="reports" role="grid">
    <thead>
        <tr class="top" style="background: #FFF8E5;color:black">

            <th class="border-bottom-0 sorting fs-14 font-w500">Type</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Date</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Time</th>
            <th class="border-bottom-0 sorting fs-14 font-w500">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
               $incident_id = htmlspecialchars($row['incident_id']); 
$date = htmlspecialchars($row['date']);
$type = htmlspecialchars($row['type']);
$time = htmlspecialchars($row['time']);
$childName = htmlspecialchars($row['child_name']);

$formattedDate = date('M d, Y', strtotime($date));

$formattedTime = date('g:i A', strtotime($time)); 

                echo "<tr>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$type</button>
            </form>
        </td>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$formattedDate</button>
            </form>
        </td>

        <td>
            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' style='border:none; background:none; padding:0; font-size: inherit; color: inherit; cursor:pointer;'>$formattedTime</button>
            </form>
        </td>

                         <td>

            <form action='view_report.php' method='POST'>
                <input type='hidden' name='incident_id' value='$incident_id'>
                <button type='submit' class='btn btn-danger btn-lg ' style='font-size: 12px;padding: 10px 15px;background:#035392;margin-bottom:3px'>
                   <i class='bx bx-right-arrow-alt'></i> View
                </button>
            </form>

            <button class='btn btn-danger btn-lg delete-report' data-id='$incident_id' style='font-size: 12px;padding: 10px;background:#EF5741'><i class='bx bx-trash'></i> Delete</button>
        </td>

                      </tr>";
            }
        } else {

            echo "";
        }

        $conn->close();
        ?>
    </tbody>
</table>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab5">
                                    <div class="box-body pl-15 pr-15 pb-20 pr-0">
                                        <h5 class="mb-10 mt-32 font-w600 fs-18 line-h18">Child Release Authorization Records</h5>
                                        <p>In childcare settings, maintaining detailed records of individuals authorized for the release of children is essential for safety and communication. Each record includes comprehensive information organized in a table format that captures all necessary details.</p>
<table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="invoice-tables">
        <tr>
        </tr>
        <tr>
            <td>Names</td>
            <td><?php echo htmlspecialchars($data['released_name1']); ?><br><?php echo htmlspecialchars($data['released_name2']); ?></td>
        </tr>
        <tr>
            <td>Addresses</td>
            <td><?php echo htmlspecialchars($data['released_address1']); ?><br><?php echo htmlspecialchars($data['released_address2']); ?></td>
        </tr>
        <tr>
            <td>Contact Numbers</td>
            <td><?php echo htmlspecialchars($data['released_number1']); ?><br><?php echo htmlspecialchars($data['released_number2']); ?></td>
        </tr>
        <tr>
            <td>Relationship to Child</td>
            <td><?php echo htmlspecialchars($data['released_relationtochild1']); ?><br><?php echo htmlspecialchars($data['released_relationtochild2']); ?></td>
        </tr>
        <tr>
            <td>Relationship to Parent</td>
            <td><?php echo htmlspecialchars($data['released_relationtoparent1']); ?><br><?php echo htmlspecialchars($data['released_relationtoparent2']); ?></td>
        </tr>
        <tr>
            <td>Employment Status</td>
            <td><?php echo htmlspecialchars($data['released_status']); ?></td>
        </tr>
        <tr>
            <td>Other Relevant Information</td>
            <td><?php echo htmlspecialchars($data['released_other']); ?></td>
        </tr>
    </table>
    <p>Such thorough documentation ensures that caregivers have all necessary details at hand to facilitate secure and efficient child release procedures, thereby fostering a safe environment for children and providing peace of mind for parents.</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab6">
                                    <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">
                                        <h5 class="mb-10 mt-32 font-w600 fs-18 line-h18">Emergency Contact Information</h5>
                                        <p>In childcare settings, maintaining detailed records of emergency contacts is essential for safety and communication. Each record includes comprehensive information organized in a table format that captures all necessary details.</p>
<table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="invoice-tables">
        <tr>
            <th>Name</th>
            <th>Number</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($data['emergencyname_1']); ?></td>
            <td><?php echo htmlspecialchars($data['emergencynum_1']); ?></td>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($data['emergencyname_2']); ?></td>
            <td><?php echo htmlspecialchars($data['emergencynum_2']); ?></td>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($data['emergencyname_3']); ?></td>
            <td><?php echo htmlspecialchars($data['emergencynum_3']); ?></td>
        </tr>
    </table>
    <h5>School Information</h5>
    <p>Emergency School: <strong  class="color-primary" ><?php echo htmlspecialchars($data['emergencyschool']); ?></strong></p>
    <p>Such thorough documentation ensures that caregivers have all necessary details at hand to respond effectively in case of emergencies, fostering a safe environment for children and providing peace of mind for parents.</p>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab7">
                                     <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">
                                        <h5 class="mb-10 mt-32 font-w600 fs-18 line-h18">Emergency Medical Authorization</h5>
                                        <p>Should <strong  class="color-primary"><?php echo htmlspecialchars($data['child_name']); ?></strong> Date of Birth <strong  class="color-primary"><?php echo htmlspecialchars(date('M j, Y', strtotime($data['date_of_birth']))); ?></strong> suffer an injury or illness while in the Child Minding Center and the facility is unable to contact me immediately, it shall be authorized to secure such medical attention and care for the child as may be necessary. I shall assume responsibility for payment for services.</p>

<table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="invoice-tables">
        <tr>
        </tr>
        <tr>
            <td>Parent / Guardian</td>
            <td><?php echo htmlspecialchars($data['emergencymid_parent']); ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($data['emergencymid_parentdate']))); ?></td>
        </tr>
        <tr>
            <td>Facility Administration / Person-in-Charge</td>
            <td><?php echo htmlspecialchars($data['emergencymid_facilityadmin']); ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($data['emergencymid_facilityadmindate']))); ?></td>
        </tr>
    </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab8" >
                                    <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10" >
                                        <h5 class="mb-10 mt-32 font-w600 fs-18 line-h18">Agreement</h5>
<p >
    The <strong  class="color-primary"><?php echo htmlspecialchars($data['parental_agreement_facility_name']); ?></strong> agrees to provide child care for 
    <strong  class="color-primary"><?php echo htmlspecialchars($data['parental_agreement_child_name']); ?></strong>.
</p>
<p>
    On <strong  class="color-primary" style="margin: 0px 5px;"><?php echo htmlspecialchars($data['parental_agreement_days_of_week']); ?></strong> times a week from 
       <strong style="margin: 0px 5px;"  class="color-primary"><?php echo htmlspecialchars($data['parental_agreement_start_time']); ?></strong> to 
    <strong style="margin: 0px 5px;"  class="color-primary"><?php echo htmlspecialchars($data['parental_agreement_end_time']);?></strong>.
</p>
<p>
    From 
    <strong  class="color-primary" style="margin: 0px 5px;">
    <?php 
    $startMonth = DateTime::createFromFormat('Y-m', $data['parental_agreement_start_month']);
    echo htmlspecialchars($startMonth->format('F Y')); 
    ?>
    </strong> 
    to 
    <strong  class="color-primary" style="margin: 0px 5px;">
    <?php 
    $endMonth = DateTime::createFromFormat('Y-m', $data['parental_agreement_end_month']);
    echo htmlspecialchars($endMonth->format('F Y')); 
    ?>
    </strong>.
</p>
<br>
<p><strong  class="color-primary" >Agreement Terms:</strong></p>
<p><strong  class="color-primary" >1. Entry and Exit Protocol</strong><br>
I understand that my child will not be permitted to enter or leave the facility without being escorted by the parent(s), an authorized person designated by the parent(s), or facility personnel.</p>
<p><strong  class="color-primary" >2. Responsibility for Records</strong><br>
I acknowledge that it is my responsibility to maintain up-to-date records for my child, reflecting any significant changes as they occur, such as telephone numbers, work location, emergency contacts, and other pertinent information.</p>
<p><strong  class="color-primary" >3. Communication of Incidents</strong><br>
The facility agrees to keep me informed of any incidents involving my child, including but not limited to illnesses, injuries, and other relevant occurrences.</p>
<p><strong  class="color-primary" >4. Policy on Illness</strong><br>
I understand and agree that if my child is ill, they may not be accepted for care or allowed to remain in care at the facility.</p>
<p><strong  class="color-primary" >5. Policies and Procedures</strong><br>
I confirm that I have received a copy of the facility’s policies and procedures and agree to abide by them.</p>
<p><strong  class="color-primary" >6. Child’s Progress and Participation</strong><br>
I understand that the facility will keep me informed about my child's progress and any issues related to their care, including special needs. I also recognize that my participation in facility activities is encouraged.</p>
<br>
                                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="invoice-tables">
        <tr>
        </tr>
        <tr>
            <td>Parent / Guardian</td>
            <td><?php echo htmlspecialchars($data['parental_agreement_parent']); ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($data['parental_agreement_parentdate']))); ?></td>
        </tr>
        <tr>
            <td>Facility Administration / Person-in-Charge</td>
            <td><?php echo htmlspecialchars($data['parental_agreement_facilityadmin']); ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($data['parental_agreement_facilityadmindate']))); ?></td>
        </tr>
    </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab9">
                                    <div class="box-body pl-15 pr-15 pb-20 table-responsive activity mt-10">
                                        <h5 class="mb-10 mt-32 font-w600 fs-18 line-h18">Documents</h5>
                                        <div class="flexdocs">
<?php
function isImage($file) {
    $imageFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($extension, $imageFileTypes);
}
function filePath($file) {
    return '../../uploads/' . $file;
}
?>
<?php if (!empty($data['upload_2x2'])): ?>
<a href="<?php echo htmlspecialchars(filePath($data['upload_2x2'])); ?>" target="_blank" class="docscon">
    <div class="boxcon" style="background-image: url('<?php echo htmlspecialchars(isImage($data['upload_2x2']) ? filePath($data['upload_2x2']) : '../../uploads/notimg.png'); ?>');"></div>
    <div class="text-below">2x2 Picture</div>
</a>
<?php endif; ?>
<?php if (!empty($data['upload_birth'])): ?>
<a href="<?php echo htmlspecialchars(filePath($data['upload_birth'])); ?>" target="_blank" class="docscon">
    <div class="boxcon" style="background-image: url('<?php echo htmlspecialchars(isImage($data['upload_birth']) ? filePath($data['upload_birth']) : '../../uploads/notimg.png'); ?>');"></div>
    <div class="text-below">Birth Certificate</div>
</a>
<?php endif; ?>
<?php if (!empty($data['upload_parentID'])): ?>
<a href="<?php echo htmlspecialchars(filePath($data['upload_parentID'])); ?>" target="_blank" class="docscon">
    <div class="boxcon" style="background-image: url('<?php echo htmlspecialchars(isImage($data['upload_parentID']) ? filePath($data['upload_parentID']) : '../../uploads/notimg.png'); ?>');"></div>
    <div class="text-below">Parent ID</div>
</a>
<?php endif; ?>
<?php if (!empty($data['upload_cor'])): ?>
<a href="<?php echo htmlspecialchars(filePath($data['upload_cor'])); ?>" target="_blank" class="docscon">
    <div class="boxcon" style="background-image: url('<?php echo htmlspecialchars(isImage($data['upload_cor']) ? filePath($data['upload_cor']) : '../../uploads/notimg.png'); ?>');"></div>
    <div class="text-below">Certificate of Registration</div>
</a>
<?php endif; ?>
    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="box user-pro-list overflow-hidden mb-30">
                        <div class="box-body">
                            <div class="user-pic text-center">
                                <div class="avatar ">
                                    <img src="../../img/favicon.png" alt="" height="112px" width="112px">
                                </div>
                                <div class="pro-user mt-3">
                                    <h5 class="pro-user-username text-dark mb-2 fs-15 mt-42 color-span"><?php echo $data['child_name']; ?></h5>
                                    <h6 class="pro-user-desc text-muted fs-14"><?php echo $data['ref']; ?></h6>
                                    <h6 class="pro-user-desc text-muted fs-14"><?php echo $data['email']; ?></h6>
                              <?php
$status = $data['enrollment_status'];
$backgroundColor = '';
$color = '';
if ($status === 'pending') {
    $backgroundColor = 'rgba(255, 214, 38, 0.3)'; 
    $color = 'rgba(255, 214, 38, 1)'; 
} elseif ($status === 'accepted') {
    $backgroundColor = 'rgba(3, 83, 146, 0.3)'; 
    $color = 'rgba(3, 83, 146, 1)'; 
} else {
    $backgroundColor = 'rgba(239, 87, 65, 0.3)'; 
    $color = 'rgba(239, 87, 65, 1)'; 
}
echo '<span style="background-color: ' . $backgroundColor . '; color: ' . $color . '; padding: 5px 10px; font-size:10px; border-radius: 5px; display: inline-block;">' . htmlspecialchars($status) . '</span>';
?>
                                </div>
                            </div>
                        </div>
                        <?php
if ($data['enrollment_status'] === 'pending') {
    echo '<div class="box-footer pt-41">
            <div class="btn-list text-center">
                <a class="btn btn-primary accept-btn" data-ref="' . htmlspecialchars($data['ref']) . '" data-email="' . htmlspecialchars($data['email']) . '">Accept</a>
               

                 <a class="btn" style="background: #EF5741; color: white;"  href="#" data-toggle="modal" data-target="#reject_enroll">Reject</a>
            </div>
          </div>';
}


if ($data['enrollment_status'] === 'rejected') {

    echo '<p class="notice" style="font-size: 14px; color: #555; margin-top: 20px; padding: 10px; border: 1px solid #f0f0f0; background-color: #f9f9f9; border-radius: 5px;">
            <strong style="color: #EF5741">Remarks:</strong> 
            ' . htmlspecialchars($data['remarks']) . '
           
        </p>';
} 

?>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>





      <div id="add_project" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Project</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-sm-6 mb-0">
                                        <div class="form-group">
                                            <label>Project Name</label>
                                            <input class="form-control" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-0">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <select class="select">
                                                <option>Client 1</option>
                                                <option>Client 2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-0">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <div class="cal-icon">
                                                <input class="form-control " type="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-0">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <div class="cal-icon">
                                                <input class="form-control" type="date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 mb-0">
                                        <div class="form-group">
                                            <label>Rate</label>
                                            <input placeholder="$50" class="form-control" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mb-0">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <select class="select">
                                                <option>Hourly</option>
                                                <option selected>Fixed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-0">
                                        <div class="form-group">
                                            <label>Priority</label>
                                            <select class="select">
                                                <option selected>High</option>
                                                <option>Medium</option>
                                                <option>Low</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="4" class="form-control" placeholder="Enter your message here"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Upload Files</label>
                                    <input class="form-control" type="file">
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


              <div id="reject_enroll" class="modal custom-modal fade" role="dialog" style="color:#19181a">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Enrollment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="rejectEnrollmentForm">
    <div class="row" style="position: absolute;left: -50000000000000px;">
        <div class="col-sm-6 mb-0">
            <div class="form-group">
                <label>Reference</label>
                <input class="form-control" name="ref" value="<?php echo htmlspecialchars($data['ref']);?>" type="text">
            </div>
        </div>
        <div class="col-sm-6 mb-0">
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" name="email" value="<?php echo htmlspecialchars($data['email']);?>" type="text">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Remarks: </label>
        <textarea rows="4" class="form-control" name="remarks" placeholder="Enter the reason(s) for rejecting this enrollment."></textarea>
    </div>

    <div class="submit-section" style="display:flex;justify-content: flex-end;">
        <button type="submit" class="btn btn-primary submit-btn">Submit</button>
    </div>
</form>
                        </div>
                    </div>
                </div>
            </div>
    <div class="overlay"></div>
<script>
$(document).ready(function() {
   $('.accept-btn').click(function(event) {
    event.preventDefault(); 
    
    var $button = $(this);
    $button.removeClass('accept-btn');
    $button.text('Processing...');

    var ref = $button.data('ref');
    var email = $button.data('email');
    
     
 
    $.ajax({
        url: 'accept_enrollment.php',
        type: 'POST',
        data: { ref: ref, email: email },
        success: function(response) {
            alert('Enrollment accepted!');
            location.reload(); 
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
        },
        complete: function() {
            $button.prop('disabled', false).text('Accept');
        }
    });
});


   $('#rejectEnrollmentForm').submit(function(e) {
    e.preventDefault(); 

    var formData = $(this).serialize(); 
    var submitButton = $('#rejectEnrollmentForm button'); 

    submitButton.prop('disabled', true).text('Processing...');

    $.ajax({
        type: 'POST',
        url: 'reject_enrollment.php',
        data: formData,
        success: function(response) {
            alert('Enrollment Rejected Successfully!');
            $('#rejectEnrollmentForm')[0].reset();
            window.location.reload()
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
            console.error(error);
        },
        complete: function() {
            submitButton.prop('disabled', false).text('Reject Enrollment');
        }
    });
});


    
});
$(document).ready(function() {

    function handleDelete(deleteButtonClass, deleteUrl, idName, successMessage) {
        $(deleteButtonClass).on('click', function() {
            var id = $(this).data('id'); 

            if (confirm("Are you sure you want to delete this record?")) {

                $.ajax({
                    type: 'POST',
                    url: deleteUrl, 
                    data: { [idName]: id }, 
                    success: function(response) {
                        if (response == 'success') {
                            alert(successMessage);
                            window.location.reload();
                        } else {
                            alert('Error: ' + response);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the record.');
                    }
                });
            }
        });
    }

    handleDelete('.delete-appointment', 'delete_appointment.php', 'appointment_id', 'Appointment deleted successfully!');
    handleDelete('.delete-attendance', 'delete_attendance.php', 'attendance_id', 'Attendance record deleted successfully!');
    handleDelete('.delete-report', 'delete_incident.php', 'incident_id', 'Report deleted successfully!');
});

function filterTable(inputId, tableId, totalFoundId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    let checkedCells = 0;
    let matchingCells = 0;

    const searchWords = filter.split(/\s+/).filter(word => word.length > 0);

    if (searchWords.length === 0) {
        for (let i = 1; i < rows.length; i++) {
            rows[i].style.display = ""; 
        }
        matchingCells = rows.length - 1; 
    } else {

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let match = true;  

            for (let word of searchWords) {
                let wordFoundInRow = false;

                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        const cellValue = cells[j].textContent || cells[j].innerText;
                        checkedCells++;  

                        if (cellValue.toLowerCase().includes(word)) {
                            wordFoundInRow = true;
                            break;
                        }
                    }
                }

                if (!wordFoundInRow) {
                    match = false;
                    break;
                }
            }

            rows[i].style.display = match ? "" : "none";
            if (match) matchingCells++;  
        }
    }

    document.getElementById(totalFoundId).innerText = `${matchingCells} Found`;
}

window.onload = function() {
    filterTable('searchInput', 'attendance', 'totalfound'); 
     filterTable('searchInput2', 'appointment', 'totalfound2');
          filterTable('searchInput3', 'reports', 'totalfound3');
};

document.getElementById('searchInput').addEventListener('input', function() {
    filterTable('searchInput', 'attendance', 'totalfound');
});

document.getElementById('searchInput2').addEventListener('input', function() {
    filterTable('searchInput2', 'appointment', 'totalfound2');
});

document.getElementById('searchInput3').addEventListener('input', function() {
    filterTable('searchInput3', 'reports', 'totalfound3');
});

</script>
 <script src="../libs/jquery/jquery.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
    <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="../libs/apexcharts/apexcharts.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/shortcode.js"></script>
    <script src="../js/pages/dashboard.js"></script>

</body>
</html>