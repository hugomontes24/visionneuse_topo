const API_NAME = 'orthophotosValorterreLMS'; // 
// const API_NAME = 'orthophotos'; // pour Valorec Rognac
// const API_NAME = 'orthophotosBeffes'; // beffes **** decommenter pour un projet cc47

const basepath = "./orthos/";
const api_url = `http://localhost/${API_NAME}/api_viewer/`;
// const api_url = 'https://otopography.ddns.net/api_viewer/'; // PROD 

// < ** a faire manuellement nouveau site ** >
const API_MAP_EPSG = `4326`; // WGS84
const API_EPSG = `3944`;// pour beffes EPSG:3947
// const API_EPSG = `3947`;     //***BEFFES  decommenter et aussi dans *** utils.js pour un projet cc47
const API_COORDS = `CC44`; // Système de coordonnées du projet (CC44, CC47, etc.)

const initialCoords = [43.5127, 5.3693]; // Centre du quart Sud-Est de la France (latitude, longitude)
const zoomInitial1 = 9; // Zoom adapté au quart Sud-Est
// </ ** a faire manuellement nouveau site ** >


// ********* VARIABLES GLOBALES *********

let isBeforeAfterActive = false; // Variable pour suivre l'état du mode avant/après
let sideBySideControl = null; // Variable pour stocker le contrôle Side-by-Side
let leftLayer = null;// variables layer before after
let rightLayer = null;// variables layer before after
let isPointCoordsActive = false;// Variable pour suivre l'état du mode point info
let pointInfoHandler = null; // Stockera la fonction de callback
const appMode = {   // Définition des modes de l'application,changer le curseur et les interactions selon le mode
                    NORMAL: 'mode_normal',
                    BEFORE_AFTER: 'mode_before_after',
                    TIMELAPSE: 'mode_timelapse',
                    POINT_INFO: 'mode_point_info',
                    EDIT: 'mode_edit',
                    ADD: 'mode_add',
                    EDIT_ZONE: 'mode_edit_zone'
                };
let mode = appMode.NORMAL;
const divMode = document.getElementById('mode');

const mapContainer = document.getElementById('map'); 


// ********************************************************************************//
// **************A CHANGER A CHAQUE NOUVEAU SITE****************************//
// ********************************************************************************//

// ** TODO **  recevoir les données de l'API pour les photos - orthos
const photos = [
    // { url: "./ortho_tuiles2021/{z}/{x}/{y}.png", year: 2021, description: "Orthoimage historique 2021" }
    { url: "./tuiles/tuiles_012026/{z}/{x}/{y}.jpg", year: 2026, date: "01-2026", description: "Image 01-2026" },
    { url: "./tuiles/tuiles_07082026/{z}/{x}/{y}.png", year: 2026, date: "08-2026", description: "Image 08-2026" },
    { url: "./tuiles/tuiles_102026/{z}/{x}/{y}.png", year: 2026, date: "10-2026", description: "Image 10-2026" },
];

let siteRasters = []; // Tableau pour stocker les rasters récupérés depuis l'API

//valorterre lms // ** a faire manuellement nouveau site **
const SITES_IDS = [ 4 ];  // sites qu'on veut faire apparaitre sur la carte

