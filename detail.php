<?php 
session_start(); 
include 'koneksi.php'; 

// Ambil ID dari URL dan amankan tipe datanya[cite: 1]
$id_produk = (int)$_GET['id']; 

// Ambil data produk berdasarkan ID[cite: 1]
$query = mysqli_query($koneksi, "SELECT products.*, kategori.nama_kategori                                   
                                  FROM products                                   
                                  JOIN kategori ON products.id_kategori = kategori.id_kategori                                  
                                 WHERE id_produk = '$id_produk'"); 
$data = mysqli_fetch_assoc($query); 

// Jika produk tidak ada[cite: 1]
if (!$data) {     
    echo "<script>alert('Produk tidak ditemukan'); location='index.php';</script>";     
    exit(); 
}
?>
<!DOCTYPE html> 
<html lang="id"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title><?= htmlspecialchars($data['name']); ?> - Retail Place</title>     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">     
    <style>         
        body { background-color: #f0f0f0; font-family: sans-serif; }         
        .main-image { width: 100%; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); object-fit: cover; }         
        .detail-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }         
        .harga-besar { font-size: 28px; font-weight: bold; color: #333; }         
        .stok-badge { background-color: #e3f2fd; color: #0d47a1; padding: 5px 10px; border-radius: 20px; font-size: 14px; font-weight: bold; }         
        .btn-beli { background-color: #ff4d4d; color: white; width: 100%; padding: 12px; border: none; font-weight: bold; border-radius: 8px; }         
        .btn-beli:hover { background-color: #e60000; color: white; }     
    </style> 
</head> 
<body>     
    <nav class="navbar navbar-expand-lg bg-white py-3 shadow-sm mb-4">         
        <div class="container">             
            <a class="navbar-brand fw-bold" href="index.php" style="color: #333;">                 
                <i class="fas fa-arrow-left me-2"></i> Kembali Belanja             
            </a>             
            <span class="navbar-text fw-bold mx-auto"><?= htmlspecialchars($data['name']); ?></span>         
        </div>     
    </nav>     
    <div class="container mb-5">         
        <nav aria-label="breadcrumb">             
            <ol class="breadcrumb">                 
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>                 
                <li class="breadcrumb-item active"><?= htmlspecialchars($data['nama_kategori']); ?></li>                 
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($data['name']); ?></li>             
            </ol>         
        </nav>         
        <div class="row">             
            <div class="col-md-5 mb-4">                 
                <img src="assets/images/<?= $data['image']; ?>" onerror="this.onerror=null; this.src='https://via.placeholder.com/500x500?text=No+Image';" class="main-image" alt="<?= htmlspecialchars($data['name']); ?>">             
            </div>             
            <div class="col-md-7">                 
                <div class="detail-card">                     
                    <h2 class="fw-bold"><?= htmlspecialchars($data['name']); ?></h2>                                          
                    <div class="d-flex align-items-center gap-3 mb-3">                         
                        <span class="stok-badge"><i class="fas fa-box"></i> Stok: <?= $data['stok']; ?></span>                         
                        <div class="text-warning"><i class="fas fa-star"></i> 5.0 / 5.0</div>                     
                    </div>                     
                    <div class="harga-besar mb-4">Rp. <?= number_format($data['harga'], 0, ',', '.'); ?></div>                     
                    <hr>                     
                    <h5 class="fw-bold">Deskripsi Produk</h5>                     
                    <p class="text-muted" style="line-height: 1.8;">                         
                        <?= empty($data['deskripsi']) ? 'Tidak ada deskripsi.' : nl2br(htmlspecialchars($data['deskripsi'])); ?>                     
                    </p>                     
                    <div class="mt-5">                         
                        <form action="tambah_keranjang.php" method="POST">                                                          
                            <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">                             
                            <div class="row align-items-end">                                 
                                <div class="col-4">                                     
                                    <label class="form-label fw-bold">Jumlah</label>                                     
                                    <input type="number" name="jumlah" class="form-control" value="1" min="1" max="<?= $data['stok']; ?>" required>                                 
                                </div>                                 
                                <div class="col-8">                                     
                                    <button type="submit" class="btn btn-beli">                                         
                                        <i class="fas fa-shopping-cart me-2"></i> Masuk Keranjang                                     
                                    </button>                                 
                                </div>                             
                            </div>                         
                        </form>                     
                    </div>                 
                </div>             
            </div>         
        </div>     
    </div>     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>