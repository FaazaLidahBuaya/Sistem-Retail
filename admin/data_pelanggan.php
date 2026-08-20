<?php
session_start();
// Cek Keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php"); exit();
}

include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan - Retail Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }
        
        /* Sidebar Styling (Sama dengan halaman lain) */
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #dee2e6; width: 260px; position: fixed; top: 0; left: 0; z-index: 1000; }
        .sidebar-brand { padding: 20px 25px; font-size: 1.25rem; font-weight: bold; color: #0d6efd; border-bottom: 1px solid #f0f0f0; }
        .sidebar-menu { padding: 20px 15px; }
        .nav-link { color: #555; padding: 12px 15px; margin-bottom: 5px; border-radius: 10px; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; }
        .nav-link i { width: 25px; text-align: center; margin-right: 10px; }
        
        /* Main Content */
        .main-content { margin-left: 260px; padding: 30px; }
        
        /* Responsive */
        @media (max-width: 768px) { .sidebar { margin-left: -260px; } .main-content { margin-left: 0; } }
        
        /* Avatar Initials */
        .avatar-initial {
            width: 40px; height: 40px;
            background-color: #e7f1ff; color: #0d6efd;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 1.1rem;
        }
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
            <li class="nav-item"><a href="data_pelanggan.php" class="nav-link active"><i class="fas fa-users"></i> Pelanggan</a></li>
            <li class="nav-item mt-2"><small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Transaksi</small></li>
            <li class="nav-item"><a href="data_pesanan.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Pesanan Masuk</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li class="nav-item mt-4 border-top pt-2"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark">Data Pelanggan</h3>
                <p class="text-muted mb-0">Daftar pengguna yang terdaftar sebagai customer.</p>
            </div>
            </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3" width="50">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Ambil hanya user yang role-nya customer
                            $query = mysqli_query($koneksi, "SELECT * FROM users WHERE role='customer' ORDER BY user_id DESC");
                            
                            if(mysqli_num_rows($query) > 0):
                                while($data = mysqli_fetch_assoc($query)):
                                    // Ambil inisial nama untuk avatar
                                    $inisial = strtoupper(substr($data['name'], 0, 1));
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial me-3"><?= $inisial; ?></div>
                                        <div>
                                            <div class="fw-bold"><?= $data['name']; ?></div>
                                            <small class="text-muted">ID: #CUST-<?= $data['user_id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="fas fa-envelope me-2 text-muted"></i> <?= $data['email']; ?></div>
                                    <div class="small"><i class="fas fa-phone me-2 text-muted"></i> <?= $data['no_telp'] ? $data['no_telp'] : '-'; ?></div>
                                </td>
                                <td>
                                    <?php if($data['alamat']): ?>
                                        <small><?= substr($data['alamat'], 0, 50); ?>...</small>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border">Belum diisi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="hapus_pelanggan.php?id=<?= $data['user_id']; ?>" 
                                       class="btn btn-sm btn-outline-danger rounded-3" 
                                       onclick="return confirm('Hapus pelanggan ini? Semua data transaksi terkait juga akan terhapus!')"
                                       title="Hapus Pelanggan">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-3x mb-3"></i><br>
                                    Belum ada data pelanggan.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>