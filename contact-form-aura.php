<?php
/**
 * Plugin Name: Contact Form 8
 * Description: Next-generation WordPress form builder — developed by Ogrodut IT.
 * Version: 1.0.0
 * Author: Ogrodut It
 * Author URI: https://ogrodutit.com/
 * Plugin URI: https://ogrodutit.com/contact-form-8
 * Text Domain: contact-form-8
 */

if (!defined('ABSPATH')) exit;

// Define constants
define('CF8_PATH', plugin_dir_path(__FILE__));
define('CF8_URL', plugin_dir_url(__FILE__));

// Autoload files
require_once CF8_PATH . 'includes/class-cf8-loader.php';

register_activation_hook(__FILE__, 'wp_contact_form_8_install');
function wp_contact_form_8_install() {
    global $wpdb;
    $table = $wpdb->prefix . 'wp_contact_form_8_forms';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(200) NOT NULL,
        content longtext DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('plugins_loaded', ['CF8_Loader', 'init']);