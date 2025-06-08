<?php
session_start();
require '../koneksi.php';

// Ban/unban action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = strtolower($_GET['action']);

    if ($action === 'ban') {
        $stmt = $conn->prepare("UPDATE user SET status_user = 'banned' WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif ($action === 'unban') {
        $stmt = $conn->prepare("UPDATE user SET status_user = 'active' WHERE id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Ambil data user
$sql = "SELECT id_user, nama, email, no_telepon, alamat, status_user, bergabung_user FROM user ORDER BY id_user ASC";
$result = $conn->query($sql);


$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Management</title>
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

        .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
            border-radius: 5px;
        }

        /* Alert animation */
        .alert-hide {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section id="user-management" class="py-5">
            <div class="container">
                <h2 class="text-center text-capitalize fw-bold mb-5 pb-3 border-bottom">
                    User Management
                </h2>

                <?php if (isset($_GET['msg'])): ?>
                    <div id="alertBox" class="alert alert-<?= $_GET['msg'] == 'banned' ? 'success' : ($_GET['msg'] == 'error' ? 'danger' : 'warning') ?>">
                        <?=
                        $_GET['msg'] == 'banned' ? 'User berhasil dibanned!' : ($_GET['msg'] == 'unbanned' ? 'User berhasil diunban!' : ($_GET['msg'] == 'error' ? 'Gagal memproses aksi.' : ''))
                        ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table user-table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Join Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user):

                                            $status = strtolower($user['status_user']); ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['id_user']) ?></td>
                                                <td><?= htmlspecialchars($user['nama']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td><?= htmlspecialchars($user['no_telepon']) ?></td>
                                                <td><?= htmlspecialchars($user['alamat']) ?></td>
                                                <td>
                                                    <span class="status-badge <?= $status === 'banned' ? 'banned-true' : 'banned-false' ?>">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d M Y', strtotime($user['bergabung_user'])) ?></td>
                                                <td>
                                                    <a href="?action=<?= $status === 'banned' ? 'unban' : 'ban' ?>&id=<?= $user['id_user'] ?>"
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
                                            <td colspan="6" class="text-center text-muted">No users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-muted">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                You can ban or unban users directly from this page.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Sembunyikan alert setelah 3 detik
        window.addEventListener('DOMContentLoaded', () => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.add('alert-hide');
                    setTimeout(() => {
                        alertBox.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>