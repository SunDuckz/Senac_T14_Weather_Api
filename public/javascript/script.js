function inicializarMapa() {
    mapa = L.map('map').setView([-15.7801, -47.9292], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(mapa);
}

function atualizarMapa(lat, lng) {
    mapa.setView([lat, lng], 12);
    L.marker([lat, lng]).addTo(mapa);
}