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
        add_action('woocommerce_account_yhteydenotot_endpoint', [$this, 'endpoint_content'], 20);
        add_filter('woocommerce_get_query_vars', [$this, 'add_query_vars']);
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
     * Endpoint content - display shortcodes
     */
    public function endpoint_content() {
        global $shortcode_tags;

        // Check if shortcode exists
        if (isset($shortcode_tags['cf7-views'])) {
            echo do_shortcode('[cf7-views id="2369"]');
            echo do_shortcode('[cf7-views id="2374"]');
        } else {
            // Fallback: render via WP_Post object simulation
            $post = new stdClass();
            $post->post_content = '[cf7-views id="2369"][cf7-views id="2374"]';
            $post->ID = 0;

            setup_postdata($post);
            echo do_shortcode($post->post_content);
            wp_reset_postdata();
        }
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
