<?php
require_once 'koneksi.php'; // koneksi database

function uploadGambar($file)
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if (!in_array($file['type'], $allowedTypes)) {
        die("Tipe file tidak diperbolehkan.");
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $uploadDir . time() . '_' . uniqid() . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $targetFile;
    } else {
        die("Gagal upload gambar.");
    }
}

// Ambil dan validasi data form
$judul_donasi = trim($_POST['judul_donasi'] ?? '');
$tgl_unggah = $_POST['tgl_unggah'] ?? date('Y-m-d');
$isi_donasi = trim($_POST['isi_donasi'] ?? '');
$target_donasi = floatval($_POST['target_donasi'] ?? 0);
$bentuk_arr = $_POST['bentuk'] ?? [];
$id_kategori = intval($_POST['id_kategori'] ?? 0);
$status_donasi = 'Non Active';

if (empty($judul_donasi) || empty($isi_donasi) || $target_donasi <= 0 || $id_kategori <= 0) {
    die("Data tidak lengkap atau tidak valid.");
}

$bentuk_donasi = implode(',', $bentuk_arr);
$gambar = uploadGambar($_FILES['gambar'] ?? null);

// Prepare dan eksekusi query
$sql = "INSERT INTO donasi (judul_donasi, tgl_unggah, isi_donasi, target_donasi, gambar, bentuk_donasi, id_kategori, status_donasi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare statement gagal: " . $conn->error);
}

$stmt->bind_param("sssissis", $judul_donasi, $tgl_unggah, $isi_donasi, $target_donasi, $gambar, $bentuk_donasi, $id_kategori, $status_donasi);

if ($stmt->execute()) {
    echo "Donasi berhasil disimpan!";
    // redirect kalau perlu: header("Location: index2.php"); exit;
} else {
    echo "Gagal menyimpan donasi: " . $stmt->error;
}

$stmt->close();
$conn->close();
