<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = (int)$_POST['id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$harga = (int)$_POST['harga'];
$stok = (int)$_POST['stok'];

// Cek apakah upload gambar baru
if(!empty($_FILES['gambar']['name'])){
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = "../Assets/images/" . $gambar;
    $gambar_db = "images/" . $gambar;
    
    if(move_uploaded_file($tmp, $path)){
        $query = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga', stok='$stok', gambar='$gambar_db' WHERE id_produk='$id'";
    } else {
        echo "<script>alert('Gagal upload gambar!'); window.location.href='edit_produk.php?id=$id';</script>";
        exit;
    }
} else {
    $query = "UPDATE produk SET nama_produk='$nama', kategori='$kategori', deskripsi='$deskripsi', harga='$harga', stok='$stok' WHERE id_produk='$id'";
}

mysqli_query($conn, $query);

if(mysqli_error($conn)){
    echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.location.href='edit_produk.php?id=$id';</script>";
} else {
    echo "<script>alert('Produk berhasil diupdate!'); window.location.href='index.php';</script>";
}
?>