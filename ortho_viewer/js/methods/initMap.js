/**
 * Initialisation de la carte
 */


const map = L.map('map',{   zoomControl: false,
                                preferCanvas: true // Utilisation du canvas pour de meilleures performances
                                }).setView(initialCoords, zoomInitial1);


// Création de plans (panes) personnalisés avec des z-index contrôlés
map.createPane('paneRasters');
map.getPane('paneRasters').style.zIndex = 200; // En dessous

map.createPane('paneSideBySide');
map.getPane('paneSideBySide').style.zIndex = 300; // Au-dessus


const noLayer = L.layerGroup(); // Couche vide pour le contrôle des calques
const osmLayer =  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 22,
                                attribution: '&copy; OpenStreetMap'
                            }).addTo(map);

const baseMaps = {"OpenStreetMap": osmLayer, "Fond transparent": noLayer };// L.tileLayer habituel, on peut mettre d'autres}