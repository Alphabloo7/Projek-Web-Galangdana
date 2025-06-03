<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Ajuan Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-5">
  <div class="container mt-5" style="max-width: 800px;">
    <div class="card shadow-lg">
      <div class="card-body">
        <h3 class="text-center text-primary">Form Donasi</h3>
        <p class="text-center text-muted mb-4">Silakan isi data donasi dengan lengkap!</p>

        <form action="proses_donasi.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label fw-bold">Judul Donasi</label>
            <input type="text" name="judul_donasi" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Tanggal Peluncuran Donasi</label>
            <input type="date" name="tgl_unggah" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Isi Deskripsi Donasi</label>
            <textarea name="isi_donasi" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Gambar Donasi</label>
            <input type="file" name="gambar" class="form-control">
          </div>

          <div class="mb-3">
            <label for="kategori" class="form-label fw-bold">Kategori Donasi</label>
            <select id="kategori" name="id_kategori" class="form-select" required>
              <option value="" disabled selected>Pilih Kategori</option>
              <option value="1">Bencana Alam</option>
              <option value="2">Sosial</option>
              <option value="3">Pendidikan</option>
              <option value="4">Kesehatan</option>
              <option value="5">Lingkungan</option>
              <option value="6">Keagamaan</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Bentuk Donasi</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="bentuk[]" value="Uang" id="donasiUang">
              <label class="form-check-label" for="donasiUang">Uang</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="bentuk[]" value="Barang" id="donasiBarang">
              <label class="form-check-label" for="donasiBarang">Barang</label>
            </div>
          </div>

          <div class="mb-3" id="inputUang" style="display: none;">
            <label class="form-label">Target Donasi (Rp)</label>
            <input type="text" name="nominal" id="nominalInput" class="form-control"
            placeholder="Masukkan nominal (hanya angka & titik)">
          </div>

          <div class="mb-3" id="inputBarang" style="display: none;">
            <label class="form-label">Jenis Barang</label>
            <input type="text" name="jenis_barang" id="jenisBarangInput" class="form-control" placeholder="Masukkan jenis barang">

            <label class="form-label mt-3">Target Donasi (Rp) untuk Barang</label>
            <input type="text" name="nominal_barang" id="nominalBarangInput" class="form-control" placeholder="Masukkan nominal (hanya angka & titik)">
          </div>

          
          <div class="d-flex justify-content-between">
            <input type="hidden" name="status" value="Non Active">

            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-warning">Reset</button>
            <a href="index2.php" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  
  <script> // js buat kolom pilihan bentuk uang dan barang
    document.addEventListener("DOMContentLoaded", function () {
      const radioUang = document.getElementById("donasiUang");
      const radioBarang = document.getElementById("donasiBarang");
      const inputUangContainer = document.getElementById("inputUang");
      const inputBarangContainer = document.getElementById("inputBarang");

      const inputNominalUang = document.getElementById("nominalInput");
      const inputJenisBarang = document.getElementById("jenisBarangInput");
      const inputNominalBarang = document.getElementById("nominalBarangInput");

      // Awalnya sembunyikan kedua container
      inputUangContainer.style.display = "none";
      inputBarangContainer.style.display = "none";

      // Fungsi untuk update tampilan input sesuai pilihan radio
      function updateForm() {
        if (radioUang.checked) {
          inputUangContainer.style.display = "block";
          inputNominalUang.focus();

          inputBarangContainer.style.display = "none";
          inputJenisBarang.value = "";
          inputNominalBarang.value = "";
        } else if (radioBarang.checked) {
          inputBarangContainer.style.display = "block";
          inputJenisBarang.focus();

          inputUangContainer.style.display = "none";
          inputNominalUang.value = "";
        } else {
          // Kalau gak ada pilihan
          inputUangContainer.style.display = "none";
          inputBarangContainer.style.display = "none";

          inputNominalUang.value = "";
          inputJenisBarang.value = "";
          inputNominalBarang.value = "";
        }
      }

      // Pas user ganti radio, update tampilan
      radioUang.addEventListener("change", updateForm);
      radioBarang.addEventListener("change", updateForm);

      // Validasi input nominal uang & barang (hanya angka dan titik)
      function filterAngkaTitik(e) {
        e.target.value = e.target.value.replace(/[^0-9.]/g, '');
      }

      inputNominalUang.addEventListener("input", filterAngkaTitik);
      inputNominalBarang.addEventListener("input", filterAngkaTitik);

      // Inisialisasi
      updateForm();
    });

    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>