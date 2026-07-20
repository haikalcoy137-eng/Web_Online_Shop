<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$data = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .admin-container { width: 1200px; margin: 20px auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .admin-header h1 { font-size: 24px; color: #222; }
        .admin-nav { display: flex; gap: 10px; margin-bottom: 20px; }
        .admin-nav a { padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500; transition: .3s; }
        .admin-nav a.active { background: #16a34a; color: white; }
        .admin-nav a:not(.active) { background: white; color: #555; border: 1px solid #ddd; }
        .admin-nav a:not(.active):hover { border-color: #16a34a; color: #16a34a; }
        .admin-table { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .admin-table h2 { font-size: 18px; margin-bottom: 15px; }
        .admin-table table { width: 100%; border-collapse: collapse; }
        .admin-table th { text-align: left; padding: 12px; background: #f9fafb; font-size: 13px; color: #666; border-bottom: 2px solid #eee; }
        .admin-table td { padding: 12px; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
        .role-badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .role-badge.admin { background: #fee2e2; color: #ef4444; }
        .role-badge.user { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fa-solid fa-users" style="color:#16a34a;"></i> Kelola Users</h1>
        <a href="index.php" style="padding:8px 16px;background:#16a34a;color:white;text-decoration:none;border-radius:8px;font-size:13px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="admin-nav">
        <a href="index.php"><i class="fa-solid fa-box"></i> Produk</a>
        <a href="pesanan.php"><i class="fa-solid fa-truck"></i> Pesanan</a>
        <a href="user.php" class="active"><i class="fa-solid fa-users"></i> Users</a>
    </div>

    <div class="admin-table">
        <h2><i class="fa-solid fa-users"></i> Daftar Users (<?= mysqli_num_rows($data) ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>ID User</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row=mysqli_fetch_array($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><span class="role-badge <?= $row['role'] ?>"><?= $row['role'] ?></span></td>
                    <td>#<?= $row['id_user'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>