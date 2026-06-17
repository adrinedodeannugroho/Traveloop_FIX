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
        $errors[] = 'Email tidak valid.';
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

        <!-- Card Login -->
        <div class="auth-card">
          <div class="auth-card-header text-center">
            <h4 class="fw-bold mb-1"><i class="bi bi-box-arrow-in-right me-2"></i>Selamat Datang!</h4>
            <p class="text-muted small mb-0">Masuk ke akunmu untuk mengakses wishlist wisata</p>
          </div>

          <div class="auth-card-body">
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3">
                <ul class="mb-0 ps-3 small">
                  <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
              <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 text-center">
                <i class="bi bi-check-circle-fill me-1"></i>
                <span class="small">Registrasi berhasil! Silakan login.</span>
              </div>
            <?php endif; ?>

            <form method="POST" action="" autocomplete="off">
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                  <input type="email" name="email" class="form-control border-start-0 auth-input" 
                         placeholder="contoh@email.com" value="<?= htmlspecialchars($old_email) ?>" required />
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-lock text-muted"></i></span>
                  <input type="password" name="password" id="loginPassword" class="form-control border-start-0 border-end-0 auth-input" 
                         placeholder="Masukkan password" required />
                  <span class="input-group-text bg-transparent border-start-0 cursor-pointer" onclick="togglePw('loginPassword', this)">
                    <i class="bi bi-eye-slash text-muted"></i>
                  </span>
                </div>
              </div>

              <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 auth-btn">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
              </button>
            </form>
          </div>

          <div class="auth-card-footer text-center">
            <span class="text-muted small">Belum punya akun?</span>
            <a href="register.php" class="fw-bold text-warning text-decoration-none ms-1 small">Daftar Gratis</a>
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
