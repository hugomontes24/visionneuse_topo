# Architecture - Affichage des coordonnées CC du site

## Structure des données

```
API PHP (SiteRepository)
    ↓
SELECT id, name, city, img, 
       json_build_array(ST_X(geom), ST_Y(geom), ST_Z(geom)) AS coordinates_cc,
       ST_AsGeoJSON(ST_Transform(geom, 4326))::json->'coordinates' AS coordinates_wgs84
FROM sites
    ↓
SiteGetDTO (hydrate + toArray)
    ↓
JSON Response
{
    "id": 1,
    "name": "Site Name",
    "city": "City",
    "img": "image.jpg",
    "coordinates": [-0.25, 47.66],
    "coordinatesCc": [1700000.123, 6200000.456, 45.789],
    "coordinatesWgs84": [-0.25, 47.66, 45.789]
}
    ↓
Frontend JavaScript (serviceSites.getAllSites)
    ↓
Site Object
{
    id: 1,
    name: "Site Name",
    city: "City",
    img: "image.jpg",
    coordinates: [-0.25, 47.66],
    coordinatesCc: [1700000.123, 6200000.456, 45.789],
    coordinatesWgs84: [-0.25, 47.66, 45.789]
}
    ↓
User clicks marker
    ↓
serviceHttp.displaySiteCoordinates(site)
    ↓
Display in sidebar:
<div id="aside-site-coords">
    <span id="aside-site-coords-cc">
        CC44 du site : X: 1700000.123 m, Y: 6200000.456 m, Z: 45.789 m
    </span>
    <span id="aside-site-coords-wgs84">
        GPS du site : Lon: -0.250341, Lat: 47.662704
    </span>
</div>
```

## Conversion de coordonnées PostgreSQL

```
Stockage BDD (EPSG:3947 = CC47)
    ↓ (via geom column)
ST_X(geom) → Récupère la composante X (longitude)
ST_Y(geom) → Récupère la composante Y (latitude)
ST_Z(geom) → Récupère la composante Z (altitude)
    ↓
[X, Y, Z] = Coordonnées CC44 en mètres
    ↓
ST_Transform(geom, 4326) → Convertit en WGS84 (EPSG:4326)
    ↓
ST_AsGeoJSON(...) → Format GeoJSON
    ↓
[Longitude, Latitude, Z] = Coordonnées WGS84 en degrés décimaux
```

## Flux d'interaction utilisateur

```
1. Page charge
   ├─ config.js définit API_COORDS = "CC44"
   ├─ serviceHttp.js crée références DOM
   └─ serviceSites.js prépare géolocalisateurs

2. Utilisateur clique sur marqueur site
   ├─ initApp → marker.on('click')
   ├─ Zoom vers site
   ├─ Affiche titre dans sidebar
   ├─ Appelle displaySiteCoordinates(site)
   └─ displaySiteCoordinates:
      ├─ Vérifie coordinatesCc.length >= 2
      ├─ Affiche dans aside-site-coords-cc avec format 3 décimales
      ├─ Vérifie coordinatesWgs84.length >= 2
      ├─ Affiche dans aside-site-coords-wgs84 avec format 6 décimales
      └─ Montre le conteneur aside-site-coords (display: block)

3. Chargement des tampons/rasters
   └─ Affichage des éléments enfants du site
```

## Gestion des erreurs

```
Site sans coordonnées CC:
    coordinatesCc = []
    Affichage: Conteneur visible mais vide (ne s'affiche pas)

Site avec Z manquant:
    coordinatesCc = [X, Y]
    Affichage: Z: N/A

Site sans coordonnées WGS84:
    coordinatesWgs84 = []
    Affichage: Conteneur non visible
```

## Points de vérification

- ✓ Coordonnées CC: Format [X, Y, Z] en mètres, 3 décimales
- ✓ Coordonnées WGS84: Format [Lon, Lat, Z] en degrés, 6 décimales
- ✓ Conversion PostGIS: ST_Transform(geom, 4326) → [Lon, Lat]
- ✓ Affichage: HTML div masqué par défaut, affiché au clic
- ✓ Labels: API_COORDS utilisé pour identifier le système
