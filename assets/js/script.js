// ============================================================
//  Traveloop — Main Script & Configuration (PHP/MySQL Aligned)
// ============================================================

const CONFIG = {
    GOOGLE_MAPS_API_KEY: "https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap",
    DEFAULT_CENTER: { lat: -7.4226, lng: 109.2354 }, 
    DEFAULT_RADIUS: 50000,
};

let state = {
    places: [], filtered: [], currentPage: 1, perPage: 9, view: "grid",
    filters: { cat: "all", minRating: 0, search: "" }, sort: "prominence"
};

// Variabel Global Baru untuk menyimpan data rute yang sedang di-generate
let currentItineraryData = null;

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
    const full = Math.floor(numRating), half = numRating % 1 >= 0.5 ? 1 : 0, empty = 5 - full - half;
    return `${'<i class="bi bi-star-fill star-fill text-warning"></i>'.repeat(full)}${half ? '<i class="bi bi-star-half star-fill text-warning"></i>' : ""}${'<i class="bi bi-star star-empty text-muted opacity-50"></i>'.repeat(empty)}<span class="rating-text fw-bold text-dark ms-1">${numRating.toFixed(1)}</span>`;
}

const CAT_CONFIG = {
    "Alam": { label: "Alam & Hutan", color: "#2d9e6b", icon: "bi-tree" },
    "Pantai": { label: "Pantai & Laut", color: "#0ea5e9", icon: "bi-water" },
    "Gunung": { label: "Pegunungan", color: "#7c5c3b", icon: "bi-snow2" },
    "Budaya": { label: "Seni Budaya", color: "#e88a22", icon: "bi-building-fill" },
    "Sejarah": { label: "Situs Sejarah", color: "#9b4f96", icon: "bi-bank2" },
    "nature": { label: "Alam", color: "#2d9e6b", icon: "bi-tree" },
    "beach": { label: "Pantai", color: "#0ea5e9", icon: "bi-water" },
    "historical": { label: "Sejarah", color: "#9b4f96", icon: "bi-bank2" }
};

function getCatConfig(kategori) { return CAT_CONFIG[kategori] || { label: kategori || "Wisata", color: "#64748b", icon: "bi-geo-alt" }; }

function formatTarif(tarif) {
    if (!tarif || tarif.trim() === "" || tarif.toLowerCase().includes("gratis")) return '<span class="htm-free text-success fw-bold">GRATIS / Sesuai Kebijakan</span>';
    return `<span class="htm-price fw-bold text-dark">${tarif}</span>`;
}

async function toggleFav(id, btn) {
    const destinasiId = parseInt(id);
    const originalHtml = btn.innerHTML;
    try {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
        btn.disabled = true;

        const response = await fetch('api/wishlist.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggle', destinasi_id: destinasiId })
        });
        const result = await response.json();
        btn.disabled = false;

        if (result.status === 'added') {
            btn.innerHTML = '<i class="bi bi-heart-fill me-2"></i>Tersimpan';
            btn.classList.replace('btn-outline-secondary', 'btn-danger'); btn.classList.add('text-white');
            const cardElements = document.querySelectorAll(`[data-id="${destinasiId}"]`);
            cardElements.forEach(el => el.setAttribute('data-saved', 'true'));
            
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Destinasi ditambahkan ke Wishlist Anda!', timer: 1500, showConfirmButton: false });
        } 
        else if (result.status === 'removed') {
            btn.innerHTML = '<i class="bi bi-heart me-2"></i>Simpan Destinasi';
            btn.classList.replace('btn-danger', 'btn-outline-secondary'); btn.classList.remove('text-white');
            const cardElements = document.querySelectorAll(`[data-id="${destinasiId}"]`);
            cardElements.forEach(el => el.setAttribute('data-saved', 'false'));
            if(window.location.pathname.toLowerCase().includes('wishlist.php')) setTimeout(() => window.location.reload(), 600);
        } 
        else if (result.status === 'error') {
            btn.innerHTML = originalHtml; 
            Swal.fire({ icon: 'error', title: 'Oops...', text: result.message, confirmButtonColor: '#0d6efd' }).then(() => {
                if(result.message.toLowerCase().includes('login')) window.location.href = 'auth/login.php'; 
            });
        }
    } catch (error) {
        console.error('Error:', error); btn.innerHTML = originalHtml; btn.disabled = false;
        Swal.fire({ icon: 'error', title: 'Server Error', text: 'Terjadi kesalahan pada server. Pastikan kamu sudah terhubung ke database.', confirmButtonColor: '#dc3545' });
    }
}

