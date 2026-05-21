<?php
include 'koneksi.php';

$alert_msg = '';
$alert_class = 'd-none';

// Logika pemrosesan form kontak saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_wa = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $topik = mysqli_real_escape_string($koneksi, $_POST['topik']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $tanggal = date('Y-m-d H:i:s');

    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        $query = "INSERT INTO pesan_kontak (nama, email, no_wa, topik, pesan, tanggal) VALUES ('$nama', '$email', '$no_wa', '$topik', '$pesan', '$tanggal')";
        
        if (mysqli_query($koneksi, $query)) {
            $alert_class = 'alert alert-success';
            $alert_msg = '<i class="bi bi-check-circle me-2"></i>Pesan berhasil dikirim! Kami akan membalas segera.';
        } else {
            $alert_class = 'alert alert-danger';
            $alert_msg = '<i class="bi bi-exclamation-triangle me-2"></i>Gagal mengirim pesan. Silakan coba lagi.';
        }
    } else {
        $alert_class = 'alert alert-danger';
        $alert_msg = 'Harap isi semua field yang wajib (*).';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kontak — Traveloop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<nav class="navbar navbar-expand-lg wn-navbar wn-navbar-solid fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php"><span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="bi bi-info-circle me-1"></i>Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="explore.php"><i class="bi bi-compass me-1"></i>Explore</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php"><i class="bi bi-envelope me-1"></i>Kontak</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="page-header-solid">
  <div class="container">
    <h1 class="page-title">Hubungi Kami</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Kontak</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-7">
        <p class="section-eyebrow">Kirim Pesan</p>
        <h2 class="section-title">Ada Pertanyaan atau Saran?</h2>
        <p class="about-text mt-2 mb-4">Kami senang mendengar dari kamu! Isi formulir di bawah dan tim kami akan membalas dalam 1×24 jam.</p>

        <div id="contactAlert" class="<?= $alert_class ?>">
            <?= $alert_msg ?>
        </div>

        <form method="POST" action="contact.php" class="contact-form-wrap">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="contact-label" for="nama">Nama Lengkap *</label>
              <input type="text" id="nama" name="nama" class="form-control contact-input" placeholder="Nama kamu" required/>
            </div>
            <div class="col-md-6">
              <label class="contact-label" for="email">Email *</label>
              <input type="email" id="email" name="email" class="form-control contact-input" placeholder="email@kamu.com" required/>
            </div>
            <div class="col-md-6">
              <label class="contact-label" for="no_wa">No. WhatsApp</label>
              <input type="text" id="no_wa" name="no_wa" class="form-control contact-input" placeholder="08xxxxxxxxxx"/>
            </div>
            <div class="col-md-6">
              <label class="contact-label" for="topik">Topik</label>
              <select id="topik" name="topik" class="form-select contact-input">
                <option value="">Pilih topik…</option>
                <option value="Informasi Destinasi">Informasi Destinasi</option>
                <option value="Saran Destinasi Baru">Saran Destinasi Baru</option>
                <option value="Kerjasama / Partnership">Kerjasama / Partnership</option>
                <option value="Laporkan Bug / Error">Laporkan Bug / Error</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="col-12">
              <label class="contact-label" for="pesan">Pesan *</label>
              <textarea id="pesan" name="pesan" class="form-control contact-input" rows="5" placeholder="Tulis pesanmu di sini…" required></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-contact-send w-100">
                <i class="bi bi-send me-2"></i>Kirim Pesan
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-5">
        <p class="section-eyebrow">Info Kontak</p>
        <h2 class="section-title">Temukan Kami</h2>
        <div class="contact-info-list mt-4">
          <div class="contact-info-item">
            <div class="contact-info-icon" style="--ci-color:#eab308"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
              <h6 class="contact-info-title">Alamat</h6>
              <p class="contact-info-text">Jl. HR. Bunyamin No. 993,<br>Purwokerto, Banyumas, Jawa Tengah 53122</p>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon" style="--ci-color:#25d366"><i class="bi bi-whatsapp"></i></div>
            <div>
              <h6 class="contact-info-title">WhatsApp</h6>
              <p class="contact-info-text"><a href="https://wa.me/6281234567890" target="_blank">+62 857-1322-8321</a></p>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon" style="--ci-color:#0ea5e9"><i class="bi bi-envelope-fill"></i></div>
            <div>
              <h6 class="contact-info-title">Email</h6>
              <p class="contact-info-text"><a href="mailto:hello@traveloop.id">withtraveloop@gmail.com</a></p>
            </div>
          </div>
          <div class="contact-info-item">
            <div class="contact-info-icon" style="--ci-color:#e88a22"><i class="bi bi-clock-fill"></i></div>
            <div>
              <h6 class="contact-info-title">Jam Operasional</h6>
              <p class="contact-info-text">Senin – Jumat: 08.00 – 17.00 WIB<br>Sabtu: 09.00 – 13.00 WIB</p>
            </div>
          </div>
        </div>

        <div class="contact-socials mt-4">
          <h6 class="contact-label mb-3">Ikuti Kami</h6>
          <div class="d-flex gap-3">
            <a href="#" class="contact-social-link" style="--cs-color:#e1306c"><i class="bi bi-instagram"></i><span>Instagram</span></a>
            <a href="#" class="contact-social-link" style="--cs-color:#1877f2"><i class="bi bi-facebook"></i><span>Facebook</span></a>
            <a href="#" class="contact-social-link" style="--cs-color:#000"><i class="bi bi-tiktok"></i><span>TikTok</span></a>
          </div>
        </div>

        <div class="contact-map-wrap mt-4">
          <iframe
            width="100%" height="200" style="border:0;border-radius:12px;" loading="lazy"
            allowfullscreen referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.0!2d109.2354!3d-7.4226!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMjUnMjEuNCJTIDEwOcKwMTQnMDcuNCJF!5e0!3m2!1sid!2sid!4v1234567890">
          </iframe>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="section-pad bg-soft">
  </section>

<footer class="wn-footer">
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', () => initNavScroll());
  // script custom sendContactForm() telah dihapus sepenuhnya dari sini
</script>
</body>
</html>