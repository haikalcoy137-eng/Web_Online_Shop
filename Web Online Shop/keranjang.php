<?php
include "config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data keranjang
$data = mysqli_query($conn, "
    SELECT keranjang.*, produk.nama_produk, produk.harga, produk.gambar, produk.stok
    FROM keranjang
    JOIN produk ON keranjang.id_produk = produk.id_produk
    WHERE keranjang.id_user = '$id_user'
    ORDER BY keranjang.id_keranjang DESC
");

$total = 0;
$count = mysqli_num_rows($data);
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2><i class="fa-solid fa-cart-shopping"></i> Keranjang Belanja</h2>
    <span style="font-size:14px;color:#999;"><?= $count ?> item</span>
</div>

<?php if($count > 0): ?>
<div class="cart-page">
    <div class="cart-items">
        <h2>Daftar Belanja</h2>
        
        <?php while($d = mysqli_fetch_array($data)): 
            $subtotal = $d['harga'] * $d['jumlah'];
            $total += $subtotal;
        ?>
        <div class="cart-item" data-id="<?= $d['id_keranjang'] ?>" data-harga="<?= $d['harga'] ?>">
            <img src="Assets/<?= $d['gambar'] ?>" alt="<?= $d['nama_produk'] ?>" onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
            <div class="item-info">
                <h4><?= $d['nama_produk'] ?></h4>
                <div class="item-price">Rp <?= number_format($d['harga']) ?></div>
            </div>
            <div class="item-qty">
                <button onclick="updateCart(<?= $d['id_keranjang'] ?>, <?= $d['jumlah']-1 ?>, this.nextElementSibling)">-</button>
                <span><?= $d['jumlah'] ?></span>
                <button onclick="updateCart(<?= $d['id_keranjang'] ?>, <?= $d['jumlah']+1 ?>, this.previousElementSibling)">+</button>
            </div>
            <div class="item-total">Rp <?= number_format($subtotal) ?></div>
            <button class="delete-btn" onclick="deleteCartItem(<?= $d['id_keranjang'] ?>, this)"><i class="fa-solid fa-trash-can"></i></button>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="cart-summary">
        <h3>Ringkasan Belanja</h3>
        <div class="row">
            <span>Total Harga</span>
            <span class="cart-subtotal">Rp <?= number_format($total) ?></span>
        </div>
        <div class="row">
            <span>Total Item</span>
            <span><?= $count ?> item</span>
        </div>
        <div class="row total">
            <span>Total</span>
            <span class="cart-total">Rp <?= number_format($total) ?></span>
        </div>
        <a href="checkout.php" class="checkout-btn">
            <i class="fa-solid fa-bolt"></i> Checkout
        </a>
        <a href="produk.php" style="display:block;text-align:center;margin-top:10px;color:#888;font-size:13px;text-decoration:none;">
            <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
        </a>
    </div>
</div>
<?php else: ?>
<div class="empty-state">
    <i class="fa-solid fa-cart-shopping"></i>
    <h3>Keranjang Belanja Kosong</h3>
    <p>Yuk, mulai belanja produk-produk terbaik kami!</p>
    <a href="produk.php" class="btn"><i class="fa-solid fa-store"></i> Mulai Belanja</a>
</div>
<?php endif; ?>

<?php include "footer.php"; ?>

<write_to_file>
<path>checkout.php</path>
<content><?php
include "config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data keranjang
$data = mysqli_query($conn, "
    SELECT keranjang.*, produk.nama_produk, produk.harga, produk.gambar, produk.stok
    FROM keranjang
    JOIN produk ON keranjang.id_produk = produk.id_produk
    WHERE keranjang.id_user = '$id_user'
");

$count = mysqli_num_rows($data);

if($count == 0){
    header("Location: keranjang.php");
    exit;
}

// Ambil alamat user
$alamat = mysqli_query($conn, "SELECT * FROM alamat WHERE id_user='$id_user' ORDER BY is_utama DESC");

// Hitung total
$total = 0;
$items = [];
while($d = mysqli_fetch_array($data)){
    $subtotal = $d['harga'] * $d['jumlah'];
    $total += $subtotal;
    $items[] = $d;
}

// Proses checkout
if(isset($_POST['checkout'])){
    $id_alamat = (int)$_POST['id_alamat'];
    
    // Buat pesanan
    mysqli_query($conn, "INSERT INTO pesanan(id_user, tanggal, total, status) VALUES('$id_user', NOW(), '$total', 'pending')");
    $id_pesanan = mysqli_insert_id($conn);
    
    // Insert detail pesanan
    foreach($items as $item){
        $id_produk = $item['id_produk'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        mysqli_query($conn, "INSERT INTO detail_pesanan(id_pesanan, id_produk, jumlah, harga) VALUES('$id_pesanan', '$id_produk', '$jumlah', '$harga')");
        
        // Update stok dan terjual
        mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah, terjual = terjual + $jumlah WHERE id_produk='$id_produk'");
    }
    
    // Hapus keranjang
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_user='$id_user'");
    
    echo "<script>alert('Pesanan berhasil dibuat!'); window.location.href='pesanan_saya.php';</script>";
    exit;
}
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2><i class="fa-solid fa-truck"></i> Checkout</h2>
</div>

<form method="POST" class="checkout-page">
    <div>
        <!-- Alamat -->
        <div class="checkout-section">
            <h3><i class="fa-solid fa-location-dot"></i> Alamat Pengiriman</h3>
            <?php if(mysqli_num_rows($alamat) > 0): ?>
                <?php while($alm = mysqli_fetch_assoc($alamat)): ?>
                <label class="address-card <?= $alm['is_utama'] ? 'selected' : '' ?>" style="display:block;margin-bottom:10px;">
                    <input type="radio" name="id_alamat" value="<?= $alm['id_alamat'] ?>" <?= $alm['is_utama'] ? 'checked' : '' ?> required style="display:none;">
                    <div class="label">
                        <i class="fa-solid fa-location-dot" style="color:#16a34a;"></i> 
                        <?= htmlspecialchars($alm['label']) ?>
                        <?php if($alm['is_utama']): ?>
                        <span style="background:#16a34a;color:white;padding:2px 8px;border-radius:4px;font-size:10px;margin-left:8px;">Utama</span>
                        <?php endif; ?>
                    </div>
                    <div class="detail">
                        <?= htmlspecialchars($alm['alamat']) ?><br>
                        <?= htmlspecialchars($alm['kota']) ?>, <?= htmlspecialchars($alm['provinsi']) ?> <?= htmlspecialchars($alm['kode_pos']) ?>
                    </div>
                </label>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#888;font-size:14px;">Belum ada alamat. Silakan tambah alamat di profil.</p>
            <?php endif; ?>
            <a href="profil.php?tab=alamat" style="display:inline-block;margin-top:10px;color:#16a34a;font-size:13px;text-decoration:none;">
                <i class="fa-solid fa-plus"></i> Tambah Alamat Baru
            </a>
        </div>

        <!-- Produk -->
        <div class="checkout-section">
            <h3><i class="fa-solid fa-box"></i> Produk Dipesan</h3>
            <?php foreach($items as $item): ?>
            <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #f5f5f5;align-items:center;">
                <img src="Assets/<?= $item['gambar'] ?>" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                <div style="flex:1;">
                    <h4 style="font-size:14px;"><?= $item['nama_produk'] ?></h4>
                    <p style="font-size:12px;color:#888;"><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga']) ?></p>
                </div>
                <div style="font-weight:600;color:#16a34a;">Rp <?= number_format($item['harga'] * $item['jumlah']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Ringkasan -->
    <div>
        <div class="cart-summary">
            <h3>Ringkasan Pesanan</h3>
            <div class="row">
                <span>Total Harga</span>
                <span>Rp <?= number_format($total) ?></span>
            </div>
            <div class="row">
                <span>Ongkos Kirim</span>
                <span style="color:#16a34a;">Gratis</span>
            </div>
            <div class="row total">
                <span>Total</span>
                <span>Rp <?= number_format($total) ?></span>
            </div>
            <button type="submit" name="checkout" class="checkout-btn">
                <i class="fa-solid fa-check"></i> Buat Pesanan
            </button>
            <a href="keranjang.php" style="display:block;text-align:center;margin-top:10px;color:#888;font-size:13px;text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang
            </a>
        </div>
    </div>
</form>

<?php include "footer.php"; ?>