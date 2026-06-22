<?php
// api/simpan_itinerary.php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once dirname(__DIR__) . '/config/koneksi.php';

    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);

    $tema = mysqli_real_escape_string($koneksi, $input['tema'] ?? '');
    $kota_utama = mysqli_real_escape_string($koneksi, $input['kota_utama'] ?? '');
    $destinasi_ids = $input['destinasi_ids'] ?? [];

    if (empty($tema) || empty($destinasi_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Data itinerary tidak lengkap.']);
        exit;
    }

    // Ubah array ID menjadi string JSON untuk disimpan
    $ids_json = mysqli_real_escape_string($koneksi, json_encode($destinasi_ids));

    // Cek batas simpan agar tidak spam (Opsional: batasi max 10 itinerary per user)
    $cek_limit = mysqli_query($koneksi, "SELECT COUNT(id) as total FROM user_itineraries WHERE user_id = $user_id");
    if ($cek_limit && mysqli_fetch_assoc($cek_limit)['total'] >= 10) {
        echo json_encode(['status' => 'error', 'message' => 'Batas maksimal penyimpanan (10 itinerary) tercapai. Silakan hapus itinerary lama Anda.']);
        exit;
    }

    $query = "INSERT INTO user_itineraries (user_id, tema, kota_utama, destinasi_ids) VALUES ($user_id, '$tema', '$kota_utama', '$ids_json')";
    
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Itinerary berhasil disimpan ke akun Anda!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>