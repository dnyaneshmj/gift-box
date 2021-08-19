<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>
          
    <style>
        .pack-n {
            font-size: 15px;
            color: #555;
        }
        .cb-row {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding: 15px 15px;
            background-color:#fbfbfb;
            }
        .cb-thumb {
            margin-right: 2%;
            display: flex;
        flex-direction: row;
        }
        .dd-select{background-color:transparent !important;}
        .dd-selected {padding: 4px 7px !important;}
        .dd-selected-text{margin-bottom:0px;line-height: 33px !important;}
        .dd-option{padding: 4px 7px !important;}
        .dd-option-text{margin-bottom:0px;line-height: 33px !important;}
        .cb-item-detail {
            margin-right: 16%;
            max-width:30%;
            width: 30%;
            display: flex;
        flex-direction: column;
        justify-content: normal;
        align-content: flex-start;
        }
        .item-cb-free {
            font-size: 20px;
        font-weight: 600;
        color: #333;
        }
        .item-cb {
            font-size: 20px;
        font-weight: 600;
        color: #333;
        }
        .cb-item-price {
            margin-left: 49%;
        }

        .pdthumb {width:100px;}
        .gf-row {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding: 15px 15px;
            }
        .gf-row:nth-child(2n) {background-color: #fff;}
        .gf-row:nth-child(2n+1) {background-color: #f0f8ff;}

        .gf-thumb {
            margin-right: 2%;
        }
        .gf-item-detail {
            margin-right: 16%;
            max-width:30%;
            width: 30%;
            display: flex;
        flex-direction: column;
        justify-content: normal;
        align-content: flex-start;
        }
        .gf-item-count {
            margin-right: 10%;
        }
        .gf-item-price {
            margin-right: 10%;
        }

        .quantity-minus, .quantity-plus {font-size: 32px;
        line-height: 25px;
        display: inline-block;
        width: 32px;
        height: 32px;
        background: #f1eeeb;
        text-align: center;vertical-align: middle;}

        .item-title {
            font-size: 18px;
            color: #333;
        }
        .item-sku {
            font-size: 13px;
            color: #333; margin-bottom: 0;
        }
        .gf-row-delete .fa-trash{padding: 5px 0;
        font-size: 16px;
        color: red;}
        .package-gb-cont{width: 85%;margin: 0 auto; border: 1px dashed #ddd; padding: 10px 10px;}
        .item-price {
            font-size: 20px;
        font-weight: 600;
        color: #333;
        }
        .cb-pack-con {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }
        .cb-pack-rec {
            margin-left: 10%; cursor: pointer;
        }
        .btnOpenForm {
            font-size: 15px;
            padding: 3px 3px;
            border: 1px dashed #bbb;
            width: max-content;
        }
        .ar-button {
            background-color: #000;
            border: none;
            padding: 9px 25px;
            border-radius: 2px;
            font-size: 13px;
            color: #fff;
            text-transform: uppercase;
            margin-top: 30px;
        }
        .anr-button {
            background-color: #000;
            border: none;
            padding: 9px 25px;
            border-radius: 2px;
            font-size: 13px;
            color: #fff;
            text-transform: uppercase;
            margin-top: 30px;
            float: right;
            margin-right: 8%;
        }
        .pd-button {
            background-color: #000;
            border: none;
            padding: 9px 25px;
            border-radius: 2px;
            font-size: 13px;
            color: #fff;
            text-transform: uppercase;
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 1rem;
            width: 48%;
            float: left;
            margin: 0 12px 0 0px;
        }
        .form-popup-bg {
        position:absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-content: center;
        justify-content: center;
        }
        .form-popup-bg {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(94, 110, 141, 0.9);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
        -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
        transition: opacity 0.3s 0s, visibility 0s 0.3s;
        overflow-y: auto;
        z-index: 10000;
        }
        .form-popup-bg.is-visible {
        opacity: 1;
        visibility: visible;
        -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
        -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
        transition: opacity 0.3s 0s, visibility 0s 0s;
        }
        .form-container {
            background-color:#bfbfbf;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            position:relative;
        padding: 40px;
        color: #fff;
        }
        .close-button {
        background:none;
        color: #fff;
        width: 40px;
        height: 40px;
        position: absolute;
        top: 0;
        right: 0;
        border: solid 1px #fff;
        }

        .form-popup-bg:before{
            content:'';
            background-color: #fff;
            opacity: .25;
            position:absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
    </style>     
    
    <script>
           function changeGiftBox( package, new_gb_id, old_gb_id,old_gb_ckey ) {
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

            function changeGiftWrap( package, new_gw_id, old_gw_id,old_gw_ckey ) {
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
<!-- </div> -->
<section class="product-table">  
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <?php do_action( 'woocommerce_before_cart_table' ); ?>
                    <?php   
                    echo '<input type="submit" name="clear_cart" value="Clear Cart">';
                    echo ' ( temporary button for clearing packages ) ';

                    if( isset($_POST['clear_cart']) ){
                            WC()->cart->empty_cart();
                            WC()->session->set('wcgb_packages', '' );
                            WC()->session->set('wcgb_current_package',  '' );
                           WC()->session->set('wcgb_wraps', '');
                    }
                            
                        $wcgb_packages = WC()->session->get('wcgb_packages');
                        $current_package = WC()->session->get('wcgb_current_package');
                        $wcgb_wraps = WC()->session->get('wcgb_wraps');
                        //var_dump(WC()->session);
                        
                        $showButtons = false;

                            if( isset($_POST['new_box']) ){

                                if(  !$wcgb_packages && !$current_package ){
	
                                    WC()->session->set('wcgb_packages', [ 'package1' => ['product_id' => 'new','cart_item_key' => '' ] ] );
                                    WC()->session->set('wcgb_current_package', 'package1' );
                    
                                }else{
                                    $currentPackageCount = count($wcgb_packages ) + 1;
                                    $current_package = 'package'.$currentPackageCount;
                                    $wcgb_packages = array_merge($wcgb_packages, [  $current_package => ['product_id' => 'new','cart_item_key' => '' ] ]);
    
                                    WC()->session->set('wcgb_packages', $wcgb_packages );
                                    WC()->session->set('wcgb_current_package',  $current_package );
                                    WC()->session->set('wcgb_new_package', 'true' );
                                }

                                
                            }
                            //var_dump($wcgb_packages); 
                            //var_dump($current_package); 
                            //die;
                        
                            $cart_packages = [];

                            foreach($wcgb_packages as $package => $data){

                                //var_dump($package);
                                $package_product = $data['product_id'];

                                $packages_product = [];
                                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                    
                                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                    $item_package = $cart_item['item_package'];
                    
                                    $is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
                                    $is_gift_wrap = get_post_meta( $product_id, 'is_gift_wrap', true );

                                    if( $package == $item_package && $package_product != $product_id && $is_gift_box != 'true' && $is_gift_wrap != 'true' ){
                                        
                                        if($current_package == $package && $package_product != 'new' )
                                                $showButtons = true;
                                        
                                        $packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product,  'cart_item_key' => $cart_item_key, 'cart_item' => $cart_item ];
                                    //$packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product, 'item_package' => $item_package ];
                                    }
                                }
                                $cart_packages = array_merge($cart_packages, $packages_product);

                            }
                           // var_dump($cart_packages); die;

                        

                            $gift_box_public = new Woocommerce_Gift_Box_Public('',1.0);
                            
                            $giftBoxes = $gift_box_public->wcgb_get_gift_box_options();
                            $giftWraps = $gift_box_public->wcgb_get_gift_wrap_options();
                            

                            foreach($wcgb_packages as $package => $data){ 
                                //echo $package;
                                //echo get_the_title( $package_product );
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
        
                                        <div class="cb-pack-rec">
                                            <h1 class="btnOpenForm" data-package="<?php echo $package; ?>">Add Receipient</h1>

                                        </div>
                                    </div>
                                
                                    <div class="cb-row">
      
                                        <div class="cb-thumb">
                                            <img src="<?php echo get_the_post_thumbnail_url($package_product); ?>" class="pdthumb">
                                        </div>

                                        <div id="demo-htmlselect" class="dd-container" style="width: 260px;">
                                                <!-- <div class="dd-select" style="width: 260px; background: rgb(238, 238, 238);">
                                                    <input class="dd-selected-value" type="hidden" value="1">
                                                    <a class="dd-selected">
                                                        <img class="dd-selected-image" src="<?php echo get_the_post_thumbnail_url($package_product); ?>">
                                                        <label class="dd-selected-text" style="line-height: 51px;"><?php echo get_the_title( $package_product ); ?></label>
                                                    </a>
                                                    <span class="dd-pointer dd-pointer-down"></span>
                                                </div> -->

                                                <select class='giftbox-dropdown-<?php echo $package; ?>' data-package='<?php echo $package; ?>' data-old-gb = '<?php echo $package_product; ?>' data-old-ckey= '<?php echo $gb_cart_item_key; ?>' >
                                                    <?php 
                                                        foreach ($giftBoxes as $box ) {
                                                            $_product = wc_get_product( $box );
                                                            $price = ( $_product->get_price() != 0)? get_woocommerce_currency_symbol().''.$_product->get_price() : 'Free';
                                                            echo '<option value="'.$box.'" '.selected( $package_product, $box, false).' data-imagesrc="'.get_the_post_thumbnail_url($box).'" data-description="'.$price.'" > '.get_the_title( $box ).'</option>';
                                                        }

                                                    ?>
                                                </select>    
                                                <script>
                                                    jQuery(document).ready(function(e) {
                                                        jQuery('.giftbox-dropdown-<?php echo $package; ?>').ddslick({
                                                                onSelected: function(selectedData){
                                                                    var package = jQuery( selectedData.original ).data('package');
                                                                    var old_gb_id = jQuery( selectedData.original ).data('old-gb');
                                                                    var old_gb_ckey = jQuery( selectedData.original ).data('old-ckey');

                                                                    var new_gb_id =  selectedData.selectedData.value;
                                                                    
                                                                    if(new_gb_id != old_gb_id ){    
                                                                        changeGiftBox(package, new_gb_id, old_gb_id, old_gb_ckey );
                                                                    }
                                                                    
                                                                }   
                                                            });
                                                    });
                                            
                                                </script>




                                        </div>
                                            
                                       
                                    
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
                                    $is_have_product = false;
                                        foreach ( $cart_packages as $product ) {
                                            
                                            if($product['package'] != $package ) continue;
                                            
                                            $is_have_product = true; 

                                            $cart_item = $product['cart_item'];
                                            $cart_item_key  = $product['cart_item_key'];

                                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                            ?>

                                            <div class="gf-row">
                                                
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
                                                
                                                <div class="gf-item-count">
                                                    <!-- <span class="quantity-minus">-</span>
                                                    <span class="quantity-count" style="vertical-align: middle;">2</span>
                                                    <span class="quantity-plus">+</span> -->
                                                    <?php
                                                        if ( $_product->is_sold_individually() ) {
                                                            $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                        } else {
                                                            $product_quantity = woocommerce_quantity_input( array(
                                                                'input_name'    => "cart[{$cart_item_key}][qty]",
                                                                'input_value'   => $cart_item['quantity'],
                                                                'max_value'     => $_product->get_max_purchase_quantity(),
                                                                'min_value'     => '0',
                                                                'product_name'  => $_product->get_name(),
                                                            ), $_product, false );
                                                        }

                                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
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
                                        if(!$is_have_product){
                                            $url = wc_get_page_permalink( 'shop' );
                                            echo '<div class="gf-row">';
                                            echo "There is no itme in package! please add product in package";
                                            echo '<a href="'.$url.'" class="pd-button" style="margin-left: 2%;margin-top: 0%;">Go to Shop</a>';
                                            echo '</div>';
                                        }
                                    
                                    ?>
                                    Gift Wrap
                                    <div class="cb-row">
                                        
                                        <div class="cb-thumb">    
                                           <img src="<?php echo get_the_post_thumbnail_url($gift_wrap); ?>" class="pdthumb">
                                        </div>
                                        
                                        <div id="demo-giftwrap" class="dd-container" style="width: 260px;">
                                            <select class='giftwrap-dropdown-<?php echo $package; ?>' data-package='<?php echo $package; ?>' data-old-gw = '<?php echo $gift_wrap; ?>' data-old-ckey= '<?php echo $gw_cart_item_key; ?>' >  
                                                <?php 
                                                
                                                    foreach ($giftWraps as $wrap ) {
                                                        $_product = wc_get_product( $wrap );
                                                        $price = ( $_product->get_price() != 0)? get_woocommerce_currency_symbol().''.$_product->get_price() : 'Free';
                                                        echo '<option value="'.$wrap.'" '.selected( $gift_wrap, $wrap, false).' data-imagesrc="'.get_the_post_thumbnail_url($wrap).'" data-description="'.$price.'" > '.get_the_title( $wrap ).'</option>';
                                                    }

                                                ?>
                                            </select>    
                                                    <script>
                                                        jQuery(document).ready(function(e) {
                                                            jQuery('.giftwrap-dropdown-<?php echo $package; ?>').ddslick({
                                                                    onSelected: function(selectedData){
                                                                        var package = jQuery( selectedData.original ).data('package');
                                                                        var old_gw_id = jQuery( selectedData.original ).data('old-gw');
                                                                        var old_gw_ckey = jQuery( selectedData.original ).data('old-ckey');

                                                                        var new_gw_id =  selectedData.selectedData.value;
                                                                        
                                                                        if(new_gw_id != old_gw_id ){    
                                                                            changeGiftWrap(package, new_gw_id, old_gw_id, old_gw_ckey );
                                                                        }
                                                                        
                                                                    }   
                                                                });
                                                        });
                                                
                                                    </script>
                                            
                                        </div>
                                        
                                        <div class="cb-item-price">
                                            <?php 
                                                
                                                $_product = wc_get_product( $gift_wrap ); 
                                                if($_product){
                                                    echo( $_product->get_price() != '0' )?  '<h2 class="item-cb"> $<span>'.$_product->get_price().'</span> </h2>' : '<h2 class="item-cb-free"> FREE</h2>';
                                                }
                                            
                                            ?>
                                        </div>
                                        
                                                
                                    </div>

                                    Simple Greeting Note
                                    <div class="cb-row">
                                        
                                        <div class="cb-thumb">    
                                        Simple Greeting Note
                                           <!-- <img src="<?php echo get_the_post_thumbnail_url($gift_wrap); ?>" class="pdthumb"> -->
                                        </div>
                                        
                                        <div id="demo-giftwrap" class="dd-container" style="width: 260px;">
                                            <textarea class="package-note" rows="4" cols="50" ></textarea>
                                            
                                        </div>
                                        
                                        <div class="cb-item-price">
                                            <?php echo '<h2 class="item-cb-free"> FREE</h2>'; ?>
                                        </div>
                                        
                                                
                                    </div>

                                    <div class="pack-rb">
                                        <button class="delete-package pd-button" data-package='<?php echo $package; ?>' data-gb-id = '<?php echo $package_product; ?>' data-gb-ckey= '<?php echo $gb_cart_item_key; ?>'  >Delete package</button>
                                    </div>
                                </div>
                            <?php } ?>
                        
                        <div class="package-gb-cont">
                            <?php if ( wc_coupons_enabled() ) { ?>
                                <div class="coupon">
                                    <label for="coupon_code"><?php _e( 'Coupon:', 'g5plus-handmade' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'g5plus-handmade' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'g5plus-handmade' ); ?>" />
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            <?php } ?>

                            <input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'g5plus-handmade' ); ?>" />
                            
                            <?php if($showButtons ) { ?>
                                <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                                <input type="submit" class="button" name="new_box" value="<?php esc_attr_e( 'Add New Box', 'g5plus-handmade' ); ?>" />
                            <?php }?>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>

                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>

                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>


                <div class="form-popup-bg">
                    <div class="form-container">
                        <button id="btnCloseForm" class="close-button">X</button>
                            <p>Recipient Delivery Details</p>
                            <form id="wcgb-user-address">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" name="delivery-package" value="">
                                </div>
                                <div class="form-group">
                                    <label for="">Company Name</label>
                                    <input class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail Address</label>
                                    <input class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone Number</label>
                                    <input class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <input class="form-control" type="text">
                                </div>
                                </br>
                                <button id="submit-address"class="ar-button">Submit</button>
                                <input type="hidden" name="package" id="package-id" value="">
                            </form>
                    </div>
                </div>
                <script>
                        /* Contact Form Interactions */
                        jQuery('.btnOpenForm').on('click', function(event) {
                            event.preventDefault();
                            
                            jQuery('.form-popup-bg').addClass('is-visible');
                            var package = jQuery(this).data('package');
                            jQuery('.form-popup-bg #package-id').val(package);
                        });
                    
                        //close popup when clicking x or off popup
                        jQuery('.form-popup-bg').on('click', function(event) {
                            if (jQuery(event.target).is('.form-popup-bg') || jQuery(event.target).is('#btnCloseForm')) {
                                event.preventDefault();
                                jQuery(this).removeClass('is-visible');
                            }
                        });

                        jQuery('#submit-address')
                </script>

            </div>
        </div>
    </div>
</section>

<script>

 

    function closeForm() {
        jQuery('.form-popup-bg').removeClass('is-visible');
    }

    jQuery(document).ready(function($) {

        jQuery('.delete-package').on('click', function(e) {

            e.preventDefault();
            var package = jQuery(this).data('package');
            var gb_key = jQuery(this).data('gb-ckey');
            var gb_id = jQuery(this).data('gb-id');

            var products = [];
            jQuery(".delete-"+package).map(function() {
                products.push(jQuery(this).data('cikey'));
            }).get();

            console.log(products);

            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
            var formData = {
                package: package,
                gb_key: gb_key,
                products: products,
            };

            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    formData: formData,
                    // security: check_ref,
                    dataType: "json",
                    encode: true,
                    action: 'wcgb_remove_pkg_from_cart'
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
            
        
        });    

    });

</script>
    
<script>
    // jQuery('.giftbox-dropdown').on('hover', function(e){
    //     jQuery(this).ddslick({
    //         onSelected: function(selectedData){
    //             //callback function: do something with selectedData;
    //         }   
    //     });
    // })


    // jQuery('#demo-giftwrap').ddslick({
    //     onSelected: function(selectedData){
    //         //callback function: do something with selectedData;
    //     }   
    // });
</script>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
<div class="cart-collaterals">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
