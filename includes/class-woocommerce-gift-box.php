<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       dnyaneshmahajan.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/includes
 * @author     Dnyanesh Mahajan <dnyaneshmahajan12@gmail.com>
 */
class Woocommerce_Gift_Box {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Gift_Box_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOCOMMERCE_GIFT_BOX_VERSION' ) ) {
			$this->version = WOOCOMMERCE_GIFT_BOX_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-gift-box';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Gift_Box_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Gift_Box_i18n. Defines internationalization functionality.
	 * - Woocommerce_Gift_Box_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Gift_Box_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-gift-box-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-gift-box-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-gift-box-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-gift-box-public.php';

		$this->loader = new Woocommerce_Gift_Box_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Gift_Box_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Gift_Box_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Gift_Box_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wcgb_gift_product_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wcgb_gift_product_save_metabox' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Gift_Box_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'wcgb_create_shortcode' );

		$this->loader->add_action('init', $plugin_public, 'prevent_admin_access');

		$this->loader->add_action( 'woocommerce_product_query', $plugin_public, 'wcgb_hide_box_and_wrap' );
		
		$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_public, 'wcgb_on_product_add', 20, 6);
		$this->loader->add_filter( 'woocommerce_add_to_cart_redirect',  $plugin_public, 'my_custom_add_to_cart_redirect' );
		
		$this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'wcgb_add_item_data',10,3);
		$this->loader->add_filter('woocommerce_get_cart_item_from_session', $plugin_public, 'wcgb_add_item_data',10,3);
		
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'wcgb_locate_template', 10, 3 );
		
		$this->loader->add_action( 'wp_ajax_wcgb_change_gb_in_cart',  $plugin_public, 'wcgb_change_gb_value_in_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_change_gb_in_cart',  $plugin_public, 'wcgb_change_gb_value_in_cart' );
		

		$this->loader->add_action( 'wp_ajax_wcgb_change_gw_in_cart',  $plugin_public, 'wcgb_change_gw_value_in_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_change_gw_in_cart',  $plugin_public, 'wcgb_change_gw_value_in_cart' );

		$this->loader->add_action( 'wp_ajax_wcgb_remove_pkg_from_cart',  $plugin_public, 'wcgb_remove_packages_from_cart' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_remove_pkg_from_cart',  $plugin_public, 'wcgb_remove_packages_from_cart' );

		$this->loader->add_action( 'wp_ajax_wcgb_add_address_to_package',  $plugin_public, 'wcgb_add_package_address' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_add_address_to_package',  $plugin_public, 'wcgb_add_package_address' );
		
		$this->loader->add_action( 'wp_ajax_wcgb_get_address_of_package',  $plugin_public, 'wcgb_get_package_address' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_get_address_of_package',  $plugin_public, 'wcgb_get_package_address' );
		
		$this->loader->add_action( 'wp_ajax_wcgb_save_note_of_package',  $plugin_public, 'wcgb_save_package_note' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcgb_save_note_of_package',  $plugin_public, 'wcgb_save_package_note' );
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Gift_Box_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
