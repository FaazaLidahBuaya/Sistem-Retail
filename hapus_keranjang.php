<?php
session_start();

// Ambil ID dari URL
$id_produk = $_GET['id'];

// Hapus session keranjang berdasarkan ID tersebut
unset($_SESSION["keranjang"][$id_produk]);

echo "<script>alert('Produk dihapus dari keranjang'); location='keranjang.php';</script>";
?>