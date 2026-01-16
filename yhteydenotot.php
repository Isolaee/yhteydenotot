<?php
/**
 * Plugin Name: Yhteydenotot
 * Description: Adds a custom WooCommerce My Account tab for contact forms
 * Version: 1.0.0
 * Author: Eero Isola
 * Text Domain: yhteydenotot
 */

if (!defined('ABSPATH')) {
    exit;
}

class Yhteydenotot_Endpoint {

    public function __construct() {
        add_action('init', [$this, 'add_endpoint']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_menu_item']);
        add_action('woocommerce_account_yhteydenotot_endpoint', [$this, 'endpoint_content']);
        add_filter('woocommerce_get_query_vars', [$this, 'add_query_vars']);
        add_filter('woocommerce_locate_template', [$this, 'locate_template'], 10, 3);
    }

    /**
     * Register the endpoint
     */
    public function add_endpoint() {
        add_rewrite_endpoint('yhteydenotot', EP_ROOT | EP_PAGES);
    }

    /**
     * Add query vars
     */
    public function add_query_vars($vars) {
        $vars['yhteydenotot'] = 'yhteydenotot';
        return $vars;
    }

    /**
     * Add menu item to My Account menu
     */
    public function add_menu_item($items) {
        $new_items = [];

        foreach ($items as $key => $value) {
            $new_items[$key] = $value;

            if ($key === 'dashboard') {
                $new_items['yhteydenotot'] = __('Yhteydenotot', 'yhteydenotot');
            }
        }

        if (!isset($new_items['yhteydenotot'])) {
            $logout = isset($new_items['customer-logout']) ? $new_items['customer-logout'] : null;
            unset($new_items['customer-logout']);
            $new_items['yhteydenotot'] = __('Yhteydenotot', 'yhteydenotot');
            if ($logout) {
                $new_items['customer-logout'] = $logout;
            }
        }

        return $new_items;
    }

    /**
     * Locate plugin template
     */
    public function locate_template($template, $template_name, $template_path) {
        if ($template_name === 'myaccount/yhteydenotot.php') {
            $plugin_template = plugin_dir_path(__FILE__) . 'templates/' . $template_name;
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
        return $template;
    }

    /**
     * Endpoint content - load template
     */
    public function endpoint_content() {
        wc_get_template('myaccount/yhteydenotot.php');
    }

    /**
     * Flush rewrite rules on activation
     */
    public static function activate() {
        $instance = new self();
        $instance->add_endpoint();
        flush_rewrite_rules();
    }

    /**
     * Flush rewrite rules on deactivation
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}

new Yhteydenotot_Endpoint();

register_activation_hook(__FILE__, ['Yhteydenotot_Endpoint', 'activate']);
register_deactivation_hook(__FILE__, ['Yhteydenotot_Endpoint', 'deactivate']);
