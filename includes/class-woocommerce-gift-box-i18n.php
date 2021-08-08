<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       dnyaneshmahajan.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/includes
 * @author     Dnyanesh Mahajan <dnyaneshmahajan12@gmail.com>
 */
class Woocommerce_Gift_Box_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-gift-box',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
