<?php
session_start();
include '../koneksi.php';

$id = $_GET['id'];
$ambil = $koneksi->query("SELECT * FROM kategori WHERE id_kategori='$id'");
$pecah = $ambil->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background-color: #f8f9fa; padding: 50px; }</style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-warning text-dark fw-bold text-center py-3">
                        Edit Kategori
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="proses_edit_kategori.php">
                            <input type="hidden" name="id_kategori" value="<?= $pecah['id_kategori']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Kategori</label>
                                <input type="text" class="form-control" name="nama_kategori" value="<?= $pecah['nama_kategori']; ?>" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" name="ubah">Simpan Perubahan</button>
                                <a href="data_kategori.php" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>