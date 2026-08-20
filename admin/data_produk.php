<?php
session_start();
// Cek Keamanan
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=Silakan login terlebih dahulu!");
    exit();
}

include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk - retail Place</title>
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
        .img-produk { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
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
            <li class="nav-item"><a href="data_produk.php" class="nav-link active"><i class="fas fa-box-open"></i> Produk</a></li>
            <li class="nav-item"><a href="data_pelanggan.php" class="nav-link"><i class="fas fa-users"></i> Pelanggan</a></li>
            <li class="nav-item mt-2"><small class="text-muted ms-3 text-uppercase fw-bold" style="font-size:0.7rem;">Transaksi</small></li>
            <li class="nav-item"><a href="data_pesanan.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Pesanan Masuk</a></li>
            <li class="nav-item"><a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a></li>
            <li class="nav-item mt-4 border-top pt-2"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Manajemen Produk</h3>
            <a href="tambah_produk.php" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Tambah Produk
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Join tabel produk dengan kategori agar nama kategori muncul
                            $query = mysqli_query($koneksi, "SELECT products.*, kategori.nama_kategori 
                                                             FROM products 
                                                             LEFT JOIN kategori ON products.id_kategori = kategori.id_kategori 
                                                             ORDER BY id_produk DESC");
                            while($data = mysqli_fetch_assoc($query)):
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td>
                                    <?php if($data['image']): ?>
                                        <img src="../assets/images/<?= $data['image']; ?>" class="img-produk shadow-sm">
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= $data['name']; ?></td>
                                <td><span class="badge bg-info text-dark"><?= $data['nama_kategori']; ?></span></td>
                                <td>Rp <?= number_format($data['harga']); ?></td>
                                <td>
                                    <?php if($data['stok'] < 5): ?>
                                        <span class="text-danger fw-bold"><?= $data['stok']; ?> (Menipis)</span>
                                    <?php else: ?>
                                        <?= $data['stok']; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="edit_produk.php?id=<?= $data['id_produk']; ?>" class="btn btn-sm btn-warning text-white me-1" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_produk.php?id=<?= $data['id_produk']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
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