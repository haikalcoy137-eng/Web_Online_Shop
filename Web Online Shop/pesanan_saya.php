<?php
include "config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil pesanan
$data = mysqli_query($conn, "
    SELECT * FROM pesanan WHERE id_user='$id_user' ORDER BY tanggal DESC
");
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2><i class="fa-solid fa-box"></i> Pesanan Saya</h2>
</div>

<div style="width:1200px;margin:0 auto;">
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
                <div>
                    <span class="order-id">Pesanan #<?= $pesanan['id_pesanan'] ?></span>
                    <span style="font-size:12px;color:#888;margin-left:10px;"><?= date('d F Y H:i', strtotime($pesanan['tanggal'])) ?></span>
                </div>
                <span class="order-status <?= $pesanan['status'] ?>">
                    <?php 
                    $statusLabels = [
                        'pending' => 'Menunggu Pembayaran',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan'
                    ];
                    echo $statusLabels[$pesanan['status']] ?? $pesanan['status'];
                    ?>
                </span>
            </div>
            
            <?php while($item = mysqli_fetch_assoc($detail)): ?>
            <div class="order-product">
                <img src="Assets/<?= $item['gambar'] ?>" alt="" onerror="this.src='https://via.placeholder.com/60x60?text=No+Image'">
                <div class="info">
                    <h4><?= $item['nama_produk'] ?></h4>
                    <p><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga']) ?></p>
                </div>
                <div style="margin-left:auto;font-weight:600;color:#16a34a;">
                    Rp <?= number_format($item['jumlah'] * $item['harga']) ?>
                </div>
            </div>
            <?php endwhile; ?>
            
            <div style="text-align:right;margin-top:15px;padding-top:10px;border-top:1px solid #eee;">
                <span style="font-size:14px;color:#888;">Total Pesanan: </span>
                <span style="font-size:18px;font-weight:700;color:#16a34a;">Rp <?= number_format($pesanan['total']) ?></span>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
    <div class="empty-state">
        <i class="fa-solid fa-box-open"></i>
        <h3>Belum Ada Pesanan</h3>
        <p>Kamu belum melakukan pemesanan apapun. Yuk, mulai belanja!</p>
        <a href="produk.php" class="btn"><i class="fa-solid fa-store"></i> Mulai Belanja</a>
    </div>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>