# ARCHITECTURE.md
# Visionneuse Topographique
## Présentation
La Visionneuse Topographique est une application web permettant la consultation et l'analyse de données géospatiales.
L'application permet :
- la visualisation de sites sur une carte interactive ;
- l'affichage d'orthophotographies ;
- la consultation des réseaux humides (EP et EU) ;
- la comparaison temporelle de campagnes de prises de vues ;
- la visualisation de modèles 3D ;
- l'accès à des nuages de points ;
- la récupération de coordonnées cartographiques.
---
# Architecture globale
```text
┌───────────────┐
│ Utilisateur   │
└───────┬───────┘
    	│
    	▼
┌────────────────────────────┐
│ Frontend JS Vanilla    	│
│ Leaflet + Three.js     	│
└─────────────┬──────────────┘
          	│ HTTP REST
          	▼
┌────────────────────────────┐
│ API PHP MVC            	│
└─────────────┬──────────────┘
          	│
          	▼
┌────────────────────────────┐
│ Repository / Mapper    	│
└─────────────┬──────────────┘
          	│
          	▼
┌────────────────────────────┐
│ Base de données        	│
└────────────────────────────┘
```
---
# Arborescence du projet
```text
visionneuse_topo
│
├── .github
│	├── INSTRUCTIONS.md
│	├── ARCHITECTURE.md
│	├── CONTEXT.md
│
├── api_viewer
│   ├── config
│   ├── index.php
│   └── appli
│   	├── Controller
│   	├── Entity
│   	├── Repository
│   	├── Mapper
│   	├── Kernel
│   	└── ErrorHandler
│
└── ortho_viewer
	├── css
	├── js
	├── gltfpack
	├── index.html
	└── zzBac
```
---
# Frontend
## Technologies
- JavaScript Vanilla
- HTML5
- CSS3 / SCSS
- Leaflet
- Leaflet Draw
- Leaflet SideBySide
- Proj4JS
- Three.js
---
# Structure Frontend
## Configuration
```text
js/config/
└── config.js
```
Contient :
- URL API ;
- paramètres applicatifs ;
- constantes.
---
## Entités Métier
```text
js/Entities/
```
### Site
Représente un site ou chantier.
### Zone
Zone géographique d'étude.
### Raster
Orthophotographie.
### Photo
Campagne photo ou photo terrain.
### Pipe
Canalisation réseau.
### Tampon
Ouvrage réseau.
### Modele3D
Modèle 3D d'un site.
---
## Services
```text
js/services/
```
Responsables des appels API.
### serviceHttp.js
Couche HTTP commune.
### serviceSites.js
Gestion des sites.
### serviceZones.js
Gestion des zones.
### servicePhotos.js
Gestion des photos.
### serviceRasters.js
Gestion des orthophotos.
### servicePipes.js
Gestion des réseaux.
### serviceTampons.js
Gestion des tampons.
---
## Méthodes cartographiques
```text
js/methods/
```
### initMap.js
Initialisation Leaflet.
### layersManager.js
Gestion centralisée des couches.
### beforeAfter.js
Comparaison Avant / Après.
### pointCoords.js
Récupération des coordonnées.
### utils.js
Fonctions utilitaires.
---
## Modules Vue
```text
js/modules/
```
### views.module.js
Gestion des vues de l'application.
---
## Visualisation
### viewer.js
Gestion générale des viewers.
### viewer3D.js
Visualisation Three.js.
---
# Backend
## Technologies
- PHP 8+
- API REST
- Architecture MVC personnalisée
- PDO
---
# Pattern MVC
Le backend suit le schéma :
```text
Controller
	↓
Repository
	↓
Mapper
	↓
Entity
```
---
# Contrôleurs
```text
Controller/
```
### SiteController
Gestion des sites.
### ZoneController
Gestion des zones.
### RasterController
Gestion des orthophotos.
### PhotoController
Gestion des photos.
### PipeController
Gestion des canalisations.
### TamponController
Gestion des tampons.
---
# Entités Backend
```text
Entity/
```
Entités principales :
- Site
- Zone
- Raster
- Photo
- Pipe
- Tampon
DTO disponibles :
- SiteGetDTO
- ZoneGetDTO
- RasterGetDTO
- PhotoGetDTO
- PhotoPatchDTO
- PipeGetDTO
- TamponGetDTO
---
# Repositories
```text
Repository/
```
Responsables :
- des requêtes SQL ;
- du CRUD ;
- de la récupération des données.
---
# Mappers
```text
Mapper/
```
Conversion :
```text
Base de données
   	↓
 	Mapper
   	↓
 Objet PHP
```
---
# Noyau technique
```text
Kernel/
```
### PDOConnection.php
Gestion de la connexion base de données.
---
# Flux métier principal
## Chargement d'un site
```text
Carte Leaflet
  	│
  	▼
Sélection Site
  	│
  	▼
serviceSites.js
  	│
  	▼
API /sites
  	│
  	▼
SiteController
  	│
  	▼
SiteRepository
  	│
  	▼
Base de données
  	│
  	▼
JSON
  	│
  	▼
Affichage Carte
```
---
# Fonctionnalités métier
## Orthophotos
- consultation raster ;
- historique des campagnes ;
- comparaison temporelle.
## Réseaux
### EP
- canalisations ;
- tampons.
### EU
- canalisations ;
- tampons.
## Avant / Après
- synchronisation ;
- transparence ;
- analyse d'évolution.
## Modèles 3D
- chargement GLB ;
- navigation 3D ;
- visualisation photogrammétrique.
## Nuages de points
- contrôle ;
- inspection ;
- visualisation détaillée.
---
# Évolutions prévues
- gestion des Coniques Conformes (CC) ;
- calcul automatique des systèmes de coordonnées ;
- recherche multicritère ;
- exports PDF ;
- exports Excel ;
- outils de mesure ;
- rapports automatiques ;
- intégration Microsoft Copilot ;
- automatisation du pointage des livrables.

