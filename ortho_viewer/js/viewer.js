const mapDiv = document.getElementById('map_div'); 
const mapControlDiv = document.getElementById('map-controls');

const chosenPictureElmt = document.getElementById("chosen_picture");
const spanPhotoName = document.getElementById("photo_name");
const spanLng = document.getElementById("lng");
const spanLat = document.getElementById("lat");
const pDescription = document.getElementById("p_description");

const btnEdit = document.getElementById("btn_edit");

const btnMicro = document.getElementById("btn_micro");
const btn2D = document.getElementById("btn_2d");
const btn3D = document.getElementById("btn_3d");

const btnNuage = document.getElementById("btn_nuage");

// const btnFilter = document.getElementById("btn_filter");
// const displayFilter = document.getElementById("display_filter");
// const selectEtat = document.getElementById("select_etat");

const btnBefAft = document.getElementById("btn_bef_aft");
const btnPointCoords = document.getElementById("btn_point_coords");
const btnFullscreen = document.getElementById("btn_fullscreen");
const sidebarDiv =  document.getElementById("sidebar_div");
let systemeProjection = 1; // 0 : CC44, 1 : WGS84   Par défaut, on affiche les coordonnées en cc44   

// **************************
// ****  MAIN EXECUTION  *****
// **************************
document.addEventListener('DOMContentLoaded', function () {
    
    initApp(); // httpService.js - fetch photos from API and initialize the app
    updateMapZoomClass(); // Initialisation de la classe de zoom, taille des tampons
    // let currentView =  viewType.VIEW2D; // Default view 
    
    // Écoute des événements
    map.on('zoomend', () => {updateMapZoomClass(); updateSiteVisibility();}); // Update zoom class and site visibility on zoom change
    map.on('load', updateMapZoomClass);

    // Lancement au chargement du DOM
    // document.addEventListener('DOMContentLoaded', () => {
    //     initTimeline();
    // });
    
    btnEdit.addEventListener("click", () => {
        toggleActiveBtn(btnEdit); // 
        mode = mode === appMode.EDIT ? appMode.NORMAL : appMode.EDIT;
        // Changer l'apparence de la carte
        if (mode === appMode.EDIT) {
            mapContainer.style.cursor = "crosshair"; // Curseur de précision
        } else {
            mapContainer.style.cursor = ""; // Retour au curseur normal
        }
    });

    // btnFilter.addEventListener("click", () => {
    //     displayFilter.classList.toggle("active");
    //     btnFilter.classList.toggle("active");
    // });


    // ['click','mousedown','wheel','touchstart'].forEach(evt => {
    //     btnFullscreen.addEventListener(evt, e => e.stopPropagation());
    //     btnFilter.addEventListener(evt, e => e.stopPropagation());
    // });

    btnFullscreen.addEventListener("click", () => { toggleFullscreen(); });

    btnMicro.addEventListener("click", () => {
        const recognition = new SpeechRecognition();
        recognition.lang = 'fr-FR';
        recognition.continuous = true;
        recognition.interimResults = false;
        startVoiceRecognition(recognition); 

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript.trim().toLowerCase();
            console.log('Voice input received: ' + transcript);
            // Here you can add code to handle the voice command   
            handleVoiceCommand(transcript);         
        }   
    });

    btn2D.addEventListener("click", () => { view2D();});

    btn3D.addEventListener("click", () => { view3D(); });

    btnNuage.addEventListener("click", () => { openCloudView(); });

    btnBefAft.addEventListener("click", () => {
        isBeforeAfterActive = !isBeforeAfterActive;  
        initBeforeAfter(); // Initialize the beforeAfter mode when the button is clicked
    });

    btnPointCoords.addEventListener("click", () => {
        isPointCoordsActive = !isPointCoordsActive; 
        toggleActiveBtn(btnPointCoords); //      
        pointCoords(); // display or hide coordinates on map click
    });

     map.on(L.Draw.Event.CREATED, function (event) {
        const layer = event.layer;
        // On ajoute le dessin sur la carte pour qu'il reste visible
        zoneLayerGroup.addLayer(layer);
        const geojson = layer.toGeoJSON(); // Convertit le dessin en format GeoJSON      

        // 1. On récupère le contour (le premier tableau)
        const coords = geojson.geometry.coordinates[0];
 
        const nomZone = prompt("Nom de la zone ?");
         if (nomZone) {
            const zone = new Zone(null,nomZone,0,'',0,coords ); // objet Zone.php
            console.log(zone);
            
            addZone(zone); // objet Zone.php
        }
    });

});

// **************************
// ****  fonctions      *****
// **************************


// // Gestionnaire d'événement sur le changement de projection
// document.getElementById('crs-select').addEventListener('change', () => {
//     if (selectedTampon) {
//         // Redraw avec le nouveau système choisi
//         renderCoords(selectedTampon, currentDescrElem, currentCoordsElem);
//     }
// });

// function updateAsideInfo(tampon, asideInfoDescr, asideInfoCoords) {
//     selectedTampon = tampon;
//     currentDescrElem = asideInfoDescr;
//     currentCoordsElem = asideInfoCoords;

//     asideInfoDescr.innerHTML = `<strong>Tampon :</strong> ${tampon.description}`;
//     renderCoords(tampon, asideInfoCoords);
// }

// function renderCoords(tampon, containerElem) {
//     const selectedCRS = document.getElementById('crs-select').value;
//     const [lng, lat] = tampon.coordinates;

//     if (selectedCRS === 'CC44') {
//         const cc44 = convertWGS84ToCC44(lng, lat);
//         containerElem.innerHTML = `X : ${cc44.x.toFixed(2)} m<br>Y : ${cc44.y.toFixed(2)} m (CC44)`;
//     } else {
//         // Mode WGS84 par défaut
//         containerElem.innerHTML = `Lat : ${lat.toFixed(6)}°<br>Lng : ${lng.toFixed(6)}° (WGS84)`;
//     }
// }



 // window.addEventListener('message', function(e){

    //     if (e.data.type === "PHOTO_CLICK"){

    //         chosenPhoto = getPhotoById(e.data.id); // class Photo
    //         const [lng,lat] = chosenPhoto.coordinates
            
    //         chosenPictureElmt.src = `${basepath}${chosenPhoto.name}`;
    //         spanPhotoName.innerHTML= chosenPhoto.name;
    //         spanLng.innerHTML = lng;
    //         spanLat.innerHTML = lat;
    //         pDescription.innerHTML = chosenPhoto.description;           

    //         chosenPictureElmt.className = ""; // reset class
    
    //         if(chosenPhoto.etat === 1){
    //             chosenPictureElmt.className = "warning";
    //         }else if(chosenPhoto.etat === 2){
    //             chosenPictureElmt.className = "danger";
    //         }
    //     }
    // }); 

        // selectEtat.addEventListener("change", function(){         
    //     refreshPhotos(Number(this.value));
    // });

    //     btnEtatNormal.addEventListener("click", function(){
    //         refreshPhotos(0);
    //     });
    //     btnEtatAttention.addEventListener("click", function(){
    //         refreshPhotos(1);
    //     });
    //     btnEtatDanger.addEventListener("click", function(){
    //         refreshPhotos(2);
    //     });
