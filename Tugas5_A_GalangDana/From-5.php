<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mydonate</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      text-decoration: none;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: url(image/pexels-pixabay-47334.jpg) no-repeat center center/cover;
      background-attachment: fixed;
      margin-top: 50px;
      margin-bottom: 50px;
      min-height: 100vh;
      padding: 20px;
    }

    .body-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px 30px;
      border: 2px solid rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
    }

    .judul {
      text-align: center;
      font-size: 28px;
      margin-bottom: 5px;
      font-family: Arial, Helvetica, sans-serif;
      color: rgb(255, 226, 64);
    }

    .subjudul {
      text-align: center;
      font-size: 16px;
      margin-bottom: 20px;
      font-family: "poppins", sans-serif;
      color: rgb(253, 250, 74);
    }

    .input-box {
      width: 100%;
      margin-top: 15px;
    }

    .form-label {
      font-family: "poppins", sans-serif;
      font-size: 16px;
      color: rgb(250, 218, 38);
      margin-bottom: 5px;
      font-weight: bold;
      display: block;
    }

    .form-control {
      background: rgba(0, 0, 0, 0.7);
      padding: 12px 20px;
      border-radius: 10px;
      box-shadow: 0 0 15px 0 rgba(255, 255, 255, 0.5);
      color: #fff;
      width: 100%;
      border: none;
    }

    .button {
      width: 100%;
      height: 35px;
      margin-top: 20px;
      background-color: rgb(255, 255, 255);
      font-family: "poppins", sans-serif;
      font-size: 14px;
      font-weight: bold;
      color: rgb(0, 0, 0);
      border: none;
      border-radius: 10px;
      box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.5);
      cursor: pointer;
    }

    .button:hover {
      background-color: #000000;
      color: antiquewhite;
    }

    table {
      margin-top: 30px;
      border-collapse: collapse;
      width: 100%;
      background-color: white;
      color: black;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="body-container">
    <h2 class="judul">Form Donasi</h2>
    <p class="subjudul">Silahkan Isi Data Donasi Anda</p>

    <!-- FORM MENGARAH KE PHP -->
    <form action="proses_donasi.php" method="POST" enctype="multipart/form-data">

      <div class="input-box">
        <label class="form-label">Judul Donasi</label>
        <input type="text" name="judul" class="form-control" required/>
      </div>

      <div class="input-box">
        <label class="form-label">Deskripsi Donasi</label>
        <input type="text" name="deskripsi" class="form-control" required/>
      </div>

      <div class="input-box">
        <label class="form-label">Tampilan Donasi</label>
        <input type="file" name="gambar" class="form-control"/>
      </div>

      <div class="input-box">
        <label class="form-label">Tanggal Peluncuran Donasi</label>
        <input type="date" name="tanggal" class="form-control" required/>
      </div>

      <div class="input-box">
        <label class="form-label">Jenis Donasi</label>
        <label><input type="radio" name="jenis" value="Uang" checked/> Uang</label>
        <label><input type="radio" name="jenis" value="Barang"/> Barang</label>
      </div>

      <div class="input-box">
        <label class="form-label">Target Donasi</label>
        <label><input type="checkbox" name="target[]" value="Dalam Negeri"/> Dalam Negeri</label>
        <label><input type="checkbox" name="target[]" value="Luar Negeri"/> Luar Negeri</label>
      </div>

      <div class="input-box">
        <label class="form-label">Jumlah Nominal</label>
        <select name="nominal" class="form-control">
          <option value="1.000.000-5.000.000">1.000.000 - 5.000.000</option>
          <option value="5.000.000-10.000.000">5.000.000 - 10.000.000</option>
          <option value="10.000.000-20.000.000">10.000.000 - 20.000.000</option>
        </select>
      </div>

      <div>
        <button type="submit" class="button">Submit</button>
        <button type="reset" class="button">Reset</button>
        <button type="button" class="button" onclick="window.location.href='index2.php'">Kembali</button>
      </div>
    </form>
  </div>
</body>
</html>