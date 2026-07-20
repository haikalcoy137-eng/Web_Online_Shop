<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga = (int)$_POST['harga'];
$stok = (int)$_POST['stok'];

// Upload gambar
$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];
$path = "../Assets/images/" . $gambar;

if(move_uploaded_file($tmp, $path)){
    $gambar_db = "images/" . $gambar;
    $query = "INSERT INTO produk(nama_produk, deskripsi, kategori, harga, stok, gambar) VALUES('$nama','$deskripsi','$kategori','$harga','$stok','$gambar_db')";
    mysqli_query($conn, $query);
    
    if(mysqli_error($conn)){
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.location.href='tambah_produk.php';</script>";
    } else {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='index.php';</script>";
    }
} else {
    echo "<script>alert('Gagal upload gambar!'); window.location.href='tambah_produk.php';</script>";
}
?>