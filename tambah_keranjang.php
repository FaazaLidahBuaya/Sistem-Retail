<?php
session_start();

// 1. Tangkap ID Produk dan Jumlah yang dikirim dari form detail.php
$id_produk = $_POST['id_produk'];
$jumlah = $_POST['jumlah'];

// 2. Logika Masuk Keranjang
// Jika produk itu sudah ada di keranjang, maka jumlahnya ditambahkan
if (isset($_SESSION['keranjang'][$id_produk])) {
    $_SESSION['keranjang'][$id_produk] += $jumlah;
} 
// Jika produk itu belum ada di keranjang, maka buat baru
else {
    $_SESSION['keranjang'][$id_produk] = $jumlah;
}

// 3. Alihkan ke halaman keranjang
echo "<script>alert('Produk berhasil masuk keranjang!'); window.location='keranjang.php';</script>";
?>