const dynamicLayers = [];
let layerControl = null;
let overlayMaps = {}; 

const sitesLayerGroup = L.layerGroup(); // Groupe de calques pour les sites

const zoneLayerGroup = new L.FeatureGroup().addTo(map);

const tamponEPLayerGroup = L.layerGroup();
const pipeEPLayerGroup = L.layerGroup();

const tamponEULayerGroup = L.layerGroup();
const pipeEULayerGroup = L.layerGroup();

const eauPluvialeGroup = L.layerGroup([tamponEPLayerGroup, pipeEPLayerGroup]);
const eauUseeGroup     = L.layerGroup([tamponEULayerGroup, pipeEULayerGroup]);

// eauPluvialeGroup.addTo(map);  // decommenter si site avec tampons  *********
// eauUseeGroup.addTo(map);  // decommenter si site avec tampons  *********

function addDynamicLayers( rasters ) {
    rasters.forEach((raster, index) => {
        // Création de la couche Leaflet pour chaque raster du tableau
        const layer = L.tileLayer(raster.url, { 
                        minZoom: 13, 
                        maxZoom: 22,
                        pane: 'paneRasters', 
                        tms: false, 
                        attribution: 'Created by QGIS' 
                    });
        // Optionnel : tu peux décider d'en afficher une par défaut sur la carte (ex: la dernière)
        if (index === rasters.length - 1) {
            layer.addTo(map);
        }
        // On la stocke dans le tableau global des couches
        dynamicLayers.push(layer);

        // On l'ajoute dynamiquement dans l'objet overlayMaps avec sa date comme titre
        overlayMaps[raster.date] = layer;
       
    });

    updateLayerControl(overlayMaps);

}

function updateLayerControl(overlayMaps) {
    // 1. Si un contrôle existe déjà sur la carte, on le supprime proprement
    if (layerControl) {
        map.removeControl(layerControl);
        layerControl = null;
    }

    // updateOverlayMaps(overlayMaps); // // decommenter si site avec tampons  *********

    // 2. On recrée le contrôle avec ton objet overlayMaps mis à jour
    layerControl = L.control.layers(baseMaps, overlayMaps, {
        collapsed: true,
        position: 'bottomleft'
    }).addTo(map);
}

function updateOverlayMaps(overlayMaps) {
    overlayMaps["Eaux Pluviales"] = eauPluvialeGroup;
    overlayMaps["Eaux Usées"] = eauUseeGroup;
}