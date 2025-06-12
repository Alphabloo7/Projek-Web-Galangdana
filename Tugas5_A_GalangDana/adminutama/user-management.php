<?php
session_start();
require '../koneksi.php';

// Ambil tipe user atau mitra
$tipe = $_GET['tipe'] ?? 'user';

// Ban/unban action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = strtolower($_GET['action']);

    if ($tipe === 'mitra') {
        $field = 'status_mitra';
        $table = 'mitra';
        $id_field = 'id_mitra';
    } else {
        $field = 'status_user';
        $table = 'user';
        $id_field = 'id_user';
    }

    if ($action === 'ban') {
        $stmt = $conn->prepare("UPDATE $table SET $field = 'banned' WHERE $id_field = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif ($action === 'unban') {
        $stmt = $conn->prepare("UPDATE $table SET $field = 'active' WHERE $id_field = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: ?tipe=$tipe&msg=$action");
    exit;
}

// Ambil data
if ($tipe === 'mitra') {
    $sql = "SELECT id_mitra AS id, nama_mitra AS nama, email, no_telepon, alamat, status_mitra AS status, bergabung_mitra AS bergabung FROM mitra ORDER BY id_mitra ASC";
} else {
    $sql = "SELECT id_user AS id, nama, email, no_telepon, alamat, status_user AS status, bergabung_user AS bergabung FROM user ORDER BY id_user ASC";
}

$result = $conn->query($sql);
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User & Mitra Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="dashboard-admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .user-table th {
            background-color: #043873;
            color: white;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 15px;
        }

        .banned-true {
            background-color: #dc3545;
            color: white;
        }

        .banned-false {
            background-color: #28a745;
            color: white;
        }

        .btn-toggle {
            margin-right: 10px;
        }

        .alert-hide {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section class="py-5">
            <div class="container">
                <h2 class="text-center text-capitalize fw-bold mb-4 pb-2 border-bottom">
                    <?= ucfirst($tipe) ?> Management
                </h2>

                <div class="mb-4 text-center">
                    <a href="?tipe=user" class="btn btn-toggle <?= $tipe === 'user' ? 'btn-primary' : 'btn-outline-primary' ?>">User</a>
                    <a href="?tipe=mitra" class="btn btn-toggle <?= $tipe === 'mitra' ? 'btn-primary' : 'btn-outline-primary' ?>">Mitra</a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div id="alertBox" class="alert alert-success">
                        <?= $_GET['msg'] === 'ban' ? ucfirst($tipe) . ' berhasil dibanned!' : ucfirst($tipe) . ' berhasil diunban!' ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table user-table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $item):
                                            $status = strtolower($item['status']); ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['id']) ?></td>
                                                <td><?= htmlspecialchars($item['nama']) ?></td>
                                                <td><?= htmlspecialchars($item['email']) ?></td>
                                                <td><?= htmlspecialchars($item['no_telepon']) ?></td>
                                                <td><?= htmlspecialchars($item['alamat']) ?></td>
                                                <td>
                                                    <span class="status-badge <?= $status === 'banned' ? 'banned-true' : 'banned-false' ?>">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d M Y', strtotime($item['bergabung'])) ?></td>
                                                <td>
                                                    <a href="?tipe=<?= $tipe ?>&action=<?= $status === 'banned' ? 'unban' : 'ban' ?>&id=<?= $item['id'] ?>"
                                                        class="btn btn-sm <?= $status === 'banned' ? 'btn-success' : 'btn-danger' ?>"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fas <?= $status === 'banned' ? 'fa-unlock' : 'fa-ban' ?>"></i>
                                                        <?= $status === 'banned' ? 'Unban' : 'Ban' ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Data tidak ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-muted">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Klik tombol Ban/Unban untuk mengatur status pengguna/mitra.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Auto-hide alert
        window.addEventListener('DOMContentLoaded', () => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.add('alert-hide');
                    setTimeout(() => alertBox.remove(), 500);
                }, 3000);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>