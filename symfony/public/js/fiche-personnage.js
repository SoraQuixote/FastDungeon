/* public/js/fiche-personnage.js */

/* ===== BARRE DE VIE ===== */
function changeVie(val) {
    const span = document.getElementById("vieActuelle");
    const max = parseInt(document.getElementById("vieMax").value) || 1;
    let actuelle = parseInt(span.textContent);

    actuelle = Math.max(0, Math.min(max, actuelle + val));
    span.textContent = actuelle;
    document.getElementById("vieActuelleInput").value = actuelle;
    updateVieBar();
}

function updateVieBar() {
    const actuelle = parseInt(document.getElementById("vieActuelle").textContent);
    const max = parseInt(document.getElementById("vieMax").value) || 1;
    const pct = Math.min(100, (actuelle / max) * 100);
    document.getElementById("lifeFill").style.width = pct + "%";
    document.getElementById("vieActuelleInput").value = actuelle;
}

/* ===== STATS ===== */
function changeStat(stat, val) {
    const span = document.getElementById(stat);
    let current = parseInt(span.textContent);
    current = Math.max(-999, current + val);
    span.textContent = current;
    document.getElementById(stat + "_input").value = current;
}

/* ===== PORTRAIT ===== */
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("uploadPortrait").addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (evt) {
            const preview = document.getElementById("portraitPreview");
            const placeholder = document.getElementById("portraitPlaceholder");
            preview.src = evt.target.result;
            preview.style.display = "block";
            placeholder.style.display = "none";
        };
        reader.readAsDataURL(file);
    });

    // Init barre de vie au chargement
    updateVieBar();
});
function toggleAttaqueModal() {
    const modal = document.getElementById('modalAttaque');
    modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
}

function ajouterAttaque() {
    // Récupération des valeurs
    const nom = document.getElementById('atk_nom').value;
    const degat = document.getElementById('atk_degat').value;
    const portee = document.getElementById('atk_portee').value;
    const effet = document.getElementById('atk_effet').value;

    if (nom === "") return alert("La capacité doit avoir un nom !");

    const container = document.getElementById('liste-attaques');
    
    // Supprimer le message "vide"
    if (container.querySelector('.empty-msg')) {
        container.innerHTML = '';
    }

    // Création de l'élément visuel
    const div = document.createElement('div');
    div.className = 'attaque-card';
    div.innerHTML = `
        <strong>${nom}</strong> - ⚔️ ${degat} (Portée: ${portee})<br>
        <small>${effet}</small>
        <!-- Champs cachés pour envoyer les données au serveur -->
        <input type="hidden" name="personnage[attaques_nouveau][][nom]" value="${nom}">
        <input type="hidden" name="personnage[attaques_nouveau][][ptsDegat]" value="${degat}">
        <input type="hidden" name="personnage[attaques_nouveau][][portee]" value="${portee}">
        <input type="hidden" name="personnage[attaques_nouveau][][effet]" value="${effet}">
    `;

    container.appendChild(div);

    // Réinitialisation et fermeture
    document.getElementById('atk_nom').value = "";
    document.getElementById('atk_degat').value = "";
    document.getElementById('atk_effet').value = "";
    toggleAttaqueModal();
}
// Tableau pour stocker les attaques temporairement avant la sauvegarde finale du perso
let attaquesData = [];

/**
 * Gère l'affichage des champs selon le type d'attaque (Physique ou Magique)
 */
function toggleTypeFields() {
    const type = document.getElementById('atk_type').value;
    const fieldContre = document.getElementById('field_contre');
    const fieldMagie = document.getElementById('field_magie');

    if (type === 'physique') {
        fieldContre.style.display = 'block';
        fieldMagie.style.display = 'none';
    } else {
        fieldContre.style.display = 'none';
        fieldMagie.style.display = 'block';
    }
}

/**
 * Ouvre la modale (neuve ou pour modification)
 */