function openDetailBtn(el) {
    const id = el.getAttribute('data-id');
    const nama = el.getAttribute('data-nama');
    const kategori = el.getAttribute('data-kategori');
    const alamat = el.getAttribute('data-alamat');
    const rating = el.getAttribute('data-rating') || '0.0';
    const deskripsi = el.getAttribute('data-deskripsi') || 'Belum ada deskripsi untuk tempat ini.';
    const foto = el.getAttribute('data-foto') || 'https://placehold.co/800x500/e2e8f0/64748b?text=Foto+Tidak+Tersedia';
    const maps = el.getAttribute('data-maps');
    const tarif = el.getAttribute('data-tarif') || 'Gratis / Sesuai Kebijakan';
    const history = el.getAttribute('data-history') || '';
    const tips = el.getAttribute('data-tips') || '';
    const kontak = el.hasAttribute('data-kontak') ? el.getAttribute('data-kontak') : 'Tidak tersedia';

    const cat = getCatConfig(kategori);
    const isHiddenGem = deskripsi && deskripsi.toLowerCase().includes('hidden gem');
    const isSaved = window.location.pathname.toLowerCase().includes('wishlist.php') || el.getAttribute('data-saved') === 'true';
    const favBtnClass = isSaved ? 'btn-danger text-white' : 'btn-outline-secondary';
    const favIcon = isSaved ? 'bi-heart-fill' : 'bi-heart';
    const favText = isSaved ? 'Tersimpan' : 'Simpan Destinasi';

    let mapUrl = maps;
    if (maps && maps.includes('<iframe') && maps.includes('src="')) {
        const match = maps.match(/src="([^"]+)"/);
        if(match) mapUrl = match[1];
    }

    let mapsAction = '';
    if (mapUrl && mapUrl.startsWith('http')) {
        mapsAction = `<div class="d-flex flex-column gap-2 mt-3"><a href="${mapUrl}" target="_blank" class="btn btn-primary rounded-pill fw-bold shadow-sm py-2"><i class="bi bi-cursor-fill me-2"></i>Mulai Navigasi Arah</a><button type="button" class="btn ${favBtnClass} rounded-pill fw-bold shadow-sm py-2" onclick="toggleFav('${id}', this)"><i class="bi ${favIcon} me-2"></i>${favText}</button></div>`;
    } else {
        mapsAction = `<div class="d-flex flex-column gap-2 mt-3"><button type="button" class="btn btn-light rounded-pill fw-bold shadow-sm py-2 text-muted" disabled><i class="bi bi-sign-turn-right me-2"></i>Navigasi Belum Tersedia</button><button type="button" class="btn ${favBtnClass} rounded-pill fw-bold shadow-sm py-2" onclick="toggleFav('${id}', this)"><i class="bi ${favIcon} me-2"></i>${favText}</button></div>`;
    }

    const modalBody = document.getElementById('modalBody');
    if (!modalBody) return;

    modalBody.innerHTML = `
        <div class="position-relative w-100 rounded-top-4 overflow-hidden" style="height: 280px;">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3 shadow" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.5); padding: 0.8rem; border-radius: 50%;"></button>
            <img src="${foto}" class="w-100 h-100 object-fit-cover" alt="${nama}" loading="lazy">
            <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(15,23,42,0.95), transparent);">
                <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-geo-alt-fill me-1"></i>${kategori}</span>
                ${isHiddenGem ? '<span class="badge bg-primary text-white fw-bold mb-2 px-3 py-2 rounded-pill shadow-sm ms-1"><i class="bi bi-gem me-1"></i>Hidden Gem</span>' : ''}
                <h2 class="text-white fw-bold mb-0">${nama}</h2>
            </div>
        </div>
        <div class="modal-body p-4 bg-light rounded-bottom-4">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Tentang Destinasi</h5>
                        <p class="text-muted" style="line-height: 1.6; text-align: justify;">${deskripsi}</p>
                    </div>
                    ${history && history !== 'Belum ada informasi sejarah.' ? `<div class="mb-4 bg-white p-3 p-md-4 rounded-4 shadow-sm border-start border-4 border-warning"><h6 class="fw-bold text-dark mb-2"><i class="bi bi-clock-history text-warning me-2"></i>Sejarah Singkat</h6><p class="text-muted mb-0 small" style="line-height: 1.6; text-align: justify;">${history}</p></div>` : ''}
                    ${tips && tips !== 'Belum ada tips berkunjung.' ? `<div class="mb-4 bg-white p-3 p-md-4 rounded-4 shadow-sm border-start border-4 border-success"><h6 class="fw-bold text-dark mb-2"><i class="bi bi-lightbulb-fill text-success me-2"></i>Tips Berkunjung</h6><p class="text-muted mb-0 small" style="line-height: 1.6; text-align: justify;">${tips}</p></div>` : ''}
                </div>
                <div class="col-lg-5">
                    <div class="bg-white p-4 rounded-4 shadow-sm border-0 sticky-top" style="top: 1rem;">
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-fill text-danger me-2"></i>Lokasi & Akses</h6>
                            <p class="text-muted small mb-0"><i class="bi bi-pin-map me-1"></i>${alamat}</p>
                            ${mapsAction}
                        </div>
                        <hr class="text-muted opacity-25 my-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-card-list text-info me-2"></i>Informasi Cepat</h6>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2"><span class="text-muted"><i class="bi bi-star-fill text-warning me-2"></i>Rating</span><span class="fw-bold text-dark">${rating} / 5.0</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2"><span class="text-muted"><i class="bi bi-ticket-perforated-fill text-success me-2"></i>Tiket Masuk</span><span class="fw-bold text-dark text-end" style="max-width: 60%;">${tarif}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2 border-bottom-0"><span class="text-muted"><i class="bi bi-telephone-fill text-primary me-2"></i>Kontak</span><span class="fw-bold text-dark text-end">${kontak}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>`;
    const myModal = new bootstrap.Modal(document.getElementById('detailModal'));
    myModal.show();
}

