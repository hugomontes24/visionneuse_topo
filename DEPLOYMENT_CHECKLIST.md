# 📋 Checklist de déploiement - Affichage du système CC

## ✅ Phase 1: Vérification pré-déploiement

### Environnement
- [ ] PHP 7.4+ installé
- [ ] PostgreSQL 10+ avec PostGIS installé
- [ ] Serveur web en cours d'exécution (Apache/Nginx)
- [ ] Navigateur moderne (Chrome 90+, Firefox 88+, Safari 14+)

### Base de données
- [ ] Table `sites` existe et a une colonne `geom` de type geometry
- [ ] Géométries en EPSG:3947 (CC47) pour projection CC44
- [ ] Au moins un site avec géométrie valide

### Code
- [ ] Tous les fichiers PHP modifiés sont syntaxiquement valides
- [ ] Tous les fichiers JavaScript sont présents
- [ ] HTML est bien formé

---

## ✅ Phase 2: Déploiement

### Backend (PHP)
```bash
# 1. Copier les fichiers PHP modifiés
cp api_viewer/appli/Entity/SiteGetDTO.php /chemin/vers/serveur/
cp api_viewer/appli/Repository/SiteRepository.php /chemin/vers/serveur/

# 2. Vérifier les permissions
chmod 644 api_viewer/appli/Entity/SiteGetDTO.php
chmod 644 api_viewer/appli/Repository/SiteRepository.php

# 3. Redémarrer le serveur (si nécessaire)
```

### Frontend (JavaScript/HTML)
```bash
# 1. Copier les fichiers JavaScript modifiés
cp ortho_viewer/js/config/config.js /chemin/vers/serveur/
cp ortho_viewer/js/Entities/Site.js /chemin/vers/serveur/
cp ortho_viewer/js/services/serviceSites.js /chemin/vers/serveur/
cp ortho_viewer/js/services/serviceHttp.js /chemin/vers/serveur/

# 2. Copier le fichier HTML modifié
cp ortho_viewer/index.html /chemin/vers/serveur/

# 3. Vider le cache du navigateur (Ctrl+Shift+Delete ou Cmd+Shift+Delete)
```

---

## ✅ Phase 3: Tests post-déploiement

### Test de l'API
```bash
# Vérifier que l'API retourne les coordonnées
curl "http://localhost/orthophotosValorterreLMS/api_viewer/sites"

# Résultat attendu (JSON):
# {
#   "id": 1,
#   "name": "...",
#   "coordinates": [...],
#   "coordinatesCc": [1700000.123, 6200000.456, 45.789],
#   "coordinatesWgs84": [-0.25, 47.66, 45.789]
# }
```

### Test du Frontend
1. Ouvrir `http://localhost/orthophotosValorterreLMS/ortho_viewer/`
2. Attendre le chargement de la carte
3. Vérifier que les marqueurs de sites apparaissent (zoom < 15)
4. Cliquer sur un marqueur de site
5. **Vérifier que la sidebar affiche:**
   - Nom du site
   - Coordonnées CC44 (X, Y, Z) en mètres
   - Coordonnées GPS (Lon, Lat) en degrés

### Tests supplémentaires
- [ ] Coordonnée Z manquante → Affichage "N/A"
- [ ] Site sans géométrie → Pas d'affichage des coordonnées
- [ ] Zoom avant/arrière → Coordonnées restent cohérentes
- [ ] Navigateur + version → Fonctionne sur tous les navigateurs

---

## ✅ Phase 4: Validation des données

### Format CC44
- [ ] X en mètres (environ 1 700 000)
- [ ] Y en mètres (environ 6 200 000)
- [ ] Z en mètres (altitude)
- [ ] 3 décimales de précision

### Format GPS/WGS84
- [ ] Longitude entre -180 et 180
- [ ] Latitude entre -90 et 90
- [ ] 6 décimales de précision

---

## ✅ Phase 5: Rollback (en cas de problème)

### Restaurer la version précédente
```bash
git revert HEAD  # Annuler le dernier commit
# ou
git checkout HEAD~1 -- <fichier>  # Restaurer un fichier spécifique
```

### Nettoyer le cache
```bash
# Navigateur
Ctrl+Shift+Delete  # Windows/Linux
Cmd+Shift+Delete   # Mac

# Serveur (si cache)
rm -rf /var/cache/nginx/*
```

---

## 📊 Contrôle de qualité

### Performance
- [ ] Temps de chargement page < 3s
- [ ] Temps d'affichage coordonnées < 100ms
- [ ] Pas de fuite mémoire (DevTools → Memory)

### Sécurité
- [ ] Pas d'injection SQL (coordonnées filtrées)
- [ ] Pas d'XSS (données échappées en HTML)
- [ ] HTTPS activé en production

### Compatibilité
- [ ] Chrome 90+ ✓
- [ ] Firefox 88+ ✓
- [ ] Safari 14+ ✓
- [ ] Edge 90+ ✓
- [ ] Mobile (iOS Safari, Chrome Mobile) ✓

---

## 📞 Support

### En cas de problème

**Les coordonnées ne s'affichent pas:**
1. Vérifier que PostGIS est installé: `SELECT PostGIS_version();`
2. Vérifier que les sites ont une géométrie: `SELECT * FROM sites WHERE geom IS NOT NULL;`
3. Consulter les logs serveur: `error.log`, `access.log`

**Erreurs JavaScript:**
1. Ouvrir la console (F12 → Console)
2. Chercher les messages d'erreur
3. Vérifier que `API_COORDS` est bien défini dans config.js

**Erreurs PHP:**
1. Vérifier la syntaxe: `php -l file.php`
2. Consulter les logs PHP
3. Vérifier les permissions des fichiers

---

## ✅ Checklist finale

- [ ] Tous les fichiers déployés
- [ ] Syntaxe vérifiée
- [ ] API testée
- [ ] Frontend testé
- [ ] Données valides
- [ ] Performance OK
- [ ] Compatibilité navigateurs OK
- [ ] Documentation à jour
- [ ] Team notifiée

---

## 🎉 Déploiement terminé!

Vérifiez que tout fonctionne correctement et notifiez l'équipe.

---

**Date de déploiement:** _____________________
**Validé par:** _____________________
**Notes:** ________________________________________________
