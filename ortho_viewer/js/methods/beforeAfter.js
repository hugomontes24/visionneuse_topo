// const buttonsAvAp = document.getElementById('buttonsAvAp');
let sideBySideLeftContainer = null;
let opacityControlLeftDiv = null;
let buttonsAvApLft = null;
let titleL = null;
let btnBackL = null; // flèche pour sortir du mode side by side

let sideBySideRightContainer = null;
let opacityControlRightDiv = null;
let buttonsAvApRgt = null;
let titleR = null;

// function pour initialiser la fonctionnalité "Avant/Après"
function initBeforeAfter() {
    sideBySideLeftContainer = document.createElement('div'); // sideBySideLeftContainer
    sideBySideLeftContainer.id = 'side-by-side-left-container';
    // Création de la div d'opacité
        opacityControlLeftDiv = document.createElement('div');
        opacityControlLeftDiv.id = 'opacity-control-left';
        //<input type="range" id="opacity-slider-left" min="0" max="1" step="0.05" value="1">
        opacityControlLeftDiv.innerHTML =`<input type="range" id="opacity-slider-left" min="0" max="1" step="0.05" value="1"><br>`;
   
        buttonsAvApLft = document.createElement('div');
        buttonsAvApLft.className = 'custom-left-timeline';
            titleL = document.createElement('div');
            titleL.className = 'timeline-title';
            titleL.innerHTML = 'Raster G';
        buttonsAvApLft.appendChild(titleL);
      btnBackL = document.createElement('div');
      btnBackL.title = 'Retour';
      btnBackL.className = 'back';
      btnBackL.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none"><path d="M19 12H5m0 0 7-7m-7 7 7 7" stroke="#e7133a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
      btnBackL.addEventListener('click', quitBeforeAfter);
    sideBySideLeftContainer.appendChild(btnBackL);
    sideBySideLeftContainer.appendChild(opacityControlLeftDiv);
    sideBySideLeftContainer.appendChild(buttonsAvApLft);
    mapDiv.appendChild(sideBySideLeftContainer);

    sideBySideRightContainer = document.createElement('div'); // sideBySideLeftContainer
    sideBySideRightContainer.id = 'side-by-side-right-container';
    opacityControlRightDiv = document.createElement('div');
    opacityControlRightDiv.id = 'opacity-control-right';
    opacityControlRightDiv.innerHTML=`<input type="range" id="opacity-slider-right" min="0" max="1" step="0.05" value="1"><br>`;
    buttonsAvApRgt = document.createElement('div');
    buttonsAvApRgt.className = 'custom-right-timeline';
        titleR = document.createElement('div');
        titleR.className = 'timeline-title';
        titleR.innerHTML = 'Raster D';
    buttonsAvApRgt.appendChild(titleR);
       const btnbackR = btnBackL.cloneNode(true);  // clone du bouton pour sortir du mode side by side
       btnbackR.addEventListener('click', quitBeforeAfter);
    sideBySideRightContainer.appendChild(btnbackR);
    sideBySideRightContainer.appendChild(opacityControlRightDiv);
    sideBySideRightContainer.appendChild(buttonsAvApRgt);
    mapDiv.appendChild(sideBySideRightContainer);

    // leftLayer = L.tileLayer(photos[0].url, { maxZoom: 22, minZoom: 13,//*******j'ai changé le [] photos par siteRasters pour récupérer les rasters depuis l'API
    leftLayer = L.tileLayer(siteRasters[0].url, { maxZoom: 22, minZoom: 13,
                                                tms:false,
                                                pane: 'paneSideBySide', attribution: 'Created by QGIS' 
                                            }).addTo(map);
    rightLayer = L.tileLayer(siteRasters[siteRasters.length - 1].url, { maxZoom: 22, minZoom: 13,
                                                tms:false,
                                                pane: 'paneSideBySide', attribution: 'Created by QGIS' 
                                            }).addTo(map);

    // --- AJOUT : ÉCOUTE DU SLIDER D'OPACITÉ SUR LEFTLAYER ---
    const leftSlider = document.getElementById('opacity-slider-left');
    const rightSlider = document.getElementById('opacity-slider-right');
    // const labelValue = document.getElementById('opacity-value');

    if (leftSlider) {
        // Réinitialiser la valeur du slider à 1 (100%) au lancement
        leftSlider.value = 1;
        // if (labelValue) labelValue.textContent = '100%';
        // Appliquer l'opacité dynamique sur leftLayer
        leftSlider.oninput = (e) => {
            const opacityVal = parseFloat(e.target.value);
            if (leftLayer) {
                leftLayer.setOpacity(opacityVal);
            }
            // if (labelValue) {labelValue.textContent = `${Math.round(opacityVal * 100)}%`;}
        };
    }
    if (rightSlider) {
        rightSlider.value = 1;
        rightSlider.oninput = (e) => {
            const opacityVal = parseFloat(e.target.value);
            if (rightLayer) {
                rightLayer.setOpacity(opacityVal);
            }
        };
    }

    // if (!map.hasLayer(leftLayer)) {leftLayer.addTo(map);}
    // if (!map.hasLayer(rightLayer)) {rightLayer.addTo(map);}
    if (!sideBySideControl) {
        sideBySideControl = L.control.sideBySide(leftLayer, rightLayer).addTo(map);
 
    // Intégration de la protection anti-accrochage de la carte dès la création du contrôle
        const waitForDivider = setInterval(() => {
            const divider = document.querySelector('.leaflet-sbs-range');
            if (divider) {
                clearInterval(waitForDivider);
                // Désactive le déplacement de la carte quand on attrape la barre
                divider.addEventListener('mousedown', () => map.dragging.disable());
                divider.addEventListener('touchstart', () => map.dragging.disable());
                // Réactive le déplacement de la carte au relâché
                document.addEventListener('mouseup', () => map.dragging.enable());
                document.addEventListener('touchend', () => map.dragging.enable());
            }
        }, 100);
    }
    
      // 4. Boucle sur les photos pour alimenter les deux côtés
    siteRasters.forEach((photo, index) => {
        // --- CÔTÉ DROIT (buttonsAvAp left and right) ---
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        if (index === siteRasters.length - 1) timelineItem.classList.add('active'); // Dernier actif par défaut à droite

        const btn = document.createElement('button');
        btn.className = 'button';
        btn.textContent = photo.date ? photo.date : photo.year;

        btn.addEventListener('click', () => {
            rightLayer.setUrl(photo.url); // Met à jour la couche de droite
            document.querySelectorAll('.timeline-item').forEach(item => item.classList.remove('active'));
            timelineItem.classList.add('active');
        });

        timelineItem.appendChild(btn);
        buttonsAvApRgt.appendChild(timelineItem);


        // --- CÔTÉ GAUCHE (buttonsAvApLft) ---
        const timelineItem1 = document.createElement('div');
        timelineItem1.className = 'timeline1-item';
        if (index === 0) timelineItem1.classList.add('active'); // Premier actif par défaut à gauche

        const btn1 = document.createElement('button');
        btn1.className = 'button1';
        btn1.textContent = photo.date ? photo.date : photo.year;

        btn1.addEventListener('click', () => {
            leftLayer.setUrl(photo.url); // Met à jour la couche de gauche
            document.querySelectorAll('.timeline1-item').forEach(item => item.classList.remove('active'));
            timelineItem1.classList.add('active');
        });

        timelineItem1.appendChild(btn1);
        buttonsAvApLft.appendChild(timelineItem1);
    });
    
    mapControlDiv.classList.add('none'); // Masque ou affiche les boutons de contrôle de la carte
}

