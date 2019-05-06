var $ = require('jquery');

$('#add-image').click(function(){
    // Récupération des numéros de champs qui vont être créé
    const index = +$('#widgets-counter').val();
    // Récupération du prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    // Injection du code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    handleDeleteButton();
});

function handleDeleteButton() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButton();
