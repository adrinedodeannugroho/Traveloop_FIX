<?php
// about.php
// Memanggil Header yang memuat koneksi database dan aset CSS global
require_once 'includes/header.php';
?>

<div class="page-header-solid" style="padding-top: 120px;">
  <div class="container text-center">
    <h1 class="page-title text-white">Tentang Kami</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb justify-content-center mt-3">
        <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-none opacity-75 hover-white">Home</a></li>
        <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">Tentang Kami</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad bg-white">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <p class="section-eyebrow">Siapa Kami</p>
        <h2 class="section-title">Mengenalkan Keindahan Barlingmascakeb ke Dunia</h2>
        <p class="about-text mt-3">
          <strong>Traveloop</strong> adalah platform direktori pariwisata terpadu yang didedikasikan untuk mengintegrasikan, memperkenalkan, dan mempromosikan destinasi wisata unggulan di kawasan Banjarnegara, Purbalingga, Banyumas, Cilacap, dan Kebumen (Barlingmascakeb). Kami percaya bahwa optimalisasi pariwisata daerah dapat dicapai melalui digitalisasi yang merata.
        </p>
        <p class="about-text mb-4">
          Hadir bukan sekadar sebagai direktori statis, Traveloop dirancang untuk menjadi jembatan ekosistem pariwisata modern — menghubungkan pelancong dengan pesona alam tersembunyi, warisan budaya luhur, sekaligus memberdayakan entitas bisnis serta ekonomi kreatif lokal.
        </p>
        
        <div class="row g-3">
          <div class="col-6"><div class="about-stat-box shadow-sm border-0 bg-light"><span class="about-stat-num">50+</span><span class="about-stat-label">Destinasi Terkurasi</span></div></div>
          <div class="col-6"><div class="about-stat-box shadow-sm border-0 bg-light"><span class="about-stat-num">10K+</span><span class="about-stat-label">Wisatawan Terbantu</span></div></div>
          <div class="col-6"><div class="about-stat-box shadow-sm border-0 bg-light"><span class="about-stat-num">4</span><span class="about-stat-label">Kabupaten Tercover</span></div></div>
          <div class="col-6"><div class="about-stat-box shadow-sm border-0 bg-light"><span class="about-stat-num">1K+</span><span class="about-stat-label">Ulasan Valid</span></div></div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="about-img-collage">
          <div class="about-img-main shadow"><img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=700&q=80" alt="Wisata Alam" loading="lazy"/></div>
          <div class="about-img-side">
            <img class="shadow" src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=400&q=80" alt="Air Terjun Regional" loading="lazy"/>
            <img class="shadow" src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=400&q=80" alt="Pegunungan Slamet" loading="lazy"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-soft">
  <div class="container">
    <div class="text-center mb-5">
      <p class="section-eyebrow">Arah Strategis</p>
      <h2 class="section-title">Visi & Misi Bisnis</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="visi-card border-0 shadow-sm h-100">
          <div class="visi-icon"><i class="bi bi-eye-fill"></i></div>
          <h4 class="visi-title text-dark">Visi</h4>
          <p class="visi-text">Menjadi ekosistem digital dan platform referensi pariwisata interaktif terbesar serta paling terpercaya di kawasan Jawa Tengah bagian barat daya guna menggerakkan roda ekonomi daerah secara inklusif.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="visi-card border-0 shadow-sm h-100" style="--vm-color:#0ea5e9">
          <div class="visi-icon"><i class="bi bi-bullseye"></i></div>
          <h4 class="visi-title text-dark">Misi</h4>
          <ul class="visi-list ps-3">
            <li>Menyediakan data direktori pariwisata terverifikasi, akurat, dan terus diperbarui secara dinamis.</li>
            <li>Mengembangkan alat bantu pintar pelancong guna menyusun rencana perjalanan efisien dalam satu platform.</li>
            <li>Membuka ruang kolaborasi komersial bagi pelaku UMKM lokal, pemandu wisata, dan pengelola destinasi daerah.</li>
            <li>Menggalakkan promosi pariwisata berbasis pelestarian lingkungan dan kearifan budaya nusantara.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <p class="section-eyebrow">Inovator Di Balik Layar</p>
      <h2 class="section-title">Tim Manajemen</h2>
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 justify-content-center">
      <div class="col">
        <div class="team-card border-0 shadow-sm h-100 bg-light">
          <div class="team-avatar shadow" style="background:linear-gradient(135deg,#eab308,#ca8a04)"><i class="bi bi-person-fill"></i></div>
          <h6 class="team-name text-dark">Azhar Khoirul A R</h6>
          <span class="team-role">Founder & CEO</span>
          <p class="team-desc mt-2 small text-muted">Mengarahkan kompas bisnis, tata kelola kemitraan strategis, dan menjalin hubungan kerja sama dengan instansi kedinasan pariwisata daerah.</p>
        </div>
      </div>
      
      <div class="col">
        <div class="team-card border-0 shadow-sm h-100 bg-light">
          <div class="team-avatar shadow" style="background:linear-gradient(135deg,#9b4f96,#5b2168)"><i class="bi bi-person-fill"></i></div>
          <h6 class="team-name text-dark">Danendra Yafi Kumara</h6>
          <span class="team-role">Content & Research</span>
          <p class="team-desc mt-2 small text-muted">Melakukan riset komprehensif, validasi akurasi data lapangan, kurasi narasi konten kreatif, serta penyesuaian tarif berkala.</p>
        </div>
      </div>
      
      <div class="col">
        <div class="team-card border-0 shadow-sm h-100 bg-light">
          <div class="team-avatar shadow" style="background:linear-gradient(135deg,#f59e0b,#652b00)"><i class="bi bi-person-fill"></i></div>
          <h6 class="team-name text-dark">Izul Robettul Imam</h6>
          <span class="team-role">Tech Lead</span>
          <p class="team-desc mt-2 small text-muted">Merancang arsitektur sistem, optimalisasi performa basis data, memastikan keamanan platform kustom, serta menangani integrasi pemetaan API.</p>
        </div>
      </div>
      
      <div class="col">
        <div class="team-card border-0 shadow-sm h-100 bg-light">
          <div class="team-avatar shadow" style="background:linear-gradient(135deg,#2d9e6b,#16573c)"><i class="bi bi-person-fill"></i></div>
          <h6 class="team-name text-dark">Adrinedo Dean Nugroho</h6>
          <span class="team-role">Design & UX Specialist</span>
          <p class="team-desc mt-2 small text-muted">Menyusun kerangka visual antarmuka sistem (UI/UX) dan memastikan kenyamanan interaksi pengguna berjalan responsif lintas perangkat.</p>
        </div>
      </div>
      
      <div class="col">
        <div class="team-card border-0 shadow-sm h-100 bg-light">
          <div class="team-avatar shadow" style="background:linear-gradient(135deg,#0ea5e9,#0369a1)"><i class="bi bi-person-fill"></i></div>
          <h6 class="team-name text-dark">Izzati Shafa</h6>
          <span class="team-role">QA & Data Analyst</span>
          <p class="team-desc mt-2 small text-muted">Melakukan penjaminan mutu fungsionalitas fitur, pelacakan kendala teknis, serta analisis metrik pencarian untuk algoritma rekomendasi.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-soft">
  <div class="container">
    <div class="text-center mb-5">
      <p class="section-eyebrow">Nilai Utama</p>
      <h2 class="section-title">Prinsip Kerja Kami</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 h-100 bg-white">
          <i class="bi bi-shield-check nilai-icon d-inline-block p-2 rounded-3 bg-light mb-3" style="color:#2d9e6b"></i>
          <h5 class="text-dark fw-bold mb-2">Akurasi</h5>
          <p class="text-muted small mb-0">Informasi dan koordinat geolokasi destinasi yang kami sajikan telah melalui proses verifikasi berkala demi kenyamanan perjalanan.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 h-100 bg-white">
          <i class="bi bi-heart-fill nilai-icon d-inline-block p-2 rounded-3 bg-light mb-3" style="color:#ef4444"></i>
          <h5 class="text-dark fw-bold mb-2">Inklusi Lokal</h5>
          <p class="text-muted small mb-0">Kami berdedikasi tinggi untuk mengangkat potensi ekonomi daerah serta memperluas visibilitas digital para pelaku UMKM lokal.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 h-100 bg-white">
          <i class="bi bi-tree-fill nilai-icon d-inline-block p-2 rounded-3 bg-light mb-3" style="color:#eab308"></i>
          <h5 class="text-dark fw-bold mb-2">Berkelanjutan</h5>
          <p class="text-muted small mb-0">Mendukung penuh kampanye pariwisata yang ramah lingkungan, bertanggung jawab, serta menghormati kearifan lokal adat setempat.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="nilai-card border-0 shadow-sm p-4 h-100 bg-white">
          <i class="bi bi-people-fill nilai-icon d-inline-block p-2 rounded-3 bg-light mb-3" style="color:#0ea5e9"></i>
          <h5 class="text-dark fw-bold mb-2">Ekosistem</h5>
          <p class="text-muted small mb-0">Membangun kerja sama yang harmonis dan berkelanjutan antara pengelola wisata daerah, wisatawan, komunitas, dan pemerintah.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="partner-strip text-center bg-white border-bottom border-light">
  <div class="container">
    <p class="text-muted small text-uppercase tracking-wider mb-0">Telah Didukung Oleh Berbagai Instansi Pariwisata Daerah Terkait</p>
  </div>
</section>

<?php
// Memanggil Footer global yang menutup tag halaman secara sempurna
require_once 'includes/footer.php';
?>