<?php
/*
* Plugin Name:        WooCommerce Product Label
* Plugin URI:         https://wewebby.gumroad.com/
* Description:        Input text (Name To Print) to customize a product's label.
* Version:            1.0.0
* Requires at least:  6.4.2
* Requires PHP:       7.4.33
* Author:             WeWebby.com
* Author URI:         https://wewebby.com
* License:            GPLv2 or later
* License URI:        https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:        wooprolab
* Domain Path:        /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'WOOPROLAB_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/wooprolab-style.php');

include( plugin_dir_path( __FILE__ ) . 'includes/wooprolab-input-text-prod.php');
