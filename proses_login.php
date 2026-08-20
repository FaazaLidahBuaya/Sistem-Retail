<?php 
session_start(); 
include 'koneksi.php'; 

$email = mysqli_real_escape_string($koneksi, $_POST['email']); 
$password = $_POST['password']; 

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'"); 
$cek = mysqli_num_rows($query); 

if ($cek > 0) {     
    $data = mysqli_fetch_assoc($query);     
    
    // Verifikasi Password Hash
    if (password_verify($password, $data['password']) || $password == $data['password']) {
        $_SESSION['status'] = "login_pelanggan";     
        $_SESSION['user_id'] = $data['user_id'];     
        $_SESSION['name'] = $data['name'];     
        $_SESSION['role'] = $data['role'];     
        
        if($data['role'] == "admin"){         
            header("location:admin/dashboard.php");     
        } else {         
            header("location:index.php");     
        } 
    } else {
        header("location:login.php?pesan=Email atau Password Salah!");
    }
} else {     
    header("location:login.php?pesan=Email atau Password Salah!"); 
}
?>