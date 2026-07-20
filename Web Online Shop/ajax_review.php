<?php
session_start();
include "config/koneksi.php";

header('Content-Type: application/json');

if(!isset($_SESSION['id_user'])){
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$id_produk = (int)$input['id_produk'];
$rating = (int)$input['rating'];
$komentar = mysqli_real_escape_string($conn, $input['komentar']);
$id_user = $_SESSION['id_user'];

if($rating < 1 || $rating > 5){
    echo json_encode(['success' => false, 'message' => 'Rating harus 1-5']);
    exit;
}

// Cek apakah sudah pernah review
$cek = mysqli_query($conn, "SELECT * FROM review WHERE id_user='$id_user' AND id_produk='$id_produk'");
if(mysqli_num_rows($cek) > 0){
    echo json_encode(['success' => false, 'message' => 'Kamu sudah memberikan review untuk produk ini']);
    exit;
}

// Cek apakah user sudah membeli produk ini
$cekPesanan = mysqli_query($conn, "
    SELECT detail_pesanan.* FROM detail_pesanan 
    JOIN pesanan ON detail_pesanan.id_pesanan = pesanan.id_pesanan 
    WHERE pesanan.id_user='$id_user' AND detail_pesanan.id_produk='$id_produk' AND pesanan.status='selesai'
");
if(mysqli_num_rows($cekPesanan) == 0){
    echo json_encode(['success' => false, 'message' => 'Kamu hanya bisa mereview produk yang sudah dibeli']);
    exit;
}

mysqli_query($conn, "INSERT INTO review(id_user, id_produk, rating, komentar) VALUES('$id_user','$id_produk','$rating','$komentar')");

// Update rating produk
$avg = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating FROM review WHERE id_produk='$id_produk'");
$avgData = mysqli_fetch_assoc($avg);
$avgRating = round($avgData['avg_rating'], 1);
mysqli_query($conn, "UPDATE produk SET rating='$avgRating' WHERE id_produk='$id_produk'");

echo json_encode(['success' => true, 'message' => 'Review berhasil dikirim']);
?>