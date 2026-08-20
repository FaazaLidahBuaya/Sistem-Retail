<?php
session_start();
// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f2f5; /* Warna background abu lembut */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 30px;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="login-header">
                        <i class="fas fa-store fa-3x mb-2"></i>
                        <h4 class="fw-bold mb-0">Faaza Store</h4>
                        <small>Administrator Panel</small>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if(isset($_GET['pesan'])): ?>
                            <div class="alert alert-danger py-2 small text-center rounded-3">
                                <?= $_GET['pesan']; ?>
                            </div>
                        <?php endif; ?>

                        <form action="proses_login.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email">Email Address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk Dashboard
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center py-3 border-0">
                        <small class="text-muted">&copy; 2026 Faaza Store Retail System</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>