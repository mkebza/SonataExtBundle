$(document).ready(function() {
    // Bind data-confirm handler
    $('a[data-confirm]').click(function() {
        return confirm($(this).data('confirm'));
    });
    $('button[data-confirm]').click(function() {
        return confirm($(this).data('confirm'));
    });
})
