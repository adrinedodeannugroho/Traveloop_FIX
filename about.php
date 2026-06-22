<?php
// about.php
// Memanggil Header yang memuat koneksi database dan aset CSS global
require_once 'includes/header.php';
?>

<style>
  .team-card-pro {
      background: #ffffff;
      border-radius: 1.25rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 1px solid rgba(0,0,0,0.05);
      display: flex;
      flex-direction: column;
      height: 100%;
  }
  .team-card-pro:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08) !important;
      border-color: rgba(245, 158, 11, 0.3);
  }
  .team-avatar-pro {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      flex-shrink: 0;
  }
  .tupoksi-list li {
      margin-bottom: 0.75rem;
      display: flex;
      align-items: flex-start;
      line-height: 1.5;
  }
  .tupoksi-list li i {
      margin-top: 2px;
      flex-shrink: 0;
  }
  .manifesto-text {
      font-size: 1.15rem;
      line-height: 1.8;
      color: #475569;
  }
</style>

<div class="page-header-solid" style="padding-top: 120px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
  <div class="container text-center pb-5">
    <h1 class="page-title text-white fw-bold mb-3" style="letter-spacing: -0.5px;">Tentang Traveloop</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb justify-content-center mt-3 mb-0">
        <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-none opacity-75 hover-white">Home</a></li>
        <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">Tentang Kami</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad bg-white py-5 mt-4">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-10 col-xl-8">
        <p class="section-eyebrow text-warning fw-bold tracking-wider mb-2">Manifesto Perusahaan</p>
        <h2 class="section-title fw-bold text-dark mb-4">Mengenalkan Keindahan Barlingmascakeb ke Dunia</h2>
        <p class="manifesto-text mb-4">
          <strong>Traveloop</strong> adalah platform direktori pariwisata terpadu yang didedikasikan untuk mengintegrasikan, memperkenalkan, dan mempromosikan destinasi wisata unggulan di kawasan Banjarnegara, Purbalingga, Banyumas, Cilacap, dan Kebumen (Barlingmascakeb). Kami percaya bahwa optimalisasi pariwisata daerah dapat dicapai melalui digitalisasi yang merata.
        </p>
        <p class="manifesto-text mb-5">
          Hadir bukan sekadar sebagai direktori statis, Traveloop dirancang untuk menjadi jembatan ekosistem pariwisata modern — menghubungkan pelancong dengan pesona alam tersembunyi, warisan budaya luhur, sekaligus memberdayakan entitas bisnis serta ekonomi kreatif lokal.
        </p>
        
        <div class="row g-3 justify-content-center mt-2 border-top pt-5">
          <div class="col-6 col-md-3">
            <div class="p-3 text-center">
              <span class="d-block fw-bold display-6 text-dark mb-1">50+</span>
              <span class="text-muted small text-uppercase tracking-wider fw-bold">Destinasi Terkurasi</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center border-start-md border-light">
              <span class="d-block fw-bold display-6 text-dark mb-1">10K+</span>
              <span class="text-muted small text-uppercase tracking-wider fw-bold">Wisatawan Terbantu</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center border-start-md border-light">
              <span class="d-block fw-bold display-6 text-dark mb-1">4</span>
              <span class="text-muted small text-uppercase tracking-wider fw-bold">Kabupaten Tercover</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="p-3 text-center border-start-md border-light">
              <span class="d-block fw-bold display-6 text-dark mb-1">1K+</span>
              <span class="text-muted small text-uppercase tracking-wider fw-bold">Ulasan Valid</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-light py-5">
  <div class="container py-4">
    <div class="text-center mb-5">
      <p class="section-eyebrow text-warning fw-bold tracking-wider mb-2">Arah Strategis</p>
      <h2 class="section-title fw-bold text-dark">Visi & Misi Bisnis</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-5">
        <div class="visi-card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white h-100 transition hover-zoom">
          <div class="visi-icon mb-4"><i class="bi bi-eye-fill fs-1 text-primary bg-primary bg-opacity-10 p-3 rounded-circle"></i></div>
          <h4 class="visi-title text-dark fw-bold mb-3">Visi</h4>
          <p class="visi-text text-muted" style="line-height: 1.8;">Menjadi ekosistem digital dan platform referensi pariwisata interaktif terbesar serta paling terpercaya di kawasan Jawa Tengah bagian barat daya guna menggerakkan roda ekonomi daerah secara inklusif.</p>
        </div>
      </div>
      <div class="col-md-7">
        <div class="visi-card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white h-100 transition hover-zoom">
          <div class="visi-icon mb-4"><i class="bi bi-bullseye fs-1 text-warning bg-warning bg-opacity-10 p-3 rounded-circle"></i></div>
          <h4 class="visi-title text-dark fw-bold mb-3">Misi</h4>
          <ul class="visi-list ps-3 text-muted" style="line-height: 1.8;">
            <li class="mb-2">Menyediakan data direktori pariwisata terverifikasi, akurat, dan terus diperbarui secara dinamis.</li>
            <li class="mb-2">Mengembangkan alat bantu pintar pelancong guna menyusun rencana perjalanan efisien.</li>
            <li class="mb-2">Membuka ruang kolaborasi komersial bagi pelaku UMKM lokal dan pengelola destinasi daerah.</li>
            <li>Menggalakkan promosi pariwisata berbasis pelestarian lingkungan dan kearifan budaya lokal.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-white py-5">
  <div class="container py-4">
    <div class="text-center mb-5">
      <p class="section-eyebrow text-warning fw-bold tracking-wider mb-2">Struktur Organisasi & Tanggung Jawab</p>
      <h2 class="section-title fw-bold text-dark">Profil Tim Pengembang Traveloop</h2>
      <p class="text-muted mx-auto mt-2" style="max-width: 600px;">Dibangun oleh kolaborasi talenta terbaik yang berdedikasi tinggi untuk memastikan kehandalan, estetika, dan stabilitas operasional sistem.</p>
    </div>
    
    <div class="row g-4 justify-content-center align-items-stretch">
      
      <div class="col-md-6 col-xl-4">
        <div class="team-card-pro shadow-sm p-4">
          <div class="d-flex align-items-center mb-4">
            <div class="team-avatar-pro shadow-sm me-3" style="background: linear-gradient(135deg, #f59e0b, #ca8a04);"><i class="bi bi-person-fill"></i></div>
            <div>
              <h5 class="text-dark fw-bold mb-1">Azhar Khoirul R.A</h5>
              <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="bi bi-kanban me-1"></i>Project Manager</span>
            </div>
          </div>
          <div class="bg-light p-3 rounded-3 flex-grow-1 d-flex flex-column">
            <span class="d-block small fw-bold text-muted mb-3 text-uppercase tracking-wider border-bottom pb-2">Tanggung Jawab Utama:</span>
            <ul class="list-unstyled mb-0 tupoksi-list small text-dark">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Memantau progres keseluruhan proyek (Accountable).</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Mengkoordinasi tim dan menjadwalkan meeting.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Memastikan timeline dan milestone terpenuhi.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Menyiapkan laporan progress kepada stakeholder.</li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="col-md-6 col-xl-4">
        <div class="team-card-pro shadow-sm p-4">
          <div class="d-flex align-items-center mb-4">
            <div class="team-avatar-pro shadow-sm me-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);"><i class="bi bi-person-fill"></i></div>
            <div>
              <h5 class="text-dark fw-bold mb-1">Izul Robettul Imam</h5>
              <span class="badge bg-primary fw-bold px-2 py-1"><i class="bi bi-server me-1"></i>Data/API Specialist</span>
            </div>
          </div>
          <div class="bg-light p-3 rounded-3 flex-grow-1 d-flex flex-column">
            <span class="d-block small fw-bold text-muted mb-3 text-uppercase tracking-wider border-bottom pb-2">Tanggung Jawab Utama:</span>
            <ul class="list-unstyled mb-0 tupoksi-list small text-dark">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Responsible untuk integrasi API sistem.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Mengintegrasikan storage & cloud services.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Melakukan sinkronisasi data antar sistem.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Menyusun dokumentasi API & pemetaan data flow.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="team-card-pro shadow-sm p-4">
          <div class="d-flex align-items-center mb-4">
            <div class="team-avatar-pro shadow-sm me-3" style="background: linear-gradient(135deg, #8b5cf6, #5b21b6);"><i class="bi bi-person-fill"></i></div>
            <div>
              <h5 class="text-dark fw-bold mb-1">Danendra Yafi Kumara</h5>
              <span class="badge text-white fw-bold px-2 py-1" style="background-color: #8b5cf6;"><i class="bi bi-terminal-fill me-1"></i>Backend Developer</span>
            </div>
          </div>
          <div class="bg-light p-3 rounded-3 flex-grow-1 d-flex flex-column">
            <span class="d-block small fw-bold text-muted mb-3 text-uppercase tracking-wider border-bottom pb-2">Tanggung Jawab Utama:</span>
            <ul class="list-unstyled mb-0 tupoksi-list small text-dark">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Responsible penuh untuk backend development.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Mengembangkan API & struktur logika bisnis utama.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Mengelola stabilitas database & struktur data.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Implementasi modul autentikasi & keamanan sistem.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="team-card-pro shadow-sm p-4">
          <div class="d-flex align-items-center mb-4">
            <div class="team-avatar-pro shadow-sm me-3" style="background: linear-gradient(135deg, #10b981, #047857);"><i class="bi bi-person-fill"></i></div>
            <div>
              <h5 class="text-dark fw-bold mb-1">Izzati Shafa</h5>
              <span class="badge bg-success fw-bold px-2 py-1"><i class="bi bi-window-sidebar me-1"></i>Frontend Developer</span>
            </div>
          </div>
          <div class="bg-light p-3 rounded-3 flex-grow-1 d-flex flex-column">
            <span class="d-block small fw-bold text-muted mb-3 text-uppercase tracking-wider border-bottom pb-2">Tanggung Jawab Utama:</span>
            <ul class="list-unstyled mb-0 tupoksi-list small text-dark">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Responsible untuk alur UI/UX development.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Merancang & mengimplementasikan antarmuka pengguna.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Membuat komponen reusable dan modal interaktif.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Melakukan audit aksesibilitas UI/UX & refactoring code.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="team-card-pro shadow-sm p-4">
          <div class="d-flex align-items-center mb-4">
            <div class="team-avatar-pro shadow-sm me-3" style="background: linear-gradient(135deg, #0ea5e9, #0369a1);"><i class="bi bi-person-fill"></i></div>
            <div>
              <h5 class="text-dark fw-bold mb-1">Adrinedo Dean Nugroho</h5>
              <span class="badge bg-info text-dark fw-bold px-2 py-1"><i class="bi bi-window-sidebar me-1"></i>Frontend Developer</span>
            </div>
          </div>
          <div class="bg-light p-3 rounded-3 flex-grow-1 d-flex flex-column">
            <span class="d-block small fw-bold text-muted mb-3 text-uppercase tracking-wider border-bottom pb-2">Tanggung Jawab Utama:</span>
            <ul class="list-unstyled mb-0 tupoksi-list small text-dark">
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Responsible untuk interaktivitas UI/UX development.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Merancang antarmuka & merealisasikannya ke baris kode.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Membangun komponen reusable frontend modern.</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Melakukan audit UI lintas browser & refactoring code.</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="section-pad bg-light py-5 border-top">
  <div class="container py-4">
    <div class="text-center mb-5">
      <p class="section-eyebrow text-warning fw-bold tracking-wider mb-2">Nilai Utama</p>
      <h2 class="section-title fw-bold text-dark">Prinsip Kerja Kami</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 rounded-4 h-100 bg-white text-center transition hover-zoom">
          <i class="bi bi-shield-check display-4 mb-3 d-block" style="color:#2d9e6b"></i>
          <h5 class="text-dark fw-bold mb-2">Akurasi</h5>
          <p class="text-muted small mb-0">Informasi dan koordinat geolokasi destinasi yang kami sajikan diverifikasi secara ketat.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 rounded-4 h-100 bg-white text-center transition hover-zoom">
          <i class="bi bi-heart-fill display-4 mb-3 d-block" style="color:#ef4444"></i>
          <h5 class="text-dark fw-bold mb-2">Inklusi Lokal</h5>
          <p class="text-muted small mb-0">Kami berdedikasi tinggi untuk mengangkat potensi dan visibilitas pelaku UMKM lokal.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 rounded-4 h-100 bg-white text-center transition hover-zoom">
          <i class="bi bi-tree-fill display-4 mb-3 d-block" style="color:#eab308"></i>
          <h5 class="text-dark fw-bold mb-2">Berkelanjutan</h5>
          <p class="text-muted small mb-0">Mendukung kampanye pariwisata yang ramah lingkungan dan menghormati kearifan lokal.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 rounded-4 h-100 bg-white text-center transition hover-zoom">
          <i class="bi bi-people-fill display-4 mb-3 d-block" style="color:#0ea5e9"></i>
          <h5 class="text-dark fw-bold mb-2">Ekosistem</h5>
          <p class="text-muted small mb-0">Membangun kerja sama yang harmonis antara pengelola wisata, wisatawan, dan pemerintah.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="partner-strip text-center bg-white border-top border-light py-4">
  <div class="container">
    <p class="text-muted small fw-bold text-uppercase tracking-wider mb-0">Telah Didukung Oleh Berbagai Instansi Pariwisata Daerah Terkait</p>
  </div>
</section>

<?php
// Memanggil Footer global yang menutup tag halaman secara sempurna
require_once 'includes/footer.php';
?>