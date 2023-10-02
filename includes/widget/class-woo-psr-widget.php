<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Woo_Psr_Widget extends WP_Widget { // Creating the widget 
	
	public function __construct() {
		
		parent::__construct( // Base ID of your widget
				'Woo_Psr_Widget',
				__('Product Selfie Review', 'woopsr'), // Widget name will appear in UI
				array( 'description' => __( 'Widget based on Product Selfie Review', 'woopsr' ), ) // Widget description
			);
	}
	
	public function widget( $args, $instance ) { // Creating widget front-end This is where the action happens
		
		$heading = $instance['heading'];
		$product_id = $instance['product_id'];
		$no_of_cols = $instance['no_of_cols'];
		echo do_shortcode('[woo_product_selfie_review heading="'.$heading . '" product_id="'.$product_id.'" cols="'.$no_of_cols.'"][/woo_product_selfie_review]');
	}
	
	// Widget Backend 
	public function form( $instance ) {

		$heading   = isset( $instance[ 'heading' ] ) ? $instance[ 'heading' ] : '';
		$pro_id   = isset( $instance[ 'product_id' ] ) ? $instance[ 'product_id' ] : '';
		$cols_no  = isset( $instance[ 'no_of_cols' ] ) ? $instance[ 'no_of_cols' ] : '1';

		$args     = array( 'post_type' => 'product' );
		$products = get_posts( $args ); 

		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'heading' ); ?>"><?php _e( 'Heading : ', 'woopsr' ); ?></label> 
			<input type="text" name="<?php echo $this->get_field_name( 'heading' ); ?>" value="<?php echo $heading; ?>"> 
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'product_id' ); ?>"><?php _e( 'Select Product : ', 'woopsr' ); ?></label> 
	
			<select name="<?php echo $this->get_field_name( 'product_id' ); ?>">
				<option value=""><?php echo __( 'All Products', 'woopsr' );?></option>
				<?php foreach ( $products as $key => $value ) { ?>
					<option class="widefat" id="<?php echo $this->get_field_id( 'product_id' ); ?>" <?php selected( $pro_id , $value->ID ); ?> value="<?php echo esc_attr( $value->ID ); ?>"> <?php echo esc_attr( $value->post_title ); ?></option><?php 
				}?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e( 'Select Cols : ', 'woopsr' ); ?></label> 
			<input type="text" name="<?php echo $this->get_field_name( 'no_of_cols' ); ?>" value="<?php echo $cols_no; ?>"> 
		</p><?php
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['heading'] = ( ! empty( $new_instance['heading'] ) ) ? strip_tags( $new_instance['heading'] ) : '';
		$instance['product_id'] = ( ! empty( $new_instance['product_id'] ) ) ? strip_tags( $new_instance['product_id'] ) : '';
		$instance['no_of_cols'] = ( ! empty( $new_instance['no_of_cols'] ) ) ? strip_tags( $new_instance['no_of_cols'] ) : '';
		return $instance;
	}
} // Class wpb_widget ends here
?>