window.addEventListener('DOMContentLoaded', () => {
    initNavScroll();
    if(document.getElementById("heroParticles")) generateParticles();
});

function showItineraryModal() {
    if (typeof isUserLoggedIn !== 'undefined' && !isUserLoggedIn) {
        Swal.fire({ icon: 'warning', title: 'Akses Terkunci!', text: 'Anda harus Login terlebih dahulu untuk menggunakan fitur Smart Itinerary eksklusif ini.', showCancelButton: true, confirmButtonText: '<i class="bi bi-box-arrow-in-right me-1"></i> Login Sekarang', cancelButtonText: 'Batal', confirmButtonColor: '#0d6efd', cancelButtonColor: '#6c757d' }).then((result) => {
            if (result.isConfirmed) window.location.href = 'auth/login.php'; 
        });
        return false;
    }
    const myModal = new bootstrap.Modal(document.getElementById('itineraryModal'));
    myModal.show();
    return false;
}

function generateMockItinerary(tema) {
    const body = document.getElementById('itineraryModalBody');
    body.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div><h5 class="fw-bold">Menyusun Rencana Perjalanan...</h5><p class="text-muted small">AI Traveloop sedang meracik rute ${tema} terbaik dari database untuk Anda.</p></div>`;

    fetch(`api/itinerary.php?tema=${tema}`)
        .then(response => response.json())
        .then(res => {
            setTimeout(() => {
                if(res.status === 'error') {
                    body.innerHTML = `<div class="text-center py-4"><i class="bi bi-x-circle text-danger display-4 mb-3 d-block"></i><h5 class="fw-bold">Gagal Mengambil Data</h5><p class="text-muted">${res.message}</p></div>`;
                    return;
                }

                if(res.status === 'success' && res.data.length > 0) {
                    let places = res.data;
                    let kotaPencarian = res.kota_utama || 'Purwokerto';
                    
                    // SIMPAN KE VARIABEL GLOBAL UNTUK FUNGSI "SIMPAN KE AKUN SAYA" NANTI
                    currentItineraryData = {
                        tema: tema,
                        kota_utama: kotaPencarian,
                        destinasi_ids: places.map(p => p.id)
                    };
                    
                    let linkAfiliasiHotel = `https://www.agoda.com/id-id/search?city=${kotaPencarian}&cid=1891463`; 
                    let linkAfiliasiTransport = `https://www.traveloka.com/id-id/car-rental/city=${kotaPencarian}`; 
                    
                    let html = `<div id="pdf-content" class="p-2 bg-white rounded"><div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom"><div><h5 class="fw-bold text-dark mb-1">Paket Liburan ${tema}</h5><span class="badge bg-light text-dark border"><i class="bi bi-clock me-1"></i>Estimasi 8 Jam</span><span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i>${places.length} Destinasi</span></div><div class="no-print"><img src="assets/Image/Menu_Utama.png" alt="Traveloop" style="height: 30px;" onerror="this.style.display='none'"></div></div><div class="itin-timeline">`;

                    const timeSlots = [
                        { time: '08:00 WIB', title: 'Memulai Petualangan Pagi', color: 'warning' },
                        { time: '12:30 WIB', title: 'Eksplorasi Siang', color: 'danger' },
                        { time: '15:30 WIB', title: 'Menikmati Sore', color: 'primary' }
                    ];

                    places.forEach((place, index) => {
                        let slot = timeSlots[index] || { time: '18:00 WIB', title: 'Malam Hari', color: 'success' };
                        let tarif = place.tarif ? place.tarif : 'Sesuai Kebijakan';
                        let isLast = (index === places.length - 1);
                        let borderClass = isLast ? '' : `border-start border-2 border-${slot.color}`;
                        
                        html += `<div class="position-relative ${borderClass} ms-3 ps-4 pb-4"><div class="position-absolute bg-${slot.color} rounded-circle border border-white border-2" style="width: 16px; height: 16px; left: -9px; top: 0;"></div><p class="text-${slot.color} small fw-bold mb-1"><i class="bi bi-clock me-1"></i>${slot.time}</p><h6 class="fw-bold mb-2">${slot.title}</h6><div class="card border-0 shadow-sm bg-light rounded-4 overflow-hidden mt-2 position-relative"><div class="d-flex align-items-center p-2"><img src="${place.foto_url}" class="rounded-3 object-fit-cover" style="width: 70px; height: 70px;" alt="${place.nama}" crossorigin="anonymous"><div class="ms-3 pe-2"><h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 180px;">${place.nama}</h6><p class="text-muted small mb-1 text-truncate" style="max-width: 180px;">${place.alamat}</p><span class="badge bg-white text-dark border" style="font-size:0.7rem;"><i class="bi bi-ticket-perforated text-success me-1"></i>${tarif}</span></div></div></div></div>`;
                    });

                    html += `</div><div class="mt-4 pt-4 border-top"><div class="d-flex justify-content-between align-items-center mb-3"><h6 class="fw-bold text-dark mb-0"><i class="bi bi-bag-check-fill text-primary me-2"></i>Lengkapi Perjalananmu</h6><span class="badge bg-light text-muted border">Partner Resmi</span></div><div class="row g-2"><div class="col-6"><a href="${linkAfiliasiHotel}" target="_blank" class="text-decoration-none"><div class="card bg-primary bg-opacity-10 border-0 h-100 rounded-4 p-3 transition hover-zoom text-center"><i class="bi bi-buildings-fill fs-3 text-primary mb-1"></i><span class="fw-bold text-dark small d-block mb-1">Cari Hotel</span><span class="text-muted" style="font-size: 0.7rem;">Diskon s/d 30% di ${kotaPencarian}</span></div></a></div><div class="col-6"><a href="${linkAfiliasiTransport}" target="_blank" class="text-decoration-none"><div class="card bg-success bg-opacity-10 border-0 h-100 rounded-4 p-3 transition hover-zoom text-center"><i class="bi bi-car-front-fill fs-3 text-success mb-1"></i><span class="fw-bold text-dark small d-block mb-1">Sewa Mobil</span><span class="text-muted" style="font-size: 0.7rem;">Area ${kotaPencarian} & Sekitarnya</span></div></a></div></div></div></div><div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top no-print"><button class="btn btn-outline-secondary rounded-pill fw-bold flex-grow-1 py-2" onclick="generateMockItinerary('${tema}')"><i class="bi bi-arrow-clockwise me-1"></i>Acak Rute</button><button class="btn btn-danger rounded-pill fw-bold flex-grow-1 py-2" onclick="downloadPDF('${tema}', event)"><i class="bi bi-file-earmark-pdf-fill me-1"></i>Cetak PDF</button><button class="btn btn-primary rounded-pill fw-bold w-100 py-2 mt-1 shadow-sm" id="btnSimpanItinerary" onclick="simpanItinerary(this)"><i class="bi bi-cloud-arrow-up-fill me-1"></i>Simpan ke Akun Saya</button></div>`;
                    
                    body.innerHTML = html;
                } else {
                    body.innerHTML = `<div class="text-center py-4"><i class="bi bi-exclamation-circle text-warning display-4 mb-3 d-block"></i><h5 class="fw-bold">Data Tidak Cukup</h5><p class="text-muted">Maaf, belum ada cukup destinasi dengan tema ${tema} di database kami.</p></div>`;
                }
            }, 1200);
        })
        .catch(error => {
            body.innerHTML = `<div class="alert alert-danger">Gagal menghubungi server. Silakan coba lagi.</div>`;
        });
}

