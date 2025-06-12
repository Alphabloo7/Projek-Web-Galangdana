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

        .nav-link.active {
            background-color: #0d6efd;
            color: #fff !important;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section id="donations" class="py-5">
            <div class="container">
                <h2 class="text-center text-capitalize fw-bold mb-5 pb-3 border-bottom">
                    Donations
                </h2>


                <?php if (isset($_GET['msg'])): ?>
                    <div id="alertBox" class="alert alert-<?= $_GET['msg'] == 'deleted' ? 'success' : ($_GET['msg'] == 'error' ? 'danger' : 'warning') ?>">
                        <?=
                        $_GET['msg'] == 'deleted' ? 'Donasi berhasil dihapus!' : ($_GET['msg'] == 'error' ? 'Gagal menghapus donasi.' : 'ID tidak valid.')
                        ?>
                    </div>
                <?php endif; ?>


                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    function generateAdminDonationCard($id, $image, $title, $description, $status)
                    {
                        $correctedImagePath = '../' . $image;
                        $normalizedStatus = strtolower(trim($status));
                        $isActive = $normalizedStatus === 'active';

                        $buttonClass = $isActive ? 'btn-warning' : 'btn-success';
                        $buttonLabel = $isActive ? 'Nonaktifkan' : 'Aktifkan';

                        // Buat tombol toggle dengan data atribut untuk JS
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
                                            <a href="edit-donasi.php?id=' . intval($id) . '" class="btn btn-sm btn-outline-success">Edit</a>
                                            <a href="delete-donasi.php?id=' . intval($id) . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Yakin ingin menghapus donasi ini?\')">Delete</a>
                                            <button class="btn btn-sm btn-outline-primary" onclick="showDonationDetail(' . intval($id) . ')">Detail</button>
                                        </div>
                                        <button
                                            class="btn btn-sm ' . $buttonClass . ' toggle-status-btn"
                                            data-id="' . intval($id) . '"
                                            data-status="' . $normalizedStatus . '"
                                            style="margin-left: 10px;"
                                        >' . $buttonLabel . '</button>
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
                    <input type="hidden" id="donasiIdHidden">
                </div>
                <div class="modal-footer">
                    <button id="unggahBtn" class="btn btn-success">Unggah ke Publik</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi tampilkan detail donasi di modal
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
                    document.getElementById('donasiIdHidden').value = id;

                    let modal = new bootstrap.Modal(document.getElementById('donationModal'));
                    modal.show();
                })
                .catch(err => {
                    alert("Gagal mengambil data donasi.");
                    console.error(err);
                });
        }

        // Event listener tombol Unggah ke Publik (modal)
        document.getElementById('unggahBtn').addEventListener('click', function() {
            const idDonasi = document.getElementById('donasiIdHidden').value;

            fetch('update-status-donasi.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    // Kirim current_status agar backend bisa toggle dengan benar, tapi kalau tidak ada bisa disesuaikan backendnya
                    body: 'id_donasi=' + encodeURIComponent(idDonasi)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Donasi berhasil diunggah ke publik!');
                        location.reload(); // reload karena mungkin banyak perubahan
                    } else {
                        alert(data.error || 'Gagal mengunggah donasi.');
                    }
                })
                .catch(err => {
                    alert("Terjadi kesalahan saat unggah.");
                    console.error(err);
                });
        });

        // Event delegation untuk tombol toggle status
        document.querySelector('.row').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('toggle-status-btn')) {
                const btn = e.target;
                const idDonasi = btn.getAttribute('data-id');
                const currentStatus = btn.getAttribute('data-status');

                const confirmText = currentStatus === 'active' ?
                    'Yakin ingin "menonaktifkan" donasi ini?' :
                    'Yakin ingin "mengaktifkan" donasi ini?';

                if (!confirm(confirmText)) return; // Batal jika user tidak setuju

                fetch('update-status-donasi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_donasi=' + encodeURIComponent(idDonasi) + '&current_status=' + encodeURIComponent(currentStatus)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update tombol: label, warna, dan data-status
                            btn.setAttribute('data-status', data.new_status);
                            if (data.new_status === 'active') {
                                btn.textContent = 'Nonaktifkan';
                                btn.classList.remove('btn-success');
                                btn.classList.add('btn-warning');
                            } else {
                                btn.textContent = 'Aktifkan';
                                btn.classList.remove('btn-warning');
                                btn.classList.add('btn-success');
                            }
                        } else {
                            alert(data.error || 'Gagal mengubah status donasi.');
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan saat mengubah status.');
                        console.error(err);
                    });
            }
        });
    </script>
    <script>
        // Sembunyikan alert setelah 3 detik
        window.addEventListener('DOMContentLoaded', () => {
            const alertBox = document.getElementById('alertBox');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => {
                        alertBox.remove(); // hapus dari DOM
                    }, 500); // setelah animasi selesai
                }, 3000); // tampil selama 3 detik
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>