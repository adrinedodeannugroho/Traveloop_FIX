<?php
session_start();
// Pastikan path ke koneksi.php benar (sesuaikan jika admin.php ada di dalam folder /admin/)
include '../koneksi.php'; 

// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Untuk contoh, kita hardcode kredensial admin. 
    // Idealnya, Anda mengecek ke tabel `admin` di database: SELECT * FROM admin WHERE email='$email'
    if ($email === 'admin@traveloop.com' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Email atau Password salah!";
    }
}

// --- LOGIKA LOGOUT ---
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- MENGAMBIL STATISTIK JIKA SUDAH LOGIN ---
if (isset($_SESSION['admin_logged_in'])) {
    $jml_destinasi = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM destinasi"));
    $jml_pesan = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM pesan_kontak"));
    $jml_alam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM destinasi WHERE kategori='nature'"));
    $jml_gem = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM destinasi WHERE tag='hidden-gem'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin — Traveloop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="admin.css"/>
</head>
<body class="admin-body">

<?php if (!isset($_SESSION['admin_logged_in'])): ?>
<div id="loginScreen" class="admin-login-screen">
  <div class="login-card">
    <div class="login-brand mb-4"><i class="bi bi-compass"></i><span>Traveloop</span></div>
    <h4 class="login-title">Admin Panel</h4>
    <p class="login-sub">Sign in to manage destinations</p>
    
    <?php if(isset($login_error)): ?>
        <div class="alert alert-danger py-2 small"><?= $login_error ?></div>
    <?php endif; ?>

    <form method="POST" action="admin.php">
        <div class="mb-3 mt-4">
          <label class="form-label admin-label">Email</label>
          <input type="email" name="email" class="form-control admin-input" placeholder="admin@traveloop.com" required/>
        </div>
        <div class="mb-3">
          <label class="form-label admin-label">Password</label>
          <input type="password" name="password" class="form-control admin-input" placeholder="Password…" required/>
        </div>
        <button type="submit" name="login" class="btn btn-admin-primary w-100 mt-2">
          Sign In <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </form>
  </div>
</div>

<?php else: ?>
<div id="adminShell" class="admin-shell">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand"><i class="bi bi-compass"></i><span>Traveloop</span></div>
    <nav class="sidebar-nav">
      <a class="sidebar-link active" data-tab="dashboard" onclick="switchTab('dashboard',this)"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
      <a class="sidebar-link" data-tab="places" onclick="switchTab('places',this)"><i class="bi bi-geo-alt"></i><span>Places</span></a>
      <a class="sidebar-link" data-tab="messages" onclick="switchTab('messages',this)">
          <i class="bi bi-chat-dots"></i><span>Pesan Masuk</span>
          <span class="badge bg-danger ms-auto"><?= $jml_pesan ?></span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="../index.php" class="sidebar-link" target="_blank"><i class="bi bi-house"></i><span>View Site</span></a>
      <a href="admin.php?action=logout" class="sidebar-link"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
    </div>
  </aside>

  <main class="admin-main">
    <header class="admin-topbar">
      <button class="sidebar-toggle-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
      <h6 class="admin-page-title" id="pageTitle">Dashboard</h6>
      <div class="topbar-right">
        <span class="admin-user"><i class="bi bi-person-circle me-1"></i>Administrator</span>
      </div>
    </header>

    <div class="admin-content p-4">

      <div class="admin-tab active" id="tab-dashboard">
        <div class="row g-4 mb-5">
          <div class="col-6 col-lg-3">
              <div class="stat-card">
                  <div class="stat-card-icon" style="background:#fef3c7;color:#eab308"><i class="bi bi-geo-alt-fill"></i></div>
                  <div class="stat-card-num"><?= $jml_destinasi ?></div><div class="stat-card-label">Total Destinasi</div>
              </div>
          </div>
          <div class="col-6 col-lg-3">
              <div class="stat-card">
                  <div class="stat-card-icon" style="background:#e0f2fe;color:#0ea5e9"><i class="bi bi-tree"></i></div>
                  <div class="stat-card-num"><?= $jml_alam ?></div><div class="stat-card-label">Wisata Alam</div>
              </div>
          </div>
          <div class="col-6 col-lg-3">
              <div class="stat-card">
                  <div class="stat-card-icon" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-gem"></i></div>
                  <div class="stat-card-num"><?= $jml_gem ?></div><div class="stat-card-label">Hidden Gems</div>
              </div>
          </div>
        </div>
      </div>

      <div class="admin-tab" id="tab-places">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
          <div class="admin-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="admin-input-sm" placeholder="Cari destinasi (Visual Only)"/>
          </div>
          <button class="btn btn-admin-primary" onclick="openAddModal()">
            <i class="bi bi-plus-lg me-2"></i>Tambah Destinasi
          </button>
        </div>
        <div class="admin-card">
          <div class="admin-card-body p-0">
            <table class="table admin-table mb-0" id="placesTable">
              <thead><tr><th>Nama</th><th>Kategori</th><th>Lokasi</th><th>Rating</th><th>Aksi</th></tr></thead>
              <tbody>
                <?php
                $query_places = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($query_places)){
                    echo "<tr>
                            <td><strong>{$row['nama']}</strong></td>
                            <td><span class='cat-admin-badge'>{$row['kategori']}</span></td>
                            <td class='text-muted small'>{$row['alamat']}</td>
                            <td><span style='color:#eab308'>★</span> {$row['rating']}</td>
                            <td>
                                <button class='btn-icon-sm me-1' onclick='editPlace({$row['id']})'><i class='bi bi-pencil'></i></button>
                                <a href='proses_hapus.php?id={$row['id']}' class='btn-icon-sm del' onclick='return confirm(\"Yakin hapus destinasi ini?\")'><i class='bi bi-trash'></i></a>
                            </td>
                          </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="admin-tab" id="tab-messages">
        <h6 class="admin-card-title mb-4">Pesan dari Pengunjung</h6>
        <div class="admin-card">
            <div class="admin-card-body p-0">
                <table class="table admin-table mb-0">
                    <thead><tr><th>Tanggal</th><th>Nama</th><th>Email</th><th>Topik</th><th>Pesan</th></tr></thead>
                    <tbody>
                        <?php
                        $query_pesan = mysqli_query($koneksi, "SELECT * FROM pesan_kontak ORDER BY id DESC");
                        while($msg = mysqli_fetch_assoc($query_pesan)){
                            echo "<tr>
                                    <td>{$msg['tanggal']}</td>
                                    <td><strong>{$msg['nama']}</strong></td>
                                    <td>{$msg['email']}</td>
                                    <td>{$msg['topik']}</td>
                                    <td>{$msg['pesan']}</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
      </div>

    </div>
  </main>
</div>

<div class="modal fade" id="placeModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <form method="POST" action="proses_tambah.php" enctype="multipart/form-data" class="modal-content" style="border-radius:16px;border:none;">
      <div class="modal-header" style="border-bottom:1px solid var(--border);padding:1.25rem 1.5rem">
        <h5 class="modal-title admin-card-title mb-0" id="placeModalTitle">Tambah Destinasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label admin-label">Nama Destinasi *</label>
            <input type="text" name="nama" class="form-control admin-input" required/>
          </div>
          <div class="col-md-4">
            <label class="form-label admin-label">Kategori *</label>
            <select name="kategori" class="form-select admin-input">
              <option value="nature">Nature</option>
              <option value="beach">Beach</option>
              <option value="mountain">Mountain</option>
              <option value="historical">Historical</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label admin-label">Alamat Lengkap</label>
            <input type="text" name="alamat" class="form-control admin-input" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label admin-label">Rating (0-5)</label>
            <input type="number" name="rating" step="0.1" class="form-control admin-input"/>
          </div>
          <div class="col-12">
            <label class="form-label admin-label">URL Foto</label>
            <input type="url" name="foto_url" class="form-control admin-input" placeholder="https://..."/>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid var(--border)">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-admin-primary">
          <i class="bi bi-check2 me-1"></i>Simpan Destinasi
        </button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JavaScript sekarang HANYA digunakan untuk UI Toggle (Navigasi & Modal)
function switchTab(tab, link) {
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  link.classList.add('active');
  
  const titles = { dashboard: 'Dashboard', places: 'Kelola Destinasi', messages: 'Pesan Masuk' };
  document.getElementById('pageTitle').textContent = titles[tab] || tab;
}

function toggleSidebar() {
  document.getElementById('adminSidebar').classList.toggle('open');
}

function openAddModal() {
  new bootstrap.Modal(document.getElementById('placeModal')).show();
}

function editPlace(id) {
  // Nantinya bisa diarahkan ke edit.php?id=... atau fetch data via AJAX
  alert("Fungsi edit untuk ID: " + id + " akan diproses lewat PHP.");
}
</script>
</body>
</html>