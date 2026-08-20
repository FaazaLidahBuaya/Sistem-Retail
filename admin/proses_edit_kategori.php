<?php
include '../koneksi.php';

$id = $_POST['id_kategori'];
$nama = $_POST['nama_kategori'];

// Update nama saja
mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori='$id'");

header("location:data_kategori.php");
?>