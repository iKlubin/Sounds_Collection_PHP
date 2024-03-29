<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('isUserAuthenticated')) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function isUserAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('redirectToLogin')) {
    function redirectToLogin() {
        header('Location: login.php');
        exit();
    }
}

if (!function_exists('isAdmin')) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function isAdmin() {
        return isset($_SESSION['is_admin']);
    }
}
?>
