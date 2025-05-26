<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="dashboard-admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <section id="donations" class="py-5">
            <div class="container">
                <h2 class="display-4 fw-bold mb-4">Manage Donations</h2>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    function generateAdminDonationCard($image, $title, $description) {
                        return '
                        <div class="col">
                            <div class="card shadow-sm h-100">
                                <img src="'.$image.'" class="card-img-top fixed-size-img" alt="'.$title.'">
                                <div class="card-body">
                                    <h5 class="card-title">'.$title.'</h5>
                                    <p class="card-text">'.substr($description, 0, 100).'...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-success">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </div>
                                        <small class="text-muted">Active</small>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }

                    echo generateAdminDonationCard(
                        "images/OpenDonation1.png",
                        "Banjir di Kabupaten Bandung",
                        "Masyarakat Kabupaten Bandung memerlukan bantuan Anda untuk penanganan krisis setelah banjir..."
                    );
                    
                    echo generateAdminDonationCard(
                        "images/OpenDonation2.png",
                        "Tsunami di Aceh",
                        "Peringatan! Tsunami dahsyat telah melanda Aceh pada 26 Desember 2004, menyebabkan lebih dari 170.000 korban jiwa. Mari bantu saudara-saudara kita yang terdampak."
                    );
                    
                    echo generateAdminDonationCard(
                        "images/OpenDonation3.png",
                        "Krisis Air Bersih di Sekolah Indonesia",
                        "Peringatan! Sebanyak 3,1 juta siswa di Indonesia belum memiliki akses ke air bersih di sekolah mereka. Mari bantu anak-anak kita mendapatkan fasilitas air bersih yang layak."
                    );
                    
                    echo generateAdminDonationCard(
                        "images/OpenDonation4.png",
                        "Kebakaran Hutan Kumpeh",
                        "Hutan di Kecamatan Kumpeh, Muarojambi, Jambi, telah terbakar, mempengaruhi masyarakat sekitar. Mari bantu menyediakan fasilitas kesehatan bagi mereka yang terdampak."
                    );
                    echo generateAdminDonationCard(
                        "images/OpenDonation5.png",
                        "Gempa Bumi di Tuban",
                        "Gempa berkekuatan 6,1 skala Richter mengguncang Kabupaten Tuban, Jawa Timur, pada 22 Maret 2024, menyebabkan kerusakan bangunan dan memerlukan bantuan segera. Mari bantu mereka pulih dengan menyediakan makanan dan obat-obatan."
                    );
                    
                    echo generateAdminDonationCard(
                        "images/OpenDonation6.png",
                        "Kekeringan di Nusa Tenggara Timur",
                        "Warga Nusa Tenggara Timur saat ini menderita akibat kekeringan parah, bantu mereka mendapatkan air bersih!"
                    );
                    
                    // Tambahkan 5 donasi lainnya sesuai data landing page
                    ?>
                </div>
            </div>
        </section>
    </div>
</body>
</html>