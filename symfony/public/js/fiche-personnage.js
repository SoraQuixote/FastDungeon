/* public/js/fiche-personnage.js */
let attaquesData = [];
let statsData = [];
let objetsData = [];
/* ===== BARRE DE VIE ===== */
function updateVieBar() {
    const span = document.getElementById("vieActuelle");
    if(!span) return;
    const actuelle = parseInt(span.textContent);
    const max = parseInt(document.getElementById("vieMax").value) || 1;
    const pct = Math.min(100, (actuelle / max) * 100);
    const fill = document.getElementById("lifeFill");
    if(fill) fill.style.width = pct + "%";
    
    // On met à jour l'input caché pour la sauvegarde
    const input = document.getElementById("vieActuelleInput");
    if(input) input.value = actuelle;
}

function changeVie(val) {
    const span = document.getElementById("vieActuelle");
    const maxInput = document.getElementById("vieMax");
    if(!span || !maxInput) return;

    const max = parseInt(maxInput.value) || 1;
    let actuelle = parseInt(span.textContent);

    actuelle = Math.max(0, Math.min(max, actuelle + val));
    span.textContent = actuelle;
    updateVieBar();
}

/* ===== STATS ===== */
function changeStat(index, val) {
    statsData[index].valeur = (statsData[index].valeur || 0) + val;
    renderStats();
}

/* ===== INITIALISATION & PORTRAIT ===== */
document.addEventListener("DOMContentLoaded", function () {
    const upload = document.getElementById("uploadPortrait");
    if (upload) {
        upload.addEventListener("change", function (e) {
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
    }
    updateVieBar();
});

/* ===== GESTION DES ATTAQUES ===== */


function toggleTypeFields() {
    const type = document.getElementById('atk_type').value;
    const fContre = document.getElementById('field_contre');
    const fMagie = document.getElementById('field_magie');
    
    if(fContre) fContre.style.display = (type === 'physique') ? 'block' : 'none';
    if(fMagie) fMagie.style.display = (type === 'magique') ? 'block' : 'none';
}

function openAttaqueModal(index = null) {
    const modal = document.getElementById('modalAttaque');
    const title = document.getElementById('modalTitle');
    
    if (index !== null) {
        const atk = attaquesData[index];
        title.innerText = "📝 Modifier Capacité";
        document.getElementById('edit_index').value = index;
        document.getElementById('atk_nom').value = atk.nom;
        document.getElementById('atk_type').value = atk.type;
        document.getElementById('atk_degat').value = atk.degat;
        document.getElementById('atk_cout_global').value = atk.cout || 0;
        document.getElementById('atk_portee').value = atk.portee || "";
        document.getElementById('atk_effet').value = atk.effet || "";
        document.getElementById('atk_desc').value = atk.desc || "";
        
        if(atk.type === 'physique') {
            document.getElementById('atk_contre').value = atk.contre || 0;
        } 
    } else {
        title.innerText = "Nouvelle Capacité";
        document.getElementById('edit_index').value = "";
        document.getElementById('atk_nom').value = "";
        document.getElementById('atk_degat').value = 0;
        document.getElementById('atk_cout_global').value = 0;
        document.getElementById('atk_portee').value = "";
        document.getElementById('atk_effet').value = "";
        document.getElementById('atk_desc').value = "";
        document.getElementById('atk_contre').value = 0;
    }
    
    toggleTypeFields();
    modal.style.display = 'block';
}

function closeAttaqueModal() {
    document.getElementById('modalAttaque').style.display = 'none';
}

/* ===== GESTION DES ATTAQUES (CORRIGÉ) ===== */
function supprimerAttaque(index) {
    if(confirm("Supprimer cette capacité ?")) {
        attaquesData.splice(index, 1);
        renderAttaques();
    }
}

// Modifie aussi la fonction sauvegarderAttaque pour gérer correctement les IDs
function sauvegarderAttaque() {
    const nom = document.getElementById('atk_nom').value;
    if (!nom) return alert("Nom requis");

    const type = document.getElementById('atk_type').value;
    
    const atk = {
        nom: nom,
        type: type,
        degat: document.getElementById('atk_degat').value || 0,
        cout: document.getElementById('atk_cout_global').value || 0,
        portee: document.getElementById('atk_portee').value || "",
        effet: document.getElementById('atk_effet').value || "",
        desc: document.getElementById('atk_desc').value || "",
        contre: (type === 'physique') ? document.getElementById('atk_contre').value : 0,
    };

    const index = document.getElementById('edit_index').value;
    if (index !== "") {
        attaquesData[index] = atk;
    } else {
        attaquesData.push(atk);
    }

    renderAttaques();
    closeAttaqueModal();
}

// Ajoute cette fonction pour pouvoir ré-éditer une attaque en cliquant dessus
function renderAttaques() {
    const contPhysique = document.getElementById('liste-physique');
    const contMagique = document.getElementById('liste-magique');
    if(!contPhysique || !contMagique) return;

    contPhysique.innerHTML = '';
    contMagique.innerHTML = '';

    attaquesData.forEach((atk, index) => {
        let html = `
            <div class="attaque-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; background: rgba(255,255,255,0.1); padding: 5px; border-radius: 4px;">
                <span onclick="openAttaqueModal(${index})" style="cursor: pointer; flex-grow: 1;">
                    <strong>${atk.nom}</strong> <small>(Dmg: ${atk.degat})</small>
                </span>
                <input type="hidden" name="personnage[attaques][${index}][type]" value="${atk.type}">
                <input type="hidden" name="personnage[attaques][${index}][nom]" value="${atk.nom}">
                <input type="hidden" name="personnage[attaques][${index}][degat]" value="${atk.degat}">
                <input type="hidden" name="personnage[attaques][${index}][cout]" value="${atk.cout}">
                <input type="hidden" name="personnage[attaques][${index}][portee]" value="${atk.portee}">
                <input type="hidden" name="personnage[attaques][${index}][effet]" value="${atk.effet}">
                <input type="hidden" name="personnage[attaques][${index}][desc]" value="${atk.desc}">
                <input type="hidden" name="personnage[attaques][${index}][contre]" value="${atk.contre || 0}">
                <button type="button" class="btn-stat" onclick="supprimerAttaque(${index})" style="background: #800; border: none; color: white; border-radius: 3px; cursor: pointer;">🗑</button>
            </div>
        `;

        if (atk.type === 'magique') {
            contMagique.insertAdjacentHTML('beforeend', html);
        } else {
            contPhysique.insertAdjacentHTML('beforeend', html);
        }
    });

    if (attaquesData.length === 0) {
        contPhysique.innerHTML = '<p class="empty-msg">Aucune capacité physique</p>';
        contMagique.innerHTML = '<p class="empty-msg">Aucune capacité magique</p>';
    }
}

window.onclick = function(event) {
    const modalAtk = document.getElementById('modalAttaque');
    const modalObj = document.getElementById('modalObjet');
    const modalStat = document.getElementById('modalStat');
    if (event.target === modalAtk) closeAttaqueModal();
    if (event.target === modalObj) closeObjetModal();
    if (event.target === modalStat) closeStatModal();
}
function switchTab(tabId) {
    // Masquer tous les contenus
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });

    // Désactiver tous les boutons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Afficher le bon onglet
    const activeTab = document.getElementById('tab-' + tabId);
    if (activeTab) {
        activeTab.style.display = 'block';
        activeTab.classList.add('active');
    }

    // Activer le bouton (on cherche le bouton qui a l'ID correspondant ou le texte)
    // Plus simple : on gère la classe active via le bouton cliqué si besoin, 
    // mais pour l'instant, assure-toi que l'onglet s'affiche.
}
/* ===== GESTION DES OBJETS ===== */


