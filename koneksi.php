<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "core_place"; // Pastikan nama DB sesuai di phpMyAdmin

$koneksi = mysqli_connect($hostname, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>