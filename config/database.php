<?php
// Pengaturan Database
$db_host = 'localhost';
$db_user = 'root'; // Ganti dengan username database Anda
$db_pass = 'Testing123#';     // Ganti dengan password database Anda
$db_name = 'jual_beli_motor'; // Ganti dengan nama database Anda

// Membuat Koneksi
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Memulai Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>