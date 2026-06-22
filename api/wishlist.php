<?php
// api/wishlist.php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once dirname(__DIR__) . '/config/koneksi.php';

    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu untuk mengakses Wishlist.']);
        exit();
    }

    $user_id = (int)$_SESSION['user_id'];
    
    // Menangkap request JSON dari Script.js (Fetch API)
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    $action = $input['action'] ?? $_POST['action'] ?? $_GET['action'] ?? 'toggle';
    $response = ['status' => 'error', 'message' => 'Aksi tidak diketahui.'];

    switch ($action) {
        case 'toggle':
            $destinasi_id = isset($input['destinasi_id']) ? (int)$input['destinasi_id'] : 0;
            
            if ($destinasi_id <= 0) {
                $response['message'] = 'ID destinasi tidak valid.';
                break;
            }

            $cek = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $user_id AND destinasi_id = $destinasi_id LIMIT 1");
            
            if ($cek && mysqli_num_rows($cek) > 0) {
                $hapus = mysqli_query($koneksi, "DELETE FROM wishlist WHERE user_id = $user_id AND destinasi_id = $destinasi_id");
                if ($hapus) {
                    $response['status'] = 'removed';
                    $response['message'] = 'Destinasi dihapus dari wishlist.';
                }
            } else {
                $tambah = mysqli_query($koneksi, "INSERT INTO wishlist (user_id, destinasi_id) VALUES ($user_id, $destinasi_id)");
                if ($tambah) {
                    $response['status'] = 'added';
                    $response['message'] = 'Destinasi ditambahkan ke wishlist!';
                }
            }
            break;

        case 'check_batch':
            $ids_raw = $input['destinasi_ids'] ?? '';
            $ids = array_filter(array_map('intval', explode(',', $ids_raw)));

            if (empty($ids)) {
                $response['status'] = 'success';
                $response['wishlisted_ids'] = [];
                break;
            }

            $ids_str = implode(',', $ids);
            $q = mysqli_query($koneksi, "SELECT destinasi_id FROM wishlist WHERE user_id = $user_id AND destinasi_id IN ($ids_str)");
            
            $wishlisted = [];
            if ($q) {
                while ($r = mysqli_fetch_assoc($q)) {
                    $wishlisted[] = (int)$r['destinasi_id'];
                }
            }
            
            $response['status'] = 'success';
            $response['wishlisted_ids'] = $wishlisted;
            break;
    }

    echo json_encode($response);
    exit;

} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
    exit;
}
?>