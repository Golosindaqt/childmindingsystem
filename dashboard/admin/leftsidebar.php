<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['teacher_logged_email'])) {

  $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];

} else {
    echo "<script>window.history.back()</script>";
}

$current_page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

$enrollment_links = ($current_page == 'all_enrollments' || 
                     $current_page == 'accepted_enrollments' || 
                     $current_page == 'pending_enrollments' ||
                     $current_page == 'view_application' ||  
                     $current_page == 'rejected_enrollments') ? 'active' : '';


$clients_links = ($current_page == 'parents' ||
                 $current_page == 'children') ? 'active' : '';

$reports_links = (
    
    $current_page == 'clientsheet' ||
     $current_page == 'activities' ||
    $current_page == 'reports' ) ? 'active' : '';

?>

<style type="text/css">


    .simplebar-sc {
  height: 80%;  
  overflow-y: scroll;
}

.simplebar-sc::-webkit-scrollbar {
  width: 4px; 
}

.simplebar-sc::-webkit-scrollbar-thumb {
  background-color: #888; 
  border-radius: 4px;      
}

.simplebar-sc::-webkit-scrollbar-thumb:hover {
  background-color: #555; 
}

.simplebar-sc::-webkit-scrollbar-track {
  background: #f1f1f1; 
  border-radius: 10px;   
}

</style>

<div class="sidebar">
    <div>
        <div class="sidebar-close" id="sidebar-close" style="height: 100px;">
            <i class='bx bx-left-arrow-alt' style="position: absolute;right: 10px;top: 10px;"></i>
        </div>
        <a href="index" style="display: flex; justify-content: center;">
            <img src="../../img/horizontallogo.png" style="width:80%; height: 100px;margin:30px auto;">
        </a>
    </div>
    <div class="simplebar-sc" data-simplebar>
        <ul class="sidebar-menu tf">

            <li class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">
                <a href="index">
                    <i class='bx bx-home'></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'Appointments') ? 'active' : ''; ?>">
                <a href="appointments">
                      <i class='bx bx-walk'></i>
                    <span>Appointments</span>
                     <div id="appointmentseencount" style="margin-left:10px">
        
    </div>
                </a>
            </li>

            <li class="sidebar-submenu <?php echo $enrollment_links; ?>">
                <a href="#" class="sidebar-menu-dropdown">
                    <i class='bx bx-user-plus'></i>
                    <span>Enrollments  
</span>
    
    <div id="pendingcount" style="margin-left:10px">
        
    </div>
                    <div class="dropdown-icon"><i class='bx bx-chevron-down'></i> </div>
                </a>
                <ul class="sidebar-menu sidebar-menu-dropdown-content <?php echo $enrollment_links; ?>">
                    <li><a href="all_enrollments" class="<?php echo ($current_page == 'all_enrollments') ? 'active' : ''; ?>">All</a></li>
                    <li><a href="accepted_enrollments" class="<?php echo ($current_page == 'accepted_enrollments') ? 'active' : ''; ?>">Accepted</a></li>
                    <li><a href="pending_enrollments" class="<?php echo ($current_page == 'pending_enrollments') ? 'active' : ''; ?>">Pending <span id="pendingcount2" style="font-size: 14px;"> </span></a></li>
                    <li><a href="rejected_enrollments" class="<?php echo ($current_page == 'rejected_enrollments') ? 'active' : ''; ?>">Rejected</a></li>
                </ul>
            </li>

            <li class="sidebar-submenu <?php echo $clients_links; ?>">
                <a href="#" class="sidebar-menu-dropdown">
                    <i class='bx bx-group'></i>
                    <span>Clients</span>
                    <div class="dropdown-icon"><i class='bx bx-chevron-down'></i></div>
                </a>
                <ul class="sidebar-menu sidebar-menu-dropdown-content <?php echo $clients_links; ?>">
                    <li><a href="parents" class="<?php echo ($current_page == 'parents') ? 'active' : ''; ?>">Parents</a></li>
                    <li><a href="children" class="<?php echo ($current_page == 'children') ? 'active' : ''; ?>">Children</a></li>
                </ul>
            </li>

            <li class="<?php echo ($current_page == 'notifications') ? 'active' : ''; ?>">
                <a href="notifications">
                    <i class='bx bx-bell'></i>
                    <span>Notifications</span>
                </a>
            </li>

       
           <li class="<?php echo ($current_page == 'messages') ? 'active' : ''; ?>" >
                <a href="messages" style="display: flex;justify-content: space-between;">
                    <div>
                    <i class='bx bx-message'></i>
                    <span style="padding-left: 10px;">Messages</span>
                </div>

    <div id="unseenCount">
        
    </div>

                </a>

            </li>

            <li class="<?php echo ($current_page == 'attendance') ? 'active' : ''; ?>">
                <a href="attendance">
                    <i class='bx bx-check-circle'></i>
                    <span>Attendance</span>
                </a>
            </li>

        
            <li class="sidebar-submenu <?php echo $reports_links; ?>">
                <a href="#" class="sidebar-menu-dropdown">
                    <i class='bx bx-collection'></i>
                    <span>Reports</span>
                    <div class="dropdown-icon"><i class='bx bx-chevron-down'></i></div>
                </a>
                <ul class="sidebar-menu sidebar-menu-dropdown-content <?php echo $reports_links; ?>">
                    <li><a href="clientsheet" class="<?php echo ($current_page == 'clientsheet') ? 'active' : ''; ?>">Clients' Sheet</a></li>
                    <li><a href="activities" class="<?php echo ($current_page == 'activities') ? 'active' : ''; ?>">Activity Reports</a></li>
                    <li><a href="reports" class="<?php echo ($current_page == 'reports') ? 'active' : ''; ?>">Incident Reports</a></li>
                    <li><a href="monthlyattendance" class="<?php echo ($current_page == 'monthlyattendance') ? 'active' : ''; ?>">Monthly Attendance</a></li>
                </ul>
            </li>


          

            <li>
                <a href="contactus">
                    <i class='bx bx-envelope'></i>
                    <span>Contact Us</span>
                     <div id="contactusseen" style="margin-left:10px">
        
    </div>
                </a>
            </li>

            <li>
                <a class="darkmode-toggle" id="darkmode-toggle" onclick="switchTheme()">
                    <div>
                        <i class='bx bx-cog mr-10'></i>
                        <span>darkmode</span>
                    </div>

                    <span class="darkmode-switch"></span>
                </a>
            </li>

            <li>
                <a href="logout">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>


