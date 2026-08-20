<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Faaza Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* RESET & CENTER LAYOUT */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: white;
            overflow: hidden;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* --- BACKGROUND SHAPES --- */
        .shape { 
            position: absolute; 
            border-radius: 50%; 
            z-index: 0; 
            pointer-events: none; 
        }
        
        .circle-yellow { width: 180px; height: 180px; background: #FAFF00; top: -40px; left: -40px; }
        .circle-mint { width: 120px; height: 120px; background: #80FFCC; top: 15%; right: 20%; }
        .circle-red { width: 250px; height: 250px; background: #FF5555; bottom: -80px; left: -60px; border-radius: 50%; }
        .circle-lime { width: 350px; height: 350px; background: #99FF66; bottom: -100px; right: -100px; }

        /* --- CARD FORM --- */
        .card-custom {
            background-color: #E0E0E0;
            border-radius: 20px;
            padding: 25px;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
            z-index: 10;
        }

        .form-title { 
            font-size: 20px; 
            font-weight: bold; 
            text-align: center; 
            margin-bottom: 20px; 
            color: #333; 
        }

        .form-label-custom { 
            font-size: 11px; 
            color: #555; 
            margin-bottom: 3px; 
            display: block; 
            font-weight: 600; 
        }
        
        .form-control-custom {
            background: transparent;
            border: 1px solid #999;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 13px;
            width: 100%;
        }
        
        .form-control-custom:focus { 
            background: white; 
            border-color: #333; 
            box-shadow: none; 
        }

        .btn-custom {
            background: #33FF33; 
            color: black;
            border: none; 
            border-radius: 20px;
            padding: 8px; 
            font-size: 14px;
            font-weight: bold; 
            width: 100%;
            margin-top: 10px; 
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(51, 255, 51, 0.3);
        }
        
        .btn-custom:hover { 
            background: #2ce62c; 
            transform: translateY(-2px); 
        }

        .link-text { 
            font-size: 11px; 
            text-align: center; 
            margin-top: 20px; 
            color: #333; 
        }
        
        .link-text a { color: #007bff; text-decoration: none; font-weight: bold; }

        .alert-mini {
            font-size: 11px;
            padding: 5px 10px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 8px;
        }

        /* Style untuk tombol Admin Kecil */
        .btn-admin {
            font-size: 10px;
            color: #888;
            text-decoration: none;
            border: 1px solid #ccc;
            padding: 3px 10px;
            border-radius: 15px;
            background: white;
            transition: 0.2s;
        }
        .btn-admin:hover {
            background: #333;
            color: white;
            border-color: #333;
        }
    </style>
</head>
<body>

    <div class="shape circle-yellow"></div>
    <div class="shape circle-mint"></div>
    <div class="shape circle-red"></div>
    <div class="shape circle-lime"></div>

    <div class="card-custom">
        <div class="form-title">Masuk</div>

        <?php if(isset($_GET['pesan'])): ?>
            <div class="alert alert-danger alert-mini" role="alert">
                <?= $_GET['pesan']; ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            
            <label class="form-label-custom">Email</label>
            <input type="email" name="email" class="form-control form-control-custom" required>

            <label class="form-label-custom">Password</label>
            <input type="password" name="password" class="form-control form-control-custom" required>

            <button type="submit" class="btn btn-custom">Masuk</button>
        </form>

        <div class="link-text">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
        
        <div class="text-center mt-4 d-flex justify-content-between align-items-center">
            <a href="index.php" style="font-size: 10px; color: #555; text-decoration: none; font-weight: 500;">
                &larr; Kembali
            </a>

            <a href="admin/login.php" class="btn-admin">
                <i class="fas fa-user-shield"></i> Login Admin
            </a>
        </div>
    </div>

</body>
</html>