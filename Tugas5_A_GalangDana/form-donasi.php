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

        <form id="form-donasi" action="proses_donasi.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Judul Donasi</label>
            <input type="text" name="judul_donasi" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Peluncuran Donasi</label>
            <input type="date" name="tgl_unggah" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Isi Deskripsi Donasi</label>
            <textarea name="isi_donasi" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Target Donasi</label>
            <div class="row">
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="target_donasi" id="target3jt" value="3000000" required>
                  <label class="form-check-label" for="target3jt">Rp3.000.000</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="target_donasi" id="target5jt" value="5000000">
                  <label class="form-check-label" for="target5jt">Rp5.000.000</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="target_donasi" id="target10jt" value="10000000">
                  <label class="form-check-label" for="target10jt">Rp10.000.000</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="target_donasi" id="target15jt" value="15000000">
                  <label class="form-check-label" for="target15jt">Rp15.000.000</label>
                </div>
              </div>
            </div>
          </div>



          <div class="mb-3">
            <label class="form-label">Gambar Donasi</label>
            <input type="file" name="gambar" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Bentuk Donasi</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="bentuk[]" value="Uang" id="donasiUang">
              <label class="form-check-label" for="donasiUang">Uang</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="bentuk[]" value="Barang" id="donasiBarang">
              <label class="form-check-label" for="donasiBarang">Barang</label>
            </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AJAX Script -->
  <script>
    const form = document.getElementById('form-donasi');

    form.addEventListener('submit', function(e) {
      e.preventDefault(); // cegah submit biasa

      const formData = new FormData(form);

      fetch('proses_donasi.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json()) // anggap server balas JSON
        .then(data => {
          if (data.success) {
            // redirect ke landing page jika berhasil
            window.location.href = 'index2.php';
          } else {
            alert('Gagal submit donasi: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          alert('Error saat submit: ' + error);
        });
    });
  </script>
</body>

</html>