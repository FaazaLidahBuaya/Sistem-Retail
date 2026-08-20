<?php
include '../koneksi.php';

$id = $_GET['id'];

// 1. Ambil nama file gambar dulu sebelum dihapus datanya
$data = mysqli_query($koneksi, "SELECT image FROM products WHERE id_produk='$id'");
$row = mysqli_fetch_assoc($data);
$gambar_lama = "../assets/images/" . $row['image'];

// 2. Hapus file gambar fisik dari folder (Jika ada)
if (file_exists($gambar_lama)) {
    unlink($gambar_lama); // Fungsi PHP untuk menghapus file
}

// 3. Hapus data dari database
mysqli_query($koneksi, "DELETE FROM products WHERE id_produk='$id'");

// 4. Kembali ke halaman data produk
header("location:data_produk.php?alert=terhapus");
?>