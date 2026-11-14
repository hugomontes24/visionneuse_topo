# Application de Visualisation Topographique
## Présentation
Cette application web permet la consultation et l'analyse de données topographiques, d'orthophotographies et de réseaux géoréférencés directement sur une carte interactive.
Elle est principalement utilisée pour l'étude, le contrôle et la comparaison temporelle de relevés topographiques sur différents sites.
L'application repose sur :
- Frontend : JavaScript Vanilla
- Cartographie : Leaflet
- Backend : API PHP
- Données : orthophotos, nuages de points, modèles 3D et réseaux géoréférencés
---
# Fonctionnalités principales
## 1. Visualisation des sites
Au chargement de l'application, l'utilisateur visualise une carte Leaflet contenant l'ensemble des sites disponibles.
Chaque site est représenté par un marqueur géographique.
L'utilisateur peut :
- naviguer sur la carte ;
- rechercher un site ;
- sélectionner un site.
---
## 2. Consultation d'un site
Lorsqu'un site est sélectionné :
- la carte effectue un zoom automatique sur l'emprise du site ;
- l'orthophotographie principale est affichée ;
- les couches disponibles sont chargées.
Selon les données disponibles, l'utilisateur peut visualiser :
- l'orthophotographie ;
- les réseaux pluviaux ;
- les réseaux d'eaux usées ;
- d'autres couches SIG associées au projet.
---
## 3. Comparaison Avant / Après
Le mode Avant / Après permet de comparer deux campagnes de prise de vue ou deux états d'un même site.
### Fonctionnement
Lorsque ce mode est activé :
- l'écran est divisé en deux panneaux ;
- une image est affichée à gauche ;
- une image est affichée à droite ;
- les deux cartes restent synchronisées.
### Utilisations
- suivi de chantier ;
- contrôle de travaux ;
- comparaison de différentes campagnes drones ;
- analyse d'évolution d'un site.
---
## 4. Gestion de la transparence
L'utilisateur peut ajuster l'opacité des couches affichées.
Cette fonctionnalité permet :
- de superposer plusieurs orthophotos ;
- de comparer différentes dates ;
- de visualiser simultanément les réseaux et l'orthophoto.
---
## 5. Visualisation 3D
Pour les sites disposant de données tridimensionnelles, l'application permet d'afficher :
- un modèle 3D ;
- un maillage ;
- une scène photogrammétrique.
Cette fonctionnalité facilite :
- l'inspection visuelle ;
- l'analyse volumétrique ;
- la compréhension du terrain.
---
## 6. Consultation du nuage de points
L'application permet également l'ouverture du nuage de points associé à un projet.
Le nuage de points peut provenir :
- d'un levé drone ;
- d'un scanner laser ;
- d'une campagne photogrammétrique.
L'utilisateur peut :
- naviguer dans le nuage ;
- inspecter les détails ;
- effectuer des vérifications visuelles.
---
# Architecture technique
## Frontend
### Technologies
- JavaScript Vanilla
- HTML5
- CSS3
- Leaflet
### Responsabilités
- affichage cartographique ;
- gestion des couches ;
- interactions utilisateur ;
- synchronisation des vues.
---
## Backend
### Technologies
- PHP
- API REST
### Responsabilités
- récupération des données projet ;
- gestion des couches cartographiques ;
- préparation des métadonnées ;
- sécurisation des accès.
---
# Flux utilisateur
1. Ouverture de l'application.
2. Affichage de la carte générale.
3. Sélection d'un site.
4. Zoom automatique sur le site.
5. Chargement des couches disponibles.
6. Consultation des orthophotos.
7. Affichage optionnel :
   - réseaux pluviaux ;
   - réseaux eaux usées ;
   - comparaison avant/après ;
   - modèle 3D ;
   - nuage de points.
---
# Cas d'usage principaux
## Suivi de chantier
Comparer l'état d'un site avant et après travaux.
## Contrôle topographique
Visualiser les orthophotos et les réseaux géoréférencés.
## Analyse de réseaux
Superposer les réseaux humides avec les prises de vue aériennes.
## Exploitation photogrammétrique
Consulter les modèles 3D et les nuages de points associés aux projets.
---
# Évolutions envisagées
- ajout d'informations projet ;
- recherche avancée ;
- filtres par client ;
- filtres par date ;
- gestion des systèmes de coordonnées (CC) ;
- intégration d'outils de mesure ;
- exports PDF ;
- exports Excel ;
- automatisation du pointage des livrables.
---
# Stack Technique
| Composant | Technologie |
|------------|------------|
| Frontend | JavaScript Vanilla |
| Cartographie | Leaflet |
| Backend | PHP |
| API | REST |
| Orthophotos | GeoTIFF / Tuiles raster |
| Réseaux | Données SIG |
| Visualisation 3D | Visionneuse 3D |
| Nuages de points | Visionneuse dédiée |
---
# Objectif Métier
L'application constitue un portail centralisé permettant aux équipes topographiques, SIG et exploitation de :
- localiser un site ;
- consulter les livrables cartographiques ;
- comparer différentes campagnes de relevés ;
- visualiser les réseaux enterrés ;
- accéder aux modèles 3D ;
- consulter les nuages de points ;
- préparer les études et interventions terrain.
