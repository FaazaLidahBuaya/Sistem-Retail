<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php"); exit();
}

$id_order = $_GET['id'];

// Ambil Data Order & User
$ambil = $koneksi->query("SELECT * FROM orders JOIN users ON orders.id_user = users.user_id WHERE orders.id_order='$id_order'");
$detail = $ambil->fetch_assoc();

// Ambil Data Produk yang dibeli
$produk_dibeli = array();
$ambil_produk = $koneksi->query("SELECT * FROM order_detail JOIN products ON order_detail.id_produk = products.id_produk WHERE order_detail.id_order='$id_order'");
while($pecah = $ambil_produk->fetch_assoc()){
    $produk_dibeli[] = $pecah;
}

// PROSES UPDATE STATUS
if (isset($_POST['proses'])) {
    $status = $_POST['status'];
    $koneksi->query("UPDATE orders SET status_pembelian='$status' WHERE id_order='$id_order'");
    echo "<script>alert('Status Pesanan Diupdate!'); location='detail_pesanan.php?id=$id_order';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan - Retail Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #dee2e6; width: 260px; position: fixed; top: 0; left: 0; z-index: 1000; }
        .sidebar-brand { padding: 20px 25px; font-size: 1.25rem; font-weight: bold; color: #0d6efd; border-bottom: 1px solid #f0f0f0; }
        .sidebar-menu { padding: 20px 15px; }
        .nav-link { color: #555; padding: 12px 15px; margin-bottom: 5px; border-radius: 10px; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; }
        .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
        .main-content { margin-left: 260px; padding: 30px; }
        @media (max-width: 768px) { .sidebar { margin-left: -260px; } .main-content { margin-left: 0; } }
        
        .card-detail { border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .table-items th { background-color: #f8f9fa; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column shadow-sm">
        <a href="dashboard.php" class="sidebar-brand text-decoration-none d-flex align-items-center gap-2">
            <i class="fas fa-store"></i> Retail Place
        </a>
        <ul class="nav flex-column sidebar-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item"><small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Master Data</small></li>
            <li class="nav-item"><a href="data_kategori.php" class="nav-link"><i class="fas fa-tags"></i> Kategori</a></li>
            <li class="nav-item"><a href="data_produk.php" class="nav-link"><i class="fas fa-box-open"></i> Produk</a></li>
            <li class="nav-item"><a href="data_pelanggan.php" class="nav-link"><i class="fas fa-users"></i> Pelanggan</a></li>
            <li class="nav-item mt-2"><small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Transaksi</small></li>
            <li class="nav-item"><a href="data_pesanan.php" class="nav-link active"><i class="fas fa-shopping-cart"></i> Pesanan Masuk</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li class="nav-item mt-4 border-top pt-2"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="data_pesanan.php" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                <h3 class="fw-bold text-dark">Detail Transaksi #<?= $detail['id_order']; ?></h3>
            </div>
            
            <?php 
                $status_class = 'bg-secondary';
                if($detail['status_pembelian']=='pending') $status_class = 'bg-warning text-dark';
                elseif($detail['status_pembelian']=='selesai') $status_class = 'bg-success';
                elseif($detail['status_pembelian']=='batal') $status_class = 'bg-danger';
                elseif($detail['status_pembelian']=='dikirim') $status_class = 'bg-info text-dark';
            ?>
            <span class="badge <?= $status_class; ?> fs-6 px-4 py-2 rounded-pill text-uppercase">
                <?= $detail['status_pembelian']; ?>
            </span>
        </div>

        <div class="row">
            <div class="col-lg-8">
                
                <div class="card card-detail mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-map-marker-alt me-2"></i> Informasi Pengiriman</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Nama Penerima</small>
                                <p class="fw-bold mb-0 fs-5"><?= $detail['name']; ?></p>
                                <p class="text-muted mb-0"><i class="fas fa-phone-alt me-1"></i> <?= $detail['no_telp']; ?></p>
                                <p class="text-muted"><i class="fas fa-envelope me-1"></i> <?= $detail['email']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem;">Alamat Tujuan</small>
                                <p class="mb-0"><?= nl2br($detail['alamat_pengiriman']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-detail">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-box me-2"></i> Barang yang Dibeli</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-items align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Jml</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produk_dibeli as $p): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= $p['name']; ?></div>
                                        </td>
                                        <td class="text-end">Rp <?= number_format($p['harga']); ?></td>
                                        <td class="text-center"><?= $p['jumlah']; ?></td>
                                        <td class="text-end pe-4 fw-bold">Rp <?= number_format($p['harga'] * $p['jumlah']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold py-3">Total Tagihan</td>
                                        <td class="text-end pe-4 fw-bold py-3 text-primary fs-5">Rp <?= number_format($detail['total_pembelian']); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card card-detail bg-white">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-cog me-2"></i> Kelola Pesanan</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Update Status</label>
                                <select class="form-select" name="status">
                                    <option value="pending" <?= $detail['status_pembelian']=='pending'?'selected':''; ?>>Pending</option>
                                    <option value="dikemas" <?= $detail['status_pembelian']=='dikemas'?'selected':''; ?>>Dikemas</option>
                                    <option value="dikirim" <?= $detail['status_pembelian']=='dikirim'?'selected':''; ?>>Dikirim (Kurir)</option>
                                    <option value="selesai" <?= $detail['status_pembelian']=='selesai'?'selected':''; ?>>Selesai</option>
                                    <option value="batal" <?= $detail['status_pembelian']=='batal'?'selected':''; ?>>Batalkan Pesanan</option>
                                </select>
                            </div>
                            
                            <div class="d-grid">
                                <button class="btn btn-primary fw-bold" name="proses">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">
                        
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i> 
                            Pastikan pembayaran sudah valid sebelum mengubah status menjadi <strong>Dikemas</strong> atau <strong>Selesai</strong>.
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>