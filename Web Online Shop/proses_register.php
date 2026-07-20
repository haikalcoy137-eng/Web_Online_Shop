<?php
include "config/koneksi.php";

$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($cek) > 0){
    echo "<script>
    alert('Email sudah digunakan');
    window.location='register.php';
    </script>";
} else {
    mysqli_query($conn, "INSERT INTO users(nama, email, password, role) VALUES('$nama','$email','$password','user')");
    
    if(mysqli_error($conn)){
        echo "<script>alert('Gagal registrasi!'); window.location='register.php';</script>";
    } else {
        echo "<script>
        alert('Registrasi Berhasil! Silakan login.');
        window.location='login.php';
        </script>";
    }
}
?>