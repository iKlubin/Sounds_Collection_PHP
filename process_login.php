<?php
if ($login_failed) {
    $_SESSION['login_error'] = 'Invalid username or password';
    header('Location: login.php');
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}
?>
