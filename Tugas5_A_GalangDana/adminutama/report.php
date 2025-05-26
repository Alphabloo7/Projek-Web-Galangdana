<?php
session_start();

// ================== EDIT DATA LAPORAN DI SINI ==================
$hardcoded_reports = [
    [
        'title' => 'Invalid Donation',
        'reporter' => 'user123',
        'description' => 'Found suspicious donation campaign',
        'date' => '2024-03-20',
        'status' => 'pending'
    ],
    [
        'title' => 'Documentation Error',
        'reporter' => 'mitraA',
        'description' => 'Wrong documentation date',
        'date' => '2024-03-19',
        'status' => 'resolved'
    ],
    [
        'title' => 'Invalid Donation',
        'reporter' => 'user123',
        'description' => 'Found suspicious donation campaign',
        'date' => '2024-03-20',
        'status' => 'pending'
    ],
    [
        'title' => 'Documentation Error',
        'reporter' => 'mitraA',
        'description' => 'Wrong documentation date',
        'date' => '2024-03-19',
        'status' => 'resolved'
    ]
    // Tambahkan/edit/hapus entri di atas untuk manipulasi data
];

$_SESSION['reports'] = $hardcoded_reports;
// ================== BATAS EDIT DATA ==================
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="dashboard-admin.css">
    <style>
        .status-badge { cursor: pointer; transition: opacity 0.3s; }
        .status-badge:hover { opacity: 0.8; }
        .report-card { transition: transform 0.2s; }
        .report-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container mt-4">
            <h2 class="mb-4">User Reports</h2>
            
            <!-- Filter Section -->
            <div class="mb-4 btn-group">
                <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                <button class="btn btn-outline-warning filter-btn" data-filter="pending">Pending</button>
                <button class="btn btn-outline-success filter-btn" data-filter="resolved">Resolved</button>
            </div>

            <!-- Reports List -->
            <div class="row g-4">
                <?php foreach($_SESSION['reports'] as $index => $report): ?>
                <div class="col-md-6 report-card" data-status="<?= $report['status'] ?>">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title"><?= htmlspecialchars($report['title']) ?></h5>
                                <span class="badge bg-<?= $report['status'] == 'pending' ? 'warning' : 'success' ?> status-badge">
                                    <?= ucfirst($report['status']) ?>
                                </span>
                            </div>
                            <p class="text-muted small mb-2">Reported by: <?= htmlspecialchars($report['reporter']) ?></p>
                            <p><?= htmlspecialchars($report['description']) ?></p>
                             <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= $report['date'] ?></small>
                                <button class="btn btn-sm btn-danger" 
                                    onclick="if(confirm('Delete report?')) { 
                                        window.location='delete-report.php?index=<?= $index ?>'
                                    }">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                document.querySelectorAll('.report-card').forEach(card => {
                    card.style.display = (filter === 'all' || card.dataset.status === filter) 
                        ? 'block' 
                        : 'none';
                });
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>