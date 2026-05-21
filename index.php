<?php
// Pastikan file koneksi.php sudah dibuat dan disesuaikan dengan database MySQL
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Traveloop — Jelajahi Wisata Banyumas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
<nav class="navbar navbar-expand-lg wn-navbar fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php"><span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-house me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="bi bi-info-circle me-1"></i>Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="explore.php"><i class="bi bi-compass me-1"></i>Explore</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="bi bi-envelope me-1"></i>Kontak</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero-section" id="hero">
  <div class="hero-overlay"></div>
  <div class="hero-particles" id="heroParticles"></div>
  <div class="container hero-content text-center">
    <p class="hero-eyebrow">✦ Discover Banyumas ✦</p>
    <h1 class="hero-title">Jelajahi<br><em>Wisata Banyumas</em></h1>
    <p class="hero-sub">Dari Gunung Slamet hingga air terjun tersembunyi — petualangan Banyumas menantimu.</p>
    <div class="hero-search-wrap mx-auto">
      <div class="hero-search-box">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="heroSearch" class="form-control hero-input" placeholder="Cari tempat wisata di Banyumas…" autocomplete="off"/>
        <button class="btn btn-search" onclick="doHeroSearch()">Cari</button>
      </div>
    </div>
    <div class="trending-chips mt-4" id="trendingChips">
      <span class="chip-label">Trending:</span>
      <a href="explore.php?q=Baturraden" class="chip">Baturraden</a>
      <a href="explore.php?q=Curug" class="chip">Air Terjun</a>
      <a href="explore.php?q=Gunung+Slamet" class="chip">Gunung Slamet</a>
      <a href="explore.php?q=Purwokerto" class="chip">Purwokerto</a>
    </div>
  </div>
  <div class="hero-scroll-hint"><span>Scroll</span><div class="scroll-line"></div></div>
</section>

<section class="stats-strip">
  <div class="container">
    <div class="row g-0 text-center">
      <div class="col-6 col-md-3 stat-item"><span class="stat-num">16+</span><span class="stat-label">Destinasi</span></div>
      <div class="col-6 col-md-3 stat-item"><span class="stat-num">5</span><span class="stat-label">Kecamatan</span></div>
      <div class="col-6 col-md-3 stat-item"><span class="stat-num">500+</span><span class="stat-label">Ulasan</span></div>
      <div class="col-6 col-md-3 stat-item"><span class="stat-num">10K+</span><span class="stat-label">Wisatawan</span></div>
    </div>
  </div>
</section>

<div id="recommendSection" class="d-none">
  <section class="section-pad" style="padding-top:2.5rem;padding-bottom:2rem">
    <div class="container">
      <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
          <p class="section-eyebrow" id="recommendEyebrow">Berdasarkan Pencarianmu</p>
          <h2 class="section-title mb-0" id="recommendTitle">Rekomendasi untuk Kamu</h2>
        </div>
        <span class="recommend-badge"><i class="bi bi-magic me-1"></i>Personalized</span>
      </div>
      <div class="row g-4 mt-2" id="recommendGrid"></div>
    </div>
  </section>
</div>

<section class="section-pad bg-soft" id="categories">
  <div class="container">
    <div class="section-header text-center">
      <p class="section-eyebrow">Browse by Type</p>
      <h2 class="section-title">Mau Wisata Apa?</h2>
    </div>
    <div class="row g-4 justify-content-center mt-2">
      <div class="col-6 col-md-4 col-lg-2"><a href="explore.php?cat=nature" class="cat-card"><div class="cat-icon-wrap" style="--cat-color:#2d9e6b"><i class="bi bi-tree"></i></div><span class="cat-name">Alam</span></a></div>
      <div class="col-6 col-md-4 col-lg-2"><a href="explore.php?cat=beach" class="cat-card"><div class="cat-icon-wrap" style="--cat-color:#0ea5e9"><i class="bi bi-water"></i></div><span class="cat-name">Pantai</span></a></div>
      <div class="col-6 col-md-4 col-lg-2"><a href="explore.php?cat=mountain" class="cat-card"><div class="cat-icon-wrap" style="--cat-color:#7c5c3b"><i class="bi bi-snow2"></i></div><span class="cat-name">Gunung</span></a></div>
      <div class="col-6 col-md-4 col-lg-2"><a href="explore.php?cat=cultural" class="cat-card"><div class="cat-icon-wrap" style="--cat-color:#e88a22"><i class="bi bi-building-fill"></i></div><span class="cat-name">Budaya</span></a></div>
      <div class="col-6 col-md-4 col-lg-2"><a href="explore.php?cat=historical" class="cat-card"><div class="cat-icon-wrap" style="--cat-color:#9b4f96"><i class="bi bi-bank2"></i></div><span class="cat-name">Sejarah</span></a></div>
    </div>
  </div>
