// ============================================================
// IIFE 1 — FORMULAIRE SCENE (création / modification)
// ============================================================
(function() {
    const musicienContainer = document.getElementById('musiciens-container');
    if (!musicienContainer) return;

    const instrumentsData = document.getElementById('instruments-data');
    const instruments = JSON.parse(instrumentsData.dataset.instruments);

    let count = 0;

    function addMusicien() {
        const div = document.createElement('div');
        div.className = 'musicien-row border rounded p-3 mb-3';
        div.setAttribute('data-index', count);
        div.innerHTML = `
            <div class="d-flex gap-2 align-items-center mb-2">
                <input
                    type="text"
                    name="musiciens[${count}][nom]"
                    placeholder="Nom du musicien"
                    class="form-control"
                    required
                >
                <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="this.closest('.musicien-row').remove()">
                    ✕
                </button>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center chips-container mb-2"
                 data-index="${count}"></div>

            <div class="position-relative">
                <input
                    type="text"
                    class="form-control form-control-sm instrument-search"
                    placeholder="Rechercher un instrument..."
                    oninput="filtrerInstruments(this, ${count})"
                    onfocus="filtrerInstruments(this, ${count})"
                    autocomplete="off"
                >
                <div class="dropdown-instruments" id="dropdown-${count}" style="display:none;"></div>
            </div>
        `;

        musicienContainer.appendChild(div);
        count++;
    }

    window.filtrerInstruments = function(input, index) {
        const recherche = input.value.toLowerCase();
        const dropdown = document.getElementById(`dropdown-${index}`);
        const resultats = instruments.filter(i => i.libelle.toLowerCase().includes(recherche));

        if (resultats.length === 0) {
            dropdown.style.display = 'none';
            return;
        }

        dropdown.innerHTML = '';
        resultats.forEach(i => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = i.libelle;
            item.onclick = () => ajouterInstrument(index, i.id, i.libelle, input, dropdown);
            dropdown.appendChild(item);
        });

        dropdown.style.display = 'block';
    }

    window.ajouterInstrument = function(index, id, libelle, input, dropdown) {
        const chipsContainer = document.querySelector(`.chips-container[data-index="${index}"]`);

        const dejaAjoute = document.querySelector(
            `input[type="hidden"][data-id="${id}"][data-index="${index}"]`
        );
        if (dejaAjoute) {
            dropdown.style.display = 'none';
            input.value = '';
            return;
        }

        const chip = document.createElement('div');
        chip.className = 'instrument-chip';
        chip.innerHTML = `
            ${libelle}
            <button type="button" onclick="retirerInstrument(this, '${id}', '${index}')">✕</button>
        `;
        chipsContainer.appendChild(chip);

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `musiciens[${index}][instrument_id][]`;
        hiddenInput.value = id;
        hiddenInput.dataset.id = id;
        hiddenInput.dataset.index = index;
        chipsContainer.appendChild(hiddenInput);

        dropdown.style.display = 'none';
        input.value = '';
    }

    window.retirerInstrument = function(bouton, id, index) {
        bouton.closest('.instrument-chip').remove();

        const input = document.querySelector(
            `input[type="hidden"][data-id="${id}"][data-index="${index}"]`
        );
        if (input) input.remove();
    }

    document.addEventListener('click', (e) => {
        if (!e.target.classList.contains('instrument-search')) {
            document.querySelectorAll('.dropdown-instruments').forEach(d => {
                d.style.display = 'none';
            });
        }
    });

    document.getElementById('btn-add-musicien').addEventListener('click', addMusicien);

    addMusicien();
})();


