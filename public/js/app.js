let index = 1;

document.getElementById('add_instrument').addEventListener('click', function() {
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