</section>

<section class="section-pad" id="featured">
  <div class="container">
    <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-3">
      <div><p class="section-eyebrow">Pilihan Editor</p><h2 class="section-title mb-0">Destinasi Unggulan</h2></div>
      <a href="explore.php" class="btn btn-outline-brand">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4 mt-2" id="featuredGrid">
      <?php
      // Mengambil 6 data destinasi dari database MySQL
      $query_featured = mysqli_query($koneksi, "SELECT * FROM destinasi LIMIT 6");
      
      if(mysqli_num_rows($query_featured) > 0) {
          while($row = mysqli_fetch_assoc($query_featured)) {
              $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
              ?>
              <div class="col-12 col-md-6 col-xl-4">
                <div class="place-card" onclick="window.location.href='detail.php?id=<?= $row['id'] ?>'">
                  <div class="place-card-img-wrap">
                    <img src="<?= $foto ?>" class="place-card-img" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                    <span class="cat-badge cat-badge-overlay"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['kategori']) ?></span>
                  </div>
                  <div class="place-card-body">
                    <h5 class="place-card-title"><?= htmlspecialchars($row['nama']) ?></h5>
                    <p class="place-card-addr"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                    <div class="place-card-footer">
                      <div class="stars-row"><i class="bi bi-star-fill star-fill"></i> <span class="rating-text ms-1"><?= $row['rating'] ?></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
          }
      } else {
          echo '<div class="col-12 text-center py-5"><p class="text-muted">Belum ada destinasi unggulan tersedia.</p></div>';
      }
      ?>
    </div>
  </div>
</section>

<section class="section-pad" id="hiddenGemSection">
  <div class="container">
    <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-3">
      <div>
        <p class="section-eyebrow"><i class="bi bi-gem me-1"></i>Tersembunyi & Ekslusif</p>
        <h2 class="section-title mb-0">Hidden Gem Banyumas</h2>
        <p class="text-muted mt-1 mb-0" style="font-size:.9rem">Tempat-tempat yang belum banyak diketahui — dicurasi langsung oleh tim kami.</p>
      </div>
      <a href="explore.php?cat=nature&tag=hidden-gem" class="btn btn-outline-brand">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4 mt-2" id="hiddenGemGrid">
      <?php
      // Mengambil data dengan kondisi tag tertentu (sesuaikan dengan nama kolom di tabel Anda)
      $query_hidden = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE tag = 'hidden-gem' LIMIT 3");
      
      if($query_hidden && mysqli_num_rows($query_hidden) > 0) {
          while($row = mysqli_fetch_assoc($query_hidden)) {
              $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Hidden+Gem';
              ?>
              <div class="col-12 col-md-6 col-xl-4">
                <div class="place-card" onclick="window.location.href='detail.php?id=<?= $row['id'] ?>'">
                  <div class="place-card-img-wrap">
                    <img src="<?= $foto ?>" class="place-card-img" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                    <span class="cat-badge cat-badge-overlay"><i class="bi bi-gem me-1"></i>Hidden Gem</span>
                  </div>
                  <div class="place-card-body">
                    <h5 class="place-card-title"><?= htmlspecialchars($row['nama']) ?></h5>
                    <p class="place-card-addr"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                  </div>
                </div>
              </div>
              <?php
          }
      } else {
          echo '<div class="col-12 text-center py-4"><p class="text-muted">Data Hidden Gem belum tersedia.</p></div>';
      }
      ?>
    </div>
  </div>
</section>

