<?php
session_start();
include 'koneksi.php';

// Jika tidak login, tidak boleh masuk sini
if (!isset($_SESSION["status"]) || $_SESSION['status'] != 'login_pelanggan') {
    echo "<script>alert('Silakan login dulu!'); location='login.php';</script>";
    exit();
}

$id_user = $_SESSION['user_id'];

// Ambil semua riwayat belanja user ini
$query = $koneksi->query("SELECT * FROM orders WHERE id_user = '$id_user' ORDER BY tanggal_pembelian DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Belanja - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #f0f0f0; font-family: sans-serif; }</style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Faaza Store</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-dark btn-sm">Kembali Belanja</a>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <h3 class="fw-bold mb-4">Riwayat Belanja & Status Pesanan</h3>
        
        <?php if(mysqli_num_rows($query) == 0): ?>
            <div class="alert alert-info">Kamu belum pernah belanja. Yuk belanja dulu!</div>
        <?php else: ?>

            <div class="row">
                <?php while($pecah = $query->fetch_assoc()): ?>
                <div class="col-md-12">
                    <div class="card mb-3 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                
                                <div class="col-md-3">
                                    <small class="text-muted">Tanggal</small><br>
                                    <strong><?= date("d F Y", strtotime($pecah['tanggal_pembelian'])); ?></strong>
                                </div>
                                
                                <div class="col-md-3">
                                    <small class="text-muted">Status</small><br>
                                    
                                    <?php if($pecah['status_pembelian']=="pending"): ?>
                                        <span class="badge bg-warning text-dark text-uppercase">Pending</span>
                                        <br><small class="text-danger fst-italic">Menunggu Konfirmasi Admin</small>
                                    
                                    <?php elseif($pecah['status_pembelian']=="dikemas"): ?>
                                        <span class="badge bg-info text-dark text-uppercase">Sedang Dikemas</span>
                                    
                                    <?php elseif($pecah['status_pembelian']=="dikirim"): ?>
                                        <span class="badge bg-primary text-uppercase">Sedang Dikirim</span>
                                        <br><small class="text-muted">Paket sedang di jalan</small>

                                    <?php elseif($pecah['status_pembelian']=="selesai"): ?>
                                        <span class="badge bg-success text-uppercase">Selesai</span>
                                        <br><small class="text-success">Terima kasih!</small>
                                    
                                    <?php elseif($pecah['status_pembelian']=="batal"): ?>
                                        <span class="badge bg-danger text-uppercase">Dibatalkan</span>
                                    <?php endif; ?>

                                </div>
                                
                                <div class="col-md-3">
                                    <small class="text-muted">Total Tagihan</small><br>
                                    <strong class="text-danger">Rp <?= number_format($pecah['total_pembelian']); ?></strong>
                                </div>

                                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                    <a href="nota.php?id=<?= $pecah['id_order']; ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-invoice me-1"></i> Lihat Nota
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

        <?php endif; ?>
    </div>

</body>
</html>