function openObjetModal(index = null) {
    const modal = document.getElementById('modalObjet');
    const title = document.getElementById('objModalTitle');
    
    if (index !== null) {
        const obj = objetsData[index];
        title.innerText = "📝 Modifier l'Objet";
        document.getElementById('obj_edit_index').value = index;
        document.getElementById('obj_nom').value = obj.nom;
        document.getElementById('obj_degat').value = obj.degat;
        document.getElementById('obj_vie').value = obj.ptsDeVie;
        document.getElementById('obj_effet').value = obj.effet || "";
        document.getElementById('obj_desc').value = obj.desc || "";
    } else {
        title.innerText = "Nouvel Objet";
        document.getElementById('obj_edit_index').value = "";
        document.getElementById('obj_nom').value = "";
        document.getElementById('obj_degat').value = 0;
        document.getElementById('obj_vie').value = 0;
        document.getElementById('obj_effet').value = "";
        document.getElementById('obj_desc').value = "";
    }
    
    modal.style.display = 'block';
}

function closeObjetModal() {
    document.getElementById('modalObjet').style.display = 'none';
}

function sauvegarderObjet() {
    const nom = document.getElementById('obj_nom').value;
    if (!nom) return alert("Le nom de l'objet est requis");

    const obj = {
        nom: nom,
        degat: document.getElementById('obj_degat').value || 0,
        ptsDeVie: document.getElementById('obj_vie').value || 0,
        effet: document.getElementById('obj_effet').value || "",
        desc: document.getElementById('obj_desc').value || ""
    };

    const index = document.getElementById('obj_edit_index').value;
    if (index !== "") {
        objetsData[index] = obj;
    } else {
        objetsData.push(obj);
    }

    renderObjets();
    closeObjetModal();
}

