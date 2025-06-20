<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$stmt = $conn->prepare("SELECT nama_mitra, email, no_telepon, alamat FROM mitra WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Mitra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h3><i class="fas fa-user me-2"></i>Profil Saya</h3>
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <td><?= htmlspecialchars($row['nama_mitra']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($row['email']) ?></td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
            </tr>
        </table>
        <a href="edit-profile.php" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Edit Profil</a>
    </div>
</body>

</html>