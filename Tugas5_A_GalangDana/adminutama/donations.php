<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="dashboard-admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .fixed-size-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section id="donations" class="py-5">
            <div class="container">
                <h2 class="display-4 fw-bold mb-4">Manage Donations</h2>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    function generateAdminDonationCard($id, $image, $title, $description, $status)
                    {
                        $correctedImagePath = '../' . $image;
                        return '
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <img src="' . htmlspecialchars($correctedImagePath) . '" 
                                     class="card-img-top fixed-size-img" 
                                     alt="' . htmlspecialchars($title) . '" 
                                     onclick="showDonationDetail(' . intval($id) . ')">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($title) . '</h5>
                                    <p class="card-text">' . htmlspecialchars(substr($description, 0, 100)) . '...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a href="edit_donasi.php?id=' . intval($id) . '" class="btn btn-sm btn-outline-success">Edit</a>
                                            <a href="delete_donasi.php?id=' . intval($id) . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Yakin ingin menghapus donasi ini?\')">Delete</a>
                                        </div>
                                        <small class="text-muted">' . htmlspecialchars($status) . '</small>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }

                    $query = "SELECT * FROM donasi";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo generateAdminDonationCard(
                                $row['id_donasi'],
                                $row['gambar'],
                                $row['judul_donasi'],
                                $row['isi_donasi'],
                                $row['status_donasi']
                            );
                        }
                    } else {
                        echo '<p class="text-muted">Belum ada donasi di database.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="donationModal" class="modal fade" tabindex="-1" aria-labelledby="donationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle" class="modal-title">Judul Donasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid mb-3" alt="Gambar Donasi">
                    <p id="modalDescription"></p>
                    <p><strong>Bentuk Donasi:</strong> <span id="modalBentuk"></span></p>
                    <p><strong>Target Donasi:</strong> Rp <span id="modalTarget"></span></p>
                    <p><strong>Tanggal Unggah:</strong> <span id="modalTanggal"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDonationDetail(id) {
            fetch('get-donations-detail.php?id_donasi=' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    document.getElementById('modalTitle').innerText = data.judul_donasi || 'Judul kosong';
                    document.getElementById('modalImage').src = data.gambar ? '../' + data.gambar : 'default-image.jpg';
                    document.getElementById('modalDescription').innerText = data.isi_donasi || '-';
                    document.getElementById('modalBentuk').innerText = data.bentuk_donasi || '-';
                    document.getElementById('modalTarget').innerText = data.target_donasi ? Number(data.target_donasi).toLocaleString('id-ID') : '0';
                    document.getElementById('modalTanggal').innerText = data.tgl_unggah || '-';

                    let modal = new bootstrap.Modal(document.getElementById('donationModal'));
                    modal.show();
                })
                .catch(err => {
                    alert("Gagal mengambil data donasi.");
                    console.error(err);
                });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>