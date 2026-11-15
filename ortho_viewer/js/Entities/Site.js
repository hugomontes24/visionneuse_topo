class Site{
    constructor(id, name, city, img, coordinates, coordinatesCc, coordinatesWgs84) {
        this.id = id;
        this.name = name;     
        this.city = city;
        this.img = img; // URL de l'image                 
        this.coordinates = coordinates; // [  [lng, lat], [lng, lat]  ] 
        this.coordinatesCc = coordinatesCc; // [x, y, z] en CC44
        this.coordinatesWgs84 = coordinatesWgs84; // [lng, lat, z] en WGS84
    }

    
    getLeafletLatLngs() {
        return this.coordinates.map(coord => [coord[1], coord[0]]); // [lat, lng]
    }
    
   
}