<script type="text/javascript">
    

    function updateUnseenCount() {
            fetch('teacherunseen.php')
                .then(response => response.json())
                .then(data => {
                     const unseenCount = data.unseenCount;

                    const unseenCountElement = document.getElementById('unseenCount');

                    if (unseenCount == 0) {
                        unseenCountElement.style.display = 'none';
                    } else {

                        unseenCountElement.style.display = 'inline-block'; 
                        unseenCountElement.style.float = 'right';
                unseenCountElement.style.alignSelf = 'right';
                unseenCountElement.style.background = '#EF5741';
                unseenCountElement.style.borderRadius = '100%';
                unseenCountElement.style.height = '25px';
                unseenCountElement.style.width = '25px';
                unseenCountElement.style.paddingTop = '1px';
                unseenCountElement.style.textAlign = 'center';
                unseenCountElement.style.fontSize = '15px';
                unseenCountElement.style.color = 'white';
                        unseenCountElement.textContent = unseenCount; 
                    }
                })
                .catch(error => {
                    console.error('Error fetching unseen count:', error);
                });
        }

        setInterval(updateUnseenCount, 100);



        function pendingcount() {
            fetch('pendingcount.php')
                .then(response => response.json())
                .then(data => {
                     const unseenCount = data.unseenCount;

                    const unseenCountElement = document.getElementById('pendingcount');
                    const unseenCountElement2 = document.getElementById('pendingcount2');

                    if (unseenCount == 0) {
                        unseenCountElement.style.display = 'none';
                    } else {

                        unseenCountElement.style.display = 'inline-block'; 
                        unseenCountElement.style.float = 'right';
                unseenCountElement.style.alignSelf = 'right';
                unseenCountElement.style.background = '#EF5741';
                unseenCountElement.style.borderRadius = '100%';
                unseenCountElement.style.height = '10px';
                unseenCountElement.style.width = '10px';
                unseenCountElement.style.paddingTop = '1px';
                unseenCountElement.style.textAlign = 'center';
                unseenCountElement.style.fontSize = '15px';
                unseenCountElement.style.color = 'white';



                       unseenCountElement2.style.color = 'white';
unseenCountElement2.style.marginLeft = '5px';
unseenCountElement2.style.background = '#EF5741';
unseenCountElement2.style.height = '20px';
unseenCountElement2.style.width = '20px';
unseenCountElement2.style.borderRadius = '100%';
unseenCountElement2.style.textAlign = 'center';

                        unseenCountElement2.textContent = unseenCount; 
                    }
                })
                .catch(error => {
                    console.error('Error fetching unseen count:', error);
                });
        }

        setInterval(pendingcount, 100);





         function appointmentseencount() {
            fetch('appointmentseencount.php')
                .then(response => response.json())
                .then(data => {
                     const unseenCount = data.unseenCount;

                    const unseenCountElement = document.getElementById('appointmentseencount');

                    if (unseenCount == 0) {
                        unseenCountElement.style.display = 'none';
                    } else {


 unseenCountElement.style.display = 'inline-block'; 
                        unseenCountElement.style.float = 'right';
                unseenCountElement.style.alignSelf = 'right';
                unseenCountElement.style.background = '#EF5741';
                unseenCountElement.style.borderRadius = '100%';
                unseenCountElement.style.height = '10px';
                unseenCountElement.style.width = '10px';
                unseenCountElement.style.paddingTop = '1px';
                unseenCountElement.style.textAlign = 'center';
                unseenCountElement.style.fontSize = '15px';
                unseenCountElement.style.color = 'white';

                    }
                })
                .catch(error => {
                    console.error('Error fetching unseen count:', error);
                });
        }

        setInterval(appointmentseencount, 100);



           function contactusseen() {
            fetch('contactusseen.php')
                .then(response => response.json())
                .then(data => {
                     const unseenCount = data.unseenCount;

                    const unseenCountElement = document.getElementById('contactusseen');

                    if (unseenCount == 0) {
                        unseenCountElement.style.display = 'none';
                    } else {


 unseenCountElement.style.display = 'inline-block'; 
                        unseenCountElement.style.float = 'right';
                unseenCountElement.style.alignSelf = 'right';
                unseenCountElement.style.background = '#EF5741';
                unseenCountElement.style.borderRadius = '100%';
                unseenCountElement.style.height = '10px';
                unseenCountElement.style.width = '10px';
                unseenCountElement.style.paddingTop = '1px';
                unseenCountElement.style.textAlign = 'center';
                unseenCountElement.style.fontSize = '15px';
                unseenCountElement.style.color = 'white';

                    }
                })
                .catch(error => {
                    console.error('Error fetching unseen count:', error);
                });
        }

        setInterval(contactusseen, 100);



        
</script>