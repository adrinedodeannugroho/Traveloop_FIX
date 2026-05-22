// ============================================================
//  Traveloop — Main Script & Configuration (PHP/MySQL Aligned)
// ============================================================

const CONFIG = {
    // Masukkan API Key Google Maps kamu di sini
    GOOGLE_MAPS_API_KEY: "https://maps.app.goo.gl/L4ihaVqDXnn4GRnz7",
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
    return `${'<i class="bi bi-star-fill star-fill"></i>'.repeat(full)}${half ? '<i class="bi bi-star-half star-fill"></i>' : ""}${'<i class="bi bi-star star-empty"></i>'.repeat(empty)}<span class="rating-text ms-1">${numRating.toFixed(1)}</span>`;
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
        return '<span class="htm-free">GRATIS</span>';
    }
    return `<span class="htm-price">${tarif}</span>`;
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
        btn.innerHTML = '<i class="bi bi-heart"></i>';
    } else {
        favs.push(id);
        btn.innerHTML = '<i class="bi bi-heart-fill fav-active text-danger"></i>';
    }
    localStorage.setItem("tl_favs", JSON.stringify(favs));
}

// ============================================================
//  DETAIL MODAL (Diselaraskan dengan struktur Database)
// ============================================================
function openDetail(placeId) {
    // Fungsi ini dirancang agar bisa menerima data langsung dari elemen HTML 
    // jika data di-render secara dinamis oleh PHP (menggunakan attribute data-*)
    // Contoh penggunaannya di tombol: onclick="openDetailBtn(this)"
    console.warn("Gunakan openDetailBtn(this) untuk integrasi PHP yang lebih baik.");
}

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

    const cat = getCatConfig(kategori);
    const isHiddenGem = deskripsi && deskripsi.toLowerCase().includes('hidden gem');
    
    // Ekstrak URL Maps atau gunakan fallback
    let mapsIframe = '';
    if (maps.includes('<iframe')) {
        mapsIframe = maps; // Jika admin memasukkan Iframe
    } else if (CONFIG.GOOGLE_MAPS_API_KEY !== "YOUR_GOOGLE_MAPS_API_KEY") {
        mapsIframe = `<iframe width="100%" height="250" style="border:0;border-radius:12px;" loading="lazy" allowfullscreen 
                      src="https://www.google.com/maps/embed/v1/place?key=${CONFIG.GOOGLE_MAPS_API_KEY}&q=${encodeURIComponent(nama + ' ' + alamat)}"></iframe>`;
    } else {
        mapsIframe = `<div class="map-fallback py-5 bg-light rounded-3 text-center border">
                        <i class="bi bi-map display-4 text-muted mb-2 d-block"></i>
                        <p class="text-muted small">Peta tidak tersedia</p>
                        ${maps ? `<a href="${maps}" target="_blank" class="btn btn-primary btn-sm">Buka Link Maps</a>` : ''}
                      </div>`;
    }

    const modalEl = document.getElementById("detailModal");
    if (!modalEl) return;

    document.getElementById("modalBody").innerHTML = `
    <div class="detail-hero" style="background-image:url('${foto}')">
      <div class="detail-hero-overlay"></div>
      <div class="detail-hero-content">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="cat-badge shadow-sm" style="--cat-col:${cat.color}"><i class="bi ${cat.icon} me-1"></i>${cat.label}</span>
          ${isHiddenGem ? '<span class="gem-badge-detail shadow-sm"><i class="bi bi-gem me-1"></i>Hidden Gem</span>' : ""}
        </div>
        <h2 class="detail-title mt-2">${nama}</h2>
        <p class="detail-addr mb-0"><i class="bi bi-geo-alt-fill text-warning me-2"></i>${alamat}</p>
      </div>
    </div>
    <div class="detail-body p-4 p-md-5 bg-white">
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="detail-rating-row mb-4 bg-light p-3 rounded-3 d-inline-block border">
            <div class="stars-row stars-lg">${renderStars(rating)}</div>
          </div>
          <p class="detail-desc lead fs-6 text-muted">${deskripsi || "Destinasi wisata menarik di Barlingmascakeb."}</p>
          
          <div class="detail-htm-box mb-4">
            <div class="detail-htm-title"><i class="bi bi-ticket-perforated me-2"></i>Informasi Tiket & Akses</div>
            <div class="detail-htm-row mt-2">
              <div class="detail-htm-item"><span class="htm-label">Estimasi Biaya</span><span class="fs-5">${formatTarif(tarif)}</span></div>
            </div>
          </div>
          
          ${tips ? `
          <div class="detail-tips mt-4">
            <div class="detail-section-title"><i class="bi bi-lightbulb-fill text-warning me-2"></i>Tips Berkunjung</div>
            <p class="tips-text">${tips}</p>
          </div>` : ""}
          
          ${history ? `
          <div class="detail-history mt-4">
            <div class="detail-section-title"><i class="bi bi-book-half text-primary me-2"></i>Sejarah & Latar Belakang</div>
            <p class="history-text">${history}</p>
          </div>` : ""}
          
          <div class="detail-actions mt-5 pt-3 border-top d-flex flex-wrap gap-2">
            ${maps ? `<a href="${maps.includes('<') ? '#' : maps}" target="_blank" class="btn btn-primary"><i class="bi bi-cursor-fill me-2"></i>Rute Maps</a>` : ''}
            <button class="btn btn-outline-secondary" onclick="toggleFav('${id}', this)">
              <i class="bi bi-heart${isFav(id) ? "-fill text-danger" : ""} me-2"></i>Simpan Destinasi
            </button>
          </div>
        </div>
        
        <div class="col-lg-5">
          <div class="detail-map-wrap mb-4 shadow-sm">${mapsIframe}</div>
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