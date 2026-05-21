<?php
// Pastikan file koneksi.php sudah dibuat
include 'koneksi.php';

// Menangkap parameter GET untuk fitur Search dan Filter
$search   = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$category = isset($_GET['cat']) ? mysqli_real_escape_string($koneksi, $_GET['cat']) : 'all';
$tag      = isset($_GET['tag']) ? mysqli_real_escape_string($koneksi, $_GET['tag']) : '';

// Menyusun Query Dasar
$sql = "SELECT * FROM destinasi WHERE 1=1";

// Menambahkan kondisi berdasarkan input filter
if (!empty($search)) {
    $sql .= " AND (nama LIKE '%$search%' OR alamat LIKE '%$search%')";
}
if (!empty($category) && $category != 'all') {
    $sql .= " AND kategori = '$category'";
}
if (!empty($tag) && $tag == 'hidden-gem') {
    $sql .= " AND tag = 'hidden-gem'";
}

// Eksekusi Query
$query_explore = mysqli_query($koneksi, $sql);
$total_results = mysqli_num_rows($query_explore);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Explore — Traveloop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body class="explore-page">

<nav class="navbar navbar-expand-lg wn-navbar wn-navbar-solid fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php">
      <span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="bi bi-info-circle me-1"></i>Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link active" href="explore.php"><i class="bi bi-compass me-1"></i>Explore</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="bi bi-envelope me-1"></i>Kontak</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="page-header-solid">
  <div class="container">
    <h1 class="page-title">Jelajahi Wisata Banyumas</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Explore</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">

    <div class="col-lg-3">
      <form action="explore.php" method="GET" class="filter-panel" id="filterPanel">
        <div class="filter-panel-header">
          <h5 class="filter-title mb-0"><i class="bi bi-sliders2 me-2"></i>Filters</h5>
          <a href="explore.php" class="btn-reset-filter text-decoration-none">Reset</a>
        </div>

        <div class="filter-section">
          <label class="filter-label">Search</label>
          <div class="filter-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" name="q" id="filterSearch" class="form-control filter-input" placeholder="Nama tempat…" value="<?= htmlspecialchars($search) ?>"/>
          </div>
        </div>

        <div class="filter-section">
          <label class="filter-label">Category</label>
          <div class="cat-filter-list">
            <input type="hidden" name="cat" id="catInput" value="<?= htmlspecialchars($category) ?>">
            <button type="button" class="cat-filter-btn <?= $category == 'all' ? 'active' : '' ?>" onclick="setCategory('all')">All</button>
            <button type="button" class="cat-filter-btn <?= $category == 'nature' ? 'active' : '' ?>" onclick="setCategory('nature')"><i class="bi bi-tree"></i> Nature</button>
            <button type="button" class="cat-filter-btn <?= $category == 'beach' ? 'active' : '' ?>" onclick="setCategory('beach')"><i class="bi bi-water"></i> Beach</button>
            <button type="button" class="cat-filter-btn <?= $category == 'mountain' ? 'active' : '' ?>" onclick="setCategory('mountain')"><i class="bi bi-snow2"></i> Mountain</button>
            <button type="button" class="cat-filter-btn <?= $category == 'cultural' ? 'active' : '' ?>" onclick="setCategory('cultural')"><i class="bi bi-building-fill"></i> Cultural</button>
            <button type="button" class="cat-filter-btn <?= $category == 'historical' ? 'active' : '' ?>" onclick="setCategory('historical')"><i class="bi bi-bank2"></i> Historical</button>
          </div>
        </div>

        <div class="filter-section">
            <label class="filter-label">Tipe</label>
            <div class="d-flex gap-2 flex-wrap">
              <input type="checkbox" name="tag" value="hidden-gem" id="gemCheckbox" class="d-none" <?= $tag == 'hidden-gem' ? 'checked' : '' ?>>
              <button type="button" class="cat-filter-btn w-100 <?= $tag == 'hidden-gem' ? 'active' : '' ?>" id="gemFilterBtn" onclick="toggleGemUI()">
                <i class="bi bi-gem me-1"></i> Hanya Hidden Gem
              </button>
            </div>
          </div>
          
          <button type="submit" class="btn btn-apply-filter w-100 mt-4">Terapkan Filter</button>
      </form>
    </div>

    <div class="col-lg-9">
      <div class="explore-toolbar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="results-info" id="resultsInfo">Menampilkan <?= $total_results ?> destinasi</div>
        
        <div class="toolbar-right d-flex gap-2 align-items-center">
          <button class="btn btn-itinerary-sm me-2" onclick="openItineraryModal()"><i class="bi bi-calendar-check me-1"></i>Itinerary 1 Hari</button>
          <button class="view-toggle active" id="gridToggle" onclick="setView('grid')" title="Grid View">
            <i class="bi bi-grid-3x3-gap"></i>
          </button>
          <button class="view-toggle" id="listToggle" onclick="setView('list')" title="List View">
            <i class="bi bi-list-ul"></i>
          </button>
        </div>
      </div>

      <div class="row g-4" id="placeGrid">
        <?php
        if ($total_results > 0) {
            while ($row = mysqli_fetch_assoc($query_explore)) {
                $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
                ?>
                <div class="col-12 col-md-6 col-xl-4">
                  <div class="place-card" onclick="window.location.href='detail.php?id=<?= $row['id'] ?>'">
                    <div class="place-card-img-wrap">
                      <img src="<?= $foto ?>" class="place-card-img" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                      <span class="cat-badge cat-badge-overlay"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['kategori']) ?></span>
                    </div>
                    <div class="place-card-body">
                      <h5 class="place-card-title"><?= htmlspecialchars($row['nama']) ?></h5>
                      <p class="place-card-addr"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                      <div class="place-card-footer">
                        <div class="stars-row"><i class="bi bi-star-fill star-fill"></i> <span class="rating-text ms-1"><?= $row['rating'] ?></span></div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center py-5">
                    <i class="bi bi-search display-4 text-muted mb-3"></i>
                    <p class="text-muted">Destinasi yang Anda cari tidak ditemukan.</p>
                  </div>';
        }
        ?>
      </div>
    </div>

  </div>
</div>

<footer class="wn-footer mt-5">
  <div class="container">
    <div class="footer-bottom">
      <p class="mb-0">© 2025 Traveloop — Wisata Banyumas. Built with Bootstrap 5 & PHP Native MySQL.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
  // Script untuk menangani sinkronisasi UI filter ke form input hidden PHP
  function setCategory(catName) {
      document.getElementById('catInput').value = catName;
      // Hapus class active dari semua tombol kategori
      let btns = document.querySelectorAll('.cat-filter-btn');
      btns.forEach(btn => {
          if(!btn.id) btn.classList.remove('active'); // Abaikan tombol hidden gem
      });
      // Beri efek aktif pada event target
      event.currentTarget.classList.add('active');
  }

  function toggleGemUI() {
      let btn = document.getElementById('gemFilterBtn');
      let checkbox = document.getElementById('gemCheckbox');
      btn.classList.toggle('active');
      checkbox.checked = btn.classList.contains('active');
  }

  window.addEventListener('DOMContentLoaded', () => {
    initNavScroll();
    // initExplorePage() & getUserLocation() dari script.js kemungkinan perlu di-disable 
    // jika fungsinya menimpa render grid dari PHP.
    // renderItineraryOptions();
  });
</script>
</body>
</html>