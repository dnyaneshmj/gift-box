<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'g5plus-handmade' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'g5plus-handmade' ) . '</a>' );
//wc_print_notice( $info_message, 'notice' );
?>
<?php if (empty( WC()->cart->applied_coupons )): ?>
<div class="woocommerce-checkout-info">
	<?php echo wp_kses_post($info_message); ?>
</div>
<?php endif; ?>
<form class="checkout_coupon" method="post" style="display:none">

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'g5plus-handmade' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'g5plus-handmade' ); ?>" />
	</p>

	<div class="clear"></div>
</form>

<?php

if(! WC()->cart->is_empty() ){ 
			$wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			$wcgb_wraps = WC()->session->get('wcgb_wraps');
            $showButtons = false;

            $cart_packages = [];
            if( !empty($wcgb_packages) ) {
            
                foreach($wcgb_packages as $package => $data){

                    //var_dump($package);
                    if( empty( $data )) continue;

                    $package_product = $data['product_id'];

                    $packages_product = [];
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        $item_package = $cart_item['item_package'];
        
                        $is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
                        $is_gift_wrap = get_post_meta( $product_id, 'is_gift_wrap', true );
                        $is_individual = get_post_meta( $product_id, 'is_individual', true );

                        if( $package == $item_package && $package_product != $product_id && $is_gift_box != 'true' && $is_gift_wrap != 'true' && $is_individual != 'true'  ){
                            
                            //if($current_package == $package && $package_product != 'new' )
                            //$showButtons = false;
                            
                            $packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product,  'cart_item_key' => $cart_item_key, 'cart_item' => $cart_item ];
                        //$packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product, 'item_package' => $item_package ];
                        }
  
                    }
                    $cart_packages = array_merge($cart_packages, $packages_product);

                }

                $gift_box_public = new Woocommerce_Gift_Box_Public('Woocommerce_Gift_Box',1.0);
                                
                $miniGiftBoxes = $gift_box_public->wcgb_get_gift_box_options();
                $miniGiftWraps = $gift_box_public->wcgb_get_gift_wrap_options();
                
                $package_count = count( $wcgb_packages );
                $current_package_count = 1;
                $showButtons = false;

                foreach($wcgb_packages as $package => $data){ 

                    if( !isset($data['product_id']) && $data['product_id'] == '' ) {
                        $current_package_count++;
                        continue;
                    }
                   

                    $showButtons = true;
                    $package_product = $data['product_id'];
                     $gb_cart_item_key = $data['cart_item_key'];
                    
                    $gift_wrap = $gw_cart_item_key = '';

                    if($wcgb_wraps[$package]){
                        $wrap_data = $wcgb_wraps[$package];
                        $gift_wrap = $wrap_data['product_id'];
                        $gw_cart_item_key = $wrap_data['cart_item_key'];
                    }


                    ?>
                    <div class="package-gb-cont">
                        
                        <div class="cb-pack-con">
                            <div class="cb-pack-info"> 
                                <h3 class="pack-n"> <?php echo $package; ?></h3>
                            </div>
                        </div>
                    
                        <div class="cb-row">

                            <div class="cb-thumb">
                                <img src="<?php echo get_the_post_thumbnail_url($package_product); ?>" class="pdthumb">
                            </div>

                    
                            <select class='mini-giftbox-dropdown-<?php echo $package; ?>' data-package='<?php echo $package; ?>' data-old-gb = '<?php echo $package_product; ?>' data-old-ckey= '<?php echo $gb_cart_item_key; ?>' >
                                <?php 
                                    foreach ($miniGiftBoxes as $box ) {
                                        $_product = wc_get_product( $box );
                                        $price = ( $_product->get_price() != 0)? get_woocommerce_currency_symbol().''.$_product->get_price() : 'Free';
                                        echo '<option value="'.$box.'" '.selected( $package_product, $box, false).' data-imagesrc="'.get_the_post_thumbnail_url($box).'" data-description="'.$price.'" > '.get_the_title( $box ).'</option>';
                                    }

                                ?>
                            </select>    
                            <script>
                                jQuery(document).ready(function(e) {
                                    jQuery('.mini-giftbox-dropdown-<?php echo $package; ?>').ddslick({
                                            onSelected: function(selectedData){
                                                var package = jQuery( selectedData.original ).data('package');
                                                var old_gb_id = jQuery( selectedData.original ).data('old-gb');
                                                var old_gb_ckey = jQuery( selectedData.original ).data('old-ckey');

                                                var new_gb_id =  selectedData.selectedData.value;
                                                
                                                if(new_gb_id != old_gb_id ){    
                                                    changeMiniCartGiftBox(package, new_gb_id, old_gb_id, old_gb_ckey );
                                                }
                                                
                                            }   
                                        });
                                });
                        
                            </script>
                                
                        <div class="cb-item-price">
                            <?php 
                                
                                    $_product = wc_get_product( $package_product ); 
                                    if($_product){
                                        echo( $_product->get_price() != '0' )?  '<h2 class="item-cb"> $<span>'.$_product->get_price().'</span> </h2>' : '<h2 class="item-cb-free"> FREE</h2>';
                                    }
                                
                            ?>
                        </div>
                        
                                
                    </div>

                        <?php 
                        $is_have_product = $has_greeting = false;
                            foreach ( $cart_packages as $product ) {
                                
                                if($product['package'] != $package ) continue;
                                
                                $is_have_product = true; 

                                $cart_item = $product['cart_item'];
                                $cart_item_key  = $product['cart_item_key'];

                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                
                                $has_greeting = (get_post_meta( $product_id, 'is_greeting_card', true ) == 'true')? true : false;

                                ?>

                                <div class="gf-row woocommerce-cart-form__cart-item cart_item">
                                    
                                    <div class="gf-thumb">
                                    <!-- <img src="Bookblock-Florists-pink-spray-large-arrangement-main.jpg" class="pdthumb"> -->
                                        <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( $thumbnail );
                                            } else {
                                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
                                            }
                                        ?>
                                    </div>
                                    
                                    <div class="gf-item-detail">
                                    <!-- <h2 class="item-title"> Flowers Lily </h2> -->
                                            <?php
                                                if ( ! $product_permalink ) {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                                } else {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="item-title" >%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                }

                                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                // Meta data.
                                                echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                                // Backorder notification
                                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'g5plus-handmade' ) . '</p>' ) );
                                                }
                                            ?>
                                    <p class="item-sku"> <b>SKU :</b> <span> ER345</span> </p>
                                    </div>
                                    
                                    <div class="gf-item-count product-quantity">
                                        <!-- <span class="quantity-minus">-</span>
                                        <span class="quantity-count" style="vertical-align: middle;">2</span>
                                        <span class="quantity-plus">+</span> -->
                                        <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                                    </div>
                                    
                                    <div class="gf-item-price">
                                    <h2 class="item-price"> 
                                        <!-- $<span>150 </span>  -->
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                        ?>
                                        </h2>
                                    </div>
                                    
                                    <!-- <div class="gf-row-delete">
                                    <?php
                                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                                '<a href="%s" class="cart-remove %s" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cikey="%s"><i class="fa fa-trash"></i></a>',
                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                'delete-'.$product['package'],
                                                __( 'Remove this item', 'g5plus-handmade' ),
                                                esc_attr( $product_id ),
                                                esc_attr( $_product->get_sku() ),
                                                $cart_item_key
                                            ), $cart_item_key );
                                        ?>
                                    
                                    </div> -->
                                    
                                </div>
                                <?php

                            }
                            $current_count = $package_count - $current_package_count;

                             ?>

                        
                        Gift Wrap
                        <div class="cb-row">
                            
                            <div class="cb-thumb">    
                                <img src="<?php echo get_the_post_thumbnail_url($gift_wrap); ?>" class="pdthumb">
                            </div>
                            
                            
                            <input type="hidden" id="gw-ckey-<?php echo $package; ?>" value= '<?php echo $gw_cart_item_key; ?>'>
                            <select class='mini-giftwrap-dropdown-<?php echo $package; ?>' data-package='<?php echo $package; ?>' data-old-gw = '<?php echo $gift_wrap; ?>' data-old-ckey= '<?php echo $gw_cart_item_key; ?>' >  
                                <?php 
                                
                                    foreach ($miniGiftWraps as $wrap ) {
                                        $_product = wc_get_product( $wrap );
                                        $price = ( $_product->get_price() != 0)? get_woocommerce_currency_symbol().''.$_product->get_price() : 'Free';
                                        echo '<option value="'.$wrap.'" '.selected( $gift_wrap, $wrap, false).' data-imagesrc="'.get_the_post_thumbnail_url($wrap).'" data-description="'.$price.'" > '.get_the_title( $wrap ).'</option>';
                                    }

                                ?>
                            </select>    
                            <script>
                                jQuery(document).ready(function(e) {
                                    jQuery('.mini-giftwrap-dropdown-<?php echo $package; ?>').ddslick({
                                            onSelected: function(selectedData){
                                                var package = jQuery( selectedData.original ).data('package');
                                                var old_gw_id = jQuery( selectedData.original ).data('old-gw');
                                                var old_gw_ckey = jQuery( selectedData.original ).data('old-ckey');

                                                var new_gw_id =  selectedData.selectedData.value;
                                                
                                                if(new_gw_id != old_gw_id ){    
                                                    changeMiniCartGiftWrap(package, new_gw_id, old_gw_id, old_gw_ckey );
                                                }
                                                
                                            }   
                                        });
                                });
                        
                            </script>
                                
                    
                            
                            <div class="cb-item-price">
                                <?php 
                                    
                                    $_product = wc_get_product( $gift_wrap ); 
                                    if($_product){
                                        echo( $_product->get_price() != '0' )?  '<h2 class="item-cb"> $<span>'.$_product->get_price().'</span> </h2>' : '<h2 class="item-cb-free"> FREE</h2>';
                                    }
                                
                                ?>
                            </div>
                            
                                    
                        </div>
                        

                    </div>
                    <?php 
                    
                    $current_package_count++;

                } 
            }

            $individual_products = [];
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                $is_individual = get_post_meta( $product_id, 'is_individual', true );
                if($is_individual == 'true' ){
                    $individual_products[] = [ 'cart_item_key' => $cart_item_key, 'cart_item' => $cart_item ];
                }

            }
           
           if(!empty($individual_products)){
                echo 'Individual Product
                        <div class="package-gb-cont individual">';
                foreach ( $individual_products as $product ) {
                            

                    $is_have_product = true; 

                    $cart_item = $product['cart_item'];
                    $cart_item_key  = $product['cart_item_key'];

                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    ?>

                    <div class="gf-row woocommerce-cart-form__cart-item cart_item">
                        
                        <div class="gf-thumb">
                        <!-- <img src="Bookblock-Florists-pink-spray-large-arrangement-main.jpg" class="pdthumb"> -->
                            <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                if ( ! $product_permalink ) {
                                    echo wp_kses_post( $thumbnail );
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
                                }
                            ?>
                        </div>
                        
                        <div class="gf-item-detail">
                        <!-- <h2 class="item-title"> Flowers Lily </h2> -->
                                <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="item-title" >%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                    // Meta data.
                                    echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                    // Backorder notification
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'g5plus-handmade' ) . '</p>' ) );
                                    }
                                ?>
                        <p class="item-sku"> <b>SKU :</b> <span> ER345</span> </p>
                        </div>
                        
                        <div class="gf-item-count product-quantity">
                            <!-- <span class="quantity-minus">-</span>
                            <span class="quantity-count" style="vertical-align: middle;">2</span>
                            <span class="quantity-plus">+</span> -->
                            <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                            'min_value'    => '0',
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                ?>
                        </div>
                        
                        <div class="gf-item-price">
                        <h2 class="item-price"> 
                            <!-- $<span>150 </span>  -->
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            ?>
                            </h2>
                        </div>
                        
                        <!-- <div class="gf-row-delete">
                        <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="cart-remove %s" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cikey="%s"><i class="fa fa-trash"></i></a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    'delete-'.$product['package'],
                                    __( 'Remove this item', 'g5plus-handmade' ),
                                    esc_attr( $product_id ),
                                    esc_attr( $_product->get_sku() ),
                                    $cart_item_key
                                ), $cart_item_key );
                            ?>
                        
                        </div> -->
                        
                    </div>
                    <?php

                }
                echo '</div>';
           }
        }