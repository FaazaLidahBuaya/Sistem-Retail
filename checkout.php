<?php 
session_start(); 
include 'koneksi.php'; 

// 1. CEK KEAMANAN: User harus login
if (!isset($_SESSION["status"]) || $_SESSION['status'] != 'login_pelanggan') {     
    echo "<script>alert('Silakan login dulu!'); location='login.php';</script>";     
    exit(); 
}

// 2. CEK KERANJANG: Gak boleh kosong
if (empty($_SESSION["keranjang"]) || !isset($_SESSION["keranjang"])) {     
    echo "<script>alert('Keranjang kosong, belanja dulu yuk!'); location='index.php';</script>";     
    exit(); 
} // --- PENUTUPAN KURAWAL YANG SEBELUMNYA HILANG ---

// --- PROSES UTAMA: JIKA TOMBOL 'PROSES ORDER' DITEKAN ---[cite: 1]
if (isset($_POST['checkout'])) {          
    $id_user = $_SESSION['user_id'];     
    $tanggal = date("Y-m-d");     
    $alamat_pengiriman = mysqli_real_escape_string($koneksi, $_POST['alamat_pengiriman']);     
    
    // A. Hitung Total Belanja[cite: 1]
    $total_belanja = 0;     
    foreach ($_SESSION["keranjang"] as $id_produk => $jumlah) {         
        $id_produk = (int)$id_produk;
        $ambil = mysqli_query($koneksi, "SELECT harga FROM products WHERE id_produk='$id_produk'");         
        $pecah = mysqli_fetch_assoc($ambil);         
        $total_belanja += $pecah['harga'] * $jumlah;     
    }     

    // B. Simpan data utama ke tabel 'orders'[cite: 1]
    $koneksi->query("INSERT INTO orders (id_user, tanggal_pembelian, total_pembelian, alamat_pengiriman, status_pembelian)                      
                     VALUES ('$id_user', '$tanggal', '$total_belanja', '$alamat_pengiriman', 'pending')");          
    
    // C. Dapatkan ID_ORDER yang barusan dibuat[cite: 1]
    $id_order_barusan = $koneksi->insert_id;     

    // D. Looping keranjang untuk simpan ke 'order_detail' & Kurangi Stok[cite: 1]
    foreach ($_SESSION["keranjang"] as $id_produk => $jumlah) {         
        $id_produk = (int)$id_produk;
        $jumlah = (int)$jumlah;
        $ambil = mysqli_query($koneksi, "SELECT * FROM products WHERE id_produk='$id_produk'");         
        $pecah = mysqli_fetch_assoc($ambil);                  
        $harga = $pecah['harga'];

        // 1. Simpan rincian barang[cite: 1]
        $koneksi->query("INSERT INTO order_detail (id_order, id_produk, jumlah, harga)                          
                         VALUES ('$id_order_barusan', '$id_produk', '$jumlah', '$harga')");         
        // 2. Kurangi stok produk[cite: 1]
        $koneksi->query("UPDATE products SET stok = stok - $jumlah WHERE id_produk='$id_produk'");     
    }     

    // E. Kosongkan Keranjang Belanja[cite: 1]
    unset($_SESSION["keranjang"]);     
    // F. Alihkan ke Halaman Nota[cite: 1]
    echo "<script>alert('Pembelian Sukses!'); location='nota.php?id=$id_order_barusan';</script>"; 
    exit();
} 
?> <!-- PENUTUPAN TAG PHP YANG SEBELUMNYA HILANG -->[cite: 1]

<!DOCTYPE html> 
<html lang="id"> 
<head>     
    <meta charset="UTF-8">     
    <title>Checkout - Retail Place</title>     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head> 
<body class="bg-light">     
    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 mb-4">         
        <div class="container">             
            <a class="navbar-brand fw-bold" href="index.php">Retail Place</a>         
        </div>     
    </nav>     
    <div class="container mb-5">         
        <h2 class="fw-bold mb-4">Konfirmasi Pembayaran</h2>         
        <div class="row">                          
            <div class="col-md-7">                 
                <div class="card p-3 shadow-sm border-0 mb-3">                     
                    <h5 class="fw-bold">Barang yang dibeli</h5>                     
                    <table class="table">                         
                        <thead>                             
                            <tr>                                 
                                <th>Produk</th>                                 
                                <th>Harga</th>                                 
                                <th>Jml</th>                                 
                                <th>Subtotal</th>                             
                            </tr>                         
                        </thead>                         
                        <tbody>                             
                            <?php $total = 0; ?>                             
                            <?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>                                 
                                <?php                                 
                                $id_produk = (int)$id_produk;
                                $ambil = mysqli_query($koneksi, "SELECT * FROM products WHERE id_produk='$id_produk'");                                 
                                $pecah = mysqli_fetch_assoc($ambil);                                 
                                $subtotal = $pecah["harga"] * $jumlah;                                 
                                $total += $subtotal;                                 
                                ?>                                 
                                <tr>                                     
                                    <td><?= htmlspecialchars($pecah['name']); ?></td>                                     
                                    <td>Rp <?= number_format($pecah['harga']); ?></td>                                     
                                    <td><?= $jumlah; ?></td>                                     
                                    <td>Rp <?= number_format($subtotal); ?></td>                                 
                                </tr>                             
                            <?php endforeach; ?>                         
                        </tbody>                         
                        <tfoot class="bg-light fw-bold">                             
                            <tr>                                 
                                <td colspan="3">Total Tagihan</td>                                 
                                <td class="text-danger">Rp <?= number_format($total); ?></td>                             
                            </tr>                         
                        </tfoot>                     
                    </table>                 
                </div>             
            </div>             
            <div class="col-md-5">                 
                <div class="card p-4 shadow-sm border-0">                     
                    <h5 class="fw-bold mb-3">Tujuan Pengiriman</h5>                                          
                    <form method="POST">                         
                        <div class="mb-3">                             
                            <label class="form-label">Nama Penerima</label>                             
                            <input type="text" readonly value="<?= htmlspecialchars($_SESSION['name']); ?>" class="form-control bg-light">                         
                        </div>                                                  
                        <div class="mb-3">                             
                            <label class="form-label">Alamat Lengkap</label>                             
                            <textarea class="form-control" name="alamat_pengiriman" rows="4" placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Kecamatan)..." required></textarea>                         
                        </div>                                                  
                        <div class="alert alert-info small">                             
                            <i class="fas fa-info-circle me-1"></i> Ongkos kirim dihitung kurir (COD).                         
                        </div>                         
                        <button class="btn btn-success w-100 fw-bold py-2" name="checkout">                             
                            <i class="fas fa-check-circle me-2"></i> Proses Order                         
                        </button>                     
                    </form>                 
                </div>             
            </div>         
        </div>     
    </div> 
</body> 
</html> 