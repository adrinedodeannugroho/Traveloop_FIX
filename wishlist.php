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

<div class="page-header-solid bg-dark position-relative" style="padding-top: 100px; padding-bottom: 40px;">
  <div class="container text-center px-3 position-relative z-1">
    <h1 class="page-title text-white fw-bold mb-2"><i class="bi bi-heart-fill text-danger me-2"></i>Wishlist Saya</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center bg-transparent mb-0">
        <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none small">Beranda</a></li>
        <li class="breadcrumb-item text-white active small" aria-current="page">Wishlist</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad py-4 py-md-5 bg-light min-vh-100">
  <div class="container px-3 px-md-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
      <div>
        <h4 class="fw-bold mb-1 text-dark">
          <i class="bi bi-bookmark-check-fill text-success me-2"></i>Destinasi Tersimpan
        </h4>
        <p class="text-muted small mb-0">
          Kamu memiliki <strong class="text-dark fs-6"><?= $total_wishlist ?></strong> destinasi di wishlist.
        </p>
      </div>
      <?php if ($total_wishlist > 0): ?>
        <a href="explore.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm w-100 w-md-auto">
          <i class="bi bi-search me-1"></i>Cari Destinasi Lain
        </a>
      <?php endif; ?>
    </div>

    <?php if ($total_wishlist > 0): ?>
      <div class="row g-4" id="wishlistGrid">
        <?php while ($row = mysqli_fetch_assoc($query_wishlist)):
          $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
          $deskripsi_aman = $row['deskripsi'] ?? 'Belum ada deskripsi.';
          $isGem = (stripos($deskripsi_aman, 'hidden gem') !== false);
          $wishlisted_date = date('d M Y', strtotime($row['wishlisted_at']));
        ?>
          <div class="col-12 col-md-6 col-lg-4" id="wishlist-card-<?= $row['id'] ?>">
            <div class="place-card h-100 bg-white shadow-sm border-0 rounded-4 position-relative overflow-hidden transition hover-zoom"
                 data-id="<?= $row['id'] ?>"
                 data-nama="<?= htmlspecialchars($row['nama']) ?>"
                 data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                 data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                 data-deskripsi="<?= htmlspecialchars($deskripsi_aman) ?>"
                 data-foto="<?= htmlspecialchars($foto) ?>"
                 data-rating="<?= $row['rating'] ?>"
                 data-maps="<?= htmlspecialchars($row['maps_url'] ?? '') ?>"
                 data-tarif="<?= htmlspecialchars($row['tarif'] ?? '') ?>"
                 data-history="<?= htmlspecialchars($row['history'] ?? '') ?>"
                 data-tips="<?= htmlspecialchars($row['tips'] ?? '') ?>"
                 data-kontak="<?= htmlspecialchars($row['kontak'] ?? '') ?>"
                 data-saved="true"
                 onclick="openDetailBtn(this)" 
                 style="cursor:pointer;">
                 
              <div class="place-card-img-wrap position-relative" style="height: 220px;">
                <img src="<?= $foto ?>" class="w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 40%);"></div>

                <span class="position-absolute top-0 start-0 m-3 shadow-sm bg-white text-dark px-3 py-1 rounded-pill fw-bold small" style="font-size: 0.75rem;">
                  <i class="bi bi-geo-alt-fill text-warning me-1"></i><?= htmlspecialchars($row['kategori']) ?>
                </span>
                
                <?php if ($isGem): ?>
                  <span class="position-absolute bottom-0 start-0 m-3 shadow-sm bg-primary text-white px-3 py-1 rounded-pill fw-bold small" style="font-size: 0.75rem;">
                    <i class="bi bi-gem me-1"></i>Hidden Gem
                  </span>
                <?php endif; ?>
                
                <button class="btn btn-danger position-absolute top-0 end-0 m-3 rounded-circle shadow-sm d-flex align-items-center justify-content-center z-3"
                        onclick="removeFromWishlist(<?= $row['id'] ?>, event)"
                        title="Hapus dari Wishlist"
                        style="width: 40px; height: 40px; opacity: 0.9;">
                  <i class="bi bi-trash3-fill"></i>
                </button>
              </div>
              
              <div class="p-3 p-md-4">
                <h5 class="fw-bold text-dark mb-1 text-truncate"><?= htmlspecialchars($row['nama']) ?></h5>
                <p class="text-muted small mb-3 text-truncate"><i class="bi bi-pin-map-fill me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <i class="bi bi-star-fill text-warning" style="font-size: 0.9rem;"></i> 
                    <span class="ms-1 fw-bold small"><?= $row['rating'] ?></span>
                  </div>
                  <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i><?= $wishlisted_date ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      
    <?php else: ?>
      <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm">
        <div class="wishlist-empty-state mx-auto px-3">
          <i class="bi bi-heart-break display-1 text-danger opacity-50 d-block mb-3"></i>
          <h4 class="fw-bold text-dark mb-2">Wishlist Kamu Masih Kosong</h4>
          <p class="text-muted mb-4 small" style="max-width: 400px; margin: 0 auto;">
            Belum ada destinasi yang kamu simpan. Yuk mulai jelajahi dan temukan tempat wisata impianmu untuk liburan akhir pekan ini!
          </p>
          <a href="explore.php" class="btn btn-warning fw-bold rounded-pill px-4 py-2 shadow-sm">
            <i class="bi bi-compass me-2"></i>Mulai Eksplorasi Sekarang
          </a>
        </div>
      </div>
    <?php endif; ?>
    
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Hapus Destinasi via AJAX + Konfirmasi SweetAlert2 Profesional
function removeFromWishlist(destinasiId, event) {
  // Mencegah div place-card (modal) terpicu saat tombol hapus diklik
  event.stopPropagation(); 

  Swal.fire({
      title: 'Hapus Destinasi?',
      text: "Destinasi ini akan dihapus permanen dari wishlist Anda.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      customClass: {
          popup: 'rounded-4'
      }
  }).then((result) => {
      if (result.isConfirmed) {
          
          // Lakukan request ke server
          fetch('api/wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggle', destinasi_id: destinasiId })
          })
          .then(r => r.json())
          .then(data => {
            if (data.status === 'removed') {
              
              // Notifikasi Sukses
              Swal.fire({
                  icon: 'success',
                  title: 'Terhapus!',
                  text: 'Destinasi telah dikeluarkan dari wishlist.',
                  timer: 1500,
                  showConfirmButton: false
              });

              // Efek menghilang pada kartu UI
              const card = document.getElementById('wishlist-card-' + destinasiId);
              if (card) {
                card.style.transition = 'all 0.3s ease-out';
                card.style.transform = 'scale(0.9)';
                card.style.opacity = '0';
                
                setTimeout(() => {
                  card.remove();
                  
                  // Cek apakah list menjadi kosong, jika ya refresh halaman
                  const remaining = document.querySelectorAll('#wishlistGrid .col-12').length;
                  if (remaining === 0) {
                    location.reload(); 
                  }
                }, 300);
              }
            } else {
              Swal.fire('Gagal', data.message || 'Terjadi kesalahan sistem.', 'error');
            }
          })
          .catch(err => {
              console.error('Wishlist error:', err);
              Swal.fire('Error', 'Gagal menghubungi server.', 'error');
          });

      }
  });
}
</script>

<?php 
// Panggil footer (Gunakan path yang sesuai dengan struktur Anda)
require_once 'includes/footer.php'; 
?>