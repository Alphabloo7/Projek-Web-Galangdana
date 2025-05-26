<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Simulasi Donasi Bertahap</title>
  <style>
    body {
      font-family: Arial;
      background: #f5f5f5;
      padding: 20px;
    }
    .container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, select {
      padding: 10px;
      width: 100%;
      margin-bottom: 15px;
    }
    .button {
      padding: 10px;
      background-color: teal;
      color: white;
      border: none;
      width: 100%;
      cursor: pointer;
    }
    .result {
      margin-top: 20px;
      background: #e6f2ff;
      padding: 15px;
      border-radius: 5px;
      width: 100%;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Simulasi Donasi Bertahap</h2>
  <form method="POST">
    <label>Donasi Sultan (Rp)</label>
    <input type="number" name="harga" required min="1000000" value="10000000">

    <label>Uang Muka (%)</label>
    <input type="number" name="dp_persen" required min="20" max="100">

    <label>Tenor (Tahun)</label>
    <input type="number" name="tenor" required min="1" max="5">

    <button type="submit" name="hitung" class="button">Hitung Simulasi</button>
  </form>

  <?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hitung'])) {
  $harga = (int) $_POST['harga'];
  $dp_persen = (float) $_POST['dp_persen'];
  $tenor = (int) $_POST['tenor'];

  if ($dp_persen < 20 || $dp_persen > 100) {
    echo "<p style='color:red;'>Uang muka harus antara 20% - 100%</p>";
  } elseif ($tenor < 1 || $tenor > 5) {
    echo "<p style='color:red;'>Tenor maksimal 5 tahun</p>";
  } else {
    $dp = ($dp_persen / 100) * $harga;
    $sisa_pokok = $harga - $dp;
    $pokok_per_tahun = $sisa_pokok / $tenor;
    
    echo "<div class='result'>";
    echo "<h4>Rincian Simulasi Pertahun:</h4>";
    echo "<p>Harga Barang: Rp" . number_format($harga, 0, ',', '.') . "</p>";
    echo "<p>Uang Muka ({$dp_persen}%): Rp" . number_format($dp, 0, ',', '.') . "</p>";
    echo "<p>Sisa Pokok: Rp" . number_format($sisa_pokok, 0, ',', '.') . "</p>";

    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr>
            <th>Tahun</th>
            <th>Sisa Pokok Awal</th>
            <th>Cicilan Pokok</th>
            <th>Biaya Admin (1.5%)</th>
            <th>Total Bayar Tahun Ini</th>
            <th>Sisa Pokok Akhir</th>
          </tr>";

    $current_pokok = $sisa_pokok;
    for ($i = 1; $i <= $tenor; $i++) {
      $admin = 0.015 * $current_pokok;
      $total_bayar = $pokok_per_tahun + $admin;
      $sisa_akhir = $current_pokok - $pokok_per_tahun;

      echo "<tr>
              <td>$i</td>
              <td>Rp" . number_format($current_pokok, 0, ',', '.') . "</td>
              <td>Rp" . number_format($pokok_per_tahun, 0, ',', '.') . "</td>
              <td>Rp" . number_format($admin, 0, ',', '.') . "</td>
              <td>Rp" . number_format($total_bayar, 0, ',', '.') . "</td>
              <td>Rp" . number_format($sisa_akhir, 0, ',', '.') . "</td>
            </tr>";

      $current_pokok = $sisa_akhir;
    }

    echo "</table>";

    // Tambahkan tombol kembali ke landing page
    echo "<div style='margin-top: 20px; text-align: center;'>
            <a href='index2.php' style='
              display: inline-block;
              padding: 10px 20px;
              background-color: teal;
              color: white;
              text-decoration: none;
              border-radius: 5px;
            '>Kembali ke Landing Page</a>
          </div>";

    echo "</div>";
  }
}
?>

</div>

</body>
</html>
