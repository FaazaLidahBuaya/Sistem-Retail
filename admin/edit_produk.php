<?php
session_start();
include '../koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data produk yang mau diedit
$query_produk = mysqli_query($koneksi, "SELECT * FROM products WHERE id_produk='$id'");
$data = mysqli_fetch_assoc($query_produk);

// Ambil data kategori untuk dropdown
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Edit Produk</div>
            <div class="card-body">
                
                <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                    
                    <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="<?= $data['name']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" class="form-select" required>
                            <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                                <option value="<?= $k['id_kategori']; ?>" <?= ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : ''; ?>>
                                    <?= $k['nama_kategori']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Rating (0-5.0)</label>
                            <input type="number" name="rating" class="form-control" step="0.1" min="0" max="5" value="<?= isset($data['rating']) ? $data['rating'] : '5.0'; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= $data['deskripsi']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label><br>
                        <img src="../assets/images/<?= $data['image']; ?>" width="100" class="mb-2 rounded border">
                        <br>
                        <label class="form-label small text-muted">Ganti Gambar (Kosongkan jika tidak ingin mengganti)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <a href="data_produk.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>