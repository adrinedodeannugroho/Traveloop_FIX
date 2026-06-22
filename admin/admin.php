<?php
session_start();
// Mengarah ke folder config/koneksi.php yang baru
require_once '../config/koneksi.php'; 

// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Query untuk mengambil data admin berdasarkan email
    $query_login = mysqli_query($koneksi, "SELECT * FROM admin WHERE email = '$email'");
    
    if ($query_login && mysqli_num_rows($query_login) > 0) {
        $admin = mysqli_fetch_assoc($query_login);
        
        // Verifikasi password dengan password_verify
        if (password_verify($password, $admin['password'])) {
            // Login berhasil
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            $_SESSION['admin_email'] = $admin['email'];
            
            // Update last_login
            $admin_id = $admin['id'];
            $update_login = mysqli_query($koneksi, "UPDATE admin SET last_login = NOW() WHERE id = '$admin_id'");
            
            header("Location: admin.php");
            exit;
        } else {
            $login_error = "Email atau Password salah!";
        }
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
    $jml_destinasi = 0;
    $jml_pesan = 0;
    $jml_alam = 0;
    $jml_gem = 0;

    $q_dest = mysqli_query($koneksi, "SELECT id FROM destinasi");
    if($q_dest) $jml_destinasi = mysqli_num_rows($q_dest);
    
    $query_pesan = @mysqli_query($koneksi, "SELECT id FROM pesan_kontak");
    if($query_pesan) $jml_pesan = mysqli_num_rows($query_pesan);
    
    $q_alam = mysqli_query($koneksi, "SELECT id FROM destinasi WHERE kategori='Alam' OR kategori='nature'");
    if($q_alam) $jml_alam = mysqli_num_rows($q_alam);
    
    $q_gem = mysqli_query($koneksi, "SELECT id FROM destinasi WHERE deskripsi LIKE '%hidden gem%' OR deskripsi LIKE '%Hidden Gem%'");
    if($q_gem) $jml_gem = mysqli_num_rows($q_gem);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard — Traveloop</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
  
  <style>
      :root {
          /* Disamakan dengan tema Index (Kuning / Gelap) */
          --primary-color: #eab308; /* Warning / Yellow */
          --primary-hover: #ca8a04;
          --dark-bg: #0f172a;
          --font-base: 'Plus Jakarta Sans', sans-serif;
      }
      body { font-family: var(--font-base); background-color: #f1f5f9; color: #334155; }
      
      /* Sidebar */
      .admin-sidebar { width: 280px; transition: 0.3s; box-shadow: 2px 0 20px rgba(0,0,0,0.03); z-index: 1000; }
      .sidebar-link { border: none; border-radius: 12px; margin-bottom: 8px; font-weight: 600; padding: 12px 16px; color: #64748b; background: transparent; transition: all 0.2s; }
      .sidebar-link:hover { background: #f8fafc; color: var(--dark-bg); }
      .sidebar-link.active { background: var(--primary-color); color: #000; box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3); }
      
      /* Topbar & Cards */
      .admin-topbar { box-shadow: 0 2px 15px rgba(0,0,0,0.02); z-index: 999; }
      .stat-card { background: #fff; border-radius: 20px; padding: 24px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 20px; transition: transform 0.2s; }
      .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
      .stat-card-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }
      
      /* Tables */
      .table-card { background: #fff; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
      .table th { background-color: #f8fafc; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; padding: 16px; border-bottom: 2px solid #e2e8f0; }
      .table td { padding: 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
      .table-hover tbody tr:hover { background-color: #fcfcfc; }
      
      /* Badges & Inputs */
      .cat-admin-badge { background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; }
      .form-control, .form-select { border-radius: 10px; border: 1px solid #cbd5e1; padding: 12px 16px; font-size: 0.95rem; }
      .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(234, 179, 8, 0.15); }
      
      /* Login Screen */
      .login-screen-bg { background: linear-gradient(135deg, var(--dark-bg), #1e3a5f); height: 100vh; display: flex; align-items: center; justify-content: center; }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged_in'])): ?>
<div class="login-screen-bg">
  <div class="login-card bg-white p-5 rounded-4 shadow-lg text-center" style="width: 100%; max-width: 420px;">
    <div class="mb-4 fs-2 fw-bolder text-dark tracking-tight"><i class="bi bi-compass-fill text-warning me-2"></i>Traveloop</div>
    <h5 class="fw-bold text-dark mb-1">Admin Workspace</h5>
    <p class="text-muted small mb-4">Masuk untuk mengelola ekosistem wisata.</p>
    
    <?php if(isset($login_error)): ?>
        <div class="alert alert-danger py-2 small border-0 fw-bold rounded-3"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= $login_error ?></div>
    <?php endif; ?>

    <form method="POST" action="admin.php" class="text-start">
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted">Alamat Email</label>
          <input type="email" name="email" class="form-control bg-light border-0" placeholder="admin@traveloop.com" required/>
        </div>
        <div class="mb-4">
          <label class="form-label small fw-bold text-muted">Kata Sandi</label>
          <input type="password" name="password" class="form-control bg-light border-0" placeholder="••••••••" required/>
        </div>
        <button type="submit" name="login" class="btn fw-bold py-3 rounded-3 shadow-sm w-100" style="background-color: var(--primary-color); color: #000;">
          Masuk Dashboard <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </form>
  </div>
</div>

<?php else: ?>
<div id="adminShell" class="d-flex" style="height: 100vh; overflow: hidden;">
  
  <aside class="admin-sidebar bg-white border-end d-flex flex-column" id="adminSidebar">
    <div class="p-4 fs-4 fw-bolder border-bottom text-dark">
        <i class="bi bi-compass-fill text-warning me-2"></i>Traveloop<span class="text-warning">.</span>
    </div>
    <nav class="flex-grow-1 p-3 d-flex flex-column">
      <p class="small fw-bold text-muted text-uppercase tracking-wider mb-2 px-2">Menu Utama</p>
      <button class="sidebar-link active text-start" data-tab="dashboard" onclick="switchTab('dashboard',this)">
          <i class="bi bi-grid-1x2-fill me-3"></i> Tinjauan Sistem
      </button>
      <button class="sidebar-link text-start" data-tab="places" onclick="switchTab('places',this)">
          <i class="bi bi-map-fill me-3"></i> Kelola Destinasi
      </button>
      <button class="sidebar-link text-start d-flex justify-content-between align-items-center" data-tab="messages" onclick="switchTab('messages',this)">
          <span><i class="bi bi-envelope-fill me-3"></i> Pesan Masuk</span>
          <?php if($jml_pesan > 0): ?>
            <span class="badge bg-danger rounded-pill shadow-sm"><?= $jml_pesan ?></span>
          <?php endif; ?>
      </button>
    </nav>
    <div class="p-3 border-top bg-light m-3 rounded-4 text-center">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
          <div class="bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height:35px;">
              <?= strtoupper(substr($_SESSION['admin_nama'] ?? 'A', 0, 1)) ?>
          </div>
          <div class="text-start">
              <p class="mb-0 fw-bold small text-dark"><?= $_SESSION['admin_nama'] ?? 'Administrator' ?></p>
              <p class="mb-0 text-success fw-bold" style="font-size: 11px;">● Online</p>
          </div>
      </div>
      <div class="d-flex gap-2">
        <a href="../index.php" class="btn btn-outline-dark btn-sm w-50" target="_blank" title="Lihat Website"><i class="bi bi-globe"></i></a>
        <a href="admin.php?action=logout" class="btn btn-danger btn-sm w-50" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <main class="flex-grow-1 d-flex flex-column" style="overflow-y: auto;">
    
    <header class="admin-topbar bg-white p-3 px-4 d-flex justify-content-between align-items-center sticky-top">
      <div class="d-flex align-items-center gap-3">
          <button class="btn btn-light d-md-none border-0 shadow-sm rounded-3" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
          <div>
            <h4 class="mb-0 fw-bolder text-dark" id="pageTitle">Tinjauan Sistem</h4>
            <p class="mb-0 text-muted small">Ringkasan aktivitas dan data wilayah Barlingmascakeb.</p>
          </div>
      </div>
      <div class="text-end d-none d-md-block">
        <p class="mb-0 small fw-bold text-dark"><?= date('d M Y') ?></p>
      </div>
    </header>

    <div class="p-4 p-md-5">

      <div class="admin-tab active" id="tab-dashboard">
        <div class="row g-4 mb-4">
          <div class="col-12 col-md-6 col-xl-3">
              <div class="stat-card shadow-sm">
                  <div class="stat-card-icon" style="background:#fefce8;color:#ca8a04"><i class="bi bi-map-fill"></i></div>
                  <div><h3 class="mb-0 fw-bolder text-dark"><?= $jml_destinasi ?></h3><span class="text-muted small fw-bold text-uppercase tracking-wider">Total Destinasi</span></div>
              </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
              <div class="stat-card shadow-sm">
                  <div class="stat-card-icon" style="background:#f0fdf4;color:#22c55e"><i class="bi bi-tree-fill"></i></div>
                  <div><h3 class="mb-0 fw-bolder text-dark"><?= $jml_alam ?></h3><span class="text-muted small fw-bold text-uppercase tracking-wider">Wisata Alam</span></div>
              </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
              <div class="stat-card shadow-sm">
                  <div class="stat-card-icon" style="background:#f8fafc;color:#0f172a"><i class="bi bi-gem"></i></div>
                  <div><h3 class="mb-0 fw-bolder text-dark"><?= $jml_gem ?></h3><span class="text-muted small fw-bold text-uppercase tracking-wider">Hidden Gems</span></div>
              </div>
          </div>
          <div class="col-12 col-md-6 col-xl-3">
              <div class="stat-card shadow-sm">
                  <div class="stat-card-icon" style="background:#fef2f2;color:#ef4444"><i class="bi bi-envelope-paper-fill"></i></div>
                  <div><h3 class="mb-0 fw-bolder text-dark"><?= $jml_pesan ?></h3><span class="text-muted small fw-bold text-uppercase tracking-wider">Pesan Masuk</span></div>
              </div>
          </div>
        </div>
      </div>

      <div class="admin-tab d-none" id="tab-places">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
          <div class="input-group shadow-sm rounded-3 overflow-hidden" style="max-width: 400px;">
            <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-0 bg-white" placeholder="Cari destinasi... (Visual UI)"/>
          </div>
          <button class="btn btn-dark fw-bold shadow-sm rounded-3 px-4 py-2" onclick="openAddModal()">
            <i class="bi bi-plus-lg text-warning me-2"></i>Tambah Destinasi Baru
          </button>
        </div>
        
        <div class="table-card">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="placesTable">
              <thead>
                  <tr>
                      <th class="px-4">Destinasi</th>
                      <th>Kategori</th>
                      <th>Lokasi</th>
                      <th>Rating</th>
                      <th class="text-center">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                <?php
                $query_places = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
                if($query_places && mysqli_num_rows($query_places) > 0) {
                    while($row = mysqli_fetch_assoc($query_places)){
                        $id = $row['id'];
                        $nama = htmlspecialchars($row['nama'], ENT_QUOTES); 
                        $kategori = $row['kategori'];
                        $alamat = htmlspecialchars($row['alamat'], ENT_QUOTES); 
                        $rating = str_replace('.', ',', $row['rating']); 
                        $deskripsi = htmlspecialchars(isset($row['deskripsi']) ? $row['deskripsi'] : '', ENT_QUOTES);
                        $foto = isset($row['foto_url']) ? $row['foto_url'] : '';
                        $maps_url = htmlspecialchars(isset($row['maps_url']) ? $row['maps_url'] : '', ENT_QUOTES);
                        $kontak = htmlspecialchars(isset($row['kontak']) ? $row['kontak'] : '', ENT_QUOTES);
                        $tarif = htmlspecialchars(isset($row['tarif']) ? $row['tarif'] : '', ENT_QUOTES);
                        $history = htmlspecialchars(isset($row['history']) ? $row['history'] : '', ENT_QUOTES);
                        $tips = htmlspecialchars(isset($row['tips']) ? $row['tips'] : '', ENT_QUOTES);

                        echo "<tr>
                                <td class='px-4'>
                                    <div class='d-flex align-items-center gap-3'>
                                        <img src='{$foto}' alt='foto' class='rounded-3 object-fit-cover' style='width: 45px; height: 45px; background: #e2e8f0;'>
                                        <strong class='text-dark'>{$nama}</strong>
                                    </div>
                                </td>
                                <td><span class='cat-admin-badge'>{$kategori}</span></td>
                                <td><span class='text-muted small text-truncate d-inline-block' style='max-width:200px;' title='{$alamat}'>{$alamat}</span></td>
                                <td><span class='badge bg-warning text-dark fw-bold'><i class='bi bi-star-fill me-1'></i>{$rating}</span></td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-light border me-1 text-primary shadow-sm rounded-3' 
                                            data-id='{$id}' data-nama='{$nama}' data-kategori='{$kategori}' data-alamat='{$alamat}' data-rating='{$rating}'
                                            data-deskripsi='{$deskripsi}' data-foto='{$foto}' data-maps='{$maps_url}' data-kontak='{$kontak}'
                                            data-tarif='{$tarif}' data-history='{$history}' data-tips='{$tips}'
                                            onclick='openEditModal(this)' title='Edit Data'>
                                        <i class='bi bi-pencil-square'></i>
                                    </button>
                                    <button class='btn btn-sm btn-light border text-danger shadow-sm rounded-3' 
                                            onclick='confirmHapus(\"../config/koneksi.php?action=hapus&id={$id}\", \"{$nama}\")' title='Hapus Data'>
                                        <i class='bi bi-trash-fill'></i>
                                    </button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Belum ada data destinasi.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="admin-tab d-none" id="tab-messages">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Tgl & Pengirim</th>
                            <th>Kontak</th>
                            <th>Topik</th>
                            <th>Pesan</th>
                            <th class="text-center">Aksi / Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_pesan = @mysqli_query($koneksi, "SELECT * FROM pesan_kontak ORDER BY id DESC");
                        if($query_pesan && mysqli_num_rows($query_pesan) > 0){
                            while($msg = mysqli_fetch_assoc($query_pesan)){
                                $tgl = date('d/m/Y H:i', strtotime($msg['tanggal']));
                                
                                // Escaping Data Untuk Modal
                                $m_id = $msg['id'];
                                $m_nama = htmlspecialchars($msg['nama'], ENT_QUOTES);
                                $m_email = htmlspecialchars($msg['email'], ENT_QUOTES);
                                $m_wa = htmlspecialchars($msg['no_wa'], ENT_QUOTES);
                                $m_topik = htmlspecialchars($msg['topik'], ENT_QUOTES);
                                $m_pesan = htmlspecialchars($msg['pesan'], ENT_QUOTES);
                                
                                // Simulasi Status Selesai (Jika ID genap/ganjil untuk preview UI)
                                // Jika tabel kamu memiliki kolom `status`, ganti logika ini.
                                $isDone = ($msg['id'] % 2 == 0) ? true : false;
                                
                                echo "<tr>
                                        <td class='px-4'>
                                            <span class='d-block small text-muted fw-bold mb-1'>{$tgl}</span>
                                            <strong class='text-dark'>{$m_nama}</strong>
                                        </td>
                                        <td>
                                            <span class='d-block small text-primary mb-1'><i class='bi bi-envelope me-1'></i>{$m_email}</span>
                                            <span class='d-block small text-success'><i class='bi bi-whatsapp me-1'></i>{$m_wa}</span>
                                        </td>
                                        <td><span class='badge bg-light text-dark border'>{$m_topik}</span></td>
                                        <td>
                                            <div class='small text-muted text-truncate' style='max-width: 200px;' title='{$m_pesan}'>
                                                {$m_pesan}
                                            </div>
                                        </td>
                                        <td class='text-center'>
                                            <div class='d-flex justify-content-center gap-2'>
                                                <button class='btn btn-sm btn-dark text-warning rounded-3 shadow-sm'
                                                        data-id='{$m_id}' data-nama='{$m_nama}' data-email='{$m_email}' 
                                                        data-wa='{$m_wa}' data-topik='{$m_topik}' data-pesan='{$m_pesan}' data-tgl='{$tgl}'
                                                        onclick='openMessageModal(this)' title='Lihat Detail Pesan'>
                                                    <i class='bi bi-eye-fill'></i> Detail
                                                </button>
                                                
                                                <button class='btn btn-sm ".($isDone ? "btn-success" : "btn-outline-success")." rounded-3'
                                                        onclick='markPesanSelesai(this)' title='Tandai Selesai'>
                                                    <i class='bi bi-check2-all'></i> ".($isDone ? "Selesai" : "Tandai")."
                                                </button>
                                            </div>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                  <i class='bi bi-envelope-paper display-4 opacity-25 d-block mb-3'></i>Kotak masuk Anda kosong.</td></tr>";
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
    <form method="POST" action="../config/koneksi.php" enctype="multipart/form-data" class="modal-content shadow-lg border-0 rounded-4" id="formDestinasi">
      <input type="hidden" name="action" id="formAction" value="tambah">
      <input type="hidden" name="id" id="formId" value="">
      <input type="hidden" name="foto_url_lama" id="formFotoLama" value="">

      <div class="modal-header bg-white border-bottom py-3 px-4">
        <h5 class="modal-title fw-bold text-dark" id="placeModalTitle">Tambah Destinasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body px-4 py-4 bg-light">
        <div class="row g-4">
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Informasi Utama</h6>
                <div class="row g-3">
                    <div class="col-md-8">
                      <label class="form-label small fw-bold text-muted">Nama Destinasi *</label>
                      <input type="text" name="nama" id="formNama" class="form-control" required/>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-bold text-muted">Kategori *</label>
                      <select name="kategori" id="formKategori" class="form-select">
                        <option value="Alam">Alam & Hutan</option>
                        <option value="Pantai">Pantai & Laut</option>
                        <option value="Gunung">Pegunungan</option>
                        <option value="Budaya">Seni Budaya</option>
                        <option value="Sejarah">Situs Sejarah</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted">Alamat Lengkap *</label>
                      <input type="text" name="alamat" id="formAlamat" class="form-control" required/>
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted">Ringkasan Singkat (Tambahkan kata "Hidden Gem" di sini jika sesuai)</label>
                      <textarea name="deskripsi" id="formDeskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted">Tautan Iframe / URL Google Maps</label>
                      <input type="text" name="maps_url" id="formMaps" class="form-control" placeholder="https://..."/>
                    </div>
                </div>
            </div>
          </div>

          <div class="col-lg-4">
             <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">Detail & Akses</h6>
                <div class="row g-3">
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted d-flex justify-content-between">
                          <span>Rating (0-5)</span>
                          <span class="text-warning fw-normal" style="font-size: 11px;">Bisa gunakan koma</span>
                      </label>
                      <input type="text" name="rating" id="formRating" class="form-control fw-bold" placeholder="Contoh: 4,5" oninput="formatRating(this)"/>
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted">Harga Tiket Masuk</label>
                      <input type="text" name="tarif" id="formTarif" class="form-control" placeholder="Misal: Rp 15.000 / Gratis"/>
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-bold text-muted">Kontak Pengelola</label>
                      <input type="text" name="kontak" id="formKontak" class="form-control" placeholder="No WhatsApp / IG"/>
                    </div>
                    <div class="col-12 mt-4">
                      <label class="form-label small fw-bold text-muted">Unggah Foto Sampul <span class="text-danger">(Maks. 1MB)</span></label>
                      <input type="file" name="foto_file" id="formFotoFile" class="form-control" accept="image/png, image/jpeg, image/webp"/>
                      <div id="fotoHelp" class="form-text text-danger" style="font-size: 11px;">Kosongkan jika tidak ingin mengubah foto saat Mode Edit. Disarankan gunakan format <b>WebP</b> agar optimal di InfinityFree.</div>
                    </div>
                </div>
             </div>
          </div>

          <div class="col-12">
              <div class="card border-0 shadow-sm rounded-4 p-4">
                  <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Sejarah / Latar Belakang Detail</label>
                        <textarea name="history" id="formHistory" class="form-control" rows="4"></textarea>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Tips Berkunjung</label>
                        <textarea name="tips" id="formTips" class="form-control" rows="4" placeholder="Waktu terbaik datang, barang bawaan..."></textarea>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer bg-white border-top py-3 px-4">
        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batalkan</button>
        <button type="submit" class="btn btn-dark text-warning fw-bold px-4 rounded-3 shadow-sm">
          <i class="bi bi-save-fill me-2"></i>Simpan ke Database
        </button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="messageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header bg-dark text-white border-bottom-0 py-3 px-4 rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="bi bi-envelope-open text-warning me-2"></i>Detail Pesan Masuk</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 bg-light">
          <div class="bg-white p-3 rounded-3 border mb-3 shadow-sm">
              <p class="text-muted small mb-1">Diterima Pada: <span id="msgTgl" class="fw-bold text-dark"></span></p>
              <h5 id="msgNama" class="fw-bolder text-dark mb-0"></h5>
              <hr class="my-2 text-secondary">
              <div class="d-flex justify-content-between">
                  <span class="small"><i class="bi bi-envelope text-primary me-1"></i><span id="msgEmail"></span></span>
                  <span class="small"><i class="bi bi-whatsapp text-success me-1"></i><span id="msgWa"></span></span>
              </div>
          </div>
          
          <div class="bg-white p-3 rounded-3 border shadow-sm">
              <span class="badge bg-light border text-dark mb-2" id="msgTopik"></span>
              <p id="msgIsi" class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem;"></p>
          </div>
      </div>
      <div class="modal-footer bg-white border-top py-3 px-4 justify-content-between">
          <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
          <div class="d-flex gap-2">
              <a href="#" id="btnReplyEmail" class="btn btn-primary fw-bold rounded-3"><i class="bi bi-envelope-fill me-1"></i> Balas Email</a>
              <a href="#" id="btnReplyWa" target="_blank" class="btn btn-success fw-bold rounded-3"><i class="bi bi-whatsapp me-1"></i> Balas WA</a>
          </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>

<script>
// --- LOGIKA SWEETALERT2 UNTUK STATUS GET ---
document.addEventListener("DOMContentLoaded", function() {
    <?php if(isset($_GET['status'])): ?>
        let status = '<?= $_GET['status'] ?>';
        let title = 'Berhasil!';
        let text = '';
        let icon = 'success';
        let confirmBtnColor = '#eab308'; // Warning color

        if(status === 'success_tambah') text = 'Data destinasi baru telah berhasil ditambahkan.';
        else if(status === 'success_edit') text = 'Perubahan data destinasi telah disimpan.';
        else if(status === 'success_hapus') text = 'Destinasi telah berhasil dihapus dari sistem.';
        else { title = 'Error!'; text = 'Terjadi kesalahan pada sistem.'; icon = 'error'; confirmBtnColor = '#ef4444'; }

        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: confirmBtnColor,
            confirmButtonText: 'Tutup',
            timer: 3500,
            timerProgressBar: true
        });

        // Membersihkan URL dari parameter status agar alert tidak muncul terus
        window.history.replaceState({}, document.title, "admin.php");
    <?php endif; ?>
});

// Konfirmasi Hapus Data dengan SweetAlert2
function confirmHapus(url, nama) {
    Swal.fire({
        title: 'Hapus Destinasi?',
        html: `Apakah Anda yakin ingin menghapus <strong>${nama}</strong>?<br>Data tidak dapat dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Logika Pindah Tab (Dashboard, Kelola, Pesan)
function switchTab(tab, link) {
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.add('d-none', 'active'));
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
  
  document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
  
  document.getElementById('tab-' + tab).classList.remove('d-none');
  document.getElementById('tab-' + tab).classList.add('active');
  link.classList.add('active');
  
  const titles = { dashboard: 'Tinjauan Sistem', places: 'Manajemen Destinasi', messages: 'Pusat Pesan & Kolaborasi' };
  document.getElementById('pageTitle').textContent = titles[tab] || tab;
}

function toggleSidebar() {
  const sidebar = document.getElementById('adminSidebar');
  sidebar.style.marginLeft = sidebar.style.marginLeft === '-280px' ? '0px' : '-280px';
}

// FORMAT RATING 0-5
function formatRating(input) {
    let val = input.value.replace(/[^0-9,.]/g, '').replace('.', ','); 
    let numVal = parseFloat(val.replace(',', '.'));
    if (numVal > 5) input.value = '5';
    else if (numVal < 0) input.value = '0';
    else input.value = val;
}

// Submit handler
document.getElementById('formDestinasi')?.addEventListener('submit', function(e) {
    let ratingInput = document.getElementById('formRating');
    if(ratingInput.value) ratingInput.value = ratingInput.value.replace(',', '.');
});

// MODAL KELOLA DESTINASI
function openAddModal() {
  document.getElementById('placeModalTitle').innerHTML = '<i class="bi bi-file-earmark-plus-fill text-warning me-2"></i>Tambah Destinasi Baru';
  document.getElementById('formAction').value = "tambah";
  document.getElementById('formDestinasi').reset();
  
  document.getElementById('formId').value = "";
  document.getElementById('formKategori').value = "Alam";
  document.getElementById('formFotoLama').value = "";
  document.getElementById('formFotoFile').required = true; 
  document.getElementById('fotoHelp').classList.add('d-none'); 
  
  new bootstrap.Modal(document.getElementById('placeModal')).show();
}

function openEditModal(btn) {
  document.getElementById('placeModalTitle').innerHTML = '<i class="bi bi-pencil-square text-warning me-2"></i>Edit Destinasi';
  document.getElementById('formAction').value = "edit";
  
  document.getElementById('formId').value = btn.getAttribute('data-id');
  document.getElementById('formNama').value = btn.getAttribute('data-nama');
  document.getElementById('formKategori').value = btn.getAttribute('data-kategori');
  document.getElementById('formAlamat').value = btn.getAttribute('data-alamat');
  document.getElementById('formRating').value = btn.getAttribute('data-rating').replace('.', ',');
  document.getElementById('formDeskripsi').value = btn.getAttribute('data-deskripsi');
  document.getElementById('formFotoLama').value = btn.getAttribute('data-foto');
  
  document.getElementById('formMaps').value = btn.getAttribute('data-maps');
  document.getElementById('formKontak').value = btn.getAttribute('data-kontak');
  document.getElementById('formTarif').value = btn.getAttribute('data-tarif');
  document.getElementById('formHistory').value = btn.getAttribute('data-history');
  document.getElementById('formTips').value = btn.getAttribute('data-tips');
  
  document.getElementById('formFotoFile').required = false; 
  document.getElementById('fotoHelp').classList.remove('d-none'); 
  
  new bootstrap.Modal(document.getElementById('placeModal')).show();
}

// MODAL DETAIL PESAN & LOGIKA BALAS
function openMessageModal(btn) {
    let nama = btn.getAttribute('data-nama');
    let email = btn.getAttribute('data-email');
    let wa = btn.getAttribute('data-wa');
    let topik = btn.getAttribute('data-topik');
    let pesan = btn.getAttribute('data-pesan');
    let tgl = btn.getAttribute('data-tgl');

    document.getElementById('msgNama').textContent = nama;
    document.getElementById('msgEmail').textContent = email;
    document.getElementById('msgWa').textContent = wa;
    document.getElementById('msgTopik').textContent = "Topik: " + topik;
    document.getElementById('msgIsi').textContent = pesan;
    document.getElementById('msgTgl').textContent = tgl;

    // Set href untuk Email
    document.getElementById('btnReplyEmail').href = `mailto:${email}?subject=Balasan dari Tim Traveloop Barlingmascakeb`;

    // Set href untuk WA (Format 08 ke 628)
    let formatWa = wa.replace(/\D/g, ''); // Hapus semua karakter non-angka
    if (formatWa.startsWith('0')) {
        formatWa = '62' + formatWa.substring(1);
    }
    document.getElementById('btnReplyWa').href = `https://wa.me/${formatWa}`;

    new bootstrap.Modal(document.getElementById('messageModal')).show();
}

// LOGIKA TANDAI SELESAI
function markPesanSelesai(btn) {
    // Karena kita tidak benar-benar mengeksekusi koneksi.php untuk update ini (demi keamanan database saat ini)
    // Kita buat manipulasi visual interaktif dengan SweetAlert
    
    let isAlreadyDone = btn.classList.contains('btn-success');
    
    if (isAlreadyDone) {
        Swal.fire({
            icon: 'info',
            title: 'Sudah Selesai',
            text: 'Pesan ini sudah ditandai sebagai selesai sebelumnya.',
            confirmButtonColor: '#eab308'
        });
        return;
    }

    Swal.fire({
        title: 'Tandai Selesai?',
        text: "Pesan ini akan ditandai sudah dibaca dan diselesaikan.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Tandai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Manipulasi Kelas Tombol secara Instan
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-success');
            btn.innerHTML = "<i class='bi bi-check2-all'></i> Selesai";
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Status pesan diperbarui.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>
</body>
</html>