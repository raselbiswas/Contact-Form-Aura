<?php

check_ajax_referer('wp_contact_form_aura_nonce', 'nonce');

if (!current_user_can('manage_options')) {
    wp_send_json_error(['message' => 'Permission denied']);
}

$form_id = intval($_POST['form_id'] ?? 0);
$content = wp_unslash($_POST['content'] ?? '');

if (!$form_id) {
    wp_send_json_error(['message' => 'Invalid form ID']);
}

global $wpdb;
$table = $wpdb->prefix . 'contact_form_aura_forms';

$updated = $wpdb->update(
    $table,
    ['content' => $content],
    ['id' => $form_id]
);

if ($updated !== false) {
    wp_send_json_success(['message' => 'Form saved successfully']);
}

wp_send_json_error(['message' => 'Failed to save form']);