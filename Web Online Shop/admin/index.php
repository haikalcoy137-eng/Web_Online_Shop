<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$data = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");

// Ambil statistik
$totalProduk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk"))['total'];
$totalUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='user'"))['total'];
$totalPesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan"))['total'];
$totalPendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS total FROM pesanan WHERE status='selesai'"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Toko Online</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .admin-container { width: 1200px; margin: 20px auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .admin-header h1 { font-size: 24px; color: #222; }
        .admin-header a { padding: 10px 20px; background: #16a34a; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: .3s; }
        .admin-header a:hover { background: #15803d; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .stat-card .icon { font-size: 32px; margin-bottom: 10px; }
        .stat-card .value { font-size: 28px; font-weight: 700; color: #222; }
        .stat-card .label { font-size: 13px; color: #888; margin-top: 4px; }
        .admin-table { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .admin-table h2 { font-size: 18px; margin-bottom: 15px; }
        .admin-table table { width: 100%; border-collapse: collapse; }
        .admin-table th { text-align: left; padding: 12px; background: #f9fafb; font-size: 13px; color: #666; border-bottom: 2px solid #eee; }
        .admin-table td { padding: 12px; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
        .admin-table td img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .admin-table .actions a { padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500; margin-right: 5px; }
        .admin-table .actions .edit { background: #dbeafe; color: #2563eb; }
        .admin-table .actions .edit:hover { background: #bfdbfe; }
        .admin-table .actions .delete { background: #fee2e2; color: #ef4444; }
        .admin-table .actions .delete:hover { background: #fecaca; }
        .admin-nav { display: flex; gap: 10px; margin-bottom: 20px; }
        .admin-nav a { padding: 8px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500; transition: .3s; }
        .admin-nav a.active { background: #16a34a; color: white; }
        .admin-nav a:not(.active) { background: white; color: #555; border: 1px solid #ddd; }
        .admin-nav a:not(.active):hover { border-color: #16a34a; color: #16a34a; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fa-solid fa-shield-halted" style="color:#16a34a;"></i> Dashboard Admin</h1>
        <div>
            <a href="../index.php"><i class="fa-solid fa-store"></i> Lihat Toko</a>
            <a href="../logout.php" style="background:#ef4444;margin-left:10px;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </div>

    <div class="admin-nav">
        <a href="index.php" class="active"><i class="fa-solid fa-box"></i> Produk</a>
        <a href="pesanan.php"><i class="fa-solid fa-truck"></i> Pesanan</a>
        <a href="user.php"><i class="fa-solid fa-users"></i> Users</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="icon" style="color:#16a34a;"><i class="fa-solid fa-box"></i></div>
            <div class="value"><?= $totalProduk ?></div>
            <div class="label">Total Produk</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="color:#3b82f6;"><i class="fa-solid fa-users"></i></div>
            <div class="value"><?= $totalUser ?></div>
            <div class="label">Total User</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="color:#f59e0b;"><i class="fa-solid fa-truck"></i></div>
            <div class="value"><?= $totalPesanan ?></div>
            <div class="label">Total Pesanan</div>
        </div>
        <div class="stat-card">
            <div class="icon" style="color:#ef4444;"><i class="fa-solid fa-money-bill-trend-up"></i></div>
            <div class="value">Rp <?= number_format($totalPendapatan ?: 0) ?></div>
            <div class="label">Pendapatan</div>
        </div>
    </div>

    <div class="admin-table">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <h2><i class="fa-solid fa-box"></i> Daftar Produk</h2>
            <a href="tambah_produk.php" style="padding:8px 16px;background:#16a34a;color:white;text-decoration:none;border-radius:8px;font-size:13px;font-weight:600;">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Terjual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row=mysqli_fetch_array($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><img src="../Assets/<?= $row['gambar'] ?>" alt="" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'"></td>
                    <td><strong><?= $row['nama_produk'] ?></strong></td>
                    <td><?= $row['kategori'] ?: '-' ?></td>
                    <td>Rp <?= number_format($row['harga']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td><?= $row['terjual'] ?: 0 ?></td>
                    <td class="actions">
                        <a href="edit_produk.php?id=<?= $row['id_produk'] ?>" class="edit"><i class="fa-solid fa-pen"></i> Edit</a>
                        <a href="hapus_produk.php?id=<?= $row['id_produk'] ?>" class="delete" onclick="return confirm('Hapus produk ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>