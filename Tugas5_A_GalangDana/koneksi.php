<?php
$host = "localhost";
$username = "root";
$password = "1234";
$database = "mydonate2";

$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek apakah user default sudah ada
$check = "SELECT * FROM user WHERE email = 'admin@admin.com'";
$result = $koneksi->query($check);

if ($result->num_rows == 0) {
    // Membuat user default
    $default_nama = "admin";
    $default_email = "admin@admin.com";
    $default_pass = password_hash("admin123", PASSWORD_DEFAULT);

    // Gunakan prepared statement
    $sql = "INSERT INTO user (nama, email, password) VALUES (?, ?, ?)";
    $stmt = $koneksi->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $koneksi->error);
    }

    $stmt->bind_param("sss", $default_nama, $default_email, $default_pass);

    if (!$stmt->execute()) {
        die("Error inserting default user: " . $stmt->error);
    }

    $stmt->close();
}
?>
