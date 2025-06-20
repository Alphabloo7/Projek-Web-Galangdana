<?php
session_start();
// Sertakan file koneksi database Anda
// Pastikan path ini benar relatif terhadap lokasi file profile.php
include '../../koneksi.php';

// Periksa apakah pengguna sudah login
// Jika belum, arahkan kembali ke halaman login
if (!isset($_SESSION['id_user'])) {
    header("Location: .../auth/Login.php"); // Sesuaikan path ke halaman login Anda
    exit();
}

$id_user = $_SESSION['id_user'];
$nama = $email = $password_hash = $no_telepon = $alamat = $username = "";
$error = $success = "";

// Ambil pesan sukses atau error dari session (jika ada setelah submit form)
if (isset($_SESSION['success_message'])) {
    $success = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan
}
if (isset($_SESSION['error_message'])) {
    $error = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
}

// --- Ambil data profil pengguna dari database ---
try {
    // Siapkan query untuk mengambil data pengguna
    // Sesuaikan nama kolom tabel 'users' Anda jika berbeda
    $stmt = $conn->prepare("SELECT nama, email, password, no_telepon, alamat, username FROM user WHERE id_user = ?");
    if (!$stmt) {
        throw new Exception("Gagal menyiapkan statement: " . $conn->error);
    }
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama = htmlspecialchars($row['nama']);
        $email = htmlspecialchars($row['email']);
        // Password hash tidak akan ditampilkan di form, hanya untuk validasi backend
        $password_hash = htmlspecialchars($row['password']);
        $no_telepon = htmlspecialchars($row['no_telepon']);
        $alamat = htmlspecialchars($row['alamat']);
        $username = htmlspecialchars($row['username']);
    } else {
        $error = "Data profil tidak ditemukan. Silakan hubungi administrator.";
    }
    $stmt->close();
} catch (Exception $e) {
    $error = "Terjadi kesalahan saat mengambil data profil: " . $e->getMessage();
}


