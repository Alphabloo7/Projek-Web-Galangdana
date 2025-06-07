<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Landing Page </title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="index-style.css">
    <style>
    .img-fixed {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    </style>
</head>

<body>

    <!-- Start navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center">
                <img src="images/logo-navbar.png" alt="Logo" width="150" height="auto">
            </a>
            <!-- Toggle Button (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#about-us">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#open-donations">Donate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#how-to-donate">How to Donate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#documentation">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="form-donasi.php">Form Donate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="form-documentation.php">Form Documentation</a>
                    </li>
                </ul>

                <!-- Login & Sign Up -->
                <?php if (isset($_SESSION['nama'])): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?= htmlspecialchars($_SESSION['nama']); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="pages/auth/logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="profile.php" class="btn btn-warning">Login</a>
                <a href="pages/auth/SignupUser.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Hero Section -->
    <div class="container-fluid hero position-relative text-center">
        <video autoplay loop muted playsinline class="hero-video">
            <source src="assets/videos/mydonation.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="overlay"></div>
        <div class="formcontainer position-absolute top-50 start-50 translate-middle text-white">
            <h1 class="hero-title">
                Happiness comes from <br> your action
            </h1>
            <a class="btn btn-dark px-5 py-3 fw-bold mt-4" href="#open-donations">Donate Now</a>
        </div>
    </div>

    <!-- End hero section -->


    <!-- Start About Us -->
    <section id="about-us" class="pb-5">
        <div class="container">
            <div class="col-12 p-4">
                <h2 class="display-4 text-center fw-bold mt-4">Our Mission</h2>
            </div>
            <!-- Isi -->
            <div class="row">
                <div class="col-12 col-md-6 text-justify">

                    <p>Di dunia yang penuh dengan kebaikan, masih banyak orang yang membutuhkan uluran tangan.
                        Banyak orang yang ingin membantu sesama, tetapi sering kali kesulitan untuk menemukan cara
                        yang
                        tepat.
                        Website ini hadir sebagai jembatan yang menghubungkan para dermawan dengan mereka yang
                        membutuhkan,
                        melalui sistem donasi yang trensparan, mudah, dan aman. Kami percaya bahwa setiap kontribusi
                        sekecil apa pun
                        dapat membawa perubahan besar bagi mereka yang sedang berjuang.</p>

                    <p>Kami memiliki komitmen yang kuat untuk terus mengembangkan platform ini agar dapat menjangkau
                        lebih banyak
                        orang yang membutuhkan. Sebagai bagian dari upaya ini, kami berencana untuk memperluas kerja
                        sama dengan
                        berbagai komunitasserta organisasi sosial guna memastikan bahwa setiap bantuan yang
                        terkumpul
                        dapat didistribusikan
                        sesuai dengan kebutuhan yang diperlukan. Bersama sama, kita dapat membangun budaya tolong
                        menolong yang lebih kuat,
                        dan mementingkan nilai nilai kehidupan sosial serta memberikan manfaat yang nyata begi
                        mereka
                        yang membutuhkan.</p>
                </div>
                <div class="col-12 col-md-6">
                    <img src="images/donate.jpg" alt="our mission" width=" 100%">
                </div>
            </div>
        </div>
    </section>
    <!-- End About Us -->


    <!-- Start Open Donations -->
    <section id="open-donations" class="pb-5">
        <div class="container">
            <div class="col-12 p-4">
                <h2 class="display-4 text-center fw-bold mt-4">Open Donations</h2>
            </div>
            <!-- Search Bar-->

            <div class="container mt-4">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-6">
                        <div class="form position-relative">
                            <i class="fa fa-search search-icon"></i>
                            <input type="text" class="form-control form-input" placeholder="Search...">
                        </div>
                    </div>
                </div>
            </div>


            <?php
      // Panggil koneksi dari file eksternal
      include 'koneksi.php';

      function formatDate($dateString)
      {
        $date = new DateTime($dateString);
        return $date->format('F j, Y');
      }

      function generateDonationCard($id, $image, $title, $description, $date, $timeAgo, $donationCount = 0)
      {
        $shortDescription = substr($description, 0, 100) . '...';
        $fullDescription = $description;
        return "
    <div class='col'>
      <div class='card shadow-sm' style='min-height: 500px;'>
        <img src='$image' class='card-img-top img-fixed' alt='Gambar Deskripsi'>
        <div class='card-body'>
          <div class='d-flex justify-content-between'>
            <small class='text-muted'>$date</small>
            <a href='#' class='text-primary text-decoration-none fw-bold'>{$donationCount} donations</a>
          </div>
          <h5 class='fw-bold text-dark mt-1'>$title</h5>
          <p class='card-text short-description'>$shortDescription</p>
          <p class='card-text full-description' style='display: none;'>$fullDescription</p>
          <button class='btn btn-sm btn-outline-secondary toggle-description'>Baca Selengkapnya</button>
          <div class='d-flex justify-content-between align-items-center mt-3'>
            <div class='btn-group'>
              <a href='percobaan.php' id=$id' class='btn btn-sm btn-outline-success text-decoration-none'>Donate</a>
            </div>
            <small class='text-body-secondary'>$timeAgo</small>
          </div>
        </div>
      </div>
    </div>
  ";
      }

      // Ambil data donasi aktif dari database
      $sql = "SELECT * FROM donasi WHERE status_donasi = 'active' ORDER BY tgl_unggah";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo '<div class="album py-5 bg-body-tertiary">
          <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
        while ($row = $result->fetch_assoc()) {
          echo generateDonationCard(
            $row['id_donasi'],
            htmlspecialchars($row['gambar']),
            htmlspecialchars($row['judul_donasi']),
            htmlspecialchars($row['isi_donasi']),
            formatDate($row['tgl_unggah']),
            "Baru diunggah", // Placeholder; bisa kamu ubah ke fungsi waktu nyata seperti "2 hari lalu"
            0 // Donation count dummy
          );
        }
        echo '</div></div></div>';
      } else {
        echo "<p class='text-center'>No donations found.</p>";
      }

      $conn->close();
      ?>
        </div>
    </section>
    <!-- End Open Donations -->


    <!-- Toggle Deskripsi -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.toggle-description');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const cardBody = this.closest('.card-body');
                const shortDescription = cardBody.querySelector('.short-description');
                const fullDescription = cardBody.querySelector('.full-description');

                if (shortDescription.style.display === 'none') {
                    // Tampilkan deskripsi pendek
                    shortDescription.style.display = 'block';
                    fullDescription.style.display = 'none';
                    this.textContent = 'Baca Selengkapnya';
                } else {
                    // Tampilkan deskripsi lengkap
                    shortDescription.style.display = 'none';
                    fullDescription.style.display = 'block';
                    this.textContent = 'Tampilkan Lebih Sedikit';
                }
            });
        });
    });
    </script>
    <!-- End Open Donations -->


    <!-- Start How To Donate -->
    <div class="container-fluid p-0">
        <a id="how-to-donate">
            <img src="images/coba56.jpg" class="img-fluid w-100" alt="How To Donate">
        </a>
    </div>
    <!-- End How To Donate -->


    <!-- Start Documentation -->
    <section id="documentation" class="pb-5">
        <div class="container">
            <div class="col-12 p-4">
                <h2 class="display-4 text-center fw-bold mt-4">Documentation</h2>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                <!-- Card 1 -->
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="images/banjirSemarang.jpeg" alt="Banjir Semarang" class="card-img-top fixed-size-img">
                        <div class="card-body">
                            <p class="card-text short-text">Donasi telah tersalurkan untuk bencana banjir di kota
                                Semarang pada 17 Maret 2022. Donasi yang terkumpul berupa uang sejumlah 8.450.000.
                                Terima Kasih atas bantuan yang telah anda diberikan untuk korban bencana ini.</p>
                            <p class="card-text full-text" style="display: none;">Donasi telah tersalurkan untuk
                                bencana
                                banjir di kota Semarang pada 17 Maret 2022. Donasi yang terkumpul berupa uang
                                sejumlah
                                8.450.000. Terima Kasih atas bantuan yang telah anda diberikan untuk korban bencana
                                ini.
                                <strong>Informasi tambahan:</strong> Bantuan ini disalurkan melalui posko bencana
                                setempat.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary toggle-text">Baca Selengkapnya</a>
                                <small class="text-body-secondary">18 Maret 2022</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="images/BanjirBantul.jpeg" alt="Banjir Bantul" class="card-img-top fixed-size-img">
                        <div class="card-body">
                            <p class="card-text short-text">Pada 15 Desember 2022, tim kami telah menyalurkan donasi
                                berupa bahan makanan pokok serta uang tunai sejumlah 3.450.000 kepada warga
                                terdampak
                                bencana banjir di Bantul, Yogyakarta. Bantuan ini diharapkan dapat meringankan beban
                                mereka dalam menghadapi situasi sulit.</p>
                            <p class="card-text full-text" style="display: none;">Pada 15 Desember 2022, tim kami
                                telah
                                menyalurkan donasi berupa bahan makanan pokok serta uang tunai sejumlah 3.450.000
                                kepada
                                warga terdampak bencana banjir di Bantul, Yogyakarta. Bantuan ini diharapkan dapat
                                meringankan beban mereka dalam menghadapi situasi sulit. <strong>Informasi
                                    tambahan:</strong> Donasi disalurkan melalui relawan setempat.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary toggle-text">Baca Selengkapnya</a>
                                <small class="text-body-secondary">17 Desember 2022</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="images/tanah-longsor.jpg" alt="Banjir Bantul" class="card-img-top fixed-size-img">
                        <div class="card-body">
                            <p class="card-text short-text">Dengan penuh rasa syukur, kami sampaikan bahwa donasi
                                yang
                                telah terkumpul telah berhasil disalurkan kepada para korban bencana tanah longsor
                                di
                                Batam pada 16 Juni 2023.</p>
                            <p class="card-text full-text" style="display: none;">Dengan penuh rasa syukur, kami
                                sampaikan bahwa donasi yang telah terkumpul telah berhasil disalurkan kepada para
                                korban
                                bencana tanah longsor di Batam pada 16 Juni 2023. Bantuan tersebut diharapkan dapat
                                meringankan beban mereka dan membantu proses pemulihan di tengah situasi yang sulit
                                ini.
                                Terima kasih atas kepedulian dan dukungan Anda.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary toggle-text">Baca Selengkapnya</a>
                                <small class="text-body-secondary">19 Juni 2023</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="images/gempa.jpg" alt="Gempa" class="card-img-top fixed-size-img">
                        <div class="card-body">
                            <p class="card-text short-text">Kami beritahukan pada 18 Desember 2023, donasi yang
                                telah
                                dihimpun telah disalurkan sepenuhnya kepada warga terdampak gempa di Cianjur.</p>
                            <p class="card-text full-text" style="display: none;">Kami beritahukan pada 18 Desember
                                2023, donasi yang telah dihimpun telah disalurkan sepenuhnya kepada warga terdampak
                                gempa di Cianjur. Semoga bantuan tersebut dapat membantu meringankan kesulitan
                                mereka
                                dan mendukung pemulihan kehidupan sehari-hari. Kami ucapkan terima kasih atas
                                dukungan
                                dan kontribusi yang telah diberikan.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="btn btn-outline-primary toggle-text">Baca Selengkapnya</a>
                                <small class="text-body-secondary">20 Desember 2023</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript Internal -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButtons = document.querySelectorAll(".toggle-text");

        toggleButtons.forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault(); // Mencegah perilaku default link
                const cardBody = this.closest(".card-body");
                const shortText = cardBody.querySelector(".short-text");
                const fullText = cardBody.querySelector(".full-text");

                if (shortText.style.display === "none") {
                    shortText.style.display = "-webkit-box"; // Tampilkan teks singkat
                    fullText.style.display = "none"; // Sembunyikan teks lengkap
                    this.textContent = "Baca Selengkapnya"; // Ubah teks tombol
                } else {
                    shortText.style.display = "none"; // Sembunyikan teks singkat
                    fullText.style.display = "block"; // Tampilkan teks lengkap
                    this.textContent = "Tampilkan Lebih Sedikit"; // Ubah teks tombol
                }
            });
        });
    });
    </script>



    <!-- Start Footer -->
    <div class="b-example-divider"></div>
    <div class="container">
        <footer class="py-5">
            <div class="row">
                <!-- Logo dan Deskripsi -->
                <div class="col-md-5 mb-3">
                    <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                        <img src="images/logo-footer.png" class="me-2" alt="Logo" width="280" height="auto">
                    </a>
                    <p class="text-body-secondary">
                        MyDonate adalah platform galang dana yang membantu Anda mengumpulkan dana untuk berbagai
                        keperluan, mulai dari bantuan medis, pendidikan, hingga proyek sosial. Kami berkomitmen
                        untuk
                        memberikan transparansi dan kemudahan dalam setiap donasi yang dilakukan.
                    </p>
                </div>

                <!-- Section 1: Navigasi -->
                <div class="col-6 col-md-2 mb-3">
                    <h5>Navigasi</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
                        <li class="nav-item mb-2"><a href="#about-us" class="nav-link p-0 text-body-secondary">About
                                Us</a></li>
                        <li class="nav-item mb-2"><a href="#open-donations"
                                class="nav-link p-0 text-body-secondary">Donate</a></li>
                        <li class="nav-item mb-2"><a href="#how-to-donate" class="nav-link p-0 text-body-secondary">How
                                to Donate</a></li>
                        <li class="nav-item mb-2"><a href="#documentation"
                                class="nav-link p-0 text-body-secondary">Documentation</a></li>
                    </ul>
                </div>

                <!-- Section 2: Kontak -->
                <div class="col-6 col-md-2 mb-3">
                    <h5>Kontak</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><span class="nav-link p-0 text-body-secondary">Alamat: Jl. Jend.
                                Urip
                                Sumoharjo No.116 Jebres, Kota Surakarta, Jawa Tengah 57129</span></li>
                        <li class="nav-item mb-2"><a href="https://wa.me/6281234567890"
                                class="nav-link p-0 text-body-secondary" target="_blank">WhatsApp: +62
                                812-3456-7890</a>
                        </li>
                    </ul>
                </div>

                <!-- Section 3: Media Sosial -->
                <div class="col-6 col-md-2 mb-3">
                    <h5>Media Sosial</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="link-body-emphasis" href="https://www.instagram.com/pamungcasse/" target="_blank">
                                <img src="images/ig.png" width="24" height="24" alt="Instagram"> Instagram
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="link-body-emphasis" href="https://www.facebook.com/" target="_blank">
                                <a class="link-body-emphasis" href="https://www.instagram.com/pamungcasse/"
                                    target="_blank">
                                    <img src="images/fb.png" width="24" height="24" alt="Facebook"> Facebook
                                </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="link-body-emphasis" href="https://twitter.com/" target="_blank">
                                <a class="link-body-emphasis" href="https://www.instagram.com/pamungcasse/"
                                    target="_blank">
                                    <img src="images/twitter.png" width="24" height="24" alt="Twitter"> Twitter
                                </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Hak Cipta -->
            <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                <p>&copy; MyDonate Company, Inc. All rights reserved.</p>
            </div>
        </footer>
        <!-- End Footer -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>