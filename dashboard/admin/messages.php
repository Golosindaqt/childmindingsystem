<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['teacher_logged_email'])) {

    $teacherEmail = $_SESSION['teacher_logged_email'];
    $teacherUserId = $_SESSION['teacher_logged_user_id'];
    $teacherId = $_SESSION['teacher_logged_teacher_id'];
    $teacherUsername = $_SESSION['teacher_logged_username'];

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
         <script>
  function updateDateTime() {
            const now = new Date();
            const xx = 0 * 60 * 60 * 1000;
            const adjustedTime = new Date(now.getTime() + xx);
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            const datePart = adjustedTime.toLocaleDateString('en-PH', options);
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const timePart = adjustedTime.toLocaleTimeString('en-PH', timeOptions);
            const formattedDate = `${datePart} at ${timePart}`;
            document.getElementById('dateInput').value = formattedDate;

        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
    <?php include("head.php"); ?>

    <style>

        #messages-container {
            max-height: 500px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        #messages-container::-webkit-scrollbar {
            width: 2px;
        }

        #messages-container::-webkit-scrollbar-thumb {
            background: #FFF8E5;
            border-radius: 10px;
        }

        #messages-container::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</head>

<body class="sidebar-expand">

    <?php include("leftsidebar.php"); ?>
    <?php include("header.php"); ?>

    <div class="main">
        <div class="main-content message">
            <div class="row">
                <div class="col-4 col-md-12">
                    <div class="row">
                        <div class="col-12 mb-0">
                            <div class="box box-message">
                                   <h4 class="card-title">Messages</h4>

                                <div class="input-group search-area" style="margin-top:20px">
                                    <span class="input-group-text">
                                        <a href="javascript:void(0)">
                                            <i class="bx bx-search"></i>
                                        </a>
                                    </span>    <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeyup="searchUsers()">

                                </div>

                         <div class="box-content" style="margin-top: 10px; overflow-y:auto;">

<ul class="message-list" id="userList">
    <?php
    include '../../db_conn.php';

    if (isset($_SESSION['teacher_logged_email'])) {
        $teacherEmail = $_SESSION['teacher_logged_email'];
        $teacherUserId = $_SESSION['teacher_logged_user_id'];
        $teacherId = $_SESSION['teacher_logged_teacher_id'];
        $teacherUsername = $_SESSION['teacher_logged_username'];
        
        $query = "
            SELECT u.username, u.email, p.mother_name, p.father_name, u.user_id, p.*
            FROM user u
            INNER JOIN parental_information p ON u.email = p.email
            WHERE u.role_id = 2 
              AND u.username IS NOT NULL 
              AND u.username <> ''
        ";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Query failed: ' . mysqli_error($conn));
        }

        $uniqueResults = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if (!isset($uniqueResults[$row['email']])) {
                $uniqueResults[$row['email']] = $row;
            }
        }

        foreach ($uniqueResults as $row):
            $userId = $row['user_id'];
            $unseenQuery = "
                SELECT COUNT(*) AS unseen_count 
                FROM notification 
                WHERE type = 'msg' 
                  AND teacher_id = '$teacherUserId' 
                  AND teacherseen = 'no'
                  AND user_id = '$userId'
            ";

            $unseenResult = mysqli_query($conn, $unseenQuery);
            if (!$unseenResult) {
                die('Query failed: ' . mysqli_error($conn));
            }

            $unseenCountRow = mysqli_fetch_assoc($unseenResult);
            $unseenCount = $unseenCountRow['unseen_count'];
    ?>


        <li class="waves-effect waves-teal user-item" style="margin-top: 10px;width: 100%" 
            data-username="<?php echo htmlspecialchars($row['username']); ?>" 
            data-mothername="<?php echo htmlspecialchars($row['mother_name']); ?>" 
            data-fathername="<?php echo htmlspecialchars($row['father_name']); ?>" 
            data-email="<?php echo htmlspecialchars($row['email']); ?>">
            <div class="left d-flex">

               <?php if ($unseenCount > 0): ?>
                <div style="position: absolute;right: 10px; top: 10px; text-align: center;padding-top: 1px;color:white; background: red;height: 25px;width: 25px;border-radius: 100%;">
                 <?php echo ($unseenCount > 0) ? "" . $unseenCount : "0"; ?>
             </div>
                 <?php endif; ?>

                <div class="avatar">
                    <img src="../../img/favicon.png" alt="">
                </div>
                <div class="content">
                    <div class="username">
                        <div class="name h6">
                            <div class="parent_user_id" style="position:absolute;left: -5000000000000px">
                                <?php echo htmlspecialchars($row['user_id']); ?>
                            </div>
                            <div class="mothernamediv"><?php echo htmlspecialchars($row['mother_name']); ?></div>
                            <div class="fathernamediv"><?php echo htmlspecialchars($row['father_name']); ?></div>
                          
                        </div>
                    </div>
                    <div class="text">
                        <p><?php echo htmlspecialchars($row['email']); ?></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </li>
    <?php endforeach; ?>
    <?php
    }
    ?>
