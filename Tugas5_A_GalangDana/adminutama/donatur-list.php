<?php
require '../koneksi.php';

// Handle search dan sort
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'total';

// Query dasar
$query = "
    SELECT u.id_user, u.nama, u.email, 
           SUM(p.total) AS total,
           MAX(p.tgl_donasi) AS tanggal_donasi
    FROM user u
    JOIN transaksi p ON u.id_user = p.id_user
    WHERE u.nama LIKE ?
    GROUP BY u.id_user, u.nama, u.email
";

// Sorting
if ($sort === 'tanggal') {
    $query .= " ORDER BY tgl_donasi DESC";
} else {
    $query .= " ORDER BY total DESC";
}

$stmt = $conn->prepare($query);
$likeSearch = "%" . $search . "%";
$stmt->bind_param("s", $likeSearch);
$stmt->execute();
$result = $stmt->get_result();

$donaturList = [];
while ($row = $result->fetch_assoc()) {
    $donaturList[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Donatur List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content p-4">
        <h2 class="mb-4">Daftar Donatur</h2>

        <!-- Search & Sort Form -->
        <form method="GET" class="d-flex mb-3 gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama donatur..." value="<?= htmlspecialchars($search) ?>">
            <select name="sort" class="form-select w-auto">
                <option value="total" <?= $sort === 'total' ? 'selected' : '' ?>>Sortir: Total Donasi</option>
                <option value="tanggal" <?= $sort === 'tanggal' ? 'selected' : '' ?>>Sortir: Tanggal Terakhir</option>
            </select>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Total Donasi</th>
                    <th>Terakhir Donasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($donaturList) > 0): ?>
                    <?php foreach ($donaturList as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($d['nama']) ?></td>
                            <td><?= htmlspecialchars($d['email']) ?></td>
                            <td>Rp<?= number_format($d['total'], 0, ',', '.') ?></td>
                            <td><?= date('d M Y', strtotime($d['tanggal_donasi'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data donatur ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>