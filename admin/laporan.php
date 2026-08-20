<?php
session_start();
// 1. Cek Keamanan Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php"); exit();
}

include '../koneksi.php';

// 2. Inisialisasi Variabel Pelaporan
$semua_data = array();
$tgl_mulai = "-";
$tgl_selesai = "-";
$status_filter = "";

// 3. Logika Filter Laporan
if (isset($_POST["kirim"])) {
    $tgl_mulai = $_POST["tglm"];
    $tgl_selesai = $_POST["tgls"];
    
    // Ambil data orders JOIN users, khusus yang statusnya 'selesai' (Pendapatan Valid)
    // Jika ingin semua status, hapus bagian "AND status_pembelian='selesai'"
    $ambil = $koneksi->query("SELECT * FROM orders 
                              JOIN users ON orders.id_user = users.user_id 
                              WHERE status_pembelian='selesai' 
                              AND tanggal_pembelian BETWEEN '$tgl_mulai' AND '$tgl_selesai'
                              ORDER BY tanggal_pembelian ASC");
    
    while($pecah = $ambil->fetch_assoc()){
        $semua_data[] = $pecah;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Retail Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #dee2e6; width: 260px; position: fixed; top: 0; left: 0; z-index: 1000; }
        .sidebar-brand { padding: 20px 25px; font-size: 1.25rem; font-weight: bold; color: #0d6efd; border-bottom: 1px solid #f0f0f0; }
        .sidebar-menu { padding: 20px 15px; }
        .nav-link { color: #555; padding: 12px 15px; margin-bottom: 5px; border-radius: 10px; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; }
        .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
        
        /* Main Content */
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
            <li class="nav-item"><a href="data_pesanan.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Pesanan Masuk</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link active"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li class="nav-item mt-4 border-top pt-2"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h3 class="fw-bold text-dark mb-4">Laporan Penjualan</h3>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <form method="post">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" class="form-control" name="tglm" value="<?= $tgl_mulai !== '-' ? $tgl_mulai : date('Y-m-01') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tgls" value="<?= $tgl_selesai !== '-' ? $tgl_selesai : date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" name="kirim">
                                <i class="fas fa-filter me-2"></i> Tampilkan Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_POST["kirim"])): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-table me-2"></i> 
                    Data Laporan (<?= date("d M Y", strtotime($tgl_mulai)) ?> s/d <?= date("d M Y", strtotime($tgl_selesai)) ?>)
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($semua_data as $key => $value): 
                                $total += $value['total_pembelian'];
                            ?>
                            <tr>
                                <td class="ps-4"><?= $key+1; ?></td>
                                <td><?= $value["name"]; ?></td>
                                <td><?= date("d F Y", strtotime($value["tanggal_pembelian"])); ?></td>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td class="fw-bold">Rp <?= number_format($value["total_pembelian"]); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if(empty($semua_data)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Tidak ada data penjualan selesai pada rentang tanggal ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if(!empty($semua_data)): ?>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="4" class="text-end py-3">Total Pendapatan</th>
                                <th class="py-3 text-primary fs-5">Rp <?= number_format($total) ?></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            
            <?php if(!empty($semua_data)): ?>
            <div class="card-footer bg-white border-0 py-3 text-end">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print me-2"></i> Cetak Laporan
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Silakan pilih rentang tanggal dan klik tombol <strong>Tampilkan Laporan</strong> untuk melihat data.
            </div>
        <?php endif; ?>
    </div>

</body>
</html>