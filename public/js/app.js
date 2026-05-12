
//ajout isntrument creation scene

let index = 1;
const addBtn = document.getElementById('add_instrument');
if (addBtn) {
    addBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.innerHTML = `
            <div class="instrument-row">
                <select name="instruments[${index}]">
                    ${document.querySelector('select[name="instruments[0]"]').innerHTML}
                </select>
                <input type="text" name="nomMusicien[${index}]" placeholder="Nom musicien">
                <button type="button" style="color:red; background:none; border:none;">✕</button>
            </div>
        `;
        document.getElementById('instruments').appendChild(div);
        index++;
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
        const q = input.value.trim();

        if (q.length < 2) {
            suggestions.style.display = 'none';;
            return;
        }

        fetch('/catalogue/search?q=' +encodeURIComponent(q), {
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
                    const cible = document.getElementById('materiel-' +item.id);
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
