<?php
session_start();
include "config/koneksi.php";

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$data = mysqli_fetch_assoc($q);

if($data && password_verify($password, $data['password'])){
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];
    
    if($data['role'] == 'admin'){
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
} else {
    echo "<script>alert('Email atau password salah!'); window.location.href='login.php';</script>";
}
?>