<?php
session_start();
if(isset($_SESSION['id_user'])){
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Toko Online</title>
    <link rel="stylesheet" href="assets/css/asset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fa-solid fa-store"></i>
            <h2>Daftar</h2>
            <p>Buat akun Toko Online baru</p>
        </div>

        <form action="proses_register.php" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            <button type="submit" class="btn-submit">Daftar</button>
        </form>

        <div class="auth-link">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </div>
    </div>
</div>
</body>
</html>