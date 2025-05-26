<?php
session_start();

if (isset($_GET['index']) && is_numeric($_GET['index'])) {
    $index = $_GET['index'];

    if (isset($_SESSION['donasi'][$index])) {
        array_splice($_SESSION['donasi'], $index, 1);
    }
}

header("Location: hasil-form-donasi.php");
exit;
