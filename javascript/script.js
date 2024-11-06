var mapa
function inicializarMapa() {
    mapa = L.map('map').setView([-15.7801, -47.9292], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(mapa);
}

function atualizarMapa(lat, lon) {
    mapa.setView([lat, lon], 13);
    L.marker([lat, lon]).addTo(mapa)
        .bindPopup('Localização: ' + lat + ', ' + lon)
        .openPopup();
}

document.addEventListener("DOMContentLoaded", function() {
        inicializarMapa();
        var lat = parseFloat(document.getElementById("lat").textContent);
        var lon = parseFloat(document.getElementById("lon").textContent);
        atualizarMapa(lat, lon); 
    }
)

// function buscaDadosCidade(){
//     var cidade = "Santa Cruz do Sul";//document.getElementById("campo_cidade").value;
//     $.ajax(
//         {
//             url: "buscarInformacoesCidade.php", 
//             method: "POST",
//             data: {"name": cidade},
//             success: function(result){
//                 console.log(result);
//             }
//         }
//     );
// }