<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section class="py-5">
            <div class="container">
                <h2 class="display-4 fw-bold mb-4">Manage Documentation</h2>
                
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    // Contoh data dokumentasi
                    $documentations = [
                        [
                            'image' => 'images/banjirSemarang.jpeg',
                            'title' => 'Banjir Semarang',
                            'date' => '18 Maret 2022',
                            'description' => 'Donasi telah tersalurkan untuk bencana banjir di kota Semarang...'
                        ],
                        // Tambahkan data lainnya
                    ];

                    foreach($documentations as $doc) {
                        echo '
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <img src="'.$doc['image'].'" class="card-img-top fixed-size-img" alt="'.$doc['title'].'">
                                <div class="card-body">
                                    <h5 class="card-title">'.$doc['title'].'</h5>
                                    <small class="text-muted">'.$doc['date'].'</small>
                                    <p class="card-text mt-2">'.$doc['description'].'</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-success">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
</body>
</html>