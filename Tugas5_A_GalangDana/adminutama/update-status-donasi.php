<?php
include '../koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id_donasi']) || empty($_POST['id_donasi'])) {
        echo json_encode(['error' => 'Parameter id_donasi tidak ditemukan']);
        exit;
    }

    $id = intval($_POST['id_donasi']);
    $current_status = isset($_POST['current_status']) ? strtolower(trim($_POST['current_status'])) : '';

    if ($current_status === 'active') {
        $new_status = 'inactive';
        $stmt = $conn->prepare("UPDATE donasi SET status_donasi = ? WHERE id_donasi = ?");
        if (!$stmt) {
            echo json_encode(['error' => 'Gagal prepare statement: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("si", $new_status, $id);
    } else {
        $new_status = 'active';
        $stmt = $conn->prepare("UPDATE donasi SET status_donasi = ?, tgl_unggah = NOW() WHERE id_donasi = ?");
        if (!$stmt) {
            echo json_encode(['error' => 'Gagal prepare statement: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("si", $new_status, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    } else {
        echo json_encode(['error' => 'Gagal mengubah status donasi: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
} else {
    echo json_encode(['error' => 'Metode tidak diizinkan']);
    exit;
}
