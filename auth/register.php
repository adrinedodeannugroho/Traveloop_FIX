<?php
// auth/register.php
require_once dirname(__DIR__) . '/config/koneksi.php';

$errors = [];
$success = false;
$old_nama = '';
$old_email = '';

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
        $errors[] = 'Email tidak valid.';
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
            $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
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
            $errors[] = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
        }
    }
}

// Deteksi halaman aktif untuk header
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
  
  <link rel="stylesheet" href="../assets/css/style.css"/>
</head>
<body class="auth-page">

<div class="auth-wrapper">
  <div class="auth-bg-pattern"></div>
  
  <div class="container position-relative z-1">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
      <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-4">
        
        <!-- Logo -->
        <div class="text-center mb-4">
          <a href="../index.php" class="auth-logo text-decoration-none">
            <i class="bi bi-compass text-warning fs-1"></i>
            <h3 class="fw-bold mt-2" style="font-family: var(--ff-display); color: #fff;">Traveloop</h3>
          </a>
        </div>

        <!-- Card Register -->
        <div class="auth-card">
          <div class="auth-card-header text-center">
            <h4 class="fw-bold mb-1"><i class="bi bi-person-plus me-2"></i>Buat Akun Baru</h4>
            <p class="text-muted small mb-0">Daftar untuk mulai menyimpan wishlist destinasi favoritmu</p>
          </div>

          <div class="auth-card-body">
            <?php if ($success): ?>
              <div class="alert alert-success border-0 shadow-sm rounded-3 text-center py-4">
                <i class="bi bi-check-circle-fill display-5 d-block mb-3 text-success"></i>
                <h6 class="fw-bold">Registrasi Berhasil! 🎉</h6>
                <p class="small text-muted mb-3">Akun kamu sudah terdaftar. Silakan login untuk melanjutkan.</p>
                <a href="login.php" class="btn btn-warning fw-bold rounded-pill px-4">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
                </a>
              </div>
            <?php else: ?>
              
              <?php if (!empty($errors)): ?>
                <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3">
                  <ul class="mb-0 ps-3 small">
                    <?php foreach ($errors as $err): ?>
                      <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <form method="POST" action="" autocomplete="off">
                <div class="mb-3">
                  <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="nama_lengkap" class="form-control border-start-0 auth-input" 
                           placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($old_nama) ?>" required />
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold text-muted">Email</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 auth-input" 
                           placeholder="contoh@email.com" value="<?= htmlspecialchars($old_email) ?>" required />
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold text-muted">Password</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" id="regPassword" class="form-control border-start-0 border-end-0 auth-input" 
                           placeholder="Minimal 6 karakter" required minlength="6" />
                    <span class="input-group-text bg-transparent border-start-0 cursor-pointer" onclick="togglePw('regPassword', this)">
                      <i class="bi bi-eye-slash text-muted"></i>
                    </span>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label small fw-bold text-muted">Konfirmasi Password</label>
                  <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                    <input type="password" name="konfirmasi_password" id="regPasswordConfirm" class="form-control border-start-0 border-end-0 auth-input" 
                           placeholder="Ulangi password" required minlength="6" />
                    <span class="input-group-text bg-transparent border-start-0 cursor-pointer" onclick="togglePw('regPasswordConfirm', this)">
                      <i class="bi bi-eye-slash text-muted"></i>
                    </span>
                  </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 auth-btn">
                  <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                </button>
              </form>
            <?php endif; ?>
          </div>

          <div class="auth-card-footer text-center">
            <span class="text-muted small">Sudah punya akun?</span>
            <a href="login.php" class="fw-bold text-warning text-decoration-none ms-1 small">Login di sini</a>
          </div>
        </div>

        <p class="text-center mt-4">
          <a href="../index.php" class="text-white-50 text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
          </a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
function togglePw(id, el) {
  const input = document.getElementById(id);
  const icon = el.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  } else {
    input.type = 'password';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
