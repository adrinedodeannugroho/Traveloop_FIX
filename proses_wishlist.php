<?php
// 1. Matikan sementara pesan error PHP agar tidak merusak balikan JSON
error_reporting(0);

// 2. Cek cerdas: Jika session belum ada, baru jalankan session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Panggil koneksi database
require_once 'config/koneksi.php';

// 4. Paksa output menjadi JSON murni
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
    exit;
}

$uid = (int)$_SESSION['user_id'];

// Ambil data JSON yang dikirim dari JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['destinasi_id'])) {
    $destinasi_id = (int)$data['destinasi_id'];

    // Cek apakah destinasi ini sudah ada di wishlist user
    $cek = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $uid AND destinasi_id = $destinasi_id");

    if ($cek && mysqli_num_rows($cek) > 0) {
        // Hapus dari wishlist
        $query = "DELETE FROM wishlist WHERE user_id = $uid AND destinasi_id = $destinasi_id";
        if (mysqli_query($koneksi, $query)) {
            echo json_encode(['status' => 'removed']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database.']);
        }
    } else {
        // Tambahkan ke wishlist
        $query = "INSERT INTO wishlist (user_id, destinasi_id) VALUES ($uid, $destinasi_id)";
        if (mysqli_query($koneksi, $query)) {
            echo json_encode(['status' => 'added']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID destinasi tidak valid atau kosong.']);
}
?>