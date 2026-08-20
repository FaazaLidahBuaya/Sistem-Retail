<?php 
session_start(); 
include 'koneksi.php'; 

// 1. QUERY UNTUK KATEGORI (DAILY BEST SALE) - Perbaikan syntax query
$query_kategori = mysqli_query($koneksi, "
    SELECT k.*, 
     (SELECT image FROM products p WHERE p.id_kategori = k.id_kategori ORDER BY RAND() LIMIT 1) as foto_random
    FROM kategori k
    LIMIT 3
");

// 2. QUERY UNTUK NAVBAR (DROPDOWN KATEGORI)
$query_semua_kategori = mysqli_query($koneksi, "SELECT * FROM kategori"); 

// --- LOGIKA "LOAD MORE" ---
$hitung_total = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM products"); 
$data_total = mysqli_fetch_assoc($hitung_total); 
$total_produk = $data_total['jumlah']; 
$limit_awal = 8; 
$limit_sekarang = isset($_GET['limit']) ? (int)$_GET['limit'] : $limit_awal; // Mencegah SQL Injection

$query_produk = mysqli_query($koneksi, "SELECT * FROM products ORDER BY rating DESC LIMIT $limit_sekarang"); 

if (!$query_kategori || !$query_produk) { 
    die("Query Error: " . mysqli_error($koneksi)); 
}
?>
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>RETAIL LOBBY</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
    <style> 
        body { background-color: #f0f0f0; font-family: sans-serif; } 
        .logo-circle { width: 35px; height: 35px; background: #ff6600; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; } 
        .btn-cart { background-color: #3e2723; color: white; border: none; } 
        .search-box input { border-radius: 20px 0 0 20px; border-right: none; } 
        .search-box button { border-radius: 0 20px 20px 0; background: #333; color: white; border: none; } 

        @media (min-width: 992px) { 
            .dropdown-menu-steam { 
                min-width: 600px; gap: 15px; padding: 20px; border-radius: 12px; 
                box-shadow: 0 15px 40px rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.05); 
                left: 50% !important; opacity: 0; 
                transform: translateX(-50%) translateY(-20px) scale(0.95); display: none;  
            } 
            @keyframes menuPop { 
                0% { opacity: 0; transform: translateX(-50%) translateY(-20px) scale(0.95); } 
                100% { opacity: 1; transform: translateX(-50%) translateY(0) scale(1); } 
            } 
            .dropdown-menu-steam.show { 
                display: grid; grid-template-columns: repeat(3, 1fr); 
                animation: menuPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; 
            } 
            .dropdown-item-steam { 
                padding: 12px; border-radius: 8px; transition: 0.2s; font-weight: 500; 
                color: #333; border: 1px solid #f0f0f0; text-align: center; display: block; background: #fff; 
            } 
            .dropdown-item-steam:hover { 
                background-color: #222; color: #fff !important; 
                transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #222; 
            } 
        } 
        .card-kategori { background: white; border-radius: 15px; padding: 20px; text-align: center; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: 0.3s; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; } 
        .card-kategori:hover { transform: translateY(-5px); cursor: pointer; } 
        .kategori-img { width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-bottom: 10px; border: 3px solid #f0f0f0; } 
        .card-produk { border: none; border-radius: 10px; overflow: hidden; background: #fff; height: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: 0.3s; } 
        .card-produk:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); } 
        .card-produk img { height: 180px; object-fit: cover; border-radius: 10px; margin: 10px; width: calc(100% - 20px); } 
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
                <div class="logo-circle"><i class="fas fa-store"></i></div> Retail 
            </a> 
            <form action="pencarian.php" method="GET" class="input-group w-50 mx-auto search-box"> 
                <input type="text" name="keyword" class="form-control" placeholder="Cari barang apa..."> 
                <button class="btn" type="submit"><i class="fas fa-search"></i></button> 
            </form> 
            <div class="d-flex gap-3 align-items-center"> 
                <a href="index.php" class="text-dark text-decoration-none fw-bold">Home</a> 
                <div class="dropdown position-static"> 
                    <a href="#" class="text-dark text-decoration-none dropdown-toggle fw-bold" data-bs-toggle="dropdown"> 
                        Category 
                    </a> 
                    <div class="dropdown-menu dropdown-menu-steam"> 
                        <?php  
                        mysqli_data_seek($query_semua_kategori, 0);  
                        while($k = mysqli_fetch_assoc($query_semua_kategori)):  
                        ?> 
                            <a class="dropdown-item dropdown-item-steam text-decoration-none" href="kategori.php?id=<?= $k['id_kategori']; ?>"> 
                                <i class="fas fa-tag me-2 text-warning"></i>  
                                <?= htmlspecialchars($k['nama_kategori']); ?> 
                            </a> 
                        <?php endwhile; ?> 
                    </div> 
                </div> 
                <a href="keranjang.php" class="btn btn-cart px-3 text-decoration-none"> 
                    <i class="fas fa-shopping-cart me-2"></i> Cart 
                </a> 
                <?php if(isset($_SESSION['status']) && $_SESSION['status'] == "login_pelanggan"): ?> 
                    <div class="dropdown"> 
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown"> 
                            Halo, <?= htmlspecialchars(explode(" ", $_SESSION['name'])[0]); ?> 
                        </button> 
                        <ul class="dropdown-menu"> 
                            <li><a class="dropdown-item" href="riwayat.php">Riwayat Belanja</a></li> 
                            <li><hr class="dropdown-divider"></li> 
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li> 
                        </ul> 
                    </div> 
                <?php else: ?> 
                    <a href="login.php" class="btn btn-outline-dark btn-sm">Login</a> 
                    <a href="register.php" class="btn btn-dark btn-sm">Daftar</a> 
                <?php endif; ?> 
            </div> 
        </div> 
    </nav> 

    <div class="container" style="background-color: #b0b0b0; padding: 30px; border-radius: 20px; margin-top: 20px;"> 
        <h4 class="text-center fw-bold mb-4">Daily Best Sale</h4> 
        <div class="row justify-content-center mb-5"> 
            <?php while ($kat = mysqli_fetch_assoc($query_kategori)) : ?> 
            <div class="col-md-3 col-4"> 
                <a href="kategori.php?id=<?= $kat['id_kategori']; ?>" class="text-decoration-none text-dark"> 
                    <div class="card-kategori"> 
                        <?php  
                            if (!empty($kat['foto_random'])) { 
                                $gambar_kat = "assets/images/" . $kat['foto_random']; 
                            } else { 
                                $gambar_kat = "https://via.placeholder.com/80?text=" . substr($kat['nama_kategori'], 0, 1); 
                            } 
                        ?> 
                        <img src="<?= $gambar_kat; ?>" class="kategori-img" alt="icon"> 
                        <h6 class="fw-bold m-0"><?= htmlspecialchars($kat['nama_kategori']); ?></h6> 
                        <small class="text-muted">Best</small> 
                    </div> 
                </a> 
            </div> 
            <?php endwhile; ?> 
        </div> 

        <h5 id="rekomendasi" class="fw-bold mb-3">Rekomendasi</h5> 
        <div class="row g-3"> 
            <?php while ($p = mysqli_fetch_assoc($query_produk)) : ?> 
            <div class="col-md-3 col-6"> 
                <div class="card-produk"> 
                    <a href="detail.php?id=<?= $p['id_produk']; ?>" class="link-produk"> 
                        <img src="assets/images/<?= $p['image']; ?>" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x300?text=No+Image';" alt="<?= htmlspecialchars($p['name']); ?>"> 
                    </a> 
                    <div class="card-body"> 
                        <a href="detail.php?id=<?= $p['id_produk']; ?>" class="link-produk"> 
                            <div class="produk-title"><?= htmlspecialchars($p['name']); ?></div> 
                        </a> 
                        <div class="d-flex align-items-center mb-2"> 
                            <i class="fas fa-star rating me-1"></i> 
                            <small class="text-muted"><?= isset($p['rating']) ? $p['rating'] : '5.0'; ?></small> 
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
            <?php endwhile; ?> 
        </div> 
        
        <?php if ($limit_sekarang < $total_produk): $limit_berikutnya = $limit_sekarang + 8; ?> 
            <div class="text-center my-5"> 
                <a href="index.php?limit=<?= $limit_berikutnya; ?>#rekomendasi" class="btn btn-light rounded-pill px-5 fw-bold shadow-sm"> 
                    More (+8 Products) 
                </a> 
            </div> 
        <?php endif; ?> 
    </div> 

    <?php include 'footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>