jQuery(document).ready(function($) {
    $('.wp_contact_form_8-create-btn').on('click', function(e) {
        e.preventDefault();

        if (!confirm('Create a new form?')) return;

        $.ajax({
            url: wp_contact_form_8_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'wp_contact_form_8_create_form',
                nonce: wp_contact_form_8_ajax.nonce
            },
            beforeSend: function() {
                $('.wp_contact_form_8-create-btn').text('Creating...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    alert(response.data.message || 'Error creating form.');
                }
            },
            error: function() {
                alert('AJAX request failed.');
            },
            complete: function() {
                $('.wp_contact_form_8-create-btn').text('Create New Form').prop('disabled', false);
            }
        });
    });

  $(document).on('click', '.wp_contact_form_8-delete-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this form?')) return;

        $.ajax({
            url: wp_contact_form_8_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'wp_contact_form_8_delete_form',
                nonce: wp_contact_form_8_ajax.nonce,
                id: id
            },
            beforeSend: function() {
                $('.wp_contact_form_8-delete-btn[data-id="' + id + '"]').text('Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Deleted successfully.');
                    location.reload();
                } else {
                    alert(response.data.message || 'Failed to delete.');
                }
            },
            error: function() {
                alert('AJAX request failed.');
            },
            complete: function() {
                $('.wp_contact_form_8-delete-btn[data-id="' + id + '"]').text('Delete');
            }
        });
    });

});