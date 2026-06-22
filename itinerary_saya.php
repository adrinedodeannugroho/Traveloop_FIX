<?php
// itinerary_saya.php
require_once 'includes/header.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo "<script>window.location.href='auth/login.php?redirect=../itinerary_saya.php';</script>";
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Ambil riwayat komplit itinerary dari database
$query_itin = mysqli_query($koneksi, "SELECT * FROM user_itineraries WHERE user_id = $user_id ORDER BY created_at DESC");
$total_itin = $query_itin ? mysqli_num_rows($query_itin) : 0;
?>

<style>
  .itin-card-modern {
      border: none;
      border-radius: 1.25rem;
      background: #ffffff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .itin-card-modern:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
  }
  .rute-node {
      background: #f8fafc;
      border-left: 4px solid #f59e0b;
      padding: 0.75rem 1rem;
      border-radius: 0 0.5rem 0.5rem 0;
      font-size: 0.9rem;
  }
  .btn-action-circle {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
  }
</style>

<div class="page-header-solid bg-dark position-relative" style="padding-top: 100px; padding-bottom: 40px;">
  <div class="container text-center px-3 position-relative z-1">
    <h1 class="page-title text-white fw-bold mb-2"><i class="bi bi-map-fill text-warning me-2"></i>Itinerary Saya</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center bg-transparent mb-0">
        <li class="breadcrumb-item"><a href="index.php" class="text-white-50 text-decoration-none small">Beranda</a></li>
        <li class="breadcrumb-item text-white active small" aria-current="page">Itinerary</li>
      </ol>
    </nav>
  </div>
</div>

<section class="section-pad py-4 py-md-5 bg-light min-vh-100">
  <div class="container px-3 px-md-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 border-bottom pb-3">
      <div>
        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-journal-bookmark-fill text-primary me-2"></i>Rencana Perjalanan Bisnis & Liburan</h4>
        <p class="text-muted small mb-0">Terdaftar sebanyak <strong class="text-dark fs-6"><?= $total_itin ?></strong> rute cerdas terintegrasi.</p>
      </div>
      <button class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm w-100 w-md-auto" onclick="return showItineraryModal();">
        <i class="bi bi-magic me-1"></i>Buat Rute AI Baru
      </button>
    </div>

    <?php if ($total_itin > 0): ?>
      <div class="row g-4" id="itineraryContainerGrid">
        <?php while ($row = mysqli_fetch_assoc($query_itin)): 
            $destinasi_ids = json_decode($row['destinasi_ids'], true);
            $ids_str = implode(',', array_map('intval', $destinasi_ids));
            
            // Mengambil daftar detail destinasi secara terstruktur
            $destinasi_list = [];
            if (!empty($ids_str)) {
                // Gunakan FIELD() agar urutan destinasi yang ditarik dari DB konsisten dengan array susunan awal
                $q_dest = mysqli_query($koneksi, "SELECT id, nama FROM destinasi WHERE id IN ($ids_str) ORDER BY FIELD(id, $ids_str)");
                while($d = mysqli_fetch_assoc($q_dest)){
                    $destinasi_list[] = $d;
                }
            }
            
            $names_only = array_column($destinasi_list, 'nama');
            $ringkasan = implode(" ➔ ", $names_only);
            $tanggal = date('d M Y - H:i', strtotime($row['created_at']));
            $tema_icon = ($row['tema'] === 'Pantai') ? 'bi-water text-primary' : (($row['tema'] === 'Sejarah') ? 'bi-bank2 text-warning' : 'bi-tree-fill text-success');
        ?>
          <div class="col-12 col-lg-6" id="itinerary-card-row-<?= $row['id'] ?>">
            <div class="card itin-card-modern h-100">
              <div class="card-body p-4">
                
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill mb-2 shadow-sm">
                      <i class="bi <?= $tema_icon ?> me-1"></i><?= htmlspecialchars($row['tema']) ?>
                    </span>
                    <h5 class="fw-bold text-dark mb-1">Eksplorasi Kawasan Bersejarah <?= htmlspecialchars($row['kota_utama']) ?></h5>
                    <p class="text-muted small mb-0"><i class="bi bi-calendar3 me-1"></i>Penyusunan: <?= $tanggal ?></p>
                  </div>
                  
                  <div class="d-flex gap-1 no-print">
                    <button class="btn btn-outline-primary btn-action-circle" 
                            onclick="prosesEditRuteJSON(<?= $row['id'] ?>, <?= htmlspecialchars(json_encode($destinasi_list)) ?>)" 
                            title="Edit Susunan Rute">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-action-circle" 
                            onclick="prosesHapusItinerary(<?= $row['id'] ?>)" 
                            title="Hapus Itinerary">
                      <i class="bi bi-trash3-fill"></i>
                    </button>
                  </div>
                </div>
                
                <div class="bg-light p-3 rounded-3 mt-3 border border-dashed">
                  <span class="d-block small fw-bold text-muted mb-2 text-uppercase tracking-wider">Urutan Singgah:</span>
                  <div class="d-flex flex-column gap-2" id="list-nodes-summary-<?= $row['id'] ?>">
                     <?php foreach($names_only as $idx => $node_name): ?>
                        <div class="rute-node fw-medium text-dark">
                           <small class="text-muted me-2">Stop <?= $idx+1 ?>:</small> <?= htmlspecialchars($node_name) ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
                </div>

              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm">
        <div class="mx-auto px-3">
          <i class="bi bi-map display-1 text-primary opacity-50 d-block mb-3"></i>
          <h4 class="fw-bold text-dark mb-2">Belum Ada Itinerary Tersimpan</h4>
          <p class="text-muted mb-4 small" style="max-width: 400px; margin: 0 auto;">
            Biarkan AI kami merancangkan rencana perjalanan 1 hari penuh untuk Anda secara otomatis.
          </p>
          <button class="btn btn-warning fw-bold rounded-pill px-4 py-2 shadow-sm" onclick="return showItineraryModal();">
            <i class="bi bi-magic me-2"></i>Mulai Racik Itinerary
          </button>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================================
