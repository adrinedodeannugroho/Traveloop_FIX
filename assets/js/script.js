// ============================================================
//  Traveloop — Main Script & Configuration (PHP/MySQL Aligned)
// ============================================================

const CONFIG = {
    // Masukkan API Key Google Maps kamu di sini
    GOOGLE_MAPS_API_KEY: "https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap",
    DEFAULT_CENTER: { lat: -7.4226, lng: 109.2354 }, // Purwokerto
    DEFAULT_RADIUS: 50000,
};

// ── Global State ─────────────────────────────────────────────
let state = {
    places: [],
    filtered: [],
    currentPage: 1,
    perPage: 9,
    view: "grid",
    filters: {
        cat: "all",
        minRating: 0,
        search: "",
    },
    sort: "prominence"
};

// ============================================================
//  CORE UI & UTILITIES
// ============================================================

function initNavScroll() {
    const nav = document.getElementById("mainNav");
    if (!nav) return;
    const fn = () => nav.classList.toggle("scrolled", window.scrollY > 60);
    window.addEventListener("scroll", fn, { passive: true });
    fn();
}

function generateParticles() {
    const el = document.getElementById("heroParticles");
    if (!el) return;
    for (let i = 0; i < 30; i++) {
        const p = document.createElement("div");
        p.className = "particle";
        p.style.cssText = `left:${Math.random() * 100}%;top:${Math.random() * 100}%;width:${2 + Math.random() * 4}px;height:${2 + Math.random() * 4}px;animation-delay:${Math.random() * 8}s;animation-duration:${6 + Math.random() * 10}s;`;
        el.appendChild(p);
    }
}

function renderStars(rating) {
    if (!rating || rating == 0) return '<span class="text-muted small">Belum ada rating</span>';
    const numRating = parseFloat(rating);
    const full = Math.floor(numRating),
          half = numRating % 1 >= 0.5 ? 1 : 0,
          empty = 5 - full - half;
    return `${'<i class="bi bi-star-fill star-fill text-warning"></i>'.repeat(full)}${half ? '<i class="bi bi-star-half star-fill text-warning"></i>' : ""}${'<i class="bi bi-star star-empty text-muted opacity-50"></i>'.repeat(empty)}<span class="rating-text fw-bold text-dark ms-1">${numRating.toFixed(1)}</span>`;
}

// ── Kategori (Diselaraskan dengan Database MySQL) ────────────
const CAT_CONFIG = {
    "Alam": { label: "Alam & Hutan", color: "#2d9e6b", icon: "bi-tree" },
    "Pantai": { label: "Pantai & Laut", color: "#0ea5e9", icon: "bi-water" },
    "Gunung": { label: "Pegunungan", color: "#7c5c3b", icon: "bi-snow2" },
    "Budaya": { label: "Seni Budaya", color: "#e88a22", icon: "bi-building-fill" },
    "Sejarah": { label: "Situs Sejarah", color: "#9b4f96", icon: "bi-bank2" },
    
    // Fallback jika menggunakan bahasa Inggris
    "nature": { label: "Alam", color: "#2d9e6b", icon: "bi-tree" },
    "beach": { label: "Pantai", color: "#0ea5e9", icon: "bi-water" },
    "historical": { label: "Sejarah", color: "#9b4f96", icon: "bi-bank2" }
};

function getCatConfig(kategori) {
    return CAT_CONFIG[kategori] || { label: kategori || "Wisata", color: "#64748b", icon: "bi-geo-alt" };
}

// ── Format Tarif ────────────────────────────────────────────
function formatTarif(tarif) {
    if (!tarif || tarif.trim() === "" || tarif.toLowerCase().includes("gratis")) {
        return '<span class="htm-free text-success fw-bold">GRATIS / Sesuai Kebijakan</span>';
    }
    return `<span class="htm-price fw-bold text-dark">${tarif}</span>`;
}

// ============================================================
//  FITUR FAVORIT (AJAX KE DATABASE MYSQL)
// ============================================================

