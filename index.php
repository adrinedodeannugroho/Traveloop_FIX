<?php
// index.php
// Memanggil bagian atas (Header & Navbar)
include 'includes/header.php';
?>

<!-- Perbaikan Background: Tambah no-repeat dan pastikan posisinya terpusat di berbagai layar -->
<section class="hero-section position-relative" id="hero" style="background-image: url('assets/image/Menu_Utama.png'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
  <div class="hero-overlay"></div>
  <div class="hero-particles" id="heroParticles"></div>
  
  <!-- Penyesuaian Padding untuk Layar HP (px-3) dan Desktop -->
  <div class="container hero-content text-center px-3" style="padding-top: 100px; padding-bottom: 60px;">
    <p class="hero-eyebrow text-warning fw-bold tracking-wider mb-3">✦ DISCOVER CENTRAL JAVA ✦</p>
    
    <!-- Judul dibuat responsif agar tidak tumpang tindih di HP -->
    <h1 class="hero-title display-4 display-md-3 fw-bold text-white mb-4">
      Jelajahi<br><em class="text-warning" style="font-family: var(--ff-display);">Keajaiban Lokal</em>
    </h1>
    
    <p class="hero-sub fs-6 fs-md-5 text-light opacity-75 mb-5 mx-auto" style="max-width: 700px;">
      Dari pesona sejuk Gunung Slamet hingga deburan ombak pesisir selatan. Temukan hidden gem terbaik melintasi wilayah <strong>Banjarnegara, Purbalingga, Banyumas, Cilacap, dan Kebumen</strong>.
    </p>
    
    <div class="hero-search-wrap mx-auto bg-white p-2 rounded-pill shadow-lg" style="max-width: 600px;">
      <div class="d-flex align-items-center">
        <i class="bi bi-search text-muted fs-5 ms-3"></i>
        <!-- Input field responsif -->
        <input type="text" id="heroSearch" class="form-control border-0 shadow-none bg-transparent py-2 py-md-3 px-3 w-100" placeholder="Ketik nama tempat atau kota..." autocomplete="off"/>
        <button class="btn btn-warning rounded-pill px-3 px-md-4 py-2 fw-bold text-nowrap" onclick="doHeroSearch()">Eksplorasi</button>
      </div>
    </div>

    <div class="trending-chips mt-4 d-flex justify-content-center align-items-center gap-2 flex-wrap" id="trendingChips">
      <span class="chip-label text-white-50 small fw-bold text-uppercase"><i class="bi bi-fire text-danger me-1"></i> Populer:</span>
      <a href="explore.php?q=Baturraden" class="badge rounded-pill bg-white text-dark text-decoration-none px-3 py-2 bg-opacity-25 hover-white transition">Baturraden</a>
      <a href="explore.php?q=Curug" class="badge rounded-pill bg-white text-dark text-decoration-none px-3 py-2 bg-opacity-25 hover-white transition">Curug & Alam</a>
      <a href="explore.php?cat=beach" class="badge rounded-pill bg-white text-dark text-decoration-none px-3 py-2 bg-opacity-25 hover-white transition">Pantai Selatan</a>
      <a href="explore.php?tag=hidden-gem" class="badge rounded-pill bg-warning text-dark text-decoration-none px-3 py-2 transition"><i class="bi bi-gem me-1"></i>Hidden Gem</a>
    </div>
  </div>
  
  <div class="hero-scroll-hint opacity-50 d-none d-md-flex">
      <span class="small text-uppercase tracking-wider">Scroll</span>
      <div class="scroll-line mt-2"></div>
  </div>
</section>

