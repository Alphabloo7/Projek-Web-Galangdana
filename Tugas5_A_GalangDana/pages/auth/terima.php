<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
?>
    <h2>HASIL FORMULIR DONASI</h2>
    <table border="1">
        <tr>
            <th>Judul Donasi</th>
            <th>Deskripsi Donasi</th>
            <th>Tampilan Donasi</th>
            <th>Tanggal Peluncuran Donasi</th>
            <th>Jenis Donasi</th>
            <th>Target Donatur</th>
            <th>Jumlah Nominal</th>
        </tr>
        <tr>
            <td><?php echo $_POST['name']?></td>
            <td><?php echo $_POST['description']?></td>
            <td><?php echo $_POST['picture']?></td>
            <td><?php echo $_POST['date']?></td>
            <td><?php echo $_POST['types']?></td>
            <td><?php echo $_POST['target']?></td>
            <td><?php echo $_POST['category']?></td>
        </tr>
    </table>
<?php 
} else {
    echo "Form belum dikirim!";}