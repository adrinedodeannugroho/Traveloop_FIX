<?php
// contact.php
// 1. Memanggil koneksi database terlebih dahulu untuk memproses form
require_once 'config/koneksi.php';

$alert_msg = '';
$alert_class = 'd-none';

// Logika pemrosesan form kontak saat disubmit (Metode POST) menggunakan pola PRG
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_pesan'])) {
    // Pengamanan data dari SQL Injection
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $topik = mysqli_real_escape_string($koneksi, $_POST['topik']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $tanggal = date('Y-m-d H:i:s');

    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        $query = "INSERT INTO pesan_kontak (nama, email, no_wa, topik, pesan, tanggal) VALUES ('$nama', '$email', '$no_wa', '$topik', '$pesan', '$tanggal')";
        
        if (mysqli_query($koneksi, $query)) {
            // MENGHINDARI PESAN GANDA: Alihkan kembali ke halaman ini dengan status sukses
            header("Location: contact.php?status=success");
            exit;
        } else {
            $alert_class = 'alert alert-danger shadow-sm border-0 mb-4 rounded-3';
            $alert_msg = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Gagal!</strong> Terjadi kesalahan sistem. Error: ' . mysqli_error($koneksi);
        }
    } else {
        $alert_class = 'alert alert-warning shadow-sm border-0 mb-4 rounded-3';
        $alert_msg = '<i class="bi bi-info-circle-fill me-2"></i>Harap isi semua kolom yang bertanda wajib (*).';
    }
}