function supprimerObjet(index) {
    if(confirm("Supprimer cet objet de l'inventaire ?")) {
        objetsData.splice(index, 1);
        renderObjets();
    }
}

function renderObjets() {
    const container = document.getElementById('objets-list-container');
    if(!container) return;

    container.innerHTML = '';

    objetsData.forEach((obj, index) => {
        let html = `
            <div class="objet-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; background: rgba(255,255,255,0.1); padding: 8px; border-radius: 4px; border: 1px solid #555;">
                <span onclick="openObjetModal(${index})" style="cursor: pointer; flex-grow: 1;">
                    <strong>${obj.nom}</strong> <small>(Dmg: ${obj.degat}, PV: ${obj.ptsDeVie})</small>
                    <br><i style="font-size: 0.8em; color: #ccc;">${obj.effet}</i>
                </span>
                <input type="hidden" name="personnage[objets][${index}][nom]" value="${obj.nom}">
                <input type="hidden" name="personnage[objets][${index}][ptsDegat]" value="${obj.degat}">
                <input type="hidden" name="personnage[objets][${index}][ptsDeVie]" value="${obj.ptsDeVie}">
                <input type="hidden" name="personnage[objets][${index}][effet]" value="${obj.effet}">
                <input type="hidden" name="personnage[objets][${index}][description]" value="${obj.desc}">
                
                <button type="button" class="btn-stat" onclick="supprimerObjet(${index})" style="background: #800; border: none; color: white; padding: 2px 6px; border-radius: 3px; cursor: pointer;">🗑</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });
}
/* ===== GESTION DES STATS DYNAMIQUES ===== */


function openStatModal(index = null) {
    const modal = document.getElementById('modalStat');
    const title = document.getElementById('statModalTitle');

    if (index !== null) {
        const stat = statsData[index];
        title.innerText = "📝 Modifier la Stat";
        document.getElementById('stat_edit_index').value = index;
        document.getElementById('stat_nom').value = stat.nom;
        document.getElementById('stat_valeur').value = stat.valeur;
    } else {
        title.innerText = "Nouvelle Stat";
        document.getElementById('stat_edit_index').value = "";
        document.getElementById('stat_nom').value = "";
        document.getElementById('stat_valeur').value = 0;
    }

    modal.style.display = 'block';
}

function closeStatModal() {
    document.getElementById('modalStat').style.display = 'none';
}

function sauvegarderStat() {
    const nom = document.getElementById('stat_nom').value.trim();
    if (!nom) return alert("Le nom de la stat est requis");

    const stat = {
        nom: nom,
        valeur: parseInt(document.getElementById('stat_valeur').value) || 0
    };

    const index = document.getElementById('stat_edit_index').value;
    if (index !== "") {
        statsData[parseInt(index)] = stat;
    } else {
        statsData.push(stat);
    }

    renderStats();
    closeStatModal();
}

function supprimerStat(index) {
    if (confirm("Supprimer cette stat ?")) {
        statsData.splice(index, 1);
        renderStats();
    }
}

function renderStats() {
    const container = document.getElementById('liste-stats');
    if (!container) return;

    container.innerHTML = '';

    if (statsData.length === 0) {
        container.innerHTML = '<p class="empty-msg" style="color:#5a3a1b; font-size:0.85rem;">Aucune stat. Cliquez sur + Ajouter.</p>';
        return;
    }

    statsData.forEach((stat, index) => {
        const html = `
            <div class="stat-ligne">
                <span class="stat-nom" onclick="openStatModal(${index})" style="cursor:pointer;" title="Cliquer pour modifier">${stat.nom}</span>
                <button type="button" class="btn-stat" onclick="changeStat(${index}, -1)">−</button>
                <span class="stat-valeur" id="stat_val_${index}">${stat.valeur}</span>
                <button type="button" class="btn-stat" onclick="changeStat(${index}, 1)">+</button>
                <button type="button" class="btn-stat" onclick="supprimerStat(${index})" style="background:#800; color:white;">🗑</button>

                <input type="hidden" name="personnage[stats][${index}][nom]" value="${stat.nom}">
                <input type="hidden" name="personnage[stats][${index}][valeur]" id="stat_input_${index}" value="${stat.valeur}">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });
}