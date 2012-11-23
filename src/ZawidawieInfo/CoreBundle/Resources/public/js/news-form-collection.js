var photoCollectionHolder, addPhotoLink, $newPhotoLinkLi;

function addTagForm() {
    var prototype = photoCollectionHolder.attr('data-prototype');
    var newForm = prototype.replace(/\$\$name\$\$/g, photoCollectionHolder.children().length);

    var $newFormLi = $('<li></li>').append(newForm);
    $newPhotoLinkLi.before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<br /><a href="#" class="plain button medium red">Usuń</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.bind('click', function(e) {
        e.preventDefault();
        $tagFormLi.remove();
    });
}

jQuery(document).ready(function() {
    photoCollectionHolder = $('#photos');

    addPhotoLink = $('<br class="clear" /><a href="#" class="plain button medium green">Dodaj zdjęcie</a>');
    $newPhotoLinkLi = $('<li></li>').append(addPhotoLink);

    photoCollectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    photoCollectionHolder.append($newPhotoLinkLi);

    addPhotoLink.bind('click', function(e) {
        e.preventDefault();
        addTagForm();
    });
});