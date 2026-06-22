<?php
// api/kelola_itinerary.php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once dirname(__DIR__) . '/config/koneksi.php';

    // Proteksi Keamanan Sesi Sisi Server
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Otorisasi ditolak. Silakan login terlebih dahulu.']);
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    $action = $input['action'] ?? '';
    $itinerary_id = (int)($input['itinerary_id'] ?? 0);

    if ($itinerary_id <= 0 || empty($action)) {
        echo json_encode(['status' => 'error', 'message' => 'Parameter permintaan tidak valid.']);
        exit;
    }

    // Validasi Hak Milik: Pastikan itinerary ini benar-benar milik pengguna yang sedang login
    $cek_kepemilikan = mysqli_query($koneksi, "SELECT id, destinasi_ids FROM user_itineraries WHERE id = $itinerary_id AND user_id = $user_id LIMIT 1");
    if (!$cek_kepemilikan || mysqli_num_rows($cek_kepemilikan) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses ke rute ini.']);
        exit;
    }

    $current_data = mysqli_fetch_assoc($cek_kepemilikan);

    switch ($action) {
        // ─── AKSI 1: HAPUS ITINERARY PERMANEN ────────────────────────────────
        case 'delete':
            $hapus = mysqli_query($koneksi, "DELETE FROM user_itineraries WHERE id = $itinerary_id AND user_id = $user_id");
            if ($hapus) {
                echo json_encode(['status' => 'success', 'message' => 'Rencana perjalanan berhasil dihapus secara permanen.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database.']);
            }
            break;

        // ─── AKSI 2: EDIT / MODIFIKASI URUTAN DESTINASI RUTE ──────────────────
        case 'update':
            $updated_ids = $input['destinasi_ids'] ?? [];
            if (empty($updated_ids) || !is_array($updated_ids)) {
                echo json_encode(['status' => 'error', 'message' => 'Rute perjalanan minimal harus menyisakan 1 destinasi wisata.']);
                exit;
            }

            // Bersihkan data array ID ke format integer aman
            $clean_ids = array_filter(array_map('intval', $updated_ids));
            $ids_json = mysqli_real_escape_string($koneksi, json_encode(array_values($clean_ids)));

            $update = mysqli_query($koneksi, "UPDATE user_itineraries SET destinasi_ids = '$ids_json' WHERE id = $itinerary_id AND user_id = $user_id");
            if ($update) {
                echo json_encode(['status' => 'success', 'message' => 'Rute perjalanan Anda berhasil diperbarui!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui susunan database.']);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Operasi manajemen tidak dikenali.']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Sistem Sibuk: ' . $e->getMessage()]);
}
?>