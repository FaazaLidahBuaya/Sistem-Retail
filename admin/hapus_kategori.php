<?php
include '../koneksi.php';
$id = $_GET['id'];

// Hapus gambar fisik
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar_kategori FROM kategori WHERE id_kategori='$id'"));
if(file_exists("../assets/images/".$data['gambar_kategori'])){
    unlink("../assets/images/".$data['gambar_kategori']);
}

// Hapus data DB
mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori='$id'");
header("location:data_kategori.php");
?>