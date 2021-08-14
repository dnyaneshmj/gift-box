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
            $showButtons = false;

            $cart_packages = [];

            foreach($wcgb_packages as $package => $package_product){

                //var_dump($package);
                $packages_product = [];
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    $item_package = $cart_item['item_package'];
                    $is_gift_box = get_post_meta( $product_id, 'is_gift_box', true );

                    if( $package == $item_package && $is_gift_box != 'true' ){
                        $showButtons = true;
                        $packages_product[] = [ 'package'=> $package ,'package_product'=> $package_product,  'cart_item_key' => $cart_item_key, 'cart_item' => $cart_item ];
                    }
                }
                $cart_packages = array_merge($cart_packages, $packages_product);

            }

            foreach($wcgb_packages as $package => $package_product){ 
                echo $package;
                ?>
                <ul class="cart_list product_list_widget <?php echo esc_attr($args['list_class']); ?>">
                    <li> 
                            <h3> Gift Box : <?php echo get_the_title( $package_product ) ?></h3>
                            <a href='#'>Delete Package</a>
                    </li>
                <?php 

                foreach ( $cart_packages as $product ) {
                            
                    if($product['package'] != $package ) continue;
                    
                    $cart_item = $product['cart_item'];
                    $cart_item_key  = $product['cart_item_key'];
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
                ?>
                </ul>
                    
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
					<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="button wc-forward"><?php esc_html_e( 'View Cart', 'g5plus-handmade' ); ?></a>
				<?php endif; ?>
				<?php if (isset($opt_header_shopping_cart_button['checkout']) && ($opt_header_shopping_cart_button['checkout'] == '1') && $showButtons ):?>
					<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button checkout wc-forward"><?php esc_html_e( 'Checkout', 'g5plus-handmade' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_mini_cart' ); ?>
</div>