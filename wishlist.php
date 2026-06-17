<?php
// wishlist.php
require_once 'includes/header.php';

// Redirect jika belum login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo "<script>window.location.href='auth/login.php?redirect=../wishlist.php';</script>";
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Query wishlist dengan JOIN ke tabel destinasi
$query_wishlist = mysqli_query($koneksi, "
    SELECT d.*, w.created_at as wishlisted_at 
    FROM wishlist w 
    JOIN destinasi d ON w.destinasi_id = d.id 
    WHERE w.user_id = $user_id 
    ORDER BY w.created_at DESC
");
$total_wishlist = $query_wishlist ? mysqli_num_rows($query_wishlist) : 0;
?>

<div class="page-header-solid" style="padding-top: 120px;">
  <div class="container text-center">
    <h1 class="page-title text-white"><i class="bi bi-heart-fill text-danger me-2"></i>Wishlist Saya</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center bg-transparent mb-0">
        <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item text-white active" aria-current="page">Wishlist</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad py-5">
  <div class="container">
    
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div>
        <h4 class="fw-bold mb-1">
          <i class="bi bi-bookmark-heart text-danger me-2"></i>Destinasi Impian
        </h4>
        <p class="text-muted small mb-0">
          Kamu memiliki <strong class="text-dark"><?= $total_wishlist ?></strong> destinasi di wishlist.
          <?php if ($total_wishlist > 0): ?>
            Ayo wujudkan perjalananmu! 🚀
          <?php endif; ?>
        </p>
      </div>
      <?php if ($total_wishlist > 0): ?>
        <a href="explore.php" class="btn btn-outline-primary fw-bold rounded-pill px-4">
          <i class="bi bi-plus-circle me-1"></i>Tambah Lagi
        </a>
      <?php endif; ?>
    </div>

    <?php if ($total_wishlist > 0): ?>
      <div class="row g-4" id="wishlistGrid">
        <?php while ($row = mysqli_fetch_assoc($query_wishlist)):
          $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
          $isGem = (stripos($row['deskripsi'], 'hidden gem') !== false);
          $wishlisted_date = date('d M Y', strtotime($row['wishlisted_at']));
        ?>
          <div class="col-12 col-md-6 col-xl-4" id="wishlist-card-<?= $row['id'] ?>">
            <div class="place-card h-100 shadow-sm border-0 position-relative"
                 data-id="<?= $row['id'] ?>"
                 data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>"
                 data-kategori="<?= htmlspecialchars($row['kategori'], ENT_QUOTES) ?>"
                 data-alamat="<?= htmlspecialchars($row['alamat'], ENT_QUOTES) ?>"
                 data-rating="<?= $row['rating'] ?>"
                 data-deskripsi="<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES) ?>"
                 data-foto="<?= htmlspecialchars($foto, ENT_QUOTES) ?>"
                 data-maps="<?= htmlspecialchars($row['maps_url'] ?? '', ENT_QUOTES) ?>"
                 data-tarif="<?= htmlspecialchars($row['tarif'] ?? '', ENT_QUOTES) ?>"
                 data-history="<?= htmlspecialchars($row['history'] ?? '', ENT_QUOTES) ?>"
                 data-tips="<?= htmlspecialchars($row['tips'] ?? '', ENT_QUOTES) ?>"
                 data-saved="true" 
                 onclick="openDetailBtn(this)" 
                 style="cursor:pointer;">
                 
              <div class="place-card-img-wrap rounded-top-4 position-relative">
                <img src="<?= $foto ?>" class="place-card-img w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                <span class="cat-badge cat-badge-overlay position-absolute top-0 start-0 m-3 shadow-sm bg-white text-dark px-3 py-1 rounded-pill fw-bold small">
                  <i class="bi bi-geo-alt-fill text-warning me-1"></i><?= htmlspecialchars($row['kategori']) ?>
                </span>
                <?php if ($isGem): ?>
                  <span class="gem-badge position-absolute bottom-0 start-0 m-3 shadow-sm bg-primary text-white px-3 py-1 rounded-pill fw-bold small">
                    <i class="bi bi-gem me-1"></i>Hidden Gem
                  </span>
                <?php endif; ?>
                
                <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-3 rounded-circle shadow wishlist-remove-btn"
                        onclick="event.stopPropagation(); removeFromWishlist(<?= $row['id'] ?>)"
                        title="Hapus dari Wishlist"
                        style="width: 38px; height: 38px;">
                  <i class="bi bi-heart-fill"></i>
                </button>
              </div>
              
              <div class="place-card-body p-4 bg-white rounded-bottom-4">
                <h5 class="place-card-title fw-bold text-dark mb-1"><?= htmlspecialchars($row['nama']) ?></h5>
                <p class="place-card-addr text-muted small"><i class="bi bi-pin-map-fill me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                <div class="place-card-footer mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                  <div class="stars-row">
                    <i class="bi bi-star-fill star-fill text-warning"></i> 
                    <span class="rating-text ms-1 fw-bold"><?= $row['rating'] ?></span>
                  </div>
                  <span class="text-muted small"><i class="bi bi-clock me-1"></i>Ditambahkan <?= $wishlisted_date ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      
    <?php else: ?>
      <div class="text-center py-5">
        <div class="wishlist-empty-state mx-auto">
          <i class="bi bi-heart display-1 text-muted opacity-25 d-block mb-4"></i>
          <h4 class="fw-bold text-dark mb-2">Wishlist Masih Kosong</h4>
          <p class="text-muted mb-4" style="max-width: 420px; margin: 0 auto;">
            Belum ada destinasi yang kamu simpan. Yuk mulai jelajahi dan temukan tempat wisata impianmu!
          </p>
          <a href="explore.php" class="btn btn-warning fw-bold rounded-pill px-5 py-2 shadow-sm">
            <i class="bi bi-compass me-2"></i>Mulai Eksplorasi
          </a>
        </div>
      </div>
    <?php endif; ?>
    
  </div>
</section>

<script>
// Hapus destinasi dari wishlist (di halaman wishlist)
function removeFromWishlist(destinasiId) {
  // Diselaraskan dengan proses_wishlist.php yang menggunakan JSON
  fetch('proses_wishlist.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ destinasi_id: destinasiId })
  })
  .then(r => r.json())
  .then(data => {
    // Mengecek respon JSON dari proses_wishlist.php
    if (data.status === 'removed') {
      const card = document.getElementById('wishlist-card-' + destinasiId);
      if (card) {
        // Animasi pop-out sebelum dihapus
        card.style.transition = 'all 0.4s ease';
        card.style.transform = 'scale(0.8)';
        card.style.opacity = '0';
        
        setTimeout(() => {
          card.remove();
          // Cek apakah sisa grid kosong
          const remaining = document.querySelectorAll('#wishlistGrid .col-12').length;
          if (remaining === 0) {
            location.reload(); // Refresh untuk memunculkan "Empty State"
          }
        }, 400);
      }
    } else {
      alert(data.message || 'Gagal menghapus dari wishlist.');
    }
  })
  .catch(err => console.error('Wishlist error:', err));
}
</script>

<?php 
require_once 'includes/footer.php'; 
?>