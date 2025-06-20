<?php
header('Content-Type: application/json');
include '../koneksi.php';

if (!isset($_GET['id_donasi']) || !is_numeric($_GET['id_donasi'])) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$id = intval($_GET['id_donasi']);

// Ambil data umum dari donasi
$stmt = $conn->prepare("
    SELECT d.judul_donasi, d.isi_donasi, d.gambar, d.bentuk_donasi, d.tgl_unggah, k.jenis_kategori AS kategori
    FROM donasi d
    JOIN kategori_donasi k ON d.id_kategori = k.id_kategori
    WHERE d.id_donasi = ?
");
if (!$stmt) {
    echo json_encode(['error' => 'Prepare error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo json_encode(['error' => 'Donasi tidak ditemukan']);
    exit;
}

$donation = $result->fetch_assoc();
$bentuk = $donation['bentuk_donasi'];

// Ambil target donasi sesuai bentuknya
if ($bentuk === 'uang') {
    $stmt2 = $conn->prepare("SELECT target_uang FROM donasi_uang WHERE id_donasi = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $donation['target_donasi'] = $res2 && $res2->num_rows > 0 ? $res2->fetch_assoc()['target_uang'] : 0;
    $stmt2->close();
} elseif ($bentuk === 'barang') {
    $stmt3 = $conn->prepare("SELECT harga_paket FROM donasi_barang WHERE id_donasi = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    $donation['target_donasi'] = $res3 && $res3->num_rows > 0 ? $res3->fetch_assoc()['harga_paket'] : 0;
    $stmt3->close();
}

echo json_encode($donation, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
exit;
