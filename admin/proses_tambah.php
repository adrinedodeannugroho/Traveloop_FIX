<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_logged_in'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $rating   = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $foto_url = mysqli_real_escape_string($koneksi, $_POST['foto_url']);
    
    // Sesuaikan nama kolom dengan struktur tabel `destinasi` di database Anda
    $query = "INSERT INTO destinasi (nama, kategori, alamat, rating, foto_url) 
              VALUES ('$nama', '$kategori', '$alamat', '$rating', '$foto_url')";
              
    if(mysqli_query($koneksi, $query)) {
        header("Location: admin.php"); // Kembali ke admin panel jika sukses
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>