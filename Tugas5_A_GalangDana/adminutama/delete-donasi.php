<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM donasi WHERE id_donasi = $id";

    if (mysqli_query($conn, $query)) {
        header('Location: donations.php?msg=deleted');
        exit;
    } else {
        echo "Gagal menghapus donasi.";
    }
} else {
    echo "ID tidak ditemukan.";
}
