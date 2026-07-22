(function ($) {
    'use strict';

    $(function () {
        var form = $('#categoryForm');

        form.validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
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
            submitHandler: function (categoryForm) {
                var button = $(categoryForm).find('button[type="submit"]');
                var errorBox = $(categoryForm).find('.ajax-errors');
                button.prop('disabled', true);
                errorBox.addClass('d-none').empty();

                $.ajax({
                    url: categoryForm.action,
                    method: 'POST',
                    data: $(categoryForm).serialize()
                }).done(function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
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
                        : [xhr.responseJSON?.message || 'Unable to save category.'];
                    errorBox.html('<ul><li>' + messages.join('</li><li>') + '</li></ul>').removeClass('d-none');
                }).always(function () {
                    button.prop('disabled', false);
                });
            }
        });

        $('#addCategoryButton').on('click', function () {
            categoryFormReset();
        });

        $('.edit-category').on('click', function () {
            categoryFormReset();
            form.attr('action', $(this).data('url'));
            form.find('[name="_method"]').val('PUT');
            form.find('.modal-title').text('Edit Category');
            form.find('button[type="submit"]').text('Update Category');
            $('#category_name').val($(this).data('name'));
            $('#category_color').val($(this).data('color'));
            $('#category_status').prop('checked', Number($(this).data('status')) === 1);
        });

        $('.delete-category').on('click', function () {
            var url = $(this).data('url');
            var name = $(this).data('name');

            Swal.fire({
                icon: 'warning',
                title: 'Delete category?',
                text: 'Delete "' + name + '"?',
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

        function categoryFormReset() {
            form.get(0).reset();
            form.attr('action', form.data('store-url'));
            form.find('[name="_method"]').val('POST');
            form.find('.modal-title').text('Add Category');
            form.find('button[type="submit"]').text('Save Category');
            form.find('.ajax-errors').addClass('d-none').empty();
            form.find('.is-invalid').removeClass('is-invalid');
            form.validate().resetForm();
            $('#category_color').val('#123e72');
            $('#category_status').prop('checked', true);
        }
    });
})(jQuery);
