<?php
session_start();
include "config/koneksi.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($_SESSION['id_user'])){
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_produk = (int)$input['id_produk'];
$jumlah = isset($input['jumlah']) ? (int)$input['jumlah'] : 1;
$id_user = $_SESSION['id_user'];

// Cek stok
$cekStok = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk='$id_produk'");
$stokData = mysqli_fetch_assoc($cekStok);

if(!$stokData || $stokData['stok'] < $jumlah){
    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
    exit;
}

// Cek apakah sudah di keranjang
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_produk='$id_produk' AND id_user='$id_user'");

if(mysqli_num_rows($cek) > 0){
    $d = mysqli_fetch_assoc($cek);
    $newJumlah = $d['jumlah'] + $jumlah;
    
    // Cek stok lagi
    if($stokData['stok'] < $newJumlah){
        echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
        exit;
    }
    
    mysqli_query($conn, "UPDATE keranjang SET jumlah='$newJumlah' WHERE id_keranjang='{$d['id_keranjang']}'");
} else {
    mysqli_query($conn, "INSERT INTO keranjang(id_user, id_produk, jumlah) VALUES('$id_user','$id_produk','$jumlah')");
}

// Hitung total keranjang
$q = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM keranjang WHERE id_user='$id_user'");
$d = mysqli_fetch_assoc($q);

echo json_encode([
    'success' => true,
    'total_keranjang' => $d['total'] ?? 0
]);
?>

<write_to_file>
<path>ajax_wishlist.php</path>
<content><?php
session_start();
include "config/koneksi.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($_SESSION['id_user'])){
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_produk = (int)$input['id_produk'];
$id_user = $_SESSION['id_user'];

$cek = mysqli_query($conn, "SELECT * FROM wishlist WHERE id_user='$id_user' AND id_produk='$id_produk'");

if(mysqli_num_rows($cek) > 0){
    mysqli_query($conn, "DELETE FROM wishlist WHERE id_user='$id_user' AND id_produk='$id_produk'");
    echo json_encode(['status' => 'removed', 'success' => true]);
} else {
    mysqli_query($conn, "INSERT INTO wishlist(id_user, id_produk) VALUES('$id_user','$id_produk')");
    echo json_encode(['status' => 'added', 'success' => true]);
}
?>