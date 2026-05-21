<?php
include 'koneksi.php';

// Menangkap ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE id = $id");

if(mysqli_num_rows($query) == 0) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'><h2>Destinasi tidak ditemukan.</h2><a href='index.php'>Kembali ke Home</a></div>");
}

$row = mysqli_fetch_assoc($query);
$foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/1200x600/e2e8f0/64748b?text=Wisata';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($row['nama']) ?> — Traveloop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg wn-navbar wn-navbar-solid fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php"><span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop</a>
    <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link" href="explore.php">Kembali ke Explore</a></li></ul>
  </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="row g-4">
        <div class="col-12">
            <img src="<?= $foto ?>" class="img-fluid rounded-4 w-100 object-fit-cover" style="height: 400px;" alt="<?= htmlspecialchars($row['nama']) ?>">
        </div>
        
        <div class="col-lg-8 mt-4">
            <span class="badge bg-primary mb-2"><?= htmlspecialchars($row['kategori']) ?></span>
            <h1 class="display-5 fw-bold" style="font-family: 'Playfair Display', serif;"><?= htmlspecialchars($row['nama']) ?></h1>
            <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-2"></i><?= htmlspecialchars($row['alamat']) ?></p>
            
            <hr>
            
            <h4 class="mt-4 fw-bold">Tentang Destinasi</h4>
            <p style="line-height: 1.8;">
                <?= isset($row['deskripsi']) ? nl2br(htmlspecialchars($row['deskripsi'])) : 'Deskripsi belum tersedia untuk destinasi ini.' ?>
            </p>
        </div>

        <div class="col-lg-4 mt-4">
            <div class="card shadow-sm border-0 rounded-4 p-4" style="background-color: var(--bg-soft);">
                <h5 class="fw-bold mb-3">Informasi Singkat</h5>
                <ul class="list-unstyled mb-0" style="line-height: 2;">
                    <li><i class="bi bi-star-fill text-warning me-2"></i> <strong>Rating:</strong> <?= $row['rating'] ?> / 5.0</li>
                    </ul>
                <a href="https://maps.google.com/?q=<?= urlencode($row['nama'] . ' ' . $row['alamat']) ?>" target="_blank" class="btn btn-primary w-100 mt-4 rounded-3" style="background-color: var(--accent); border: none;">
                    <i class="bi bi-map me-2"></i>Buka di Google Maps
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>