<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<style>
    /* The Modal (background) */
.modal {
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

</style>


<script>
           function changeMiniCartGiftBox( package, new_gb_id, old_gb_id,old_gb_ckey ) {
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                var formData = {
                    package: package,
                    new_gb_id: new_gb_id,
                    old_gb_id: old_gb_id,
                    old_gb_ckey: old_gb_ckey
                };

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        formData: formData,
                        // security: check_ref,
                        dataType: "json",
                        encode: true,
                        action: 'wcgb_change_gb_in_cart'
                    },
                    error: function(response) {
                        console.log(response);
                    },
                    success: function(response) {
                        
                        if (response.success) {
                            window.location.href = "<?php echo  wc_get_page_permalink( 'cart' ) ?>";

                        } else {
                            
                        }
                    }
                });
            }

            function changeMiniCartGiftWrap( package, new_gw_id, old_gw_id,old_gw_ckey ) {
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                var formData = {
                    package: package,
                    new_gw_id: new_gw_id,
                    old_gw_id: old_gw_id,
                    old_gw_ckey: old_gw_ckey
                };

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        formData: formData,
                        // security: check_ref,
                        dataType: "json",
                        encode: true,
                        action: 'wcgb_change_gw_in_cart'
                    },
                    error: function(response) {
                        console.log(response);
                    },
                    success: function(response) {
                        
                        if (response.success) {
                            window.location.href = "<?php echo  wc_get_page_permalink( 'cart' ) ?>";

                        } else {
                            
                        }
                    }
                });
            }
    </script>

<?php

if (!isset($args) || !isset($args['list_class'])) {
	$args['list_class'] = '';
}
$cart_list_sub_classes = array();
$cart_list_sub_classes[] = 'cart_list_wrapper';
if ( ! WC()->cart->is_empty() ) {
	$cart_list_sub_classes[] = 'has-cart';
}
$cart_list_sub_class = implode(' ',$cart_list_sub_classes);
$opt_header_shopping_cart_button = g5plus_get_option('header_shopping_cart_button', array(
	'view-cart' => '1',
	'checkout' => '1',
));
?>

<div class="widget_shopping_cart_icon">
	<i class="wicon fa fa-shopping-cart"></i>
	<span class="total"><?php echo count( WC()->cart->get_cart()); ?></span>
</div>
<div class="sub-total-text"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
<div class="<?php echo esc_attr($cart_list_sub_class) ?>">

<?php
    if(! WC()->cart->is_empty() ){ 
			
        $wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			$wcgb_wraps = WC()->session->get('wcgb_wraps');
            $wcgb_last_package = WC()->session->get('wcgb_last_package' );
            $has_package = false;
            $showButtons = false;

            $cart_packages = $greeting_cards = [];
            
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
                        
                        if(get_post_meta( $product_id, 'is_greeting_card', true ) == 'true')
                            $greeting_cards[] = $product_id ;
                            
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
                    $has_package = true;
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
                                    
                                    <div class="gf-row-delete">
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
                                    
                                    </div>
                                    
                                </div>
                                <?php

                            }
                            $current_count = $package_count - $current_package_count;

                            if( !$is_have_product &&  $wcgb_last_package != $package){?>
                                <div class="gf-row">
                                    There is no itme in package!
                                    <button class="delete-package pd-button" data-package='<?php echo $package; ?>' data-gb-id = '<?php echo $package_product; ?>' data-gb-ckey= '<?php echo $gb_cart_item_key; ?>'  style="margin-left: 2%;margin-top: 0%;" >Delete package</button>
                                    <button class="delete-package hidden" data-package='<?php echo $package; ?>' data-gb-id = '<?php echo $package_product; ?>' data-gb-ckey= '<?php echo $gb_cart_item_key; ?>'  style="margin-left: 2%;margin-top: 0%;" >Delete package</button>
                                    
                                </div>
                            <?php } 
                            
                            if(!$is_have_product && $wcgb_last_package == $package ){

                                $url = wc_get_page_permalink( 'shop' );
                                echo '<div class="gf-row">';
                                echo "There is no itme in package! please add product in package";
                                echo '<a href="'.$url.'" class="pd-button" style="margin-left: 2%;margin-top: 0%;">Go to Shop</a>';
                                echo '</div>';
                                echo '<button class="delete-package hidden" data-package="'.$package.'" data-gb-id = "'. $package_product.'" data-gb-ckey= "'.$gb_cart_item_key.'"  style="margin-left: 2%;margin-top: 0%;" >Delete package</button>';
                                
                            
                            }

                            
                        if( $is_have_product && !$has_greeting && get_option( 'wcgb_greeting_id') &&  $wcgb_last_package == $package ){ ?>
                                <div class="gf-row">
                                    <a href="<?php echo esc_url( get_permalink( get_option( 'wcgb_greeting_id') ) ); ?>" class="button" > Add Greeting card! </a>
                                </div>
                            <?php } ?>

                        
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
                        
                        <div class="gf-row-delete">
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
                        
                        </div>
                        
                    </div>
                    <?php

                }
                echo '</div>';
           }
        }else{ ?>

            <ul class="cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
                <li class="empty">
                    <h4><?php esc_html_e( 'An empty cart', 'g5plus-handmade' ); ?></h4>
                    <p><?php esc_html_e( 'You have no item in your shopping cart', 'g5plus-handmade' ); ?></p>
                </li>
            </ul>
        
       <?php }?>


	<?php if ( ! WC()->cart->is_empty() ) : ?>
		<div class="cart-total">
			<p class="total"><strong><?php esc_html_e( 'Total', 'g5plus-handmade' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="buttons">
				<?php if (isset($opt_header_shopping_cart_button['view-cart']) && ($opt_header_shopping_cart_button['view-cart'] == '1')):?>
					<?php 
						$show_popup = WC()->session->get('wcgb_show_popup');
                        
						if( empty($greeting_cards) && $show_popup != 'false' && $has_package ){ ?>
							<script>

								jQuery(document).ready(function() {
								
									jQuery('body').on('click',"#wcgb-checkout-btn", function(e) {
									    e.preventDefault();
									    jQuery('#wcgb-checkout-modal').show();
									});

									jQuery('body').on('click',"#wcgb-checkout-modal .close", function(e) {
                                        e.preventDefault();
                                        jQuery('#wcgb-checkout-modal').hide();
									
									});
									
								});
							</script>

                    		<button id="wcgb-checkout-btn" class="button wc-forward"><?php esc_html_e( 'View Cart', 'g5plus-handmade' ); ?></button>
                                
                    <?php } else{ ?>

                    	<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="button wc-forward"><?php esc_html_e( 'View Cart', 'g5plus-handmade' ); ?></a> 

					<?php } ?>

				<?php endif; ?>
				<!-- <?php if (isset($opt_header_shopping_cart_button['checkout']) && ($opt_header_shopping_cart_button['checkout'] == '1') && $showButtons ):?>
					<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button checkout wc-forward"><?php esc_html_e( 'Checkout', 'g5plus-handmade' ); ?></a>
				<?php endif; ?> -->
			</p>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>

            </div>
        </div>
    </div>
</section>
