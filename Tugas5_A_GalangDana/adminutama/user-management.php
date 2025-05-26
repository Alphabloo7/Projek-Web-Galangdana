<?php
session_start();

// ================== DATA AWAL USER & MITRA ==================
// Data ini hanya digunakan untuk inisialisasi session jika kosong
$initial_users_data = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'User', // Hanya User dan Mitra
        'banned' => false,
        'join_date' => '2024-01-15'
    ],
    [
        'id' => 2,
        'name' => 'Mitra Bantu Jaya', // Nama Mitra lebih deskriptif
        'email' => 'mitra@example.com',
        'role' => 'Mitra', // Hanya User dan Mitra
        'banned' => false,
        'join_date' => '2023-12-01'
    ],
    [
        'id' => 3,
        'name' => 'User Aktif Sekali', // Contoh user lain
        'email' => 'user.aktif@example.com',
        'role' => 'User', // Hanya User dan Mitra
        'banned' => false,
        'join_date' => '2024-02-20'
    ],
    [
        'id' => 4,
        'name' => 'Mitra Donasi Cepat',
        'email' => 'mitradonasi@example.com',
        'role' => 'Mitra',
        'banned' => true, // Contoh mitra yang sudah terbanned
        'join_date' => '2023-11-10'
    ]
];

// Inisialisasi data pengguna di session jika belum ada
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = $initial_users_data;
}

// Handle ban/unban action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Loop melalui pengguna di SESSION untuk update
    foreach ($_SESSION['users'] as &$user_ref) { // Gunakan reference (&) agar perubahan langsung ke array session
        if ($user_ref['id'] === $id) {
            // Pastikan role adalah User atau Mitra sebelum melakukan ban/unban
            if ($user_ref['role'] === 'User' || $user_ref['role'] === 'Mitra') {
                if ($action === 'ban') {
                    $user_ref['banned'] = true;
                } elseif ($action === 'unban') {
                    $user_ref['banned'] = false;
                }
            }
            break; // User ditemukan dan diproses, keluar dari loop
        }
    }
    unset($user_ref); // Penting: Hapus referensi setelah loop

    // Redirect untuk membersihkan parameter GET dan mencegah re-submit form
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Data pengguna untuk ditampilkan diambil dari session
$users_to_display = $_SESSION['users'];
// ================== BATAS EDIT DATA ==================
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="dashboard-admin.css">
    <style>
        .user-table th {
            background-color: #043873;
            color: white;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .banned-true { background-color: #dc3545; color: white; }
        .banned-false { background-color: #28a745; color: white; }
        .role-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            color: white; /* Pastikan teks badge putih agar kontras */
        }
        .role-user { background-color: #6c757d; }
        .role-mitra { background-color: #007bff; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; // Pastikan file sidebar.php ada atau hapus jika tidak digunakan ?>

    <div class="main-content">
        <div class="container mt-4">
            <h2 class="mb-4"><i class="fas fa-users-cog me-2"></i>User Management</h2>

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table user-table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users_to_display)): ?>
                                    <?php foreach($users_to_display as $user_item): ?>
                                    <tr>
                                        <td><?= $user_item['id'] ?></td>
                                        <td><?= htmlspecialchars($user_item['name']) ?></td>
                                        <td><?= htmlspecialchars($user_item['email']) ?></td>
                                        <td>
                                            <span class="role-badge
                                                <?= 'role-'.strtolower($user_item['role']) ?>">
                                                <?= htmlspecialchars($user_item['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge
                                                <?= $user_item['banned'] ? 'banned-true' : 'banned-false' ?>">
                                                <?= $user_item['banned'] ? 'Banned' : 'Active' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($user_item['join_date'])) ?></td>
                                        <td>
                                            <?php // Tombol ban/unban hanya untuk role User dan Mitra ?>
                                            <?php if($user_item['role'] === 'User' || $user_item['role'] === 'Mitra'): ?>
                                            <a href="?action=<?= $user_item['banned'] ? 'unban' : 'ban' ?>&id=<?= $user_item['id'] ?>"
                                               class="btn btn-sm <?= $user_item['banned'] ? 'btn-success' : 'btn-danger' ?>"
                                               onclick="return confirm('Are you sure?')">
                                                <i class="fas <?= $user_item['banned'] ? 'fa-unlock' : 'fa-ban' ?>"></i>
                                                <?= $user_item['banned'] ? 'Unban' : 'Ban' ?>
                                            </a>
                                            <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">No users found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-2"></i>
                            Hanya akun dengan role 'User' atau 'Mitra' yang dapat di-ban atau di-unban dari halaman ini.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>