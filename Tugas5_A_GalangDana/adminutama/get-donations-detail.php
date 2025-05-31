<?php
header('Content-Type: application/json');
include '../koneksi.php';

if (!isset($_GET['id_donasi']) || !is_numeric($_GET['id_donasi'])) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$id = intval($_GET['id_donasi']);

// Pastikan $conn adalah mysqli object (OOP)
$stmt = $conn->prepare("
    SELECT d.judul_donasi, d.isi_donasi, d.gambar, d.bentuk_donasi, d.target_donasi, d.tgl_unggah, k.jenis_kategori AS kategori
    FROM donasi d
    JOIN kategori_donasi k ON d.id_kategori = k.id_kategori
    WHERE d.id_donasi = ?
");
if (!$stmt) {
    echo json_encode(['error' => 'Prepare statement error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $donation = $result->fetch_assoc();
    echo json_encode($donation);
} else {
    echo json_encode(['error' => 'Donasi tidak ditemukan']);
}

$stmt->close();
$conn->close();
exit;
