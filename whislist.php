<?php require_once 'includes/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bookmark-heart-fill text-danger me-2"></i>Destinasi Tersimpan</h2>
    <div id="wishlistContainer" class="row g-4">
        <div class="text-center p-5">Memuat destinasi tersimpan...</div>
    </div>
</div>

<script>
function loadWishlist() {
    const favIds = getFavs();
    const container = document.getElementById('wishlistContainer');
    
    // Asumsi 'state.places' sudah terisi dari database
    const savedPlaces = state.places.filter(p => favIds.includes(p.id.toString()));

    if (savedPlaces.length === 0) {
        container.innerHTML = '<div class="col-12 text-center py-5"><p>Belum ada destinasi yang disimpan.</p></div>';
        return;
    }

    container.innerHTML = savedPlaces.map(p => `
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <img src="${p.foto}" class="card-img-top rounded-top-4" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="fw-bold">${p.nama}</h5>
                    <button class="btn btn-sm btn-outline-danger" onclick="toggleFav('${p.id}'); loadWishlist();">Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

window.addEventListener('DOMContentLoaded', loadWishlist);
</script>

<?php require_once 'includes/footer.php'; ?>