<section class="stats-strip bg-dark text-white py-4 py-md-5 border-top border-warning border-4">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3">
          <div class="p-2 p-md-3">
              <i class="bi bi-geo-alt-fill display-6 display-md-5 text-warning mb-2 mb-md-3 d-block opacity-75"></i>
              <span class="stat-num fs-2 fs-md-1 fw-bold mb-1">50+</span>
              <span class="stat-label text-uppercase small tracking-wider text-white-50">Destinasi</span>
          </div>
      </div>
      <div class="col-6 col-md-3">
          <div class="p-2 p-md-3 border-start border-secondary">
              <i class="bi bi-map-fill display-6 display-md-5 text-warning mb-2 mb-md-3 d-block opacity-75"></i>
              <span class="stat-num fs-2 fs-md-1 fw-bold mb-1">5</span>
              <span class="stat-label text-uppercase small tracking-wider text-white-50">Kabupaten</span>
          </div>
      </div>
      <div class="col-6 col-md-3">
          <div class="p-2 p-md-3 border-start-0 border-start-md border-secondary">
              <i class="bi bi-star-fill display-6 display-md-5 text-warning mb-2 mb-md-3 d-block opacity-75"></i>
              <span class="stat-num fs-2 fs-md-1 fw-bold mb-1">1K+</span>
              <span class="stat-label text-uppercase small tracking-wider text-white-50">Ulasan Positif</span>
          </div>
      </div>
      <div class="col-6 col-md-3">
          <div class="p-2 p-md-3 border-start border-secondary">
              <i class="bi bi-people-fill display-6 display-md-5 text-warning mb-2 mb-md-3 d-block opacity-75"></i>
              <span class="stat-num fs-2 fs-md-1 fw-bold mb-1">10K+</span>
              <span class="stat-label text-uppercase small tracking-wider text-white-50">Wisatawan Aktif</span>
          </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-soft" id="categories">
  <div class="container">
    <div class="section-header text-center mb-5 px-3">
      <p class="section-eyebrow text-warning fw-bold tracking-wider">Browse by Type</p>
      <h2 class="section-title fw-bold">Pilih Suasana Liburanmu</h2>
      <p class="text-muted mt-2">Temukan tempat yang paling cocok dengan mood kamu hari ini.</p>
    </div>
    
    <div class="row g-3 g-md-4 justify-content-center">
      <div class="col-6 col-md-4 col-lg-2">
          <a href="explore.php?cat=nature" class="cat-card bg-white shadow-sm border-0 rounded-4 p-3 p-md-4 text-center d-block text-decoration-none transition hover-zoom">
              <div class="cat-icon-wrap mx-auto mb-3" style="--cat-color:#2d9e6b; width: 60px; height: 60px; border-radius: 16px;"><i class="bi bi-tree fs-3"></i></div>
              <span class="cat-name fw-bold text-dark small d-block">Alam Segar</span>
          </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
          <a href="explore.php?cat=beach" class="cat-card bg-white shadow-sm border-0 rounded-4 p-3 p-md-4 text-center d-block text-decoration-none transition hover-zoom">
              <div class="cat-icon-wrap mx-auto mb-3" style="--cat-color:#0ea5e9; width: 60px; height: 60px; border-radius: 16px;"><i class="bi bi-water fs-3"></i></div>
              <span class="cat-name fw-bold text-dark small d-block">Pantai Biru</span>
          </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
          <a href="explore.php?cat=mountain" class="cat-card bg-white shadow-sm border-0 rounded-4 p-3 p-md-4 text-center d-block text-decoration-none transition hover-zoom">
              <div class="cat-icon-wrap mx-auto mb-3" style="--cat-color:#7c5c3b; width: 60px; height: 60px; border-radius: 16px;"><i class="bi bi-snow2 fs-3"></i></div>
              <span class="cat-name fw-bold text-dark small d-block">Pegunungan</span>
          </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
          <a href="explore.php?cat=cultural" class="cat-card bg-white shadow-sm border-0 rounded-4 p-3 p-md-4 text-center d-block text-decoration-none transition hover-zoom">
              <div class="cat-icon-wrap mx-auto mb-3" style="--cat-color:#e88a22; width: 60px; height: 60px; border-radius: 16px;"><i class="bi bi-building-fill fs-3"></i></div>
              <span class="cat-name fw-bold text-dark small d-block">Seni & Budaya</span>
          </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
          <a href="explore.php?cat=historical" class="cat-card bg-white shadow-sm border-0 rounded-4 p-3 p-md-4 text-center d-block text-decoration-none transition hover-zoom">
              <div class="cat-icon-wrap mx-auto mb-3" style="--cat-color:#9b4f96; width: 60px; height: 60px; border-radius: 16px;"><i class="bi bi-bank2 fs-3"></i></div>
              <span class="cat-name fw-bold text-dark small d-block">Situs Sejarah</span>
          </a>
      </div>
    </div>
  </div>
