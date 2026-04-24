let index = 1;

document.getElementById('add_instrument').addEventListener('click', function() {
    const div = document.createElement('div');
    div.innerHTML = `
        <input type="text" name="instruments[${index}]"
            class="form-control mb-2" placeholder="Instrument">
    `;
    document.getElementById('instruments').appendChild(div);
    index++;
});