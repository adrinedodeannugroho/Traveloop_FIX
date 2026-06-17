<?php
// api/wishlist.php
// AJAX Endpoint untuk operasi Wishlist (toggle, check, list, count)
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/config/koneksi.php';

$response = ['success' => false, 'message' => ''];

// Cek apakah user sudah login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $response['message'] = 'not_logged_in';
    echo json_encode($response);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ─── Toggle Wishlist (Tambah / Hapus) ─────────────────────
    case 'toggle':
        $destinasi_id = isset($_POST['destinasi_id']) ? (int)$_POST['destinasi_id'] : 0;
        
        if ($destinasi_id <= 0) {
            $response['message'] = 'ID destinasi tidak valid.';
            break;
        }

        // Cek apakah sudah ada di wishlist
        $cek = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $user_id AND destinasi_id = $destinasi_id LIMIT 1");
        
        if ($cek && mysqli_num_rows($cek) > 0) {
            // Sudah ada → Hapus dari wishlist
            $hapus = mysqli_query($koneksi, "DELETE FROM wishlist WHERE user_id = $user_id AND destinasi_id = $destinasi_id");
            if ($hapus) {
                $response['success'] = true;
                $response['action'] = 'removed';
                $response['message'] = 'Destinasi dihapus dari wishlist.';
            } else {
                $response['message'] = 'Gagal menghapus dari wishlist.';
            }
        } else {
            // Belum ada → Tambah ke wishlist
            $tambah = mysqli_query($koneksi, "INSERT INTO wishlist (user_id, destinasi_id) VALUES ($user_id, $destinasi_id)");
            if ($tambah) {
                $response['success'] = true;
                $response['action'] = 'added';
                $response['message'] = 'Destinasi ditambahkan ke wishlist!';
            } else {
                $response['message'] = 'Gagal menambahkan ke wishlist.';
            }
        }

        // Hitung jumlah wishlist terbaru
        $count_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wishlist WHERE user_id = $user_id");
        $response['wishlist_count'] = ($count_q) ? (int)mysqli_fetch_assoc($count_q)['total'] : 0;
        break;

    // ─── Cek Status Wishlist ──────────────────────────────────
    case 'check':
        $destinasi_id = isset($_POST['destinasi_id']) ? (int)$_POST['destinasi_id'] : 0;

        if ($destinasi_id <= 0) {
            $response['message'] = 'ID destinasi tidak valid.';
            break;
        }

        $cek = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $user_id AND destinasi_id = $destinasi_id LIMIT 1");
        $response['success'] = true;
        $response['is_wishlisted'] = ($cek && mysqli_num_rows($cek) > 0);
        break;

    // ─── Cek Multiple Wishlist (untuk batch card check) ──────
    case 'check_batch':
        $ids_raw = $_POST['destinasi_ids'] ?? '';
        $ids = array_filter(array_map('intval', explode(',', $ids_raw)));

        if (empty($ids)) {
            $response['success'] = true;
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
        
        $response['success'] = true;
        $response['wishlisted_ids'] = $wishlisted;
        break;

    // ─── Hitung Jumlah Wishlist ──────────────────────────────
    case 'count':
        $count_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wishlist WHERE user_id = $user_id");
        $response['success'] = true;
        $response['wishlist_count'] = ($count_q) ? (int)mysqli_fetch_assoc($count_q)['total'] : 0;
        break;

    default:
        $response['message'] = 'Action tidak dikenali.';
        break;
}

echo json_encode($response);
?>
