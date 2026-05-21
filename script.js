// ============================================================
//  Traveloop — Main Script v3
//  Features: Detail lengkap, HTM, sejarah, hidden gem,
//            rekomendasi, itinerary 1-hari, nearby places,
//            smart search tracking
// ============================================================

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
    maxDistance: null,
    search: "",
    tags: [],
  },
  sort: "prominence",
  userLocation: null,
};

// ============================================================
//  CORE UI
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
  if (!rating) return '<span class="text-muted small">Belum ada rating</span>';
  const full = Math.floor(rating),
    half = rating % 1 >= 0.5 ? 1 : 0,
    empty = 5 - full - half;
  return `${'<i class="bi bi-star-fill star-fill"></i>'.repeat(full)}${half ? '<i class="bi bi-star-half star-fill"></i>' : ""}${'<i class="bi bi-star star-empty"></i>'.repeat(empty)}<span class="rating-text ms-1">${rating.toFixed(1)}</span>`;
}

// ── Category config ───────────────────────────────────────────
const CAT_CONFIG = {
  nature: { label: "Alam", color: "#2d9e6b", icon: "bi-tree" },
  beach: { label: "Pantai", color: "#0ea5e9", icon: "bi-water" },
  mountain: { label: "Gunung", color: "#7c5c3b", icon: "bi-snow2" },
  cultural: { label: "Budaya", color: "#e88a22", icon: "bi-building-fill" },
  historical: { label: "Sejarah", color: "#9b4f96", icon: "bi-bank2" },
};

function getCatConfig(place) {
  return (
    CAT_CONFIG[place.category || guessCategory(place)] || {
      label: "Wisata",
      color: "#64748b",
      icon: "bi-geo-alt",
    }
  );
}
function guessCategory(place) {
  const t = ((place.types || []).join(" ") + (place.name || "")).toLowerCase();
  if (t.includes("beach") || t.includes("pantai")) return "beach";
  if (t.includes("mountain") || t.includes("gunung")) return "mountain";
  if (t.includes("candi") || t.includes("museum")) return "historical";
  if (t.includes("taman") || t.includes("curug") || t.includes("danau"))
    return "nature";
  return "cultural";
}

function getPhotoUrl(place, maxW = 600) {
  if (place.photo_url) return place.photo_url;
  const cats = {
    nature: "2d9e6b,134e30",
    beach: "0ea5e9,0369a1",
    mountain: "7c5c3b,3d2e1e",
    cultural: "e88a22,b45309",
    historical: "9b4f96,5b2168",
  };
  const c = cats[place.category] || "64748b,334155";
  return `https://placehold.co/600x400/${c.split(",")[0]}/${c.split(",")[1]}?text=${encodeURIComponent(place.name)}`;
}

// ── Format rupiah ────────────────────────────────────────────
function rupiah(n) {
  if (!n || n === 0) return '<span class="htm-free">GRATIS</span>';
  return `<span class="htm-price">Rp ${n.toLocaleString("id-ID")}</span>`;
}

// ============================================================
//  PLACE CARD
// ============================================================

function getFavs() {
  try {
    return JSON.parse(localStorage.getItem("tl_favs") || "[]");
  } catch {
    return [];
  }
}
function isFav(id) {
  return getFavs().includes(id);
}
function toggleFav(id, btn) {
  let favs = getFavs();
  if (favs.includes(id)) {
    favs = favs.filter((f) => f !== id);
    btn.innerHTML = '<i class="bi bi-heart"></i>';
  } else {
    favs.push(id);
    btn.innerHTML = '<i class="bi bi-heart-fill fav-active"></i>';
  }
  localStorage.setItem("tl_favs", JSON.stringify(favs));
}

