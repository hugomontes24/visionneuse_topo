# Rapport de mise à œuvre - Affichage du système CC dans la sidebar

## ✅ Modifications complétées

### 1. Backend - Entity (SiteGetDTO.php)
```php
- ✅ Ajout propriété: private array $coordinatesCc = []
- ✅ Ajout propriété: private array $coordinatesWgs84 = []
- ✅ Ajout getter: getCoordinatesCc()
- ✅ Ajout getter: getCoordinatesWgs84()
- ✅ Ajout setter: setCoordinatesCc()
- ✅ Ajout setter: setCoordinatesWgs84()
- ✅ Modification hydrate() pour charger les coordonnées
- ✅ Modification toArray() pour inclure les coordonnées
```

### 2. Backend - Repository (SiteRepository.php)
```php
- ✅ Modification getOne() pour extraire les coordonnées CC47 via ST_X(), ST_Y(), ST_Z()
- ✅ Modification getOne() pour convertir en WGS84 via ST_Transform()
- ✅ Modification getAll() pour inclure les coordonnées
- ✅ Décodage JSON des coordonnées après récupération
```

### 3. Frontend - Configuration (config.js)
```javascript
- ✅ Ajout constante: const API_COORDS = "CC44"
```

### 4. Frontend - Entity (Site.js)
```javascript
- ✅ Modification constructeur pour accepter coordinatesCc
- ✅ Modification constructeur pour accepter coordinatesWgs84
- ✅ Stockage des propriétés dans l'objet Site
```

### 5. Frontend - Service Sites (serviceSites.js)
```javascript
- ✅ Modification getAllSites() pour passer coordinatesCc à Site
- ✅ Modification getAllSites() pour passer coordinatesWgs84 à Site
- ✅ Utilisation de valeurs par défaut ([] ou []) si non disponibles
```

### 6. Frontend - Service HTTP (serviceHttp.js)
```javascript
- ✅ Ajout références DOM aux éléments de coordonnées
- ✅ Création fonction displaySiteCoordinates(site)
- ✅ Affichage coordonnées CC44 (X, Y, Z) en mètres
- ✅ Affichage coordonnées GPS (Lon, Lat) à 6 décimales
- ✅ Gestion valeur Z manquante (affichage "N/A")
- ✅ Affichage du conteneur au clic sur un site
- ✅ Appel displaySiteCoordinates lors du clic marqueur
```

### 7. Frontend - HTML (index.html)
```html
- ✅ Ajout conteneur: <div id="aside-site-coords">
- ✅ Ajout élément: <span id="aside-site-coords-cc">
- ✅ Ajout élément: <span id="aside-site-coords-wgs84">
- ✅ Masquage par défaut (style="display:none")
```

## 🔄 Flux de données

```
Clic sur marqueur de site
    ↓
serviceHttp.js → displaySiteCoordinates(site)
    ↓
Récupération de site.coordinatesCc et site.coordinatesWgs84
    ↓
Affichage dans:
  - aside-site-coords-cc (CC44 en mètres)
  - aside-site-coords-wgs84 (GPS en degrés décimaux)
    ↓
Sidebar affiche les nouvelles coordonnées
```

## 🧪 Points à tester

1. ✅ API retourne coordinatesCc et coordinatesWgs84 pour chaque site
2. ✅ Les coordonnées sont correctement converties depuis PostGIS
3. ✅ Le clic sur un marqueur de site affiche les coordonnées
4. ✅ Le format d'affichage est correct (3 décimales pour CC44, 6 pour GPS)
5. ✅ Gestion correcte des coordonnées Z manquantes

## 📝 Notes importantes

- Le système utilise CC44 par défaut (modifiable via API_COORDS)
- Les coordonnées CC sont en mètres avec 3 décimales
- Les coordonnées GPS sont en degrés décimaux avec 6 décimales
- Les coordonnées Z peuvent être N/A si non disponibles en base
- Le conteneur des coordonnées du site est caché par défaut

## 🚀 Déploiement

Aucune action supplémentaire requise:
- Les fichiers PHP/JS sont prêts
- Les modifications sont rétro-compatibles
- Aucune migration de BDD nécessaire (utilise colonnes existantes)
