<?php
if (!defined('ABSPATH')) exit;

class CFAURA_Loader {

    public static function admin_assets($hook) {
        if ($hook !== 'toplevel_page_wp_contact_form_aura') return;

        wp_enqueue_style(
            'wp_contact_form_aura-style-admin',
            CFAURA_URL . 'assets/css/admin.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');

        wp_enqueue_script(
            'wp_contact_form_aura-admin',
            CFAURA_URL . 'assets/js/wp_contact_form_aura-admin.js',
            ['jquery', 'jquery-ui-sortable', 'jquery-ui-draggable'],
            '1.0.0',
            true
        );

        wp_localize_script('wp_contact_form_aura-admin', 'wp_contact_form_aura_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wp_contact_form_aura_nonce')
        ]);
    }

    public static function init() {
        add_action('init', [__CLASS__, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        add_action('admin_menu', [__CLASS__, 'register_admin_page']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_assets']);

        add_action('wp_ajax_wp_contact_form_aura_create_form', [__CLASS__, 'create_form']);
        add_action('wp_ajax_wp_contact_form_aura_delete_form', [__CLASS__, 'delete_form']);
        add_action('wp_ajax_wp_contact_form_aura_save_form',   [__CLASS__, 'save_form']);
        add_action('wp_ajax_wp_contact_form_aura_get_form',    [__CLASS__, 'get_form']);
    }

    public static function enqueue_assets() {
        wp_enqueue_style(
            'wp_contact_form_aura-style',
            CFAURA_URL . 'assets/css/style.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'wp_contact_form_aura-script',
            CFAURA_URL . 'assets/js/script.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }

    public static function register_shortcodes() {
        add_shortcode('contact_form_aura', [__CLASS__, 'render_form']);
    }

    public static function render_form($atts = [], $content = '') {
        ob_start();
        include CFAURA_PATH . 'templates/form-basic.php';
        return ob_get_clean();
    }

    public static function register_admin_page() {
        add_menu_page(
            __('Contact Form Aura', 'contact-form-aura'),
            __('Contact Form Aura', 'contact-form-aura'),
            'manage_options',
            'wp_contact_form_aura',
            [__CLASS__, 'admin_page_content'],
            'dashicons-email-alt2',
            26
        );
    }

    public static function admin_page_content() {

        global $wpdb;
        $table = $wpdb->prefix . 'contact_form_aura_forms';

        if (isset($_GET['edit_form'])) {
            $form_id = intval($_GET['edit_form']);
            $form = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $form_id)
            );

            echo '<div class="wrap wp_contact_form_aura-builder">';
            echo '<h1>Edit Form: ' . esc_html($form->title ?? 'Untitled') . '</h1>';
            include CFAURA_PATH . 'templates/admin/builder.php';
            echo '</div>';

            wp_enqueue_style(
                'wp_contact_form_aura-builder-style',
                CFAURA_URL . 'assets/css/wp_contact_form_aura-builder.css',
                [],
                '1.0.0'
            );

            wp_enqueue_script(
                'wp_contact_form_aura-builder-script',
                CFAURA_URL . 'assets/js/wp_contact_form_aura-builder.js',
                ['jquery'],
                '1.0.0',
                true
            );
            return;
        }

        $forms = $wpdb->get_results("SELECT * FROM {$table} ORDER BY id DESC");

        echo '<div class="wrap wp_contact_form_aura-admin">';
        echo '<h1 class="wp_contact_form_aura-title">Contact Form Aura</h1>';

        if (empty($forms)) {
            echo '<div class="wp_contact_form_aura-empty">';
            echo '<p>No forms created yet.</p>';
            echo '<a href="#" class="button button-primary wp_contact_form_aura-create-btn">Create New Form</a>';
            echo '</div>';
        } else {

            echo '<a href="#" class="button button-primary wp_contact_form_aura-create-btn" style="margin-bottom:15px;">+ Create New Form</a>';
            echo '<table class="widefat fixed striped">';
            echo '<thead><tr>
                    <th>ID</th>
                    <th>Form Name</th>
                    <th>Shortcode</th>
                    <th>Date</th>
                    <th style="width:150px;">Actions</th>
                  </tr></thead><tbody>';

            foreach ($forms as $form) {
                $shortcode = '[contact_form_aura id="' . esc_attr($form->id) . '"]';
                $edit_url  = admin_url('admin.php?page=wp_contact_form_aura&edit_form=' . $form->id);

                echo '<tr>';
                echo '<td>' . esc_html($form->id) . '</td>';
                echo '<td>' . esc_html($form->title) . '</td>';
                echo '<td><code>' . esc_html($shortcode) . '</code></td>';
                echo '<td>' . esc_html($form->created_at) . '</td>';
                echo '<td>
                        <a href="' . esc_url($edit_url) . '" class="button button-small wp_contact_form_aura-edit-btn">Edit</a>
                        <a href="#" data-id="' . esc_attr($form->id) . '" class="button button-small button-danger wp_contact_form_aura-delete-btn">Delete</a>
                      </td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        }

        echo '</div>';
    }

    public static function create_form() {
        check_ajax_referer('wp_contact_form_aura_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }

        global $wpdb;
        $table = $wpdb->prefix . 'contact_form_aura_forms';

        $title = 'New Form ' . date('Y-m-d H:i');
        $inserted = $wpdb->insert($table, [
            'title'   => $title,
            'content' => ''
        ]);

        if ($inserted) {
            $form_id = $wpdb->insert_id;
            $redirect_url = admin_url('admin.php?page=wp_contact_form_aura&edit_form=' . $form_id);
            wp_send_json_success(['redirect_url' => $redirect_url]);
        }

        wp_send_json_error(['message' => 'Database insert failed']);
    }

    public static function delete_form() {
        include CFAURA_PATH . 'includes/cfaura-delete.php';
    }

    public static function save_form() {
        include CFAURA_PATH . 'includes/cfaura-save.php';
    }

    public static function get_form() {
        check_ajax_referer('wp_contact_form_aura_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }

        global $wpdb;
        $id = intval($_POST['id'] ?? 0);

        if (!$id) {
            wp_send_json_error(['message' => 'Invalid form ID']);
        }

        $table = $wpdb->prefix . 'contact_form_aura_forms';
        $form  = $wpdb->get_row(
            $wpdb->prepare("SELECT content FROM {$table} WHERE id = %d", $id)
        );

        if ($form) {
            wp_send_json_success(['content' => wp_unslash($form->content)]);
        }

        wp_send_json_error(['message' => 'Form not found']);
    }
}