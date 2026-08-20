<?php
include '../koneksi.php';

$id = $_POST['id_produk'];
$name = $_POST['name'];
$id_kategori = $_POST['id_kategori'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$rating = $_POST['rating']; // TAMBAHAN: Menangkap nilai rating
$deskripsi = $_POST['deskripsi'];

// Cek apakah user mengganti gambar?
if($_FILES['image']['name'] != "") {
    
    // --- KASUS 1: GANTI GAMBAR ---
    
    $filename = $_FILES['image']['name'];
    $ukuran = $_FILES['image']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $rand = rand();

    if(!in_array($ext, ['png','jpg','jpeg'])) {
        header("location:edit_produk.php?id=$id&alert=gagal_ekstensi");
        exit;
    }

    if($ukuran < 2048000){		
        // 1. Hapus gambar lama dulu
        $data = mysqli_query($koneksi, "SELECT image FROM products WHERE id_produk='$id'");
        $row = mysqli_fetch_assoc($data);
        $gambar_lama = "../assets/images/" . $row['image'];
        if (file_exists($gambar_lama)) {
            unlink($gambar_lama);
        }

        // 2. Upload gambar baru
        $xx = $rand.'_'.$filename;
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/'.$xx);

        // 3. Update database DENGAN gambar baru (DAN RATING)
        mysqli_query($koneksi, "UPDATE products SET id_kategori='$id_kategori', name='$name', deskripsi='$deskripsi', harga='$harga', stok='$stok', rating='$rating', image='$xx' WHERE id_produk='$id'");
        
        header("location:data_produk.php?alert=berhasil_update");
    } else {
        header("location:edit_produk.php?id=$id&alert=gagal_ukuran");
    }

} else {

    // --- KASUS 2: TIDAK GANTI GAMBAR ---
    // Update database TANPA menyentuh kolom 'image' (TAPI UPDATE RATING)
    mysqli_query($koneksi, "UPDATE products SET id_kategori='$id_kategori', name='$name', deskripsi='$deskripsi', harga='$harga', stok='$stok', rating='$rating' WHERE id_produk='$id'");
    
    header("location:data_produk.php?alert=berhasil_update");
}
?>