<section class="section-pad bg-soft" id="itinerarySection">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5">
        <p class="section-eyebrow"><i class="bi bi-calendar-check me-1"></i>Planner Wisata</p>
        <h2 class="section-title">Cuma Ada Waktu 1 Hari?</h2>
        <p class="about-text mt-2">Biarkan sistem kami merekomendasikan itinerary terbaik untukmu — lengkap dengan estimasi waktu, biaya, dan rekomendasi kuliner khas Banyumas.</p>
        <div class="itin-feature-list mt-4">
          <div class="itin-feature-item"><i class="bi bi-check-circle-fill"></i><span>4 pilihan paket wisata</span></div>
          <div class="itin-feature-item"><i class="bi bi-check-circle-fill"></i><span>Estimasi HTM & biaya total</span></div>
          <div class="itin-feature-item"><i class="bi bi-check-circle-fill"></i><span>Rekomendasi kuliner khas</span></div>
          <div class="itin-feature-item"><i class="bi bi-check-circle-fill"></i><span>Jadwal dari pagi hingga sore</span></div>
        </div>
        <button class="btn btn-cta-itin mt-4" onclick="openItineraryModal()">
          <i class="bi bi-magic me-2"></i>Buat Itinerary Sekarang
        </button>
      </div>
      <div class="col-lg-7">
        <div class="itin-preview-cards">
          <div class="itin-preview-chip" style="--ic:0"><i class="bi bi-tree"></i>Alam & Air Terjun</div>
          <div class="itin-preview-chip" style="--ic:1"><i class="bi bi-wallet2"></i>Paket Hemat</div>
          <div class="itin-preview-chip" style="--ic:2"><i class="bi bi-bank2"></i>Wisata Sejarah</div>
          <div class="itin-preview-chip" style="--ic:3"><i class="bi bi-people"></i>Wisata Keluarga</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-banner">
  <div class="container text-center">
    <h2 class="cta-title">Siap Mulai Petualangan?</h2>
    <p class="cta-sub">Temukan tempat wisata terbaik di Kabupaten Banyumas dan sekitarnya.</p>
    <a href="explore.php" class="btn btn-cta me-2">Explore Sekarang <i class="bi bi-compass ms-1"></i></a>
    <a href="contact.php" class="btn btn-cta-outline">Hubungi Kami <i class="bi bi-envelope ms-1"></i></a>
  </div>
</section>

<footer class="wn-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <a class="wn-brand footer-brand" href="index.php"><i class="bi bi-compass me-2"></i>Traveloop</a>
        <p class="footer-desc mt-3">Panduan lengkap menjelajahi destinasi wisata terbaik di Banyumas dan sekitarnya.</p>
        <div class="footer-socials mt-3">
          <a href="#" class="footer-social-btn"><i class="bi bi-instagram"></i></a>
          <a href="#" class="footer-social-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="footer-social-btn"><i class="bi bi-tiktok"></i></a>
          <a href="#" class="footer-social-btn"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Halaman</h6>
        <ul class="footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">Tentang Kami</a></li>
          <li><a href="explore.php">Explore</a></li>
          <li><a href="contact.php">Kontak</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Kategori</h6>
        <ul class="footer-links">
          <li><a href="explore.php?cat=nature">Alam</a></li>
          <li><a href="explore.php?cat=beach">Pantai</a></li>
          <li><a href="explore.php?cat=mountain">Gunung</a></li>
          <li><a href="explore.php?cat=historical">Sejarah</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h6 class="footer-heading">Newsletter</h6>
        <p class="footer-desc small">Dapatkan info wisata & tips terbaru di Banyumas.</p>
        <div class="footer-newsletter">
          <input type="email" class="form-control" placeholder="email@kamu.com"/>
          <button class="btn btn-brand-sm">Langganan</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom mt-5 pt-4">
      <p class="mb-0">© 2025 Traveloop — Wisata Banyumas. Built with Bootstrap 5 & Google Places API.</p>
    </div>
  </div>
</footer>

<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content wn-modal-content">
      <button type="button" class="btn-close wn-modal-close" data-bs-dismiss="modal"></button>
      <div class="modal-body p-0" id="modalBody"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="itineraryModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content wn-modal-content">
      <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border)">
        <h5 class="modal-title" style="font-family:var(--ff-display);font-weight:700">
          <i class="bi bi-calendar-check me-2 text-warning"></i>Itinerary 1 Hari di Banyumas
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p class="text-muted mb-4">Pilih paket wisata sesuai minat dan kondisimu hari ini:</p>
        <div class="row g-3" id="itineraryOptions"></div>
        <div id="itineraryResult" class="d-none mt-4"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="config.js"></script>
<script src="script.js"></script>
<script>
  document.getElementById('heroSearch').addEventListener('keydown', e => {
    if (e.key === 'Enter') doHeroSearch();
  });
  
  function doHeroSearch() {
    const q = document.getElementById('heroSearch').value.trim();
    if (q) { 
        // Arahkan ke explore.php bukan .html
        window.location.href = `explore.php?q=${encodeURIComponent(q)}`; 
    }
  }
  
  window.addEventListener('DOMContentLoaded', () => {
    initNavScroll(); 
    generateParticles(); 
    // loadFeatured() dan loadHiddenGems() sudah DIBUANG karena datanya dirender oleh PHP
    // loadPersonalRecommendations(); dan loadSmartTrending(); bisa dipertahankan jika masih berbasis JS
    renderItineraryOptions();
  });
</script>
</body>
</html>