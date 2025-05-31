<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "mydonate4";

// Buat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membuat admin default
function buatUserDefault($conn)
{
    $email_default = 'admin@admin.com';

    $check = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $check->bind_param("s", $email_default);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $default_nama = "admin";
        $default_pass = password_hash("admin123", PASSWORD_DEFAULT);

        $insert = $conn->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $default_nama, $email_default, $default_pass);

        if (!$insert->execute()) {
            die("Gagal membuat user default: " . $insert->error);
        }

        $insert->close();
    }

    $check->close();
}

// Jalankan fungsi
buatUserDefault($conn);
