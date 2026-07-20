<?php
session_start();
include "../config/koneksi.php";

$id_produk=$_GET['id'];

$id_user=$_SESSION['id_user'];

$cek=mysqli_query($conn,"
SELECT * FROM keranjang
WHERE id_produk='$id_produk'
AND id_user='$id_user'
");

if(mysqli_num_rows($cek)>0){

    mysqli_query($conn,"
    UPDATE keranjang
    SET jumlah=jumlah+1
    WHERE id_produk='$id_produk'
    AND id_user='$id_user'
    ");

}else{

    mysqli_query($conn,"
    INSERT INTO keranjang(id_user,id_produk,jumlah)
    VALUES('$id_user','$id_produk',1)
    ");

}

if(mysqli_error($conn)){
    die(mysqli_error($conn));
}

header("Location:../keranjang.php");
exit;
?>