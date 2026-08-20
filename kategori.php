<?php
session_start();
include 'koneksi.php';

// Ambil ID Kategori dari URL
$id_kategori = $_GET["id"];

// Ambil Nama Kategori untuk Judul
$ambil_nama = $koneksi->query("SELECT nama_kategori FROM kategori WHERE id_kategori='$id_kategori'");
$kat = $ambil_nama->fetch_assoc();
$nama_kategori = $kat['nama_kategori'];

// Ambil Produk berdasarkan Kategori
$semuadata = array();
$ambil = $koneksi->query("SELECT * FROM products WHERE id_kategori='$id_kategori'");
while($pecah = $ambil->fetch_assoc()){
    $semuadata[] = $pecah;
}

// Untuk Navbar Dropdown di halaman ini juga
$query_semua_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori: <?= $nama_kategori; ?> - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f0f0f0; font-family: sans-serif; }
        
        /* Navbar Styles (Sama seperti Index) */
        .logo-circle { width: 35px; height: 35px; background: #ff6600; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; }
        .btn-cart { background-color: #3e2723; color: white; border: none; }
        .search-box input { border-radius: 20px 0 0 20px; border-right: none; }
        .search-box button { border-radius: 0 20px 20px 0; background: #333; color: white; border: none; }

        /* Card Produk */
        .card-produk { border: none; border-radius: 10px; overflow: hidden; background: #fff; height: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.3s; }
        .card-produk:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-produk img { height: 180px; object-fit: cover; width: 100%; border-radius: 10px; margin: 10px; width: calc(100% - 20px); }
        .card-body { padding: 0 15px 15px 15px; }
        .produk-title { font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .harga { font-weight: bold; font-size: 14px; }
        .rating { color: #ffc107; font-size: 12px; }
        .link-produk { text-decoration: none; color: inherit; }
        .btn-add { background-color: #ff4d4d; color: white; font-size: 12px; padding: 5px 15px; border-radius: 5px; border: none; }
        .btn-add:hover { background-color: #e60000; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white py-3 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-circle"><i class="fas fa-store"></i></div> Faaza
            </a>
            
            <form action="pencarian.php" method="GET" class="input-group w-50 mx-auto search-box">
                <input type="text" name="keyword" class="form-control" placeholder="Cari barang apa...">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            </form>

            <div class="d-flex gap-3 align-items-center">
                <a href="index.php" class="text-dark text-decoration-none fw-bold">Home</a>
                
                <div class="dropdown">
                    <a href="#" class="text-dark text-decoration-none dropdown-toggle fw-bold" data-bs-toggle="dropdown">
                        Category
                    </a>
                    <ul class="dropdown-menu">
                        <?php while($k = mysqli_fetch_assoc($query_semua_kategori)): ?>
                            <li>
                                <a class="dropdown-item" href="kategori.php?id=<?= $k['id_kategori']; ?>">
                                    <?= $k['nama_kategori']; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <a href="keranjang.php" class="btn btn-cart px-3 text-decoration-none"><i class="fas fa-shopping-cart me-2"></i> Cart</a>
                
                <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "login_pelanggan"): ?>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-dark btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5" style="background-color: #b0b0b0; padding: 30px; border-radius: 20px;">
        
        <h4 class="fw-bold mb-4 text-white">Kategori: <?= $nama_kategori ?></h4>
        
        <?php if (empty($semuadata)): ?>
            <div class="alert alert-warning text-center p-5">
                <h3><i class="fas fa-box-open mb-3" style="font-size: 50px;"></i></h3>
                <p class="fw-bold">Belum ada produk di kategori ini.</p>
                <a href="index.php" class="btn btn-dark mt-2">Lihat Produk Lain</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($semuadata as $key => $p): ?>
                <div class="col-md-3 col-6">
                    <div class="card-produk">
                        
                        <a href="detail.php?id=<?= $p['id_produk']; ?>" class="link-produk">
                            <img src="assets/images/<?= $p['image']; ?>" 
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x300?text=No+Image';" 
                                 alt="<?= $p['name']; ?>">
                        </a>

                        <div class="card-body">
                            <a href="detail.php?id=<?= $p['id_produk']; ?>" class="link-produk">
                                <div class="produk-title"><?= $p['name']; ?></div>
                            </a>
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-star rating me-1"></i>
                                <small class="text-muted">5.0</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="harga">Rp. <?= number_format($p['harga'], 0, ',', '.'); ?></span>
                                <a href="detail.php?id=<?= $p['id_produk']; ?>" class="btn-add text-decoration-none">
                                    <i class="fas fa-shopping-cart"></i> Add
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>