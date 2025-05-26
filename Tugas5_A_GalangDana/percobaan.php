<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Bencana</title>
    <style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f5f5;
}

.container {
  width: 100%;
  margin: auto;
  background-color: white;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.content {
  display: flexbox;
}

.title {
  background-color: #003366;
  height: 600px;
  color: white;
  padding: 15px;
  font-size: 24px;
  display: relative;
  justify-content: space-between;
}

.uptitle {
  margin-left: 30px;
}

.picture {
  display: flexbox;
  justify-items: auto;
  width: 200px;
  height: 100px;
  margin-left: 30px;
}

.date {
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  font-size: 18px;
  margin-top: -15px;
}

.description {
  display: flex;
  margin-left: 630px;
  justify-content: space-between;
}

.identity {
  margin-left: 30px;
  margin-top: -50px;
}

.profile-picture {
  border-radius: 25px;
  width: 70px;
  height: 70px;
}

.nama-yayasan {
  font-family: "Times New Roman", Times, serif;
  font-size: 20px;
  font-weight: bold;
  margin-top: -70px;
  margin-left: 100px;
  margin-bottom: 15px;
  justify-content: flex-start;
  width: 300px;
}

.status {
  font-family: "Times New Roman", Times, serif;
  font-size: 14px;
  font-weight: bold;
  margin-top: 20px;
  margin-left: 100px;
}

.next-identity {
  margin-left: 30px;
  margin-top: -50px;
  display: flex;
}

.donation {
  width: 300px;
  height: 200px;
  font-size: 32px;
  font-weight: bold;
  margin-top: 80px;
  margin-left: -430px;
  justify-content: flex-start;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
}

.information {
  padding: 5px;
  font-size: 18px;
  line-height: 1.5;
  margin-top: 180px;
  margin-left: -160px;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
}

.donation-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 15px 0;
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

.quantity {
  display: flex;
  align-items: center;
}

.quantity button {
  padding: 5px 10px;
  font-size: 16px;
  border: none;
  background-color: #003366;
  color: white;
  cursor: pointer;
  margin-left: 5px;
  margin-right: 5px;
}

.total {
  font-size: 20px;
  font-weight: bold;
  margin-top: 20px;
  margin-bottom: 20px;
}

.payment-methods img {
  width: 80px;
  margin: 10px;
  cursor: pointer;
}

.payment-methods img.selected {
  border: 3px solid #2196F3;
  border-radius: 12px;
  padding: 5px;
  box-shadow: 0 0 10px rgba(33, 150, 243, 0.4);
}

.donate-btn {
  background-color: black;
  color: white;
  padding: 15px;
  width: 100%;
  font-size: 18px;
  border: none;
  cursor: pointer;
}

    </style>
</head>

<body>
  <div id="notifikasi" style="
    display: none;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    margin: 20px;
    position: sticky;
    top: 0;
    z-index: 9999;
  "></div>

    <div class="container">
        <div class="content">
            <div class="title">
                <h1 class="uptitle">Tsunami Palu 2018</h1>
                <div class="picture">
                    <p class="date">28 September 2018</p>
                    <img src="images/tsunami1.png" style="height: 400px;">
                </div>
                <div class="description">
                    <div class="identity">
                        <img src="https://placehold.co/50x50" class="profile-picture">
                        <p class="nama-yayasan">Yayasan Sejahtera Umat</p>
                        <p class="status">Identitas Terverifikasi</p>
                    </div>
                    <div class="next-identity">
                        <p class="donation">Rp. 8.750.000</p>
                        <p class="information">
                            Bencana tsunami yang melanda Palu, Sulawesi Tengah pada 28 September 2018 telah meninggalkan dampak yang parah.
                            Lebih dari 4.300 orang meninggal dunia dan ribuan lainnya kehilangan tempat tinggal dan mata pencaharian.
                            Kami berusaha membantu mereka yang terkena dampak dengan menyediakan bantuan darurat dan mendukung upaya pemulihan.
                    </div>
                </div>
            </div>
        </div>

        <h3>Pilihan Paket Barang</h3>
        <div class="donation-item">
            <span>Paket A - Rp 25.000</span>
            <span>Telur, Ayam, Ikan</span>
            <div class="quantity">
                <button onclick="updateQuantity('paketA', -1)">-</button>
                <span id="paketA">1</span>
                <button onclick="updateQuantity('paketA', 1)">+</button>
            </div>
        </div>
        <div class="donation-item">
            <span>Paket B - Rp 45.000</span>
            <span>Daging, Sayuran, Buah</span>
            <div class="quantity">
                <button onclick="updateQuantity('paketB', -1)">-</button>
                <span id="paketB">1</span>
                <button onclick="updateQuantity('paketB', 1)">+</button>
            </div>
        </div>

        <div class="total">Total: Rp <span id="totalAmount">70.000</span></div>

        <h3>Pilih Metode Pembayaran</h3>
        <div class="payment-methods">
          <input type="hidden" id="selectedPaymentMethod" name="paymentMethod">
            <img src="images/bni.png" alt="BNI">
            <img src="images/bri.png" alt="BRI">
            <img src="images/bca.png" alt="BCA">
            <img src="images/mandiri.png" alt="Mandiri">
            <img src="images/gopay.png" alt="Gopay">
            <img src="images/dana.png" alt="Dana">
            <img src="images/shopeepay.png" alt="ShopeePay">
        </div>

        <form id="donasiForm" onsubmit="return false;">
          <input type="hidden" name="paket" id="inputPaket">
          <input type="hidden" name="total" id="inputTotal">
          <input type="hidden" name="metode" id="inputMetode">
          <button type="submit" class="donate-btn" onclick="return prepareAndSubmit()">Donasikan Sekarang</button>
        </form>
    </div>

    <script>
        function updateQuantity(packageId, change) {
            let quantityElement = document.getElementById(packageId);
            let quantity = parseInt(quantityElement.textContent);
            quantity = Math.max(0, quantity + change);
            quantityElement.textContent = quantity;
            updateTotal();
        }

        function updateTotal() {
            let paketA = parseInt(document.getElementById('paketA').textContent) * 25000;
            let paketB = parseInt(document.getElementById('paketB').textContent) * 45000;
            let total = paketA + paketB;
            document.getElementById('totalAmount').textContent = total.toLocaleString('id-ID');
        }

        function donateNow() {
          let totalAmount = document.getElementById('totalAmount').textContent;
          let paymentMethod = document.getElementById('selectedPaymentMethod').value;

        if (!paymentMethod) {
          alert("Silakan pilih metode pembayaran terlebih dahulu!");
          return;
        }

          alert("Total donasi: Rp " + totalAmount + "\nMetode pembayaran: " + paymentMethod);
        }

        document.querySelectorAll('.payment-methods img').forEach(img => {
          img.addEventListener('click', function () {
          // Hapus 'selected' dari semua gambar
          document.querySelectorAll('.payment-methods img').forEach(i => i.classList.remove('selected'));

          // Tambahkan 'selected' ke gambar yang diklik
          this.classList.add('selected');

          // Simpan metode pembayaran ke input tersembunyi
          document.getElementById('selectedPaymentMethod').value = this.alt;
          });
        });
        
        let selectedPayment = "";
          document.querySelectorAll('.payment-methods img').forEach(img => {
            img.addEventListener('click', function() {
            document.querySelectorAll('.payment-methods img').forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            selectedPayment = this.alt; // ambil dari alt-nya
            });
          });

          function prepareAndSubmit() {
            let qtyA = parseInt(document.getElementById('paketA').textContent);
            let qtyB = parseInt(document.getElementById('paketB').textContent);
            let paketList = [];

            if (qtyA > 0) paketList.push(`${qtyA}x Paket A`);
            if (qtyB > 0) paketList.push(`${qtyB}x Paket B`);

            let total = parseInt(document.getElementById('totalAmount').textContent.replace(/\./g, '').replace(',', ''));

            if (paketList.length === 0) {
              tampilkanNotifikasi("Pilih setidaknya satu paket donasi.", true);
              return false;
            }

            if (!selectedPayment) {
              tampilkanNotifikasi("Pilih metode pembayaran.", true);
              return false;
            }

            const paket = paketList.join(', ');
            const formData = new FormData();
            formData.append('paket', paket);
            formData.append('total', total);
            formData.append('metode', selectedPayment);

            fetch('proses_transaksi.php', {
              method: 'POST',
              body: formData
            })
              .then(response => response.text())
              .then(result => {
              tampilkanNotifikasi(result, result.includes("berhasil") ? false : true);
            })
              .catch(error => {
              tampilkanNotifikasi("Terjadi kesalahan saat mengirim data.", true);
              console.error("Error:", error);
            });

            return false;
          }

          function tampilkanNotifikasi(pesan, isError) {
            const notif = document.getElementById('notifikasi');
            notif.style.display = 'block';
            notif.textContent = pesan;
            notif.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
            notif.style.color = isError ? '#721c24' : '#155724';
            notif.style.border = isError ? '1px solid #f5c6cb' : '1px solid #c3e6cb';

            setTimeout(() => {
              notif.style.display = 'none';
            }, 5000);
          }


    </script>
</body>

</html>