const sidebarHeader = document.getElementById('sidebar_header');
const asideInfodescr = document.getElementById('aside-info-descr');
const asideInfoName = document.getElementById('aside-info-name');
const asidecoords84 = document.getElementById('aside-coords-w84');
const asidecoordsCC = document.getElementById('aside-coords-cc44');

const squareIconEP = L.divIcon({
    className: 'tampon-square-markerEP', // Classe CSS à personnaliser
    iconSize: [2, 2],                // Largeur et hauteur en pixels
    iconAnchor: [1, 1]                 // Centre de l'icône [largeur/2, hauteur/2]
});
const roundIconEP = L.divIcon({
    className: 'tampon-round-markerEP', // Classe CSS à personnaliser
    iconSize: [2, 2],                // Largeur et hauteur en pixels
    iconAnchor: [1, 1]                 // Centre de l'icône [largeur/2, hauteur/2]
});
const squareIconEU = L.divIcon({
    className: 'tampon-square-markerEU', // Classe CSS à personnaliser
    iconSize: [2, 2],                // Largeur et hauteur en pixels
    iconAnchor: [1, 1]                 // Centre de l'icône [largeur/2, hauteur/2]
});

async function getAllTamponsByIdSite(siteId) {
    try {
        const response = await fetch(`${api_url}tampons/${siteId}/site`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });
        const data = await response.json();
        //console.log("Tampons chargés :", data);
        // On transforme le tableau d'objets JSON en tableau d'objets Tampon
        const tampons = data.map(s => new Tampon(
            s.id,
            s.matricule,
            s.siteId,
            s.type,
            s.reseau,
            s.description,
            s.rotation,
            s.amontIds,
            s.avalIds,
            s.coordinatesCc,
            s.coordinatesWgs84
            ) );
        return tampons;

    } catch (error) {
        console.error("Erreur de chargement :", error);
    }
};

//recuperer les tampons d'un site et les afficher sur la carte
function displayTamponsOnMap(tampons) {
    tamponEPLayerGroup.clearLayers(); // Efface les tampons précédents du groupe de calques
    tamponEULayerGroup.clearLayers(); // Efface les tampons précédents du groupe de calques
    const tamponsEp = filterTamponsByReseau(tampons, 1); // Filtrer les tampons par réseau (reseau = 1)
//    console.log("Tampons EP :", tamponsEp);
    const tamponsEu=filterTamponsByReseau(tampons, 2); // Filtrer les tampons par réseau (reseau = 2)

    tamponsEp.forEach(tampon => {
        // Sélection de l'icône selon le type
        const icon = (tampon.type === 1) ? roundIconEP : squareIconEP;

        // Création du marqueur
        const marker = L.marker(tampon.getLeafletLatLngs(), { icon: icon }); 
            marker.bindPopup(`Tampon ID : ${tampon.id}`);
        // const cc44coords = convertWGS84ToCC44(tampon.coordinates[0], tampon.coordinates[1]);
        marker.on('click', () => updateAsideInfo(tampon));
        tamponEPLayerGroup.addLayer(marker);
    });

    tamponsEu.forEach(tampon => {
        const marker = L.marker(tampon.getLeafletLatLngs(),{ icon: squareIconEU });
        marker.bindPopup(`Tampon ID : ${tampon.id}`);
        // const cc44coords = convertWGS84ToCC44(tampon.coordinates[0], tampon.coordinates[1]);
        // marker.on('click', () => updateAsideInfo(tampon,cc44coords));
        marker.on('click', () => updateAsideInfo(tampon));
        tamponEULayerGroup.addLayer(marker);
    });
};
// trier les tampons par reseau
function filterTamponsByReseau(tampons, idReseau) {
    // console.log(`Filtrage des tampons pour le réseau ${idReseau}`);
    return tampons.filter((tampon) => tampon.reseau === idReseau);
};
function updateAsideInfo(tampon) {
    sidebarHeader.innerHTML = `Matricule :  ${tampon.matricule ? transformerTexte(tampon.matricule, "phrase") : "Non défini"}`;
    asideInfoName.innerHTML = `Tampon ID : ${tampon.id}`;
    asideInfodescr.innerHTML = `${transformerTexte(tampon.description, "phrase")}`;
    // const cc47coords = convertWGS84ToCC47(tampon.coordinates[1], tampon.coordinates[0]);
    asidecoordsCC.innerHTML = `${API_COORDS} :<br>X :${tampon.coordinatesCc[0].toFixed(3)} m<br>
                                 Y : ${tampon.coordinatesCc[1].toFixed(3)} m<br>
                                 Z : ${tampon.coordinatesCc[2].toFixed(3)} m`;
    asidecoords84.innerHTML = `GPS :<br>Lat :${tampon.coordinatesWgs84[1].toFixed(6)}<br> Lon : ${tampon.coordinatesWgs84[0].toFixed(6)}`;
    
};


function updateMapZoomClass() {
    const zoom = map.getZoom();
    // On retire les anciennes classes de zoom
    mapContainer.className = mapContainer.className.replace(/map-zoom-\d+/g, '');
    // On ajoute la classe correspondant au zoom actuel
    mapContainer.classList.add(`map-zoom-${Math.round(zoom)}`);
}




// function convertWGS84ToCC44(lng, lat) {
//     // proj4(source, destination, [lng, lat])
//     return proj4("EPSG:4326", "EPSG:3944", [lng, lat]);
//     // const coordsCC44 = proj4("EPSG:4326", "EPSG:3944", [lng, lat]);
//     // console.log(coordsCC44);
//     // return [coordsCC44[0], coordsCC44[1]];
// }