<?php
session_start();
// Cek Keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=Silakan login terlebih dahulu!");
    exit();
}

include '../koneksi.php';

// Menghitung data untuk Widget Dashboard
$jml_produk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM products"));
$jml_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE role='customer'"));
$jml_order = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM orders WHERE status_pembelian='pending'"));
// Menghitung pendapatan (contoh yang statusnya 'selesai')
$pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pembelian) AS total FROM orders WHERE status_pembelian='selesai'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Retail Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #dee2e6;
            width: 260px;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 20px 25px;
            font-size: 1.25rem;
            font-weight: bold;
            color: #0d6efd;
            border-bottom: 1px solid #f0f0f0;
        }
        .sidebar-menu { padding: 20px 15px; }
        .nav-link {
            color: #555;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: 600;
        }
        .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
        
        /* Main Content */
        .main-content {
            margin-left: 260px; /* Sesuaikan dengan lebar sidebar */
            padding: 30px;
        }
        
        /* Widget Cards */
        .card-widget {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
            color: white;
            overflow: hidden;
            position: relative;
        }
        .card-widget:hover { transform: translateY(-5px); }
        .card-widget .icon-bg {
            position: absolute;
            right: 15px;
            bottom: 15px;
            font-size: 4rem;
            opacity: 0.2;
        }
        
        /* Warna Gradient untuk Widget */
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
        .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); }
        .bg-gradient-danger  { background: linear-gradient(45deg, #e74a3b, #be2617); }

        @media (max-width: 768px) {
            .sidebar { margin-left: -260px; transition: margin 0.3s; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column shadow-sm">
        <a href="dashboard.php" class="sidebar-brand text-decoration-none d-flex align-items-center gap-2">
            <i class="fas fa-store"></i> Retail Place
        </a>
        
        <ul class="nav flex-column sidebar-menu">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Master Data</small>
            </li>
            <li class="nav-item">
                <a href="data_kategori.php" class="nav-link">
                    <i class="fas fa-tags"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a href="data_produk.php" class="nav-link">
                    <i class="fas fa-box-open"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a href="data_pelanggan.php" class="nav-link">
                    <i class="fas fa-users"></i> Pelanggan
                </a>
            </li>
            
            <li class="nav-item mt-2">
                <small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Transaksi</small>
            </li>
            <li class="nav-item">
                <a href="data_pesanan.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i> Pesanan Masuk
                    <?php if($jml_order > 0): ?>
                        <span class="badge bg-danger float-end rounded-pill"><?= $jml_order ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="laporan.php" class="nav-link">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
            </li>
            
            <li class="nav-item mt-4 border-top pt-2">
                <a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark">Dashboard Overview</h3>
                <p class="text-muted mb-0">Selamat datang kembali, <strong><?= $_SESSION['name']; ?></strong>!</p>
            </div>
            <a href="../index.php" target="_blank" class="btn btn-outline-primary rounded-pill">
                <i class="fas fa-external-link-alt me-1"></i> Lihat Website
            </a>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card card-widget bg-gradient-success shadow">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2" style="opacity:0.8">Pendapatan</h6>
                        <h4 class="fw-bold mb-0">Rp <?= number_format($pendapatan['total'] ?? 0); ?></h4>
                        <i class="fas fa-money-bill-wave icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-widget bg-gradient-warning shadow">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2 text-dark" style="opacity:0.8">Order Pending</h6>
                        <h4 class="fw-bold mb-0 text-dark"><?= $jml_order; ?> Pesanan</h4>
                        <i class="fas fa-shopping-basket icon-bg text-dark"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-widget bg-gradient-primary shadow">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2" style="opacity:0.8">Total Produk</h6>
                        <h4 class="fw-bold mb-0"><?= $jml_produk; ?> Item</h4>
                        <i class="fas fa-box icon-bg"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-widget bg-gradient-danger shadow">
                    <div class="card-body">
                        <h6 class="text-uppercase mb-2" style="opacity:0.8">Pelanggan</h6>
                        <h4 class="fw-bold mb-0"><?= $jml_user; ?> Orang</h4>
                        <i class="fas fa-users icon-bg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">No Resi</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query_terbaru = mysqli_query($koneksi, "SELECT * FROM orders JOIN users ON orders.id_user = users.user_id ORDER BY id_order DESC LIMIT 5");
                            while($tampil = mysqli_fetch_assoc($query_terbaru)): 
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold">#ORD-<?= $tampil['id_order'] ?></td>
                                <td><?= $tampil['name'] ?></td>
                                <td><?= $tampil['tanggal_pembelian'] ?></td>
                                <td>Rp <?= number_format($tampil['total_pembelian']) ?></td>
                                <td>
                                    <?php if($tampil['status_pembelian']=="pending"): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif($tampil['status_pembelian']=="selesai"): ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="detail_pesanan.php?id=<?= $tampil['id_order'] ?>" class="btn btn-sm btn-primary">Detail</a>
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