// Variable globale pour garder la trace du conteneur de gauche
let buttonsAvAp1 = null;

function initBeforeAfter() {
    buttonsAvAp.innerHTML = '';

    // Si le mode est déjà actif, on désactive et on nettoie tout
    if (isBeforeAfterActive) {
        if (map.hasLayer(leftLayer)) map.removeLayer(leftLayer);
        if (map.hasLayer(rightLayer)) map.removeLayer(rightLayer);
        
        if (sideBySideControl) {
            map.removeControl(sideBySideControl);
            sideBySideControl = null;
        }

        // Rallumer les couches de base QGIS
        if (!map.hasLayer(tilesource2026_layer)) tilesource2026_layer.addTo(map);
        if (!map.hasLayer(tilesource2025_layer)) tilesource2025_layer.addTo(map);

        // Nettoyer les timelines
        buttonsAvAp.innerHTML = '';
        if (buttonsAvAp1) {
            buttonsAvAp1.remove(); // Supprime le conteneur de gauche de la carte
            buttonsAvAp1 = null;
        }

        isBeforeAfterActive = false;
        console.log("Mode Avant/Après désactivé");
        return;
    }

    // --- SINON : ON ACTIVE LE MODE ---
    
    // 1. Éteindre les couches QGIS normales
    if (map.hasLayer(tilesource2025_layer)) map.removeLayer(tilesource2025_layer);
    if (map.hasLayer(tilesource2026_layer)) map.removeLayer(tilesource2026_layer);

    // 2. Initialisation des couches Side-by-Side
    leftLayer = L.tileLayer(photos[0].url, { maxZoom: 22, minZoom: 13, tms: false, attribution: 'Created by QGIS' }).addTo(map);
    rightLayer = L.tileLayer(photos[1].url, { maxZoom: 22, minZoom: 13, tms: false, attribution: 'Created by QGIS' }).addTo(map);

    if (!sideBySideControl) {
        sideBySideControl = L.control.sideBySide(leftLayer, rightLayer).addTo(map);
   
        // Protection anti-accrochage de la carte
        const waitForDivider = setInterval(() => {
            const divider = document.querySelector('.leaflet-sbs-range');
            if (divider) {
                clearInterval(waitForDivider);
                divider.addEventListener('mousedown', () => map.dragging.disable());
                divider.addEventListener('touchstart', () => map.dragging.disable());
                document.addEventListener('mouseup', () => map.dragging.enable());
                document.addEventListener('touchend', () => map.dragging.enable());
            }
        }, 100);
    }

    // 3. CRÉATION DU CONTENEUR DE GAUCHE (Une seule fois ici)
    buttonsAvAp1 = document.createElement('div');
    // On utilise les classes natives de Leaflet pour le positionner en bas à gauche par-dessus la carte
    buttonsAvAp1.className = 'leaflet-bottom leaflet-left custom-timeline-container';
    buttonsAvAp1.style.margin = '10px'; // Un petit décalage par rapport aux bords
    map.getContainer().appendChild(buttonsAvAp1);

    // 4. Boucle sur les photos pour alimenter les deux côtés
    photos.forEach((photo, index) => {
        // --- CÔTÉ DROIT (buttonsAvAp) ---
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item';
        if (index === photos.length - 1) timelineItem.classList.add('active'); // Dernier actif par défaut à droite

        const btn = document.createElement('button');
        btn.className = 'button';
        btn.textContent = photo.date ? photo.date : photo.year;

        btn.addEventListener('click', () => {
            rightLayer.setUrl(photo.url); // Met à jour la couche de droite
            document.querySelectorAll('#buttonsAvAp .timeline-item').forEach(item => item.classList.remove('active'));
            timelineItem.classList.add('active');
        });

        timelineItem.appendChild(btn);
        buttonsAvAp.appendChild(timelineItem);


        // --- CÔTÉ GAUCHE (buttonsAvAp1) ---
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
        buttonsAvAp1.appendChild(timelineItem1);
    });

    isBeforeAfterActive = true;
}