function createPlaceCard(place, mode = "grid") {
  const cat = getCatConfig(place);
  const photo = getPhotoUrl(place);
  const addr = place.vicinity || "Banyumas, Jawa Tengah";
  const htm = place.htm ? rupiah(place.htm.weekday) : "";
  const dist = place.distance_from_purwokerto
    ? `<span class="card-dist"><i class="bi bi-signpost-2 me-1"></i>${place.distance_from_purwokerto} km</span>`
    : "";
  const gemBadge = place.hidden_gem
    ? '<span class="gem-badge"><i class="bi bi-gem me-1"></i>Hidden Gem</span>'
    : "";

  if (mode === "list") {
    return `<div class="col-12">
      <div class="place-card place-card-list" onclick="openDetail('${place.place_id}')">
        <div class="place-card-list-img">
          <img src="${photo}" alt="${place.name}" loading="lazy" onerror="this.style.background='#e2e8f0'"/>
          ${gemBadge}
        </div>
        <div class="place-card-list-body">
          <span class="cat-badge" style="--cat-col:${cat.color}"><i class="bi ${cat.icon} me-1"></i>${cat.label}</span>
          <h5 class="place-card-title mt-2">${place.name}</h5>
          <p class="place-card-addr"><i class="bi bi-geo-alt me-1"></i>${addr}</p>
          <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
            <div class="stars-row">${renderStars(place.rating)}</div>
            ${dist}
            <span class="ms-auto">${htm}</span>
          </div>
          ${place.description ? `<p class="place-card-desc">${place.description.slice(0, 120)}…</p>` : ""}
        </div>
        <div class="place-card-list-action">
          <button class="btn btn-detail">Lihat <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
    </div>`;
  }

  return `<div class="col-12 col-md-6 col-xl-4">
    <div class="place-card" onclick="openDetail('${place.place_id}')">
      <div class="place-card-img-wrap">
        <img src="${photo}" class="place-card-img" alt="${place.name}" loading="lazy" onerror="this.style.background='#e2e8f0'"/>
        <span class="cat-badge cat-badge-overlay" style="--cat-col:${cat.color}"><i class="bi ${cat.icon} me-1"></i>${cat.label}</span>
        ${gemBadge ? `<span class="gem-badge-overlay"><i class="bi bi-gem me-1"></i>Hidden Gem</span>` : ""}
        <button class="place-fav-btn" onclick="event.stopPropagation();toggleFav('${place.place_id}',this)">
          <i class="bi bi-heart${isFav(place.place_id) ? "-fill fav-active" : ""}"></i>
        </button>
      </div>
      <div class="place-card-body">
        <h5 class="place-card-title">${place.name}</h5>
        <p class="place-card-addr"><i class="bi bi-geo-alt me-1"></i>${addr}</p>
        <div class="place-card-footer">
          <div class="stars-row">${renderStars(place.rating)}</div>
          <div class="d-flex align-items-center gap-2">${dist}${htm}</div>
        </div>
      </div>
    </div>
  </div>`;
}

// ============================================================
//  DETAIL MODAL — Full info: sejarah, HTM, fasilitas, nearby
// ============================================================