function quitBeforeAfter() {

    mapControlDiv.classList.remove('none');
    // 1. Retirer les couches du side-by-side et de la carte
    if (map.hasLayer(leftLayer)) map.removeLayer(leftLayer);
    if (map.hasLayer(rightLayer)) map.removeLayer(rightLayer);

    // Suppression des conteneurs visuels
    if (sideBySideLeftContainer) { sideBySideLeftContainer.remove(); sideBySideLeftContainer = null; }
    if (sideBySideRightContainer) { sideBySideRightContainer.remove(); sideBySideRightContainer = null; }
    
    if (opacityControlLeftDiv) { opacityControlLeftDiv.remove(); opacityControlLeftDiv = null; }
    if (opacityControlRightDiv) { opacityControlRightDiv.remove(); opacityControlRightDiv = null; }

    if (sideBySideControl) { // sidebyside leaflet
        map.removeControl(sideBySideControl);
        sideBySideControl = null;
    }

    // 3. Vider les timelines
    if (buttonsAvApRgt) { buttonsAvApRgt.remove(); buttonsAvApRgt = null;}   
    if (buttonsAvApLft) { buttonsAvApLft.remove(); buttonsAvApLft = null;} 

    isBeforeAfterActive = false;
}

// function pour activer/désactiver le mode "Point Info"


// 3. Écouteur d'événement sur la carte
// function displayPointInfo() {
//     map.on('click', function(e) {
//         const lat = e.latlng.lat;
//         const lng = e.latlng.lng;
        
//         // cc44Coords contient [X, Y] en mètres
//         const cc44Coords = convertWGS84ToCC44(lat, lng);
        
//         const xCC44 = cc44Coords[0].toFixed(3); // X en mètres (~1 700 000 m)
//         const yCC44 = cc44Coords[1].toFixed(3); // Y en mètres (~6 200 000 m)

//         // Création du popup
//         L.popup()
//             .setLatLng(e.latlng)
//             .setContent(`
//                 <div style="font-family: sans-serif; line-height: 1.4;">
//                     <strong>Coordonnées RGF93 / CC44 :</strong><br>
//                     <b>X :</b> ${xCC44} m<br>
//                     <b>Y :</b> ${yCC44} m
//                 </div>
//             `)
//             .openOn(map);
//     });
// }
