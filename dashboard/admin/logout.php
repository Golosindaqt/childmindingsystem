<?php

session_start();

unset($_SESSION['teacher_logged_teacher_id']);
unset($_SESSION['teacher_logged_user_id']);
unset($_SESSION['teacher_logged_username']);
unset($_SESSION['teacher_logged_email']);

header('Location: login.php');
exit;
?>