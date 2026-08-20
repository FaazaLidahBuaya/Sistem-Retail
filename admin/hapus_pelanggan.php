<?php
include '../koneksi.php';

$id = $_GET['id'];

// Hapus data pelanggan berdasarkan ID
$hapus = mysqli_query($koneksi, "DELETE FROM users WHERE user_id='$id'");

if ($hapus) {
    echo "<script>alert('Data Pelanggan Berhasil Dihapus'); location='data_pelanggan.php';</script>";
} else {
    echo "<script>alert('Gagal Menghapus Data'); location='data_pelanggan.php';</script>";
}
?>