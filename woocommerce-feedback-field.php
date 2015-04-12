<?php
/**
 * Plugin Name: WooCommerce purchase experience feedback field
 * Description: Needs rewrite
 */

// Hook in
add_action( 'woocommerce_after_order_notes', 'woo_purchase_feedback_field' );
// Our hooked in function - $fields is passed via the filter!
function woo_purchase_feedback_field( $checkout ) {

	echo '<div id="my_custom_checkout_field"><h2>' . __('Purchase Experience') . '</h2>';

	woocommerce_form_field( 'my_field_name', array(
			'label'     => __('Purchase Experience', 'woocommerce'),
			'placeholder'   => _x('Purchase Experience', 'placeholder', 'woocommerce'),
			'required'    => false,
			'clear'       => false,
			'type'        => 'select',
			'options'     => array(
				'awesome' => __('Awesome', 'woocommerce' ),
				'not_bad' => __('Not bad', 'woocommerce' ),
				'Awful' => __('Awful', 'woocommerce' )

			)
		),

		$checkout->get_value( 'my_field_name' ));

	echo '</div>';
}

add_action('woocommerce_checkout_process', 'woo_purchase_feedback_field_process');

function woo_purchase_feedback_field_process() {
// Check if set, if its not set add an error.
	if ( ! $_POST['my_field_name'] )
		wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );
}

add_action( 'woocommerce_checkout_update_order_meta', 'woo_purchase_feedback_field_update_order_meta' );

function woo_purchase_feedback_field_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['my_field_name'] ) ) {
		update_post_meta( $order_id, 'purchase_feedback', sanitize_text_field( $_POST['my_field_name'] ) );
	}
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'woo_purchase_feedback_field_display_admin_order_meta', 10, 1 );
function woo_purchase_feedback_field_display_admin_order_meta($order){
	echo '<p><strong>'.__('Customer Purchase Feedback').':</strong> ' . get_post_meta( $order->id, 'purchase_feedback', true ) . '</p>';
}
