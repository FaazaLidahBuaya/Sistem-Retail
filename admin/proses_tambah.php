<?php
include '../koneksi.php';

$name = $_POST['name'];
$id_kategori = $_POST['id_kategori'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$deskripsi = $_POST['deskripsi'];

// LOGIKA UPLOAD GAMBAR
$rand = rand(); // Angka acak biar nama file gak kembar
$filename = $_FILES['image']['name'];
$ukuran = $_FILES['image']['size'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);

// Cek format gambar (hanya boleh png, jpg, jpeg)
if(!in_array($ext, ['png','jpg','jpeg'])) {
    header("location:tambah_produk.php?alert=gagal_ekstensi");
} else {
    // Jika ukuran kurang dari 2MB (2048000 bytes)
    if($ukuran < 2048000){		
        $xx = $rand.'_'.$filename;
        
        // PINDAHKAN file ke folder assets/images
        // Note: Kita ada di folder 'admin', jadi harus keluar dulu (../)
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/'.$xx);
        
        // SIMPAN ke Database
        mysqli_query($koneksi, "INSERT INTO products VALUES (NULL,'$id_kategori','$name','$deskripsi','$harga','$stok','5.0','$xx')");
        
        header("location:data_produk.php?alert=berhasil");
    } else {
        header("location:tambah_produk.php?alert=gagal_ukuran");
    }
}
?>