//  FUNGSI 1: MENGHAPUS RUTE ITINERARY (AJAX METODE)
// ============================================================
function prosesHapusItinerary(id) {
    Swal.fire({
        title: 'Hapus Rute Perjalanan?',
        text: "Seluruh daftar singgah objek wisata tersimpan ini akan dieliminasi dari profil.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/kelola_itinerary.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', itinerary_id: id })
            })
            .then(r => r.json())
            .then(res => {
                if(res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, timer: 1500, showConfirmButton: false });
                    
                    // Hilangkan baris kartu rute dari grid interface
                    const card = document.getElementById('itinerary-card-row-' + id);
                    if(card) {
                        card.style.transition = 'all 0.4s ease';
                        card.style.transform = 'scale(0.8)';
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.remove();
                            if(document.querySelectorAll('#itineraryContainerGrid .col-12').length === 0) {
                                location.reload();
                            }
                        }, 400);
                    }
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Gagal memproses jaringan server.', 'error'));
        }
    });
}

// ============================================================
//  FUNGSI 2: MENGEDIT SUSUNAN RUTE DENGAN POP-UP PRESET ADVANCED
// ============================================================
function prosesEditRuteJSON(id, destinasiList) {
    // Bangun form pilihan dinamis berbasis objek wisata yang terikat di rute tersebut
    let formHtml = `<div class="text-start mb-3 small text-muted">Centang objek wisata yang ingin dipertahankan dalam rute, lalu gunakan tombol simpan:</div>`;
    
    destinasiList.forEach((dest, index) => {
        formHtml += `
        <div class="form-check text-start p-2 border-bottom">
            <input class="form-check-input ms-0 me-2 input-node-check-edit" type="checkbox" value="${dest.id}" id="chk-node-${id}-${dest.id}" checked style="transform: scale(1.15);">
            <label class="form-check-label fw-bold text-dark" for="chk-node-${id}-${dest.id}">
               Stop #${index + 1}: ${dest.nama}
            </label>
        </div>`;
    });

    Swal.fire({
        title: 'Modifikasi Rencana Wisata',
        html: `<div class="p-2">${formHtml}</div>`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-cloud-check-fill me-1"></i> Perbarui Rute',
        cancelButtonText: 'Kembali',
        customClass: { popup: 'rounded-4' },
        preConfirm: () => {
            // Ambil seluruh ID checkbox destinasi yang tetap dalam kondisi terpilih (checked)
            const checkedBoxes = document.querySelectorAll('.input-node-check-edit:checked');
            if (checkedBoxes.length === 0) {
                Swal.showValidationMessage('Rute wisata tidak boleh kosong! Pilih minimal 1 objek destinasi.');
                return false;
            }
            
            let selectedIds = [];
            checkedBoxes.forEach(box => selectedIds.push(parseInt(box.value)));
            return selectedIds;
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Kirim paket data array ID baru ke backend API via fetch stream
            fetch('api/kelola_itinerary.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update', itinerary_id: id, destinasi_ids: result.value })
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 1500, showConfirmButton: false })
                    .then(() => {
                        // Segarkan halaman untuk me-render perubahan susunan string data terupdate
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal Merubah', res.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Gangguan otentikasi transmisi data.', 'error'));
        }
    });
}
</script>

<?php 
require_once 'includes/footer.php'; 
?>