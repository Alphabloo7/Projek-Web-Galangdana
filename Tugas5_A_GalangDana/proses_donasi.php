<?php
include 'koneksi.php'; // koneksi ke database

$judul     = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$tanggal   = $_POST['tanggal'];
$jenis     = $_POST['jenis'];
$target    = isset($_POST['target']) ? implode(", ", $_POST['target']) : '';
$nominal   = $_POST['nominal'];

// Upload Gambar
$namaFile  = $_FILES['gambar']['name'];
$tmpName   = $_FILES['gambar']['tmp_name'];
$folder    = "uploads/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true); // buat folder jika belum ada
}

$uploadPath = $folder . basename($namaFile);

if (move_uploaded_file($tmpName, $uploadPath)) {
    // Simpan ke database
    $sql = "INSERT INTO form_donasi (judul, deskripsi, gambar, tanggal, jenis, target, nominal) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssssss", $judul, $deskripsi, $namaFile, $tanggal, $jenis, $target, $nominal);

    if ($stmt->execute()) {
        // Tampilkan popup dan redirect
        echo "<script>
                alert('Donasi berhasil disimpan!');
                window.location.href = 'From-5.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data: " . addslashes($stmt->error) . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Gagal mengupload gambar.');
            window.history.back();
          </script>";
}

$koneksi->close();
?>
