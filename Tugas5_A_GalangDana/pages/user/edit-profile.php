<?php
session_start();
include '../../koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile.php"); 
    exit();
}

if (!isset($_SESSION['id_user'])) {
    header("Location: .../auth/Login.php"); 
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data dari form
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password_baru = $_POST['password'] ?? ''; 
$no_telepon = $_POST['no_telepon'] ?? '';
$alamat = $_POST['alamat'] ?? '';

// Validasi Input
if (empty($nama) || empty($username) || empty($email)) {
    $_SESSION['error_message'] = "Nama, Username, dan Email tidak boleh kosong.";
    header("Location: profile.php");
    exit();
}

// Set up query fields and parameters dynamically
$update_fields = [];
$bind_types = '';
$bind_params = [];

// field yang selalu diupdate
$update_fields[] = 'nama = ?';
$bind_types .= 's';
$bind_params[] = $nama;
$update_fields[] = 'username = ?';
$bind_types .= 's';
$bind_params[] = $username;
$update_fields[] = 'email = ?';
$bind_types .= 's';
$bind_params[] = $email;
$update_fields[] = 'no_telepon = ?';
$bind_types .= 's';
$bind_params[] = $no_telepon;
$update_fields[] = 'alamat = ?';
$bind_types .= 's';
$bind_params[] = $alamat;


if (!empty($password_baru)) {
    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
    $update_fields[] = 'password = ?';
    $bind_types .= 's';
    $bind_params[] = $hashed_password;
}

$bind_types .= 'i';
$bind_params[] = $id_user;

$sql = "UPDATE user SET " . implode(', ', $update_fields) . " WHERE id_user = ?";

// --- Eksekusi Query Update ---
try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Gagal menyiapkan statement UPDATE: " . $conn->error);
    }

    // Bind parameter secara dinamis
    call_user_func_array([$stmt, 'bind_param'], array_merge([$bind_types], $bind_params));

    if ($stmt->execute()) {
        // Update nama pengguna di sesi agar navbar langsung menampilkan nama terbaru
        $_SESSION['nama'] = $nama;
        $_SESSION['success_message'] = "Profil berhasil diperbarui!";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui profil: " . $stmt->error;
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error_message'] = "Terjadi kesalahan sistem saat memperbarui profil: " . $e->getMessage();
}

$conn->close();

header("Location: profile.php");
exit();
