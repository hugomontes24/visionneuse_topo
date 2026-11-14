class Site{
    constructor(id, name, city, img, coordinates) {
        this.id = id;
        this.name = name;     
        this.city = city;
        this.img = img; // URL de l'image                 
        this.coordinates = coordinates; // [  [lng, lat], [lng, lat]  ] 
    }

    
    getLeafletLatLngs() {
        return this.coordinates.map(coord => [coord[1], coord[0]]); // [lat, lng]
    }
    
   
}