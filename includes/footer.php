<?php
// includes/footer.php
// Pastikan session sudah berjalan untuk mengecek status login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Cek apakah user sudah login (sesuaikan 'user_id' dengan nama session login Anda)
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<footer class="wn-footer bg-dark text-white pt-5 pb-4 mt-5">
  <div class="container px-3 px-md-4">
    <div class="row g-4">
      
      <div class="col-lg-4 text-center text-lg-start">
        <a class="wn-brand footer-brand text-white text-decoration-none fs-4 fw-bold" href="index.php">
          <i class="bi bi-compass me-2 text-warning"></i>Traveloop
        </a>
        <p class="footer-desc mt-3 text-white-50 small" style="line-height: 1.7;">
          Platform direktori wisata dan penyusun <em>smart itinerary</em> terpadu untuk kawasan <strong>Barlingmascakeb</strong> (Banjarnegara, Purbalingga, Banyumas, Cilacap, dan Kebumen). Misi kami adalah menghubungkan pelancong dengan pesona alam dan budaya lokal secara mudah, cerdas, dan terpercaya.
        </p>
        <div class="footer-socials mt-4 d-flex gap-3 justify-content-center justify-content-lg-start">
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-instagram"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-facebook"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-tiktok"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider small">Tautan Cepat</h6>
        <ul class="list-unstyled footer-links small">
          <li class="mb-2"><a href="index.php" class="text-decoration-none text-white-50 hover-white transition">Beranda</a></li>
          <li class="mb-2"><a href="explore.php" class="text-decoration-none text-white-50 hover-white transition">Eksplorasi Wisata</a></li>
          <li class="mb-2"><a href="about.php" class="text-decoration-none text-white-50 hover-white transition">Tentang Kami</a></li>
          <li class="mb-2"><a href="wishlist.php" class="text-decoration-none text-white-50 hover-white transition">Wishlist Saya</a></li>
          <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-white transition" onclick="return showItineraryModal();">Smart Itinerary</a></li>
        </ul>
      </div>
      
      <div class="col-6 col-lg-3">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider small">Kategori Populer</h6>
        <div class="row g-2">
          <div class="col-12 col-md-6">
            <ul class="list-unstyled footer-links small">
              <li class="mb-2"><a href="explore.php?cat=nature" class="text-decoration-none text-white-50 hover-white transition">Alam & Hutan</a></li>
              <li class="mb-2"><a href="explore.php?cat=beach" class="text-decoration-none text-white-50 hover-white transition">Pantai & Laut</a></li>
              <li class="mb-2"><a href="explore.php?cat=mountain" class="text-decoration-none text-white-50 hover-white transition">Pegunungan</a></li>
            </ul>
          </div>
          <div class="col-12 col-md-6">
            <ul class="list-unstyled footer-links small mt-md-0 mt-n2">
              <li class="mb-2"><a href="explore.php?cat=cultural" class="text-decoration-none text-white-50 hover-white transition">Seni & Budaya</a></li>
              <li class="mb-2"><a href="explore.php?cat=historical" class="text-decoration-none text-white-50 hover-white transition">Situs Sejarah</a></li>
              <li class="mb-2"><a href="explore.php?tag=hidden-gem" class="text-decoration-none text-warning fw-semibold hover-white transition">Hidden Gem</a></li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 text-center text-lg-start mt-4 mt-lg-0">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider small">Kemitraan & Bantuan</h6>
        <p class="footer-desc small text-white-50 mb-3" style="line-height: 1.6;">
          Punya destinasi wisata baru yang ingin didaftarkan? Atau mengalami kendala teknis? Tim kami siap membantu Anda.
        </p>
        <a href="contact.php" class="btn btn-outline-warning btn-sm w-100 fw-bold py-2 mb-3 transition rounded-pill">
          <i class="bi bi-envelope-fill me-2"></i>Hubungi Kemitraan
        </a>
        <p class="text-white-50 small mt-2 mb-0"><i class="bi bi-telephone-fill text-warning me-2"></i>+62 857-1322-8321</p>
      </div>
      
    </div>
    
    <div class="footer-bottom mt-5 pt-4 border-top border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
      <p class="mb-0 text-white-50 small">© <?= date('Y'); ?> Traveloop Barlingmascakeb. Hak Cipta Dilindungi.</p>
    </div>
  </div>
</footer>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable px-2">
    <div class="modal-content wn-modal-content border-0 rounded-4" id="modalBody">
      </div>
  </div>
</div>

<div class="modal fade" id="itineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered px-2">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 bg-light">
        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-magic text-primary me-2"></i>Traveloop Itinerary</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light" id="itineraryModalBody">
        <p class="text-muted small mb-4">Pilih tema liburanmu, dan biarkan sistem merancang perjalanan sempurna 1 hari penuh di sekitar Barlingmascakeb.</p>
        
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 border-bottom border-success border-4 bg-white p-4 text-center rounded-4 shadow-sm transition hover-zoom" style="cursor:pointer" onclick="generateMockItinerary('Alam')">
                    <i class="bi bi-tree-fill display-5 text-success mb-3"></i>
                    <h6 class="fw-bold text-dark mb-1">Jelajah Alam</h6>
                    <small class="text-muted" style="font-size: 0.8rem;">Hutan, Curug & Udara Segar</small>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 border-bottom border-primary border-4 bg-white p-4 text-center rounded-4 shadow-sm transition hover-zoom" style="cursor:pointer" onclick="generateMockItinerary('Pantai')">
                    <i class="bi bi-water display-5 text-primary mb-3"></i>
                    <h6 class="fw-bold text-dark mb-1">Pesisir Biru</h6>
                    <small class="text-muted" style="font-size: 0.8rem;">Pantai, Pasir & Seafood</small>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 border-bottom border-warning border-4 bg-white p-4 text-center rounded-4 shadow-sm transition hover-zoom" style="cursor:pointer" onclick="generateMockItinerary('Sejarah')">
                    <i class="bi bi-bank2 display-5 text-warning mb-3"></i>
                    <h6 class="fw-bold text-dark mb-1">Mesin Waktu</h6>
                    <small class="text-muted" style="font-size: 0.8rem;">Sejarah, Candi & Budaya</small>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Jembatan PHP ke JS untuk status Login -->
<script>
    const isUserLoggedIn = <?= $isLoggedIn ?>;
</script>

<!-- Tambahan Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>