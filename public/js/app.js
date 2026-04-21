// ============================================================
// IIFE 1 — FORMULAIRE SCENE (création / modification)
// S'active uniquement si le conteneur #musiciens-container existe
// ============================================================
(function() {
    const musicienContainer = document.getElementById('musiciens-container');
    if (!musicienContainer) return;

    const instrumentsData = document.getElementById('instruments-data');
    const instruments = JSON.parse(instrumentsData.dataset.instruments);

    let count = 0;

    // ---- Ajoute une nouvelle ligne musicien dans le formulaire ----
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

    // ---- Filtre la liste d'instruments selon la saisie ----
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

    // ---- Ajoute un instrument comme chip sur la ligne musicien ----
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

    // ---- Retire un instrument de la ligne musicien ----
    window.retirerInstrument = function(bouton, id, index) {
        bouton.closest('.instrument-chip').remove();

        const input = document.querySelector(
            `input[type="hidden"][data-id="${id}"][data-index="${index}"]`
        );
        if (input) input.remove();
    }

    // ---- Ferme tous les dropdowns si on clique ailleurs ----
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
// IIFE 2 — PLAN DE SCENE (drag & drop + récap équipements + config batterie)
// S'active uniquement si le conteneur #scene-container existe
// ============================================================
(function() {
    const sceneContainer = document.getElementById('scene-container');
    if (!sceneContainer) return;

    let elementGlisse = null;
    let clone = null;
    let offsetX = 0;
    let offsetY = 0;

    // ---- Branche les événements mousedown sur un élément draggable ----
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

    // ---- Au chargement : masquer dans la sidebar les éléments déjà posés ----
    document.querySelectorAll('.instrument-placed').forEach(item => {
        const id = item.dataset.id;
        const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
        if (sidebarItem) sidebarItem.style.display = 'none';
    });

    // ---- Au chargement : afficher le panneau batterie si une batterie est déjà posée ----
    document.querySelectorAll('.instrument-placed').forEach(el => {
        if (el.dataset.instrument === 'Batterie') {
            afficherPanneauBatterie(el.dataset.id);
        }
    });

    // ---- Au chargement : calculer le récap initial ----
    mettreAJourRecap();

    // ---- Le clone suit la souris ----
    document.addEventListener('mousemove', (e) => {
        if (!clone) return;
        clone.style.left = (e.pageX - offsetX) + 'px';
        clone.style.top = (e.pageY - offsetY) + 'px';
    });

    // ---- Au relâché de la souris : on pose l'élément ----
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

    // ---- Sauvegarde la position en base via AJAX ----
    function sauvegarderPosition(element, posX, posY) {
        const type = element.dataset.type;

        if (type === 'equipement') {
            const sceneId = sceneContainer.dataset.sceneId;
            const instrumentId = element.dataset.id.replace('equipement-', '');

            fetch(`/scene/${sceneId}/equipement/${instrumentId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ positionX: posX, positionY: posY }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    afficherElementSurScene(data.id, posX, posY, element);
                }
            });

        } else {
            const id = element.dataset.id;

            fetch(`/scene/element/${id}/position`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ positionX: posX, positionY: posY }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    afficherElementSurScene(id, posX, posY, element);
                }
            });
        }
    }

    // ---- Affiche ou déplace un élément sur la scène ----
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

        // On masque dans la sidebar gauche
        const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
        if (sidebarItem) sidebarItem.style.display = 'none';

        // On recalcule le récap
        mettreAJourRecap();

        // Si c'est une batterie, on affiche le panneau de config
        if (source.dataset.instrument === 'Batterie') {
            afficherPanneauBatterie(id);
        }
    }

    // ---- Double-clic : retirer un élément du plan ----
    sceneContainer.addEventListener('dblclick', (e) => {
        const elementPose = e.target.closest('.instrument-placed');
        if (!elementPose) return;

        const id = elementPose.dataset.id;

        fetch(`/scene/element/${id}/position`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ positionX: 0, positionY: 0 }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                elementPose.remove();

                const sidebarItem = document.querySelector(`.instrument-item[data-id="${id}"]`);
                if (sidebarItem) sidebarItem.style.display = 'block';

                mettreAJourRecap();

                // Si c'est une batterie, on cache le panneau et on supprime la config en base
                if (elementPose.dataset.instrument === 'Batterie') {
                    cacherPanneauBatterie();
                    fetch(`/scene/element/${id}/batterie/delete`, { method: 'POST' });
                }
            }
        });
    });

    // ---- SF15 — Met à jour le tableau récap des équipements en temps réel ----
    function mettreAJourRecap() {
        const elementsPoses = sceneContainer.querySelectorAll('.instrument-placed');

        const compteur = {};

        elementsPoses.forEach(el => {
            if (el.dataset.type === 'equipement') {
                const libelle = el.dataset.instrument;
                if (!compteur[libelle]) compteur[libelle] = 0;
                compteur[libelle]++;
            }
        });

        const tbody = document.querySelector('#recap-equipements tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        const libelles = Object.keys(compteur);

        if (libelles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-muted">Aucun équipement posé.</td></tr>';
            return;
        }

        libelles.forEach(libelle => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${libelle}</td><td>${compteur[libelle]}</td>`;
            tbody.appendChild(tr);
        });
    }

    // ---- Config batterie — affiche le panneau ----
   function afficherPanneauBatterie(elementId) {
        const panneau = document.getElementById('panneau-batterie');
        if (!panneau) return;

        document.getElementById('batterie-element-id').value = elementId;
        panneau.style.display = 'block';

        // On affiche aussi le tableau récap batterie
        const recapBatterie = document.getElementById('recap-batterie');
        if (recapBatterie) recapBatterie.style.display = 'block';
    }
        // ---- Config batterie — cache le panneau et remet à zéro ----
        function cacherPanneauBatterie() {
        const panneau = document.getElementById('panneau-batterie');
        if (!panneau) return;

        panneau.style.display = 'none';

        // On cache aussi le tableau récap batterie
        const recapBatterie = document.getElementById('recap-batterie');
        if (recapBatterie) recapBatterie.style.display = 'none';

        // On remet à zéro
        document.getElementById('batterie-element-id').value = '';
        document.getElementById('batterie-toms').value = 0;
        document.getElementById('batterie-cymbales').value = 0;
        document.getElementById('batterie-caisse-claire').value = 0;
        document.getElementById('batterie-grosse-caisse').value = 0;
        document.getElementById('batterie-charleston').value = 0;
    }

    // ---- Bouton sauvegarder la config batterie ----
    const btnSauvegarder = document.getElementById('btn-sauvegarder-batterie');
    if (btnSauvegarder) {
        btnSauvegarder.addEventListener('click', () => {
            const elementId = document.getElementById('batterie-element-id').value;
            if (!elementId) return;

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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btnSauvegarder.textContent = '✅ Sauvegardé !';
                    setTimeout(() => btnSauvegarder.textContent = 'Sauvegarder la configuration', 2000);

                    // On met à jour le tableau récap batterie
                    document.getElementById('recap-toms').textContent         = document.getElementById('batterie-toms').value;
                    document.getElementById('recap-cymbales').textContent     = document.getElementById('batterie-cymbales').value;
                    document.getElementById('recap-caisse-claire').textContent = document.getElementById('batterie-caisse-claire').value;
                    document.getElementById('recap-grosse-caisse').textContent = document.getElementById('batterie-grosse-caisse').value;
                    document.getElementById('recap-charleston').textContent   = document.getElementById('batterie-charleston').value;
                }
            });
        });
    }

})();