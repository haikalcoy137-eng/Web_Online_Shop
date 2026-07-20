<?php
include "config/koneksi.php";

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Query dengan filter
$where = "WHERE 1=1";
$params = [];

if(!empty($cari)){
    $where .= " AND (nama_produk LIKE '%$cari%' OR deskripsi LIKE '%$cari%')";
}

if(!empty($kategori)){
    $where .= " AND kategori='$kategori'";
}

$order = "ORDER BY id_produk DESC";
if($sort == 'termurah'){
    $order = "ORDER BY harga ASC";
} elseif($sort == 'termahal'){
    $order = "ORDER BY harga DESC";
} elseif($sort == 'terlaris'){
    $order = "ORDER BY terjual DESC";
} elseif($sort == 'terbaru'){
    $order = "ORDER BY id_produk DESC";
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$countQ = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk $where");
$countD = mysqli_fetch_assoc($countQ);
$totalProduk = $countD['total'];
$totalPages = ceil($totalProduk / $limit);

$data = mysqli_query($conn, "SELECT * FROM produk $where $order LIMIT $offset, $limit");
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2>
        <?php if(!empty($cari)): ?>
            Hasil Pencarian: "<?= htmlspecialchars($cari) ?>"
        <?php elseif(!empty($kategori)): ?>
            Kategori: <?= htmlspecialchars($kategori) ?>
        <?php else: ?>
            <i class="fa-solid fa-store"></i> Semua Produk
        <?php endif; ?>
        <span style="font-size:14px;color:#999;font-weight:400;margin-left:10px;"><?= $totalProduk ?> produk ditemukan</span>
    </h2>
    <div style="display:flex;gap:10px;align-items:center;">
        <label style="font-size:13px;color:#666;">Urutkan:</label>
        <select onchange="window.location.href=this.value" style="padding:6px 12px;border:1px solid #ddd;border-radius:8px;font-size:13px;outline:none;">
            <option value="?<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?>sort=terbaru" <?= $sort=='terbaru'||$sort==''?'selected':'' ?>>Terbaru</option>
            <option value="?<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?>sort=terlaris" <?= $sort=='terlaris'?'selected':'' ?>>Terlaris</option>
            <option value="?<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?>sort=termurah" <?= $sort=='termurah'?'selected':'' ?>>Termurah</option>
            <option value="?<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?>sort=termahal" <?= $sort=='termahal'?'selected':'' ?>>Termahal</option>
        </select>
    </div>
</div>

<?php if(mysqli_num_rows($data) > 0): ?>
<div class="product-grid">
    <?php while($row = mysqli_fetch_array($data)): 
        $isWishlist = in_array($row['id_produk'], $wishlistItems);
        $hargaAsli = $row['harga'] * 1.2;
    ?>
    <div class="product-card" onclick="window.location.href='detail_produk.php?id=<?= $row['id_produk'] ?>'">
        <div class="img-wrap">
            <img src="Assets/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
            <button class="wishlist-btn <?= $isWishlist ? 'active' : '' ?>" onclick="event.stopPropagation(); toggleWishlist(<?= $row['id_produk'] ?>, this)">
                <i class="fa-<?= $isWishlist ? 'solid' : 'regular' ?> fa-heart"></i>
            </button>
            <span class="badge">-20%</span>
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

<!-- Pagination -->
<?php if($totalPages > 1): ?>
<div class="pagination">
    <?php if($page > 1): ?>
    <a href="?page=<?= $page-1 ?>&<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?><?= !empty($sort) ? 'sort='.$sort.'&' : '' ?><?= !empty($cari) ? 'cari='.urlencode($cari) : '' ?>">&laquo; Sebelumnya</a>
    <?php endif; ?>
    
    <?php for($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>&<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?><?= !empty($sort) ? 'sort='.$sort.'&' : '' ?><?= !empty($cari) ? 'cari='.urlencode($cari) : '' ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    
    <?php if($page < $totalPages): ?>
    <a href="?page=<?= $page+1 ?>&<?= !empty($kategori) ? 'kategori='.urlencode($kategori).'&' : '' ?><?= !empty($sort) ? 'sort='.$sort.'&' : '' ?><?= !empty($cari) ? 'cari='.urlencode($cari) : '' ?>">Selanjutnya &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="empty-state">
    <i class="fa-solid fa-box-open"></i>
    <h3>Produk Tidak Ditemukan</h3>
    <p>Maaf, produk yang kamu cari tidak tersedia. Coba kata kunci lain.</p>
    <a href="produk.php" class="btn">Lihat Semua Produk</a>
</div>
<?php endif; ?>

<?php include "footer.php"; ?>