// 2. Memanggil Header global (berisi Navbar dan tag Head CSS)
require_once 'includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-header-solid" style="padding-top: 140px !important; padding-bottom: 60px !important; background: linear-gradient(135deg, #0f172a, #1e3a5f) !important; background-color: #0f172a !important; display: block !important; position: relative !important; z-index: 1 !important; width: 100% !important;">
  <div class="container text-center">
    <h1 class="page-title text-white mb-2" style="font-family: var(--ff-display) !important; font-weight: 700 !important; font-size: 2.5rem !important; color: #ffffff !important; text-shadow: 0 2px 10px rgba(0,0,0,0.3) !important;">Hubungi Kemitraan</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb justify-content-center" style="margin-top: 10px !important;">
        <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-none opacity-75 hover-white">Home</a></li>
        <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">Kontak</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad bg-soft" style="position: relative !important; z-index: 2 !important;">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-7">
        <p class="section-eyebrow">Pusat Bantuan & Kerja Sama</p>
        <h2 class="section-title">Ada Pertanyaan atau Ide Kolaborasi?</h2>
        <p class="about-text mt-2 mb-4 text-muted">Kami sangat antusias untuk terhubung dengan wisatawan, pengelola wisata, dan pelaku bisnis lokal di area Barlingmascakeb. Sampaikan pesan Anda di bawah ini.</p>

        <?php if(!empty($alert_msg)): ?>
        <div id="contactAlert" class="<?= $alert_class ?>">
            <?= $alert_msg ?>
        </div>
        <?php endif; ?>

        <div class="contact-form-wrap bg-white p-4 p-md-5 rounded-4 shadow-sm border-0">
          <form method="POST" action="contact.php">
            <div class="row g-4">
              <div class="col-md-6">
                <label class="contact-label text-muted small fw-bold text-uppercase tracking-wider" for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" class="form-control contact-input bg-light border-0 py-2" placeholder="Masukkan nama..." required/>
              </div>
              <div class="col-md-6">
                <label class="contact-label text-muted small fw-bold text-uppercase tracking-wider" for="email">Alamat Email *</label>
                <input type="email" id="email" name="email" class="form-control contact-input bg-light border-0 py-2" placeholder="email@domain.com" required/>
              </div>
              <div class="col-md-6">
                <label class="contact-label text-muted small fw-bold text-uppercase tracking-wider" for="no_wa">No. WhatsApp</label>
                <input type="text" id="no_wa" name="no_wa" class="form-control contact-input bg-light border-0 py-2" placeholder="08xx-xxxx-xxxx"/>
              </div>
              <div class="col-md-6">
                <label class="contact-label text-muted small fw-bold text-uppercase tracking-wider" for="topik">Topik Pesan</label>
                <select id="topik" name="topik" class="form-select contact-input bg-light border-0 py-2 text-muted">
                  <option value="">-- Pilih Topik --</option>
                  <option value="Informasi Destinasi">Informasi Destinasi</option>
                  <option value="Kerjasama / Partnership">Kerjasama / Partnership Bisnis</option>
                  <option value="Pendaftaran Wisata Baru">Pendaftaran Wisata Baru</option>
                  <option value="Laporkan Bug / Error">Laporkan Bug / Kendala Sistem</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </div>
              <div class="col-12">
                <label class="contact-label text-muted small fw-bold text-uppercase tracking-wider" for="pesan">Pesan / Penjelasan *</label>
                <textarea id="pesan" name="pesan" class="form-control contact-input bg-light border-0" rows="5" placeholder="Tuliskan detail pesan Anda di sini..." required></textarea>
              </div>
              <div class="col-12 mt-4">
                <button type="submit" name="submit_pesan" class="btn btn-warning w-100 fw-bold py-3 shadow-sm rounded-3">
                  <i class="bi bi-send-fill me-2"></i>Kirim Pesan Sekarang
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0 h-100">
          <p class="section-eyebrow">Info Kontak</p>
          <h2 class="section-title mb-4">Temukan Kami</h2>
          
          <div class="contact-info-list mt-4">
            <div class="contact-info-item mb-4">
              <div class="contact-info-icon" style="--ci-color:#eab308"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <h6 class="contact-info-title text-dark fw-bold">Kantor Pusat Traveloop</h6>
                <p class="contact-info-text small">Jl. HR. Bunyamin No. 993,<br>Purwokerto, Banyumas, Jawa Tengah 53122</p>
              </div>
            </div>
            
            <div class="contact-info-item mb-4">
              <div class="contact-info-icon" style="--ci-color:#25d366"><i class="bi bi-whatsapp"></i></div>
              <div>
                <h6 class="contact-info-title text-dark fw-bold">Layanan WhatsApp</h6>
                <p class="contact-info-text small"><a href="https://wa.me/6285713228321" target="_blank" class="text-decoration-none">+62 857-1322-8321</a></p>
              </div>
            </div>
            
            <div class="contact-info-item mb-4">
              <div class="contact-info-icon" style="--ci-color:#0ea5e9"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <h6 class="contact-info-title text-dark fw-bold">Surel Elektronik (Email)</h6>
                <p class="contact-info-text small"><a href="mailto:withtraveloop@gmail.com" class="text-decoration-none">withtraveloop@gmail.com</a></p>
              </div>
            </div>
            
            <div class="contact-info-item mb-4">
              <div class="contact-info-icon" style="--ci-color:#e88a22"><i class="bi bi-clock-fill"></i></div>
              <div>
                <h6 class="contact-info-title text-dark fw-bold">Jam Operasional</h6>
                <p class="contact-info-text small">Senin – Jumat: 08.00 – 17.00 WIB<br>Sabtu: 09.00 – 13.00 WIB</p>
              </div>
            </div>
          </div>

          <hr class="my-4 text-light">

          <h6 class="contact-label mb-3 text-muted small fw-bold text-uppercase tracking-wider">Ikuti Sosial Media Kami</h6>
          <div class="d-flex gap-3">
            <a href="#" class="contact-social-link bg-light shadow-sm flex-fill text-center border-0" style="--cs-color:#e1306c"><i class="bi bi-instagram"></i></a>
            <a href="#" class="contact-social-link bg-light shadow-sm flex-fill text-center border-0" style="--cs-color:#1877f2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="contact-social-link bg-light shadow-sm flex-fill text-center border-0" style="--cs-color:#000"><i class="bi bi-tiktok"></i></a>
          </div>

          <div class="contact-map-wrap mt-4 shadow-sm rounded-4">
              <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126604.5932599728!2d109.15570075!3d-7.4269135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655c3136423d1d%3A0x4027a76e352e4a0!2sPurwokerto%2C%20Banyumas%20Regency%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                  width="100%" 
                  height="220" 
                  style="border:0;" 
                  allowfullscreen="" 
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade">
              </iframe>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
  window.addEventListener('DOMContentLoaded', () => {
      // Pengecekan Navbar Scroll
      if(typeof initNavScroll === 'function') {
          initNavScroll();
      }

      // Menampilkan SweetAlert2 jika status pengiriman berhasil
      <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
      Swal.fire({
          icon: 'success',
          title: 'Pesan Berhasil Terkirim!',
          text: 'Tim Traveloop akan segera merespons Anda dalam 1x24 Jam.',
          showConfirmButton: false,
          timer: 3500,
          timerProgressBar: true
      });
      
      // Membersihkan URL agar pesan tidak muncul lagi saat direfresh
      window.history.replaceState(null, null, window.location.pathname);
      <?php endif; ?>
  });
</script>

<?php
// 3. Memanggil Footer global
require_once 'includes/footer.php';
?>