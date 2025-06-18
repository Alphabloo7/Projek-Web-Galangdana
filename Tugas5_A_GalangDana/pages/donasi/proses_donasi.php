<?php
// proses_donasi.php
header('Content-Type: application/json');
session_start();
include '../../koneksi.php';
include '../auth/keamanan.php';

$response = ['success' => false, 'message' => ''];

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    // Get and validate input data
    $judul = trim($_POST['judul_donasi'] ?? '');
    $isi = trim($_POST['isi_donasi'] ?? '');
    $tgl = trim($_POST['tgl_unggah'] ?? '');
    $kategori = intval($_POST['id_kategori'] ?? 0);
    $status_donasi = trim($_POST['status_donasi'] ?? 'Non Active');
    $bentuk = trim($_POST['bentuk_donasi'] ?? '');

    // Basic validation
    if (empty($judul)) {
        throw new Exception("Judul donasi harus diisi");
    }

    if (empty($isi)) {
        throw new Exception("Deskripsi donasi harus diisi");
    }

    if (empty($tgl)) {
        throw new Exception("Tanggal peluncuran harus diisi");
    }

    if (!in_array($bentuk, ['uang', 'barang'])) {
        throw new Exception("Bentuk donasi tidak valid");
    }

    if ($kategori <= 0 || $kategori > 6) {
        throw new Exception("Kategori donasi tidak valid");
    }

    // Validate date
    $date_obj = DateTime::createFromFormat('Y-m-d', $tgl);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $tgl) {
        throw new Exception("Format tanggal tidak valid");
    }

    // Check if date is not in the past
    $today = new DateTime();
    if ($date_obj < $today->setTime(0, 0, 0)) {
        throw new Exception("Tanggal peluncuran tidak boleh di masa lalu");
    }

    // Process image upload
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error saat mengunggah file: " . $file['error']);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($ext, $allowed_ext)) {
            throw new Exception("Format file tidak didukung. Gunakan JPG, PNG, atau GIF");
        }

        if ($file['size'] > $max_size) {
            throw new Exception("Ukuran file terlalu besar. Maksimal 2MB");
        }

        // Check if uploads directory exists
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception("Gagal membuat direktori upload");
            }
        }

        // Generate unique filename
        $gambar = 'donasi_' . time() . '_' . uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $gambar;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Gagal mengunggah gambar");
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert main donation record
        $stmt = $conn->prepare("INSERT INTO donasi (judul_donasi, isi_donasi, tgl_unggah, gambar, id_kategori, status_donasi, bentuk_donasi) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $stmt->bind_param("ssssiss", $judul, $isi, $tgl, $gambar, $kategori, $status_donasi, $bentuk);

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data donasi: " . $stmt->error);
        }

        $id_donasi = $conn->insert_id;

        // Process based on donation type
        if ($bentuk === 'uang') {
            $target_uang = intval($_POST['target_donasi'] ?? 0);

            if ($target_uang < 1000) {
                throw new Exception("Target uang minimal Rp 1.000");
            }

            $stmt2 = $conn->prepare("INSERT INTO donasi_uang (id_donasi, target_uang, terkumpul_uang) VALUES (?, ?, 0)");
            if (!$stmt2) {
                throw new Exception("Database prepare error: " . $conn->error);
            }

            $stmt2->bind_param("ii", $id_donasi, $target_uang);
            if (!$stmt2->execute()) {
                throw new Exception("Gagal menyimpan data donasi uang: " . $stmt2->error);
            }
        } elseif ($bentuk === 'barang') {
            $jenis_paket = trim($_POST['jenis_paket'] ?? '');
            $daftar_barang = trim($_POST['daftar_barang'] ?? '');
            $daftar_harga = trim($_POST['daftar_harga'] ?? '');

            if (!in_array($jenis_paket, ['biasa', 'istimewa'])) {
                throw new Exception("Jenis paket tidak valid");
            }

            if (empty($daftar_barang) || empty($daftar_harga)) {
                throw new Exception("Data barang tidak lengkap");
            }

            // Validate and calculate total price
            $harga_arr = array_map('trim', explode(',', $daftar_harga));
            $barang_arr = array_map('trim', explode(',', $daftar_barang));

            $target_paket = ($jenis_paket === 'istimewa') ? 3 : 2;

            if (count($barang_arr) !== $target_paket || count($harga_arr) !== $target_paket) {
                throw new Exception("Jumlah barang tidak sesuai dengan paket yang dipilih");
            }

            $harga_total = 0;
            foreach ($harga_arr as $h) {
                $harga = intval($h);
                if ($harga < 1000) {
                    throw new Exception("Setiap harga barang minimal Rp 1.000");
                }
                $harga_total += $harga;
            }

            if ($harga_total <= 0) {
                throw new Exception("Total harga paket tidak valid");
            }

            $stmt3 = $conn->prepare("INSERT INTO donasi_barang (id_donasi, jenis_paket, target_paket, harga_paket, terkumpul_paket, daftar_barang) VALUES (?, ?, ?, ?, 0, ?)");
            if (!$stmt3) {
                throw new Exception("Database prepare error: " . $conn->error);
            }

            $stmt3->bind_param("isiis", $id_donasi, $jenis_paket, $target_paket, $harga_total, $daftar_barang);
            if (!$stmt3->execute()) {
                throw new Exception("Gagal menyimpan data donasi barang: " . $stmt3->error);
            }
        }

        // Commit transaction
        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Donasi berhasil ditambahkan';
        $response['id_donasi'] = $id_donasi;
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();

        // Delete uploaded image if exists
        if (!empty($gambar) && file_exists(__DIR__ . '/uploads/' . $gambar)) {
            unlink(__DIR__ . '/uploads/' . $gambar);
        }

        throw $e;
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();

    // Log error for debugging
    error_log("Donation form error: " . $e->getMessage());
}

