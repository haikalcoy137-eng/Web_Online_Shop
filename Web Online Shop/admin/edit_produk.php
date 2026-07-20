<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = (int)$_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id'");
$row = mysqli_fetch_assoc($data);

if(!$row){
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin</title>
    <link rel="stylesheet" href="../assets/css/asset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .admin-container { width: 800px; margin: 20px auto; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .admin-header h1 { font-size: 24px; color: #222; }
        .admin-header a { padding: 8px 16px; background: #888; color: white; text-decoration: none; border-radius: 8px; font-size: 13px; }
        .form-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; outline: none; transition: .3s; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.1); }
        .form-group textarea { height: 120px; resize: vertical; }
        .btn-submit { padding: 12px 30px; background: #16a34a; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: .3s; }
        .btn-submit:hover { background: #15803d; }
        .preview-img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fa-solid fa-pen" style="color:#16a34a;"></i> Edit Produk</h1>
        <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="form-card">
        <form action="update_produk.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id_produk'] ?>">
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama_produk']) ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Pilih Kategori</option>
                    <option value="Laptop" <?= $row['kategori']=='Laptop'?'selected':'' ?>>Laptop</option>
                    <option value="Keyboard" <?= $row['kategori']=='Keyboard'?'selected':'' ?>>Keyboard</option>
                    <option value="Mouse" <?= $row['kategori']=='Mouse'?'selected':'' ?>>Mouse</option>
                    <option value="Monitor" <?= $row['kategori']=='Monitor'?'selected':'' ?>>Monitor</option>
                    <option value="Audio" <?= $row['kategori']=='Audio'?'selected':'' ?>>Audio</option>
                    <option value="Gaming" <?= $row['kategori']=='Gaming'?'selected':'' ?>>Gaming</option>
                    <option value="Aksesoris" <?= $row['kategori']=='Aksesoris'?'selected':'' ?>>Aksesoris</option>
                    <option value="Tablet" <?= $row['kategori']=='Tablet'?'selected':'' ?>>Tablet</option>
                    <option value="Printer" <?= $row['kategori']=='Printer'?'selected':'' ?>>Printer</option>
                    <option value="Storage" <?= $row['kategori']=='Storage'?'selected':'' ?>>Storage</option>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $row['harga'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= $row['stok'] ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Gambar Produk (biarkan kosong jika tidak diganti)</label>
                <input type="file" name="gambar" accept="image/*">
                <br>
                <img src="../Assets/<?= $row['gambar'] ?>" class="preview-img" onerror="this.style.display='none'">
            </div>
            <button type="submit" class="btn-submit"><i class="fa-solid fa-save"></i> Update Produk</button>
        </form>
    </div>
</div>

</body>
</html>