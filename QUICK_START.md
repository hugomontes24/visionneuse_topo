# 🎯 Quick Start - Affichage CC dans la sidebar

## En 30 secondes

L'implémentation ajoute l'affichage des coordonnées du système CC dans la sidebar latérale lorsqu'un utilisateur sélectionne un site.

### Avant
```
Sidebar affiche:
- Nom du site
- [Tampons du site...]
```

### Après
```
Sidebar affiche:
- Nom du site
- CC44 du site : X: 1700000.123 m, Y: 6200000.456 m, Z: 45.789 m
- GPS du site : Lon: -0.250341, Lat: 47.662704
- [Tampons du site...]
```

---

## 📋 Checklist de vérification

- [ ] Tous les fichiers PHP modifiés (syntaxe vérifiée)
- [ ] Tous les fichiers JavaScript présents
- [ ] Éléments HTML ajoutés (`aside-site-coords`)
- [ ] API retourne `coordinatesCc` et `coordinatesWgs84`
- [ ] Au clic sur site, coordonnées s'affichent dans sidebar

---

## 🚀 Déploiement

1. **Copier les fichiers modifiés** sur le serveur
2. **Tester l'API**: `curl http://localhost/.../api_viewer/sites`
3. **Ouvrir le navigateur** et cliquer sur un site
4. **Vérifier l'affichage** des coordonnées

---

## 📝 Fichiers modifiés

```
Backend:
  • api_viewer/appli/Entity/SiteGetDTO.php
  • api_viewer/appli/Repository/SiteRepository.php

Frontend:
  • ortho_viewer/js/config/config.js
  • ortho_viewer/js/Entities/Site.js
  • ortho_viewer/js/services/serviceSites.js
  • ortho_viewer/js/services/serviceHttp.js
  • ortho_viewer/index.html
```

---

## 🔍 Détails techniques

### Backend (PHP)
- Extraction coordonnées via `ST_X()`, `ST_Y()`, `ST_Z()`
- Conversion en WGS84 via `ST_Transform(geom, 4326)`
- Retour en JSON

### Frontend (JavaScript)
- Récupération coordonnées depuis API
- Affichage au clic sur marqueur de site
- Format CC44: 3 décimales (mètres)
- Format GPS: 6 décimales (degrés)

### HTML
- Div `aside-site-coords` (masqué par défaut)
- Affichage au clic sur site

---

## 📚 Pour plus de détails

- **README_CC_DISPLAY.md** - Guide complet
- **USER_GUIDE_CC_DISPLAY.md** - Utilisation
- **DEPLOYMENT_CHECKLIST.md** - Déploiement
- **IMPLEMENTATION_COMPLETE.md** - Détails techniques

---

## ✅ Vérifications rapides

```javascript
// Vérifier que API_COORDS existe
console.log(API_COORDS);  // Devrait afficher "CC44"

// Vérifier que la classe Site a les propriétés
const site = new Site(1, "Test", "City", null, [0, 0], [1700000, 6200000], [-0.25, 47.66]);
console.log(site.coordinatesCc);  // [1700000, 6200000]
console.log(site.coordinatesWgs84);  // [-0.25, 47.66]
```

```php
// Vérifier la syntaxe PHP
php -l api_viewer/appli/Entity/SiteGetDTO.php
php -l api_viewer/appli/Repository/SiteRepository.php
```

---

## 🎓 Architecture simplifiée

```
DB (PostgreSQL)
  ↓ PostGIS: ST_X(), ST_Y(), ST_Transform()
API (PHP)
  ↓ JSON: coordinatesCc, coordinatesWgs84
Frontend (JavaScript)
  ↓ Site object avec coordonnées
Clic utilisateur
  ↓ displaySiteCoordinates()
Sidebar
  ↓ Affichage CC44 et GPS
```

---

## ⚠️ Important

- Base de données: Géométries en EPSG:3947 (CC47)
- PostGIS: Doit être installé
- API: Retourne les coordonnées
- Frontend: Compatible avec tous les navigateurs modernes

---

## 💾 Commandes utiles

```bash
# Vérifier PostGIS
psql -c "SELECT PostGIS_version();"

# Vérifier les sites avec géométries
psql -c "SELECT id, name, ST_AsText(geom) FROM sites LIMIT 1;"

# Tester l'API
curl http://localhost/orthophotosValorterreLMS/api_viewer/sites

# Vérifier PHP
php -l api_viewer/appli/Entity/SiteGetDTO.php
```

---

**Status: ✅ COMPLÉTÉ ET PRÊT POUR LE DÉPLOIEMENT**
