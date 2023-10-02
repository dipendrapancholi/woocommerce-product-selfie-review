<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Collection Pages Class
 * 
 * Handles all the different features and functions
 * for the front end pages.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
class Woo_Psr_Shortcode {
	
	public function __construct() {
		
	}
	
	/**
	 * Add Public Hook
	 * 
	 * Handle to add public hooks
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_product_selfie_review( $atts, $content = null ) {
		
		global $woo_slider_counter;
		
		if( empty( $woo_slider_counter ) ) {
			$woo_slider_counter	= 1;
		}
		
		// do something to $content
		ob_start();
		
		$psr_enable_review = get_option( 'psr_enable_review' );
 		
 		if ( $psr_enable_review == 'no') {
 			return;
 		}
		
		$atts	= shortcode_atts(
					array(
						'heading'		=> __( 'Customer Product Selfies', 'woopsr' ),
						'product_id'	=> '',
						'cols'			=> '4'
					), $atts);
		
		wp_enqueue_style( 'woo-psr-owl-carousel-style' );
		wp_enqueue_style( 'woo-psr-owl-theme-style' );
		wp_enqueue_style( 'woo-psr-owl-transitions' );
		wp_enqueue_style( 'woo-psr-font-awesome' );
		wp_enqueue_style( 'woo-psr-custom-style' );
		wp_enqueue_script( 'woo-psr-owl-carousel-js' );
		wp_enqueue_script( 'woo-psr-carousel-js' );

		$heading	= $atts['heading'];
		$product_id	= $atts['product_id'];
		$cols		= $atts['cols'];

		?>
		<style type="text/stylesheet">
			#woo-psr-review .item{
		      margin: 3px;
		    }
		    #woo-psr-review .item img{
		      display: block;
		      width: 100%;
		      height: auto;
		    }
		</style><?php
		
		// get all approved comments with empty number arg
		$args = array(
						'post_type' 	=> 'product',
						'status' 		=> 'approve',
						'post_id'		=> $product_id,
						'meta_key' 		=> 'attachmentId',
						'meta_value' 	=> '',
						'meta_compare' 	=> '!=',
					);
		
		// The Query
		$comments_query	= new WP_Comment_Query;
		$comments		= $comments_query->query( $args );

		if( count( $comments ) <= 0 ) return false;

		?>
		<div class="pdp-section selfie-section" id="selfiesBlk">
			<div class="sort">
			    <span><?php echo $heading;?> <span id="selfieCount">(<?php echo count($comments);?>)</span></span>
			</div>
			<div id="pdp-selfie-div" class="comp comp-selfie-product reset-padding">
			<div class="bx-wrapper" style="max-width: 840px; margin: 0px auto;">
				<div id="woo-psr-review-<?php echo $woo_slider_counter;?>" class="woo-psr-review"><?php
			       if ( $comments ) {
				       foreach ($comments as $comment) :

				    		$attachmentId = get_comment_meta($comment->comment_ID, 'attachmentId', TRUE);
				    		$rating 	  = get_comment_meta($comment->comment_ID, 'rating', TRUE);

				    		if( is_numeric( $attachmentId ) && !empty( $attachmentId ) ) {

				                // atachement info
				                $attachmentLink		= wp_get_attachment_url( $attachmentId );
				                $attachmentMeta		= wp_get_attachment_metadata( $attachmentId );
				                $attachmentName		= basename(get_attached_file( $attachmentId ) );
				                $attachmentType		= get_post_mime_type( $attachmentId );
				                //$attachmentRel	= 'rel="lightbox"';
				                $contentInner		= wp_get_attachment_image( $attachmentId, array( '300', '300' ) );
								
				                // attachment link
				                $contentInnerFinal	= '<div class="item"><a class="attachmentLink" data-content="'.$comment->comment_content.'" data-rating="'.$rating.'" data-date="'.get_comment_date( 'F j, Y', $comment->comment_ID).'" title="'.$comment->comment_author.'">';
			                    $contentInnerFinal	.= $contentInner;
			                    $contentInnerFinal	.= '</a></div>';
			                    
			                    echo $contentInnerFinal;
				            }
						
				      endforeach;

				    } else {
						echo __( 'No comments found.', 'woopsr' );
					}

				      ?>
				      </div>
				</div>
			</div>
		</div>
	    <script type="text/javascript">
	    	
			jQuery( document ).ready( function( $ ) {
				
				$( "#woo-psr-review-<?php echo $woo_slider_counter;?>" ).owlCarousel({
					autoPlay: 3000, //Set AutoPlay to 3 seconds
					items : <?php echo $cols;?>,
					itemsDesktop : [1199,3],
					itemsDesktopSmall : [979,3]
				});
			});
			
	    </script><?php
		
		$woo_slider_counter++;
		
		$content= ob_get_clean();
		
		return $content;
	}
	
	/**
	 * Add Public Hook
	 * 
	 * Handle to add public hooks
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		// Add shortcode product selfie review
		add_shortcode( 'woo_product_selfie_review', array( $this, 'woo_product_selfie_review') );
	}
}