(function ($) {
    'use strict';

    $(function () {
        var form = $('#expenseForm');
        var expenseModalElement = document.getElementById('expenseModal');
        var budgetExceededModalElement = document.getElementById('budgetExceededModal');
        var expenseModal = bootstrap.Modal.getOrCreateInstance(expenseModalElement);
        var budgetExceededModal = bootstrap.Modal.getOrCreateInstance(budgetExceededModalElement);
        var pendingExpenseData = '';

        $('#expenseFilterForm').validate({
            rules: {
                search: {
                    maxlength: 255
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback'
        });

        form.validate({
            rules: {
                category_id: {
                    required: true
                },
                amount: {
                    required: true,
                    number: true,
                    min: 0.01,
                    max: 9999999999.99
                },
                expense_date: {
                    required: true,
                    date: true
                },
                description: {
                    maxlength: 1000
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
            submitHandler: function (expenseForm) {
                var button = $(expenseForm).find('button[type="submit"]');
                var errorBox = $(expenseForm).find('.ajax-errors');

                saveExpense($(expenseForm).serialize(), button, errorBox, false);
            }
        });

        $('#addExpenseButton').on('click', function () {
            expenseFormReset();
        });

        $('.edit-expense').on('click', function () {
            expenseFormReset();
            form.attr('action', $(this).data('url'));
            form.find('[name="_method"]').val('PUT');
            form.find('.modal-title').text('Edit Expense');
            form.find('button[type="submit"]').text('Update Expense');
            $('#expense_category_id').val($(this).data('category'));
            $('#expense_amount').val($(this).data('amount'));
            $('#expense_date').val($(this).data('date'));
            $('#expense_description').val($(this).data('description'));
        });

        $('#cancelExceededExpense').on('click', function () {
            $(budgetExceededModalElement).one('hidden.bs.modal', function () {
                expenseModal.show();
            });
            budgetExceededModal.hide();
        });

        $('#forceSaveExpense').on('click', function () {
            var button = $(this);
            var errorBox = form.find('.ajax-errors');

            $(budgetExceededModalElement).one('hidden.bs.modal', function () {
                saveExpense(pendingExpenseData + '&force_save=1', button, errorBox, true);
            });
            budgetExceededModal.hide();
        });

        $('.delete-expense').on('click', function () {
            var url = $(this).data('url');

            Swal.fire({
                icon: 'warning',
                title: 'Delete expense?',
                text: 'This action cannot be undone.',
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

        function saveExpense(data, button, errorBox, forceSave) {
            button.prop('disabled', true);
            errorBox.addClass('d-none').empty();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: data
            }).done(function (response) {
                if (response.budgetExceeded) {
                    pendingExpenseData = data;
                    showBudgetWarning(response);
                    return;
                }

                expenseModal.hide();
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
                    : [xhr.responseJSON?.message || 'Unable to save expense.'];
                errorBox.html('<ul><li>' + messages.join('</li><li>') + '</li></ul>').removeClass('d-none');

                if (forceSave) {
                    expenseModal.show();
                }
            }).always(function () {
                button.prop('disabled', false);
            });
        }

        function showBudgetWarning(response) {
            $('#warningBudget').text(formatMoney(response.budget));
            $('#warningCurrentExpense').text(formatMoney(response.currentExpense));
            $('#warningNewExpense').text(formatMoney(response.newExpense));
            $('#warningExceededBy').text(formatMoney(response.exceededBy));

            $(expenseModalElement).one('hidden.bs.modal', function () {
                budgetExceededModal.show();
            });
            expenseModal.hide();
        }

        function formatMoney(amount) {
            return '₹' + Number(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function expenseFormReset() {
            form.get(0).reset();
            form.attr('action', form.data('store-url'));
            form.find('[name="_method"]').val('POST');
            form.find('.modal-title').text('Add Expense');
            form.find('button[type="submit"]').text('Save Expense');
            form.find('.ajax-errors').addClass('d-none').empty();
            form.find('.is-invalid').removeClass('is-invalid');
            form.validate().resetForm();
            $('#expense_date').val(form.data('default-date'));
            pendingExpenseData = '';
        }
    });
})(jQuery);
