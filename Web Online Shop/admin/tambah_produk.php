<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin</title>
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
    </style>
</head>
<body>

<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fa-solid fa-plus" style="color:#16a34a;"></i> Tambah Produk</h1>
        <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="form-card">
        <form action="simpan_produk.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="">Pilih Kategori</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Keyboard">Keyboard</option>
                    <option value="Mouse">Mouse</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Audio">Audio</option>
                    <option value="Gaming">Gaming</option>
                    <option value="Aksesoris">Aksesoris</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Printer">Printer</option>
                    <option value="Storage">Storage</option>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" required>
                </div>
            </div>
            <div class="form-group">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div>
            <button type="submit" class="btn-submit"><i class="fa-solid fa-save"></i> Simpan Produk</button>
        </form>
    </div>
</div>

</body>
</html>