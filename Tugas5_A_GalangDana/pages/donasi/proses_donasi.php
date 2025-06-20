<?php
// proses_donasi.php

// Aktifkan pelaporan error penuh untuk debugging (HAPUS ATAU NONAKTIFKAN DI LINGKUNGAN PRODUKSI)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

header('Content-Type: application/json');
session_start();

// Inisialisasi variabel respons dan path gambar
$response = ['success' => false, 'message' => ''];
$uploaded_gambar_path = ''; // Path lengkap gambar yang berhasil diunggah

try {
    // Memasukkan file koneksi database dan keamanan
    // Sesuaikan path ini jika struktur folder Anda berbeda
    include '../../koneksi.php'; // Contoh: jika proses_donasi.php ada di admin/donasi/
    include '../auth/keamanan.php'; // Contoh: jika keamanan.php ada di admin/auth/

    // Pastikan koneksi database berhasil diinisialisasi dan tidak ada error
    if (!isset($conn) || !$conn instanceof mysqli || $conn->connect_error) {
        throw new Exception("Koneksi database gagal: " . ($conn->connect_error ?? 'Variabel $conn tidak ditemukan atau bukan objek mysqli.'));
    }

    // Validasi metode request harus POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Metode request tidak valid. Hanya POST yang diizinkan.");
    }

    // Mendapatkan dan memvalidasi data input dari POST
    $judul = trim($_POST['judul_donasi'] ?? '');
    $isi = trim($_POST['isi_donasi'] ?? '');
    $tgl = trim($_POST['tgl_unggah'] ?? '');
    $kategori = intval($_POST['id_kategori'] ?? 0);
    $status_donasi = trim($_POST['status_donasi'] ?? 'Non Active'); // Default 'Non Active'
    $bentuk = trim($_POST['bentuk_donasi'] ?? ''); // Pastikan name='bentuk_donasi' di form

    // Validasi dasar input
    if (empty($judul)) {
        throw new Exception("Judul donasi harus diisi.");
    }
    if (empty($isi)) {
        throw new Exception("Deskripsi donasi harus diisi.");
    }
    if (empty($tgl)) {
        throw new Exception("Tanggal peluncuran harus diisi.");
    }
    if (!in_array($bentuk, ['uang', 'barang'])) {
        throw new Exception("Bentuk donasi tidak valid. Harus 'uang' atau 'barang'.");
    }
    // Asumsi id_kategori dari 1 sampai 6 adalah nilai valid
    if ($kategori <= 0 || $kategori > 6) {
        throw new Exception("Kategori donasi tidak valid. Nilai harus antara 1 dan 6.");
    }

    // Validasi format tanggal (YYYY-MM-DD)
    $date_obj = DateTime::createFromFormat('Y-m-d', $tgl);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $tgl) {
        throw new Exception("Format tanggal tidak valid. Gunakan YYYY-MM-DD.");
    }

    // Validasi tanggal tidak boleh di masa lalu (hanya boleh hari ini atau di masa depan)
    $today = new DateTime();
    $today->setTime(0, 0, 0); // Reset waktu ke 00:00:00 untuk perbandingan tanggal saja
    if ($date_obj < $today) {
        throw new Exception("Tanggal peluncuran tidak boleh di masa lalu.");
    }

    // Proses upload gambar
    $gambar_nama_file = ''; // Nama file gambar saja (misal: donasi_123.jpg)
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];

        // Cek error upload dari PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $php_upload_errors = [
                UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi upload_max_filesize di php.ini.',
                UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi MAX_FILE_SIZE yang ditentukan di form HTML.',
                UPLOAD_ERR_PARTIAL    => 'File hanya terunggah sebagian.',
                UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diunggah.',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temp hilang.',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
                UPLOAD_ERR_EXTENSION  => 'Ekstensi PHP menghentikan unggahan file.',
            ];
            $error_message = $php_upload_errors[$file['error']] ?? 'Error upload tidak diketahui (kode: ' . $file['error'] . ').';
            throw new Exception("Error saat mengunggah file: " . $error_message);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        // Validasi ekstensi file
        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.");
        }

        // Validasi ukuran file
        if ($file['size'] > $max_size) {
            throw new Exception("Ukuran file terlalu besar. Maksimal 2MB.");
        }

        // Tentukan direktori upload relatif terhadap lokasi proses_donasi.php
        $upload_dir = __DIR__ . '/uploads/';

        // Buat direktori upload jika belum ada
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception("Gagal membuat direktori upload: " . $upload_dir . ". Pastikan PHP memiliki izin tulis.");
            }
        }

        // Generate nama file unik untuk menghindari tabrakan nama
        $gambar_nama_file = 'donasi_' . time() . '_' . uniqid() . '.' . $ext;
        $uploaded_gambar_path = $upload_dir . $gambar_nama_file; // Simpan path lengkap

        // Pindahkan file yang diunggah dari temporary location ke direktori tujuan
        if (!move_uploaded_file($file['tmp_name'], $uploaded_gambar_path)) {
            throw new Exception("Gagal memindahkan gambar yang diunggah. Pastikan direktori 'uploads' memiliki izin tulis.");
        }
    }

    // Memulai transaksi database
    // Ini penting untuk memastikan semua data terkait disimpan atau tidak sama sekali (atomicity)
    $conn->begin_transaction();

    try {
        // Insert main donation record
        $stmt = $conn->prepare("INSERT INTO donasi (judul_donasi, isi_donasi, tgl_unggah, gambar, id_kategori, status_donasi, bentuk_donasi) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Gagal menyiapkan statement INSERT donasi: " . $conn->error);
        }

        // Bind parameter ke statement (s:string, i:integer)
        $stmt->bind_param("ssssiss", $judul, $isi, $tgl, $gambar_nama_file, $kategori, $status_donasi, $bentuk);

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data donasi utama: " . $stmt->error);
        }

        // Dapatkan ID donasi yang baru saja di-insert
        $id_donasi = $conn->insert_id;

        // Proses berdasarkan bentuk donasi
        if ($bentuk === 'uang') {
            $target_uang = intval($_POST['target_donasi'] ?? 0);

            if ($target_uang < 1000) {
                throw new Exception("Target uang minimal Rp 1.000.");
            }

            // Query untuk memasukkan data donasi uang
            $stmt2 = $conn->prepare("INSERT INTO donasi_uang (id_donasi, target_uang, terkumpul_uang) VALUES (?, ?, 0)");
            if (!$stmt2) {
                throw new Exception("Gagal menyiapkan statement INSERT donasi_uang: " . $conn->error);
            }

            $stmt2->bind_param("ii", $id_donasi, $target_uang);
            if (!$stmt2->execute()) {
                throw new Exception("Gagal menyimpan data donasi uang: " . $stmt2->error);
            }
        } elseif ($bentuk === 'barang') {
            $jenis_paket = trim($_POST['jenis_paket'] ?? ''); // Pastikan name='jenis_paket' di <select>
            $daftar_barang = trim($_POST['daftar_barang'] ?? ''); // Dari JS: "baju,celana,topi"
            $daftar_harga = trim($_POST['daftar_harga'] ?? '');   // Dari JS: "10000,20000,5000"

            if (!in_array($jenis_paket, ['biasa', 'istimewa'])) {
                throw new Exception("Jenis paket tidak valid. Harus 'biasa' atau 'istimewa'.");
            }

            if (empty($daftar_barang) || empty($daftar_harga)) {
                throw new Exception("Data barang atau harga barang tidak boleh kosong untuk donasi barang.");
            }

            // Pisahkan string daftar harga menjadi array dan validasi
            $harga_arr = array_map('trim', explode(',', $daftar_harga));
            $barang_arr = array_map('trim', explode(',', $daftar_barang));

            $target_paket = ($jenis_paket === 'istimewa') ? 3 : 2;

            if (count($barang_arr) !== $target_paket || count($harga_arr) !== $target_paket) {
                throw new Exception("Jumlah barang atau harga tidak sesuai dengan jenis paket yang dipilih (" . $target_paket . " item diperlukan).");
            }

            $harga_total = 0;
            foreach ($harga_arr as $h) {
                $harga = intval($h);
                if ($harga < 1000) {
                    throw new Exception("Setiap harga barang minimal Rp 1.000.");
                }
                $harga_total += $harga;
            }

            if ($harga_total <= 0) {
                throw new Exception("Total harga paket tidak valid atau nol.");
            }

            // Query untuk memasukkan data donasi barang
            $stmt3 = $conn->prepare("INSERT INTO donasi_barang (id_donasi, jenis_paket, target_paket, harga_paket, terkumpul_paket, daftar_barang) VALUES (?, ?, ?, ?, 0, ?)");
            if (!$stmt3) {
                throw new Exception("Gagal menyiapkan statement INSERT donasi_barang: " . $conn->error);
            }

            $stmt3->bind_param("isiis", $id_donasi, $jenis_paket, $target_paket, $harga_total, $daftar_barang);
            if (!$stmt3->execute()) {
                throw new Exception("Gagal menyimpan data donasi barang: " . $stmt3->error);
            }
        }

        // Commit transaksi jika semua query berhasil dieksekusi
        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Donasi berhasil ditambahkan.';
        $response['id_donasi'] = $id_donasi; // Mengembalikan ID donasi yang baru dibuat

    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error dalam blok transaksi
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->rollback();
        }

        // Hapus gambar yang sudah terunggah jika terjadi error setelah gambar diunggah
        if (!empty($uploaded_gambar_path) && file_exists($uploaded_gambar_path)) {
            unlink($uploaded_gambar_path);
        }

        // Lempar kembali exception agar ditangkap oleh outer try-catch
        throw $e;
    }
} catch (Exception $e) {
    // Tangani semua exception yang terjadi (baik dari validasi awal maupun dari transaksi)
    $response['success'] = false;
    $response['message'] = $e->getMessage();

    // Pastikan gambar dihapus jika error terjadi sebelum transaksi dimulai (misal validasi input atau upload file)
    if (!empty($uploaded_gambar_path) && file_exists($uploaded_gambar_path)) {
        unlink($uploaded_gambar_path);
    }
} finally {
    // Pastikan koneksi database selalu ditutup
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

// Mengirimkan respons JSON ke client (akan selalu dieksekusi)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
