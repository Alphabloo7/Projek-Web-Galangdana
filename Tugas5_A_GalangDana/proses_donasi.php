<?php
session_start();
include 'koneksi.php';
include 'pages/auth/keamanan.php';

$response = ['success' => false];

try {
    // Ambil semua input
    $judul = $_POST['judul_donasi'];
    $isi = $_POST['isi_donasi'];
    $tgl = $_POST['tgl_unggah'];
    $kategori = $_POST['id_kategori'];
    $status = $_POST['status'];
    $bentuk = $_POST['bentuk_donasi'];

    // Proses upload gambar
    $gambar = '';
    if ($_FILES['gambar']['name'] != '') {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = 'donasi_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
    }

    // Simpan ke tabel `donasi`
    $stmt = $conn->prepare("INSERT INTO donasi (judul_donasi, isi_donasi, tgl_unggah, gambar, id_kategori, status, bentuk_donasi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $judul, $isi, $tgl, $gambar, $kategori, $status, $bentuk);
    $stmt->execute();

    // Ambil id_donasi yang baru saja dibuat
    $id_donasi = $conn->insert_id;

    // Cek bentuk donasi dan simpan ke tabel sesuai
    if ($bentuk === 'uang') {
        $target_uang = $_POST['jumlah_uang'];
        $stmt2 = $conn->prepare("INSERT INTO donasi_uang (id_donasi, target_uang, terkumpul_uang) VALUES (?, ?, 0)");
        $stmt2->bind_param("ii", $id_donasi, $target_uang);
        $stmt2->execute();
    } else if ($bentuk === 'barang') {
        $jenis_paket = $_POST['jenis_paket'] ?? $_POST['paket_select'];
        $daftar_harga = $_POST['daftar_harga'];
        $target_paket = $jenis_paket === 'istimewa' ? 3 : 2; // Asumsi target 1 donatur 1 paket

        // Hitung total harga paket dari daftar_harga
        $harga_arr = explode(",", $daftar_harga);
        $harga_total = 0;
        foreach ($harga_arr as $h) {
            $harga_total += intval(trim($h));
        }

        $stmt3 = $conn->prepare("INSERT INTO donasi_barang (id_donasi, jenis_paket, target_paket, harga_paket, terkumpul_paket) VALUES (?, ?, ?, ?, 0)");
        $stmt3->bind_param("isii", $id_donasi, $jenis_paket, $target_paket, $harga_total);
        $stmt3->execute();
    }

    $response['success'] = true;

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Return response JSON
header('Content-Type: application/json');
echo json_encode($response);
