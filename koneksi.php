<?php
// koneksi.php
$host     = "localhost";
$user     = "root"; // Username default XAMPP
$password = "";     // Password default XAMPP (kosong)
$db       = "db_traveloop"; // Sesuaikan dengan nama database Anda di phpMyAdmin

$koneksi = mysqli_connect($host, $user, $password, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>