<?php

function is_bought_items( $product_id ) {

    $bought = false;
    return $bought;
    // setting the IDs of specific products that are needed to be bought by the customer
    // => Replace the example numbers by your specific product IDs

    // Get all customer orders
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order', // WC orders post type
        'post_status' => 'wc-completed' // Only orders with status "completed"
    ) );

    // Going through each current customer orders
    foreach ( $customer_orders as $customer_order ) {
        $order = wc_get_order( $customer_order );
        // $order_id = $order->id;

        // Going through each current customer products bought in the order
        foreach ($items as $item) {

            // Your condition related to your 2 specific products Ids
            if ( $item['product_id'] == $product_id ) {

                $bought = true; // Corrected mistake in variable name
            }
        }
    }
    /*echo $bought;exit;
    return $bought;*/
    // return "true" if one the specifics products have been bought before by customer
    /*if ( $bought ) {
        return true;
    }else{
        return false;  
    }*/
}
?>