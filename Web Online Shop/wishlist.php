<?php
include "config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil wishlist
$data = mysqli_query($conn, "
    SELECT produk.*, wishlist.created_at AS wishlist_date
    FROM wishlist
    JOIN produk ON wishlist.id_produk = produk.id_produk
    WHERE wishlist.id_user = '$id_user'
    ORDER BY wishlist.created_at DESC
");
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2><i class="fa-regular fa-heart"></i> Wishlist Saya</h2>
    <span style="font-size:14px;color:#999;"><?= mysqli_num_rows($data) ?> item</span>
</div>

<?php if(mysqli_num_rows($data) > 0): ?>
<div class="product-grid">
    <?php while($row = mysqli_fetch_array($data)): 
        $hargaAsli = $row['harga'] * 1.2;
    ?>
    <div class="product-card" onclick="window.location.href='detail_produk.php?id=<?= $row['id_produk'] ?>'">
        <div class="img-wrap">
            <img src="Assets/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
            <button class="wishlist-btn active" onclick="event.stopPropagation(); toggleWishlist(<?= $row['id_produk'] ?>, this); this.closest('.product-card').style.display='none';">
                <i class="fa-solid fa-heart"></i>
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
<?php else: ?>
<div class="empty-state">
    <i class="fa-regular fa-heart"></i>
    <h3>Wishlist Kosong</h3>
    <p>Simpan produk favoritmu di sini dengan menekan ikon hati.</p>
    <a href="produk.php" class="btn"><i class="fa-solid fa-store"></i> Jelajahi Produk</a>
</div>
<?php endif; ?>

<?php include "footer.php"; ?>