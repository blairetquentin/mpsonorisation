let index2 = 1;

// Délégation pour TOUS les boutons add_instrument (y compris le premier)
document.getElementById('musiciens').addEventListener('click', function(e) {
    if (e.target.classList.contains('add_instrument')) {
        const musicienDiv = e.target.closest('.musicien');
        const instrumentsDiv = musicienDiv.querySelector('.instruments');
        let instrIndex = instrumentsDiv.children.length;
        const musicienIndex = musicienDiv.dataset.index;

        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" name="musicien[${musicienIndex}][instruments][${instrIndex}][nom]" 
                class="form-control mb-2" placeholder="Nom de l'instrument">
        `;
        instrumentsDiv.appendChild(div);
    }
});

// Ajouter un musicien
document.getElementById('add_musicien').addEventListener('click', function() {
    const div = document.createElement('div');
    div.classList.add('musicien');
    div.dataset.index = index2;
    div.innerHTML = `
        <input type="text" name="musicien[${index2}][nom]"
            class="form-control mb-2" placeholder="Nom du musicien">
        <div class="instruments">
            <input type="text" name="musicien[${index2}][instruments][0][nom]"
                class="form-control mb-2" placeholder="Nom de l'instrument">
        </div>
        <button type="button" class="add_instrument btn btn-secondary">
            + Ajouter instrument
        </button>
    `;
    document.getElementById('musiciens').appendChild(div);
    index2++;
});