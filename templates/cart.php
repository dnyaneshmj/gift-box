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

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
    <?php   
           $wcgb_packages = WC()->session->get('wcgb_packages');
           $current_package = WC()->session->get('wcgb_current_package');
           $showButtons = false;

            if( isset($_POST['new_box']) ){
                

                $currentPackageCount = count($wcgb_packages ) + 1;
                $name = 'package'.$currentPackageCount;
                $wcgb_packages = array_merge($wcgb_packages, [  $name => '' ]);

                WC()->session->set('wcgb_packages', $wcgb_packages );
			    WC()->session->set('wcgb_current_package',  $name );
            }
             $wcgb_packages = WC()->session->get('wcgb_packages');
             $current_package = WC()->session->get('wcgb_current_package');
            

             $cart_packages = [];

             foreach($wcgb_packages as $package => $package_product){

                //var_dump($package);
                $packages_product = [];
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    $item_package = $cart_item['item_package'];
                    $is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );

                    if( $package == $item_package && $package_product != $product_id && $is_gift_box != 'true' ){
                        $showButtons = true;
                       $packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product,  'cart_item_key' => $cart_item_key, 'cart_item' => $cart_item ];
                    }
                }
                $cart_packages = array_merge($cart_packages, $packages_product);

             }
             //var_dump($cart_packages);

           
            // $gift_box = [];
            // foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            //         $product_id = $cart_item['product_id'] ;
            //         $is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );
                    
            //         if($is_gift_box == true){
            //             $gift_box[] = $product_id;
            //         }
            // }
            
            foreach($wcgb_packages as $package => $package_product){ 
                echo $package;
                ?>

                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"> 
                    <tr>
                        <h3> Gift Box : <?php echo get_the_title( $package_product ) ?></h3>
                        <a href='#'>Delete Package</a>
                    </tr>
                    <?php 
                        foreach ( $cart_packages as $product ) {
                            
                            if($product['package'] != $package ) continue;
                            
                            $cart_item = $product['cart_item'];
                            $cart_item_key  = $product['cart_item_key'];

                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            ?>
                            <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                <td class="product-remove">
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                            '<a href="%s" class="cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="pe-7s-close-circle"></i></a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            __( 'Remove this item', 'g5plus-handmade' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ), $cart_item_key );
                                    ?>
                                </td>

                                <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'g5plus-handmade' ); ?>">

                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( $thumbnail );
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
                                    }
                                    ?>
                                    <div class="product-name-wrap">
                                        <div class="product-name-inner">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                            } else {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                            }

                                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                            // Backorder notification
                                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'g5plus-handmade' ) . '</p>' ) );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'g5plus-handmade' ); ?>">
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                    ?>
                                </td>

                                <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'g5plus-handmade' ); ?>">
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
                                </td>

                                <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'g5plus-handmade' ); ?>">
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                    ?>
                                </td>
                                </tr>

                            <?php

                        }
                    
                    ?>
                </table>

            <?php } ?>


	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<!-- <thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-name"><?php _e( 'Product', 'g5plus-handmade' ); ?></th>
				<th class="product-price"><?php _e( 'Price', 'g5plus-handmade' ); ?></th>
				<th class="product-quantity"><?php _e( 'Quantity', 'g5plus-handmade' ); ?></th>
				<th class="product-subtotal"><?php _e( 'Total', 'g5plus-handmade' ); ?></th>
			</tr>
		</thead> -->
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<!-- 
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="pe-7s-close-circle"></i></a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'g5plus-handmade' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'g5plus-handmade' ); ?>">

							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
							}
							?>
							<div class="product-name-wrap">
								<div class="product-name-inner">
									<?php
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

									// Backorder notification
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'g5plus-handmade' ) . '</p>' ) );
									}
									?>
								</div>
							</div>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'g5plus-handmade' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'g5plus-handmade' ); ?>">
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
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'g5plus-handmade' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
						</td>
					</tr>
					<?php
				}
			}
			?> -->

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="5" class="actions clearfix">

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
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
<div class="cart-collaterals">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
