<?php 
include 'koneksi.php'; 

$name = mysqli_real_escape_string($koneksi, $_POST['name']); 
$email = mysqli_real_escape_string($koneksi, $_POST['email']); 
$password = $_POST['password']; 
$no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']); 
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']); 

// 1. Cek apakah email sudah terdaftar?[cite: 1]
$cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'"); 

if(mysqli_num_rows($cek_email) > 0){     
    echo "<script>alert('Email sudah digunakan!'); window.location='register.php';</script>"; 
} else {     
    // Secure Password Hashing
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // 2. Simpan ke database dengan role 'customer'[cite: 1]
    $insert = mysqli_query($koneksi, "INSERT INTO users (name, email, password, no_telp, alamat, role)                                        
                                       VALUES ('$name', '$email', '$password_hashed', '$no_telp', '$alamat', 'customer')");          
    if($insert){         
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";     
    } else {         
        echo "<script>alert('Gagal mendaftar.'); window.location='register.php';</script>";     
    } 
}
?>