<?php
session_start();
include "config/koneksi.php";

// Hitung total keranjang
$totalKeranjang = 0;
if(isset($_SESSION['id_user'])){
    $id = $_SESSION['id_user'];
    $q = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM keranjang WHERE id_user='$id'");
    $d = mysqli_fetch_assoc($q);
    $totalKeranjang = $d['total'] ?? 0;
}

// Cek wishlist user
$wishlistItems = [];
if(isset($_SESSION['id_user'])){
    $id = $_SESSION['id_user'];
    $qw = mysqli_query($conn, "SELECT id_produk FROM wishlist WHERE id_user='$id'");
    while($w = mysqli_fetch_assoc($qw)){
        $wishlistItems[] = $w['id_produk'];
    }
}

// Cari parameter
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Online Shop</title>
    <link rel="stylesheet" href="assets/css/asset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<!-- Top Header -->
<div class="top-header">
    <div class="container">
        <div>
            <i class="fa-solid fa-store"></i> Web Online Shop
        </div>
        <div>
            <?php if(isset($_SESSION['id_user'])): ?>
                <a href="profil.php"><i class="fa-regular fa-user"></i> <?= $_SESSION['nama'] ?></a>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="admin/index.php"><i class="fa-solid fa-shield-halted"></i> Admin</a>
                <?php endif; ?>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            <?php else: ?>
                <a href="login.php"><i class="fa-regular fa-user"></i> Login</a>
                <a href="register.php"><i class="fa-regular fa-pen-to-square"></i> Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="main-header">
    <div class="container">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-store"></i>
            Toko Online
        </a>

        <form class="search-box" method="GET" action="produk.php">
            <input type="text" name="cari" placeholder="Cari produk..." value="<?= htmlspecialchars($cari) ?>">
            <button type="submit"><i class="fa-solid fa-search"></i></button>
        </form>

        <div class="header-actions">
            <a href="produk.php">
                <i class="fa-regular fa-rectangle-list"></i>
                <span>Produk</span>
            </a>
            <a href="wishlist.php">
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="keranjang.php">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if($totalKeranjang > 0): ?>
                <span class="cart-badge"><?= $totalKeranjang ?></span>
                <?php endif; ?>
                <span>Keranjang</span>
            </a>
        </div>
    </div>
</header>

<!-- Category Navigation -->
<nav class="category-nav">
    <div class="container">
        <a href="produk.php" class="<?= !isset($_GET['kategori']) || $_GET['kategori'] == '' ? 'active' : '' ?>">Semua</a>
        <?php
        $katQ = mysqli_query($conn, "SELECT DISTINCT kategori FROM produk WHERE kategori IS NOT NULL AND kategori != ''");
        while($kat = mysqli_fetch_assoc($katQ)):
            $active = (isset($_GET['kategori']) && $_GET['kategori'] == $kat['kategori']) ? 'active' : '';
        ?>
        <a href="produk.php?kategori=<?= urlencode($kat['kategori']) ?>" class="<?= $active ?>"><?= htmlspecialchars($kat['kategori']) ?></a>
        <?php endwhile; ?>
    </div>
</nav>