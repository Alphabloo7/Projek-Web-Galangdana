<?php
include 'koneksi.php';

// koneksi ke database

// Validasi input
$paket = trim($_POST['paket'] ?? '');
$total = intval($_POST['total'] ?? 0);
$metode = trim($_POST['metode'] ?? '');

if (empty($paket) || empty($metode) || $total <= 0) {
    echo "Data tidak valid. Silakan ulangi pengisian.";
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO transaksi (paket, total, metode_pembayaran) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $paket, $total, $metode);

if ($stmt->execute()) {
    echo "Transaksi berhasil disimpan!";
} else {
    echo "Gagal menyimpan transaksi: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>
