function showConfirmDialogBeforeDeleteItem(formClass, confirmMessage) {
    $("input[value='DELETE']").parent('form.' + formClass).submit(function (e) {
        e.preventDefault();
        if (confirm(confirmMessage)) {
            $(this).unbind('submit');
            $(this).submit();
        }
    });
}

function submitWhenAnyElementInFormClicked() {
    $('form').find('img, button, a, input[type="button"], input[type="submit"]').click(function (e) {
        $(this).parents('form').submit();
    });
}