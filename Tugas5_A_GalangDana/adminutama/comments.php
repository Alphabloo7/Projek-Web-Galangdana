<?php
session_start();

// ================== EDIT DATA KOMENTAR DI BAWAH INI ==================
$hardcoded_comments = [

    [
        'user' => 'Mas Aziz',
        'role' => 'User',
        'comment' => 'Lantas mengapa kumasih menaruh hati',
        'date' => '2024-05-25'
    ],
    [
        'user' => 'Mbak Nurul',
        'role' => 'User',
        'comment' => 'Padahalku tahu kau tlah terikat janji',
        'date' => '2024-05-25'
    ],
        [
        'user' => 'Mas Nanang',
        'role' => 'User',
        'comment' => 'Keliru ataukan bukan tak tahu',
        'date' => '2024-05-25'
    ],
    [
        'user' => 'Mitra Mesen',
        'role' => 'Mitra',
        'comment' => 'Lupakanmu tapi aku tak mau...',
        'date' => '2024-05-25'
    ],
    [
        'user' => 'Mas Bayu',
        'role' => 'User',
        'comment' => 'Pantaskah aku menyimpan rasa cemburu',
        'date' => '2024-05-25'
    ],
    [
        'user' => 'Kumlot',
        'role' => 'Mitra',
        'comment' => 'Padahal bukan aku yang memilikimu',
        'date' => '2024-05-25'
    ],
        [
        'user' => 'Mas Fiddin',
        'role' => 'User',
        'comment' => 'Keliru ataukah bukan tak tahu',
        'date' => '2024-05-25'
    ],
    [
        'user' => 'Soto Mesen',
        'role' => 'Mitra',
        'comment' => 'Sanggup sampai kapankah ku tak tahu',
        'date' => '2024-05-25'
    ],
        [
        'user' => '3R',
        'role' => 'Mitra',
        'comment' => 'Akankah akal sehat menyadarkanku...',
        'date' => '2024-05-25'
    ]
];

// Untuk menambah komentar: tambahkan array baru di atas
// Untuk menghapus: hapus salah satu array
// Untuk edit: modifikasi nilai dalam array

$_SESSION['comments'] = $hardcoded_comments;
// ================== BATAS EDIT DATA ==================
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komentar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="dashboard-admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container mt-4">
            <h2 class="mb-4">User Comments</h2>
            
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($_SESSION['comments'] as $index => $comment): ?>
                            <tr>
                                <td><?= $comment['user'] ?></td>
                                <td><span class="badge bg-<?= $comment['role'] == 'User' ? 'primary' : 'success' ?>">
                                    <?= $comment['role'] ?>
                                </span></td>
                                <td><?= $comment['comment'] ?></td>
                                <td><?= $comment['date'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" 
                                        onclick="if(confirm('Delete comment?')) { 
                                            window.location='delete-comment.php?index=<?= $index ?>'
                                        }">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
</html>