function openAttaqueModal(index = null) {
    const modal = document.getElementById('modalAttaque');
    const title = document.getElementById('modalTitle');
    
    if (index !== null) {
        // Mode Modification
        const atk = attaquesData[index];
        title.innerText = "📝 Modifier Capacité";
        document.getElementById('edit_index').value = index;
        document.getElementById('atk_nom').value = atk.nom;
        document.getElementById('atk_type').value = atk.type;
        document.getElementById('atk_degat').value = atk.degat;
        document.getElementById('atk_contre').value = atk.contre || 0;
        document.getElementById('atk_cout').value = atk.cout || 0;
        document.getElementById('atk_desc').value = atk.desc;
    } else {
        // Mode Création (Reset du formulaire)
        title.innerText = "✨ Nouvelle Capacité";
        document.getElementById('edit_index').value = "";
        document.getElementById('atk_nom').value = "";
        document.getElementById('atk_degat').value = "";
        document.getElementById('atk_desc').value = "";
    }
    
    toggleTypeFields();
    modal.style.display = 'block';
}

function closeAttaqueModal() {
    document.getElementById('modalAttaque').style.display = 'none';
}

/**
 * Action du bouton "Enregistrer" dans la modale
 */
function sauvegarderAttaque() {
    const nom = document.getElementById('atk_nom').value;
    if (!nom) {
        alert("Veuillez donner un nom à la capacité.");
        return;
    }

    const atk = {
        nom: nom,
        type: document.getElementById('atk_type').value,
        degat: document.getElementById('atk_degat').value,
        contre: document.getElementById('atk_contre').value,
        cout: document.getElementById('atk_cout').value,
        desc: document.getElementById('atk_desc').value
    };

    const index = document.getElementById('edit_index').value;

    if (index !== "") {
        // Mise à jour d'une attaque existante
        attaquesData[index] = atk;
    } else {
        // Ajout d'une nouvelle attaque
        attaquesData.push(atk);
    }

    renderAttaques();
    closeAttaqueModal();
}

/**
 * Met à jour l'affichage des listes (Physique et Magique)
 */
function renderAttaques() {
    const listPhysique = document.getElementById('liste-physique');
    const listMagique = document.getElementById('liste-magique');
    
    // On vide les conteneurs
    listPhysique.innerHTML = "";
    listMagique.innerHTML = "";

    if (attaquesData.length === 0) {
        listPhysique.innerHTML = '<p class="empty-msg">Aucune attaque physique.</p>';
        listMagique.innerHTML = '<p class="empty-msg">Aucun sort magique.</p>';
        return;
    }

    attaquesData.forEach((atk, index) => {
        // On crée le bloc blanc cliquable
        const div = document.createElement('div');
        div.className = 'attaque-card-blanche';
        div.title = "Cliquez pour modifier";
        div.onclick = () => openAttaqueModal(index);
        
        div.innerHTML = `
            <div class="atk-info">
                <strong>${atk.nom}</strong>
                <span class="atk-degat">⚔️ ${atk.degat || 0}</span>
            </div>
            <input type="hidden" name="personnage[attaques][${index}][nom]" value="${atk.nom}">
            <input type="hidden" name="personnage[attaques][${index}][type]" value="${atk.type}">
            <input type="hidden" name="personnage[attaques][${index}][ptsDegat]" value="${atk.degat}">
            <input type="hidden" name="personnage[attaques][${index}][description]" value="${atk.desc}">
            <!-- Les champs spécifiques selon le type -->
            ${atk.type === 'physique' 
                ? `<input type="hidden" name="personnage[attaques][${index}][degatDeContre]" value="${atk.contre}">`
                : `<input type="hidden" name="personnage[attaques][${index}][ptsDeVie]" value="${atk.cout}">`
            }
        `;

        if (atk.type === 'physique') {
            listPhysique.appendChild(div);
        } else {
            listMagique.appendChild(div);
        }
    });
}

// Pour fermer la modale si on clique à côté
window.onclick = function(event) {
    const modal = document.getElementById('modalAttaque');
    if (event.target == modal) {
        closeAttaqueModal();
    }
}