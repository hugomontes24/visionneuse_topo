# Guide d'utilisation - Affichage du système CC dans la sidebar

## 🎯 Fonctionnalité

Lorsqu'un utilisateur clique sur un marqueur de site sur la carte, la sidebar latérale affiche maintenant automatiquement:

1. **Coordonnées du système CC44** (Conforme Conique)
   - X : Coordonnée en mètres (ex: 1700000.123 m)
   - Y : Coordonnée en mètres (ex: 6200000.456 m)
   - Z : Altitude en mètres (ex: 45.789 m)

2. **Coordonnées GPS/WGS84**
   - Longitude : En degrés décimaux (ex: -0.250341)
   - Latitude : En degrés décimaux (ex: 47.662704)

## 📍 Utilisation

### Étape 1: Afficher les sites
- Zoomez à un niveau suffisant (< zoom 15)
- Les marqueurs de sites apparaissent sur la carte

### Étape 2: Sélectionner un site
- Cliquez sur n'importe quel marqueur de site
- La vue se centre sur le site (zoom fluide)
- La sidebar se met à jour avec:
  - Le nom du site
  - Les coordonnées CC44
  - Les coordonnées GPS

### Étape 3: Consulter les données
```
┌─────────────────────────────┐
│      NOM DU SITE            │
├─────────────────────────────┤
│                             │
│ CC44 du site :              │
│ X : 1700000.123 m           │
│ Y : 6200000.456 m           │
│ Z : 45.789 m                │
│                             │
│ GPS du site :               │
│ Lon : -0.250341             │
│ Lat : 47.662704             │
│                             │
└─────────────────────────────┘
```

## ⚙️ Configuration

### Changer le système de coordonnées

Si vous utilisez un système autre que CC44 (par exemple CC47):

1. Ouvrez `ortho_viewer/js/config/config.js`
2. Modifiez la ligne:
   ```javascript
   const API_COORDS = "CC47";  // Changez "CC44" par votre système
   ```
3. Rechargez la page

## 🔧 Dépannage

### Les coordonnées ne s'affichent pas

**Cause 1: Les sites n'ont pas de géométrie en base de données**
- Solution: Vérifier que les sites ont une colonne `geom` remplie dans PostgreSQL

**Cause 2: Les coordonnées sont vides**
- Affichage attendu: Les éléments restent invisibles
- Solution: Vérifier que PostGIS est installé et les géométries sont valides

**Cause 3: Altitude (Z) manquante**
- Affichage: "Z : N/A"
- Cause normale: La coordonnée Z n'est pas définie pour ce site

### Les coordonnées sont imprécises

- Vérifier le système de coordonnées utilisé (config.js)
- Vérifier la transformation EPSG utilisée (SiteRepository.php)
- Vérifier que les géométries en BDD sont dans le bon système de référence

## 📊 Format des données

### Coordonnées CC44
- **Unité**: Mètres
- **Précision**: 3 décimales
- **Format**: [X, Y, Z] ou [X, Y] si Z manquante
- **Exemple**: [1700000.123, 6200000.456, 45.789]

### Coordonnées WGS84 (GPS)
- **Unité**: Degrés décimaux
- **Précision**: 6 décimales
- **Format**: [Longitude, Latitude, Z] ou [Longitude, Latitude] si Z manquante
- **Exemple**: [-0.250341, 47.662704]

## 🛠️ Personnalisation visuelle

Pour personnaliser l'affichage, modifiez le CSS dans `ortho_viewer/css/style_aside.css`:

```css
#aside-site-coords {
    /* Votre style personnalisé */
    margin: 10px 0;
    padding: 10px;
    border-left: 3px solid #4CAF50;
    background-color: #f9f9f9;
}
```

## 📝 Informations techniques

### Ordre de chargement
1. `config.js` - Définit `API_COORDS`
2. `serviceHttp.js` - Crée références DOM
3. `serviceSites.js` - Charge les sites depuis API
4. Clic utilisateur → `displaySiteCoordinates()` → Affichage

### API Response
```json
{
    "id": 1,
    "name": "Rognac",
    "city": "Provence",
    "img": "image.jpg",
    "coordinates": [-0.25, 47.66],
    "coordinatesCc": [1700000.123, 6200000.456, 45.789],
    "coordinatesWgs84": [-0.25, 47.66, 45.789]
}
```

## ❓ FAQ

**Q: Pourquoi deux systèmes de coordonnées?**
- CC44 est utilisé pour les plans cadastraux locaux
- WGS84 est utilisé pour l'affichage sur cartes numériques (Leaflet)

**Q: Les coordonnées peuvent-elles être exportées?**
- Actuellement: Affichage uniquement
- À venir: Possibilité de copier/exporter les coordonnées

**Q: Quel est le EPSG du système CC?**
- EPSG:3947 = CC47 (Corse)
- EPSG:3944 = CC44 (Provence/Côte d'Azur)
- Vérifiez dans votre config PostGIS

**Q: Les coordonnées changent-elles avec le zoom?**
- Non, elles restent constantes
- C'est les coordonnées du point du site, pas de la caméra

## 📞 Support

Pour plus d'informations:
- Vérifiez la documentation PostGIS: https://postgis.net/
- Documentation Leaflet: https://leafletjs.com/
- Documentation proj4js: https://proj4js.org/
