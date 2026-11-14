// 3. Charger tes données depuis ton API PHP
async function getAllSites() {
    try {
        const response = await fetch(`${api_url}sites`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        // console.log("Sites chargés :", data);
        // On transforme le tableau d'objets JSON en tableau d'objets Photo
        sites = data.map(s => new Site(
            s.id,
            s.name,
            s.city,
            s.img,
            [parseFloat(s.coordinates[0]), parseFloat(s.coordinates[1])]// [lng, lat] 
            ) );

        return sites;

    } catch (error) {
        console.error("Erreur de chargement :", error);
    }
}

function updateSiteVisibility() {
    const zoom = map.getZoom();
    if (zoom < 15) {
        if (!map.hasLayer(sitesLayerGroup)) {
            map.addLayer(sitesLayerGroup);
        }
    } else {
        if (map.hasLayer(sitesLayerGroup)) {
            map.removeLayer(sitesLayerGroup);
        }
    }
}







   







