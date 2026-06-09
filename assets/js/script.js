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
//  FITUR FAVORIT (Menyimpan ID ke LocalStorage)
// ============================================================
function getFavs() {
    try { return JSON.parse(localStorage.getItem("tl_favs") || "[]"); } catch { return []; }
}
function isFav(id) {
    return getFavs().includes(id.toString());
}
function toggleFav(id, btn) {
    id = id.toString();
    let favs = getFavs();
    if (favs.includes(id)) {
        favs = favs.filter((f) => f !== id);
        btn.innerHTML = '<i class="bi bi-heart me-2"></i>Simpan Destinasi';
        btn.classList.replace('btn-danger', 'btn-outline-secondary');
        btn.classList.remove('text-white');
    } else {
        favs.push(id);
        btn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Tersimpan';
        btn.classList.replace('btn-outline-secondary', 'btn-danger');
        btn.classList.add('text-white');
    }
    localStorage.setItem("tl_favs", JSON.stringify(favs));
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
    // LOGIKA RENDER MAPS (MENCEGAH ERROR LAYAR HITAM)
    // =======================================================
    let mapsIframe = '';
    
    if (maps && maps.includes('<iframe')) {
        mapsIframe = `<div class="w-100 h-100 rounded-4 overflow-hidden shadow-sm border border-light">${maps}</div>`;
    } else if (maps && maps.includes('embed')) {
        mapsIframe = `<div class="w-100 h-100 rounded-4 overflow-hidden shadow-sm border border-light">
                        <iframe src="${maps}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                      </div>`;
    } else if (maps && maps.startsWith('http')) {
        // Thumbnail Peta yang Bisa Diklik (PERBAIKAN FINAL DI SINI)
        // Jadikan rute navigasi Google Maps sebagai link d-block agar seluruh area kotak bisa diklik.
        mapsIframe = `
        <a href="${maps}" target="_blank" class="d-block w-100 h-100 position-relative rounded-4 overflow-hidden border shadow-sm text-decoration-none">
            <div class="position-absolute w-100 h-100" style="background-image: url('https://placehold.co/600x400/e2e8f0/94a3b8?text=🗺️+Klik+Untuk+Buka+Peta'); background-size: cover; background-position: center;"></div>

            <div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.35);">
                <i class="bi bi-geo-alt-fill text-danger display-3 mb-2" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));"></i>
                <div class="btn btn-danger rounded-pill fw-bold shadow-lg"><i class="bi bi-cursor-fill me-2"></i>Mulai Navigasi Arah</div>
            </div>
        </a>`;
    } else {
        mapsIframe = `
        <div class="h-100 d-flex flex-column align-items-center justify-content-center bg-light rounded-4 p-5 text-center border shadow-sm" style="min-height: 250px;">
            <i class="bi bi-map-fill display-4 d-block mb-3 opacity-25 text-muted"></i>
            <span class="fs-6 fw-bold text-muted">Link Peta Belum Diisi di Database</span>
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

          <div class="w-100" style="height: 250px;">
              ${mapsIframe}
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