async function toggleFav(id, btn) {
    const destinasiId = parseInt(id);
    const originalHtml = btn.innerHTML;

    try {
        // Tampilkan animasi loading saat memproses ke database
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
        btn.disabled = true;

        // Pastikan URL file PHP pemroses sesuai dengan struktur foldermu
        const response = await fetch('proses_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ destinasi_id: destinasiId })
        });

        const result = await response.json();
        btn.disabled = false;

        if (result.status === 'added') {
            btn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Tersimpan';
            btn.classList.replace('btn-outline-secondary', 'btn-danger');
            btn.classList.add('text-white');

            // SINKRONISASI FRONTEND: Update status kartu asli di halaman agar tidak reset saat modal ditutup
            const cardElements = document.querySelectorAll(`[data-id="${destinasiId}"]`);
            cardElements.forEach(el => el.setAttribute('data-saved', 'true'));
        } 
        else if (result.status === 'removed') {
            btn.innerHTML = '<i class="bi bi-heart me-2"></i>Simpan Destinasi';
            btn.classList.replace('btn-danger', 'btn-outline-secondary');
            btn.classList.remove('text-white');
            
            // SINKRONISASI FRONTEND: Hapus status dari kartu asli di halaman
            const cardElements = document.querySelectorAll(`[data-id="${destinasiId}"]`);
            cardElements.forEach(el => el.setAttribute('data-saved', 'false'));

            // UX Pintar: Jika user "Un-favorite" saat berada di halaman wishlist, otomatis refresh halaman
            if(window.location.pathname.toLowerCase().includes('wishlist.php')) {
                setTimeout(() => window.location.reload(), 600);
            }
        } 
        else if (result.status === 'error') {
            alert(result.message);
            btn.innerHTML = originalHtml; 
            // Jika error karena belum login, arahkan ke halaman login
            if(result.message.toLowerCase().includes('login')) {
                window.location.href = 'auth/login.php'; 
            }
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server. Pastikan kamu sudah terhubung ke database.');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

// ============================================================
//  DETAIL MODAL (Diselaraskan dengan struktur Database)
// ============================================================

// Fungsi Baru: Dipanggil dengan tombol yang memiliki data attributes dari PHP
function openDetailBtn(btn) {
    const id = btn.getAttribute('data-id');
    const nama = btn.getAttribute('data-nama');
    const kategori = btn.getAttribute('data-kategori');
    const alamat = btn.getAttribute('data-alamat');
    const rating = btn.getAttribute('data-rating');
    const deskripsi = btn.getAttribute('data-deskripsi');
    const foto = btn.getAttribute('data-foto') || 'https://placehold.co/800x500/e2e8f0/64748b?text=Foto+Tidak+Tersedia';
    const maps = btn.getAttribute('data-maps') || '';
    const tarif = btn.getAttribute('data-tarif');
    const history = btn.getAttribute('data-history');
    const tips = btn.getAttribute('data-tips');
    
    const kontak = btn.hasAttribute('data-kontak') ? btn.getAttribute('data-kontak') : 'Tidak Tersedia';

    const cat = getCatConfig(kategori);
    const isHiddenGem = deskripsi && deskripsi.toLowerCase().includes('hidden gem');
    
    // =======================================================
    // LOGIKA TOMBOL NAVIGASI & SIMPAN (UPDATE DATABASE)
    // =======================================================
    
    // Mengecek apakah sedang di halaman wishlist.php (jika ya, otomatis statusnya = tersimpan)
    // Atau mengecek attribute data-saved="true" jika ada dari PHP atau hasil sinkronisasi JS
    const isSaved = window.location.pathname.toLowerCase().includes('wishlist.php') || btn.getAttribute('data-saved') === 'true';
    
    const favBtnClass = isSaved ? 'btn-danger text-white' : 'btn-outline-secondary';
    const favIcon = isSaved ? 'bi-heart-fill' : 'bi-heart';
    const favText = isSaved ? 'Tersimpan' : 'Simpan Destinasi';

    let mapsAction = '';
    
    // 2. Ekstrak URL Peta (antisipasi jika data berisi iframe dari DB)
    let mapUrl = maps;
    if (maps && maps.includes('src="')) {
        const match = maps.match(/src="([^"]+)"/);
        if(match) mapUrl = match[1];
    }

    // 3. Render Tombol Aksi (Tanpa view gambar map)
    if (mapUrl && mapUrl.startsWith('http')) {
        mapsAction = `
        <div class="d-flex flex-column gap-3 w-100">
            <a href="${mapUrl}" target="_blank" class="btn btn-danger rounded-pill fw-bold shadow-sm py-2">
                <i class="bi bi-cursor-fill me-2"></i>Mulai Navigasi Arah
            </a>
            <button type="button" class="btn ${favBtnClass} rounded-pill fw-bold shadow-sm py-2" onclick="toggleFav('${id}', this)">
                <i class="bi ${favIcon} me-2"></i>${favText}
            </button>
        </div>`;
    } else {
        mapsAction = `
        <div class="d-flex flex-column gap-3 w-100">
            <button type="button" class="btn btn-secondary rounded-pill fw-bold shadow-sm py-2" disabled>
                <i class="bi bi-map me-2"></i>Link Peta Belum Tersedia
            </button>
            <button type="button" class="btn ${favBtnClass} rounded-pill fw-bold shadow-sm py-2" onclick="toggleFav('${id}', this)">
                <i class="bi ${favIcon} me-2"></i>${favText}
            </button>
        </div>`;
    }

    const modalEl = document.getElementById("detailModal");
    if (!modalEl) return;

    document.getElementById("modalBody").innerHTML = `
    <div class="modal-header border-0 pb-0 px-4 px-lg-5 pt-4 pt-lg-5 d-flex justify-content-between align-items-start bg-white rounded-top-4">
        <div class="pe-4">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
              <span class="badge shadow-sm fs-6 px-3 py-2 rounded-pill text-white" style="background-color: ${cat.color};"><i class="bi ${cat.icon} me-1"></i>${cat.label}</span>
              ${isHiddenGem ? '<span class="badge bg-warning text-dark shadow-sm fs-6 px-3 py-2 rounded-pill"><i class="bi bi-gem me-1"></i>Hidden Gem</span>' : ""}
            </div>
            <h1 class="fw-bold display-5 text-dark mb-2">${nama}</h1>
            <p class="text-muted fs-5 mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i>${alamat}</p>
        </div>
        <button type="button" class="btn-close shadow-none bg-light p-2 rounded-circle mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body p-4 p-lg-5 bg-white rounded-bottom-4">
      
      <div class="mb-5 rounded-4 border shadow-sm overflow-hidden w-100" style="height: 500px;">
          <img src="${foto}" alt="${nama}" class="w-100 h-100" style="object-fit: cover; object-position: center;">
      </div>

      <div class="row g-5">
        
        <div class="col-lg-7 col-xl-8">
          <div class="mb-4 bg-light p-3 rounded-4 d-inline-block border shadow-sm">
            <div class="stars-row stars-lg d-flex align-items-center">${renderStars(rating)} <span class="ms-2 text-muted small">dari ulasan pengunjung</span></div>
          </div>
          
          <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Tentang Tempat Ini</h5>
          <p class="fs-6 text-muted mb-5" style="line-height: 1.8; text-align: justify;">${deskripsi || "Destinasi wisata menarik di Barlingmascakeb."}</p>
          
          ${history ? `
          <div class="mt-4 bg-info bg-opacity-10 p-4 rounded-4 border border-info border-opacity-25 mb-4">
            <h6 class="fw-bold text-info mb-3"><i class="bi bi-clock-history me-2"></i>SEJARAH & LATAR BELAKANG</h6>
            <p class="text-dark mb-0 fs-6" style="line-height: 1.8; text-align: justify;">${history}</p>
          </div>` : ""}

          ${tips ? `
          <div class="mt-4 bg-success bg-opacity-10 p-4 rounded-4 border border-success border-opacity-25 mb-4">
            <h6 class="fw-bold text-success mb-3"><i class="bi bi-lightbulb-fill me-2"></i>TIPS BERKUNJUNG</h6>
            <p class="text-dark mb-0 fs-6" style="line-height: 1.8; text-align: justify;">${tips}</p>
          </div>` : ""}
        </div>
        
        <div class="col-lg-5 col-xl-4">
          <div class="bg-warning bg-opacity-10 p-4 rounded-4 border border-warning mb-4 shadow-sm">
            <h6 class="fw-bold text-dark mb-4 border-bottom border-warning border-opacity-25 pb-2"><i class="bi bi-ticket-perforated-fill text-warning me-2"></i>INFORMASI TIKET & AKSES</h6>
            
            <div class="mb-3 d-flex justify-content-between align-items-center">
              <span class="text-muted small fw-bold text-uppercase tracking-wider">Estimasi Biaya</span>
              <div class="text-end">${formatTarif(tarif)}</div>
            </div>
            
            <div class="mb-3 d-flex justify-content-between align-items-center">
              <span class="text-muted small fw-bold text-uppercase tracking-wider">Kontak Tersedia</span>
              <div class="text-end fw-bold text-dark"><i class="bi bi-telephone-fill text-primary me-1"></i>${kontak}</div>
            </div>
          </div>

          <div class="w-100 mt-2">
              ${mapsAction}
          </div>
        </div>

      </div>
    </div>`;

    bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

// ============================================================
//  PENGINISIALISASIAN HALAMAN
// ============================================================
window.addEventListener('DOMContentLoaded', () => {
    initNavScroll();
    
    // Inisialisasi Partikel Hero jika ada
    if(document.getElementById("heroParticles")) {
        generateParticles();
    }
});

// Fungsi pencarian pintar (opsional, jika digunakan di index)
function debounce(fn, ms) {
    let t;
    return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); };
}