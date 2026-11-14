
async function getAllRastersByIdSite(siteId) {
    try {
        const response = await fetch(`${api_url}rasters/${siteId}/site`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });
        const data = await response.json();
        //console.log("Rasters chargés :", data);
        // On transforme le tableau d'objets JSON en tableau d'objets Raster
        const rasters = data.map(s => new Raster(
                            s.id,
                            s.url,
                            s.date,
                            s.year,
                            s.description,
                            s.siteId
                        ));
        return rasters;
    } catch (error) {
        console.error("Erreur de chargement :", error);
    }
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




