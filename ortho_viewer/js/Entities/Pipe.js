class Pipe {
    constructor(id, siteId, type, description, diametre, reseau, coordinatesCc, coordinatesWgs84) {
        this.id = id;
        this.siteId = siteId;
        this.type = type;     
        this.description = description;
        this.diametre = diametre;
        this.reseau = reseau;   
        this.coordinatesCc = typeof coordinatesCc === 'string' ? JSON.parse(coordinatesCc) : (coordinatesCc || []);
        this.coordinatesWgs84 = typeof coordinatesWgs84 === 'string' ? JSON.parse(coordinatesWgs84) : (coordinatesWgs84 || []);            
    }

    getLeafletLatLngs() {
        return this.coordinatesWgs84.map(coord => [coord[1], coord[0]]); // [[lat, lng], [lat, lng]]
    }
    getCoordinatesCc() {
        return this.coordinatesCc;
    }
    
}