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
}
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
  
  <link rel="stylesheet" href="assets/css/style.css"/>
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
        
        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
          <a class="btn btn-warning px-4 text-dark fw-bold" style="border-radius: 8px;" href="explore.php">Mulai Eksplorasi</a>
        </li>
      </ul>
    </div>
  </div>
</nav>