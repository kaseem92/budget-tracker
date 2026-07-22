(function ($) {
    'use strict';

    $(function () {
        var form = $('#budgetForm');

        form.validate({
            rules: {
                month: {
                    required: true,
                    min: 1,
                    max: 12
                },
                year: {
                    required: true,
                    digits: true,
                    min: 2000,
                    max: 2100
                },
                amount: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 9999999999.99
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function (error, element) {
                if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                    return;
                }

                error.insertAfter(element);
            },
            submitHandler: function (budgetForm) {
                var button = $(budgetForm).find('button[type="submit"]');
                var errorBox = $(budgetForm).find('.ajax-errors');
                button.prop('disabled', true);
                errorBox.addClass('d-none').empty();

                $.ajax({
                    url: budgetForm.action,
                    method: 'POST',
                    data: $(budgetForm).serialize()
                }).done(function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('budgetModal')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1200,
                        showConfirmButton: false,
                        heightAuto: false
                    }).then(function () {
                        window.location.reload();
                    });
                }).fail(function (xhr) {
                    var messages = xhr.responseJSON && xhr.responseJSON.errors
                        ? Object.values(xhr.responseJSON.errors).flat()
                        : [xhr.responseJSON?.message || 'Unable to save budget.'];
                    errorBox.html('<ul><li>' + messages.join('</li><li>') + '</li></ul>').removeClass('d-none');
                }).always(function () {
                    button.prop('disabled', false);
                });
            }
        });

        $('#addBudgetButton').on('click', function () {
            budgetFormReset();
        });

        $('.edit-budget').on('click', function () {
            budgetFormReset();
            form.attr('action', $(this).data('url'));
            form.find('[name="_method"]').val('PUT');
            form.find('.modal-title').text('Edit Monthly Budget');
            form.find('button[type="submit"]').text('Update Budget');
            $('#budget_month').val($(this).data('month'));
            $('#budget_year').val($(this).data('year'));
            $('#budget_amount').val($(this).data('amount'));
        });

        $('.delete-budget').on('click', function () {
            var url = $(this).data('url');

            Swal.fire({
                icon: 'warning',
                title: 'Delete budget?',
                text: 'The monthly budget will be removed.',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#dc3545',
                heightAuto: false
            }).then(function (result) {
                if (! result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'DELETE'
                }).done(function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: response.message,
                        timer: 1200,
                        showConfirmButton: false,
                        heightAuto: false
                    }).then(function () {
                        window.location.reload();
                    });
                }).fail(function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to delete',
                        text: xhr.responseJSON?.message || 'Please try again.',
                        heightAuto: false
                    });
                });
            });
        });

        function budgetFormReset() {
            form.get(0).reset();
            form.attr('action', form.data('store-url'));
            form.find('[name="_method"]').val('POST');
            form.find('.modal-title').text('Add Monthly Budget');
            form.find('button[type="submit"]').text('Save Budget');
            form.find('.ajax-errors').addClass('d-none').empty();
            form.find('.is-invalid').removeClass('is-invalid');
            form.validate().resetForm();
        }
    });
})(jQuery);
