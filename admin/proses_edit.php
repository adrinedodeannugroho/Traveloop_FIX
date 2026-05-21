<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_logged_in'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $foto_url = mysqli_real_escape_string($koneksi, $_POST['foto_url']);

    $query = "UPDATE destinasi SET nama='$nama', kategori='$kategori', alamat='$alamat', rating='$rating', foto_url='$foto_url' WHERE id='$id'";
    
    if(mysqli_query($koneksi, $query)) {
        header("Location: admin.php?status=success");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>