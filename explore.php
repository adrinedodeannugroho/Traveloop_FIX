<?php
// explore.php
// Memanggil Header yang sudah memuat koneksi.php dan seluruh tag <head> beserta Navbar
require_once 'includes/header.php';

// Menangkap parameter GET untuk fitur Search dan Filter dengan aman
$search   = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$category = isset($_GET['cat']) ? mysqli_real_escape_string($koneksi, $_GET['cat']) : 'all';
$tag      = isset($_GET['tag']) ? mysqli_real_escape_string($koneksi, $_GET['tag']) : '';

// Menyusun Query Dasar
$sql = "SELECT * FROM destinasi WHERE 1=1";

// 1. Filter Pencarian Teks
if (!empty($search)) {
    $sql .= " AND (nama LIKE '%$search%' OR alamat LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}

// 2. Filter Kategori (Pemetaan nilai bahasa Inggris ke format Database Bahasa Indonesia)
if (!empty($category) && $category != 'all') {
    $cat_map = [
        'nature' => 'Alam',
        'beach' => 'Pantai',
        'mountain' => 'Gunung',
        'cultural' => 'Budaya',
        'historical' => 'Sejarah'
    ];
    $db_cat = isset($cat_map[$category]) ? $cat_map[$category] : $category;
    $sql .= " AND kategori = '$db_cat'";
}

// 3. Filter Tag Tambahan (Hidden Gem)
if (!empty($tag) && $tag == 'hidden-gem') {
    $sql .= " AND (deskripsi LIKE '%hidden gem%' OR deskripsi LIKE '%Hidden Gem%')";
}

// Urutkan dari yang terbaru ditambahkan
$sql .= " ORDER BY id DESC";

// Eksekusi Query dengan Error Handling
$query_explore = mysqli_query($koneksi, $sql);
$total_results = $query_explore ? mysqli_num_rows($query_explore) : 0;
?>

<style>
  /* CSS Tambahan Khusus untuk Tampilan List View */
  .list-view .place-item-col { width: 100%; }
  .list-view .place-card { display: flex; flex-direction: row; align-items: stretch; }
  .list-view .place-card-img-wrap { width: 320px; flex-shrink: 0; border-radius: 16px 0 0 16px !important; }
  .list-view .place-card-body { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
  @media (max-width: 768px) {
      .list-view .place-card { flex-direction: column; }
      .list-view .place-card-img-wrap { width: 100%; height: 200px; border-radius: 16px 16px 0 0 !important; }
  }
</style>

<div class="page-header-solid" style="padding-top: 120px;">
  <div class="container text-center">
    <h1 class="page-title text-white">Jelajahi Wisata Barlingmascakeb</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb wn-breadcrumb justify-content-center mt-3">
        <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-none opacity-75 hover-white">Home</a></li>
        <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">Explore</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5 explore-page bg-soft rounded-top-5 shadow-sm" style="margin-top: -30px; position: relative; z-index: 10;">
  <div class="row g-4">

    <div class="col-lg-3">
      <form action="explore.php" method="GET" class="filter-panel p-4 shadow-sm rounded-4 bg-white border-0 sticky-lg-top" id="filterPanel" style="top: 100px;">
        <div class="filter-panel-header bg-white px-0 border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
          <h5 class="filter-title text-dark mb-0 fw-bold"><i class="bi bi-sliders2 text-warning me-2"></i>Filter</h5>
          <a href="explore.php" class="btn-reset-filter text-decoration-none text-muted small"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
        </div>

        <div class="filter-section px-0 border-0 mb-4">
          <label class="filter-label text-muted small fw-bold text-uppercase tracking-wider">Cari Destinasi</label>
          <div class="filter-search-wrap mt-2">
            <i class="bi bi-search ps-2"></i>
            <input type="text" name="q" id="filterSearch" class="form-control filter-input bg-light border-0" placeholder="Nama tempat..." value="<?= htmlspecialchars($search) ?>"/>
          </div>
        </div>

        <div class="filter-section px-0 border-0 mb-4">
          <label class="filter-label text-muted small fw-bold text-uppercase tracking-wider">Kategori</label>
          <div class="cat-filter-list mt-2">
            <input type="hidden" name="cat" id="catInput" value="<?= htmlspecialchars($category) ?>">
            
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'all' ? 'active' : '' ?>" onclick="setCategory('all', this)">
              <i class="bi bi-grid-fill text-muted me-2"></i>Semua Kategori
            </button>
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'nature' ? 'active' : '' ?>" onclick="setCategory('nature', this)">
              <i class="bi bi-tree text-success me-2"></i>Alam & Hutan
            </button>
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'beach' ? 'active' : '' ?>" onclick="setCategory('beach', this)">
              <i class="bi bi-water text-info me-2"></i>Pantai & Laut
            </button>
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'mountain' ? 'active' : '' ?>" onclick="setCategory('mountain', this)">
              <i class="bi bi-snow2 text-secondary me-2"></i>Pegunungan
            </button>
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'cultural' ? 'active' : '' ?>" onclick="setCategory('cultural', this)">
              <i class="bi bi-building-fill text-warning me-2"></i>Seni & Budaya
            </button>
            <button type="button" class="cat-filter-btn mb-1 <?= $category == 'historical' ? 'active' : '' ?>" onclick="setCategory('historical', this)">
              <i class="bi bi-bank2 text-danger me-2"></i>Situs Sejarah
            </button>
          </div>
        </div>

        <div class="filter-section px-0 border-0 mb-4">
          <label class="filter-label text-muted small fw-bold text-uppercase tracking-wider">Tipe Eksklusif</label>
          <div class="d-flex gap-2 flex-wrap mt-2">
            <input type="checkbox" name="tag" value="hidden-gem" id="gemCheckbox" class="d-none" <?= $tag == 'hidden-gem' ? 'checked' : '' ?>>
            <button type="button" class="cat-filter-btn w-100 <?= $tag == 'hidden-gem' ? 'active text-white bg-primary border-primary' : 'bg-light border-0' ?>" id="gemFilterBtn" onclick="toggleGemUI()">
              <i class="bi bi-gem me-2 <?= $tag == 'hidden-gem' ? 'text-white' : 'text-primary' ?>"></i> Hanya Hidden Gem
            </button>
          </div>
        </div>
        
        <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm">Terapkan Filter</button>
      </form>
    </div>

    <div class="col-lg-9">
      <div class="explore-toolbar bg-white shadow-sm rounded-4 p-3 mb-4 border-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="results-info text-muted">
          Menemukan <strong class="text-dark fs-5 mx-1"><?= $total_results ?></strong> destinasi
        </div>
        
        <div class="toolbar-right d-flex gap-2 align-items-center">
          <button class="btn btn-primary fw-bold px-3 me-2" onclick="showItineraryModal()"><i class="bi bi-magic me-1"></i> Buat Itinerary</button>
          <button class="view-toggle active bg-light border-0 text-dark" id="gridToggle" onclick="toggleViewLayout('grid')" title="Grid View">
            <i class="bi bi-grid-3x3-gap"></i>
          </button>
          <button class="view-toggle bg-light border-0 text-muted" id="listToggle" onclick="toggleViewLayout('list')" title="List View">
            <i class="bi bi-list-ul"></i>
          </button>
        </div>
      </div>

      <div class="row g-4" id="placeGrid">
        <?php
        if ($query_explore && $total_results > 0) {
            while ($row = mysqli_fetch_assoc($query_explore)) {
                $foto = !empty($row['foto_url']) ? $row['foto_url'] : 'https://placehold.co/600x400/e2e8f0/64748b?text=Wisata';
                $isGem = (stripos($row['deskripsi'], 'hidden gem') !== false);
                ?>
                <div class="col-12 col-md-6 col-xl-4 place-item-col">
                  
                  <div class="place-card h-100 shadow-sm border-0" 
                       data-id="<?= $row['id'] ?>"
                       data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>"
                       data-kategori="<?= htmlspecialchars($row['kategori'], ENT_QUOTES) ?>"
                       data-alamat="<?= htmlspecialchars($row['alamat'], ENT_QUOTES) ?>"
                       data-rating="<?= $row['rating'] ?>"
                       data-deskripsi="<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES) ?>"
                       data-foto="<?= htmlspecialchars($foto, ENT_QUOTES) ?>"
                       data-maps="<?= htmlspecialchars($row['maps_url'] ?? '', ENT_QUOTES) ?>"
                       data-tarif="<?= htmlspecialchars($row['tarif'] ?? '', ENT_QUOTES) ?>"
                       data-kontak="<?= htmlspecialchars($row['kontak'] ?? '', ENT_QUOTES) ?>"
                       data-history="<?= htmlspecialchars($row['history'] ?? '', ENT_QUOTES) ?>"
                       data-tips="<?= htmlspecialchars($row['tips'] ?? '', ENT_QUOTES) ?>"
                       onclick="openDetailBtn(this)" 
                       style="cursor:pointer;">
                    
                    <div class="place-card-img-wrap rounded-top-4">
                      <img src="<?= $foto ?>" class="place-card-img" alt="<?= htmlspecialchars($row['nama']) ?>" loading="lazy"/>
                      <span class="cat-badge cat-badge-overlay shadow-sm"><i class="bi bi-geo-alt me-1 text-warning"></i><?= htmlspecialchars($row['kategori']) ?></span>
                      <?php if($isGem): ?>
                        <span class="gem-badge shadow-sm"><i class="bi bi-gem me-1"></i>Hidden Gem</span>
                      <?php endif; ?>
                    </div>
                    
                    <div class="place-card-body">
                      <h5 class="place-card-title text-dark"><?= htmlspecialchars($row['nama']) ?></h5>
                      <p class="place-card-addr text-muted"><i class="bi bi-pin-map me-1"></i><?= htmlspecialchars($row['alamat']) ?></p>
                      
                      <div class="place-card-footer mt-3 pt-3 border-top border-light d-flex justify-content-between align-items-center">
                        <div class="stars-row">
                          <i class="bi bi-star-fill star-fill text-warning"></i> 
                          <span class="rating-text ms-1 fw-bold"><?= $row['rating'] ?></span>
                        </div>
                        <span class="text-warning small fw-bold">Lihat Detail <i class="bi bi-arrow-right"></i></span>
                      </div>
                    </div>

                  </div>
                </div>
                <?php
            }
        } else {
            // Tampilan Kosong/Error Profesional
            $errorData = mysqli_error($koneksi);
            $msg = $errorData ? "Error Database: " . $errorData : "Destinasi yang Anda cari belum tersedia di area Barlingmascakeb.";
            echo '
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border-0 mt-2">
              <i class="bi bi-compass display-1 text-muted opacity-25 mb-4 d-block"></i>
              <h4 class="text-dark fw-bold">Destinasi Tidak Ditemukan</h4>
              <p class="text-muted mb-4">' . $msg . '</p>
              <a href="explore.php" class="btn btn-warning fw-bold px-4">Tampilkan Semua Destinasi</a>
            </div>';
        }
        ?>
      </div>
    </div>

  </div>
</div>

<script>
  // ---------------------------------------------------------
  // LOGIKA FILTER UI
  // ---------------------------------------------------------
  function setCategory(catName, btnEl) {
      document.getElementById('catInput').value = catName;
      let btns = document.querySelectorAll('.cat-filter-btn:not(#gemFilterBtn)');
      btns.forEach(btn => btn.classList.remove('active'));
      
      if (btnEl) {
          btnEl.classList.add('active');
      } else if (typeof event !== 'undefined' && event.currentTarget) {
          event.currentTarget.classList.add('active');
      }
      
      document.getElementById('filterPanel').submit();
  }

  function toggleGemUI() {
      let btn = document.getElementById('gemFilterBtn');
      let checkbox = document.getElementById('gemCheckbox');
      
      btn.classList.toggle('active');
      btn.classList.toggle('text-white');
      btn.classList.toggle('bg-primary');
      btn.classList.toggle('border-primary');
      btn.classList.toggle('bg-light');
      
      let icon = btn.querySelector('i');
      icon.classList.toggle('text-white');
      icon.classList.toggle('text-primary');
      
      checkbox.checked = btn.classList.contains('active');
      document.getElementById('filterPanel').submit();
  }
  
  document.getElementById('filterSearch')?.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
          e.preventDefault();
          document.getElementById('filterPanel').submit();
      }
  });

  // ---------------------------------------------------------
  // LOGIKA GRID / LIST VIEW
  // ---------------------------------------------------------
  function toggleViewLayout(viewType) {
      const grid = document.getElementById('placeGrid');
      const gridBtn = document.getElementById('gridToggle');
      const listBtn = document.getElementById('listToggle');
      const cols = document.querySelectorAll('.place-item-col');

      if (viewType === 'list') {
          grid.classList.add('list-view');
          
          listBtn.classList.add('active', 'text-dark');
          listBtn.classList.remove('text-muted');
          gridBtn.classList.remove('active', 'text-dark');
          gridBtn.classList.add('text-muted');
          
          cols.forEach(el => el.className = 'col-12 place-item-col');
      } else {
          grid.classList.remove('list-view');
          
          gridBtn.classList.add('active', 'text-dark');
          gridBtn.classList.remove('text-muted');
          listBtn.classList.remove('active', 'text-dark');
          listBtn.classList.add('text-muted');
          
          cols.forEach(el => el.className = 'col-12 col-md-6 col-xl-4 place-item-col');
      }
  }
</script>

<?php
// Memanggil Footer (Mengandung penutup body, html, dan seluruh pemanggilan JS assets)
require_once 'includes/footer.php';
?>