</section>

<section class="section-pad py-5 my-4" id="featured">
  <div class="container">
    <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 px-2">
      <div>
          <p class="section-eyebrow text-warning fw-bold tracking-wider mb-1">Pilihan Editor</p>
          <h2 class="section-title fw-bold mb-0">Destinasi Paling Banyak Disukai</h2>
      </div>
      <a href="explore.php" class="btn btn-outline-primary fw-bold rounded-pill px-4">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    
    <div class="row g-4" id="featuredGrid">
      <?php
      // Eksekusi query untuk mengambil 6 tempat dengan rating tertinggi (jika ada) atau terbaru
      $query_featured = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY rating DESC, id DESC LIMIT 6");
      
      if($query_featured && mysqli_num_rows($query_featured) > 0) {
          while($row = mysqli_fetch_assoc($query_featured)) {
              $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
              $isGem = (stripos($row['deskripsi'], 'hidden gem') !== false);
              ?>
              <div class="col-12 col-md-6 col-xl-4">
                <div class="place-card h-100 shadow-sm border-0" 
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
                     onclick="openDetailBtn(this)" 
                     style="cursor:pointer;">
                     
                  <div class="place-card-img-wrap rounded-top-4 position-relative">
                    <img src="<?= $foto ?>" class="place-card-img w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                    <span class="cat-badge cat-badge-overlay position-absolute top-0 start-0 m-3 shadow-sm bg-white text-dark px-3 py-1 rounded-pill fw-bold small"><i class="bi bi-geo-alt-fill text-warning me-1"></i><?= htmlspecialchars($row['kategori']) ?></span>
                    <?php if($isGem): ?>
                        <span class="gem-badge position-absolute bottom-0 start-0 m-3 shadow-sm bg-primary text-white px-3 py-1 rounded-pill fw-bold small"><i class="bi bi-gem me-1"></i>Hidden Gem</span>
                    <?php endif; ?>
                  </div>
                  
                  <div class="place-card-body p-3 p-md-4 bg-white rounded-bottom-4">
                    <h5 class="place-card-title fw-bold text-dark mb-1"><?= htmlspecialchars($row['nama']) ?></h5>
                    <p class="place-card-addr text-muted small"><i class="bi bi-pin-map-fill me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                    <div class="place-card-footer mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                      <div class="stars-row">
                          <i class="bi bi-star-fill star-fill text-warning"></i> 
                          <span class="rating-text ms-1 fw-bold"><?= $row['rating'] ?></span>
                      </div>
                      <span class="text-primary small fw-bold">Detail <i class="bi bi-arrow-right"></i></span>
                    </div>
                  </div>
                  
                </div>
              </div>
              <?php
          }
      } else {
          // Tampilan jika data kosong
          $error_msg = mysqli_error($koneksi) ? mysqli_error($koneksi) : "Belum ada data destinasi wisata yang ditambahkan ke database.";
          echo '<div class="col-12">
                  <div class="alert alert-light border shadow-sm text-center py-5 rounded-4" role="alert">
                    <i class="bi bi-compass display-4 d-block mb-3 text-muted opacity-50"></i>
                    <h5 class="fw-bold text-dark">Eksplorasi Masih Kosong</h5>
                    <span class="text-muted">' . $error_msg . '</span>
                  </div>
                </div>';
      }
      ?>
    </div>
  </div>
</section>

