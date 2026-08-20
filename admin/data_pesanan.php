<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php"); exit();
}
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan - Retail Place</title>
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
        <h3 class="fw-bold text-dark mb-4">Daftar Pesanan Masuk</h3>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">No Resi</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Order</th>
                                <th>Total Pembelian</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM orders JOIN users ON orders.id_user = users.user_id ORDER BY id_order DESC");
                            while($pecah = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#ORD-<?= $pecah['id_order']; ?></td>
                                <td>
                                    <div class="fw-bold"><?= $pecah['name']; ?></div>
                                    <small class="text-muted"><?= $pecah['email']; ?></small>
                                </td>
                                <td><?= date("d M Y", strtotime($pecah['tanggal_pembelian'])); ?></td>
                                <td class="fw-bold text-success">Rp <?= number_format($pecah['total_pembelian']); ?></td>
                                <td>
                                    <?php if($pecah['status_pembelian']=="pending"): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                    <?php elseif($pecah['status_pembelian']=="selesai"): ?>
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                                    <?php elseif($pecah['status_pembelian']=="batal"): ?>
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">Batal</span>
                                    <?php else: ?>
                                        <span class="badge bg-info px-3 py-2 rounded-pill"><?= $pecah['status_pembelian']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="detail_pesanan.php?id=<?= $pecah['id_order']; ?>" class="btn btn-primary btn-sm rounded-3 px-3">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>