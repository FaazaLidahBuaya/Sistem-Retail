<?php
include '../koneksi.php';

$nama = $_POST['nama_kategori'];

// Langsung simpan nama saja. Kolom gambar_kategori kita isi string kosong atau NULL.
// Pastikan struktur database mendukung NULL atau string kosong.
mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

header("location:data_kategori.php");
?>