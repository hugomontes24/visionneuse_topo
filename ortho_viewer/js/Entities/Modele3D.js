class Modele3D {
    constructor(id, name, url, options = {}) {
        this.id = id;
        this.name = name;
        this.url = url; // Chemin ou URL du fichier .glb/.gltf
        
        // Métadonnées métier
        this.description = options.description || '';
        
        // Positionnement spatial (PostGIS)
        this.coordinates = options.coordinates || [0, 0]; // [lng, lat]
        this.altitude = options.altitude || 0;            // Utile pour caler la 3D par rapport au sol

        // Transformations 3D pour Three.js (si besoin de caler le modèle dans la scène)
        this.scale = options.scale || [1, 1, 1];          // Échelle [x, y, z]
        this.rotation = options.rotation || [0, 0, 0];    // Rotation en radians [x, y, z]
    }

    // Adapté pour la pastille/marqueur représentatif sur la carte 2D Leaflet
    // getStyle() {
    //     let fillColorPoint = 'rgba(70, 130, 180, 1.0)'; // SteelBlue par défaut pour différencier des photos

    //     if (this.etat === 1) {
    //         fillColorPoint = 'rgba(218, 165, 32, 1.0)'; // Jaune / Orange
    //     } else if (this.etat === 2) {
    //         fillColorPoint = 'rgba(178, 34, 34, 1.0)';  // Rouge
    //     }

    //     return {
    //         radius: 9.0, // Un peu plus grand que les photos pour la hiérarchie visuelle
    //         fillColor: fillColorPoint,
    //         color: '#ffffff',
    //         weight: 2,
    //         opacity: 1,
    //         fillOpacity: 0.85
    //     };
    // }

    // Méthodes utilitaires géographiques (inversion Leaflet [lat, lng])
    getLatLng() {
        return [this.coordinates[1], this.coordinates[0]];
    }

    getLat() {
        return this.coordinates[1];
    }

    getLng() {
        return this.coordinates[0];
    }

    // getStatusColor() {
    //     const colors = { '-1': 'gray', '0': 'green', '1': 'orange', '2': 'red' };
    //     return colors[this.etat] || 'black';
    // }
}