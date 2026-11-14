class Tampon {
    constructor(id, matricule, siteId, type, reseau,
        description, rotation, amontsIds, avalIds, 
        coordinatesCc, coordinatesWgs84) {
        this.id = id;
        this.matricule = matricule;
        this.siteId = siteId;
        this.type = type;  // 0=autre, 1=circulaire, 2=rectangulaire, 3=carré   
        this.reseau = reseau;              
        this.description = description;
        this.rotation = rotation;
        this.amontsIds = amontsIds;
        this.avalIds = avalIds;
        this.coordinatesCc = coordinatesCc;
        this.coordinatesWgs84 = coordinatesWgs84;
    }

    
    getLeafletLatLngs() {
        return [this.coordinatesWgs84[1], this.coordinatesWgs84[0]]; // [lat, lng]
    }
    
    getCoordinatesCc() {
        return this.coordinatesCc;
    }
   
}