<?php
session_start();
include 'koneksi.php';

$id_order = $_GET['id'];

// Ambil data order utama digabung data user yang beli
$ambil = $koneksi->query("SELECT * FROM orders JOIN users ON orders.id_user = users.user_id WHERE orders.id_order = '$id_order'");
$detail = $ambil->fetch_assoc();

// KEAMANAN: Jika yang beli BUKAN user yang login, usir balik ke home
// (Biar orang gak bisa intip nota orang lain dengan ganti ID di URL)
$id_user_yang_beli = $detail['id_user'];
$id_user_login = $_SESSION['user_id'];

if ($id_user_yang_beli !== $id_user_login) {
    echo "<script>alert('Nota tidak ditemukan!'); location='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .card-nota { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="container mt-5 mb-5">
        <div class="card card-nota p-4 p-md-5">
            
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold text-primary">Retail Place</h2>
                    <p class="text-muted mb-0">Invoice Pembelian</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h4 class="fw-bold">Order #<?= $detail['id_order']; ?></h4>
                    <span class="badge bg-warning text-dark px-3 py-2">Status: <?= $detail['status_pembelian']; ?></span>
                </div>
            </div>

            <hr>

            <div class="row mb-5">
                <div class="col-md-4">
                    <h6 class="fw-bold">Dipesan Oleh</h6>
                    <p class="mb-0">
                        <strong><?= $detail['name']; ?></strong> <br>
                        <?= $detail['email']; ?> <br>
                        <?= $detail['no_telp']; ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">Dikirim Ke</h6>
                    <p class="mb-0">
                        <?= nl2br($detail['alamat_pengiriman']); ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">Tanggal</h6>
                    <p class="mb-0">
                        <?= date("d F Y", strtotime($detail['tanggal_pembelian'])); ?>
                    </p>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Rincian Barang</h5>
            <table class="table table-bordered table-striped mb-4">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no=1;
                    // Ambil detail barang dari tabel order_detail JOIN products
                    $ambil_barang = $koneksi->query("SELECT * FROM order_detail JOIN products ON order_detail.id_produk = products.id_produk WHERE order_detail.id_order='$id_order'");
                    while($pecah_barang = $ambil_barang->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $pecah_barang['name']; ?></td>
                        <td>Rp <?= number_format($pecah_barang['harga']); ?></td>
                        <td><?= $pecah_barang['jumlah']; ?></td>
                        <td>Rp <?= number_format($pecah_barang['harga'] * $pecah_barang['jumlah']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Pembayaran</th>
                        <th class="fw-bold text-danger">Rp <?= number_format($detail['total_pembelian']); ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="col-md-7">
                    <div class="alert alert-primary">
                        <p class="mb-0 fw-bold">Silakan transfer Rp <?= number_format($detail['total_pembelian']); ?> ke:</p>
                        <p class="mb-0 mt-2">BANK BCA 123-456-789 (MLBBI)</p>
                    </div>
                </div>
                <div class="col-md-5 text-end align-self-center">
                    <button onclick="window.print()" class="btn btn-primary px-4 me-2"><i class="fas fa-print"></i> Cetak</button>
                    <a href="index.php" class="btn btn-secondary px-4">Kembali Belanja</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>