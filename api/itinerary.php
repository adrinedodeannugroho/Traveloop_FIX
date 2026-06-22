<?php
// api/itinerary.php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        echo json_encode(['status' => 'error', 'message' => 'Fatal Error: ' . $err['message']]);
        exit;
    }
});

try {
    require_once dirname(__DIR__) . '/config/koneksi.php';

    if (!isset($koneksi) || !$koneksi) {
        echo json_encode(['status' => 'error', 'message' => 'Koneksi Database Gagal.']);
        exit;
    }

    $tema = isset($_GET['tema']) ? mysqli_real_escape_string($koneksi, $_GET['tema']) : 'Alam';

    $kategori_map = [
        'Alam' => "'Alam', 'Gunung'",
        'Pantai' => "'Pantai'",
        'Sejarah' => "'Sejarah', 'Budaya'"
    ];
    $kategori_sql = isset($kategori_map[$tema]) ? $kategori_map[$tema] : "'Alam'";

    // Ambil data acak berdasarkan kategori tanpa filter sponsor
    $query_reguler = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE kategori IN ($kategori_sql) ORDER BY RAND() LIMIT 3");
    
    if (!$query_reguler) {
         echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan database: ' . mysqli_error($koneksi)]);
         exit;
    }

    $destinasi = [];
    $kota_utama = 'Purwokerto'; 

    while ($row = mysqli_fetch_assoc($query_reguler)) {
        $norm = [];
        foreach($row as $key => $val) {
            $norm[strtolower($key)] = $val;
        }
        if (empty($norm['foto_url'])) {
            $norm['foto_url'] = 'https://placehold.co/150x150/e2e8f0/64748b?text=Wisata';
        }
        // Mocking sponsor untuk JS agar tidak error UI
        $norm['is_sponsored'] = 0; 
        $destinasi[] = $norm;
    }

    if(count($destinasi) > 0) {
        $alamat_acuan = strtolower($destinasi[0]['alamat'] ?? '');
        if (strpos($alamat_acuan, 'banyumas') !== false || strpos($alamat_acuan, 'baturraden') !== false) $kota_utama = 'Banyumas';
        elseif (strpos($alamat_acuan, 'purbalingga') !== false) $kota_utama = 'Purbalingga';
        elseif (strpos($alamat_acuan, 'banjarnegara') !== false) $kota_utama = 'Banjarnegara';
        elseif (strpos($alamat_acuan, 'cilacap') !== false) $kota_utama = 'Cilacap';
        elseif (strpos($alamat_acuan, 'kebumen') !== false) $kota_utama = 'Kebumen';
    }

    echo json_encode([
        'status' => 'success',
        'tema' => $tema,
        'kota_utama' => $kota_utama,
        'jumlah_data' => count($destinasi),
        'data' => $destinasi
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
    exit;
}
?>