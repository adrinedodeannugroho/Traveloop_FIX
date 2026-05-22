<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. KONEKSI DATABASE
// ==========================================
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "db_traveloop_fix"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ==========================================
// 2. LOGIKA CRUD TERPUSAT
// ==========================================
if (isset($_SESSION['admin_logged_in'])) {

    // --- LOGIKA TAMBAH DATA ---
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'tambah') {
        $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $alamat    = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $rating    = mysqli_real_escape_string($koneksi, $_POST['rating']);
        $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']); 
        
        // Input Detail Baru
        $maps_url  = mysqli_real_escape_string($koneksi, $_POST['maps_url']);
        $kontak    = mysqli_real_escape_string($koneksi, $_POST['kontak']);
        $tarif     = mysqli_real_escape_string($koneksi, $_POST['tarif']);
        $history   = mysqli_real_escape_string($koneksi, $_POST['history']);
        $tips      = mysqli_real_escape_string($koneksi, $_POST['tips']);
        
        $foto_path = ""; 

        if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
            // PERBAIKAN: Mengarahkan folder uploads agar sejajar dengan root (bukan di dalam folder config)
            $target_dir = "../uploads/"; 
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_name = time() . '_' . basename($_FILES["foto_file"]["name"]);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["foto_file"]["tmp_name"], $target_file)) {
                // Hilangkan "../" agar path di database tetap bersih saat dipanggil di front-end
                $foto_path = "uploads/" . $file_name; 
            }
        }

        // PERBAIKAN: Mengubah nama kolom menjadi nama, kategori, alamat, deskripsi agar sesuai dengan database
        $query = "INSERT INTO destinasi (nama, kategori, alamat, rating, deskripsi, foto_url, maps_url, kontak, tarif, history, tips) 
                  VALUES ('$nama', '$kategori', '$alamat', '$rating', '$deskripsi', '$foto_path', '$maps_url', '$kontak', '$tarif', '$history', '$tips')";
                  
        if(mysqli_query($koneksi, $query)) {
            // PERBAIKAN PATH REDIRECT: Menggunakan ../ untuk keluar dari folder config
            header("Location: ../admin/admin.php?status=success_tambah");
            exit();
        } else {
            echo "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }

    // --- LOGIKA EDIT DATA ---
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id        = mysqli_real_escape_string($koneksi, $_POST['id']);
        $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $kategori  = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $alamat    = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $rating    = mysqli_real_escape_string($koneksi, $_POST['rating']);
        $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
        
        // Input Detail Baru
        $maps_url  = mysqli_real_escape_string($koneksi, $_POST['maps_url']);
        $kontak    = mysqli_real_escape_string($koneksi, $_POST['kontak']);
        $tarif     = mysqli_real_escape_string($koneksi, $_POST['tarif']);
        $history   = mysqli_real_escape_string($koneksi, $_POST['history']);
        $tips      = mysqli_real_escape_string($koneksi, $_POST['tips']);
        
        $foto_url  = mysqli_real_escape_string($koneksi, $_POST['foto_url_lama']); 

        if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../uploads/"; 
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_name = time() . '_' . basename($_FILES["foto_file"]["name"]);
            $target_file = $target_dir . $file_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'webp']) && move_uploaded_file($_FILES["foto_file"]["tmp_name"], $target_file)) {
                $foto_url = "uploads/" . $file_name; 
            }
        }

        // PERBAIKAN: Mengubah 'name=' menjadi 'nama='
        $query = "UPDATE destinasi SET 
                    nama='$nama', 
                    kategori='$kategori', 
                    alamat='$alamat', 
                    rating='$rating', 
                    deskripsi='$deskripsi', 
                    foto_url='$foto_url',
                    maps_url='$maps_url',
                    kontak='$kontak',
                    tarif='$tarif',
                    history='$history',
                    tips='$tips' 
                  WHERE id='$id'";
        
        if(mysqli_query($koneksi, $query)) {
            // PERBAIKAN PATH REDIRECT
            header("Location: ../admin/admin.php?status=success_edit");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }

    // --- LOGIKA HAPUS DATA ---
    if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_GET['id']);
        
        $query = "DELETE FROM destinasi WHERE id='$id'";
        if(mysqli_query($koneksi, $query)) {
            // PERBAIKAN PATH REDIRECT
            header("Location: ../admin/admin.php?status=success_hapus");
            exit();
        } else {
            echo "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    }
}
?>