</ul>



</div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-8 col-md-12">
                    <div class="box message-info">
                        <div class="box-info-messager">
                            <div class="message-pic"></div>
                            <div class="content">
                                <div class="username">
                                    <h5 class="fs-18" id="recipientname"></h5>
                                </div>
                                <div class="text">
                                    <p class="fs-14" id="recipientemail"></p>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div id="messages-container"></div>

                        <div class="form-chat" style="">
                            <form id="notificationForm" method="post" accept-charset="utf-8">
        <input type="text" name="user_id" id="user_id" hidden>
        <input type="text" name="teacher_id" value="<?php echo $teacherId; ?>" hidden>
        <input type="text" name="datesent" id="dateInput" hidden>

        <div class="message-form-chat" style="position:relative;">
            <span class="message-text" style="width: 70%;">
                <textarea name="message" id="message" placeholder="Type your message..." required></textarea>
            </span>

            <span class="btn-send" style="position: absolute;right: 15px; top: 10px;">
                <button class="waves-effect" type="button" id="sendMessageButton">Send <i class="fas fa-paper-plane"></i></button>
            </span>
        </div>
    </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="overlay"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script type="text/javascript">

 

       $(document).ready(function() {
    let pollingInterval;

    function fetchMessages() {
        const email = localStorage.getItem('email');
        if (!email) return; 

        $.ajax({
            url: 'get_msg.php',
            type: 'POST',
            data: { email: email },
            success: function(response) {
                try {
            const msgs = JSON.parse(response);

            if (msgs.success && Array.isArray(msgs.data) && msgs.data.length > 0) {
                document.getElementById('user_id').value = msgs.data[0].user_id;
                localStorage.setItem('msgs', JSON.stringify(msgs));
                renderMessages(msgs.data);

            } else if (msgs.error) {
                console.log(msgs.error);  

                alert('No messages found or there was an error: ' + msgs.error);
            } else {
               localStorage.removeItem('msgs');
        $('#messages-container').html('<p>No messages available.</p>');
            }
        } catch (e) {
            console.error("Failed to parse message data.", e);
            alert('Failed to retrieve messages. Please try again later.');
        }try {
                    const msgs = JSON.parse(response);
                    if (msgs.success && Array.isArray(msgs.data)) {
                        document.getElementById('user_id').value = msgs.data[0].user_id;
                        localStorage.setItem('msgs', JSON.stringify(msgs));
                        renderMessages(msgs.data);
                    }
                } catch (e) {
                    console.error("Failed to parse message data.", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("There was an error fetching messages: " + error);
            }
        });
    }

setInterval(fetchMessages, 100);











    $('#sendMessageButton').click(function() {
        const messagesContainer = document.getElementById('messages-container');
        const message = document.getElementById('message').value;
        var formData = $('#notificationForm').serialize();

        if (message.trim() === '') {
            alert('Please enter a message before sending.');
            return;
        }

        $.ajax({
            url: 'send_msg.php',
            type: 'POST',
            data: formData,
            success: function(response) {

                setTimeout(() => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 100);

                if (!pollingInterval) {
                    fetchMessages(); 
                    pollingInterval = setInterval(fetchMessages, 100); 
                }

                setTimeout(function() {
    window.location.reload();
}, 1000);

            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });



    function handleClick() {
        $('ul.message-list li').removeClass('active');
        $(this).addClass('active');

        var parent_user_id = $(this).find('.parent_user_id').text().trim();

        var motherName = $(this).find('.mothernamediv').text().trim();
        var fatherName = $(this).find('.fathernamediv').text().trim();
        var email = $(this).find('.text p').text().trim();

        var motherLastName = motherName.split(' ').pop();
        var fatherLastName = fatherName.split(' ').pop();

        var recipientName = "Mrs. " + motherLastName + " | " + "Mr. " + fatherLastName;

        localStorage.setItem('mother_name', motherName);
        localStorage.setItem('father_name', fatherName);
        localStorage.setItem('email', email);
        localStorage.setItem('recipient_name', recipientName);

        document.getElementById('user_id').value = parent_user_id;

        $('#recipientname').text(recipientName);
        $('#recipientemail').text(email);

               const messagesContainer = document.getElementById('messages-container');

         setTimeout(() => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 1000);

        fetchMessages(); 
    }









    $('ul.message-list li').click(handleClick);
    $('ul.message-list li').first().click();

    
function renderMessages(messages) {
    console.log('Render Messages:', messages);
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';

    const lastMessageIndex = messages.length - 1; 

    messages.forEach((msg, index) => {
        const messageBox = document.createElement('div');
        const timeAgo = formatTimeAgo(msg.datesent);

        const parentSeenText = msg.parentseen === 'yes' ? 'Seen' : 'Delivered';

        if (msg.from === 'parent' && msg.message.trim() !== '') {
            messageBox.classList.add('message-left');
            messageBox.innerHTML = `
                <div class="message-in">
                    <div class="message-body">
                        <div class="message-text">
                            <p>${msg.message}</p>
                        </div>
                        <div class="message-meta">
                            <p>${timeAgo}</p>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            `;
        }

        if (msg.from === 'teacher' && msg.message.trim() !== '') {
            messageBox.classList.add('message-right');
            messageBox.innerHTML = `
                <div class="message-out">
                    <div class="message-body">
                        <div class="message-text">
                            <p>${msg.message}</p>
                        </div>
                        <div class="message-meta" style="text-align:right">
                            <p>${timeAgo}  ${
                                index === lastMessageIndex ? `<br> ${parentSeenText}` : ''
                            }</p>
                        </div>
                    </div>
                </div>
            `;
        }

        messagesContainer.appendChild(messageBox);
    });
}


function formatTimeAgo(datesent) {
    const messageDate = new Date(datesent.replace(" at", ","));
    const currentDate = new Date();
    const diffInSeconds = Math.floor((currentDate - messageDate) / 1000);

    if (diffInSeconds < 60) {
        return 'Just now';
    }
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) {
        return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
    }

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) {
        return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
    }

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) {
        return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
    }

    const diffInMonths = Math.floor(diffInDays / 30);
    if (diffInMonths < 12) {
        return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''} ago`;
    }

    const diffInYears = Math.floor(diffInMonths / 12);
    return `${diffInYears} year${diffInYears > 1 ? 's' : ''} ago`;
}



    const msgs = JSON.parse(localStorage.getItem('msgs'));
    if (msgs && msgs.success && Array.isArray(msgs.data)) {
        renderMessages(msgs.data);
    } else {
        localStorage.removeItem('msgs');
        $('#messages-container').html('<p>No messages available.</p>');
    }
});










function searchUsers() {

    const query = document.getElementById('searchInput').value.toLowerCase();

    const users = document.querySelectorAll('.user-item');

    users.forEach(function(user) {

        const username = user.getAttribute('data-username').toLowerCase();
        const motherName = user.getAttribute('data-mothername').toLowerCase();
        const fatherName = user.getAttribute('data-fathername').toLowerCase();
        const email = user.getAttribute('data-email').toLowerCase();

        if (username.includes(query) || motherName.includes(query) || fatherName.includes(query) || email.includes(query)) {

            user.style.display = '';
        } else {

            user.style.display = 'none';
        }
    });
}

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