// --- Ambil data riwayat donasi pengguna dari database ---
$riwayat_donasi = [];
try {
    // Asumsi: Anda memiliki tabel 'donasi_users' yang menghubungkan pengguna dengan donasi,
    // dan tabel 'donasi' yang berisi detail kampanye donasi.
    // Sesuaikan nama tabel dan kolom sesuai skema database Anda.
    $stmt_donasi = $conn->prepare("
        SELECT d.judul_donasi, du.jumlah_donasi, du.tanggal_donasi
        FROM transaksi du
        JOIN donasi d ON du.id_donasi = d.id_donasi
        WHERE du.id_user = ?
        ORDER BY du.tanggal_donasi DESC
    ");
    if (!$stmt_donasi) {
        throw new Exception("Gagal menyiapkan statement riwayat donasi: " . $conn->error);
    }
    $stmt_donasi->bind_param("i", $id_user);
    $stmt_donasi->execute();
    $result_donasi = $stmt_donasi->get_result();

    if ($result_donasi->num_rows > 0) {
        while ($row_donasi = $result_donasi->fetch_assoc()) {
            $riwayat_donasi[] = $row_donasi;
        }
    }
    $stmt_donasi->close();
} catch (Exception $e) {
    $error = "Terjadi kesalahan saat mengambil riwayat donasi: " . $e->getMessage();
}
//Ambil riwayat laporan dari database
$riwayat_laporan = [];
try {
    $stmt_laporan = $conn->prepare("
        SELECT judul_laporan, isi_laporan, tgl_laporan, status_laporan, bukti_laporan
        FROM laporan
        WHERE id_user = ?
        ORDER BY tgl_laporan DESC
    ");
    $stmt_laporan->bind_param("i", $id_user);
    $stmt_laporan->execute();
    $result_laporan = $stmt_laporan->get_result();

    while ($row_laporan = $result_laporan->fetch_assoc()) {
        $riwayat_laporan[] = $row_laporan;
    }

    $stmt_laporan->close();
} catch (Exception $e) {
    $error = "Terjadi kesalahan saat mengambil riwayat laporan: " . $e->getMessage();
}

// Tutup koneksi database setelah semua data diambil
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Styling umum untuk halaman */
        body {
            background-color: #f8f9fa;
            /* Latar belakang abu-abu terang */
        }

        .profile-container {
            max-width: 960px;
            /* Lebar maksimum container */
            margin: 30px auto;
            /* Posisi tengah dengan margin atas/bawah */
            background-color: #fff;
            /* Latar belakang putih untuk card utama */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Bayangan ringan */
        }

        .back-button {
            margin-bottom: 20px;
            /* Margin bawah untuk tombol kembali */
        }

        .profile-header {
            display: flex;
            align-items: flex-start;
            gap: 30px;
            /* Jarak antara gambar profil dan konten kanan */
            margin-bottom: 30px;
            flex-wrap: wrap;
            /* Mengatur wrap pada layar kecil */
        }

        .profile-image-section {
            text-align: center;
            flex-shrink: 0;
            /* Mencegah section ini mengecil */
            width: 150px;
            /* Lebar tetap untuk gambar profil */
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            /* Membuat gambar lingkaran */
            object-fit: cover;
            /* Memastikan gambar mengisi area tanpa terdistorsi */
            border: 5px solid #eee;
            /* Border di sekitar gambar */
            margin-bottom: 15px;
        }

        .profile-content-right {
            flex-grow: 1;
            /* Konten kanan mengambil sisa ruang */
        }

        /* Styling untuk tab navigasi */
        .nav-tabs .nav-link {
            color: #495057;
            /* Warna teks tab default */
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            /* Warna teks tab aktif Bootstrap primary */
            border-color: #007bff #007bff #fff;
            /* Border bawah putih agar menyatu dengan konten */
            background-color: #fff;
        }

        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            /* Border konten tab */
            border-top: none;
            /* Menghilangkan border atas agar menyatu dengan tab */
            border-radius: 0 0 5px 5px;
            /* Radius border hanya di bawah */
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .profile-header {
                flex-direction: column;
                /* Ubah ke kolom pada layar kecil */
                align-items: center;
                /* Pusatkan item */
            }

            .profile-image-section {
                width: 100%;
                /* Lebar penuh di mobile */
            }
        }
    </style>
</head>

<body>
    <div class="container profile-container">
        <!-- Tombol Kembali -->
        <a href="javascript:history.back()" class="btn btn-outline-secondary back-button">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>

        <!-- Pesan Sukses/Error -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>



        <!-- Bagian Kanan: Konten Tab (Form Edit dan Riwayat Donasi) -->
        <div class="profile-content-right">
            <!-- Navigasi Tabs -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="true">
                        <i class="fas fa-user-edit me-2"></i> Edit Profil
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="donation-history-tab" data-bs-toggle="tab" data-bs-target="#donation-history" type="button" role="tab" aria-controls="donation-history" aria-selected="false">
                        <i class="fas fa-history me-2"></i> Riwayat Donasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="report-history-tab" data-bs-toggle="tab" data-bs-target="#report-history" type="button" role="tab" aria-controls="report-history" aria-selected="false">
                        <i class="fas fa-file-alt me-2"></i> Riwayat Laporan
                    </button>
                </li>
            </ul>

            <!-- Konten Tabs -->
            <div class="tab-content" id="profileTabsContent">
                <!-- Tab Pane: Edit Profil -->
                <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                    <h4 class="mb-4">Informasi Profil</h4>
                    <form action="edit-profile.php" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label"><i class="fas fa-signature me-2"></i> Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label"><i class="fas fa-user me-2"></i> Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $username; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fas fa-at me-2"></i> Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $email; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="fas fa-key me-2"></i> Ganti Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah">
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password Anda.</small>
                        </div>
                        <div class="mb-3">
                            <label for="no_telepon" class="form-label"><i class="fas fa-phone me-2"></i> No. Telepon</label>
                            <input type="tel" class="form-control" id="no_telepon" name="no_telepon" value="<?= $no_telepon; ?>">
                        </div>
                        <div class="mb-4">
                            <label for="alamat" class="form-label"><i class="fas fa-map-marker-alt me-2"></i> Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= $alamat; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>

                <!-- Tab Pane: Riwayat Donasi -->
                <div class="tab-pane fade" id="donation-history" role="tabpanel" aria-labelledby="donation-history-tab">
                    <h4 class="mb-4">Daftar Riwayat Donasi Anda</h4>
                    <?php if (!empty($riwayat_donasi)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Judul Donasi</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Tanggal Donasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($riwayat_donasi as $donasi): ?>
                                        <tr>
                                            <th scope="row"><?= $no++; ?></th>
                                            <td><?= htmlspecialchars($donasi['judul_donasi']); ?></td>
                                            <td>Rp<?= number_format($donasi['jumlah_donasi'], 0, ',', '.'); ?></td>
                                            <td><?= date('d M Y H:i', strtotime($donasi['tanggal_donasi'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            Anda belum memiliki riwayat donasi. Yuk, mulai berdonasi!
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab Pane : Riwayat Laporan -->
                <div class="tab-pane fade" id="report-history" role="tabpanel" aria-labelledby="report-history-tab">
                    <h4 class="mb-4">Daftar Riwayat Laporan Anda</h4>
                    <?php if (!empty($riwayat_laporan)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Isi</th>
                                        <th>Tanggal</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($riwayat_laporan as $laporan): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($laporan['judul_laporan']); ?></td>
                                            <td><?= htmlspecialchars($laporan['isi_laporan']); ?></td>
                                            <td><?= date('d M Y', strtotime($laporan['tgl_laporan'])); ?></td>
                                            <td>
                                                <?php if (!empty($laporan['bukti_laporan'])): ?>
                                                    <a href="..\..\uploads_bukti/<?= htmlspecialchars($laporan['bukti_laporan']) ?>" target="_blank">Lihat Bukti</a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?=
                                                                        $laporan['status_laporan'] == 'Resolved' ? 'success' : 'secondary'
                                                                        ?>">
                                                    <?= htmlspecialchars($laporan['status_laporan']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            Anda belum memiliki riwayat laporan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>