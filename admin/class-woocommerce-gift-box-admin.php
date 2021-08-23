<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       dnyaneshmahajan.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Gift_Box
 * @subpackage Woocommerce_Gift_Box/admin
 * @author     Dnyanesh Mahajan <dnyaneshmahajan12@gmail.com>
 */
class Woocommerce_Gift_Box_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-gift-box-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-gift-box-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function wcgb_gift_product_metabox() {

        add_meta_box(
            'wcgb-product-meta-box',
            __( 'Gift Box Option', 'textdomain' ),
            array( $this, 'wcgb_gift_product_metabox_callback' ),
            'product',
            'side',
            'high'
        );
 
    }

	public function wcgb_gift_product_metabox_callback($post){ 

		$is_gift_box = (get_post_meta( $post->ID, 'is_gift_box', true ))? get_post_meta( $post->ID, 'is_gift_box', true ) : 'false';
		$is_gift_wrap = (get_post_meta( $post->ID, 'is_gift_wrap', true ))? get_post_meta( $post->ID, 'is_gift_wrap', true ) : 'false';
		$is_individual = (get_post_meta( $post->ID, 'is_individual', true ))? get_post_meta( $post->ID, 'is_individual', true ) : 'false';
		$is_greeting_card = (get_post_meta( $post->ID, 'is_greeting_card', true ))? get_post_meta( $post->ID, 'is_greeting_card', true ) : 'false';
		
		?>
			<div class="misc-pub-section">
				<label for="is-gb">Is Gift Box</label>
			
				<select id="is-gb" name="wcgb_is_gb" style="margin: 5px;">
					<option value="true" <?php selected(  $is_gift_box , 'true', true )?> >Yes</option>
					<option value="false" <?php selected(  $is_gift_box , 'false', true )?>  >No</option>
				</select>
			</div>
			
			<div class="misc-pub-section">
				<label for="is-gw">Is Gift Wrap</label>
			
				<select id="is-gw" name="wcgb_is_gw" style="margin: 5px;">
					<option value="true" <?php selected(  $is_gift_wrap , 'true', true )?>  >Yes</option>
					<option value="false" <?php selected(  $is_gift_wrap , 'false', true )?>  >No</option>
				</select>
			</div>

			<div class="misc-pub-section">
				<label for="is-gc">Is Greeting Card</label>
			
				<select id="is-gc" name="wcgb_is_gc" style="margin: 5px;">
					<option value="true" <?php selected(  $is_greeting_card , 'true', true )?>  >Yes</option>
					<option value="false" <?php selected(  $is_greeting_card , 'false', true )?>  >No</option>
				</select>
			</div>
			
			<div class="misc-pub-section">
				<label for="is-individual">Is Sold Individually</label>
				<select id="is-individual" name="wcgb_is_individual" style="margin: 5px;">
					<option value="true" <?php selected(  $is_individual , 'true', true )?>  >Yes</option>
					<option value="false" <?php selected(  $is_individual , 'false', true )?>  >No</option>
				</select>
			</div>

		<?php
	}

	public function wcgb_gift_product_save_metabox($post_id){
		
		if( isset($_POST['wcgb_is_gb']) && get_post_type( $post_id ) == 'product'){
			if($_POST['wcgb_is_gb'] == 'true'){
				update_post_meta($post_id,'is_gift_box',$_POST['wcgb_is_gb']);
			}else{
				delete_post_meta($post_id,'is_gift_box');
			}
		}

		if( isset($_POST['wcgb_is_gw']) && get_post_type( $post_id ) == 'product'){
			if($_POST['wcgb_is_gw'] == 'true'){
				update_post_meta($post_id,'is_gift_wrap',$_POST['wcgb_is_gw']);
			}else{
				delete_post_meta($post_id,'is_gift_wrap');
			}
		}

		if( isset($_POST['wcgb_is_individual']) && get_post_type( $post_id ) == 'product'){
			if($_POST['wcgb_is_individual'] == 'true'){
				update_post_meta($post_id,'is_individual',$_POST['wcgb_is_individual']);
			}else{
				delete_post_meta($post_id,'is_individual');
			}
		}

		if( isset($_POST['wcgb_is_gc']) && get_post_type( $post_id ) == 'product'){
			if($_POST['wcgb_is_gc'] == 'true'){
				update_post_meta($post_id,'is_greeting_card',$_POST['wcgb_is_gc']);
			}else{
				delete_post_meta($post_id,'is_greeting_card');
			}
		}
	
	
	}
	public function wcgb_gift_card_page_option($settings, $current_section ){
		
		//var_dump($settings);
		return array_merge($settings, array( array(
				'name'     => __( 'Greeeting Card Page ID', 'text-domain' ),
				'id'       => 'wcgb_greeting_id',
				'type'     => 'text',
				'desc'     => __( 'Enter greeting card page id whixh contain shortcode [greeting_cards]', 'text-domain' ),
			) ) );

	}

	public function display_admin_order_item_custom_button($item_id, $item, $product ){
		
		if($product){
			$product_id = $product->get_id();
			$is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
			if($is_gift_box){ 
				$address = $item->get_meta('address');
				echo "Address for ".$item->get_meta('package');
			?>

				<div class="view">
					<table cellspacing="0" class="display_meta">
								<tbody>
									<tr>
										<th>Full Name:</th>
										<td><p><?php echo $address['full_name']?></p></td>
									</tr>
									<tr>
										<th>Comapny Name:</th>
										<td><p><?php echo $address['comp_name']?></p></td>
									</tr>
									<tr>
										<th>Email:</th>
										<td><p><?php echo $address['email']?></p></td>
									</tr>
									<tr>
										<th>Phone:</th>
										<td><p><?php echo $address['phone']?></p></td>
									</tr>
									<tr>
										<th>Address:</th>
										<td><p><?php echo $address['address']?></p></td>
									</tr>
									<!-- <tr>
										<th>Simple Greeting Note:</th>
										<td><p><?php echo $item->get_meta('note')?></p></td>
									</tr> -->
								</tbody>
					</table>
				</div>

			<?php
				
				
				
				// echo "<br> : ".$address['full_name'];
				// echo "<br> : ".$address['comp_name'];
				// echo "<br> Email: ".$address['email'];
				// echo "<br> Phone: ".$address['phone'];
				// echo "<br> Address: ".$address['address'];
				//{ ["full_name"]=> string(16) "Dnyanesh Mahajan" ["comp_name"]=> string(4) "WebD" ["email"]=> string(27) "dnyaneshmahajan12@gmail.com" 
					//"phone"]=> string(11) "08087839448" ["address"]=> string(41) "Sant krupa, Main Road, Unchgaon, Kolhapur" }
			}

		}
	}
	
}
