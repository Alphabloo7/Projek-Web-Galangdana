<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: /Projek-Web-GalangDana/Tugas5_A_GalangDana/index2.php?pesan=belum_login");
    exit();
}
?>
