const targetZoom = 19; // Niveau de zoom souhaité lors du clic sur un marqueur
const sideBarTitle = document.getElementById("sidebar_title");
const asideSiteCoordsDiv = document.getElementById("aside-site-coords");
const asideSiteCoordsCC = document.getElementById("aside-site-coords-cc");
const asideSiteCoordsWGS84 = document.getElementById("aside-site-coords-wgs84");

function displaySiteCoordinates(site) {
    if (site.coordinatesCc && site.coordinatesCc.length >= 2) {
        asideSiteCoordsCC.innerHTML = `<strong>${API_COORDS} du site :</strong><br>X : ${site.coordinatesCc[0].toFixed(3)} m<br>Y : ${site.coordinatesCc[1].toFixed(3)} m<br>Z : ${site.coordinatesCc[2] ? site.coordinatesCc[2].toFixed(3) : 'N/A'} m`;
    }
    if (site.coordinatesWgs84 && site.coordinatesWgs84.length >= 2) {
        asideSiteCoordsWGS84.innerHTML = `<strong>GPS du site :</strong><br>Lon : ${site.coordinatesWgs84[0].toFixed(6)}<br>Lat : ${site.coordinatesWgs84[1].toFixed(6)}`;
    }
    asideSiteCoordsDiv.classList.remove('hidden');
}

async function initApp() {
    try {
        // 1. Récupération des sites depuis votre API PHP
        const sites = await getAllSites(); 

        // 2. Parcourir et ajouter chaque marqueur avec son popup
        sites.forEach(site => {
            // VERIFICATION : Assurez-vous d'avoir des coordonnées valides
            if (site.coordinates && site.coordinates.length >= 2) {
                if( SITES_IDS.includes(site.id))  { // si site.id est dans sitesIds config.js
                    // const marker = L.marker([site.coordinates[1], site.coordinates[0]]).addTo(map);
                    const marker = L.marker([site.coordinates[1], site.coordinates[0]]); // TODO METTRE A JOUR SITE COORDONNEES
                    // Contenu du Pop-up
                    const popupContent = `
                        <div class="site-popup">
                            <h3>${site.name}</h3>
                            <p><strong>Ville : ${site.city}</strong></p>
                        </div>`;
                    // Attacher le popup directement à cette instance
                    marker.bindPopup(popupContent);
                        // Ajouter le marqueur au groupe (et non directement à 'map')
                        sitesLayerGroup.addLayer(marker);
                        sitesLayerGroup.addTo(map); // Ajouter le groupe de calques à la carte
                    
                    marker.on('click', async(e) => {
                    // Zoom fluide vers les coordonnées du marqueur
                        map.flyTo(e.latlng, targetZoom, {animate: true, duration: 1.5 });
                        sideBarTitle.textContent = `${transformerTexte(site.name, "capitalize")}`;
                        displaySiteCoordinates(site);
                        // addDynamicLayers(photos); // decommenter si site avec layers en dur *********

                        try {
                            siteRasters = await getAllRastersByIdSite(site.id);
                            if (siteRasters.length) {
                                console.log("Rasters chargés pour le site :", siteRasters);
                                addDynamicLayers(siteRasters);
                            }
                            // Récupérer les tampons associés à ce site
                            const siteTampons = await getAllTamponsByIdSite(site.id);
                           if (siteTampons.length) {
                            // console.log("Tampons chargés pour le site :", siteTampons);
                            displayTamponsOnMap(siteTampons);
                           }
                           if (siteTampons.length) {
                            const sitePipes = await getAllPipesByIdSite(site.id);                       
                            displayPipesOnMap(sitePipes);
                            }

                        } catch (error) {
                            console.error("Erreur lors du chargement des tampons :", error);
                        }

                    });
                }
            }
        });

    } catch (error) {
        console.error("Erreur lors du chargement des sites :", error);
    }
}

// // 3. Écouteur d'événement sur la carte
// map.on('click', function(e) {
//     const lat = e.latlng.lat;
//     const lng = e.latlng.lng;
    
//     // cc44Coords contient [X, Y] en mètres
//     const cc44Coords = convertWGS84ToCC44(lat, lng);
    
//     const xCC44 = cc44Coords[0].toFixed(3); // X en mètres (~1 700 000 m)
//     const yCC44 = cc44Coords[1].toFixed(3); // Y en mètres (~6 200 000 m)

//     // Création du popup
//     L.popup()
//         .setLatLng(e.latlng)
//         .setContent(`
//             <div style="font-family: sans-serif; line-height: 1.4;">
//                 <strong>Coordonnées RGF93 / CC44 :</strong><br>
//                 <b>X :</b> ${xCC44} m<br>
//                 <b>Y :</b> ${yCC44} m
//             </div>
//         `)
//         .openOn(map);
// });

function openCloudView() {
    // Ouvrir le nuage de points dans un nouvel onglet
    window.open('./viewer_potree/viewer_potree.html', '_blank');
}


// async function initApp() {
//     // photos = await loadPhotos();
//     // updateStats();
//     // zones = await loadZones();
//     // displayZones();
//     //On rafraîchit la carte pour afficher les points
//     //refreshPhotos(-1); // -1 = afficher tous les points
//     sites = await loadSites();
// let coordsRognac = [sites[0].coordinates[1], sites[0].coordinates[0]]; // [lat, lng] pour Leaflet

//     let markerRognac = L.marker(coordsRognac).addTo(map);
//     markerRognac.bindPopup(`
//             <strong>Site de Rognac</strong><br>
//             Bienvenue dans le Sud-Est !<br>
//             <small>Lat: ${coordsRognac[0]}, Lon: ${coordsRognac[1]}</small>
//         `);




// }


//const popupContent = `
                //     <div class="site-popup">
                //         <h3>${site.name}</h3>
                //         <p><strong>Ville :</strong> ${site.city}</p>
                //         ${site.img ? `<img src="${site.img}" alt="${site.name}" style="width:100%; max-width:150px;"/>` : ''}
                //     </div>
                // `;

// // Optionnel : enregistrer l'événement au clic sur le marqueur
// marker.on('click', () => {
//     // console.log(`Clic sur le site : ${site.name}`);
//     // Vous pouvez ici mettre à jour votre sidebar HTML 
//     // document.getElementById('photo_name').textContent = site.name;
//     // document.getElementById('lng').textContent = lng;
//     // document.getElementById('lat').textContent = lat;
    
// });
// 3. Charger tes données depuis ton API PHP
// async function loadSites() {
//     try {
//         const response = await fetch(`${api_url}sites`, {
//             method: 'GET',
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         });
//         const data = await response.json();
//         console.log("Sites chargés :", data);
//         // On transforme le tableau d'objets JSON en tableau d'objets Photo
//         sites = data.map(s => new Site(
//             s.id,
//             s.name,
//             s.city,
//             s.img,
//             [parseFloat(s.coordinates[0]), parseFloat(s.coordinates[1])]// [lng, lat] 
//             ) );

//         return sites;

//     } catch (error) {
//         console.error("Erreur de chargement :", error);
//     }
// }