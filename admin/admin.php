<?php
session_start();
include '../koneksi.php'; 

// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

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
    
    $query_pesan = @mysqli_query($koneksi, "SELECT id FROM pesan_kontak");
    $jml_pesan = $query_pesan ? mysqli_num_rows($query_pesan) : 0;
    
    $jml_alam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM destinasi WHERE kategori='nature'"));
    $jml_gem = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM destinasi WHERE deskripsi LIKE '%hidden gem%'"));
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
      <?php if(isset($_GET['status'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i>
              <?php 
                  if($_GET['status'] == 'success_tambah') echo "Destinasi berhasil ditambahkan!";
                  elseif($_GET['status'] == 'success_edit') echo "Destinasi berhasil diperbarui!";
                  elseif($_GET['status'] == 'success_hapus') echo "Destinasi berhasil dihapus!";
              ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
      <?php endif; ?>

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
            <div class="table-responsive">
              <table class="table admin-table mb-0" id="placesTable">
                <thead><tr><th>Nama</th><th>Kategori</th><th>Lokasi</th><th>Rating</th><th>Aksi</th></tr></thead>
                <tbody>
                  <?php
                  $query_places = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
                  while($row = mysqli_fetch_assoc($query_places)){
                      $id = $row['id'];
                      $nama = htmlspecialchars($row['nama'], ENT_QUOTES); 
                      $kategori = $row['kategori'];
                      $alamat = htmlspecialchars($row['alamat'], ENT_QUOTES); 
                      $rating = $row['rating'];
                      $deskripsi = htmlspecialchars(isset($row['deskripsi']) ? $row['deskripsi'] : '', ENT_QUOTES);
                      $foto = isset($row['foto_url']) ? $row['foto_url'] : '';
                      
                      // Membaca Kolom Detail Baru
                      $maps_url = htmlspecialchars(isset($row['maps_url']) ? $row['maps_url'] : '', ENT_QUOTES);
                      $kontak = htmlspecialchars(isset($row['kontak']) ? $row['kontak'] : '', ENT_QUOTES);
                      $tarif = htmlspecialchars(isset($row['tarif']) ? $row['tarif'] : '', ENT_QUOTES);
                      $history = htmlspecialchars(isset($row['history']) ? $row['history'] : '', ENT_QUOTES);
                      $tips = htmlspecialchars(isset($row['tips']) ? $row['tips'] : '', ENT_QUOTES);

                      echo "<tr>
                              <td><strong>{$nama}</strong></td>
                              <td><span class='cat-admin-badge'>{$kategori}</span></td>
                              <td class='text-muted small'>{$alamat}</td>
                              <td><span style='color:#eab308'>★</span> {$rating}</td>
                              <td>
                                  <button class='btn-icon-sm me-1 btn-primary' style='background: none; border: none; color: #0ea5e9;' 
                                          data-id='{$id}'
                                          data-nama='{$nama}'
                                          data-kategori='{$kategori}'
                                          data-alamat='{$alamat}'
                                          data-rating='{$rating}'
                                          data-deskripsi='{$deskripsi}'
                                          data-foto='{$foto}'
                                          data-maps='{$maps_url}'
                                          data-kontak='{$kontak}'
                                          data-tarif='{$tarif}'
                                          data-history='{$history}'
                                          data-tips='{$tips}'
                                          onclick='openEditModal(this)'>
                                      <i class='bi bi-pencil'></i>
                                  </button>
                                  <a href='../koneksi.php?action=hapus&id={$id}' class='btn-icon-sm del text-danger' style='text-decoration: none;' 
                                     onclick='return confirm(\"Yakin hapus destinasi {$nama}?\")'>
                                      <i class='bi bi-trash'></i>
                                  </a>
                              </td>
                            </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="admin-tab" id="tab-messages">
        <h6 class="admin-card-title mb-4">Pesan dari Pengunjung</h6>
        <div class="admin-card">
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead><tr><th>Tanggal</th><th>Nama</th><th>Email</th><th>Topik</th><th>Pesan</th></tr></thead>
                        <tbody>
                            <?php
                            $query_pesan = @mysqli_query($koneksi, "SELECT * FROM pesan_kontak ORDER BY id DESC");
                            if($query_pesan){
                                while($msg = mysqli_fetch_assoc($query_pesan)){
                                    echo "<tr>
                                            <td>{$msg['tanggal']}</td>
                                            <td><strong>{$msg['nama']}</strong></td>
                                            <td>{$msg['email']}</td>
                                            <td>{$msg['topik']}</td>
                                            <td>{$msg['pesan']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada pesan atau tabel pesan_kontak belum ada.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </main>
</div>

<div class="modal fade" id="placeModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <form method="POST" action="../koneksi.php" enctype="multipart/form-data" class="modal-content" style="border-radius:16px;border:none;">
      <input type="hidden" name="action" id="formAction" value="tambah">
      <input type="hidden" name="id" id="formId" value="">
      <input type="hidden" name="foto_url_lama" id="formFotoLama" value="">

      <div class="modal-header" style="border-bottom:1px solid var(--border);padding:1.25rem 1.5rem">
        <h5 class="modal-title admin-card-title mb-0" id="placeModalTitle">Tambah Destinasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label admin-label">Nama Destinasi *</label>
            <input type="text" name="nama" id="formNama" class="form-control admin-input" required/>
          </div>
          <div class="col-md-4">
            <label class="form-label admin-label">Kategori *</label>
            <select name="kategori" id="formKategori" class="form-select admin-input">
              <option value="nature">Nature</option>
              <option value="beach">Beach</option>
              <option value="mountain">Mountain</option>
              <option value="historical">Historical</option>
            </select>
          </div>
          <div class="col-md-9">
            <label class="form-label admin-label">Alamat Lengkap *</label>
            <input type="text" name="alamat" id="formAlamat" class="form-control admin-input" required/>
          </div>
          <div class="col-md-3">
            <label class="form-label admin-label">Rating (0-5)</label>
            <input type="number" name="rating" id="formRating" step="0.1" max="5" class="form-control admin-input"/>
          </div>

          <div class="col-md-6">
            <label class="form-label admin-label">Link Google Maps (URL) / Iframe</label>
            <input type="text" name="maps_url" id="formMaps" class="form-control admin-input" placeholder="https://maps.google.com/..."/>
          </div>
          <div class="col-md-3">
            <label class="form-label admin-label">Kontak Pengelola</label>
            <input type="text" name="kontak" id="formKontak" class="form-control admin-input" placeholder="0812xxxx / @Instagram"/>
          </div>
          <div class="col-md-3">
            <label class="form-label admin-label">Tarif Tiket Masuk</label>
            <input type="text" name="tarif" id="formTarif" class="form-control admin-input" placeholder="Misal: Rp 15.000 / Gratis"/>
          </div>

          <div class="col-12">
            <label class="form-label admin-label">Deskripsi Ringkas</label>
            <textarea name="deskripsi" id="formDeskripsi" class="form-control admin-input" rows="3"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label admin-label">Sejarah / Latar Belakang Lengkap</label>
            <textarea name="history" id="formHistory" class="form-control admin-input" rows="3" placeholder="Ceritakan sejarah atau info detail destinasi di sini..."></textarea>
          </div>
          <div class="col-12">
            <label class="form-label admin-label">Tips Berkunjung Pengunjung</label>
            <textarea name="tips" id="formTips" class="form-control admin-input" rows="3" placeholder="Contoh: Waktu terbaik datang jam 4 sore, bawa baju ganti, dll..."></textarea>
          </div>

          <div class="col-12">
            <label class="form-label admin-label">Upload Foto <small class="text-muted">(Biarkan kosong saat edit jika tidak ingin ganti foto)</small></label>
            <input type="file" name="foto_file" id="formFotoFile" class="form-control admin-input" accept="image/png, image/jpeg, image/jpg, image/webp"/>
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

// --- LOGIKA MODAL TAMBAH DATA ---
function openAddModal() {
  document.getElementById('placeModalTitle').textContent = "Tambah Destinasi";
  document.getElementById('formAction').value = "tambah";
  
  document.getElementById('formId').value = "";
  document.getElementById('formNama').value = "";
  document.getElementById('formKategori').value = "nature";
  document.getElementById('formAlamat').value = "";
  document.getElementById('formRating').value = "";
  document.getElementById('formDeskripsi').value = "";
  
  // Kosongkan input baru
  document.getElementById('formMaps').value = "";
  document.getElementById('formKontak').value = "";
  document.getElementById('formTarif').value = "";
  document.getElementById('formHistory').value = "";
  document.getElementById('formTips').value = "";

  document.getElementById('formFotoFile').required = true; 
  new bootstrap.Modal(document.getElementById('placeModal')).show();
}

// --- LOGIKA MODAL EDIT DATA (Membaca HTML5 Data Attributes) ---
function openEditModal(btn) {
  document.getElementById('placeModalTitle').textContent = "Edit Destinasi";
  document.getElementById('formAction').value = "edit";
  
  document.getElementById('formId').value = btn.getAttribute('data-id');
  document.getElementById('formNama').value = btn.getAttribute('data-nama');
  document.getElementById('formKategori').value = btn.getAttribute('data-kategori');
  document.getElementById('formAlamat').value = btn.getAttribute('data-alamat');
  document.getElementById('formRating').value = btn.getAttribute('data-rating');
  document.getElementById('formDeskripsi').value = btn.getAttribute('data-deskripsi');
  document.getElementById('formFotoLama').value = btn.getAttribute('data-foto');
  
  // Mengisi data input baru dari tombol yang di-klik
  document.getElementById('formMaps').value = btn.getAttribute('data-maps');
  document.getElementById('formKontak').value = btn.getAttribute('data-kontak');
  document.getElementById('formTarif').value = btn.getAttribute('data-tarif');
  document.getElementById('formHistory').value = btn.getAttribute('data-history');
  document.getElementById('formTips').value = btn.getAttribute('data-tips');
  
  document.getElementById('formFotoFile').required = false; 
  new bootstrap.Modal(document.getElementById('placeModal')).show();
}
</script>
</body>
</html>