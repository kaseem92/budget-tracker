(function ($) {
    'use strict';

    function uiOnlyMessage(element) {
        var modal = element.closest('.modal').get(0);
        if (modal) {
            bootstrap.Modal.getOrCreateInstance(modal).hide();
        }
        window.alert('UI preview only. Backend functionality will be connected later.');
    }

    $(function () {
        $('.sidebar-toggle').on('click', function () {
            $('body').toggleClass('sidebar-open');
        });

        $('[data-sidebar-close]').on('click', function () {
            $('body').removeClass('sidebar-open');
        });

        $('.password-toggle').on('click', function () {
            var input = $(this).siblings('input');
            var show = input.attr('type') === 'password';
            input.attr('type', show ? 'text' : 'password');
            $(this).find('i').toggleClass('bi-eye', ! show).toggleClass('bi-eye-slash', show);
        });

        $('.demo-form, .demo-filter').on('submit', function (event) {
            event.preventDefault();
            uiOnlyMessage($(this));
        });

        $('.demo-action').on('click', function () {
            uiOnlyMessage($(this));
        });

        $('#categorySearch').on('input', function () {
            var term = $(this).val().toLowerCase();
            $('#categoryTable tbody tr').each(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(term) > -1);
            });
        });
    });
})(jQuery);
