// ---- FORMULAIRE SCENE ----
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

// ---- DRAG & DROP ----
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

            // On calcule l'offset entre le coin de l'élément et le clic
            const rect = item.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;

            // On crée le clone
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

    // On branche sur les éléments de la sidebar
    document.querySelectorAll('.instrument-item').forEach(item => brangherEvenements(item));

    // On branche sur les éléments déjà placés sur la scène
    document.querySelectorAll('.instrument-placed').forEach(item => brangherEvenements(item));

    // Le clone suit la souris
    document.addEventListener('mousemove', (e) => {
        if (!clone) return;

        clone.style.left = (e.pageX - offsetX) + 'px';
        clone.style.top = (e.pageY - offsetY) + 'px';
    });

    // On pose l'élément au mouseup
    document.addEventListener('mouseup', (e) => {
        if (!clone || !elementGlisse) return;

        clone.remove();
        clone = null;

        // On vérifie qu'on est bien au-dessus de la scène
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

        // On calcule la position sur la grille
        const posX = e.clientX - rect.left - offsetX;
        const posY = e.clientY - rect.top - offsetY;

        sauvegarderPosition(elementGlisse.dataset.id, posX, posY);

        elementGlisse = null;
    });

    function sauvegarderPosition(id, posX, posY) {
        fetch(`/scene/element/${id}/position`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                positionX: posX,
                positionY: posY,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                afficherElementSurScene(id, posX, posY);
            }
        });
    }

    function afficherElementSurScene(id, posX, posY) {
        let elementExistant = sceneContainer.querySelector(`.instrument-placed[data-id="${id}"]`);

        if (elementExistant) {
            elementExistant.style.left = posX + 'px';
            elementExistant.style.top = posY + 'px';
            const img = elementExistant.querySelector('img');
            const itemSidebar = document.querySelector(`.instrument-item[data-id="${id}"]`);
            if (img && itemSidebar) {
                img.style.width = itemSidebar.dataset.largeur + 'px';
                img.style.height = itemSidebar.dataset.hauteur + 'px';
            }
        } else {
            const itemSidebar = document.querySelector(`.instrument-item[data-id="${id}"]`);

            const div = document.createElement('div');
            div.className = 'instrument-placed position-absolute';
            div.dataset.id = id;
            div.dataset.nom = itemSidebar.dataset.nom;
            div.dataset.instrument = itemSidebar.dataset.instrument;
            div.dataset.url = itemSidebar.dataset.url;
            div.dataset.largeur = itemSidebar.dataset.largeur;
            div.dataset.hauteur = itemSidebar.dataset.hauteur;
            div.style.left = posX + 'px';
            div.style.top = posY + 'px';
            div.innerHTML = `
                <img
                    src="${itemSidebar.dataset.url}"
                    alt="${itemSidebar.dataset.instrument}"
                    style="width: ${itemSidebar.dataset.largeur}px; height: ${itemSidebar.dataset.hauteur}px; object-fit: contain;"
                >
            `;

            sceneContainer.appendChild(div);
            brangherEvenements(div);
        }
    }
})();