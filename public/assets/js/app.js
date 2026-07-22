(function ($) {
    'use strict';

    $(function () {
        $.ajaxSetup({
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        $('#categorySearch').on('input', function () {
            var term = $(this).val().toLowerCase();
            $('#categoryTable tbody tr').each(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(term) > -1);
            });
        });
    });
})(jQuery);
