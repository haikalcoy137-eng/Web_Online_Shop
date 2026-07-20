<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = (int)$_GET['id'];

// Hapus juga dari keranjang, wishlist, review, detail_pesanan
mysqli_query($conn, "DELETE FROM keranjang WHERE id_produk='$id'");
mysqli_query($conn, "DELETE FROM wishlist WHERE id_produk='$id'");
mysqli_query($conn, "DELETE FROM review WHERE id_produk='$id'");
mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_produk='$id'");
mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");

header("Location: index.php");
exit;
?>