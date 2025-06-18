<?php
session_start();
include 'koneksi.php';
include 'pages/auth/keamanan.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_donasi = mysqli_real_escape_string($conn, $_POST['id_donasi']);
    $donasi_user = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $tgl_donasi = date('Y-m-d H:i:s');

    // Ambil id_user dari session login
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
    } else {
        echo "<script>alert('Anda harus login terlebih dahulu.'); window.location.href='login.php';</script>";
        exit;
    }

    // Simpan ke tabel transaksi_donasi saja
    $insert = mysqli_query($conn, "INSERT INTO transaksi_donasi (tgl_donasi, donasi_user, metode_pembayaran, id_donasi, id_user) 
                                   VALUES ('$tgl_donasi', '$donasi_user', '$metode_pembayaran', '$id_donasi', '$id_user')");

    if ($insert) {
        echo "<script>alert('Donasi berhasil disimpan! Terima kasih.'); window.location.href='percobaan.php?id=$id_donasi';</script>";
    } else {
        echo "Terjadi kesalahan saat menyimpan data: " . mysqli_error($conn);
    }
} else {
    echo "Akses tidak sah.";
}
?>
