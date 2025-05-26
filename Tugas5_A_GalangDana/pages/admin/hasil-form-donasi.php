<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <h2 class="mb-4">Data Donasi yang Tersimpan</h2>

    <?php
    if (!empty($_SESSION['donasi'])) {
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Target</th>
                        <th>Nominal</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($_SESSION['donasi'] as $index => $item) {
            $target = is_array($item['target']) ? implode(", ", $item['target']) : $item['target'];
            echo "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>{$item['judul']}</td>
                    <td>{$item['deskripsi']}</td>
                    <td>{$item['tanggal']}</td>
                    <td>{$item['jenis']}</td>
                    <td>{$target}</td>
                    <td>{$item['nominal']}</td>
                    <td>
                        <a href='hapus.php?index={$index}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='alert alert-warning'>Belum ada data donasi yang tersimpan.</p>";
    }
    ?>

    <br>
    <a href="../../index.php" class="btn btn-secondary">Kembali ke Beranda</a>

</body>
</html>