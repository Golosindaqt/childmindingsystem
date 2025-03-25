<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['parent_logged_email'], $_SESSION['parent_logged_user_id'], $_SESSION['parent_logged_username'])) {
    $parentEmail = $_SESSION['parent_logged_email'];
    $parentUserId = $_SESSION['parent_logged_user_id'];
    $parentUsername = $_SESSION['parent_logged_username'];
}

include('../../db_conn.php');

$query = "SELECT * FROM teacher LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $teacher_id = $row['teacher_id'];
    $fullname = $row['fullname'];
    $email_address = $row['email_address'];
} else {
    die('Error fetching teacher data: ' . mysqli_error($conn));
}

$updateQuery = "UPDATE notification SET parentseen = 'yes' WHERE user_id = ? AND teacher_id = ? AND type = ? AND parentseen = 'no'";
$stmt = mysqli_prepare($conn, $updateQuery);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iis", $parentUserId, $teacher_id, $type);
    $type = 'msg';
    if (mysqli_stmt_execute($stmt)) {
        echo "";
    } else {
        echo "Error updating notification: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}

mysqli_close($conn);
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
            <div class="row" style="justify-content: center;">

                <div class="col-8 col-md-12">
                    <div class="box message-info">
                        <div class="box-info-messager">
                            <div class="message-pic"></div>
                            <div class="content">
                                <div class="username">
                                    <h5 class="fs-18"><?php echo $fullname; ?></h5>
                                </div>
                                <div class="text">
                                    <p class="fs-14"><?php echo $email_address; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div id="messages-container"></div>
                        <script type="text/javascript">
                            const messagesContainer = document.getElementById('messages-container');
                setTimeout(function() {

                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 1000);
                        </script>

                        <div class="form-chat" style="">

                           <form id="notificationForm" method="post" accept-charset="utf-8">
        <input type="text" name="user_id" value="<?php echo $parentUserId;?>" hidden>
        <input type="text" name="teacher_id" value="<?php echo $teacher_id; ?>" hidden>
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

    <script>

      $(document).ready(function() {

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

                setTimeout(function() {

                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 1000);
            },
            error: function(xhr, status, error) {

                alert('Error: ' + error);
            }
        });
    });
});

       $(function() {
    function get_msg() {

         const parentEmail = "<?php echo $parentEmail; ?>";  

        $.ajax({
            url: 'get_msg.php',
            type: 'POST',
            data: {
                email: parentEmail  
            },
            success: function(response) {
                try {
                    const msgs = JSON.parse(response);
                    if (msgs.success && Array.isArray(msgs.data)) {
                        localStorage.setItem('msgs', JSON.stringify(msgs));
                        renderMessages(msgs.data);
                    } else {
                        localStorage.removeItem('msgs');
                        $('#messages-container').html('<p>No messages available.</p>');
                    }
                } catch (e) {
                    localStorage.removeItem('msgs');
                    $('#messages-container').html('<p>Error: Invalid message data.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("There was an error sending the request: " + error);
                localStorage.removeItem('msgs');
                $('#messages-container').html('<p>Error: Failed to load messages.</p>');
            }
        });
    }

    setInterval(get_msg, 1000);

    get_msg();
});


function renderMessages(messages) {
    console.log('Render Messages:', messages);
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '';

    const lastMessageIndex = messages.length - 1; 

    messages.forEach((msg, index) => {
        const messageBox = document.createElement('div');
        const timeAgo = formatTimeAgo(msg.datesent);

        const teacherSeenText = msg.teacherseen === 'yes' ? 'Seen' : 'Delivered';

        if (msg.from === 'teacher' && msg.message.trim() !== '') {
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

        if (msg.from === 'parent' && msg.message.trim() !== '') {
            messageBox.classList.add('message-right');
            messageBox.innerHTML = `
                <div class="message-out">
                    <div class="message-body">
                        <div class="message-text">
                            <p>${msg.message}</p>
                        </div>
                        <div class="message-meta" style="text-align:right">
                            <p>${timeAgo}  ${
                                index === lastMessageIndex ? `<br> ${teacherSeenText}` : ''
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