const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

async function testSiteMarkers() {
    const markers = [];
    const context = {
        document: { getElementById: () => ({}) },
        getAllSites: async () => [
            { id: 1, name: '2D', city: 'Test', coordinates: [3, 44] },
            { id: 2, name: '3D', city: 'Test', coordinates: [4, 45, 100] },
            { id: 3, name: 'Sans coordonnées', city: 'Test', coordinates: [] }
        ],
        SITES_IDS: [1, 2, 3],
        L: {
            marker: (coordinates) => {
                markers.push(coordinates);
                return { bindPopup() {}, on() {} };
            }
        },
        sitesLayerGroup: { addLayer() {}, addTo() {} },
        map: {},
        console: { error: (error) => { throw error; } }
    };

    vm.createContext(context);
    vm.runInContext(fs.readFileSync(path.join(__dirname, 'js', 'services', 'serviceHttp.js'), 'utf8'), context);
    await context.initApp();
    assert.deepEqual(markers.map(coords => Array.from(coords)), [[44, 3], [45, 4]]);
}

testSiteMarkers().catch(error => {
    console.error(error);
    process.exitCode = 1;
});
