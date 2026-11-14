// 1. Définition de la projection CC44 (EPSG:3944)
proj4.defs("EPSG:3944", "+proj=lcc +lat_1=43.25 +lat_2=44.75 +lat_0=44 +lon_0=3 +x_0=1700000 +y_0=3200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
// 1. Définition de la projection CC47 (EPSG:3947) *** beffes decommenter pour un projet cc47
// proj4.defs("EPSG:3947","+proj=lcc +lat_1=46.25 +lat_2=47.75 +lat_0=47 +lon_0=3 +x_0=1700000 +y_0=6250000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs");
function convertWGS84ToCC44(lat, lng) {
    // proj4 attend [longitude, latitude] sous forme de nombres
    return proj4(`EPSG:${API_MAP_EPSG}`, `EPSG:${API_EPSG}`, [parseFloat(lng), parseFloat(lat)]);
    // return proj4("EPSG:4326", "EPSG:3944", [parseFloat(lng), parseFloat(lat)]);
}

function pointCoords() {
    if (!isPointCoordsActive) {
        // --- DÉSACTIVATION ---
        if (pointInfoHandler) {
            map.off('click', pointInfoHandler); // Supprime l'écouteur précis
            pointInfoHandler = null;
        }
        mode = appMode.NORMAL;
        mapContainer.dataset.mode1 = "normal"; // Retour au curseur normal     
    } else {
        // --- ACTIVATION ---
        // Définition de la fonction de callback
        pointInfoHandler = function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            const cc44Coords = convertWGS84ToCC44(lat, lng); // je recois un tableau [X, Y] en mètres
            const xCC44 = cc44Coords[0].toFixed(3); // ***********
            const yCC44 = cc44Coords[1].toFixed(3);
          

            L.popup()
                .setLatLng(e.latlng)
                .setContent(`<div style="font-family: sans-serif; line-height: 1.4;">
                                <strong>Coordonnées RGF93 / CC44 :</strong><br>
                                <b>X :</b> ${xCC44} m<br>
                                <b>Y :</b> ${yCC44} m
                            </div>`)
                .openOn(map);
        };

        // Ajout de l'écouteur sur la carte
        map.on('click', pointInfoHandler);

        mode = appMode.POINT_INFO;
        mapContainer.dataset.mode1 = "point_info"; // Changement de mode
    }
}

/**  TODO
 // Dictionnaire centralisé des projections CC (de CC42 à CC50)
const CC_PROJECTIONS = {
    "EPSG:3942": "+proj=lcc +lat_1=41.25 +lat_2=42.75 +lat_0=42 +lon_0=3 +x_0=1700000 +y_0=1200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3943": "+proj=lcc +lat_1=42.25 +lat_2=43.75 +lat_0=43 +lon_0=3 +x_0=1700000 +y_0=2200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3944": "+proj=lcc +lat_1=43.25 +lat_2=44.75 +lat_0=44 +lon_0=3 +x_0=1700000 +y_0=3200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3945": "+proj=lcc +lat_1=44.25 +lat_2=45.75 +lat_0=45 +lon_0=3 +x_0=1700000 +y_0=4200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3946": "+proj=lcc +lat_1=45.25 +lat_2=46.75 +lat_0=46 +lon_0=3 +x_0=1700000 +y_0=5200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3947": "+proj=lcc +lat_1=46.25 +lat_2=47.75 +lat_0=47 +lon_0=3 +x_0=1700000 +y_0=6200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3948": "+proj=lcc +lat_1=47.25 +lat_2=48.75 +lat_0=48 +lon_0=3 +x_0=1700000 +y_0=7200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3949": "+proj=lcc +lat_1=48.25 +lat_2=49.75 +lat_0=49 +lon_0=3 +x_0=1700000 +y_0=8200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs",
    "EPSG:3950": "+proj=lcc +lat_1=49.25 +lat_2=50.75 +lat_0=50 +lon_0=3 +x_0=1700000 +y_0=9200000 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs"
};

// Enregistrement automatique de toutes les définitions dans Proj4js au démarrage
for (const [epsgCode, defString] of Object.entries(CC_PROJECTIONS)) {
    proj4.defs(epsgCode, defString);
}
 
// Par exemple, tu récupères la zone active de ton appli (via un paramètre de config, un selecteur HTML, etc.)
let currentEpsg = "EPSG:3944"; // Pour Trets (CC44)
// ou let currentEpsg = "EPSG:3947"; pour Beffes (CC47)


const wgs84 = "EPSG:4326";

map.on('click', function(e) {
    const latWgs84 = e.latlng.lat;
    const lngWgs84 = e.latlng.lng;

    // Conversion dynamique en utilisant la projection active de l'appli
    const coordProjected = proj4(wgs84, currentEpsg, [lngWgs84, latWgs84]);

    console.log(`Coordonnées en ${currentEpsg} :`, coordProjected[0], coordProjected[1]);
});





 */