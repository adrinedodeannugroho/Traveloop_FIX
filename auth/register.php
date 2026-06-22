<?php
// auth/register.php
require_once dirname(__DIR__) . '/config/koneksi.php';

$errors = [];
$success = false;
$old_nama = '';
$old_email = '';

// Jika sudah login, redirect ke index
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';

    $old_nama = $nama;
    $old_email = $email;

    // Validasi
    if (empty($nama)) {
        $errors[] = 'Nama lengkap wajib diisi.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }
    if ($password !== $konfirmasi) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }

    // Cek email sudah terdaftar
    if (empty($errors)) {
        $cek_email = mysqli_real_escape_string($koneksi, $email);
        $q = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$cek_email' LIMIT 1");
        if ($q && mysqli_num_rows($q) > 0) {
            $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain atau langsung Login.';
        }
    }

    // Simpan ke database
    if (empty($errors)) {
        $nama_safe = mysqli_real_escape_string($koneksi, $nama);
        $email_safe = mysqli_real_escape_string($koneksi, $email);
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (nama_lengkap, email, password) VALUES ('$nama_safe', '$email_safe', '$password_hash')";
        if (mysqli_query($koneksi, $query)) {
            $success = true;
        } else {
            $errors[] = 'Terjadi kesalahan saat menyimpan data ke server. Silakan coba lagi.';
        }
    }
}

$current_page = 'register.php';
$page_title = "Daftar Akun — Traveloop";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $page_title ?></title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <link rel="stylesheet" href="../assets/css/style.css"/>

  <style>
    /* UI Enhancement sama dengan Halaman Login */
    .auth-card-modern {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .input-group-text {
        border-color: #e2e8f0;
    }
    .form-control:focus + .input-group-text,
    .input-group:focus-within .input-group-text {
        border-color: #f59e0b;
        color: #f59e0b !important;
    }
    .input-group:focus-within .form-control {
        border-color: #f59e0b;
        box-shadow: none;
    }
    .auth-input {
        border-color: #e2e8f0;
        font-size: 0.95rem;
    }
  </style>
</head>
<body class="auth-page" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh;">

<div class="auth-wrapper">
  <div class="auth-bg-pattern"></div>
  
  <div class="container position-relative z-1">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
      <div class="col-11 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        
        <div class="text-center mb-4 pb-2">
          <a href="../index.php" class="auth-logo text-decoration-none transition hover-zoom d-inline-block">
            <i class="bi bi-compass text-warning" style="font-size: 3rem; filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.5));"></i>
            <h2 class="fw-bold mt-2 mb-0" style="font-family: var(--ff-display); color: #ffffff; letter-spacing: 1px;">Traveloop</h2>
          </a>
        </div>

        <div class="card auth-card-modern shadow-lg rounded-4 overflow-hidden">
          <div class="card-header bg-transparent border-0 text-center pt-4 pb-0 px-4 px-sm-5">
            <h4 class="fw-bold text-dark mb-1">Buat Akun Baru</h4>
            <p class="text-muted small mb-0">Daftar untuk mengakses Smart Itinerary & Wishlist</p>
          </div>

          <div class="card-body p-4 p-sm-5 pt-4">
            
            <form method="POST" action="" autocomplete="off" id="registerForm">
              
              <div class="mb-3">
                <label class="form-label small fw-bold text-secondary mb-1">Nama Lengkap</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                  <span class="input-group-text bg-light border-end-0 px-3"><i class="bi bi-person-fill text-muted"></i></span>
                  <input type="text" name="nama_lengkap" class="form-control border-start-0 auth-input bg-light" 
                         placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($old_nama) ?>" required />
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-secondary mb-1">Alamat Email</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                  <span class="input-group-text bg-light border-end-0 px-3"><i class="bi bi-envelope-fill text-muted"></i></span>
                  <input type="email" name="email" class="form-control border-start-0 auth-input bg-light" 
                         placeholder="nama@email.com" value="<?= htmlspecialchars($old_email) ?>" required />
                </div>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="form-label small fw-bold text-secondary mb-1">Password</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                      <span class="input-group-text bg-light border-end-0 px-2"><i class="bi bi-lock-fill text-muted"></i></span>
                      <input type="password" name="password" id="regPassword" class="form-control border-start-0 border-end-0 auth-input bg-light" 
                             placeholder="Min. 6 Karakter" required minlength="6" />
                      <span class="input-group-text bg-light border-start-0 cursor-pointer px-2" onclick="togglePw('regPassword', this)">
                        <i class="bi bi-eye-slash text-muted transition hover-warning"></i>
                      </span>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <label class="form-label small fw-bold text-secondary mb-1">Konfirmasi</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                      <span class="input-group-text bg-light border-end-0 px-2"><i class="bi bi-shield-lock-fill text-muted"></i></span>
                      <input type="password" name="konfirmasi_password" id="regPasswordConfirm" class="form-control border-start-0 border-end-0 auth-input bg-light" 
                             placeholder="Ulangi Password" required minlength="6" />
                      <span class="input-group-text bg-light border-start-0 cursor-pointer px-2" onclick="togglePw('regPasswordConfirm', this)">
                        <i class="bi bi-eye-slash text-muted transition hover-warning"></i>
                      </span>
                    </div>
                </div>
              </div>

              <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill shadow-sm transition hover-zoom mt-2">
                Daftar Sekarang <i class="bi bi-person-plus-fill ms-2"></i>
              </button>
            </form>

            <div class="position-relative text-center my-4">
                <hr class="text-muted opacity-25">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">atau</span>
            </div>

            <div class="text-center">
              <span class="text-muted small">Sudah punya akun?</span>
              <a href="login.php" class="fw-bold text-primary text-decoration-none ms-1 small hover-warning transition">Login di sini</a>
            </div>

          </div>
        </div>

        <p class="text-center mt-4 pt-2">
          <a href="../index.php" class="text-white-50 text-decoration-none small transition hover-white">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
          </a>
        </p>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Toggle Password
function togglePw(id, el) {
  const input = document.getElementById(id);
  const icon = el.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
    icon.classList.add('text-warning');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
    icon.classList.remove('text-warning');
  }
}

// Integrasi SweetAlert2 untuk PHP Status
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Menampilkan Notifikasi Sukses dan Redirect ke Login
    <?php if ($success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Selamat Datang!',
            text: 'Akun Anda berhasil didaftarkan. Anda akan diarahkan ke halaman Login.',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'rounded-4'
            }
        }).then(() => {
            // Meneruskan parameter sukses agar halaman login juga menampilkan alert
            window.location.href = 'login.php?registered=1';
        });
    <?php endif; ?>

    // 2. Menampilkan Notifikasi Error
    <?php if (!empty($errors) && !$success): ?>
        let errorMessages = <?= json_encode(implode("<br>", $errors)) ?>;
        Swal.fire({
            icon: 'error',
            title: 'Pendaftaran Gagal',
            html: errorMessages,
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Perbaiki',
            customClass: {
                popup: 'rounded-4'
            }
        });
    <?php endif; ?>

    // Tambahkan animasi loading saat disubmit
    document.getElementById('registerForm').addEventListener('submit', function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendaftarkan...';
        btn.classList.add('disabled');
    });
});
</script>

</body>
</html>