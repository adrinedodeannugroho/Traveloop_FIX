<?php
// includes/header.php

// 1. Pemanggilan koneksi menggunakan path absolut yang aman (__DIR__)
// Memastikan koneksi tidak error meskipun header dipanggil dari kedalaman folder yang berbeda
require_once dirname(__DIR__) . '/config/koneksi.php';

// 2. Logika Deteksi Halaman Aktif & Dynamic Title
$current_page = basename($_SERVER['PHP_SELF']);
$page_title = "Traveloop — Jelajahi Wisata Barlingmascakeb";

if ($current_page == 'explore.php' || $current_page == 'detail.php') {
    $page_title = "Explore Destinasi — Traveloop";
} elseif ($current_page == 'about.php') {
    $page_title = "Tentang Kami — Traveloop";
} elseif ($current_page == 'contact.php') {
    $page_title = "Kontak & Kemitraan — Traveloop";
} elseif ($current_page == 'wishlist.php') {
    $page_title = "Wishlist Saya — Traveloop";
} elseif ($current_page == 'itinerary_saya.php') {
    $page_title = "Itinerary Saya — Traveloop";
}

// 3. Cek status login user
$is_user_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_nama = $is_user_logged_in ? ($_SESSION['user_nama'] ?? 'User') : '';

// 4. Hitung jumlah wishlist jika sudah login
$wishlist_count = 0;
if ($is_user_logged_in) {
    $uid = (int)$_SESSION['user_id'];
    $wq = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wishlist WHERE user_id = $uid");
    if ($wq) {
        $wishlist_count = (int)mysqli_fetch_assoc($wq)['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
  <title><?= $page_title ?></title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  
  <link rel="stylesheet" href="assets/css/style.css"/>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    const TRAVELOOP_USER = {
      isLoggedIn: <?= $is_user_logged_in ? 'true' : 'false' ?>,
      userId: <?= $is_user_logged_in ? (int)$_SESSION['user_id'] : 'null' ?>,
      nama: <?= $is_user_logged_in ? '"' . addslashes($user_nama) . '"' : 'null' ?>
    };
  </script>
</head>
<body>

<nav class="navbar navbar-expand-lg wn-navbar fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php">
      <span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                <i class="bi bi-house me-1"></i>Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'explore.php' || $current_page == 'detail.php') ? 'active' : ''; ?>" href="explore.php">
                <i class="bi bi-compass me-1"></i>Explore
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">
                <i class="bi bi-info-circle me-1"></i>Tentang Kami
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">
                <i class="bi bi-envelope me-1"></i>Kemitraan
            </a>
        </li>

        <?php if ($is_user_logged_in): ?>
        <li class="nav-item">
            <a class="nav-link position-relative <?= ($current_page == 'wishlist.php') ? 'active' : ''; ?>" href="wishlist.php">
                <i class="bi bi-heart<?= ($current_page == 'wishlist.php') ? '-fill text-danger' : '' ?> me-1"></i>Wishlist
                <?php if ($wishlist_count > 0): ?>
                  <span class="badge bg-danger rounded-pill wishlist-badge-nav" id="navWishlistBadge"><?= $wishlist_count ?></span>
                <?php else: ?>
                  <span class="badge bg-danger rounded-pill wishlist-badge-nav d-none" id="navWishlistBadge">0</span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'itinerary_saya.php') ? 'active' : ''; ?>" href="itinerary_saya.php">
                <i class="bi bi-map<?= ($current_page == 'itinerary_saya.php') ? '-fill text-warning' : '' ?> me-1"></i>Itinerary Saya
            </a>
        </li>
        <?php endif; ?>
        
        <?php if ($is_user_logged_in): ?>
          <li class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0">
            <a class="btn btn-outline-warning px-3 text-dark fw-bold dropdown-toggle user-nav-btn" 
               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
               style="border-radius: 8px;">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user_nama) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2 user-dropdown">
              <li>
                <div class="dropdown-header px-3 py-2">
                  <p class="mb-0 fw-bold text-dark"><?= htmlspecialchars($user_nama) ?></p>
                  <p class="mb-0 small text-muted"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item px-3 py-2" href="wishlist.php">
                  <i class="bi bi-heart text-danger me-2"></i>Wishlist Saya
                  <?php if ($wishlist_count > 0): ?>
                    <span class="badge bg-danger rounded-pill float-end"><?= $wishlist_count ?></span>
                  <?php endif; ?>
                </a>
              </li>
              <li>
                <a class="dropdown-item px-3 py-2 fw-bold" href="itinerary_saya.php">
                  <i class="bi bi-map text-warning me-2"></i>Itinerary Saya
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item px-3 py-2 text-danger" href="auth/logout.php">
                  <i class="bi bi-box-arrow-right me-2"></i>Keluar
                </a>
              </li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item ms-lg-3 mt-3 mt-lg-0 d-flex gap-2">
            <a class="btn btn-outline-warning px-3 fw-bold" style="border-radius: 8px;" href="auth/login.php">
              <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </a>
            <a class="btn btn-warning px-3 text-dark fw-bold" style="border-radius: 8px;" href="auth/register.php">
              <i class="bi bi-person-plus me-1"></i>Daftar
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>