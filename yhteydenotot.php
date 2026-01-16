<?php
/**
 * Plugin Name: Yhteydenotot
 * Description: Adds a custom WooCommerce My Account endpoint for contact forms
 * Version: 1.0.0
 * Author: GR
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

        // Set Oxygen template for this endpoint
        add_filter('template_include', [$this, 'set_oxygen_template'], 99);
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

            // Add after dashboard or at the end
            if ($key === 'dashboard') {
                $new_items['yhteydenotot'] = __('Yhteydenotot', 'yhteydenotot');
            }
        }

        // If dashboard wasn't found, add at end before logout
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
     * Endpoint content - display shortcodes
     */
    public function endpoint_content() {
        echo do_shortcode('[cf7-views id="2369"]');
        echo do_shortcode('[cf7-views id="2374"]');
    }

    /**
     * Use Oxygen default template
     */
    public function set_oxygen_template($template) {
        global $wp_query;

        if (isset($wp_query->query_vars['yhteydenotot'])) {
            // Check if Oxygen is active and has a default template
            if (function_exists('ct_template_output')) {
                // Let Oxygen handle the template
                return $template;
            }
        }

        return $template;
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

// Initialize
new Yhteydenotot_Endpoint();

// Activation/Deactivation hooks
register_activation_hook(__FILE__, ['Yhteydenotot_Endpoint', 'activate']);
register_deactivation_hook(__FILE__, ['Yhteydenotot_Endpoint', 'deactivate']);
