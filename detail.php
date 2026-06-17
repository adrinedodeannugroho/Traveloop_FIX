<?php
// detail.php
require_once 'includes/header.php';

// Menangkap ID dari URL dengan validasi
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE id = $id");

if(!$query || mysqli_num_rows($query) == 0) {
    // Redirect ke explore jika id tidak ditemukan
    echo "<script>window.location.href='explore.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($query);
$foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/1200x600/e2e8f0/64748b?text=Wisata+Traveloop';
?>

<div class="detail-hero" style="background-image: url('<?= $foto ?>'); margin-top: 70px;">
    <div class="detail-hero-overlay"></div>
    <div class="container detail-hero-content">
        <span class="gem-badge-detail mb-3 d-inline-block"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['kategori']) ?></span>
        <h1 class="detail-title"><?= htmlspecialchars($row['nama']) ?></h1>
        <p class="detail-addr"><i class="bi bi-pin-map-fill me-2"></i><?= htmlspecialchars($row['alamat']) ?></p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <h4 class="detail-section-title mb-3">Cerita Destinasi</h4>
            <p class="detail-desc mb-5">
                <?= isset($row['deskripsi']) ? nl2br(htmlspecialchars($row['deskripsi'])) : 'Deskripsi belum tersedia untuk destinasi ini.' ?>
            </p>

            <?php if(!empty($row['history'])): ?>
            <h4 class="detail-section-title mb-3">Sejarah & Latar Belakang</h4>
            <div class="detail-history mb-5">
                <p class="history-text"><?= nl2br(htmlspecialchars($row['history'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if(!empty($row['tips'])): ?>
            <h4 class="detail-section-title mb-3">Tips Berkunjung</h4>
            <div class="detail-tips mb-5">
                <p class="tips-text"><i class="bi bi-lightbulb me-2 text-success"></i><?= htmlspecialchars($row['tips']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-lg-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Informasi Penting</h5>
                
                <div class="detail-info-grid">
                    <div class="detail-info-item">
                        <i class="bi bi-star-fill"></i> 
                        <strong>Rating:</strong> <?= $row['rating'] ?> / 5.0
                    </div>
                    <?php if(!empty($row['tarif'])): ?>
                    <div class="detail-info-item">
                        <i class="bi bi-ticket-perforated"></i> 
                        <strong>Tarif Masuk:</strong> <?= htmlspecialchars($row['tarif']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($row['kontak'])): ?>
                    <div class="detail-info-item">
                        <i class="bi bi-telephone"></i> 
                        <strong>Kontak:</strong> <?= htmlspecialchars($row['kontak']) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?= !empty($row['maps_url']) ? $row['maps_url'] : '#' ?>" target="_blank" class="btn btn-gmaps w-100 mt-4 py-3 rounded-3">
                    <i class="bi bi-map me-2"></i>Petunjuk Arah (Maps)
                </a>

                <!-- Tombol Wishlist -->
                <?php
                $is_wishlisted = false;
                if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                    $uid = (int)$_SESSION['user_id'];
                    $did = (int)$row['id'];
                    $wcheck = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $uid AND destinasi_id = $did LIMIT 1");
                    $is_wishlisted = ($wcheck && mysqli_num_rows($wcheck) > 0);
                }
                ?>
                <button class="btn <?= $is_wishlisted ? 'btn-danger' : 'btn-outline-danger' ?> w-100 mt-3 py-3 rounded-3 fw-bold wishlist-btn-detail"
                        id="detailWishlistBtn"
                        onclick="toggleWishlist(<?= $row['id'] ?>, this)"
                        data-id="<?= $row['id'] ?>">
                    <i class="bi bi-heart<?= $is_wishlisted ? '-fill' : '' ?> me-2 wishlist-icon-animate"></i>
                    <?= $is_wishlisted ? 'Tersimpan di Wishlist' : 'Tambah ke Wishlist' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil Footer
require_once 'includes/footer.php'; 
?>