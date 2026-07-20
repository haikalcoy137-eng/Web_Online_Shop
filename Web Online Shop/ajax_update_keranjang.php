<?php
session_start();
include "config/koneksi.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($_SESSION['id_user'])){
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_keranjang = (int)$input['id_keranjang'];
$jumlah = (int)$input['jumlah'];
$id_user = $_SESSION['id_user'];

// Cek kepemilikan
$cek = mysqli_query($conn, "SELECT keranjang.*, produk.stok FROM keranjang JOIN produk ON keranjang.id_produk=produk.id_produk WHERE id_keranjang='$id_keranjang' AND id_user='$id_user'");
$data = mysqli_fetch_assoc($cek);

if(!$data){
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

if($jumlah > $data['stok']){
    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
    exit;
}

if($jumlah < 1){
    echo json_encode(['success' => false, 'message' => 'Jumlah minimal 1']);
    exit;
}

mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah' WHERE id_keranjang='$id_keranjang'");

echo json_encode(['success' => true]);
?>

<write_to_file>
<path>ajax_hapus_keranjang.php</path>
<content><?php
session_start();
include "config/koneksi.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($_SESSION['id_user'])){
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_keranjang = (int)$input['id_keranjang'];
$id_user = $_SESSION['id_user'];

// Cek kepemilikan
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_keranjang='$id_keranjang' AND id_user='$id_user'");

if(!mysqli_num_rows($cek)){
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id_keranjang'");

echo json_encode(['success' => true]);
?>