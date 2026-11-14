# ARCHITECTURE.md
# Architecture de l'application de visualisation topographique
## Vue d'ensemble
L'application permet la consultation centralisée de données topographiques et géospatiales à travers une interface cartographique interactive.
L'utilisateur peut :
- visualiser les sites disponibles sur une carte ;
- afficher les orthophotographies associées ;
- consulter les réseaux humides (eaux pluviales et eaux usées) ;
- comparer des campagnes de prises de vue à différentes dates ;
- accéder aux modèles 3D ;
- consulter les nuages de points ;
- récupérer des coordonnées cartographiques.
L'architecture repose sur un frontend JavaScript Vanilla et une API REST PHP.
---
# Architecture générale
```text
┌─────────────────────────────┐
│     	Utilisateur     	│
└──────────────┬──────────────┘
           	│
           	▼
┌─────────────────────────────┐
│ Frontend JavaScript Vanilla │
│      	Leaflet        	│
└──────────────┬──────────────┘
           	│ Requêtes HTTP
           	▼
┌─────────────────────────────┐
│    	API REST PHP     	│
└──────────────┬──────────────┘
           	│
           	▼
┌─────────────────────────────┐
│ Repositories / Mappers  	│
└──────────────┬──────────────┘
           	│
           	▼
┌─────────────────────────────┐
│  	Base de données    	│
└─────────────────────────────┘
```
---
# Frontend
## Technologies
- JavaScript Vanilla
- HTML5
- CSS3
- Leaflet
- Leaflet Draw
- Leaflet Side By Side
- Proj4js
- Three.js
---
# Structure du Frontend
```text
js/
│
├── Entities/
│   ├── Site.js
│   ├── Zone.js
│   ├── ZoneDTO.js
│   ├── Raster.js
│   ├── Pipe.js
│   └── Tampon.js
│
├── services/
│   ├── serviceHttp.js
│   ├── serviceSites.js
│   ├── serviceZones.js
│   ├── serviceRasters.js
│   ├── servicePipes.js
│   └── serviceTampons.js
│
├── methods/
│   ├── initMap.js
│   ├── layersManager.js
│   ├── beforeAfter.js
│   ├── pointCoords.js
│   └── utils.js
│
├── modules/
│   └── views.module.js
│
├── viewer.js
├── custom.js
└── methodsZones.js
```
---
## Rôle des dossiers
### Entities
Contient les objets métier manipulés côté client.
Exemples :
- Site
- Zone
- Raster
- Pipe
- Tampon
### Services
Couche d'accès aux données.
Responsabilités :
- appels API REST ;
- récupération des données ;
- transformation des réponses ;
- gestion des requêtes HTTP.
### Methods
Fonctionnalités cartographiques et outils utilisateur.
Responsabilités :
- initialisation de la carte ;
- gestion des couches ;
- comparaison avant/après ;
- coordonnées ;
- outils utilitaires.
### Modules
Composants fonctionnels réutilisables.
---
# Interface Utilisateur
## Carte principale
La carte Leaflet constitue le cœur de l'application.
Fonctions disponibles :
- navigation ;
- zoom ;
- sélection des sites ;
- affichage des couches ;
- consultation des informations projet.
---
## Contrôles cartographiques
### Sélecteur 2D / 3D
Permet de basculer entre :
- la cartographie 2D ;
- la visualisation 3D.
### Nuage de points
Permet d'ouvrir la visionneuse du nuage de points associé au site sélectionné.
### Comparateur Avant / Après
Permet d'afficher deux orthophotographies simultanément.
Fonctionnalités :
- synchronisation des vues ;
- comparaison temporelle ;
- ajustement de la transparence.
### Coordonnées
Permet de récupérer les coordonnées d'un point sélectionné sur la carte.
### Dessin
Utilise Leaflet Draw pour :
- dessiner des polygones ;
- ajouter des marqueurs ;
- modifier des zones.
---
# Sidebar
La barre latérale affiche :
- nom du site ;
- description ;
- coordonnées ;
- informations complémentaires ;
- historique des campagnes photos.
---
# Gestion des couches
L'application manipule plusieurs types de couches :
## Orthophotos
- affichage raster ;
- historique par date ;
- comparaison temporelle.
## Réseaux EP
Réseaux d'eaux pluviales :
- canalisations ;
- tampons.
## Réseaux EU
Réseaux d'eaux usées :
- canalisations ;
- tampons.
## Zones
- emprises ;
- secteurs ;
- limites d'étude.
---
# Visualisation 3D
Basée sur Three.js.
Fonctionnalités :
- chargement d'un modèle 3D ;
- affichage plein écran ;
- optimisation du rendu ;
- gestion du cycle d'animation.
Flux :
```text
Utilisateur
  	│
  	▼
Bouton 3D
  	│
  	▼
viewer.js
  	│
  	▼
viewer3D.js
  	│
  	▼
Three.js
  	│
  	▼
Modèle .glb
```
---
# Backend
## Technologies
- PHP
- API REST
- Architecture MVC personnalisée
---
# Structure Backend
```text
appli/
│
├── Controller/
├── Repository/
├── Entity/
├── Mapper/
├── Kernel/
├── ErrorHandler/
└── config/
```
---
# Point d'entrée
```text
index.php
```
Responsabilités :
- chargement automatique des classes ;
- routage des requêtes ;
- contrôle CORS ;
- instanciation des contrôleurs ;
- dispatch REST.
---
# Routage dynamique
Le système détermine automatiquement :
- le Repository ;
- le Mapper ;
- le Controller ;
à partir de l'URL appelée.
Exemple :
```text
/api/sites
    	│
    	▼
SiteController
SiteRepository
SiteMapper
```
ou
```text
/api/zones
    	│
    	▼
ZoneController
ZoneRepository
ZoneMapper
```
---
# Contrôleurs
Responsabilités :
- réception des requêtes ;
- validation des paramètres ;
- orchestration métier ;
- préparation des réponses JSON.
Exemples :
- SiteController
- ZoneController
- RasterController
- PipeController
---
# Repositories
Responsables de :
- l'accès aux données ;
- l'exécution des requêtes SQL ;
- la persistance.
Exemples :
- SiteRepository
- ZoneRepository
- RasterRepository
- PipeRepository
---
# Mappers
Assurent la conversion :
```text
Base de données
    	│
    	▼
 	Mapper
    	│
    	▼
 Objet métier
```
Exemples :
- SiteMapper
- ZoneMapper
- RasterMapper
---
# Entités Métier
## Site
Représente un site ou un chantier.
Informations :
- identifiant ;
- nom ;
- localisation ;
- description.
## Zone
Représente une zone géographique liée au site.
## Raster
Orthophotographie ou couche raster.
## Pipe
Canalisation d'un réseau.
Types :
- eaux pluviales ;
- eaux usées.
## Tampon
Ouvrage associé aux réseaux.
---
# Flux de données
## Chargement d'un site
```text
Carte Leaflet
  	│
  	▼
Sélection du site
  	│
  	▼
serviceSites.js
  	│
  	▼
API PHP
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
Réponse JSON
  	│
  	▼
Affichage dans la carte
```
---
# Fonctionnalités Métier
## Consultation des orthophotos
- affichage des campagnes ;
- navigation temporelle ;
- comparaison des dates.
## Consultation des réseaux
- affichage EP ;
- affichage EU ;
- superposition avec l'orthophoto.
## Contrôle topographique
- consultation des coordonnées ;
- visualisation des emprises ;
- analyse spatiale.
## Analyse temporelle
- mode Avant / Après ;
- transparence ;
- comparaison des états.
## Exploitation 3D
- modèle 3D ;
- inspection visuelle ;
- compréhension du terrain.
## Nuages de points
- accès aux relevés ;
- contrôle de détail ;
- visualisation photogrammétrique.
---
# Évolutions prévues
- intégration des systèmes de coordonnées CC ;
- recherche multicritère ;
- filtrage des projets ;
- exports PDF ;
- exports Excel ;
- outils de mesure ;
- génération de rapports ;
- intégration d'agents Copilot ;
- automatisation du pointage des livrables.

