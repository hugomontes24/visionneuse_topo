# Ajout de l'affichage du système CC dans la sidebar pour les sites

## Résumé des modifications

Cette mise à jour ajoute l'affichage des coordonnées du système de coordonnées CC (Conical Conformal) dans la sidebar latérale lorsqu'un site est sélectionné.

## Fichiers modifiés

### Backend (PHP)

1. **api_viewer/appli/Entity/SiteGetDTO.php**
   - Ajout des propriétés `coordinatesCc` et `coordinatesWgs84`
   - Ajout des getters/setters correspondants
   - Modification de `toArray()` pour inclure les nouvelles coordonnées
   - Modification de `hydrate()` pour récupérer les coordonnées depuis les données

2. **api_viewer/appli/Repository/SiteRepository.php**
   - Modification de `getOne()` pour extraire les coordonnées CC et WGS84 depuis la base de données PostgreSQL
   - Modification de `getAll()` pour inclure les coordonnées CC et WGS84 pour tous les sites
   - Utilisation de `ST_X()`, `ST_Y()`, `ST_Z()` pour extraire les coordonnées CC47
   - Utilisation de `ST_Transform()` pour convertir les coordonnées en WGS84

### Frontend (JavaScript)

1. **ortho_viewer/js/Entities/Site.js**
   - Ajout des paramètres `coordinatesCc` et `coordinatesWgs84` au constructeur
   - Stockage de ces coordonnées comme propriétés de la classe

2. **ortho_viewer/js/services/serviceSites.js**
   - Modification de `getAllSites()` pour inclure les coordonnées CC et WGS84 lors de la création des objets Site
   - Récupération de `coordinatesCc` et `coordinatesWgs84` depuis la réponse API

3. **ortho_viewer/js/services/serviceHttp.js**
   - Ajout de la fonction `displaySiteCoordinates()` pour afficher les coordonnées du site dans la sidebar
   - Ajout des références aux éléments DOM pour les coordonnées du site
   - Appel de `displaySiteCoordinates(site)` lors du clic sur un marqueur de site

4. **ortho_viewer/js/config/config.js**
   - Ajout de la constante `API_COORDS = "CC44"` pour identifier le système de coordonnées utilisé

### Frontend (HTML)

1. **ortho_viewer/index.html**
   - Ajout d'un conteneur `<div id="aside-site-coords">` dans la sidebar pour afficher les coordonnées du site
   - Ajout de deux éléments `<span>` pour afficher les coordonnées CC et WGS84 du site
   - Le conteneur est masqué par défaut et s'affiche uniquement quand un site est sélectionné

## Fonctionnement

Lorsqu'un utilisateur clique sur un marqueur de site:

1. Le site est téléchargé avec ses coordonnées (depuis l'API)
2. Un zoom fluide vers le site est effectué
3. La sidebar affiche:
   - Le nom du site
   - Les coordonnées en système CC44 (X, Y, Z en mètres)
   - Les coordonnées GPS/WGS84 (Longitude, Latitude)
4. Les tampons et rasters du site sont chargés et affichés

## Dépendances

- PostgreSQL/PostGIS avec les tables de sites contenant des géométries
- Les géométries doivent être en EPSG:3947 (CC47/CC44)

## Notes

- Les coordonnées Z peuvent être N/A si non disponibles dans la base de données
- Le système de coordonnées utilise CC44 par défaut, peut être changé en modifiant `API_COORDS` dans le config
- Les coordonnées WGS84 sont converties automatiquement depuis les coordonnées CC via PostGIS
