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
			$wcgb_packages = WC()->session->get('wcgb_packages');
			$current_package = WC()->session->get('wcgb_current_package');
			$wcgb_wraps = WC()->session->get('wcgb_wraps');
            $showButtons = false;

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

			$gift_box_public = new Woocommerce_Gift_Box_Public('',1.0);
                            
			$giftBoxes = $gift_box_public->wcgb_get_gift_box_options();
			$giftWraps = $gift_box_public->wcgb_get_gift_wrap_options();

			$greeting_cards = [];
            foreach($wcgb_packages as $package => $data){

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
                <!-- <ul class="cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>"> -->
						<!-- <li>  -->
								<div class="cb-pack-con">
									<div class="cb-pack-info"> 
										<h3 class="pack-n"> <?php echo $package; ?></h3>
									</div>

									<div class="cb-pack-rec">
										<?php 
											$label = 'Add Receipient';
											$wcgb_address = WC()->session->get('wcgb_address');
											if(isset( $wcgb_address[$package] ) ){
												$address = $wcgb_address[$package];
												$label =  $address['full_name'];
											}
										
										?>
										<h1 class="btnOpenForm" data-package="<?php echo $package; ?>"><?php echo $label ?></h1>

									</div>
								</div>
							
								<div class="cb-row">

									<!-- <div class="cb-thumb">
										<img src="<?php echo get_the_post_thumbnail_url($package_product); ?>" class="pdthumb">
									</div> -->

									<div id="demo-htmlselect" class="dd-container" style="width: 100%;">
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
															width:200,
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
										
								<!-- <div class="cb-item-price">
									<?php 
										
											$_product = wc_get_product( $package_product ); 
											if($_product){
												echo( $_product->get_price() != '0' )?  '<h2 class="item-cb"> $<span>'.$_product->get_price().'</span> </h2>' : '<h2 class="item-cb-free"> FREE</h2>';
											}
										
									?>
								</div> -->
							</div>
						<!-- </li> -->
						<?php 
						$is_have_product = false;
						foreach ( $cart_packages as $product ) {
									
							if($product['package'] != $package ) continue;

							$is_have_product = true; 

							$cart_item = $product['cart_item'];
							$cart_item_key  = $product['cart_item_key'];

							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							
                            $is_greeting_card = get_post_meta( $product_id, 'is_greeting_card', true );
							($is_greeting_card)? array_push($greeting_cards,  $package ) : '';

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
								$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>


								<div class="gf-row">
									<div class="cart-left">
										<?php if ( ! $_product->is_visible() ) { ?>
											<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
										<?php } else { ?>
											<a href="<?php echo get_permalink( $product_id ); ?>">
												<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) ?>
											</a>
										<?php } ?>
									</div>
									<div class="cart-right">
										<?php if ( ! $_product->is_visible() ) { ?>
											<?php echo esc_html($product_name); ?>
										<?php } else { ?>
											<a href="<?php echo get_permalink( $product_id ); ?>">
												<?php echo esc_html($product_name); ?>
											</a>
										<?php } ?>
										<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
			
										<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
										<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="mini-cart-remove %s" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cikey="%s"><i class="pe-7s-close-circle"></i></a>',
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
								<!-- <li> -->
									
								<!-- </li> -->
							<?php
							}

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
						
						<div id="demo-giftwrap" class="dd-container">
							<input type="hidden" id="gw-ckey" value= '<?php echo $gw_cart_item_key; ?>'>
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
													width:200,
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
						

						
								
					</div>
                <!-- </ul> -->
					<div class="pack-rb">
                        <button class="delete-package pd-button" data-package='<?php echo $package; ?>' data-gb-id = '<?php echo $package_product; ?>' data-gb-ckey= '<?php echo $gb_cart_item_key; ?>'  >Delete package</button>
                	</div>
				</div>
                    
                <?php 

            }
           
