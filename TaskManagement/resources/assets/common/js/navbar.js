$(document).ready(function () {
    $('body').on('click', '[data-bs-toggle=side-menu]', function () {
        $('body').toggleClass('mini-sidebar');
    })

    $('#navbar-menu-vertical .dropdown-toggle').on('click', function (e) {
        $('#navbar-menu-vertical .dropdown-menu').not($(this).closest('.dropdown').find('.dropdown-menu')).removeClass('open');
        $(this).closest('.dropdown').find('.dropdown-menu').toggleClass('open');
    })

    $('body').on('click', function(event) {
        if (!$(event.target).closest('.dropdown-toggle').length) {
            $('.dropdown-menu.open').removeClass('open');

        }
    });
})
