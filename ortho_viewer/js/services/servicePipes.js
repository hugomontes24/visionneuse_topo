const pipeStyleEP = {
    color: '#007bff', // Bleu pour les Eaux Pluviales
    weight: 3,
    opacity: 0.8
};

const pipeStyleEU = {
    color: '#a52a2afb', // Brun / Rouge pour les Eaux Usées
    weight: 3,
    opacity: 0.8
};

async function getAllPipesByIdSite(siteId) {
    try {
        const response = await fetch(`${api_url}pipes/${siteId}/site`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        const data = await response.json();
    
        const pipes = data.map(s => {
        
            return new Pipe(
                s.id,
                s.siteId ?? s.site_id,
                s.type,
                s.description,
                s.diametre,
                s.reseau,
                s.coordinatesCc,
                s.coordinatesWgs84
            ) 
        });
        return pipes;

    } catch (error) {
        console.error("Erreur de chargement :", error);
    }
};


//recuperer les tubes d'un site et les afficher sur la carte
function displayPipesOnMap(pipes) {
    pipeEPLayerGroup.clearLayers(); // Efface les tubes précedents du groupe de calques
    pipeEULayerGroup.clearLayers(); // Efface les tubes précédents du groupe de calques
    const pipesEp = filterPipesByReseau(pipes, 1); // Filtrer les tubes par réseau (reseau = 1)
    const pipesEu=filterPipesByReseau(pipes, 2); // Filtrer les tubes par réseau (reseau = 2)
    
    pipesEp.forEach(pipe => {   
        const polyline = L.polyline(pipe.getLeafletLatLngs(), pipeStyleEP);
        polyline.bindPopup(`Tuyau ID : ${pipe.id} - Ø ${pipe.diametre}`);
        pipeEPLayerGroup.addLayer(polyline);
    });
    pipesEu.forEach(pipe => {   
        const polyline = L.polyline(pipe.getLeafletLatLngs(), pipeStyleEU);
        polyline.bindPopup(`Tuyau ID : ${pipe.id} - Ø ${pipe.diametre}`);
        pipeEULayerGroup.addLayer(polyline);
    });
};

// trier les tubes par reseau
function filterPipesByReseau(pipes, idReseau) {
    return pipes.filter((pipe) => pipe.reseau === idReseau);
};



// const squareIconEP = L.divIcon({
//     className: 'tampon-square-markerEP', // Classe CSS à personnaliser
//     iconSize: [6, 6],                // Largeur et hauteur en pixels
//     iconAnchor: [3, 3]                 // Centre de l'icône [largeur/2, hauteur/2]
// });
// const squareIconEU = L.divIcon({
//     className: 'tampon-square-markerEU', // Classe CSS à personnaliser
//     iconSize: [6, 6],                // Largeur et hauteur en pixels
//     iconAnchor: [3, 3]                 // Centre de l'icône [largeur/2, hauteur/2]
// });
