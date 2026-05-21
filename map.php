<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Map — Traveloop</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
  <style>
    #mapContainer { height: calc(100vh - 76px); width: 100%; margin-top: 76px; position: relative; }
    #gmap { width: 100%; height: 100%; }
    .map-sidebar {
      position: absolute; top: 20px; left: 20px; z-index: 10;
      width: 320px; max-height: calc(100% - 40px);
      background: var(--surface); border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,.15);
      overflow: hidden; display: flex; flex-direction: column;
    }
    .map-sidebar-header { padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--surface); }
    .map-sidebar-list { overflow-y: auto; flex: 1; }
    .map-place-item {
      padding: 12px 16px; cursor: pointer; border-bottom: 1px solid var(--border);
      transition: background .2s;
    }
    .map-place-item:hover { background: var(--bg-soft); }
    .map-place-item.active { background: var(--accent-soft); border-left: 3px solid var(--accent); }
    .map-place-name { font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: .9rem; color: var(--text); }
    .map-place-addr { font-size: .78rem; color: var(--text-muted); margin-top: 2px; }
    @media (max-width: 768px) {
      .map-sidebar { width: calc(100% - 40px); top: auto; bottom: 20px; left: 20px; max-height: 40vh; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg wn-navbar wn-navbar-solid fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand wn-brand" href="index.php">
      <span class="brand-icon"><i class="bi bi-compass"></i></span>Traveloop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="explore.php">Explore</a></li>
        <li class="nav-item"><a class="nav-link active" href="map.php">Map</a></li>
      </ul>
    </div>
  </div>
</nav>

<div id="mapContainer">
  <div id="gmap"></div>
  <div class="map-sidebar">
    <div class="map-sidebar-header">
      <div class="filter-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="mapSearch" class="form-control filter-input" placeholder="Search on map…" oninput="filterMapList()"/>
      </div>
    </div>
    <div class="map-sidebar-list" id="mapList">
      <div class="p-4 text-center text-muted small">Loading places…</div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', () => initNavScroll());

  function initMap() {
    const map = new google.maps.Map(document.getElementById('gmap'), {
      center: { lat: -7.4226, lng: 109.2354 },
      zoom: 11,
      styles: mapStyles,
      mapTypeControl: false,
      streetViewControl: false,
    });
    window._map = map;
    window._markers = [];
    window._mapPlaces = [];
    loadMapPlaces(map);
  }

  function loadMapPlaces(map) {
    const service = new google.maps.places.PlacesService(map);
    const queries = ['wisata Banyumas Jawa Tengah', 'curug Banyumas', 'wisata Purwokerto'];
    let all = [];
    let done = 0;
    queries.forEach(q => {
      service.textSearch({ query: q }, (res, stat) => {
        if (stat === google.maps.places.PlacesServiceStatus.OK) all = all.concat(res);
        done++;
        if (done === queries.length) renderMapPlaces(map, all);
      });
    });
  }

  function renderMapPlaces(map, places) {
    window._mapPlaces = places;
    const list = document.getElementById('mapList');
    list.innerHTML = '';
    const infoWindow = new google.maps.InfoWindow();

    places.forEach((p, i) => {
      const marker = new google.maps.Marker({
        map,
        position: p.geometry.location,
        title: p.name,
        icon: { url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' }
      });
      window._markers.push(marker);

      marker.addListener('click', () => {
        infoWindow.setContent(`
          <div style="font-family:'DM Sans',sans-serif;max-width:220px">
            <strong style="font-size:.95rem">${p.name}</strong><br>
            <span style="font-size:.8rem;color:#666">${p.formatted_address || p.vicinity || ''}</span><br>
            ${p.rating ? `<span style="color:#f59e0b">★ ${p.rating}</span>` : ''}
          </div>
        `);
        infoWindow.open(map, marker);
        highlightListItem(i);
      });

      const item = document.createElement('div');
      item.className = 'map-place-item';
      item.id = `mpi-${i}`;
      item.innerHTML = `<div class="map-place-name">${p.name}</div>
        <div class="map-place-addr">${p.vicinity || p.formatted_address || ''}</div>`;
      item.addEventListener('click', () => {
        map.panTo(p.geometry.location);
        map.setZoom(14);
        google.maps.event.trigger(marker, 'click');
      });
      list.appendChild(item);
    });
  }

  function highlightListItem(i) {
    document.querySelectorAll('.map-place-item').forEach(el => el.classList.remove('active'));
    const el = document.getElementById(`mpi-${i}`);
    if (el) { el.classList.add('active'); el.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
  }

  function filterMapList() {
    const q = document.getElementById('mapSearch').value.toLowerCase();
    document.querySelectorAll('.map-place-item').forEach((el, i) => {
      const name = window._mapPlaces[i]?.name?.toLowerCase() || '';
      el.style.display = name.includes(q) ? '' : 'none';
    });
  }

  const mapStyles = [
    { featureType: 'all', elementType: 'geometry', stylers: [{ color: '#f0ebe3' }] },
    { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#b8d9e8' }] },
    { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#ffffff' }] },
    { featureType: 'road', elementType: 'geometry.fill', stylers: [{ color: '#e8e0d5' }] },
    { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#c9e6c5' }] },
  ];
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap"></script>
</body>
</html>