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

<div class="detail-hero position-relative" style="background-image: url('<?= $foto ?>'); margin-top: 70px; background-size: cover; background-position: center; min-height: 40vh; display: flex; align-items: flex-end; padding-bottom: 2rem;">
    <div class="detail-hero-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.2));"></div>
    <div class="container detail-hero-content position-relative z-1 px-3">
        <span class="gem-badge-detail mb-2 d-inline-block bg-primary text-white px-3 py-1 rounded-pill small fw-bold"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['kategori']) ?></span>
        <h1 class="detail-title text-white fw-bold display-5 display-md-3 mb-2"><?= htmlspecialchars($row['nama']) ?></h1>
        <p class="detail-addr text-white-50 small mb-0"><i class="bi bi-pin-map-fill me-2 text-warning"></i><?= htmlspecialchars($row['alamat']) ?></p>
    </div>
</div>

<div class="container py-4 py-lg-5 px-3">
    <div class="row g-4 g-lg-5">
        <div class="col-lg-8">
            <h4 class="detail-section-title mb-3 fw-bold">Cerita Destinasi</h4>
            <div class="detail-desc mb-4 text-muted" style="line-height: 1.8;">
                <?= isset($row['deskripsi']) ? nl2br(htmlspecialchars($row['deskripsi'])) : 'Deskripsi belum tersedia untuk destinasi ini.' ?>
            </div>

            <?php if(!empty($row['history'])): ?>
            <h4 class="detail-section-title mb-3 fw-bold mt-4">Sejarah & Latar Belakang</h4>
            <div class="detail-history mb-4 text-muted" style="line-height: 1.8;">
                <p class="history-text mb-0"><?= nl2br(htmlspecialchars($row['history'])) ?></p>
            </div>
            <?php endif; ?>

            <?php if(!empty($row['tips'])): ?>
            <h4 class="detail-section-title mb-3 fw-bold mt-4">Tips Berkunjung</h4>
            <div class="detail-tips bg-light p-3 p-md-4 rounded-4 mb-4 border-start border-success border-4">
                <p class="tips-text mb-0 text-dark"><i class="bi bi-lightbulb-fill me-2 text-success fs-5"></i><?= htmlspecialchars($row['tips']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 p-lg-4 sticky-lg-top" style="top: 90px;">
                <h5 class="fw-bold mb-4 border-bottom pb-3">Informasi Penting</h5>
                
                <div class="detail-info-grid d-flex flex-column gap-3 mb-4">
                    <div class="detail-info-item d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-2 rounded-3 me-3 text-warning"><i class="bi bi-star-fill fs-5"></i></div> 
                        <div>
                            <small class="text-muted d-block">Rating</small>
                            <strong class="text-dark"><?= $row['rating'] ?> / 5.0</strong>
                        </div>
                    </div>
                    <?php if(!empty($row['tarif'])): ?>
                    <div class="detail-info-item d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary"><i class="bi bi-ticket-perforated-fill fs-5"></i></div> 
                        <div>
                            <small class="text-muted d-block">Tarif Masuk</small>
                            <strong class="text-dark"><?= htmlspecialchars($row['tarif']) ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($row['kontak'])): ?>
                    <div class="detail-info-item d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3 text-success"><i class="bi bi-telephone-fill fs-5"></i></div> 
                        <div>
                            <small class="text-muted d-block">Kontak</small>
                            <strong class="text-dark"><?= htmlspecialchars($row['kontak']) ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?= !empty($row['maps_url']) ? $row['maps_url'] : '#' ?>" target="_blank" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm mb-3">
                    <i class="bi bi-map-fill me-2"></i>Petunjuk Arah (Maps)
                </a>

                <?php
                $is_wishlisted = false;
                if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                    $uid = (int)$_SESSION['user_id'];
                    $did = (int)$row['id'];
                    $wcheck = mysqli_query($koneksi, "SELECT id FROM wishlist WHERE user_id = $uid AND destinasi_id = $did LIMIT 1");
                    $is_wishlisted = ($wcheck && mysqli_num_rows($wcheck) > 0);
                }
                ?>
                <button class="btn <?= $is_wishlisted ? 'btn-danger' : 'btn-outline-danger' ?> w-100 py-3 rounded-3 fw-bold wishlist-btn-detail transition"
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
require_once 'includes/footer.php'; 
?>