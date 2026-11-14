import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { KTX2Loader } from 'three/addons/loaders/KTX2Loader.js';
import { MeshoptDecoder } from 'three/addons/libs/meshopt_decoder.module.js';

// Variables de module pour conserver l'état de la scène
let scene, camera, renderer, controls;
let animationFrameId = null;
let isInitialized = false;
let isLoaded = false;

const container = document.getElementById('container3d');
// Éléments du loader DOM
const loaderUI = document.getElementById('loader-3d');
const loaderText = document.getElementById('loader-text');
const progressBar = document.getElementById('progress-bar');

export function init3DViewer(modelPath) {
  if (!container) {
    console.error("Conteneur #container3d introuvable.");
    return;
  }

  // 1. Scène, Caméra, Rendu
  scene = new THREE.Scene();
  scene.background = new THREE.Color(0x1a1a1a);

  camera = new THREE.PerspectiveCamera(60, container.clientWidth / container.clientHeight, 0.1, 10000);
  camera.position.set(0, 50, 100);

  renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize(container.clientWidth, container.clientHeight);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.toneMapping = THREE.ACESFilmicToneMapping;

  // container.innerHTML = '';   correctif pour loader 3D qui ne s'affiche pas correctement après un changement de modèle
  container.appendChild(renderer.domElement);

  // 2. Contrôles
  controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;

  // 3. Lumières
  scene.add(new THREE.AmbientLight(0xffffff, 1.5));
  const dirLight = new THREE.DirectionalLight(0xffffff, 2);
  dirLight.position.set(100, 200, 100);
  scene.add(dirLight);

  // 4. Décodeurs gltfpack
  const loader = new GLTFLoader();
  loader.setMeshoptDecoder(MeshoptDecoder);

  const ktx2Loader = new KTX2Loader()
    .setTranscoderPath('https://unpkg.com/three@0.160.0/examples/jsm/libs/basis/')
    .detectSupport(renderer);
  loader.setKTX2Loader(ktx2Loader);

  // 5. Chargement GLB
  // console.log(`Début du chargement : ${modelPath}`);
  loader.load(
    modelPath,
    (gltf) => {
      // console.log('Modèle 3D chargé avec succès !');
      scene.add(gltf.scene);
      // Cadrage automatique
      const box = new THREE.Box3().setFromObject(gltf.scene);
      const center = box.getCenter(new THREE.Vector3());
      const size = box.getSize(new THREE.Vector3());
      const maxDim = Math.max(size.x, size.y, size.z);

      camera.position.set(center.x, center.y + maxDim, center.z + maxDim * 1.5);
      controls.target.copy(center);
      controls.update();

      isLoaded = true;
      startAnimation();
        // Cacher le loader une fois prêt
        if (loaderUI) {
            loaderUI.style.display = 'none';
        }
    },
    (xhr) => {
      if (xhr.lengthComputable) {
        const percent = Math.round((xhr.loaded / xhr.total) * 100);
          const loadedMB = (xhr.loaded / (1024 * 1024)).toFixed(1);
          const totalMB = (xhr.total / (1024 * 1024)).toFixed(1);

          // Mise à jour du texte et de la barre
          if (loaderText) {
              loaderText.innerText = `Chargement 3D : ${percent}% (${loadedMB} / ${totalMB} Mo)`;
          }
          if (progressBar) {
              progressBar.style.width = `${percent}%`;
          }
      }
    },
    (error) => {
      console.error('Erreur chargement 3D :', error);
      if (loaderText) {
            loaderText.innerText = "Erreur lors du chargement de la 3D.";
      }
    }
  );

  isInitialized = true;

  // Redimensionnement
  window.addEventListener('resize', handleResize);
}

function handleResize() {
  if (!container.clientWidth || !container.clientHeight || !renderer) return;
  camera.aspect = container.clientWidth / container.clientHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(container.clientWidth, container.clientHeight);
}

export function startAnimation() {
  if (!animationFrameId && renderer) {
    const animate = () => {
      animationFrameId = requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    };
    animate();
  }
}

export function stopAnimation() {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId);
    animationFrameId = null;
  }
}

export function update3DLayout() {
  handleResize();
}

export function get3DState() {
  return { isInitialized, isLoaded };
}