function openDetail(placeId) {
  let place = state.places.find((p) => p.place_id === placeId);
  if (!place) place = DB.getPlaces().find((p) => p.place_id === placeId);
  if (!place) return;

  // Make sure global places is populated for nearby
  if (!state.places.length) state.places = DB.getPlaces();

  const cat = getCatConfig(place);
  const photo = getPhotoUrl(place, 900);
  const addr =
    place.formatted_address || place.vicinity || "Banyumas, Jawa Tengah";
  const lat = place.geometry?.location?.lat || CONFIG.DEFAULT_CENTER.lat;
  const lng = place.geometry?.location?.lng || CONFIG.DEFAULT_CENTER.lng;
  const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.name + " " + addr)}`;

  // HTM block
  const htmBlock = place.htm
    ? `
    <div class="detail-htm-box">
      <div class="detail-htm-title"><i class="bi bi-ticket-perforated me-2"></i>Harga Tiket Masuk</div>
      <div class="detail-htm-row">
        <div class="detail-htm-item"><span class="htm-label">Weekday</span>${rupiah(place.htm.weekday)}</div>
        <div class="detail-htm-item"><span class="htm-label">Weekend</span>${rupiah(place.htm.weekend)}</div>
        <div class="detail-htm-item"><span class="htm-label">Parkir</span>${rupiah(place.htm.parking)}</div>
      </div>
      ${place.htm.note ? `<p class="htm-note"><i class="bi bi-info-circle me-1"></i>${place.htm.note}</p>` : ""}
    </div>`
    : "";

  // Open hours
  const hoursBlock = place.open_hours
    ? `
    <div class="detail-info-item">
      <i class="bi bi-clock-fill" style="color:var(--accent)"></i>
      <span><strong>${place.open_hours.days}</strong><br>${place.open_hours.open} – ${place.open_hours.close} WIB</span>
    </div>`
    : "";

  // Distance
  const distBlock = place.distance_from_purwokerto
    ? `
    <div class="detail-info-item">
      <i class="bi bi-signpost-2-fill" style="color:#0ea5e9"></i>
      <span><strong>Jarak dari Purwokerto</strong><br>~${place.distance_from_purwokerto} km (~${Math.round((place.distance_from_purwokerto / 40) * 60)} menit berkendara)</span>
    </div>`
    : "";

  // Facilities
  const facBlock = place.facilities?.length
    ? `
    <div class="detail-facilities mt-3">
      <div class="detail-section-title"><i class="bi bi-grid-3x3-gap me-2"></i>Fasilitas</div>
      <div class="facility-chips">${place.facilities.map((f) => `<span class="facility-chip"><i class="bi bi-check2-circle me-1"></i>${f}</span>`).join("")}</div>
    </div>`
    : "";

  // Tips block
  const tipsBlock = place.tips
    ? `
    <div class="detail-tips mt-3">
      <div class="detail-section-title"><i class="bi bi-lightbulb me-2"></i>Tips Berkunjung</div>
      <p class="tips-text">${place.tips}</p>
    </div>`
    : "";

  // History block
  const histBlock = place.history
    ? `
    <div class="detail-history mt-3">
      <div class="detail-section-title"><i class="bi bi-book me-2"></i>Sejarah & Latar Belakang</div>
      <p class="history-text">${place.history}</p>
    </div>`
    : "";

  // Tags
  const tagsBlock = place.tags?.length
    ? `
    <div class="mt-3">
      ${place.tags.map((t) => `<span class="place-tag">#${t}</span>`).join("")}
    </div>`
    : "";

  // Nearby places (same category, within ~20km)
  const nearby = state.places
    .filter((p) => {
      if (p.place_id === placeId) return false;
      const d = haversine(
        lat,
        lng,
        p.geometry?.location?.lat || 0,
        p.geometry?.location?.lng || 0,
      );
      return d < 20000;
    })
    .slice(0, 3);

  const nearbyBlock = nearby.length
    ? `
    <div class="detail-nearby mt-4">
      <div class="detail-section-title mb-3"><i class="bi bi-geo me-2"></i>Wisata Terdekat</div>
      <div class="row g-3">${nearby
        .map(
          (p) => `
        <div class="col-12">
          <div class="nearby-card" onclick="openDetail('${p.place_id}')">
            <img src="${getPhotoUrl(p, 120)}" alt="${p.name}" loading="lazy"/>
            <div class="nearby-info">
              <div class="nearby-name">${p.name}</div>
              <div class="nearby-dist"><i class="bi bi-geo-alt me-1"></i>${Math.round((haversine(lat, lng, p.geometry?.location?.lat || 0, p.geometry?.location?.lng || 0) / 1000) * 10) / 10} km</div>
              <div class="stars-row" style="font-size:.7rem">${renderStars(p.rating)}</div>
            </div>
          </div>
        </div>`,
        )
        .join("")}
      </div>
    </div>`
    : "";

  // Map embed (or fallback static link)
  const mapEmbed =
    CONFIG.GOOGLE_MAPS_API_KEY !== "YOUR_GOOGLE_MAPS_API_KEY"
      ? `<iframe width="100%" height="220" style="border:0;border-radius:12px;" loading="lazy" allowfullscreen
        src="https://www.google.com/maps/embed/v1/place?key=${CONFIG.GOOGLE_MAPS_API_KEY}&q=${encodeURIComponent(place.name)}&center=${lat},${lng}&zoom=14"></iframe>`
      : `<div class="map-fallback">
        <i class="bi bi-map display-4 text-muted mb-2 d-block"></i>
        <p class="text-muted small">Koordinat: ${lat.toFixed(4)}, ${lng.toFixed(4)}</p>
        <a href="${mapsUrl}" target="_blank" class="btn btn-gmaps btn-sm w-100">
          <i class="bi bi-map me-2"></i>Buka di Google Maps
        </a>
      </div>`;

  const modalEl = document.getElementById("detailModal");
  if (!modalEl) return;

  document.getElementById("modalBody").innerHTML = `
  <div class="detail-hero" style="background-image:url('${photo}')">
    <div class="detail-hero-overlay"></div>
    <div class="detail-hero-content">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="cat-badge" style="--cat-col:${cat.color}"><i class="bi ${cat.icon} me-1"></i>${cat.label}</span>
        ${place.hidden_gem ? '<span class="gem-badge-detail"><i class="bi bi-gem me-1"></i>Hidden Gem</span>' : ""}
      </div>
      <h2 class="detail-title mt-2">${place.name}</h2>
      <p class="detail-addr mb-0"><i class="bi bi-geo-alt me-2"></i>${addr}</p>
    </div>
  </div>
  <div class="detail-body p-4 p-md-5">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="detail-rating-row mb-3">
          <div class="stars-row stars-lg">${renderStars(place.rating)}</div>
          ${place.user_ratings_total ? `<span class="ms-2 text-muted">(${place.user_ratings_total.toLocaleString()} ulasan)</span>` : ""}
        </div>
        <p class="detail-desc">${place.description || "Destinasi wisata menarik di Banyumas."}</p>
        ${tagsBlock}
        ${htmBlock}
        <div class="detail-info-grid mt-3">
          ${hoursBlock}
          ${distBlock}
          <div class="detail-info-item">
            <i class="bi bi-geo-alt-fill" style="color:#ef4444"></i>
            <span><strong>Alamat</strong><br>${addr}</span>
          </div>
        </div>
        ${facBlock}
        ${tipsBlock}
        ${histBlock}
        <div class="detail-actions mt-4">
          <a href="${mapsUrl}" target="_blank" class="btn btn-gmaps me-2">
            <i class="bi bi-map me-2"></i>Buka Google Maps
          </a>
          <button class="btn btn-outline-secondary me-2" onclick="toggleFav('${place.place_id}',this)">
            <i class="bi bi-heart${isFav(place.place_id) ? "-fill text-danger" : ""} me-2"></i>Simpan
          </button>
          <button class="btn btn-itinerary-sm" onclick="openItineraryFromDetail('${place.place_id}')">
            <i class="bi bi-calendar-check me-2"></i>Buat Itinerary
          </button>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="detail-map-wrap mb-3">${mapEmbed}</div>
        ${nearbyBlock}
      </div>
    </div>
  </div>`;

  bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

// ============================================================
//  EXPLORE / CATALOG PAGE
// ============================================================

function initExplorePage() {
  const params = new URLSearchParams(window.location.search);
  const q = params.get("q"),
    cat = params.get("cat");

  // Setup UI dulu (search, filter buttons, dll)
  if (q) {
    trackSearch(q);
    const el = document.getElementById("filterSearch");
    if (el) el.value = q;
  }
  if (cat) setActiveCategory(cat);

  const rr = document.getElementById("ratingRange"),
    rv = document.getElementById("ratingVal");
  if (rr)
    rr.addEventListener(
      "input",
      () => rv && (rv.textContent = rr.value == 0 ? "Semua" : `${rr.value}★+`),
    );

  document.querySelectorAll(".cat-filter-btn").forEach((btn) =>
    btn.addEventListener("click", () => {
      document
        .querySelectorAll(".cat-filter-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      state.filters.cat = btn.dataset.cat;
      state.currentPage = 1;
      applyFilters();
    }),
  );

  const fs = document.getElementById("filterSearch");
  if (fs)
    fs.addEventListener(
      "input",
      debounce(() => {
        const v = fs.value.trim();
        if (v.length >= 2) trackSearch(v);
        state.filters.search = v.toLowerCase();
        state.currentPage = 1;
        applyFilters();
      }, 400),
    );

  // Fungsi render setelah data siap
  function startWithData(places) {
    state.places = places;
    state.filtered = [...places];
    if (q) state.filters.search = q.toLowerCase();
    if (cat) state.filters.cat = cat;
    applyFilters();
  }

  // Cek apakah data Firestore sudah tersedia atau tunggu event
  if (window.firestorePlaces && window.firestorePlaces.length) {
    startWithData(window.firestorePlaces);
  } else {
    document.addEventListener("placesLoaded", (e) => startWithData(e.detail), {
      once: true,
    });
  }
}

function setActiveCategory(cat) {
  document
    .querySelectorAll(".cat-filter-btn")
    .forEach((btn) => btn.classList.toggle("active", btn.dataset.cat === cat));
}

function applyFilters() {
  const rr = document.getElementById("ratingRange"),
    ds = document.getElementById("distanceFilter");
  state.filters.minRating = rr ? parseFloat(rr.value) : 0;
  state.filters.maxDistance = ds
    ? ds.value
      ? parseInt(ds.value)
      : null
    : null;

  let places = [...state.places];
  if (state.filters.search) {
    const q = state.filters.search;
    places = places.filter(
      (p) =>
        p.name.toLowerCase().includes(q) ||
        (p.vicinity || "").toLowerCase().includes(q) ||
        (p.description || "").toLowerCase().includes(q) ||
        (p.tags || []).some((t) => t.includes(q)),
    );
  }
  if (state.filters.cat !== "all")
    places = places.filter(
      (p) => (p.category || guessCategory(p)) === state.filters.cat,
    );
  if (state.filters.minRating > 0)
    places = places.filter((p) => (p.rating || 0) >= state.filters.minRating);
  // Hidden gem filter
  if (state.filters.gemOnly) places = places.filter((p) => p.hidden_gem);
  if (state.filters.maxDistance && state.userLocation) {
    places = places.filter((p) => {
      const loc = p.geometry?.location;
      if (!loc) return true;
      return (
        haversine(
          state.userLocation.lat,
          state.userLocation.lng,
          loc.lat,
          loc.lng,
        ) <= state.filters.maxDistance
      );
    });
  }
  state.filtered = places;
  sortResults();
}

function sortResults() {
  const sel = document.getElementById("sortSelect");
  state.sort = sel?.value || "prominence";
  const places = [...state.filtered];
  if (state.sort === "rating")
    places.sort((a, b) => (b.rating || 0) - (a.rating || 0));
  else if (state.sort === "name")
    places.sort((a, b) => a.name.localeCompare(b.name));
  else if (state.sort === "distance")
    places.sort(
      (a, b) =>
        (a.distance_from_purwokerto || 99) - (b.distance_from_purwokerto || 99),
    );
  state.filtered = places;
  state.currentPage = 1;
  renderGrid();
}

function renderGrid() {
  const grid = document.getElementById("placeGrid"),
    info = document.getElementById("resultsInfo");
  const total = state.filtered.length,
    start = (state.currentPage - 1) * state.perPage;
  if (info) info.textContent = `${total} destinasi ditemukan`;
  if (total === 0) {
    grid.innerHTML = `<div class="col-12 text-center py-5">
      <i class="bi bi-search display-4 text-muted"></i>
      <p class="mt-3 text-muted">Tidak ada destinasi yang cocok.</p>
      <button class="btn btn-outline-brand mt-2" onclick="resetFilters()">Reset Filter</button>
    </div>`;
    renderPagination(0);
    return;
  }
  grid.innerHTML = state.filtered
    .slice(start, start + state.perPage)
    .map((p) => createPlaceCard(p, state.view))
    .join("");
  renderPagination(total);
}

function renderPagination(total) {
  const wrap = document.getElementById("paginationWrap");
  if (!wrap) return;
  const pages = Math.ceil(total / state.perPage);
  if (pages <= 1) {
    wrap.innerHTML = "";
    return;
  }
  let html = '<nav><ul class="pagination wn-pagination">';
  html += `<li class="page-item ${state.currentPage === 1 ? "disabled" : ""}"><button class="page-link" onclick="goPage(${state.currentPage - 1})"><i class="bi bi-chevron-left"></i></button></li>`;
  for (let i = 1; i <= pages; i++) {
    if (i === 1 || i === pages || Math.abs(i - state.currentPage) <= 1)
      html += `<li class="page-item ${i === state.currentPage ? "active" : ""}"><button class="page-link" onclick="goPage(${i})">${i}</button></li>`;
    else if (Math.abs(i - state.currentPage) === 2)
      html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
  }
  html += `<li class="page-item ${state.currentPage === pages ? "disabled" : ""}"><button class="page-link" onclick="goPage(${state.currentPage + 1})"><i class="bi bi-chevron-right"></i></button></li></ul></nav>`;
  wrap.innerHTML = html;
}

function goPage(p) {
  state.currentPage = p;
  renderGrid();
  window.scrollTo({ top: 200, behavior: "smooth" });
}
function setView(v) {
  state.view = v;
  document
    .getElementById("gridToggle")
    ?.classList.toggle("active", v === "grid");
  document
    .getElementById("listToggle")
    ?.classList.toggle("active", v === "list");
  renderGrid();
}
function resetFilters() {
  state.filters = {
    cat: "all",
    minRating: 0,
    maxDistance: null,
    search: "",
    tags: [],
  };
  const rr = document.getElementById("ratingRange"),
    rv = document.getElementById("ratingVal");
  const fs = document.getElementById("filterSearch"),
    ds = document.getElementById("distanceFilter");
  if (rr) rr.value = 0;
  if (rv) rv.textContent = "Semua";
  if (fs) fs.value = "";
  if (ds) ds.value = "";
  document
    .querySelectorAll(".cat-filter-btn")
    .forEach((b) => b.classList.toggle("active", b.dataset.cat === "all"));
  state.filtered = [...state.places];
  state.currentPage = 1;
  renderGrid();
}

// ── Featured (homepage) ──────────────────────────────────────
function loadFeatured() {
  const grid = document.getElementById("featuredGrid");
  if (!grid) return;
  function render(places) {
    state.places = places;
    const top = [...places]
      .sort((a, b) => (b.rating || 0) - (a.rating || 0))
      .slice(0, 6);
    grid.innerHTML = top.map((p) => createPlaceCard(p)).join("");
  }
  if (window.firestorePlaces?.length) render(window.firestorePlaces);
  else
    document.addEventListener("placesLoaded", (e) => render(e.detail), {
      once: true,
    });
}

// ── User location ─────────────────────────────────────────────
function getUserLocation() {
  const status = document.querySelector("#locationStatus span");
  if (!navigator.geolocation) return;
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      state.userLocation = {
        lat: pos.coords.latitude,
        lng: pos.coords.longitude,
      };
      if (status) status.textContent = "Lokasi terdeteksi ✓";
    },
    () => {
      if (status) status.textContent = "Lokasi tidak tersedia";
    },
  );
}

// ── Haversine ─────────────────────────────────────────────────
function haversine(lat1, lng1, lat2, lng2) {
  const R = 6371000,
    dLat = ((lat2 - lat1) * Math.PI) / 180,
    dLng = ((lng2 - lng1) * Math.PI) / 180;
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos((lat1 * Math.PI) / 180) *
      Math.cos((lat2 * Math.PI) / 180) *
      Math.sin(dLng / 2) ** 2;
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
function debounce(fn, ms) {
  let t;
  return (...a) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...a), ms);
  };
}

// ============================================================
//  SEARCH TRACKING & SMART RECOMMENDATIONS
// ============================================================

const SEARCH_STORE_KEY = "tl_search_history";

function trackSearch(query) {
  if (!query?.trim() || query.trim().length < 2) return;
  const q = query.trim().toLowerCase();
  let h = getSearchHistory();
  h.push({ q, ts: Date.now() });
  if (h.length > 200) h = h.slice(-200);
  localStorage.setItem(SEARCH_STORE_KEY, JSON.stringify(h));
}

function getSearchHistory() {
  try {
    return JSON.parse(localStorage.getItem(SEARCH_STORE_KEY) || "[]");
  } catch {
    return [];
  }
}

function getTopKeywords(n = 10) {
  const freq = {};
  getSearchHistory().forEach(({ q }) =>
    q.split(/\s+/).forEach((t) => {
      if (t.length >= 2) freq[t] = (freq[t] || 0) + 1;
    }),
  );
  return Object.entries(freq)
    .sort((a, b) => b[1] - a[1])
    .slice(0, n)
    .map(([word, count]) => ({ word, count }));
}

function loadSmartTrending() {
  const top = getTopKeywords(4),
    wrap = document.getElementById("trendingChips");
  if (!wrap || top.length < 2) return;
  wrap.innerHTML =
    '<span class="chip-label"><i class="bi bi-fire me-1"></i>Untukmu:</span>' +
    top
      .map(
        ({ word }) =>
          `<a href="explore.html?q=${encodeURIComponent(word)}" class="chip chip-personal">${word}</a>`,
      )
      .join("");
}

function loadPersonalRecommendations() {
  const top = getTopKeywords(3);
  if (!top.length) return;
  const all = DB.getPlaces(),
    kws = top.map((k) => k.word);
  const matched = all
    .filter((p) =>
      kws.some((kw) =>
        (
          p.name +
          " " +
          (p.description || "") +
          " " +
          p.category +
          " " +
          (p.tags || []).join(" ")
        )
          .toLowerCase()
          .includes(kw),
      ),
    )
    .slice(0, 3);
  if (!matched.length) return;
  const sec = document.getElementById("recommendSection"),
    grid = document.getElementById("recommendGrid");
  const ey = document.getElementById("recommendEyebrow");
  if (!sec || !grid) return;
  if (ey) ey.textContent = `Karena kamu sering mencari "${top[0].word}"`;
  grid.innerHTML = matched.map((p) => createPlaceCard(p)).join("");
  sec.classList.remove("d-none");
}

// ============================================================
//  HIDDEN GEM SECTION
// ============================================================

function loadHiddenGems() {
  const grid = document.getElementById("hiddenGemGrid");
  if (!grid) return;
  function render(places) {
    const gems = places.filter((p) => p.hidden_gem).slice(0, 3);
    if (!gems.length) {
      document.getElementById("hiddenGemSection")?.classList.add("d-none");
      return;
    }
    grid.innerHTML = gems.map((p) => createPlaceCard(p)).join("");
  }
  if (window.firestorePlaces?.length) render(window.firestorePlaces);
  else
    document.addEventListener("placesLoaded", (e) => render(e.detail), {
      once: true,
    });
}

// ============================================================
//  ITINERARY 1 HARI
// ============================================================

const ITINERARY_PRESETS = [
  {
    id: "alam",
    label: "Paket Alam & Air Terjun",
    icon: "bi-tree",
    color: "#2d9e6b",
    desc: "Cocok untuk pecinta alam yang ingin menjelajahi hutan dan air terjun.",
    slots: ["bms_01", "bms_02", "bms_09"],
    kuliner: ["k01", "k02"],
  },
  {
    id: "budget",
    label: "Paket Hemat Purwokerto",
    icon: "bi-wallet2",
    color: "#eab308",
    desc: "Maksimalkan liburan dengan budget minimal — banyak destinasi gratis!",
    slots: ["bms_07", "bms_05", "bms_16"],
    kuliner: ["k02", "k04"],
  },
  {
    id: "sejarah",
    label: "Paket Wisata Sejarah",
    icon: "bi-bank2",
    color: "#9b4f96",
    desc: "Jelajahi warisan budaya dan sejarah Banyumas yang kaya.",
    slots: ["bms_05", "bms_14", "bms_10"],
    kuliner: ["k03", "k05"],
  },
  {
    id: "keluarga",
    label: "Paket Wisata Keluarga",
    icon: "bi-people",
    color: "#0ea5e9",
    desc: "Destinasi ramah anak dan seru untuk seluruh keluarga.",
    slots: ["bms_09", "bms_11", "bms_07"],
    kuliner: ["k01", "k04"],
  },
];

const TIMES = ["08:00", "11:00", "14:00"];
const KULINER_TIMES = ["10:00", "13:00"];

function openItineraryModal() {
  const modal = document.getElementById("itineraryModal");
  if (!modal) return;
  renderItineraryOptions();
  bootstrap.Modal.getOrCreateInstance(modal).show();
}

function openItineraryFromDetail(placeId) {
  // Close detail modal first
  const dm = document.getElementById("detailModal");
  if (dm) bootstrap.Modal.getInstance(dm)?.hide();
  setTimeout(openItineraryModal, 300);
}

function renderItineraryOptions() {
  const wrap = document.getElementById("itineraryOptions");
  if (!wrap) return;
  wrap.innerHTML = ITINERARY_PRESETS.map(
    (p) => `
    <div class="col-6 col-md-3">
      <div class="itin-option" onclick="buildItinerary('${p.id}')" style="--itin-col:${p.color}">
        <i class="bi ${p.icon} itin-option-icon"></i>
        <div class="itin-option-label">${p.label}</div>
        <div class="itin-option-desc">${p.desc}</div>
      </div>
    </div>`,
  ).join("");
  document.getElementById("itineraryResult")?.classList.add("d-none");
}

function buildItinerary(presetId) {
  const preset = ITINERARY_PRESETS.find((p) => p.id === presetId);
  if (!preset) return;
  const allPlaces = DB.getPlaces();
  const places = preset.slots
    .map((id) => allPlaces.find((p) => p.place_id === id))
    .filter(Boolean);
  const kuliner = preset.kuliner
    .map((id) => KULINER_DATA.find((k) => k.id === id))
    .filter(Boolean);

  const result = document.getElementById("itineraryResult"),
    opts = document.getElementById("itineraryOptions");
  if (!result) return;
  opts?.classList.add("d-none");

  let totalHtm = 0;
  places.forEach((p) => {
    if (p.htm?.weekday) totalHtm += p.htm.weekday;
  });
  kuliner.forEach((k) => {
    if (k.htm) totalHtm += k.htm;
  });

  const schedule = [];
  // Morning: 2 places
  places
    .slice(0, 2)
    .forEach((p, i) =>
      schedule.push({ time: TIMES[i], type: "wisata", data: p }),
    );
  // Midday kuliner
  if (kuliner[0])
    schedule.push({ time: "12:30", type: "kuliner", data: kuliner[0] });
  // Afternoon: 1 place
  if (places[2])
    schedule.push({ time: TIMES[2], type: "wisata", data: places[2] });
  // Late afternoon kuliner
  if (kuliner[1])
    schedule.push({ time: "16:00", type: "kuliner", data: kuliner[1] });

  result.innerHTML = `
    <div class="itin-header">
      <button class="btn btn-sm btn-outline-secondary mb-3" onclick="renderItineraryOptions()">
        <i class="bi bi-arrow-left me-1"></i>Pilih Paket Lain
      </button>
      <div class="itin-title"><i class="bi ${preset.icon} me-2" style="color:${preset.color}"></i>${preset.label}</div>
      <div class="itin-meta">
        <span><i class="bi bi-clock me-1"></i>~8 jam</span>
        <span><i class="bi bi-wallet2 me-1"></i>Estimasi: ${rupiah(totalHtm)}/orang</span>
        <span><i class="bi bi-geo-alt me-1"></i>${places.length} destinasi + ${kuliner.length} kuliner</span>
      </div>
    </div>
    <div class="itin-timeline mt-3">
      ${schedule
        .map(
          (item, i) => `
        <div class="itin-step ${item.type === "kuliner" ? "itin-step-kuliner" : ""}">
          <div class="itin-time">${item.time}</div>
          <div class="itin-dot ${item.type === "kuliner" ? "itin-dot-kuliner" : ""}">
            <i class="bi ${item.type === "kuliner" ? "bi-cup-hot" : "bi-map-fill"}"></i>
          </div>
          <div class="itin-card">
            <img src="${item.type === "wisata" ? getPhotoUrl(item.data, 200) : item.data.photo_url || ""}" alt="${item.data.name}" loading="lazy"/>
            <div class="itin-card-body">
              <div class="itin-type-badge ${item.type === "kuliner" ? "badge-kuliner" : "badge-wisata"}">
                ${item.type === "kuliner" ? "🍴 Kuliner" : "🏛️ Wisata"}
              </div>
              <div class="itin-place-name">${item.data.name}</div>
              <div class="itin-place-addr"><i class="bi bi-geo-alt me-1"></i>${item.data.vicinity || item.data.vicinity || ""}</div>
              ${item.type === "wisata" && item.data.htm ? `<div class="itin-htm">HTM: ${rupiah(item.data.htm.weekday)}</div>` : ""}
              ${item.type === "kuliner" ? `<div class="itin-htm">~${rupiah(item.data.htm)}/orang</div>` : ""}
              ${item.type === "wisata" ? `<button class="btn itin-detail-btn" onclick="bootstrap.Modal.getInstance(document.getElementById('itineraryModal'))?.hide();setTimeout(()=>openDetail('${item.data.place_id}'),300)">Detail <i class="bi bi-arrow-right"></i></button>` : ""}
            </div>
          </div>
        </div>`,
        )
        .join("")}
    </div>
    <div class="itin-footer">
      <div class="itin-total">
        <i class="bi bi-wallet2 me-2"></i>Total Estimasi Biaya: <strong>${rupiah(totalHtm)}/orang</strong>
        <small class="d-block text-muted mt-1">*Belum termasuk transportasi dan pengeluaran pribadi</small>
      </div>
    </div>`;

  result.classList.remove("d-none");
}

// ============================================================
//  ADMIN — Messages & Analytics
// ============================================================

function loadAdminMessages() {
  const msgs = JSON.parse(localStorage.getItem("tl_messages") || "[]");
  const unread = msgs.filter((m) => !m.read).length;
  const badge = document.getElementById("msgBadge");
  if (badge) badge.textContent = unread > 0 ? unread : "";
  const list = document.getElementById("messagesList");
  if (!list) return;
  if (!msgs.length) {
    list.innerHTML =
      '<div class="p-4 text-center text-muted"><i class="bi bi-inbox display-6 d-block mb-2"></i>Belum ada pesan masuk.</div>';
    return;
  }
  list.innerHTML = msgs
    .map(
      (m, i) => `
    <div class="msg-item ${m.read ? "" : "msg-unread"}" onclick="markRead(${i})">
      <div class="msg-header"><div><span class="msg-name">${m.name}</span>${!m.read ? '<span class="msg-new-badge">Baru</span>' : ""}</div><span class="msg-date">${m.date}</span></div>
      <div class="msg-email"><i class="bi bi-envelope me-1"></i>${m.email}${m.phone ? ` &bull; ${m.phone}` : ""}</div>
      ${m.topic ? `<span class="msg-topic-badge">${m.topic}</span>` : ""}
      <p class="msg-body">${m.message}</p>
    </div>`,
    )
    .join("");
}

function markRead(i) {
  const msgs = JSON.parse(localStorage.getItem("tl_messages") || "[]");
  if (msgs[i]) {
    msgs[i].read = true;
    localStorage.setItem("tl_messages", JSON.stringify(msgs));
    loadAdminMessages();
  }
}
function clearMessages() {
  if (!confirm("Hapus semua pesan?")) return;
  localStorage.removeItem("tl_messages");
  loadAdminMessages();
}

function loadSearchAnalytics() {
  const body = document.getElementById("searchAnalyticsBody"),
    statsBody = document.getElementById("searchStatsBody"),
    recBody = document.getElementById("recommendPreview");
  if (!body) return;
  const top = getTopKeywords(15),
    history = getSearchHistory();
  if (!top.length) {
    body.innerHTML =
      '<p class="text-muted text-center py-3">Belum ada data pencarian.</p>';
    return;
  }
  const max = top[0].count;
  body.innerHTML = top
    .map(
      ({ word, count }, i) => `
    <div class="search-kw-item">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <span class="search-kw-rank">#${i + 1}</span><span class="search-kw-word">${word}</span><span class="search-kw-count">${count}x</span>
      </div>
      <div class="cat-bar-track"><div class="cat-bar-fill" style="width:${((count / max) * 100).toFixed(0)}%;background:var(--accent)"></div></div>
    </div>`,
    )
    .join("");
  if (statsBody)
    statsBody.innerHTML = `
    <div class="d-flex flex-column gap-3">
      <div class="d-flex justify-content-between"><span class="text-muted">Total pencarian</span><strong>${history.length}</strong></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Kata unik</span><strong>${top.length}</strong></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Paling populer</span><strong>${top[0]?.word || "-"}</strong></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Hari ini</span><strong>${history.filter((h) => Date.now() - h.ts < 86400000).length}x</strong></div>
    </div>`;
  if (recBody) {
    const kws = top.slice(0, 3).map((k) => k.word),
      all = DB.getPlaces();
    const prev = all
      .filter((p) =>
        kws.some((kw) =>
          (p.name + " " + (p.description || "") + " " + p.category)
            .toLowerCase()
            .includes(kw),
        ),
      )
      .slice(0, 3);
    recBody.innerHTML = prev.length
      ? '<p class="text-muted small mb-2">Destinasi yang akan direkomendasikan:</p>' +
        prev
          .map(
            (p) =>
              `<div class="d-flex align-items-center gap-2 mb-2"><img src="${getPhotoUrl(p, 80)}" style="width:40px;height:40px;border-radius:8px;object-fit:cover" alt=""/><div><div class="fw-600 small">${p.name}</div><div class="text-muted" style="font-size:.75rem">${p.category}</div></div></div>`,
          )
          .join("")
      : '<p class="text-muted small">Belum ada rekomendasi yang cocok.</p>';
  }
}
function clearSearchHistory() {
  if (!confirm("Reset data pencarian?")) return;
  localStorage.removeItem(SEARCH_STORE_KEY);
  loadSearchAnalytics();
}
