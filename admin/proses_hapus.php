<?php
session_start();
include '../koneksi.php';

if (isset($_GET['id']) && isset($_SESSION['admin_logged_in'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $query = "DELETE FROM destinasi WHERE id='$id'";
    if(mysqli_query($koneksi, $query)) {
        header("Location: admin.php");
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    header("Location: admin.php");
}
?>