?>

	<ul class="cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
		<?php if ( ! WC()->cart->is_empty() ) : ?>

			<!-- <?php
            do_action( 'woocommerce_before_mini_cart_contents' );
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<li>
						<div class="cart-left">
							<?php if ( ! $_product->is_visible() ) { ?>
								<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
							<?php } else { ?>
								<a href="<?php echo get_permalink( $product_id ); ?>">
									<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) ?>
								</a>
							<?php } ?>
						</div>
						<div class="cart-right">
                            <?php if ( ! $_product->is_visible() ) { ?>
                                <?php echo esc_html($product_name); ?>
                            <?php } else { ?>
                                <a href="<?php echo get_permalink( $product_id ); ?>">
                                    <?php echo esc_html($product_name); ?>
                                </a>
                            <?php } ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

							<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                            <?php
                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                '<a href="%s" class="mini-cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="pe-7s-close-circle"></i></a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                __( 'Remove this item', 'g5plus-handmade' ),
                                esc_attr( $product_id ),
                                esc_attr( $_product->get_sku() )
                            ), $cart_item_key );
                            ?>
						</div>
					</li>
				<?php
				}
			}
			do_action( 'woocommerce_mini_cart_contents' );
			?> -->


		<?php else : ?>
			<li class="empty">
				<h4><?php esc_html_e( 'An empty cart', 'g5plus-handmade' ); ?></h4>
				<p><?php esc_html_e( 'You have no item in your shopping cart', 'g5plus-handmade' ); ?></p>
			</li>
		<?php endif; ?>

	</ul><!-- end product list -->

	<?php if ( ! WC()->cart->is_empty() ) : ?>
		<div class="cart-total">
			<p class="total"><strong><?php esc_html_e( 'Total', 'g5plus-handmade' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="buttons">
				<?php if (isset($opt_header_shopping_cart_button['view-cart']) && ($opt_header_shopping_cart_button['view-cart'] == '1')):?>
					<?php 
						$show_popup = WC()->session->get('wcgb_show_popup');
                        
						if( empty($greeting_cards) && $show_popup != 'false' ){ ?>
							<script>
								jQuery(document).ready(function() {
								
									jQuery('body').on('click',"#wcgb-checkout-btn", function(e) {
									e.preventDefault();

									    jQuery('#wcgb-checkout-modal').show();
                                        //jQuery.session.set("wcgb_show_popup", "false");
                                        
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


                <script>

                    // jQuery(document).ready(function() {

                       
                    //     jQuery('.save-note').on('click', function(event) {
                    //         event.preventDefault();

                    //         jQuery('html, body, .save-note').css("cursor", "wait"); 
                            
                    //         var package = jQuery(this).data('package');

                    //         var note = jQuery('textarea[data-package="'+package+'"]').val();
                           
                    //         var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                    //         var formData = {
                    //             package: package,
                    //             note: note
                    //         };
                
                    //         jQuery.ajax({
                    //             url: ajaxurl,
                    //             type: 'post',
                    //             data: {
                    //                 formData: formData,
                    //                 dataType: "json",
                    //                 encode: true,
                    //                 action: 'wcgb_save_note_of_package'
                    //             },
                    //             error: function(response) {
                    //                 console.log(response);
                    //             },
                    //             success: function(response) {
                                    
                    //                 if (response.success) {
  
                    //                 } else {
                                       
                    //                 }
                    //                 jQuery('html, body').css("cursor", "default");   
                    //                 jQuery('.save-note').removeAttr("style")
                                    
                                   
                    //             }
                                
                    //         });
                
                            
                            
                      
                    //     });

                    //     jQuery('.btnOpenForm').on('click', function(event) {
                    //         event.preventDefault();

                    //         jQuery('html, body, .btnOpenForm').css("cursor", "wait"); 
                          

                    //         var package = jQuery(this).data('package');
                    //         jQuery('.form-popup-bg #package-id').val(package);
                            
                            

                    //         var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                    //         var formData = {
                    //             package: package,
                    //         };
                
                    //         jQuery.ajax({
                    //             url: ajaxurl,
                    //             type: 'post',
                    //             data: {
                    //                 formData: formData,
                    //                 dataType: "json",
                    //                 encode: true,
                    //                 action: 'wcgb_get_address_of_package'
                    //             },
                    //             error: function(response) {
                    //                 console.log(response);
                    //             },
                    //             success: function(response) {
                                    
                    //                 if (response.success) {
                                        
                    //                     jQuery('#package-fname').val(response.data.full_name);
                    //                     jQuery('#package-cname').val(response.data.comp_name);
                    //                     jQuery('#package-email').val(response.data.email);
                    //                     jQuery('#package-phone').val(response.data.phone);
                    //                     jQuery('#package-address').val(response.data.address);


                    //                     jQuery('.form-popup-bg').addClass('is-visible');
                    //                 } else {
                    //                     jQuery('.form-popup-bg').addClass('is-visible');
                    //                 }
                    //                 jQuery('html, body').css("cursor", "default");   
                    //                 jQuery('.btnOpenForm').removeAttr("style")
                                    
                                   
                    //             }
                                
                    //         });
                
                            
                            
                      
                    //     });
                    
                    //     //close popup when clicking x or off popup
                    //     jQuery('.form-popup-bg').on('click', function(event) {
                    //         if (jQuery(event.target).is('.form-popup-bg') || jQuery(event.target).is('#btnCloseForm')) {
                    //             event.preventDefault();
                    //             jQuery(this).removeClass('is-visible');
                    //         }
                    //     });

                    //     jQuery('#wcgb-user-package-address').submit(function (e) {
                    //         e.preventDefault();
                            
                    //         jQuery('html, body, #wcgb-user-package-address').css("cursor", "wait"); 

                    //         var full_name = jQuery('#package-fname').val();
                    //         var comp_name = jQuery('#package-cname').val();
                    //         var email = jQuery('#package-email').val();
                    //         var phone = jQuery('#package-phone').val();
                    //         var address = jQuery('#package-address').val();
                    //         var package = jQuery('.form-popup-bg #package-id').val();

                    //         var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
                    //         var formData = {
                    //             package: package,
                    //             full_name: full_name,
                    //             comp_name: comp_name,
                    //             email: email,
                    //             phone: phone,
                    //             address: address,
                    //         };
                
                    //         jQuery.ajax({
                    //             url: ajaxurl,
                    //             type: 'post',
                    //             data: {
                    //                 formData: formData,
                    //                 // security: check_ref,
                    //                 dataType: "json",
                    //                 encode: true,
                    //                 action: 'wcgb_add_address_to_package'
                    //             },
                    //             error: function(response) {
                    //                 console.log(response);
                    //             },
                    //             success: function(response) {
                    //                 console.log(response);

                    //                 if (response.success) {
                    //                     jQuery('.btnOpenForm[data-package="'+package+'"]').html(full_name);
                    //                     jQuery('.form-popup-bg').removeClass('is-visible');
                    //                 } else {
                                        
                    //                 }
                    //                 jQuery('html, body').css("cursor", "default");   
                    //                 jQuery('#wcgb-user-package-address').removeAttr("style")
                    //             }
                    //         });

                            
                            
                    //     });
                    // });                        
                </script>

            </div>
        </div>
    </div>
</section>

<script>

 

    function closeForm() {
        jQuery('.form-popup-bg').removeClass('is-visible');
    }

    // jQuery(document).ready(function($) {

    //     jQuery('.delete-package').on('click', function(e) {
			
	// 		jQuery('html, body, .delete-package').css("cursor", "wait"); 

    //         e.preventDefault();
    //         var package = jQuery(this).data('package');
    //         var gb_key = jQuery(this).data('gb-ckey');
    //         var gb_id = jQuery(this).data('gb-id');
    //         var gw_id = jQuery('#gw-ckey').val();

           

    //         var products = [];
    //         jQuery(".delete-"+package).map(function() {
    //             products.push(jQuery(this).data('cikey'));
    //         }).get();


    //         var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    
    //         var formData = {
    //             package: package,
    //             gb_key: gb_key,
    //             gw_key: gw_id,
    //             products: products,
    //         };

    //         jQuery.ajax({
    //             url: ajaxurl,
    //             type: 'post',
    //             data: {
    //                 formData: formData,
    //                 // security: check_ref,
    //                 dataType: "json",
    //                 encode: true,
    //                 action: 'wcgb_remove_pkg_from_cart'
    //             },
    //             error: function(response) {
    //                 console.log(response);
    //             },
    //             success: function(response) {
                    
    //                 if (response.success) {
    //                     window.location.href = "<?php echo  wc_get_page_permalink( 'cart' ) ?>";

    //                 } else {
                        
    //                 }
    //             }
	// 			jQuery('html, body').css("cursor", "default");   
    //             jQuery('.delete-package').removeAttr("style")
    //         });
            
        
    //     });    

    // });

</script>