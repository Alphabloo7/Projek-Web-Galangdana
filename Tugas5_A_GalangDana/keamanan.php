<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: pages/auth/login.php");
    exit();
}
?>