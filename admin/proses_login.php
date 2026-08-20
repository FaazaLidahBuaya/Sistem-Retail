<?php
session_start();
include '../koneksi.php'; // Naik satu folder untuk cari koneksi.php

$email = $_POST['email'];
$password = $_POST['password'];

// 1. Cari user berdasarkan email
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
$cek = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($query);

    // 2. Cek apakah Role-nya ADMIN? (Customer gak boleh masuk sini)
    if ($data['role'] == 'admin') {
        
        // 3. Cek Password (sementara kita pakai plain text sesuai insert tadi)
        if ($password == $data['password']) {
            
            // SUKSES LOGIN!
            $_SESSION['status'] = "login";
            $_SESSION['role'] = "admin";
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['name'] = $data['name'];
            
            header("location:dashboard.php");
            
        } else {
            header("location:login.php?pesan=Password Salah!");
        }

    } else {
        header("location:login.php?pesan=Anda bukan Admin!");
    }

} else {
    header("location:login.php?pesan=Email tidak ditemukan!");
}
?>