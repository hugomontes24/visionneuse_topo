// Test unitaire pour vérifier la structure des données Site

// Simuler une réponse API
const mockApiResponse = {
    id: 1,
    name: "Site de Test",
    city: "Rognac",
    img: "path/to/image.jpg",
    coordinates: [-0.250341, 47.662704],
    coordinatesCc: [1700000.123, 6200000.456, 45.789],
    coordinatesWgs84: [-0.250341, 47.662704, 45.789]
};

// Créer une instance Site avec les données
const testSite = new Site(
    mockApiResponse.id,
    mockApiResponse.name,
    mockApiResponse.city,
    mockApiResponse.img,
    mockApiResponse.coordinates,
    mockApiResponse.coordinatesCc,
    mockApiResponse.coordinatesWgs84
);

// Tests
console.log("✓ Site créé avec succès");
console.log("  ID:", testSite.id);
console.log("  Nom:", testSite.name);
console.log("  Ville:", testSite.city);
console.log("  Coordonnées:", testSite.coordinates);
console.log("  CC44 (X, Y, Z):", testSite.coordinatesCc);
console.log("  WGS84 (Lon, Lat, Z):", testSite.coordinatesWgs84);

// Test affichage des coordonnées
const displayed = {
    cc44: `CC44 du site : X: ${testSite.coordinatesCc[0].toFixed(3)} m, Y: ${testSite.coordinatesCc[1].toFixed(3)} m, Z: ${testSite.coordinatesCc[2] ? testSite.coordinatesCc[2].toFixed(3) : 'N/A'} m`,
    gps: `GPS du site : Lon: ${testSite.coordinatesWgs84[0].toFixed(6)}, Lat: ${testSite.coordinatesWgs84[1].toFixed(6)}`
};

console.log("\n✓ Affichage formaté:");
console.log("  " + displayed.cc44);
console.log("  " + displayed.gps);

// Test avec coordonnées manquantes
const sitePartial = new Site(
    2,
    "Site Partiel",
    "Marseille",
    null,
    [-0.250341, 47.662704],
    [],
    []
);

console.log("\n✓ Site avec coordonnées manquantes:");
console.log("  CC44:", sitePartial.coordinatesCc.length === 0 ? "Vide" : sitePartial.coordinatesCc);
console.log("  WGS84:", sitePartial.coordinatesWgs84.length === 0 ? "Vide" : sitePartial.coordinatesWgs84);
