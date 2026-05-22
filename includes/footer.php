<?php
// includes/footer.php
?>
<footer class="wn-footer bg-dark text-white pt-5 pb-4 mt-5">
  <div class="container">
    <div class="row g-4">
      
      <div class="col-lg-4">
        <a class="wn-brand footer-brand text-white text-decoration-none fs-4 fw-bold" href="index.php">
          <i class="bi bi-compass me-2 text-warning"></i>Traveloop
        </a>
        <p class="footer-desc mt-3 text-white-50" style="line-height: 1.7;">
          Platform direktori wisata dan penyusun <em>smart itinerary</em> terpadu untuk kawasan <strong>Barlingmascakeb</strong> (Banjarnegara, Purbalingga, Banyumas, Cilacap, dan Kebumen). Misi kami adalah menghubungkan pelancong dengan pesona alam dan budaya lokal secara mudah, cerdas, dan terpercaya.
        </p>
        <div class="footer-socials mt-4 d-flex gap-3">
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-instagram"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-facebook"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-tiktok"></i></a>
          <a href="#" class="text-white-50 fs-5 hover-warning transition"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider">Tautan Cepat</h6>
        <ul class="list-unstyled footer-links">
          <li class="mb-2"><a href="index.php" class="text-decoration-none text-white-50 hover-white transition">Beranda</a></li>
          <li class="mb-2"><a href="explore.php" class="text-decoration-none text-white-50 hover-white transition">Eksplorasi Wisata</a></li>
          <li class="mb-2"><a href="about.php" class="text-decoration-none text-white-50 hover-white transition">Tentang Kami</a></li>
          <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-white transition" onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('itineraryModal')).show(); return false;">Smart Itinerary</a></li>
        </ul>
      </div>
      
      <div class="col-6 col-lg-3">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider">Kategori Populer</h6>
        <div class="row">
          <div class="col-6">
            <ul class="list-unstyled footer-links">
              <li class="mb-2"><a href="explore.php?cat=nature" class="text-decoration-none text-white-50 hover-white transition">Alam & Hutan</a></li>
              <li class="mb-2"><a href="explore.php?cat=beach" class="text-decoration-none text-white-50 hover-white transition">Pantai & Laut</a></li>
              <li class="mb-2"><a href="explore.php?cat=mountain" class="text-decoration-none text-white-50 hover-white transition">Pegunungan</a></li>
            </ul>
          </div>
          <div class="col-6">
            <ul class="list-unstyled footer-links">
              <li class="mb-2"><a href="explore.php?cat=cultural" class="text-decoration-none text-white-50 hover-white transition">Seni & Budaya</a></li>
              <li class="mb-2"><a href="explore.php?cat=historical" class="text-decoration-none text-white-50 hover-white transition">Situs Sejarah</a></li>
              <li class="mb-2"><a href="explore.php?tag=hidden-gem" class="text-decoration-none text-warning fw-semibold hover-white transition">Hidden Gem</a></li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3">
        <h6 class="footer-heading text-uppercase text-warning mb-3 fw-bold tracking-wider">Kemitraan & Bantuan</h6>
        <p class="footer-desc small text-white-50 mb-3" style="line-height: 1.6;">
          Punya destinasi wisata baru yang ingin didaftarkan? Atau mengalami kendala teknis pada sistem? Tim kami siap membantu Anda.
        </p>
        <a href="contact.php" class="btn btn-outline-warning btn-sm w-100 fw-bold py-2 mb-3 transition">
          <i class="bi bi-envelope-fill me-2"></i>Hubungi Kemitraan
        </a>
        <p class="text-white-50 small mt-2 mb-0"><i class="bi bi-telephone-fill text-warning me-2"></i>+62 857-1322-8321</p>
      </div>
      
    </div>
    
    <div class="footer-bottom mt-5 pt-4 border-top border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center">
      <p class="mb-0 text-white-50 small">© <?= date('Y'); ?> Traveloop Barlingmascakeb. Hak Cipta Dilindungi.</p>
      <p class="mb-0 text-white-50 small mt-2 mt-md-0">Dirancang dengan <i class="bi bi-heart-fill text-danger mx-1"></i> di Jawa Tengah</p>
    </div>
  </div>
</footer>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content wn-modal-content border-0" id="modalBody">
      </div>
  </div>
</div>

<div class="modal fade" id="itineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
        <h4 class="modal-title fw-bold text-dark"><i class="bi bi-magic text-primary me-2"></i>Traveloop Itinerary</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="itineraryModalBody">
        <p class="text-muted mb-4">Pilih tema liburanmu, dan biarkan sistem merancang perjalanan sempurna 1 hari penuh di sekitar Barlingmascakeb.</p>
        
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card h-100 border border-success bg-light p-4 text-center rounded-4 shadow-sm" style="cursor:pointer" onclick="generateMockItinerary('Alam')">
                    <i class="bi bi-tree-fill display-4 text-success mb-3"></i>
                    <h6 class="fw-bold text-dark">Jelajah Alam</h6>
                    <small class="text-muted">Hutan, Curug & Udara Segar</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border border-primary bg-light p-4 text-center rounded-4 shadow-sm" style="cursor:pointer" onclick="generateMockItinerary('Pantai')">
                    <i class="bi bi-water display-4 text-primary mb-3"></i>
                    <h6 class="fw-bold text-dark">Pesisir Biru</h6>
                    <small class="text-muted">Pantai, Pasir & Seafood</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border border-warning bg-light p-4 text-center rounded-4 shadow-sm" style="cursor:pointer" onclick="generateMockItinerary('Sejarah')">
                    <i class="bi bi-bank2 display-4 text-warning mb-3"></i>
                    <h6 class="fw-bold text-dark">Mesin Waktu</h6>
                    <small class="text-muted">Sejarah, Candi & Budaya</small>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>