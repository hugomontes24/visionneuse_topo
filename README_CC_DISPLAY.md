# Affichage du système CC dans la sidebar - Documentation

## 📋 Vue d'ensemble

Cette implémentation ajoute l'affichage automatique du système de coordonnées CC (Conforme Conique) dans la sidebar latérale de la visionneuse topographique lorsqu'un utilisateur sélectionne un site sur la carte.

## 🎯 Résumé des changements

### Quoi?
- Récupération des coordonnées CC44 et GPS depuis la base de données
- Affichage formaté des coordonnées dans la sidebar
- Support des données manquantes (coordonnée Z)

### Où?
- Backend: SiteGetDTO.php, SiteRepository.php
- Frontend: config.js, Site.js, serviceSites.js, serviceHttp.js, index.html

### Comment?
- Extraction des coordonnées via PostGIS (ST_X, ST_Y, ST_Z)
- Conversion en WGS84 via ST_Transform
- Affichage au clic sur un marqueur de site

---

## 📚 Documentation complète

### Pour les développeurs
1. **IMPLEMENTATION_REPORT.md** - Rapport technique détaillé
2. **ARCHITECTURE_CC_DISPLAY.md** - Architecture et flux de données
3. **FEATURE_CC_DISPLAY.md** - Description de la fonctionnalité

### Pour les administrateurs
1. **DEPLOYMENT_CHECKLIST.md** - Checklist de déploiement
2. **CHANGES_SUMMARY.md** - Résumé des modifications

### Pour les utilisateurs
1. **USER_GUIDE_CC_DISPLAY.md** - Guide d'utilisation complet

### Récapitulatif
1. **IMPLEMENTATION_COMPLETE.md** - Vue d'ensemble complète

---

## 🧪 Tests

Deux fichiers de test sont fournis:

### test_site_coordinates.js
Test simple de la structure des données Site avec les coordonnées

### test_integration_coordinates.js
Test d'intégration couvrant différents scénarios:
- Site avec coordonnées complètes
- Site sans coordonnée Z
- Site sans coordonnées (données vides)

---

## 🚀 Déploiement rapide

### 1. Vérifications
```bash
# Vérifier la syntaxe PHP
php -l api_viewer/appli/Entity/SiteGetDTO.php
php -l api_viewer/appli/Repository/SiteRepository.php
```

### 2. Tests
```bash
# Tester l'API
curl http://localhost/orthophotosValorterreLMS/api_viewer/sites

# Vérifier que la réponse inclut coordinatesCc et coordinatesWgs84
```

### 3. Déploiement
Copier les fichiers modifiés sur le serveur (voir DEPLOYMENT_CHECKLIST.md)

### 4. Validation
Ouvrir la visionneuse, cliquer sur un site, vérifier l'affichage des coordonnées

---

## 📊 Fichiers modifiés

### Backend (2 fichiers)
```
api_viewer/appli/Entity/SiteGetDTO.php
  +6 propriétés/méthodes pour coordonnées

api_viewer/appli/Repository/SiteRepository.php
  +2 requêtes SQL modifiées pour extraire coordonnées
```

### Frontend (5 fichiers)
```
ortho_viewer/js/config/config.js
  +1 constante (API_COORDS)

ortho_viewer/js/Entities/Site.js
  +2 paramètres au constructeur

ortho_viewer/js/services/serviceSites.js
  Modification getAllSites() pour passer coordonnées

ortho_viewer/js/services/serviceHttp.js
  +1 fonction displaySiteCoordinates()
  +3 références DOM

ortho_viewer/index.html
  +1 div avec 2 spans pour affichage
```

---

## ✨ Fonctionnalités

✅ Affichage coordonnées CC44 (X, Y, Z en mètres)
✅ Affichage coordonnées GPS (Lon, Lat en degrés décimaux)
✅ Gestion coordonnée Z manquante (affichage "N/A")
✅ Conversion automatique via PostGIS
✅ Affichage au clic sur site
✅ Masquage par défaut du conteneur
✅ Configuration modifiable (API_COORDS)

---

## 🔧 Configuration

Pour changer le système de coordonnées:

```javascript
// ortho_viewer/js/config/config.js
const API_COORDS = "CC47";  // Changer "CC44" par votre système
```

Pour changer la précision des coordonnées:

```javascript
// ortho_viewer/js/services/serviceHttp.js
// Changer .toFixed(3) par .toFixed(2) pour 2 décimales
asideSiteCoordsCC.innerHTML = `...X :${site.coordinatesCc[0].toFixed(2)} m...`;
```

---

## 🛠️ Dépannage

### Les coordonnées ne s'affichent pas

1. Vérifier que PostGIS est installé:
   ```sql
   SELECT PostGIS_version();
   ```

2. Vérifier que les sites ont une géométrie:
   ```sql
   SELECT * FROM sites WHERE geom IS NOT NULL;
   ```

3. Consulter la console navigateur (F12) pour les erreurs

### Les coordonnées sont imprécises

1. Vérifier le système de coordonnées en base de données
2. Vérifier l'EPSG utilisé dans la transformation PostGIS
3. Vérifier que les géométries sont valides

---

## 📞 Support

Pour plus d'informations:
- Documentation PostGIS: https://postgis.net/
- Documentation Leaflet: https://leafletjs.com/
- Documentation proj4js: https://proj4js.org/

---

## 📝 Notes importantes

- ✅ Aucune migration de base de données requise
- ✅ Rétro-compatible avec le code existant
- ✅ Pas de nouvelles dépendances
- ✅ Performance optimale
- ✅ Gestion complète des erreurs

---

## ✅ Statut

**IMPLÉMENTÉ ET TESTÉ - PRÊT POUR LE DÉPLOIEMENT**

Toutes les vérifications sont passées avec succès.
Consultez DEPLOYMENT_CHECKLIST.md pour le déploiement.

---

**Créé par:** Assistant de développement Copilot
**Date:** 2024
