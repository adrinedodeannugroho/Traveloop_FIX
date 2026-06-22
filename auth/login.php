<?php
// auth/login.php
require_once dirname(__DIR__) . '/config/koneksi.php';

$errors = [];
$old_email = '';

// Jika sudah login, redirect ke index
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $old_email = $email;

    // Validasi
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if (empty($password)) {
        $errors[] = 'Password wajib diisi.';
    }

    // Cek di database
    if (empty($errors)) {
        $email_safe = mysqli_real_escape_string($koneksi, $email);
        $q = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email_safe' LIMIT 1");
        
        if ($q && mysqli_num_rows($q) > 0) {
            $user = mysqli_fetch_assoc($q);
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nama'] = $user['nama_lengkap'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect ke halaman sebelumnya atau index
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.php';
                header("Location: " . $redirect);
                exit();
            } else {
                $errors[] = 'Password yang Anda masukkan salah.';
            }
        } else {
            $errors[] = 'Email tidak terdaftar. Silakan daftar terlebih dahulu.';
        }
    }
}

$current_page = 'login.php';
$page_title = "Login — Traveloop";
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
    /* Tambahan UI/UX Enhancement untuk Halaman Login */
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
      <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
        
        <div class="text-center mb-4 pb-2">
          <a href="../index.php" class="auth-logo text-decoration-none transition hover-zoom d-inline-block">
            <i class="bi bi-compass text-warning" style="font-size: 3rem; filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.5));"></i>
            <h2 class="fw-bold mt-2 mb-0" style="font-family: var(--ff-display); color: #ffffff; letter-spacing: 1px;">Traveloop</h2>
          </a>
        </div>

        <div class="card auth-card-modern shadow-lg rounded-4 overflow-hidden">
          <div class="card-header bg-transparent border-0 text-center pt-4 pb-0 px-4 px-sm-5">
            <h4 class="fw-bold text-dark mb-1">Selamat Datang Kembali</h4>
            <p class="text-muted small mb-0">Masuk untuk melanjutkan petualanganmu</p>
          </div>

          <div class="card-body p-4 p-sm-5 pt-4">
            
            <form method="POST" action="" autocomplete="off" id="loginForm">
              
              <div class="mb-4">
                <label class="form-label small fw-bold text-secondary mb-1">Alamat Email</label>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                  <span class="input-group-text bg-light border-end-0 px-3"><i class="bi bi-envelope-fill text-muted"></i></span>
                  <input type="email" name="email" class="form-control border-start-0 auth-input bg-light" 
                         placeholder="nama@email.com" value="<?= htmlspecialchars($old_email) ?>" required />
                </div>
              </div>

              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label small fw-bold text-secondary mb-0">Password</label>
                    <a href="#" class="small text-decoration-none text-muted hover-warning" onclick="Swal.fire('Fitur Lupa Password', 'Silakan hubungi Admin untuk mereset password Anda.', 'info')">Lupa?</a>
                </div>
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                  <span class="input-group-text bg-light border-end-0 px-3"><i class="bi bi-lock-fill text-muted"></i></span>
                  <input type="password" name="password" id="loginPassword" class="form-control border-start-0 border-end-0 auth-input bg-light" 
                         placeholder="••••••••" required />
                  <span class="input-group-text bg-light border-start-0 cursor-pointer px-3" onclick="togglePw('loginPassword', this)">
                    <i class="bi bi-eye-slash text-muted transition hover-warning"></i>
                  </span>
                </div>
              </div>

              <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill shadow-sm transition hover-zoom mt-2">
                Masuk <i class="bi bi-arrow-right ms-2"></i>
              </button>
            </form>

            <div class="position-relative text-center my-4">
                <hr class="text-muted opacity-25">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">atau</span>
            </div>

            <div class="text-center">
              <span class="text-muted small">Belum punya akun wisata?</span>
              <a href="register.php" class="fw-bold text-primary text-decoration-none ms-1 small hover-warning transition">Daftar Sekarang</a>
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

// Integrasi SweetAlert2 untuk Notifikasi PHP
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Menampilkan Error Login
    <?php if (!empty($errors)): ?>
        let errorMessages = <?= json_encode(implode("<br>", $errors)) ?>;
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
            html: errorMessages,
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Coba Lagi',
            customClass: {
                popup: 'rounded-4'
            }
        });
    <?php endif; ?>

    // 2. Menampilkan Sukses Registrasi
    <?php if (isset($_GET['registered'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Registrasi Berhasil!',
            text: 'Akun Anda sudah aktif. Silakan masuk menggunakan email dan password yang telah didaftarkan.',
            confirmButtonColor: '#198754',
            confirmButtonText: 'Oke, Mengerti',
            customClass: {
                popup: 'rounded-4'
            }
        });
        
        // Membersihkan URL dari parameter ?registered agar alert tidak muncul terus saat direfresh
        window.history.replaceState({}, document.title, "login.php");
    <?php endif; ?>

    // Tambahkan animasi loading saat tombol disubmit
    document.getElementById('loginForm').addEventListener('submit', function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        btn.classList.add('disabled');
    });
});
</script>

</body>
</html>