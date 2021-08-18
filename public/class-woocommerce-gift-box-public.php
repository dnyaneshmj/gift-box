<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       dnyaneshmahajan.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/public
 * @author     Dnyanesh Mahajan <dnyaneshmahajan12@gmail.com>
 */
class Woocommerce_Gift_Box_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Gift_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Gift_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-gift-box-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Gift_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Gift_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-gift-box-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'dd-slick', plugin_dir_url( __FILE__ ) . 'js/dd-slick.js', array( 'jquery' ), '1.0', false );
		

	}
	public function wcgb_create_shortcode(){
		add_shortcode( 'gift_box_products', array($this, 'gift_box_short_code_callback') );
	}

	 function gift_box_short_code_callback(){ 
		ob_start();
		?>
		<div class="product-listing woocommerce clearfix columns-4">
			<?php
			//echo get_theme_mod('woocommerce_catalog_columns');
			
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'meta_key' => 'is_gift_box',
					'meta_value' => 'true'
					);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						
							wc_get_template_part( 'content', 'product' );
						
					endwhile;
				} else {
					echo __( 'No products found' );
				}
				wp_reset_postdata();
			?>
		</div>
		<?php
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}

	public function wcgb_hide_box_and_wrap($q){
		$meta_query = $q->get( 'meta_query' );
		$meta_query[] = array(
		  'key' => 'is_gift_box',
		  'compare' => 'NOT EXISTS'
		);
		$meta_query[] = array(
			'key' => 'is_gift_wrap',
			'value' => 'true',
			'compare' => 'NOT EXISTS'
		  );
		$q->set( 'meta_query', $meta_query );
	}


	function wcgb_locate_template( $template, $template_name, $template_path ) {
		$basename = basename( $template );

		if( $basename == 'cart.php' ) {
			$template =  WOOCOMMERCE_GIFT_BOX_PATH . 'templates/cart.php';
		}
		if( $basename == 'mini-cart.php' ) {
			$template =  WOOCOMMERCE_GIFT_BOX_PATH . 'templates/mini-cart.php';
		}
		return $template;
	
	}

	function my_custom_add_to_cart_redirect( $url ) {
	
		if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
			return $url;
		}
		
		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
		

		$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
			
		if($is_gift_box == true){
			
			$url = wc_get_page_permalink( 'shop' );
		}
			
		
		return $url;
	
	}
	
	public function wcgb_add_item_data($cart_item_data,$product_id, $variation_id){


		$current_package = WC()->session->get('wcgb_current_package');
		$packages = WC()->session->get('wcgb_packages' );
	
		if(isset($current_package) && isset($packages) && ! isset($cart_item_data['item_package']) ){
			
			$cart_item_data['item_package'] = $current_package;
			$cart_item_data['package_product'] = $packages[$current_package];

		}

		return $cart_item_data;
	}

	public function wcgb_on_product_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data  ){
		
		$wcgb_packages = WC()->session->get('wcgb_packages');
		$current_package = WC()->session->get('wcgb_current_package');
		
		$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
			
		if($is_gift_box == true){
			
			if($cart_item_data['update_package']){
				
				$package = $cart_item_data['package'];
				$wcgb_packages[$package] = ['product_id' => $product_id,'cart_item_key' => $cart_item_key ];
				WC()->session->set('wcgb_packages', $wcgb_packages );

			}elseif(  !$wcgb_packages && !$current_package ){
	
				WC()->session->set('wcgb_packages', [ 'package1' => ['product_id' => $product_id,'cart_item_key' => $cart_item_key ]] );
				WC()->session->set('wcgb_current_package', 'package1' );

			}else{

				$currentPackageCount = count($wcgb_packages ) + 1;
                $current_package = 'package'.$currentPackageCount;
                $wcgb_packages = array_merge($wcgb_packages, [  $current_package => ['product_id' => $product_id,'cart_item_key' => $cart_item_key ] ]);
				WC()->session->set('wcgb_packages', $wcgb_packages );
				WC()->session->set('wcgb_current_package', $current_package );
				
			}

		}


	}
	
	public function wcgb_get_gift_box_options(){
		
		$options = [];
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_key' => 'is_gift_box',
			'meta_value' => 'true'
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				
			$options[] = get_the_ID();
				
			endwhile;
		} else {
			$options = [];
		}
		wp_reset_postdata();

		return $options;
			
	}

	public function wcgb_get_gift_wrap_options(){
		
		$options = [];
		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'order'				=> 'asc',
			'meta_query' => array(
							array(
								'key'     => 'is_gift_wrap',
								'value'   => 'true'
							),
							array(
								'key'     => '_price',
								'value'   => 'true'
							),
						),
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				
			$options[] = get_the_ID();
				
			endwhile;
		} else {
			$options = [];
		}
		wp_reset_postdata();

		return $options;
			
	}

	public function wcgb_change_gw_value_in_cart(){

		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;
		$new_gw_id = isset($formData['new_gw_id']) && $formData['new_gw_id'] != '' ? $formData['new_gw_id'] : '' ;
		$old_gw_id = isset($formData['old_gw_id']) && $formData['old_gw_id'] != '' ? $formData['old_gw_id'] : '' ;
		$old_gw_ckey = isset($formData['old_gw_ckey']) && $formData['old_gw_ckey'] != '' ? $formData['old_gw_ckey'] : '' ;
	

		if( $package && $new_gw_id  ){

		
			$wcgb_packages = WC()->session->get('wcgb_wraps');

			//if()
			//WC()->session->set('wcgb_wraps',  );
			
			//WC()->cart->add_to_cart( $new_gb_id ,1,  0,array(), array('update_package' => true , 'package' => $package  ) );
			
			
			if($old_gb_id != '' && $old_gb_id != 'new')
				$removed = WC()->cart->remove_cart_item( $old_gb_ckey );
			

			wp_send_json_success();
            wp_die();

		}

	}	
	
	public function wcgb_change_gb_value_in_cart(){

		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;
		$new_gb_id = isset($formData['new_gb_id']) && $formData['new_gb_id'] != '' ? $formData['new_gb_id'] : '' ;
		$old_gb_id = isset($formData['old_gb_id']) && $formData['old_gb_id'] != '' ? $formData['old_gb_id'] : '' ;
		$old_gb_ckey = isset($formData['old_gb_ckey']) && $formData['old_gb_ckey'] != '' ? $formData['old_gb_ckey'] : '' ;
	

		if( $package && $new_gb_id  ){

			// $wcgb_packages = WC()->session->get('wcgb_packages');
			// $current_package = WC()->session->get('wcgb_current_package');
			
			// //$wcgb_packages[$package] = $new_gb_id;

			// //WC()->session->set('wcgb_packages', $wcgb_packages );
			
			WC()->cart->add_to_cart( $new_gb_id ,1,  0,array(), array('update_package' => true , 'package' => $package  ) );
			
			
			if($old_gb_id != '' && $old_gb_id != 'new')
				$removed = WC()->cart->remove_cart_item( $old_gb_ckey );
			

			wp_send_json_success();
            wp_die();

		}

	}

	public function wcgb_remove_packages_from_cart(){
		
		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;
		$gb_key = isset($formData['gb_key']) && $formData['gb_key'] != '' ? $formData['gb_key'] : '' ;
		$products = isset($formData['products']) && $formData['products']  ? $formData['products'] : [] ;

		//$old_gb_ckey = isset($formData['old_gb_ckey']) && $formData['old_gb_ckey'] != '' ? $formData['old_gb_ckey'] : '' ;
	

		if( $package && $gb_key ){

			$wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			
			if($wcgb_packages[$package]){
				
				$removed = WC()->cart->remove_cart_item( $gb_key );

				unset($wcgb_packages[$package]);
				
				WC()->session->set('wcgb_packages', $wcgb_packages );

				end($wcgb_packages);
				$key = key($wcgb_packages);
				WC()->session->set('wcgb_current_package', $current_package );
				
			}			
			
			if(!empty($products)){
				foreach ($products as $key ) {
					$removed = WC()->cart->remove_cart_item( $key );
				}
			}
				
			

			wp_send_json_success();
            wp_die();

		}

	}

	public function prevent_admin_access() {       

		if ( is_admin() && !defined('DOING_AJAX') && ( 
			current_user_can('usercrp') || current_user_can('userpcp') ||  
			current_user_can('subscriber') || current_user_can('contributor') || 
			current_user_can('editor'))) {
			  session_destroy();
			  wp_logout();
			  wp_redirect( home_url() );
			 exit;
		}
	}
}
