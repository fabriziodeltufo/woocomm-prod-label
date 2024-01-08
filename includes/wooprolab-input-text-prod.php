<?php

/* 1 - ADMIN SINGLE PROD : create a new product field  */
function wooprolab_woocommerce_product_custom_fields() {
  $args = array(
	        'id' => 'product_personalization_note',
	        'value' => get_post_meta( get_the_ID(), 'product_personalization_note', true ),
	        'label' => 'Customization',
	        'desc_tip' => true,
	        'description' => 'Customization',
	     );


			echo '<div class="options_group">';
				woocommerce_wp_text_input($args);
			echo '</div>';

	}
  add_action( 'woocommerce_product_options_general_product_data', 'wooprolab_woocommerce_product_custom_fields' );



/* 2 - ADMIN SINGLE PROD: save & update the changes*/
function wooprolab_woocommerce_product_custom_fields_save( $post_id ) {
    // grab the custom data from $_POST
    $product_personalization_note = isset( $_POST[ 'product_personalization_note' ] ) ? sanitize_text_field( $_POST[ 'product_personalization_note' ] ) : '';

    // grab the product
    $product = wc_get_product( $post_id );

    // save the custom data using WooCommerce built-in functions
    $product->update_meta_data( 'product_personalization_note', $product_personalization_note );

    //save
    $product->save();
}
add_action( 'woocommerce_process_product_meta', 'wooprolab_woocommerce_product_custom_fields_save' );



/* 3 - FRONT END SINGLE PROD: display the new field on single product pages */
function wooprolab_woocommerce_custom_fields_display() {
  global $post;
  $product = wc_get_product($post->ID);
    $custom_fields_woocommerce_title = $product->get_meta('product_personalization_note');
	  if ($custom_fields_woocommerce_title) {
	      printf(
	            '<div><label>%s</label><input type="text" id="custom_fields_title" name="custom_field_title" value=""></div>',
	            esc_html($custom_fields_woocommerce_title)
	      );
	  }
}
add_action('woocommerce_before_add_to_cart_button', 'wooprolab_woocommerce_custom_fields_display');



// -----------------------------------------
// 4. VALIDATION Throw error if custom input field empty
function wooprolab_product_add_on_validation( $passed, $product_id, $qty ){
   if( isset( $_POST['custom_field_title'] ) && sanitize_text_field( $_POST['custom_field_title'] ) == '' ) {
      wc_add_notice( 'Custom Label is a required field', 'error' );
      $passed = false;
   }
   return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'wooprolab_product_add_on_validation', 10, 3 );



// -----------------------------------------
// 5. SAVING CUSTOM FIELD VALUE.
// Save custom input field value into cart item + update total price x item
function wooprolab_product_add_on_cart_item_data( $cart_item_data, $product_id )
{
    if( !empty( $_POST['custom_field_title'] ) )
    {
        $cart_item_data['custom_field_title'] = $_POST['custom_field_title'];

        $product = wc_get_product($product_id);
        $price = $product -> get_price();
        $cart_item_data['total_price'] = $price + 0.99 ;

    }

    return $cart_item_data;

}
add_filter( 'woocommerce_add_cart_item_data', 'wooprolab_product_add_on_cart_item_data', 10, 2 );




// -----------------------------------------
// 6. DISPLAY CUSTOM FIELD
function wooprolab_product_add_on_display_cart( $data, $cart_item )
{

    if ( isset( $cart_item['custom_field_title'] ) )
    {
        $data[]= [
          'key' => 'Custom Label',
          'value' => sanitize_text_field($cart_item['custom_field_title'])
        ];

        $data[]= [
          'key' => 'Printing Cost Included',
          'value' => '£0.99'
        ];

    }

    return $data;
}
add_filter( 'woocommerce_get_item_data', 'wooprolab_product_add_on_display_cart', 10, 2 );




// 7. UPDATE CART PRICE FOR EVERY ITEM //
function wooprolab_update_cart_price( $cart_obj )
{

  if ( is_admin() && ! defined( 'DOING_AJAX' ) ){

    return;

  }

  foreach( $cart_obj -> get_cart() as $key => $value )
  {

    if (isset($value['total_price']))
    {
      $price = $value['total_price'];
      $value['data'] -> set_price(($price));
    }
  }
}
add_action('woocommerce_before_calculate_totals', 'wooprolab_update_cart_price');
