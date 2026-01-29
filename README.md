# Yhteydenotot

A WordPress plugin that adds a custom WooCommerce My Account tab for displaying contact form submissions.

## Description

Yhteydenotot ("Contact Notes" in Finnish) extends WooCommerce by adding a dedicated endpoint to the My Account page where customers can view their contact-related messages. The plugin integrates with the Flamingo plugin to display form submissions through the `[flamingo_omat_viestit]` shortcode.

## Requirements

- WordPress 5.0+
- WooCommerce 3.0+
- Flamingo plugin (for the `[flamingo_omat_viestit]` shortcode)

## Installation

1. Upload the `yhteydenotot` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. The new "Yhteydenotot" tab will appear in the WooCommerce My Account page

## Features

- Adds a custom endpoint to WooCommerce My Account
- Template override support for theme customization
- Clean URL structure via WordPress rewrite rules

## Template Customization

To customize the output, copy `templates/myaccount/yhteydenotot.php` to your theme:

```
your-theme/woocommerce/myaccount/yhteydenotot.php
```

## Adding Menu Item

The plugin registers the endpoint but does not add a menu item by default. Add it in your theme's `functions.php`:

```php
add_filter('woocommerce_account_menu_items', function($items) {
    $items['yhteydenotot'] = __('Yhteydenotot', 'yhteydenotot');
    return $items;
});
```

## File Structure

```
yhteydenotot/
├── yhteydenotot.php              # Main plugin file
├── templates/
│   └── myaccount/
│       └── yhteydenotot.php      # Endpoint template
└── README.md
```

## Author

Eero Isola
