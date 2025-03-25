<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['parent_logged_email']);
unset($_SESSION['parent_logged_user_id']);
unset($_SESSION['parent_logged_username']);

echo "<script>window.location.href='../../login.php'</script>";
exit;
?>
