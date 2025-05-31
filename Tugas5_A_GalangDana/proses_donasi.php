<?php
include 'koneksi.php'; // koneksi ke database

// Tangkap data dari form
$judul_donasi = $_POST['judul_donasi'];
$tgl_unggah   = $_POST['tgl_unggah'];
$isi_donasi   = $_POST['isi_donasi'];
$target_donasi = $_POST['target_donasi'];
$id_kategori  = $_POST['id_kategori'];

// Bentuk donasi bisa banyak, jadi gabungkan jadi string dengan koma
$bentuk_donasi = isset($_POST['bentuk']) ? implode(", ", $_POST['bentuk']) : '';

// Upload gambar
$namaFile = $_FILES['gambar']['name'];
$tmpName  = $_FILES['gambar']['tmp_name'];
$folder   = "uploads/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$uploadPath = $folder . basename($namaFile);

// Validasi

if ($namaFile && move_uploaded_file($tmpName, $uploadPath)) {
    // Gambar berhasil diupload
} else {
    $namaFile = null;
}

$id_admin = 1;
$id_mitra = 1;
$status_donasi = "Pending";

$sql = "INSERT INTO donasi (judul_donasi, tgl_unggah, isi_donasi, target_donasi, gambar, bentuk_donasi, id_kategori, id_admin, id_mitra, status_donasi)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $koneksi->error);
}

// tipe paramaeter
$stmt->bind_param("sssissiiis", $judul_donasi, $tgl_unggah, $isi_donasi, $target_donasi, $namaFile, $bentuk_donasi, $id_kategori, $id_admin, $id_mitra, $status_donasi);

if ($stmt->execute()) {
    echo "<script>
            alert('Donasi berhasil disimpan!');
            window.location.href = 'index2.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menyimpan data: " . addslashes($stmt->error) . "');
            window.history.back();
          </script>";
}

$stmt->close();
$koneksi->close();
