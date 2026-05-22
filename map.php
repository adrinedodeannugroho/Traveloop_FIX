<?php
// map.php
// Memanggil Header global (berisi Navbar dan tag Head CSS)
require_once 'includes/header.php';
?>

<style>
  /* Menghilangkan scroll utama browser agar map memenuhi layar */
  body { overflow: hidden; }
  
  /* Kontainer Peta Utama */
  #mapContainer { 
    height: calc(100vh - 80px); /* Dikurangi tinggi navbar */
    width: 100%; 
    margin-top: 80px; 
    position: relative; 
  }
  
  #gmap { width: 100%; height: 100%; }
  
  /* Sidebar Melayang (Floating Panel) */
  .map-sidebar {
    position: absolute; 
    top: 20px; 
    left: 20px; 
    z-index: 10;
    width: 350px; 
    max-height: calc(100% - 40px);
    background: #ffffff; 
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    overflow: hidden; 
    display: flex; 
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.05);
  }
  
  .map-sidebar-header { 
    padding: 20px; 
    border-bottom: 1px solid #e2e8f0; 
    background: #ffffff; 
  }
  
  .map-sidebar-title {
    font-family: var(--ff-display);
    font-weight: 700;
    font-size: 1.25rem;
    color: #0f172a;
    margin-bottom: 12px;
  }
  
  .map-sidebar-list { 
    overflow-y: auto; 
    flex: 1; 
  }
  
  /* Kustomisasi Scrollbar Sidebar */
  .map-sidebar-list::-webkit-scrollbar { width: 6px; }
  .map-sidebar-list::-webkit-scrollbar-track { background: transparent; }
  .map-sidebar-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
  
  .map-place-item {
    padding: 16px 20px; 
    cursor: pointer; 
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
  }
  
  .map-place-item:hover { background: #f8fafc; }
  .map-place-item.active { 
    background: #fef9e7; /* var(--accent-soft) */
    border-left: 4px solid #eab308; /* var(--accent) */
  }
  
  .map-place-name { 
    font-family: var(--ff-body); 
    font-weight: 700; 
    font-size: 0.95rem; 
    color: #0f172a; 
  }
  
  .map-place-addr { 
    font-size: 0.8rem; 
    color: #64748b; 
    margin-top: 4px;
    line-height: 1.4;
  }

  /* Responsif untuk Layar HP */
  @media (max-width: 768px) {
    .map-sidebar { 
      width: calc(100% - 30px); 
      top: auto; 
      bottom: 20px; 
      left: 15px; 
      max-height: 45vh; 
    }
    #mapContainer { height: calc(100vh - 65px); margin-top: 65px; }
  }
</style>

<div id="mapContainer">
  <div id="gmap"></div>
  
  <div class="map-sidebar">
    <div class="map-sidebar-header">
      <h3 class="map-sidebar-title"><i class="bi bi-pin-map-fill text-warning me-2"></i>Peta Destinasi</h3>
      <div class="filter-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="mapSearch" class="form-control filter-input bg-light border-0 py-2" placeholder="Cari nama lokasi..." oninput="filterMapList()"/>
      </div>
    </div>
    
    <div class="map-sidebar-list" id="mapList">
      <div class="p-5 text-center text-muted">
        <div class="spinner-border spinner-border-sm text-warning mb-3" role="status"></div>
        <br><span class="small fw-bold">Memuat titik wisata...</span>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  window.addEventListener('DOMContentLoaded', () => {
      // Pastikan fungsi ini ada di script global
      if(typeof initNavScroll === 'function') initNavScroll();
  });

  function initMap() {
    // Koordinat pusat diset di tengah-tengah Purwokerto / Banyumas
    const map = new google.maps.Map(document.getElementById('gmap'), {
      center: { lat: -7.4226, lng: 109.2354 },
      zoom: 11,
      styles: mapStyles,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false
    });
    
    window._map = map;
    window._markers = [];
    window._mapPlaces = [];
    loadMapPlaces(map);
  }

  function loadMapPlaces(map) {
    const service = new google.maps.places.PlacesService(map);
    // Area pencarian disesuaikan dengan jangkauan Barlingmascakeb
    const queries = ['wisata Banyumas Jawa Tengah', 'wisata alam Purbalingga', 'pantai Cilacap'];
    let all = [];
    let done = 0;
    
    queries.forEach(q => {
      service.textSearch({ query: q }, (res, stat) => {
        if (stat === google.maps.places.PlacesServiceStatus.OK) {
            all = all.concat(res);
        }
        done++;
        // Setelah semua query selesai dicari, hilangkan duplikat dan render
        if (done === queries.length) {
            // Hapus kemungkinan duplikasi dari hasil Places API
            const uniquePlaces = Array.from(new Set(all.map(a => a.place_id)))
                                    .map(id => { return all.find(a => a.place_id === id) });
            renderMapPlaces(map, uniquePlaces);
        }
      });
    });
  }

  function renderMapPlaces(map, places) {
    window._mapPlaces = places;
    const list = document.getElementById('mapList');
    list.innerHTML = '';
    
    if(places.length === 0) {
        list.innerHTML = '<div class="p-4 text-center text-muted small">Tidak ada data wisata ditemukan.</div>';
        return;
    }

    const infoWindow = new google.maps.InfoWindow();

    places.forEach((p, i) => {
      // Buat Marker (Titik di Peta)
      const marker = new google.maps.Marker({
        map: map,
        position: p.geometry.location,
        title: p.name,
        icon: {
            url: "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" // Menggunakan ikon standar Google berwarna kuning
        }
      });
      window._markers.push(marker);

      // Event klik pada marker di peta
      marker.addListener('click', () => {
        infoWindow.setContent(`
          <div style="font-family:'DM Sans',sans-serif;max-width:240px;padding:5px;">
            <strong style="font-size:1rem;color:#0f172a;display:block;margin-bottom:4px;">${p.name}</strong>
            <span style="font-size:0.8rem;color:#64748b;display:block;margin-bottom:6px;">${p.formatted_address || p.vicinity || ''}</span>
            ${p.rating ? `<span style="font-size:0.85rem;color:#eab308;font-weight:bold;">★ ${p.rating} / 5.0</span>` : ''}
          </div>
        `);
        infoWindow.open(map, marker);
        highlightListItem(i);
      });

      // Buat Elemen Daftar di Sidebar
      const item = document.createElement('div');
      item.className = 'map-place-item';
      item.id = `mpi-${i}`;
      item.innerHTML = `
        <div class="map-place-name">${p.name}</div>
        <div class="map-place-addr"><i class="bi bi-geo-alt me-1"></i>${p.vicinity || p.formatted_address || ''}</div>
      `;
      
      // Event klik pada daftar di sidebar
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
    if (el) { 
        el.classList.add('active'); 
        el.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); 
    }
  }

  function filterMapList() {
    const q = document.getElementById('mapSearch').value.toLowerCase();
    document.querySelectorAll('.map-place-item').forEach((el, i) => {
      const name = window._mapPlaces[i]?.name?.toLowerCase() || '';
      el.style.display = name.includes(q) ? '' : 'none';
      
      // Sembunyikan juga markernya di peta jika tidak sesuai filter
      if(window._markers[i]) {
          window._markers[i].setVisible(name.includes(q));
      }
    });
  }

  // Estetika Warna Peta (Gaya Editorial Terang)
  const mapStyles = [
    { featureType: 'all', elementType: 'geometry', stylers: [{ color: '#f8fafc' }] },
    { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#bae6fd' }] },
    { featureType: 'landscape.natural', elementType: 'geometry.fill', stylers: [{ color: '#f1f5f9' }] },
    { featureType: 'road', elementType: 'geometry.stroke', stylers: [{ color: '#ffffff' }] },
    { featureType: 'road', elementType: 'geometry.fill', stylers: [{ color: '#e2e8f0' }] },
    { featureType: 'poi.park', elementType: 'geometry', stylers: [{ color: '#dcfce7' }] },
    { featureType: 'all', elementType: 'labels.text.fill', stylers: [{ color: '#64748b' }] },
    { featureType: 'all', elementType: 'labels.text.stroke', stylers: [{ color: '#ffffff' }] }
  ];
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap"></script>

</body>
</html>