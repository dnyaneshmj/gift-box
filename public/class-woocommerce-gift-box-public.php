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
		
		// Only redirect the product IDs in the array to the checkout
		// if ( in_array( $product_id, array( 1, 16, 24) ) ) {
		// 	$url = WC()->cart->get_checkout_url();
		// }
		//var_dump($product_id);die;
		$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
			
		if($is_gift_box == true){
			$gift_box[] = $product_id;           
			
			$wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			
			if( $wcgb_packages && empty($wcgb_packages) && $current_package && $current_package == ''){
				WC()->session->set('wcgb_packages', [ 'package1' => $product_id] );
				WC()->session->set('wcgb_current_package', 'package1' );
			}else{

				$currentPackageCount = count($wcgb_packages ) + 1;
                $current_package = 'package'.$currentPackageCount;
                $wcgb_packages = array_merge($wcgb_packages, [  $current_package => $product_id ]);
				WC()->session->set('wcgb_packages', $wcgb_packages );
				WC()->session->set('wcgb_current_package', $current_package );
				
			}

			$url = wc_get_page_permalink( 'shop' );
		}
			
		
		return $url;
	
	}
	
	public function wcgb_add_item_data($cart_item_data,$product_id, $variation_id){


		$current_package = WC()->session->get('wcgb_current_package');
		$packages = WC()->session->get('wcgb_packages' );
		//var_dump($cart_item_data);die;
		if(isset($current_package) && isset($packages) && ! isset($cart_item_data['item_package']) ){
			
			$cart_item_data['item_package'] = $current_package;
			$cart_item_data['package_product'] = $packages[$current_package];

		}

		return $cart_item_data;
	}

	public function wcgb_on_product_add(){
		//$product_id = $_POST['product_id'] ;
		//var_dump($_POST['product_id'] );

		$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
		//session_start();    
		if($is_gift_box == true){
			
			WC()->session->set('wcgb_packages', [ $product_id] );

			$_SESSION['wcgb_packages'] = [ $product_id => [] ];
			$_SESSION['wcgb_current_package'] = $product_id;
			
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
}
