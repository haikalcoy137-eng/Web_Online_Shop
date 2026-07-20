<?php
include "config/koneksi.php";

// Ambil produk terbaru
$produkTerbaru = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 10");

// Ambil produk terlaris
$produkTerlaris = mysqli_query($conn, "SELECT * FROM produk ORDER BY terjual DESC LIMIT 10");

// Ambil produk dengan diskon (contoh: harga asli * 1.2 untuk simulasi diskon)
$produkDiskon = mysqli_query($conn, "SELECT * FROM produk ORDER BY RAND() LIMIT 5");
?>
<?php include "header.php"; ?>


<!-- Hero Section Simple -->
<div class="hero" style="background:linear-gradient(135deg,#16a34a,#22c55e);width:1200px;margin:20px auto;border-radius:20px;padding:50px;color:white;text-align:center;">
    <h1 style="font-size:42px;margin-bottom:15px;">Selamat Datang di Toko Online</h1>
    <p style="font-size:18px;margin-bottom:25px;opacity:.9;">Belanja mudah, cepat, dan terpercaya dengan harga terbaik!</p>
    <a href="produk.php" style="display:inline-block;padding:14px 40px;background:white;color:#16a34a;border-radius:30px;text-decoration:none;font-weight:bold;font-size:16px;transition:.3s;">Mulai Belanja</a>
</div>


<!-- Kategori -->
<div class="categories">
    <a href="produk.php?kategori=Laptop" class="category-item">
        <i class="fa-solid fa-laptop"></i>
        <span>Laptop</span>
    </a>
    <a href="produk.php?kategori=Keyboard" class="category-item">
        <i class="fa-solid fa-keyboard"></i>
        <span>Keyboard</span>
    </a>
    <a href="produk.php?kategori=Mouse" class="category-item">
        <i class="fa-solid fa-computer-mouse"></i>
        <span>Mouse</span>
    </a>
    <a href="produk.php?kategori=Audio" class="category-item">
        <i class="fa-solid fa-headphones"></i>
        <span>Audio</span>
    </a>
    <a href="produk.php?kategori=Monitor" class="category-item">
        <i class="fa-solid fa-desktop"></i>
        <span>Monitor</span>
    </a>
    <a href="produk.php?kategori=Gaming" class="category-item">
        <i class="fa-solid fa-gamepad"></i>
        <span>Gaming</span>
    </a>
    <a href="produk.php?kategori=Aksesoris" class="category-item">
        <i class="fa-solid fa-plug"></i>
        <span>Aksesoris</span>
    </a>
    <a href="produk.php?kategori=Tablet" class="category-item">
        <i class="fa-solid fa-tablet-screen-button"></i>
        <span>Tablet</span>
    </a>
    <a href="produk.php?kategori=Printer" class="category-item">
        <i class="fa-solid fa-print"></i>
        <span>Printer</span>
    </a>
    <a href="produk.php?kategori=Storage" class="category-item">
        <i class="fa-solid fa-hard-drive"></i>
        <span>Storage</span>
    </a>
</div>

<!-- Section: Produk Terbaru -->
<div class="section-title">
    <h2><i class="fa-solid fa-clock"></i> Produk Terbaru</h2>
    <a href="produk.php">Lihat Semua <i class="fa-solid fa-chevron-right"></i></a>
</div>

<div class="product-grid">
    <?php while($row = mysqli_fetch_array($produkTerbaru)): 
        $isWishlist = in_array($row['id_produk'], $wishlistItems);
        $hargaAsli = $row['harga'] * 1.2;
    ?>
    <div class="product-card" onclick="window.location.href='detail_produk.php?id=<?= $row['id_produk'] ?>'">
        <div class="img-wrap">
            <img src="Assets/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
            <button class="wishlist-btn <?= $isWishlist ? 'active' : '' ?>" onclick="event.stopPropagation(); toggleWishlist(<?= $row['id_produk'] ?>, this)">
                <i class="fa-<?= $isWishlist ? 'solid' : 'regular' ?> fa-heart"></i>
            </button>
        </div>
        <div class="info">
            <h3><?= $row['nama_produk'] ?></h3>
            <div class="price">
                Rp <?= number_format($row['harga']) ?>
                <span class="original">Rp <?= number_format($hargaAsli) ?></span>
            </div>
            <div class="meta">
                <?php if($row['rating'] > 0): ?>
                <span class="rating"><i class="fa-solid fa-star"></i> <?= $row['rating'] ?></span>
                <?php endif; ?>
                <?php if($row['terjual'] > 0): ?>
                <span class="sold">Terjual <?= $row['terjual'] ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Section: Produk Terlaris -->
<div class="section-title">
    <h2><i class="fa-solid fa-fire" style="color:#ef4444;"></i> Produk Terlaris</h2>
    <a href="produk.php?sort=terlaris">Lihat Semua <i class="fa-solid fa-chevron-right"></i></a>
</div>

<div class="product-grid">
    <?php while($row = mysqli_fetch_array($produkTerlaris)): 
        $isWishlist = in_array($row['id_produk'], $wishlistItems);
        $hargaAsli = $row['harga'] * 1.2;
    ?>
    <div class="product-card" onclick="window.location.href='detail_produk.php?id=<?= $row['id_produk'] ?>'">
        <div class="img-wrap">
            <img src="Assets/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
            <button class="wishlist-btn <?= $isWishlist ? 'active' : '' ?>" onclick="event.stopPropagation(); toggleWishlist(<?= $row['id_produk'] ?>, this)">
                <i class="fa-<?= $isWishlist ? 'solid' : 'regular' ?> fa-heart"></i>
            </button>
            <?php if($row['terjual'] > 5): ?>
            <span class="badge">Best Seller</span>
            <?php endif; ?>
        </div>
        <div class="info">
            <h3><?= $row['nama_produk'] ?></h3>
            <div class="price">
                Rp <?= number_format($row['harga']) ?>
            </div>
            <div class="meta">
                <?php if($row['rating'] > 0): ?>
                <span class="rating"><i class="fa-solid fa-star"></i> <?= $row['rating'] ?></span>
                <?php endif; ?>
                <span class="sold">Terjual <?= $row['terjual'] ?></span>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include "footer.php"; ?>