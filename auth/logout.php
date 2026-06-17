<?php
// auth/logout.php
require_once dirname(__DIR__) . '/config/koneksi.php';

// Hapus semua session user
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);
unset($_SESSION['user_nama']);
unset($_SESSION['user_email']);

// Destroy session
session_destroy();

// Redirect ke halaman utama
header("Location: ../index.php");
exit();
?>
