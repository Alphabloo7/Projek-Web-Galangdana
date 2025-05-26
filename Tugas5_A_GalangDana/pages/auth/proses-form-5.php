
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donasi = [
        "judul" => htmlspecialchars($_POST['name']),
        "deskripsi" => htmlspecialchars($_POST['description']),
        "tanggal" => htmlspecialchars($_POST['date']),
        "jenis" => htmlspecialchars($_POST['types']),
        "target" => isset($_POST['target']) ? $_POST['target'] : [],
        "nominal" => htmlspecialchars($_POST['category']),
        "status" => "sukses"
    ];

    if (!isset($_SESSION['donasi'])) {
        $_SESSION['donasi'] = [];
    }

    $_SESSION['donasi'][] = $donasi;

    header("Location: berhasil.php");
    exit();
}
