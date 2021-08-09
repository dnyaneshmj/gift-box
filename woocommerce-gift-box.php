<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              dnyaneshmahajan.com
 * @since             1.0.0
 * @package           Woocommerce_Gift_Box
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Gift Box
 * Plugin URI:        dnyaneshmahajan.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Dnyanesh Mahajan
 * Author URI:        dnyaneshmahajan.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-gift-box
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_GIFT_BOX_VERSION', '1.0.0' );

define( 'WOOCOMMERCE_GIFT_BOX_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-gift-box-activator.php
 */
function activate_woocommerce_gift_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-box-activator.php';
	Woocommerce_Gift_Box_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-gift-box-deactivator.php
 */
function deactivate_woocommerce_gift_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-box-deactivator.php';
	Woocommerce_Gift_Box_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_gift_box' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_gift_box' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-box.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_gift_box() {

	$plugin = new Woocommerce_Gift_Box();
	$plugin->run();

}
run_woocommerce_gift_box();
