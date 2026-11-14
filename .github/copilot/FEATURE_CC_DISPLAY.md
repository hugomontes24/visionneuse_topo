# Feature: Affichage du système CC dans la sidebar

## Description
Ajout de l'affichage du système de coordonnées CC (Conforme Conique) dans la sidebar latérale lorsqu'un site est sélectionné sur la carte.

## Contexte
Lors de la sélection d'un site sur la visionneuse topographique, les utilisateurs peuvent maintenant consulter:
- Les coordonnées du système CC44 (X, Y, Z en mètres)
- Les coordonnées GPS/WGS84 (Longitude, Latitude en degrés décimaux)

## Modifications

### Backend (PHP)
1. **SiteGetDTO.php**
   - Ajout des propriétés `coordinatesCc` et `coordinatesWgs84`
   - Ajout des getters/setters correspondants
   - Mise à jour de `hydrate()` et `toArray()`

2. **SiteRepository.php**
   - Extraction des coordonnées CC47 via `ST_X()`, `ST_Y()`, `ST_Z()`
   - Conversion en WGS84 via `ST_Transform(geom, 4326)`
   - Décodage JSON des réponses PostGIS

### Frontend (JavaScript)
1. **config.js**
   - Constante `API_COORDS = "CC44"`

2. **Site.js**
   - Paramètres `coordinatesCc` et `coordinatesWgs84` au constructeur

3. **serviceSites.js**
   - Passage des coordonnées lors de la création des objets Site

4. **serviceHttp.js**
   - Fonction `displaySiteCoordinates(site)`
   - Affichage formaté des coordonnées
   - Gestion des données manquantes

### Frontend (HTML)
1. **index.html**
   - Conteneur `aside-site-coords` (masqué par défaut)
   - Éléments pour afficher CC44 et GPS

## Format d'affichage
```
CC44 du site :
X : 1700000.123 m
Y : 6200000.456 m
Z : 45.789 m

GPS du site :
Lon : -0.250341
Lat : 47.662704
```

## Spécifications
- Coordonnées CC44: 3 décimales, en mètres
- Coordonnées GPS: 6 décimales, en degrés décimaux
- Coordonnée Z: "N/A" si manquante
- Affichage seulement si données disponibles

## Dépendances
- PostGIS installé et configuré
- Tables sites avec colonne geom en EPSG:3947
- Leaflet.js pour les marqueurs

## Tests
- ✅ Syntaxe PHP valide
- ✅ Références DOM correctes
- ✅ Flux de données complet
- ✅ Gestion des erreurs

## Notes
- Rétro-compatible avec le code existant
- Aucune migration de BDD requise
- Configuration modifiable via API_COORDS

## Auteur
Assistant de développement - Copilot

## Date
2024
