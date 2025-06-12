-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jun 2025 pada 17.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydonate4`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `nama_lengkap`, `password`) VALUES
(1, 'alfa', 'alfa@admin.com', 'Muhammad Alfa', 'alfa123'),
(2, 'hilmy', 'hilmy@admin.com', 'Naufal Hilmy', 'hilmy123'),
(3, 'gilang', 'gilang@admin.com', 'Gilang Pamungkas', 'gilang123'),
(4, 'zahra', 'zahra@admin.com', 'Zahra Raufatul', 'zahra123'),
(5, 'may', 'may@admin.com', 'Hasna May', 'may123'),
(6, 'admin', 'login@admin.com', 'Admin Umum', 'admin123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumentasi`
--

CREATE TABLE `dokumentasi` (
  `id_dokumentasi` int(11) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `desc_dokumentasi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tgl_upload` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumentasi`
--

INSERT INTO `dokumentasi` (`id_dokumentasi`, `judul`, `desc_dokumentasi`, `foto`, `tgl_upload`, `status`, `id_admin`) VALUES
(1, 'Banjir Semarang', 'Donasi telah tersalurkan untuk bencana banjir di kota Semarang pada 17 Maret 2022. Donasi yang terkumpul berupa uang sejumlah 8.450.000. Terima Kasih atas bantuan yang telah anda diberikan untuk korban bencana ini. Informasi tambahan: Bantuan ini disalurkan melalui posko bencana setempat.', 'iamges/banjirSemarang.jpeg', '2022-03-18', 'published', 1),
(2, 'Banjir Bantul', 'Pada 15 Desember 2022, tim kami telah menyalurkan donasi berupa bahan makanan pokok serta uang tunai sejumlah 3.450.000 kepada warga terdampak bencana banjir di Bantul, Yogyakarta. Bantuan ini diharapkan dapat meringankan beban mereka dalam menghadapi situasi sulit. Informasi tambahan: Donasi disalurkan melalui relawan setempat.', 'images/BanjirBantul.jpg', '2022-12-17', 'published', 1),
(3, 'Tanah Longsor Batam', 'Dengan penuh rasa syukur, kami sampaikan bahwa donasi yang telah terkumpul telah berhasil disalurkan kepada para korban bencana tanah longsor di Batam pada 16 Juni 2023. Bantuan tersebut diharapkan dapat meringankan beban mereka dan membantu proses pemulihan di tengah situasi yang sulit ini. Terima kasih atas kepedulian dan dukungan Anda.', 'images/tanah-longsor.jpg', '2023-06-19', 'published', 1),
(4, 'Gempa Cianjur', 'Kami beritahukan pada 18 Desember 2023, donasi yang telah dihimpun telah disalurkan sepenuhnya kepada warga terdampak gempa di Cianjur. Semoga bantuan tersebut dapat membantu meringankan kesulitan mereka dan mendukung pemulihan kehidupan sehari-hari. Kami ucapkan terima kasih atas dukungan dan kontribusi yang telah diberikan.', 'images/gempa.jpg', '2023-12-20', 'published', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL,
  `judul_donasi` varchar(100) DEFAULT NULL,
  `tgl_unggah` date DEFAULT NULL,
  `isi_donasi` text DEFAULT NULL,
  `target_donasi` int(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status_donasi` enum('Active','Non Active') NOT NULL DEFAULT 'Non Active',
  `bentuk_donasi` enum('Barang','Uang') DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_mitra` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`id_donasi`, `judul_donasi`, `tgl_unggah`, `isi_donasi`, `target_donasi`, `gambar`, `status_donasi`, `bentuk_donasi`, `id_kategori`, `id_admin`, `id_mitra`) VALUES
(1, 'Banjir di Kabupaten Bandung', '2025-06-01', 'Masyarakat Kabupaten Bandung memerlukan bantuan Anda untuk penanganan krisis setelah banjir yang merendam empat kecamatan dan menyebabkan ratusan warga mengungsi.', 10000000, 'images/OpenDonation1.png', 'Active', 'Uang', 1, 1, 1),
(2, 'Tsunami di Aceh', '2025-06-01', 'Peringatan! Tsunami dahsyat telah melanda Aceh pada 26 Desember 2004, menyebabkan lebih dari 170.000 korban jiwa. Mari bantu saudara-saudara kita yang terdampak.', 10000000, 'images/OpenDonation2.png', 'Active', 'Uang', 1, 1, 1),
(3, 'Krisis Air Bersih di Sekolah Indonesia', '2025-06-01', 'Sebanyak 3,1 juta siswa di Indonesia belum memiliki akses ke air bersih di sekolah mereka. Mari bantu anak-anak kita mendapatkan fasilitas air bersih yang layak.', 5000000, 'images/OpenDonation3.png', 'Active', 'Uang', 3, 1, 2),
(4, 'Kebakaran Hutan Kumpeh', '2025-06-01', 'Hutan di Kecamatan Kumpeh, Muarojambi, Jambi, telah terbakar, mempengaruhi masyarakat sekitar. Mari bantu menyediakan fasilitas kesehatan bagi mereka yang terdampak.', 3000000, 'images/OpenDonation4.png', 'Active', 'Uang', 4, 1, 2),
(5, 'Gempa Bumi di Tuban', '2025-06-01', 'Gempa berkekuatan 6,1 skala Richter mengguncang Kabupaten Tuban, Jawa Timur, pada 22 Maret 2024, menyebabkan kerusakan bangunan dan memerlukan bantuan segera. Mari bantu mereka pulih dengan menyediakan makanan dan obat-obatan.', 10000000, 'images/OpenDonation5.png', 'Active', 'Uang', 1, 1, 2),
(6, 'Kekeringan di Nusa Tenggara Timur', '2025-06-01', 'Warga Nusa Tenggara Timur saat ini menderita akibat kekeringan parah, bantu mereka mendapatkan air bersih!', 3000000, 'images/OpenDonation6.png', 'Active', 'Uang', 5, 1, 2),
(10, 'Nyoba 2', '2025-06-01', 'YAYAYAYYAYAYAYAYAYAYAY', 15000000, 'uploads/1748705044_683b1f14611a2.jpg', 'Active', 'Uang', 2, 3, 2),
(12, 'Nyoba 3', '2025-06-01', 'apa ajalah', 15000000, 'uploads/1748790170_683c6b9a33d49.png', 'Active', 'Barang', 1, NULL, NULL),
(13, 'Nyoba lagi', '2025-06-30', 'Yayayayyaa', 5000000, 'uploads/1748790413_683c6c8da273d.png', '', 'Uang', 1, NULL, NULL),
(14, 'Nyoba 5', '2025-06-01', 'Yayayayyaa', 5000000, 'uploads/1748790480_683c6cd09981a.png', 'Active', 'Uang', 1, NULL, NULL),
(15, 'Nyoba 5', '2025-06-30', 'Yayayayyaa', 5000000, 'uploads/1748790508_683c6cecdc765.png', '', 'Uang', 1, NULL, NULL),
(16, 'Nyoba 5', '2025-06-01', 'Yayayayyaa', 5000000, 'uploads/1748790566_683c6d26609c9.png', '', 'Uang', 1, NULL, NULL),
(17, 'Apalagii', '2025-06-01', 'Yayayayyaa', 5000000, 'uploads/1748791011_683c6ee39b1ce.png', '', 'Uang', 1, NULL, NULL),
(18, 'Apalagii yaa', '2025-06-30', 'Yayayayyaa', 5000000, 'uploads/1748791215_683c6fafaf68c.png', 'Non Active', 'Uang', 1, NULL, NULL),
(19, 'Apalagii yaa', '2025-06-30', 'Yayayayyaa', 5000000, 'uploads/1748791251_683c6fd3cb551.png', 'Non Active', 'Uang', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_donasi`
--

CREATE TABLE `kategori_donasi` (
  `id_kategori` int(11) NOT NULL,
  `jenis_kategori` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_donasi`
--

INSERT INTO `kategori_donasi` (`id_kategori`, `jenis_kategori`) VALUES
(1, 'Bencana Alam'),
(2, 'Sosial'),
(3, 'Pendidikan'),
(4, 'Kesehatan'),
(5, 'Lingkungan'),
(6, 'Keagamaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id_komentar` int(11) NOT NULL,
  `isi_komentar` text DEFAULT NULL,
  `tgl_komentar` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_mitra` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id_komentar`, `isi_komentar`, `tgl_komentar`, `id_user`, `id_mitra`) VALUES
(1, 'Semoga bantuan ini bisa bermanfaat untuk yang membutuhkan', '2025-05-27', 2, 1),
(2, 'Terima kasih Yayasan Berkah Sejahtera atas dedikasinya', '2025-05-27', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `isi_laporan` text DEFAULT NULL,
  `tgl_laporan` date DEFAULT NULL,
  `status_laporan` enum('Pending','Resolved') DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `isi_laporan`, `tgl_laporan`, `status_laporan`, `id_user`, `id_admin`) VALUES
(1, 'Donasi saya tidak tercatat di sistem setelah transfer', '2025-05-01', 'Pending', 1, 2),
(2, 'Sistem error saat input nominal donasi', '2025-05-05', 'Resolved', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitra`
--

CREATE TABLE `mitra` (
  `id_mitra` int(11) NOT NULL,
  `nama_mitra` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_mitra` enum('Active','Non Active','Pending') DEFAULT NULL,
  `bergabung_mitra` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mitra`
--

INSERT INTO `mitra` (`id_mitra`, `nama_mitra`, `no_telepon`, `alamat`, `password`, `email`, `status_mitra`, `bergabung_mitra`) VALUES
(1, 'Yayasan Berkah Sejahtera', '081234567890', 'Jl. Melati No. 12, Jakarta', 'pass123', 'berkah@mail.com', 'Pending', '2025-05-27 13:00:09'),
(2, 'Komunitas Peduli Lingkungan', '089876543210', 'Jl. Kenanga No. 5, Bandung', 'secure456', 'lingkungan@mail.com', 'Pending', '2025-05-27 13:00:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tgl_donasi` date DEFAULT NULL,
  `donasi_user` int(100) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `id_donasi` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tgl_donasi`, `donasi_user`, `metode_pembayaran`, `id_donasi`, `id_user`) VALUES
(7, '2025-05-01', 50000, 'spay', 1, 1),
(8, '2025-05-02', 100000, 'dana', 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` int(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `bergabung_user` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_user` enum('Active','Non Active','Banned') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `password`, `no_telepon`, `alamat`, `username`, `bergabung_user`, `status_user`) VALUES
(1, 'Admin', 'admin@gmail.com', '0', 2147483647, 'Jl. Melati No. 1, Jakarta', 'admin', '2025-01-15 00:00:00', 'Active'),
(2, 'Anomali', 'anomali@gmail.com', '0', 2147483647, 'Jl. Mawar No. 3, Bandung', 'anomali', '2025-03-20 00:00:00', 'Active'),
(3, 'admin', 'admin@admin.com', '$2y$10$SJw5EDD/CqzKFAiQg.9y8eyFxwzotw8tjWroMyQXacg', NULL, NULL, NULL, '2025-05-31 09:24:22', NULL),
(4, 'May', 'maynyoba@gmail.com', '$2y$10$aOg2u0mZ3QpO8ujfOKj.WuJKCwYYujcdFQ4xsP5LwAQ', 899999999, 'Mesen', 'maynyoba', '2025-05-31 17:00:00', 'Active'),
(5, 'May', 'may@gmail.com', '$2y$10$/qn0FAfLsnW77fXDk9TPrexFqqHhoyJ4SdQ/4lPU1uO', 899999999, 'Mesen', 'May', '2025-05-31 17:00:00', 'Active'),
(6, 'May', 'maymay@gmail.com', '$2y$10$TIztzK45/ecpQtk1juRtteC5K6z2Ia4gE3vkCScOZzID7PQjvDSYq', 8123456, 'Mesenn', 'May', '2025-05-31 17:00:00', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_mitra` (`id_mitra`);

--
-- Indeks untuk tabel `kategori_donasi`
--
ALTER TABLE `kategori_donasi`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_mitra` (`id_mitra`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id_mitra`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_donasi` (`id_donasi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `dokumentasi`
--
ALTER TABLE `dokumentasi`
  MODIFY `id_dokumentasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `kategori_donasi`
--
ALTER TABLE `kategori_donasi`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id_komentar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id_mitra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD CONSTRAINT `dokumentasi_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_donasi` (`id_kategori`),
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `donasi_ibfk_3` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`);

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `komentar_ibfk_2` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`);

--
-- Ketidakleluasaan untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id_donasi`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
