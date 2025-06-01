<?php
$host = "localhost";       // Ganti jika bukan localhost
$user = "root";            // Username database kamu
$password = "";            // Password database kamu (kosongkan kalau pakai XAMPP default)
$database = "mydonate4"; // Ganti dengan nama database kamu

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