<section class="section-pad bg-white border-top py-5">
  <div class="container px-4 px-md-3">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 text-center text-lg-start">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3"><i class="bi bi-stars me-1"></i> Fitur Andalan</span>
        <h2 class="fw-bold fs-1 mb-4">Bingung Mau Kemana Saja Hari Ini?</h2>
        <p class="text-muted lead mb-4">Gunakan fitur <strong class="text-dark">Smart Itinerary Traveloop</strong>. Cukup pilih tema liburanmu (Alam, Pantai, atau Budaya) dan biarkan sistem merancang rute perjalanan 1 hari penuh secara otomatis!</p>
        <ul class="list-unstyled mb-4 text-start d-inline-block d-lg-block">
            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Kombinasi tempat wisata dan spot kuliner terdekat.</li>
            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Estimasi waktu kunjungan yang terstruktur.</li>
            <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Perhitungan rute cerdas agar tidak membuang waktu di jalan.</li>
        </ul>
        <div class="d-grid d-md-block">
          <button class="btn btn-warning btn-lg fw-bold px-5 rounded-pill shadow-sm" onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('itineraryModal')).show()">
              Coba Buat Itinerary <i class="bi bi-magic ms-2"></i>
          </button>
        </div>
      </div>
      <div class="col-lg-6 text-center mt-5 mt-lg-0">
          <div class="bg-light p-4 rounded-4 shadow-sm border position-relative" style="transform: rotate(2deg);">
              <div class="d-flex align-items-center mb-3 pb-3 border-bottom text-start">
                  <i class="bi bi-calendar-check fs-2 text-primary me-3"></i>
                  <div>
                      <h5 class="fw-bold mb-0">Paket Liburan Alam</h5>
                      <small class="text-muted">Estimasi: 8 Jam • 3 Destinasi</small>
                  </div>
              </div>
              <div class="text-start ps-3 border-start border-warning border-3 mb-3">
                  <p class="small fw-bold text-muted mb-1">08:00 WIB</p>
                  <h6 class="fw-bold">Pagi di Kebun Raya Baturraden</h6>
              </div>
              <div class="text-start ps-3 border-start border-danger border-3 mb-3">
                  <p class="small fw-bold text-muted mb-1">12:30 WIB</p>
                  <h6 class="fw-bold">Makan Siang Sroto Sokaraja</h6>
              </div>
              <div class="text-start ps-3 border-start border-primary border-3">
                  <p class="small fw-bold text-muted mb-1">15:00 WIB</p>
                  <h6 class="fw-bold">Sore di Curug Jenggala</h6>
              </div>
          </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-banner my-5 mx-3 mx-lg-5 shadow-lg position-relative overflow-hidden" style="border-radius: 2rem; background: linear-gradient(135deg, #1e293b, #0f172a); padding: 4rem 1.5rem;">
  <div class="position-absolute top-0 end-0 opacity-10 p-3 p-md-5">
      <i class="bi bi-compass" style="font-size: 15rem;"></i>
  </div>
  <div class="container text-center position-relative z-1">
    <h2 class="cta-title text-white mb-3 fw-bold fs-2 fs-md-1">Siap Mengembangkan Ekosistem Wisata?</h2>
    <p class="cta-sub text-light mb-4 mx-auto" style="max-width: 600px; font-size: 1rem;">Bergabunglah sebagai mitra pengelola. Tingkatkan eksposur destinasi atau layanan kuliner Anda ke ribuan wisatawan di Barlingmascakeb.</p>
    <a href="contact.php" class="btn btn-warning btn-lg fw-bold px-4 px-md-5 rounded-pill shadow-sm">
        Gabung Kemitraan Sekarang <i class="bi bi-briefcase ms-2"></i>
    </a>
  </div>
</section>

<script>
  // Fitur Pencarian di Hero Section (Diarahkan ke Explore.php)
  document.getElementById('heroSearch')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') doHeroSearch();
  });
  
  function doHeroSearch() {
    const q = document.getElementById('heroSearch').value.trim();
    if (q) { 
        window.location.href = `explore.php?q=${encodeURIComponent(q)}`; 
    } else {
        // Jika kosong, beri animasi kecil
        document.getElementById('heroSearch').focus();
    }
  }
  
  // Inisialisasi Fungsi Eksternal saat halaman dimuat
  window.addEventListener('DOMContentLoaded', () => {
    if(typeof initNavScroll === 'function') initNavScroll(); 
    if(typeof generateParticles === 'function') generateParticles(); 
  });
</script>

<?php
// Memanggil bagian bawah (Footer & Scripts)
// (Catatan: Modal Pop-up sudah terintegrasi di dalam file footer.php sehingga aman dipanggil di sini)
include 'includes/footer.php';
?>