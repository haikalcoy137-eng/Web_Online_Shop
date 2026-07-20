<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Update status pesanan
if(isset($_GET['update_status'])){
    $id_pesanan = (int)$_GET['id'];
    $status = mysqli_real_escape_string($conn, $_GET['update_status']);
    mysqli_query($conn, "UPDATE pesanan SET status='$status' WHERE id_pesanan='$id_pesanan'");
    header("Location: pesanan.php");
    exit;
}

$data = mysqli_query($conn, "
    SELECT pesanan.*, users.nama 
    FROM pesanan 
    JOIN users ON pesanan.id_user = users.id_user 
    ORDER BY pesanan.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Admin</title>
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
        .order-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .order-header { display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .order-header .info { font-size: 14px; }
        .order-header .info strong { color: #222; }
        .order-header .info span { color: #888; margin-left: 10px; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-badge.pending { background: #fef3c7; color: #d97706; }
        .status-badge.diproses { background: #dbeafe; color: #2563eb; }
        .status-badge.dikirim { background: #e0e7ff; color: #4f46e5; }
        .status-badge.selesai { background: #d1fae5; color: #16a34a; }
        .status-badge.dibatalkan { background: #fee2e2; color: #ef4444; }
        .order-product { display: flex; gap: 12px; align-items: center; padding: 8px 0; }
        .order-product img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .order-product .info h4 { font-size: 14px; }
        .order-product .info p { font-size: 12px; color: #888; }
        .order-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 10px; border-top: 1px solid #eee; }
        .order-footer .total { font-size: 18px; font-weight: 700; color: #16a34a; }
        .order-footer .actions { display: flex; gap: 8px; }
        .order-footer .actions a { padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; }
        .btn-status { background: #f0fdf4; color: #16a34a; border: 1px solid #16a34a; }
        .btn-status:hover { background: #16a34a; color: white; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fa-solid fa-truck" style="color:#16a34a;"></i> Kelola Pesanan</h1>
        <a href="index.php" style="padding:8px 16px;background:#16a34a;color:white;text-decoration:none;border-radius:8px;font-size:13px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="admin-nav">
        <a href="index.php"><i class="fa-solid fa-box"></i> Produk</a>
        <a href="pesanan.php" class="active"><i class="fa-solid fa-truck"></i> Pesanan</a>
        <a href="user.php"><i class="fa-solid fa-users"></i> Users</a>
    </div>

    <?php if(mysqli_num_rows($data) > 0): ?>
        <?php while($pesanan = mysqli_fetch_assoc($data)): 
            $detail = mysqli_query($conn, "
                SELECT detail_pesanan.*, produk.nama_produk, produk.gambar
                FROM detail_pesanan
                JOIN produk ON detail_pesanan.id_produk = produk.id_produk
                WHERE detail_pesanan.id_pesanan = '{$pesanan['id_pesanan']}'
            ");
        ?>
        <div class="order-card">
            <div class="order-header">
                <div class="info">
                    <strong>#<?= $pesanan['id_pesanan'] ?></strong> - <?= htmlspecialchars($pesanan['nama']) ?>
                    <span><?= date('d F Y H:i', strtotime($pesanan['tanggal'])) ?></span>
                </div>
                <span class="status-badge <?= $pesanan['status'] ?>">
                    <?php 
                    $labels = ['pending'=>'Menunggu','diproses'=>'Diproses','dikirim'=>'Dikirim','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
                    echo $labels[$pesanan['status']] ?? $pesanan['status'];
                    ?>
                </span>
            </div>
            
            <?php while($item = mysqli_fetch_assoc($detail)): ?>
            <div class="order-product">
                <img src="../Assets/<?= $item['gambar'] ?>" alt="" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'">
                <div class="info">
                    <h4><?= $item['nama_produk'] ?></h4>
                    <p><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga']) ?></p>
                </div>
                <div style="margin-left:auto;font-weight:600;">Rp <?= number_format($item['jumlah'] * $item['harga']) ?></div>
            </div>
            <?php endwhile; ?>
            
            <div class="order-footer">
                <div class="total">Rp <?= number_format($pesanan['total']) ?></div>
                <div class="actions">
                    <?php if($pesanan['status'] == 'pending'): ?>
                        <a href="?update_status=diproses&id=<?= $pesanan['id_pesanan'] ?>" class="btn-status">Proses</a>
                        <a href="?update_status=dibatalkan&id=<?= $pesanan['id_pesanan'] ?>" class="btn-status" style="color:#ef4444;border-color:#ef4444;" onclick="return confirm('Batalkan pesanan?')">Batalkan</a>
                    <?php elseif($pesanan['status'] == 'diproses'): ?>
                        <a href="?update_status=dikirim&id=<?= $pesanan['id_pesanan'] ?>" class="btn-status">Kirim</a>
                    <?php elseif($pesanan['status'] == 'dikirim'): ?>
                        <a href="?update_status=selesai&id=<?= $pesanan['id_pesanan'] ?>" class="btn-status">Selesaikan</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-box-open"></i>
            <h3>Belum Ada Pesanan</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>