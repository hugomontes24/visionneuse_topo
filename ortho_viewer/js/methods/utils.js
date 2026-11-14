
/**
 * Transforme un texte selon un mode donné
 * @param {*} texte 
 * @param {*} mode 
 * @returns 
 */
function transformerTexte(texte, mode) {
    switch (mode) {

        case "uppercase":
            return texte.toUpperCase();

        case "lowercase":
            return texte.toLowerCase();

        case "capitalize":
            return texte
                .toLowerCase()
                .replace(/\b\w/g, lettre => lettre.toUpperCase());

        case "phrase": // Met en majuscule la première lettre de la phrase
            texte = texte.toLowerCase();
            return texte.charAt(0).toUpperCase() + texte.slice(1);

        default:
            return texte;
    }
}

function toggleActiveBtn(activeBtn) {  // highlight the active button
    activeBtn.classList.toggle("active");
}

function helloWorld() {
    console.log("Hello World !!!");
}

function toggleNone(element) {
    element.classList.toggle("none");
}

