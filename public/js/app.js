let index = 1;

document.getElementById('add_instrument').addEventListener('click', function() {
    const div = document.createElement('div');
    const selectTemplate = document.getElementById('select-instruments');
    const clone = selectTemplate.cloneNode(true);
    clone.removeAttribute('id');
    div.appendChild(clone);

    document.getElementById('instruments').appendChild(div);
    index++;
});

document.getElementById('instruments').addEventListener('click', function(e) {
    if (e.target.tagName === 'BUTTON') {
        e.target.closest('div').remove();
    }
});