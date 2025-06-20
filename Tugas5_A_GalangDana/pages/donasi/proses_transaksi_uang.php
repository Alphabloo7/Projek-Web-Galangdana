<?php
// Pastikan ini adalah baris paling awal
session_start();
header('Content-Type: application/json'); // Penting untuk response JSON

include '../../koneksi.php'; // Sesuaikan path koneksi Anda
include '../auth/keamanan.php'; // Sertakan file keamanan.php Anda (misal: cek login admin)

// Pastikan user adalah admin atau memiliki hak akses yang sesuai
// Asumsi 'keamanan.php' sudah melakukan redirect jika user tidak punya akses.
// Jika tidak, tambahkan pengecekan $_SESSION['id_admin'] di sini.
if (!isset($_SESSION['id_admin'])) { // Sesuaikan dengan variabel sesi admin Anda
    $response = ['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini.'];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$response = ['success' => false, 'message' => ''];
$uploaded_gambar_path = ''; // Untuk menyimpan path gambar yang diupload sementara

try {
    // Validasi request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Metode permintaan tidak valid.");
    }

    // Ambil ID Admin dari sesi (asumsi admin yang membuat donasi)
    $id_admin_pembuat = $_SESSION['id_admin'] ?? 1; // Default ke 1 jika tidak ada (untuk debugging, sesuaikan)
    $id_mitra_default = null; // Asumsi tidak ada mitra terkait langsung dari form ini

    // Ambil dan validasi data input
    $judul = trim($_POST['judul_donasi'] ?? '');
    $isi = trim($_POST['isi_donasi'] ?? '');
    $tgl_unggah = trim($_POST['tgl_unggah'] ?? ''); // Nama variabel konsisten dengan DB
    $kategori = intval($_POST['id_kategori'] ?? 0);
    $status_donasi = trim($_POST['status_donasi'] ?? 'Non Active'); // Default 'Non Active'
    $bentuk = trim($_POST['bentuk_donasi'] ?? '');

    // Validasi dasar
    if (empty($judul)) throw new Exception("Judul donasi harus diisi.");
    if (empty($isi)) throw new Exception("Deskripsi donasi harus diisi.");
    if (empty($tgl_unggah)) throw new Exception("Tanggal peluncuran harus diisi.");
    if (!in_array($bentuk, ['uang', 'barang'])) throw new Exception("Bentuk donasi tidak valid.");
    if ($kategori <= 0 || $kategori > 6) throw new Exception("Kategori donasi tidak valid.");

    // Validasi Tanggal
    $date_obj = DateTime::createFromFormat('Y-m-d', $tgl_unggah);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $tgl_unggah) {
        throw new Exception("Format tanggal tidak valid (YYYY-MM-DD).");
    }
    $today = new DateTime();
    if ($date_obj < $today->setTime(0, 0, 0)) { // Set waktu ke 00:00:00 untuk perbandingan tanggal saja
        throw new Exception("Tanggal peluncuran tidak boleh di masa lalu.");
    }

    // Proses unggah gambar
    $gambar_nama_file = ''; // Nama file gambar yang akan disimpan di DB
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error saat mengunggah file: Kode error " . $file['error']);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("Format file gambar tidak didukung. Gunakan JPG, PNG, atau GIF.");
        }
        if ($file['size'] > $max_size) {
            throw new Exception("Ukuran file gambar terlalu besar. Maksimal 2MB.");
        }

        // Direktori upload relatif terhadap file ini
        // Asumsi struktur: my_project/pages/donasi/process/
        // Direktori upload yang diinginkan: my_project/uploads/
        $upload_dir = realpath(__DIR__ . '/../../uploads/'); // Pastikan folder 'uploads' ada di root project
        if (!$upload_dir || !is_dir($upload_dir)) {
            throw new Exception("Direktori upload tidak ditemukan atau tidak dapat diakses: " . $upload_dir);
        }
        if (!is_writable($upload_dir)) {
            throw new Exception("Direktori upload tidak dapat ditulisi: " . $upload_dir);
        }

        // Generate nama file unik (prefix 'donasi_' + timestamp + unique ID + ekstensi)
        $gambar_nama_file = 'donasi_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . DIRECTORY_SEPARATOR . $gambar_nama_file; // Menggunakan DIRECTORY_SEPARATOR untuk kompatibilitas OS

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Gagal memindahkan file gambar yang diunggah.");
        }
        $uploaded_gambar_path = 'uploads/' . $gambar_nama_file; // Path relatif untuk disimpan di DB
    }

    // Mulai transaksi database untuk memastikan atomisitas
    $conn->begin_transaction();

    // --- Insert main donation record ke tabel 'donasi' ---
    // Pastikan urutan dan tipe data parameter sesuai dengan kolom tabel 'donasi' Anda
    // Kolom: judul_donasi, isi_donasi, tgl_unggah, gambar, status_donasi, bentuk_donasi, id_kategori, id_admin, id_mitra
    $stmt_donasi_utama = $conn->prepare("INSERT INTO donasi (judul_donasi, isi_donasi, tgl_unggah, gambar, status_donasi, bentuk_donasi, id_kategori, id_admin, id_mitra) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt_donasi_utama) {
        throw new Exception("Gagal menyiapkan statement utama: " . $conn->error);
    }

    $stmt_donasi_utama->bind_param(
        "ssssssiii",
        $judul,
        $isi,
        $tgl_unggah,
        $uploaded_gambar_path, // Path gambar yang akan disimpan di DB
        $status_donasi,
        $bentuk,
        $kategori,
        $id_admin_pembuat,
        $id_mitra_default // Jika tidak ada mitra, bisa NULL atau default lain
    );

    if (!$stmt_donasi_utama->execute()) {
        throw new Exception("Gagal menyimpan data donasi utama: " . $stmt_donasi_utama->error);
    }

    $id_donasi_baru = $conn->insert_id; // Dapatkan ID donasi yang baru dibuat

    // --- Proses berdasarkan bentuk donasi ---
    if ($bentuk === 'uang') {
        $target_uang = intval($_POST['target_donasi'] ?? 0);

        if ($target_uang < 1000) {
            throw new Exception("Target uang minimal Rp 1.000.");
        }

        $stmt_uang = $conn->prepare("INSERT INTO donasi_uang (id_donasi, target_uang, terkumpul_uang) VALUES (?, ?, 0)");
        if (!$stmt_uang) {
            throw new Exception("Gagal menyiapkan statement donasi uang: " . $conn->error);
        }

        $stmt_uang->bind_param("ii", $id_donasi_baru, $target_uang);
        if (!$stmt_uang->execute()) {
            throw new Exception("Gagal menyimpan data donasi uang: " . $stmt_uang->error);
        }
        $stmt_uang->close();
    } elseif ($bentuk === 'barang') {
        $jenis_paket = trim($_POST['jenis_paket'] ?? '');
        $daftar_barang = trim($_POST['daftar_barang'] ?? ''); // String nama barang dipisahkan koma
        $daftar_harga = trim($_POST['daftar_harga'] ?? ''); // String harga barang dipisahkan koma

        // Target paket (jumlah barang dalam paket)
        $target_paket_count = 0;
        if ($jenis_paket === 'istimewa') {
            $target_paket_count = 3;
        } elseif ($jenis_paket === 'biasa') {
            $target_paket_count = 2;
        } else {
            throw new Exception("Jenis paket barang tidak valid.");
        }

        // Validasi data barang dan harga
        $barang_arr = array_map('trim', explode(',', $daftar_barang));
        $harga_arr = array_map('trim', explode(',', $daftar_harga));

        // Pastikan jumlah barang dan harga sesuai dengan target paket yang dipilih
        if (count($barang_arr) !== $target_paket_count || count($harga_arr) !== $target_paket_count) {
            throw new Exception("Jumlah barang atau harga tidak sesuai dengan jenis paket yang dipilih.");
        }

        $harga_total_paket = 0; // Ini adalah harga untuk satu "paket" lengkap
        foreach ($harga_arr as $h) {
            $harga = intval($h);
            if ($harga < 1000) {
                throw new Exception("Setiap harga barang minimal Rp 1.000.");
            }
            $harga_total_paket += $harga;
        }

        if ($harga_total_paket <= 0) {
            throw new Exception("Total harga paket tidak valid.");
        }

        // Insert ke tabel donasi_barang
        // Perhatikan kolom `daftar_barang` di DB Anda harus bertipe TEXT atau VARCHAR yang cukup besar
        // Kolom: id_donasi, jenis_paket, target_paket (ini adalah total unit paket yang ingin terkumpul), harga_paket (ini adalah total harga 1 unit paket), terkumpul_paket, daftar_barang
        $stmt_barang = $conn->prepare("INSERT INTO donasi_barang (id_donasi, jenis_paket, target_paket, harga_paket, terkumpul_paket, daftar_barang) VALUES (?, ?, ?, ?, 0, ?)");

        if (!$stmt_barang) {
            throw new Exception("Gagal menyiapkan statement donasi barang: " . $conn->error);
        }

        // Parameter: id_donasi (i), jenis_paket (s), target_paket (i), harga_paket (i), terkumpul_paket (i, always 0), daftar_barang (s)
        // target_paket di sini seharusnya adalah JUMLAH unit paket yang ditargetkan, bukan jumlah barang dalam paket.
        // Jika target_paket di DB itu JUMLAH unit paket, maka ambil dari form, bukan dari $target_paket_count
        // Asumsi: target_paket di form barang adalah jumlah unit paket yang ingin terkumpul
        $target_paket_form = intval($_POST['target_paket'] ?? 1); // Ambil dari input tersembunyi/khusus di form barang jika ada
        if ($target_paket_form <= 0) {
            throw new Exception("Target jumlah paket harus lebih dari 0.");
        }


        // Re-adjust binding param for donasi_barang
        // Assuming 'target_paket' from $_POST is the intended total number of packages to be collected
        $stmt_barang->bind_param(
            "isiss",
            $id_donasi_baru,
            $jenis_paket,
            $target_paket_form, // Ini harusnya target berapa BANYAK paket yang ingin terkumpul (misal 100 paket)
            $harga_total_paket, // Ini adalah harga untuk SATU paket
            $daftar_barang
        );

        if (!$stmt_barang->execute()) {
            throw new Exception("Gagal menyimpan data donasi barang: " . $stmt_barang->error);
        }
        $stmt_barang->close();
    }

    // Commit transaksi jika semua operasi berhasil
    $conn->commit();

    $response['success'] = true;
    $response['message'] = 'Donasi berhasil ditambahkan!';
    $response['id_donasi'] = $id_donasi_baru;
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    if ($conn && $conn->in_transaction) { // Cek apakah transaksi sedang berjalan
        $conn->rollback();
    }

    // Hapus gambar yang sudah diunggah jika terjadi kesalahan setelah upload
    if (!empty($uploaded_gambar_path) && file_exists(__DIR__ . '/../../' . $uploaded_gambar_path)) {
        unlink(__DIR__ . '/../../' . $uploaded_gambar_path);
    }

    $response['success'] = false;
    $response['message'] = $e->getMessage();

    // Log error untuk debugging (lihat di log server web Anda)
    error_log("Donation form processing error: " . $e->getMessage());
} finally {
    // Pastikan koneksi database ditutup
    if ($conn) {
        $conn->close();
    }
}

// Kirim response JSON ke frontend
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
