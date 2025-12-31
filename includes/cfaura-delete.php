<?php


    check_ajax_referer('wp_contact_form_8_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    if (empty($_POST['id'])) {
        wp_send_json_error(['message' => 'Invalid form ID']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'wp_contact_form_8_forms';
    $deleted = $wpdb->delete($table, ['id' => intval($_POST['id'])]);

    if ($deleted) {
        wp_send_json_success(['message' => 'Form deleted successfully']);
    } else {
        wp_send_json_error(['message' => 'Failed to delete form']);
    }
