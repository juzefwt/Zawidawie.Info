var collectionHolder, addTagLink, $newLinkLi;

function addTagForm() {
    var prototype = collectionHolder.attr('data-prototype');
    var newForm = prototype.replace(/\$\$name\$\$/g, collectionHolder.children().length);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
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
    collectionHolder = $('#keywords');

    addTagLink = $('<br class="clear" /><a href="#" class="plain button medium green">Dodaj usługi</a>');
    $newLinkLi = $('<li></li>').append(addTagLink);

    collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    collectionHolder.append($newLinkLi);

    addTagLink.bind('click', function(e) {
        e.preventDefault();
        addTagForm();
    });
});