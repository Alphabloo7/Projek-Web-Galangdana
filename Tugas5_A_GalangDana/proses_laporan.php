<?php
session_start();
require_once 'koneksi.php'; // koneksi ke database
include 'pages/auth/keamanan.php';

// Fungsi untuk upload bukti laporan
function uploadBukti($file)
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return null; // tidak ada file, boleh NULL
    }

    if (!in_array($file['type'], $allowedTypes)) {
        die("Tipe file tidak diperbolehkan (harus JPG, PNG, atau GIF).");
    }

    if ($file['size'] > 2 * 1024 * 1024) { // maksimal 2 MB
        die("Ukuran file terlalu besar (maksimal 2MB).");
    }

    $uploadDir = 'uploads_bukti/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // buat folder jika belum ada
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = time() . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    } else {
        die("Gagal mengupload file.");
    }
}

// Ambil data dari form
$judul   = trim($_POST['judul_laporan'] ?? '');
$tanggal = $_POST['tgl_laporan'] ?? date('Y-m-d');
$isi     = trim($_POST['isi_laporan'] ?? '');
$status  = $_POST['status_laporan'] ?? 'Pending';
$id_user = $_SESSION['id_user'];

// Validasi
if ($judul === '' || $isi === '') {
    die("Judul dan isi laporan wajib diisi.");
}

// Upload gambar jika ada
$bukti = uploadBukti($_FILES['bukti_laporan'] ?? null);

// Simpan ke database
$query = "INSERT INTO laporan (judul_laporan, isi_laporan, bukti_laporan, tgl_laporan, status_laporan, id_user)
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare statement gagal: " . $conn->error);
}

$stmt->bind_param("sssssi", $judul, $isi, $bukti, $tanggal, $status, $id_user);
if ($stmt->execute()) {
    echo "<script>alert('Laporan berhasil disimpan!'); window.location.href='index2.php';</script>";
} else {
    echo "Gagal menyimpan laporan: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
