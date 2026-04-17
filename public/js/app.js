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