// ============================================================
// IIFE 2 — PLAN DE SCENE (drag & drop + récap + config batterie + suggestions)
// ============================================================
(function() {
    const sceneContainer = document.getElementById('scene-container');
    if (!sceneContainer) return;

    let elementGlisse = null;
    let clone = null;
    let offsetX = 0;
    let offsetY = 0;

    function brangherEvenements(item) {
        item.addEventListener('mousedown', (e) => {
            e.preventDefault();

            elementGlisse = item;

            const rect = item.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;

            clone = item.cloneNode(true);
            clone.style.position = 'absolute';
            clone.style.pointerEvents = 'none';
            clone.style.opacity = '0.7';
            clone.style.zIndex = '9999';
            clone.style.left = (e.pageX - offsetX) + 'px';
            clone.style.top = (e.pageY - offsetY) + 'px';
            document.body.appendChild(clone);
        });
    }

    document.querySelectorAll('.instrument-item').forEach(item => brangherEvenements(item));
    document.querySelectorAll('.instrument-placed').forEach(item => brangherEvenements(item));

    // Au chargement : masquer dans la sidebar les éléments déjà posés
    document.querySelectorAll('.instrument-placed').forEach(item => {
        const id = item.dataset.id;
        const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
        if (sidebarItem) sidebarItem.style.display = 'none';
    });

    // Au chargement : afficher le panneau batterie si une batterie est déjà posée
    document.querySelectorAll('.instrument-placed').forEach(el => {
        if (el.dataset.instrument === 'Batterie') {
            afficherPanneauBatterie(el.dataset.id);
        }
    });

    // Au chargement : calculer le récap initial
    mettreAJourRecap();

    document.addEventListener('mousemove', (e) => {
        if (!clone) return;
        clone.style.left = (e.pageX - offsetX) + 'px';
        clone.style.top = (e.pageY - offsetY) + 'px';
    });

    document.addEventListener('mouseup', (e) => {
        if (!clone || !elementGlisse) return;

        clone.remove();
        clone = null;

        const rect = sceneContainer.getBoundingClientRect();
        if (
            e.clientX < rect.left ||
            e.clientX > rect.right ||
            e.clientY < rect.top ||
            e.clientY > rect.bottom
        ) {
            elementGlisse = null;
            return;
        }

        const posX = e.clientX - rect.left - offsetX;
        const posY = e.clientY - rect.top - offsetY;

        sauvegarderPosition(elementGlisse, posX, posY);
        elementGlisse = null;
    });

    function sauvegarderPosition(element, posX, posY) {
        const type = element.dataset.type;
        const id   = element.dataset.id;

        if (type === 'equipement' && id.startsWith('equipement-')) {
            const sceneId      = sceneContainer.dataset.sceneId;
            const instrumentId = id.replace('equipement-', '');

            fetch(`/scene/${sceneId}/equipement/${instrumentId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ positionX: posX, positionY: posY }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) afficherElementSurScene(data.id, posX, posY, element);
            });

        } else {
            fetch(`/scene/element/${id}/position`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ positionX: posX, positionY: posY }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) afficherElementSurScene(id, posX, posY, element);
            });
        }
    }

    function afficherElementSurScene(id, posX, posY, source) {
        let existant = sceneContainer.querySelector(`.instrument-placed[data-id="${id}"]`);

        if (existant) {
            existant.style.left = posX + 'px';
            existant.style.top  = posY + 'px';
            const img = existant.querySelector('img');
            if (img && source) {
                img.style.width  = source.dataset.largeur + 'px';
                img.style.height = source.dataset.hauteur + 'px';
            }
        } else {
            const div = document.createElement('div');
            div.className          = 'instrument-placed position-absolute';
            div.dataset.id         = id;
            div.dataset.nom        = source.dataset.nom;
            div.dataset.url        = source.dataset.url;
            div.dataset.largeur    = source.dataset.largeur;
            div.dataset.hauteur    = source.dataset.hauteur;
            div.dataset.type       = source.dataset.type;
            div.dataset.instrument = source.dataset.instrument;
            div.style.left = posX + 'px';
            div.style.top  = posY + 'px';
            div.innerHTML = `
                <img
                    src="${source.dataset.url}"
                    alt="${source.dataset.instrument}"
                    style="width: ${source.dataset.largeur}px;
                           height: ${source.dataset.hauteur}px;
                           object-fit: contain;"
                >
            `;
            sceneContainer.appendChild(div);
            brangherEvenements(div);
        }

        const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
        if (sidebarItem) sidebarItem.style.display = 'none';

        mettreAJourRecap();

        if (source.dataset.instrument === 'Batterie') {
            afficherPanneauBatterie(id);
        }
    }

    sceneContainer.addEventListener('dblclick', (e) => {
        const elementPose = e.target.closest('.instrument-placed');
        if (!elementPose) return;

        const id = elementPose.dataset.id;

        fetch(`/scene/element/${id}/position`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ positionX: 0, positionY: 0 }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                elementPose.remove();

                const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
                if (sidebarItem) sidebarItem.style.display = 'block';

                mettreAJourRecap();

                if (elementPose.dataset.instrument === 'Batterie') {
                    cacherPanneauBatterie();
                    fetch(`/scene/element/${id}/batterie/delete`, { method: 'POST' });
                }
            }
        });
    });

    // ---- Récap équipements en temps réel ----
   function mettreAJourRecap() {
    const elementsPoses = sceneContainer.querySelectorAll('.instrument-placed');
    const compteurEquip = {};
    const compteurInstr = {};

    elementsPoses.forEach(el => {
        if (el.dataset.type === 'equipement') {
            const libelle = el.dataset.instrument;
            if (!compteurEquip[libelle]) compteurEquip[libelle] = 0;
            compteurEquip[libelle]++;
        } else {
            const libelle = el.dataset.instrument;
            if (!compteurInstr[libelle]) compteurInstr[libelle] = 0;
            compteurInstr[libelle]++;
        }
    });

    // Récap équipements
    const tbodyEquip = document.querySelector('#recap-equipements tbody');
    if (tbodyEquip) {
        tbodyEquip.innerHTML = '';
        const libelles = Object.keys(compteurEquip);
        if (libelles.length === 0) {
            tbodyEquip.innerHTML = '<tr><td colspan="2" class="text-muted">Aucun équipement posé.</td></tr>';
        } else {
            libelles.forEach(libelle => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${libelle}</td><td>${compteurEquip[libelle]}</td>`;
                tbodyEquip.appendChild(tr);
            });
        }
    }

    // Récap instruments
    const tbodyInstr = document.querySelector('#recap-instruments tbody');
    if (tbodyInstr) {
        tbodyInstr.innerHTML = '';
        const libelles = Object.keys(compteurInstr);
        if (libelles.length === 0) {
            tbodyInstr.innerHTML = '<tr><td colspan="2" class="text-muted">Aucun instrument posé.</td></tr>';
        } else {
            libelles.forEach(libelle => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${libelle}</td><td>${compteurInstr[libelle]}</td>`;
                tbodyInstr.appendChild(tr);
            });
        }
    }
}
    // ---- Config batterie — affiche le panneau ----
    function afficherPanneauBatterie(elementId) {
        const panneau = document.getElementById('panneau-batterie');
        if (!panneau) return;

        document.getElementById('batterie-element-id').value = elementId;
        panneau.style.display = 'block';

        const recapBatterie = document.getElementById('recap-batterie');
        if (recapBatterie) recapBatterie.style.display = 'block';
    }

    // ---- Config batterie — cache le panneau et remet à zéro ----
    function cacherPanneauBatterie() {
        const panneau = document.getElementById('panneau-batterie');
        if (!panneau) return;

        panneau.style.display = 'none';

        const recapBatterie = document.getElementById('recap-batterie');
        if (recapBatterie) recapBatterie.style.display = 'none';

        document.getElementById('batterie-element-id').value  = '';
        document.getElementById('batterie-toms').value         = 0;
        document.getElementById('batterie-cymbales').value     = 0;
        document.getElementById('batterie-caisse-claire').value = 0;
        document.getElementById('batterie-grosse-caisse').value = 0;
        document.getElementById('batterie-charleston').value   = 0;
    }

    // ---- Affiche le tableau matériel suggéré ----
    function afficherSuggestions(suggestions) {
        const wrapper = document.getElementById('recap-suggestions');
        const tbody   = document.querySelector('#recap-suggestions tbody');
        if (!tbody || !wrapper) return;

        tbody.innerHTML = '';

        if (suggestions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-muted">Aucune suggestion.</td></tr>';
        } else {
            suggestions.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${s.libelle}</td><td>${s.quantite}</td>`;
                tbody.appendChild(tr);
            });
        }

        wrapper.style.display = 'block';
    }

    // ---- Bouton sauvegarder la config batterie ----
    const btnSauvegarder = document.getElementById('btn-sauvegarder-batterie');
    if (btnSauvegarder) {
        btnSauvegarder.addEventListener('click', () => {
            const elementId = document.getElementById('batterie-element-id').value;
            if (!elementId) return;

            // Étape 1 — on sauvegarde la config batterie
            fetch(`/scene/element/${elementId}/batterie`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nbToms:         parseInt(document.getElementById('batterie-toms').value) || 0,
                    nbCymbales:     parseInt(document.getElementById('batterie-cymbales').value) || 0,
                    nbCaisseClaire: parseInt(document.getElementById('batterie-caisse-claire').value) || 0,
                    nbGrosseCaisse: parseInt(document.getElementById('batterie-grosse-caisse').value) || 0,
                    nbCharleston:   parseInt(document.getElementById('batterie-charleston').value) || 0,
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    btnSauvegarder.textContent = '✅ Sauvegardé !';
                    setTimeout(() => btnSauvegarder.textContent = 'Sauvegarder la configuration', 2000);

                    // Mise à jour du récap batterie
                    document.getElementById('recap-toms').textContent          = document.getElementById('batterie-toms').value;
                    document.getElementById('recap-cymbales').textContent      = document.getElementById('batterie-cymbales').value;
                    document.getElementById('recap-caisse-claire').textContent = document.getElementById('batterie-caisse-claire').value;
                    document.getElementById('recap-grosse-caisse').textContent = document.getElementById('batterie-grosse-caisse').value;
                    document.getElementById('recap-charleston').textContent    = document.getElementById('batterie-charleston').value;

                    // Étape 2 — on génère les suggestions de matériel
                    const sceneId = sceneContainer.dataset.sceneId;
                    fetch(`/scene/${sceneId}/suggestions`, { method: 'POST' })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) afficherSuggestions(res.suggestions);
                    });
                }
            });
        });
    }

})();