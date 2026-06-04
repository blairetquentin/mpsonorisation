
//ajout isntrument creation scene
attachAutocomplete(
    document.getElementById('recherche-instrument'),
    document.getElementById('suggestions-instrument')
);
 
let index = 1;
const addBtn = document.getElementById('add_instrument');
if (addBtn) {
    addBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.innerHTML = `
            <div class="instrument-row row g-2 align-items-center mb-2 position-relative">
            <div class="col-md-5">
                <input
                    type="text"
                    class="form-control"
                    name="instruments[${index}]"
                    placeholder="Rechercher un instrument..."
                    autocomplete="off"
                >
                <input type="hidden" name="instrumentsId[${index}]" id="instrument-id-${index}">
                <ul class="list-group position-absolute w-100 z-3" style="display:none;"></ul>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="nomMusicien[${index}]" placeholder="Nom du musicien">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm">✕</button>
            </div>
        </div>
        `;
        document.getElementById('instruments').appendChild(div);
        index++;
        attachAutocomplete(
            div.querySelector('input[type="text"]'),
            div.querySelector('ul')
        );
    });
 
    document.getElementById('instruments').addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            e.target.closest('div').remove();
        }
    });
}
 
//bloquer rechargement de page quand ajout au panier
document.querySelectorAll('.form-add-panier').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
 
        var btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
 
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                btn.textContent = '✓ Ajouté !';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
 
                setTimeout(function() {
                    btn.textContent = 'Ajouter au panier';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                    btn.disabled = false;
                }, 2000);
            }
        })
        .catch(function() {
            btn.disabled = false;
            form.submit();
        });
    });
});
 
//autocompletion champ recherche catalogue
const input = document.getElementById('recherche-catalogue');
const suggestions = document.getElementById('suggestions');
 
if(input) {
    input.addEventListener('input', function(){
        const query = input.value.trim();
 
        if (query.length < 2) {
            suggestions.style.display = 'none';
            return;
        }
 
        fetch('/catalogue/search?query=' + encodeURIComponent(query), {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            suggestions.innerHTML = '';
            if (data.length === 0) {
                suggestions.style.display = 'none';
                return;
            }
            data.forEach(function(item) {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.style.cursor = 'pointer';
                li.textContent = item.nom;
                li.addEventListener('click', function(){
                    const cible = document.getElementById('materiel-' + item.id);
                    if (cible) {
                        cible.scrollIntoView({ behavior: 'smooth'});
                    }
                    suggestions.style.display = 'none';
                    input.value = item.nom;
                });
                suggestions.appendChild(li);
            });
            suggestions.style.display = 'block';
        });
    });
    document.addEventListener('click', function(e){
        if (!input.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    })
}
 
//autocomplete instrument
function attachAutocomplete(input, suggestions){
    if(input) {
        input.addEventListener('input', function(){
            const query = input.value.trim();
            if (query.length < 1) {
                suggestions.style.display = 'none';
                return;
            }
            fetch('/scene/search?q=' + encodeURIComponent(query), {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                suggestions.innerHTML = '';
                if (data.length === 0) {
                    suggestions.style.display = 'none';
                    return;
                }
                data.forEach(function(item){
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.style.cursor = 'pointer';
                    li.textContent = item.libelle;
                    li.addEventListener('click', function(){
                        const row = li.closest('.instrument-row');
                            row.querySelector('input[type="text"]').value = item.libelle;
                            row.querySelector('input[type="hidden"]').value = item.id;
                            suggestions.style.display = 'none';
                    })
 
                    suggestions.appendChild(li);
                });
                suggestions.style.display = 'block';
            });
        });
        document.addEventListener('click', function(e){
            
            if (!input.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        })
    }
}