// Send JSON response
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>

<?php
// form-donasi.php
session_start();
include '../../koneksi.php';
include '../auth/keamanan.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Ajuan Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('images/bg6.jpg');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        .card-body {
            background: url(images/bg4.png) no-repeat center center/cover !important;
            border-radius: 5px;
            border: none;
        }

        .loading {
            display: none;
        }

        .form-error {
            border-color: #dc3545 !important;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="container mt-5" style="max-width: 800px;">
        <div class="card shadow-lg">
            <div class="card-body">
                <h3 class="text-center text-primary">Form Donasi</h3>
                <p class="text-center text-muted mb-4">Silakan isi data donasi dengan lengkap!</p>

                <!-- Alert untuk pesan -->
                <div id="alert-container"></div>

                <form id="form-donasi" action="proses_donasi.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul_donasi" class="form-label">Judul Donasi *</label>
                        <input type="text" name="judul_donasi" id="judul_donasi" class="form-control" required>
                        <div class="error-message" id="error-judul"></div>
                    </div>

                    <div class="mb-3">
                        <label for="tgl_unggah" class="form-label">Tanggal Peluncuran Donasi *</label>
                        <input type="date" name="tgl_unggah" id="tgl_unggah" class="form-control" required>
                        <div class="error-message" id="error-tanggal"></div>
                    </div>

                    <div class="mb-3">
                        <label for="isi_donasi" class="form-label">Isi Deskripsi Donasi *</label>
                        <textarea name="isi_donasi" id="isi_donasi" class="form-control" rows="4" required></textarea>
                        <div class="error-message" id="error-deskripsi"></div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Donasi</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                        <div class="form-text">Rekomendasi ukuran: 400x300 piksel. Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                        <div class="error-message" id="error-gambar"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bentuk Donasi *</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="bentuk_donasi" id="donasi_uang" value="uang" required>
                            <label class="form-check-label" for="donasi_uang">Uang</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="bentuk_donasi" id="donasi_barang" value="barang">
                            <label class="form-check-label" for="donasi_barang">Barang</label>
                        </div>
                        <div class="error-message" id="error-bentuk"></div>
                    </div>

                    <!-- Form Uang -->
                    <div id="form-uang" class="mb-3 d-none">
                        <label for="target_donasi" class="form-label">Target Uang yang Dikumpulkan (Rp) *</label>
                        <input type="number" class="form-control" name="target_donasi" id="target_donasi"
                            placeholder="Contoh: 10000000" min="1000">
                        <div class="form-text">Minimal Rp 1.000</div>
                        <div class="error-message" id="error-target"></div>
                    </div>

                    <!-- Form Barang -->
                    <div id="form-barang" class="mb-3 d-none">
                        <label for="paket_select" class="form-label">Pilih Paket Barang *</label>
                        <select class="form-select mb-3" id="paket_select" name="jenis_paket">
                            <option value="">-- Pilih Paket --</option>
                            <option value="biasa">Paket Biasa (2 Barang)</option>
                            <option value="istimewa">Paket Istimewa (3 Barang)</option>
                        </select>
                        <div class="error-message" id="error-paket"></div>

                        <div id="barang-fields"></div>

                        <div class="mt-3">
                            <label for="daftar_barang" class="form-label">Ringkasan Barang</label>
                            <input type="text" class="form-control" name="daftar_barang" id="daftar_barang" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="daftar_harga" class="form-label">Ringkasan Harga</label>
                            <input type="text" class="form-control" name="daftar_harga" id="daftar_harga" readonly>
                        </div>
                        <div class="mt-2">
                            <label for="total_harga" class="form-label">Total Harga Paket</label>
                            <input type="text" class="form-control" id="total_harga" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_kategori" class="form-label">Kategori Donasi *</label>
                        <select id="id_kategori" name="id_kategori" class="form-select" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="1">Bencana Alam</option>
                            <option value="2">Sosial</option>
                            <option value="3">Pendidikan</option>
                            <option value="4">Kesehatan</option>
                            <option value="5">Lingkungan</option>
                            <option value="6">Keagamaan</option>
                        </select>
                        <div class="error-message" id="error-kategori"></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <input type="hidden" name="status_donasi" value="Non Active">
                        <a href="index2.php" class="btn btn-secondary">Kembali</a>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="loading spinner-border spinner-border-sm me-2" role="status_donasi"></span>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Form elements
        const form = document.getElementById('form-donasi');
        const radioUang = document.getElementById('donasi_uang');
        const radioBarang = document.getElementById('donasi_barang');
        const formUang = document.getElementById('form-uang');
        const formBarang = document.getElementById('form-barang');
        const paketSelect = document.getElementById('paket_select');
        const barangFields = document.getElementById('barang-fields');
        const daftarBarang = document.getElementById('daftar_barang');
        const daftarHarga = document.getElementById('daftar_harga');
        const totalHarga = document.getElementById('total_harga');
        const submitBtn = form.querySelector('button[type="submit"]');
        const loading = submitBtn.querySelector('.loading');

        // Set minimum date to today
        document.getElementById('tgl_unggah').min = new Date().toISOString().split('T')[0];

        // Form visibility management
        function updateFormVisibility() {
            formUang.classList.add('d-none');
            formBarang.classList.add('d-none');

            // Clear validations
            clearFieldError('target_donasi');
            clearFieldError('paket_select');

            if (radioUang.checked) {
                formUang.classList.remove('d-none');
                document.getElementById('target_donasi').required = true;
                document.getElementById('paket_select').required = false;
            } else if (radioBarang.checked) {
                formBarang.classList.remove('d-none');
                document.getElementById('target_donasi').required = false;
                document.getElementById('paket_select').required = true;
            }
        }

        // Event listeners for radio buttons
        radioUang.addEventListener('change', updateFormVisibility);
        radioBarang.addEventListener('change', updateFormVisibility);

        // Package selection handler
        paketSelect.addEventListener('change', function() {
            const jumlah = this.value === 'istimewa' ? 3 : this.value === 'biasa' ? 2 : 0;
            barangFields.innerHTML = '';
            daftarBarang.value = '';
            daftarHarga.value = '';
            totalHarga.value = '';

            for (let i = 1; i <= jumlah; i++) {
                const row = document.createElement('div');
                row.classList.add('mb-2', 'row');
                row.innerHTML = `
                    <div class="col-md-6">
                        <input type="text" class="form-control barang-nama" 
                               placeholder="Nama Barang ${i}" required>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control barang-harga" 
                               placeholder="Harga Barang ${i}" min="1000" required>
                    </div>
                `;
                barangFields.appendChild(row);
            }

            // Add event listeners to new fields
            barangFields.querySelectorAll('.barang-nama, .barang-harga').forEach(input => {
                input.addEventListener('input', updateRingkasan);
            });
        });

        // Update summary function
        function updateRingkasan() {
            const namaList = Array.from(document.querySelectorAll('.barang-nama'))
                .map(input => input.value.trim())
                .filter(Boolean);

            const hargaList = Array.from(document.querySelectorAll('.barang-harga'))
                .map(input => input.value.trim())
                .filter(Boolean)
                .map(val => parseInt(val) || 0);

            daftarBarang.value = namaList.join(', ');
            daftarHarga.value = hargaList.join(', ');

            const total = hargaList.reduce((sum, harga) => sum + harga, 0);
            totalHarga.value = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Validation functions
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById('error-' + fieldId.replace('_', '-').replace('id-', ''));

            field.classList.add('form-error');
            if (errorDiv) {
                errorDiv.textContent = message;
            }
        }

        function clearFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById('error-' + fieldId.replace('_', '-').replace('id-', ''));

            field.classList.remove('form-error');
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        }

        function showAlert(message, type = 'danger') {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            alertContainer.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function validateForm() {
            let isValid = true;

            // Clear all previous errors
            document.querySelectorAll('.form-error').forEach(el => el.classList.remove('form-error'));
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            // Validate basic fields
            const requiredFields = [{
                    id: 'judul_donasi',
                    name: 'Judul donasi'
                },
                {
                    id: 'tgl_unggah',
                    name: 'Tanggal peluncuran'
                },
                {
                    id: 'isi_donasi',
                    name: 'Deskripsi donasi'
                },
                {
                    id: 'id_kategori',
                    name: 'Kategori donasi'
                }
            ];

            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element.value.trim()) {
                    showFieldError(field.id, `${field.name} harus diisi`);
                    isValid = false;
                }
            });

            // Validate donation type
            if (!radioUang.checked && !radioBarang.checked) {
                showFieldError('donasi_uang', 'Pilih bentuk donasi');
                isValid = false;
            }

            // Validate based on donation type
            if (radioUang.checked) {
                const targetDonasi = document.getElementById('target_donasi');
                const target = parseInt(targetDonasi.value) || 0;

                if (target < 1000) {
                    showFieldError('target_donasi', 'Target donasi minimal Rp 1.000');
                    isValid = false;
                }
            }

            if (radioBarang.checked) {
                if (!paketSelect.value) {
                    showFieldError('paket_select', 'Pilih jenis paket');
                    isValid = false;
                } else {
                    // Validate all item fields are filled
                    const namaInputs = document.querySelectorAll('.barang-nama');
                    const hargaInputs = document.querySelectorAll('.barang-harga');

                    let itemError = false;
                    namaInputs.forEach((input, index) => {
                        if (!input.value.trim()) {
                            input.classList.add('form-error');
                            itemError = true;
                        }
                    });

                    hargaInputs.forEach((input, index) => {
                        const harga = parseInt(input.value) || 0;
                        if (harga < 1000) {
                            input.classList.add('form-error');
                            itemError = true;
                        }
                    });

                    if (itemError) {
                        showFieldError('paket_select', 'Lengkapi semua data barang (harga minimal Rp 1.000)');
                        isValid = false;
                    }
                }
            }

            // Validate image file
            const gambarInput = document.getElementById('gambar');
            if (gambarInput.files.length > 0) {
                const file = gambarInput.files[0];
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    showFieldError('gambar', 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF');
                    isValid = false;
                } else if (file.size > maxSize) {
                    showFieldError('gambar', 'Ukuran file terlalu besar. Maksimal 2MB');
                    isValid = false;
                }
            }

            return isValid;
        }

        // Form submission handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                showAlert('Mohon perbaiki kesalahan pada form', 'danger');
                return;
            }

            // Show loading state
            loading.style.display = 'inline-block';
            submitBtn.disabled = true;

            const formData = new FormData(form);

            fetch('proses_donasi.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('Donasi berhasil ditambahkan!', 'success');
                        setTimeout(() => {
                            window.location.href = '../../index2.php';
                        }, 2000);
                    } else {
                        showAlert('Gagal submit donasi: ' + (data.message || 'Unknown error'), 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.', 'danger');
                })
                .finally(() => {
                    // Hide loading state
                    loading.style.display = 'none';
                    submitBtn.disabled = false;
                });
        });

        // Reset form handler
        form.addEventListener('reset', function() {
            // Clear all errors
            document.querySelectorAll('.form-error').forEach(el => el.classList.remove('form-error'));
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.getElementById('alert-container').innerHTML = '';

            // Hide donation type forms
            formUang.classList.add('d-none');
            formBarang.classList.add('d-none');

            // Clear dynamic fields
            barangFields.innerHTML = '';
            daftarBarang.value = '';
            daftarHarga.value = '';
            totalHarga.value = '';
        });

        // Real-time validation for key fields
        document.getElementById('judul_donasi').addEventListener('blur', function() {
            if (this.value.trim()) clearFieldError('judul_donasi');
        });

        document.getElementById('isi_donasi').addEventListener('blur', function() {
            if (this.value.trim()) clearFieldError('isi_donasi');
        });

        document.getElementById('target_donasi').addEventListener('input', function() {
            const target = parseInt(this.value) || 0;
            if (target >= 1000) clearFieldError('target_donasi');
        });
    </script>
</body>

</html>