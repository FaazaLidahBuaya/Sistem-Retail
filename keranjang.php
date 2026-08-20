<?php
session_start();
include 'koneksi.php';

// Jika keranjang kosong (belum ada session atau isinya kosong)
if (empty($_SESSION["keranjang"]) OR !isset($_SESSION["keranjang"])) {
    echo "<script>alert('Keranjang belanja kosong, yuk belanja dulu!'); location='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: sans-serif; }
        .card-cart { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .img-cart { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
        .btn-checkout { background: #333; color: white; width: 100%; padding: 12px; border-radius: 10px; font-weight: bold; }
        .btn-checkout:hover { background: #000; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
            </a>
            <span class="fw-bold">Keranjang Saya</span>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-cart p-3 mb-3">
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr class="border-bottom">
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_belanja = 0; ?>
                            
                            <?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>
                                
                                <?php
                                // Ambil detail produk dari database berdasarkan ID yang ada di session
                                $ambil = mysqli_query($koneksi, "SELECT * FROM products WHERE id_produk='$id_produk'");
                                $pecah = mysqli_fetch_assoc($ambil);
                                
                                // Hitung Subtotal per barang
                                $subtotal = $pecah["harga"] * $jumlah;
                                
                                // Hitung Total Belanja Keseluruhan
                                $total_belanja += $subtotal;
                                ?>

                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="assets/images/<?= $pecah['image']; ?>" class="img-cart" 
                                                 onerror="this.src='https://via.placeholder.com/80?text=No+Img'">
                                            <div>
                                                <h6 class="mb-0 fw-bold"><?= $pecah['name']; ?></h6>
                                                <?php if($pecah['stok'] < 5): ?>
                                                    <small class="text-danger">Sisa stok: <?= $pecah['stok']; ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp <?= number_format($pecah['harga']); ?></td>
                                    <td><?= $jumlah; ?></td>
                                    <td class="fw-bold">Rp <?= number_format($subtotal); ?></td>
                                    <td>
                                        <a href="hapus_keranjang.php?id=<?= $id_produk; ?>" class="btn btn-danger btn-sm rounded-circle" onclick="return confirm('Hapus barang ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-cart p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Harga</span>
                        <span class="fw-bold">Rp <?= number_format($total_belanja); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 fw-bold">Grand Total</span>
                        <span class="h5 fw-bold text-danger">Rp <?= number_format($total_belanja); ?></span>
                    </div>

                    <?php if (isset($_SESSION["status"]) && $_SESSION['status'] == 'login_pelanggan'): ?>
                        <a href="checkout.php" class="btn btn-checkout">Checkout Sekarang</a>
                    <?php else: ?>
                        <a href="login.php?pesan=Silakan login untuk checkout" class="btn btn-checkout bg-warning text-dark">Login untuk Checkout</a>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>

</body>
</html>