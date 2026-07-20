<?php
include "config/koneksi.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil produk
$q = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$produk = mysqli_fetch_assoc($q);

if(!$produk){
    header("Location: produk.php");
    exit;
}

// Ambil review
$reviews = mysqli_query($conn, "SELECT review.*, users.nama FROM review JOIN users ON review.id_user=users.id_user WHERE review.id_produk='$id' ORDER BY review.created_at DESC");

// Hitung rating rata-rata
$ratingQ = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_review FROM review WHERE id_produk='$id'");
$ratingData = mysqli_fetch_assoc($ratingQ);
$avgRating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 1) : 0;
$totalReview = $ratingData['total_review'];

// Cek wishlist
$isWishlist = false;
if(isset($_SESSION['id_user'])){
    $cek = mysqli_query($conn, "SELECT * FROM wishlist WHERE id_user='{$_SESSION['id_user']}' AND id_produk='$id'");
    $isWishlist = mysqli_num_rows($cek) > 0;
}
?>
<?php include "header.php"; ?>

<!-- Breadcrumb -->
<div style="width:1200px;margin:15px auto 0;font-size:13px;color:#888;">
    <a href="index.php" style="color:#888;text-decoration:none;">Home</a>
    <i class="fa-solid fa-chevron-right" style="margin:0 8px;font-size:10px;"></i>
    <a href="produk.php" style="color:#888;text-decoration:none;">Produk</a>
    <i class="fa-solid fa-chevron-right" style="margin:0 8px;font-size:10px;"></i>
    <span style="color:#16a34a;"><?= $produk['nama_produk'] ?></span>
</div>

<!-- Product Detail -->
<div class="product-detail">
    <div class="gallery">
        <img id="mainImage" class="main-img" src="Assets/<?= $produk['gambar'] ?>" alt="<?= $produk['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
        <div class="thumbnails">
            <img src="Assets/<?= $produk['gambar'] ?>" alt="" class="active" onclick="document.getElementById('mainImage').src=this.src; document.querySelectorAll('.thumbnails img').forEach(i=>i.classList.remove('active')); this.classList.add('active');">
            <?php
            // Generate dummy thumbnails
            $thumbnails = ['Keyboard.png', 'Mouse.webp', 'Laptop gaming.webp'];
            foreach($thumbnails as $thumb):
            ?>
            <img src="Assets/images/<?= $thumb ?>" alt="" onerror="this.style.display='none'" onclick="document.getElementById('mainImage').src=this.src; document.querySelectorAll('.thumbnails img').forEach(i=>i.classList.remove('active')); this.classList.add('active');">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="info">
        <h1><?= $produk['nama_produk'] ?></h1>
        
        <div class="rating-row">
            <span class="stars">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-<?= $i <= round($avgRating) ? 'solid' : 'regular' ?> fa-star" style="color:<?= $i <= round($avgRating) ? '#f59e0b' : '#ddd' ?>"></i>
                <?php endfor; ?>
            </span>
            <span><?= $avgRating ?> (<?= $totalReview ?> ulasan)</span>
            <span>| Terjual <?= $produk['terjual'] ?: 0 ?></span>
            <span>| Stok: <?= $produk['stok'] ?></span>
        </div>

        <div class="price-box">
            <span class="current">Rp <?= number_format($produk['harga']) ?></span>
            <span class="original">Rp <?= number_format($produk['harga'] * 1.2) ?></span>
            <span class="discount">-20%</span>
        </div>

        <div class="desc">
            <?= nl2br($produk['deskripsi']) ?>
        </div>

        <?php if($produk['stok'] > 0): ?>
        <div class="qty">
            <span style="font-size:14px;font-weight:500;color:#555;">Jumlah:</span>
            <button onclick="updateQty(-1)">-</button>
            <input type="number" id="qtyInput" value="1" min="1" max="<?= $produk['stok'] ?>" readonly>
            <button onclick="updateQty(1)">+</button>
        </div>

        <div class="actions">
            <button class="btn-primary" onclick="addToCart(<?= $produk['id_produk'] ?>, parseInt(document.getElementById('qtyInput').value))">
                <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
            </button>
            <button class="btn-secondary" onclick="addToCart(<?= $produk['id_produk'] ?>, parseInt(document.getElementById('qtyInput').value)); window.location.href='keranjang.php'">
                <i class="fa-solid fa-bolt"></i> Beli Langsung
            </button>
        </div>
        <?php else: ?>
        <div style="padding:20px;background:#fee2e2;border-radius:10px;color:#ef4444;font-weight:600;text-align:center;">
            <i class="fa-solid fa-times-circle"></i> Stok Habis
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Review Section -->
<div class="review-section">
    <h3><i class="fa-solid fa-star" style="color:#f59e0b;"></i> Ulasan Pembeli (<?= $totalReview ?>)</h3>
    
    <?php if(mysqli_num_rows($reviews) > 0): ?>
        <?php while($rv = mysqli_fetch_assoc($reviews)): ?>
        <div class="review-card">
            <div class="user">
                <div class="avatar"><?= strtoupper(substr($rv['nama'], 0, 1)) ?></div>
                <span class="name"><?= htmlspecialchars($rv['nama']) ?></span>
            </div>
            <div class="stars">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-<?= $i <= $rv['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                <?php endfor; ?>
            </div>
            <div class="comment"><?= nl2br(htmlspecialchars($rv['komentar'])) ?></div>
            <div class="date"><?= date('d F Y', strtotime($rv['created_at'])) ?></div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state" style="padding:30px;">
            <i class="fa-regular fa-comment-dots" style="font-size:40px;"></i>
            <h3>Belum Ada Ulasan</h3>
            <p>Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function updateQty(change) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + change;
    if(val < 1) val = 1;
    if(val > <?= $produk['stok'] ?>) val = <?= $produk['stok'] ?>;
    input.value = val;
}
</script>

<?php include "footer.php"; ?>