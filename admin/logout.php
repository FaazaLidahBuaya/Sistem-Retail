<?php
session_start();
session_destroy();
// Redirect ke halaman login admin setelah logout
echo "<script>alert('Anda telah logout dari Admin Panel'); location='login.php';</script>";
?>