import { init3DViewer, startAnimation, stopAnimation, update3DLayout, get3DState } from '../viewer3d.js';

const modelPath = './assets/models_3D/site_3d.glb';

        window.view3D = function() {
            btn2D.classList.remove("active");
            btn3D.classList.add("active");

            container3d.classList.add("visible");
            container3d.classList.remove("hidden");

            const state = get3DState();

            // Premier clic : initialisation et chargement
            if (!state.isInitialized) {
                init3DViewer(modelPath);
            } else {
            // Clics suivants : maj taille canvas et reprise de la boucle
                update3DLayout();
                if (state.isLoaded) {
                    startAnimation();
                }
            }
        };

        window.view2D = function() {
            btn2D.classList.add("active");
            btn3D.classList.remove("active");

            container3d.classList.remove("visible");
            container3d.classList.add("hidden");

            // Stoppe le rendu GPU en fond
            stopAnimation();
        };