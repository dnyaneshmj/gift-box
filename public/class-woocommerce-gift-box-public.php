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
		wp_dequeue_script( 'wc-cart-fragments' );

	}
	public function wcgb_create_shortcode(){
		add_shortcode( 'gift_box_products', array($this, 'gift_box_short_code_callback') );
		add_shortcode( 'greeting_cards', array($this, 'greeting_cards_short_code_callback') );
		remove_action( 'woocommerce_proceed_to_checkout','woocommerce_button_proceed_to_checkout', 20);
	}

	function gift_box_short_code_callback(){ 
		ob_start();
		?>
		<div class="product-listing woocommerce clearfix columns-4">
			<?php
			//echo get_theme_mod('woocommerce_catalog_columns');
			
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> -1,
					'order'				=> 'asc',
					'orderby'        	=> 'meta_value_num',
					'meta_key'       	=> '_price',
					'meta_query' => array(
									array(
										'key'     => 'is_gift_box',
										'value'   => 'true'
									),
								),
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


	function greeting_cards_short_code_callback(){ 
		ob_start();
		?>

		<div class="product-listing woocommerce clearfix columns-4">
			<?php
			//echo get_theme_mod('woocommerce_catalog_columns');
			
				$args = array(
					'post_type' 		=> 'product',
					'posts_per_page' 	=> -1,
					'order'				=> 'asc',
					'orderby'        	=> 'meta_value_num',
					'meta_key'       	=> '_price',
					'meta_query' => array(
									array(
										'key'     => 'is_greeting_card',
										'value'   => 'true'
									),
								),
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



	public function get_lowest_cost_product($key){
		
		$value = 0;
		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> 1,
			'order'				=> 'asc',
			'orderby'        	=> 'meta_value_num',
			'meta_key'       	=> '_price',
			'meta_query' => array(
							array(
								'key'     => $key,
								'value'   => 'true'
							),
						),
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				
				$value = get_the_ID(  );
				
			endwhile;
		} 
		wp_reset_postdata();

		return $value;
	}


	public function wcgb_hide_box_and_wrap($q){
		$meta_query = $q->get( 'meta_query' );
		$meta_query[] = array(
		  'key' => 'is_gift_box',
		  'compare' => 'NOT EXISTS'
		);
		$meta_query[] = array(
			'key' => 'is_gift_wrap',
			'compare' => 'NOT EXISTS'
		  );

		$meta_query[] = array(
			'key' => 'is_greeting_card',
			'compare' => 'NOT EXISTS'
		  );

		  $meta_query[] = array(
			'key' => 'is_individual',
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
		if( $basename == 'review-order.php' ) {
			$template =  WOOCOMMERCE_GIFT_BOX_PATH . 'templates/review-order.php';
		}
		if( $basename == 'form-coupon.php' ) {
			$template =  WOOCOMMERCE_GIFT_BOX_PATH . 'templates/form-coupon.php';
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
		$wcgb_packages = WC()->session->get('wcgb_packages' );
		
		

		$is_individual = get_post_meta( $product_id, 'is_individual', true );
		
		// if($is_individual == true){
		// 	return $cart_item_data;
		// }
		$is_package = $wcgb_packages[$current_package];

		if( $current_package != '' && $wcgb_packages && ! isset($cart_item_data['item_package']) ){
			//var_dump($current_package);die;
			$cart_item_data['item_package'] = $current_package;
			$cart_item_data['package_product'] = $wcgb_packages[$current_package];

		}
		

		// if( !isset($current_package) && !isset($packages) && ! isset($cart_item_data['item_package']) ){
		// 	$cart_item_data['item_package'] = 'package1';
		// }



		return $cart_item_data;
	}

	public function wcgb_on_product_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data  ){
		
		$wcgb_packages = WC()->session->get('wcgb_packages');
		$current_package = WC()->session->get('wcgb_current_package');
		$wcgb_wraps = WC()->session->get('wcgb_wraps');

		$gb_id = $this->get_lowest_cost_product('is_gift_box');
		$wrap_id = $this->get_lowest_cost_product('is_gift_wrap');

		$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
		$is_gift_wrap = get_post_meta( $product_id, 'is_gift_wrap', true );
		$is_individual = get_post_meta( $product_id, 'is_individual', true );
			
		if($is_gift_box == true){
			

			if($cart_item_data['update_package']){
				
				$package = $cart_item_data['package'];
				$wcgb_packages[$package] = ['product_id' => $product_id,'cart_item_key' => $cart_item_key ];
				WC()->session->set('wcgb_packages', $wcgb_packages );

			}elseif(  !$wcgb_packages && !$current_package ){
				
				WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' => 'package1'  ) );
				WC()->session->set('wcgb_packages', [ 'package1' => ['product_id' => $product_id,'cart_item_key' => $cart_item_key ]] );
				WC()->session->set('wcgb_current_package', 'package1' );
				WC()->session->set('wcgb_last_package', 'package1' );
				

			}else{

				$currentPackageCount = count($wcgb_packages ) + 1;
                $current_package = 'package'.$currentPackageCount;
                $wcgb_packages = array_merge($wcgb_packages, [  $current_package => ['product_id' => $product_id,'cart_item_key' => $cart_item_key ] ]);
				
				WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' => $current_package  ) );
				
				WC()->session->set('wcgb_packages', $wcgb_packages );
				WC()->session->set('wcgb_current_package', $current_package );
				WC()->session->set('wcgb_last_package', $current_package );
				
			}

		}

		if($is_gift_wrap == true){

			if(!$wcgb_wraps){
				WC()->session->set( 'wcgb_wraps', [ 'package1' =>  ['product_id' => $product_id, 'cart_item_key' => $cart_item_key] ]);
			
			}elseif($cart_item_data['update_package']){
				
				$package = $cart_item_data['package'];
				$wcgb_wraps[$package] = ['product_id' => $product_id,'cart_item_key' => $cart_item_key ];
				WC()->session->set('wcgb_wraps', $wcgb_wraps );
			}else{

				// $currentPackageCount = count($wcgb_packages ) + 1;
                // $current_package = 'package'.$currentPackageCount;
                // $wcgb_packages = array_merge($wcgb_wraps, [  $current_package => ['product_id' => $product_id,'cart_item_key' => $cart_item_key ] ]);
				// WC()->session->set('wcgb_packages', $wcgb_packages );
				// WC()->session->set('wcgb_current_package', $current_package );
				
			}
		}

		if($is_gift_wrap != true && $is_gift_box != true && $is_individual != true){
			
			if(!$wcgb_packages && $current_package == ''){

			
				WC()->session->set('wcgb_packages', [ 'package1' => ['product_id' => $gb_id ]] );
				WC()->session->set( 'wcgb_wraps', [ 'package1' =>  ['product_id' => $wrap_id ] ]);
				
				WC()->cart->add_to_cart( $gb_id ,1,  0,array(), array('update_package' => true , 'package' => 'package1'  ) );
				WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' => 'package1'  ) );

				WC()->session->set('wcgb_current_package', 'package1' );	
				WC()->session->set('wcgb_last_package', 'package1' );

			}
			if($wcgb_packages && $current_package != ''){
		
				
				$is_package = $wcgb_packages[$current_package];
				
				if( !isset($is_package['product_id']) ){
					
					$currentPackageCount = count($wcgb_packages ) + 1;
                	$current_package = 'package'.$currentPackageCount;
					
					

               		$wcgb_packages = array_merge($wcgb_packages, [  $current_package => ['product_id' => $gb_id ] ]);
					$wcgb_wraps = array_merge($wcgb_wraps, [  $current_package => ['product_id' => $wrap_id ] ]);
									
					WC()->session->set('wcgb_packages', $wcgb_packages );
					WC()->session->set('wcgb_wraps', $wcgb_wraps );
					
					WC()->cart->add_to_cart( $gb_id ,1,  0,array(), array('update_package' => true , 'package' => $current_package  ) );
					WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' => $current_package ) );
					WC()->cart->remove_cart_item( $cart_item_key );
					WC()->session->set('wcgb_current_package', $current_package );
					WC()->session->set('wcgb_last_package', $current_package );
					WC()->cart->add_to_cart( $product_id ,1,  0,array(), array('item_package' => $current_package  , 'package_product' => $gb_id ) );
					
				}
			}
			
		}

	}
	
	public function wcgb_get_gift_box_options(){
		
		$options = [];
		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'order'				=> 'asc',
			'orderby'        	=> 'meta_value_num',
			'meta_key'       	=> '_price',
			'meta_query' => array(
							array(
								'key'     => 'is_gift_box',
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

	public function wcgb_get_gift_wrap_options(){
		
		$options = [];
		$args = array(
			'post_type' 		=> 'product',
			'posts_per_page' 	=> -1,
			'order'				=> 'asc',
			'orderby'        	=> 'meta_value_num',
			'meta_key'       	=> '_price',
			'meta_query' => array(
							array(
								'key'     => 'is_gift_wrap',
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

		
			//$wcgb_packages = WC()->session->get('wcgb_wraps');

			//if()
			//WC()->session->set('wcgb_wraps',  );
			
			WC()->cart->add_to_cart( $new_gw_id ,1,  0,array(), array('update_package' => true , 'package' => $package  ) );
			
			
			if($old_gw_id != '' && $old_gw_id != 'new')
				$removed = WC()->cart->remove_cart_item( $old_gw_ckey );
			

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
		$gw_key = isset($formData['gw_key']) && $formData['gw_key'] != '' ? $formData['gw_key'] : '' ;
		$products = ( is_null($formData['products']) == 0 ) ? $formData['products'] : [] ;

		//var_dump($gb_key);die;
		
		//$old_gb_ckey = isset($formData['old_gb_ckey']) && $formData['old_gb_ckey'] != '' ? $formData['old_gb_ckey'] : '' ;
	

		if( $package && $gb_key ){
			
			//

			$wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			$wcgb_notes = WC()->session->get('wcgb_notes');
			$wcgb_address = WC()->session->get('wcgb_address');
			$wcgb_wraps = WC()->session->get('wcgb_wraps');
			

			if( $wcgb_packages[$package] ){
				
				$removed = WC()->cart->remove_cart_item( $gb_key );
				$removed = WC()->cart->remove_cart_item( $gw_key );
				

				$wcgb_packages[$package] = [''];
				WC()->session->set('wcgb_packages', $wcgb_packages );

				// end($wcgb_packages);
				// $key = key($wcgb_packages);
				// WC()->session->set('wcgb_current_package', $current_package );
				//WC()->session->set('wcgb_last_package', 'package1' );

				foreach($wcgb_packages as $package => $data){ 
					
					if( isset($data['product_id']) && $data['product_id'] != '' ) {
						
						WC()->session->set('wcgb_current_package', $package );
						WC()->session->set('wcgb_last_package', $package );

					}
				}

				if( isset($wcgb_notes[$package]) ){
					unset($wcgb_notes[$package]);
					WC()->session->set('wcgb_notes', $wcgb_notes );
				}

				if( isset($wcgb_address[$package]) ){
					unset($wcgb_address[$package]);
					WC()->session->set('wcgb_address', $wcgb_address );
				}

				if( isset($wcgb_wraps[$package]) ){
					$wcgb_wraps[$package] = [];
					WC()->session->set('wcgb_wraps', $wcgb_wraps );
				}

				

			}			
			
			if(!empty($products)){
				foreach ($products as $key ) {
					$removed = WC()->cart->remove_cart_item( $key );
				}
			}
				
			
			//WC()->cart->calculate_totals();

			wp_send_json_success($wcgb_packages);
            wp_die();

		}
		

	}


	public function wcgb_add_package_address(){
		
		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;
		$full_name = isset($formData['full_name']) && $formData['full_name'] != '' ? $formData['full_name'] : '' ;
		$comp_name = isset($formData['comp_name']) && $formData['comp_name']  ? $formData['comp_name'] : '' ;
		$email = isset($formData['email']) && $formData['email'] != '' ? $formData['email'] : '' ;
		$phone = isset($formData['phone']) && $formData['phone'] != '' ? $formData['phone'] : '' ;
		$address = isset($formData['address']) && $formData['address']  ? $formData['address'] : '' ;

		//var_dump($formData );

		if( $package ){

			
			$wcgb_address = WC()->session->get('wcgb_address');

			
			if( $wcgb_address != '' && !empty($wcgb_address) && !isset( $wcgb_address[$package]) ){

				$wcgb_address = array_merge($wcgb_address, [  $package => ['full_name' => $full_name,'comp_name' => $comp_name,'email' => $email,'phone' => $phone,'address' => $address ] ]);

			}else{
				$wcgb_address = [];
				$wcgb_address = [ $package => ['full_name' => $full_name,'comp_name' => $comp_name,'email' => $email,'phone' => $phone,'address' => $address ] ];

			}
			
			//var_dump($wcgb_address);
			WC()->session->set('wcgb_address', $wcgb_address );
			
			$wcgb_address = WC()->session->get('wcgb_address');
			//var_dump($wcgb_address);
			 wp_send_json_success($wcgb_address);
             wp_die();

		}

	}

	public function wcgb_get_package_address(){
		
		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;

		if( $package ){
			
			$wcgb_address = WC()->session->get('wcgb_address');
			if(isset( $wcgb_address[$package] ) ){
				wp_send_json_success($wcgb_address[$package]);
				wp_die();
			}
			

		}

	}


	public function wcgb_save_package_note(){
		
		$formData = $_POST['formData'];
		$package = isset($formData['package']) && $formData['package'] != '' ? $formData['package'] : '' ;
		$note = isset($formData['note']) && $formData['note'] != '' ? $formData['note'] : '' ;
		//var_dump($package);die;

		if( $package ){

			
			$wcgb_notes = WC()->session->get('wcgb_notes');
		
			if($wcgb_notes != '' &&  !empty($wcgb_notes) && !isset( $wcgb_notes[$package] ) ){
				$wcgb_notes = array_merge($wcgb_notes, [  $package => $note ]);
			}else{
				$wcgb_notes=[];
				$wcgb_notes = [ $package => $note ];
			}

			WC()->session->set('wcgb_notes', $wcgb_notes );
		
			wp_send_json_success();
            wp_die();

		}

	}

	public function wcgb_greeting_card_option(){
		?>

				<div class="form-popup-bg">
                    <div class="form-container">
                        <button id="btnCloseForm" class="close-button">X</button>
                            <p>Recipient Delivery Details</p>
                            <form id="wcgb-user-package-address">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input id="package-fname" type="text" class="form-control"  value="">
                                </div>
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input id="package-cname"  class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail Address</label>
                                    <input id="package-email" class="form-control"  type="email">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone Number</label>
                                    <input id="package-phone" class="form-control" type="tel" >
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <input id="package-address" class="form-control" type="text" >
                                </div>
                                </br>
                                <button id="submit-address"class="ar-button">Submit</button>
                                <input type="hidden" name="package" id="package-id" value="">
                            </form>
                    </div>
                </div>

			  <div id="wcgb-checkout-modal" class="modal" style="display:none">
					<!-- Modal content -->
					<div class="modal-content" style="text-align:center">
						<span class="close">&times;</span>
						
						Are you sending this as a gift?
						If it is, would you like to add a card?
						<div></div>
						<?php  if(get_option( 'wcgb_greeting_id')){ ?>
							<a href="<?php echo esc_url( get_permalink( get_option( 'wcgb_greeting_id') ).'?op=1' ); ?>" class="button" > Yes, add card! </a>
						<?php } ?>
							<a href="<?php echo esc_url(wc_get_cart_url().'?op=1' ); ?>" class="button" > Go To Cart </a>

								
					</div>

				</div>

		<?php
	}
	// public function prevent_admin_access() {       

	// 	if ( is_admin() && !defined('DOING_AJAX') && ( 
	// 		current_user_can('usercrp') || current_user_can('userpcp') ||  
	// 		current_user_can('subscriber') || current_user_can('contributor') || 
	// 		current_user_can('editor'))) {
	// 		  session_destroy();
	// 		  wp_logout();
	// 		  wp_redirect( home_url() );
	// 		 exit;
	// 	}
	// }

	public function wcgb_store_address_to_item($item, $cart_item_key, $values, $order ){

		
		$package = isset($values['item_package']) ? $values['item_package'] : '';
		
		if( $package )	{
			
			$wcgb_address = WC()->session->get('wcgb_address');
			$wcgb_notes = WC()->session->get('wcgb_notes');

			$address = $wcgb_address[$package];
			$note = $wcgb_notes[$package];

			$item->update_meta_data('address', $address);
			$item->update_meta_data('package', $package);
			$item->update_meta_data('note', $note);


		}

	}

	public function wcgb_get_popup_parameter(){
		
		// if(is_admin() || wp_doing_ajax())
		// 	return;

		// $wcgb_packages = WC()->session->get('wcgb_packages');
		// $current_package = WC()->session->get('wcgb_current_package');
		// $wcgb_wraps = WC()->session->get('wcgb_wraps');
		// $wcgb_notes = WC()->session->get('wcgb_notes');
		// $wcgb_address = WC()->session->get('wcgb_address');
		// var_dump($wcgb_packages );
		// echo'---------------------------------------------';
		// var_dump($current_package );
		// echo'---------------------------------------------';
		// var_dump($wcgb_notes );
		// echo'---------------------------------------------';
		// var_dump($wcgb_address );

		if (isset($_GET['op']) && $_GET['op'] == 1) {
			
			WC()->session->set('wcgb_show_popup', 'false' );
		  } 
	}

	public function wcgb_reset_session($order_id){
		WC()->session->set('wcgb_packages', '' );
		WC()->session->set('wcgb_current_package',  '' );
	    WC()->session->set('wcgb_wraps', '');
		WC()->session->set('wcgb_address', '');
		WC()->session->set('wcgb_notes', '');
		WC()->session->set('wcgb_show_popup', 'true' );
		
	}

	public function wcgb_on_cancelled_order_reset_session($order_id){
		WC()->session->set('wcgb_packages', '' );
		WC()->session->set('wcgb_current_package',  '' );
	    WC()->session->set('wcgb_wraps', '');
		WC()->session->set('wcgb_address', '');
		WC()->session->set('wcgb_notes', '');
		WC()->session->set('wcgb_show_popup', 'true' );
		WC()->cart->empty_cart();
	}

	public function wcgb_add_new_box(){

		$wcgb_packages = WC()->session->get('wcgb_packages');
		$current_package = WC()->session->get('wcgb_current_package');
		$wcgb_wraps = WC()->session->get( 'wcgb_wraps');

		if(  !$wcgb_packages && !$current_package ){
			
			$gb_id = $this->get_lowest_cost_product('is_gift_box');
			$wrap_id = $this->get_lowest_cost_product('is_gift_wrap');

			WC()->session->set('wcgb_packages', [ 'package1' => ['product_id' => $gb_id,'cart_item_key' => '' ] ] );
			WC()->session->set( 'wcgb_wraps', [ 'package1' =>  ['product_id' => $wrap_id, 'cart_item_key' => '' ] ]);
			WC()->session->set('wcgb_current_package', 'package1' );
			WC()->session->set('wcgb_last_package', 'package1' );

			WC()->cart->add_to_cart( $gb_id ,1,  0,array(), array('update_package' => true , 'package' => 'package1'  ) );
			WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' => 'package1'  ) );



		}else{

			$currentPackageCount = count($wcgb_packages ) + 1;
			$current_package = 'package'.$currentPackageCount;
			
			$gb_id = $this->get_lowest_cost_product('is_gift_box');
			$wrap_id = $this->get_lowest_cost_product('is_gift_wrap');

			$wcgb_packages = array_merge($wcgb_packages, [  $current_package => ['product_id' => $gb_id,'cart_item_key' => '' ] ]);
			
			$wcgb_wraps = array_merge($wcgb_wraps, [  $current_package => ['product_id' => $wrap_id,'cart_item_key' => '' ] ]);

			WC()->session->set('wcgb_packages', $wcgb_packages );
			WC()->session->set('wcgb_current_package',  $current_package );
			WC()->session->set( 'wcgb_wraps', $wcgb_wraps );
			WC()->session->set('wcgb_last_package', $current_package );

			WC()->session->set('wcgb_new_package', 'true' );

			WC()->cart->add_to_cart( $gb_id ,1,  0,array(), array('update_package' => true , 'package' => $current_package) );
			WC()->cart->add_to_cart( $wrap_id ,1,  0,array(), array('update_package' => true , 'package' =>  $current_package  ) );


		}

		wp_send_json_success();
		wp_die();

	}

	public function wcgb_prevent_direct_checkout(){
		
		if ( is_checkout() ) {

	
			if (strpos($_SERVER['REQUEST_URI'], "order-received") !== false && isset( $_GET['key'] )  && $_GET['key'] != '' )
				return;
			
			if(isset( $_GET['gbid'] ) && $_GET['gbid'] != ''){
				
				$gbid = $_GET['gbid'];
				$cid = WC()->session->get('wcgb_cart_id');
				if($gbid != $cid){
					wp_redirect(  wc_get_page_permalink( 'cart' ) );
				}

			}else{
				wp_redirect(  wc_get_page_permalink( 'cart' ) );
			}
				
		}	
	}

	public function wcgb_logout_and_login(){
		return false;
	}
	

}
