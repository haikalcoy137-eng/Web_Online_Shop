<?php
include "config/koneksi.php";

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'"));

// Update profil
if(isset($_POST['update_profil'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email' WHERE id_user='$id_user'");
    $_SESSION['nama'] = $nama;
    echo "<script>alert('Profil berhasil diupdate!'); window.location.href='profil.php';</script>";
    exit;
}

// Update password
if(isset($_POST['update_password'])){
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $pass_konfirm = $_POST['pass_konfirm'];
    
    if(!password_verify($pass_lama, $user['password'])){
        echo "<script>alert('Password lama salah!');</script>";
    } elseif($pass_baru != $pass_konfirm){
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } elseif(strlen($pass_baru) < 6){
        echo "<script>alert('Password minimal 6 karakter!');</script>";
    } else {
        $pass_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$pass_hash' WHERE id_user='$id_user'");
        echo "<script>alert('Password berhasil diubah!');</script>";
    }
}

// Tambah alamat
if(isset($_POST['tambah_alamat'])){
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $kode_pos = mysqli_real_escape_string($conn, $_POST['kode_pos']);
    $is_utama = isset($_POST['is_utama']) ? 1 : 0;
    
    if($is_utama){
        mysqli_query($conn, "UPDATE alamat SET is_utama=0 WHERE id_user='$id_user'");
    }
    
    mysqli_query($conn, "INSERT INTO alamat(id_user, label, alamat, kota, provinsi, kode_pos, is_utama) VALUES('$id_user','$label','$alamat','$kota','$provinsi','$kode_pos','$is_utama')");
    echo "<script>alert('Alamat berhasil ditambahkan!'); window.location.href='profil.php?tab=alamat';</script>";
    exit;
}

// Hapus alamat
if(isset($_GET['hapus_alamat'])){
    $id_alamat = (int)$_GET['hapus_alamat'];
    mysqli_query($conn, "DELETE FROM alamat WHERE id_alamat='$id_alamat' AND id_user='$id_user'");
    header("Location: profil.php?tab=alamat");
    exit;
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profil';
$alamat = mysqli_query($conn, "SELECT * FROM alamat WHERE id_user='$id_user' ORDER BY is_utama DESC");
?>
<?php include "header.php"; ?>

<div class="section-title">
    <h2><i class="fa-regular fa-user"></i> Profil Saya</h2>
</div>

<div class="profile-page">
    <div class="profile-sidebar">
        <div class="user-info">
            <div class="avatar"><?= strtoupper(substr($user['nama'], 0, 1)) ?></div>
            <h4><?= htmlspecialchars($user['nama']) ?></h4>
            <p><?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div class="menu-list">
            <a href="profil.php?tab=profil" class="<?= $tab == 'profil' ? 'active' : '' ?>">
                <i class="fa-regular fa-user"></i> Profil
            </a>
            <a href="profil.php?tab=password" class="<?= $tab == 'password' ? 'active' : '' ?>">
                <i class="fa-solid fa-lock"></i> Ubah Password
            </a>
            <a href="profil.php?tab=alamat" class="<?= $tab == 'alamat' ? 'active' : '' ?>">
                <i class="fa-solid fa-location-dot"></i> Alamat
            </a>
            <a href="pesanan_saya.php">
                <i class="fa-solid fa-box"></i> Pesanan Saya
            </a>
            <a href="wishlist.php">
                <i class="fa-regular fa-heart"></i> Wishlist
            </a>
            <a href="logout.php" style="color:#ef4444;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <div class="profile-content">
        <?php if($tab == 'profil'): ?>
        <h3>Edit Profil</h3>
        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <button type="submit" name="update_profil" class="btn-submit">Simpan Perubahan</button>
        </form>

        <?php elseif($tab == 'password'): ?>
        <h3>Ubah Password</h3>
        <form method="POST">
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="pass_lama" required>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="pass_baru" required minlength="6">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="pass_konfirm" required>
            </div>
            <button type="submit" name="update_password" class="btn-submit">Ubah Password</button>
        </form>

        <?php elseif($tab == 'alamat'): ?>
        <h3>Alamat Saya</h3>
        
        <?php while($alm = mysqli_fetch_assoc($alamat)): ?>
        <div class="address-card" style="margin-bottom:10px;position:relative;">
            <div class="label">
                <?= htmlspecialchars($alm['label']) ?>
                <?php if($alm['is_utama']): ?>
                <span style="background:#16a34a;color:white;padding:2px 8px;border-radius:4px;font-size:10px;margin-left:8px;">Utama</span>
                <?php endif; ?>
            </div>
            <div class="detail">
                <?= htmlspecialchars($alm['alamat']) ?><br>
                <?= htmlspecialchars($alm['kota']) ?>, <?= htmlspecialchars($alm['provinsi']) ?> <?= htmlspecialchars($alm['kode_pos']) ?>
            </div>
            <a href="?tab=alamat&hapus_alamat=<?= $alm['id_alamat'] ?>" onclick="return confirm('Hapus alamat ini?')" style="position:absolute;top:10px;right:10px;color:#ef4444;text-decoration:none;">
                <i class="fa-solid fa-trash-can"></i>
            </a>
        </div>
        <?php endwhile; ?>

        <h4 style="margin-top:25px;font-size:15px;color:#555;">Tambah Alamat Baru</h4>
        <form method="POST">
            <div class="form-group">
                <label>Label (contoh: Rumah, Kantor)</label>
                <input type="text" name="label" placeholder="Rumah" required>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" required></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="form-group">
                    <label>Kota</label>
                    <input type="text" name="kota" required>
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" name="kode_pos" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_utama"> Jadikan alamat utama
                </label>
            </div>
            <button type="submit" name="tambah_alamat" class="btn-submit">Simpan Alamat</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include "footer.php"; ?>