function downloadPDF(tema, event) {
    const element = document.getElementById('pdf-content');
    const opt = { margin: [10, 10, 10, 10], filename: `Traveloop_Itinerary_${tema}.pdf`, image: { type: 'jpeg', quality: 0.98 }, html2canvas: { scale: 2, useCORS: true }, jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' } };
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyiapkan PDF...';
    btn.disabled = true;

    html2pdf().set(opt).from(element).save().then(() => {
        btn.innerHTML = originalText; btn.disabled = false;
        Swal.fire({ icon: 'success', title: 'PDF Berhasil Diunduh!', text: 'Itinerary siap dibagikan.', timer: 2000, showConfirmButton: false });
    });
}

// FUNGSI BARU: Simpan Itinerary
function simpanItinerary(btn) {
    if (!currentItineraryData) {
        Swal.fire('Error', 'Data itinerary kosong. Silakan acak rute kembali.', 'error');
        return;
    }

    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    btn.disabled = true;

    fetch('api/simpan_itinerary.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(currentItineraryData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disimpan!',
                text: 'Itinerary kamu telah masuk ke halaman Itinerary Saya.',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#198754',
                confirmButtonText: 'Tutup',
                cancelButtonText: 'Lihat Itinerary Saya'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = 'itinerary_saya.php';
                }
            });
        } else {
            Swal.fire('Gagal Menyimpan', data.message, 'error');
        }
    })
    .catch(err => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        Swal.fire('Server Error', 'Gagal menghubungi server.', 'error');
    });
}