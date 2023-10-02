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
class Woo_Psr_Public {
	
	public function __construct() {
		
	}
	
	public function woo_psr_product_review_custom_field(  $comment_form ) {

		global $post;

		$user_id = get_current_user_id();
        $current_user= wp_get_current_user();
        $customer_email = $current_user->email;

	 	//If current user bought current product
	 	if( wc_customer_bought_product($customer_email, $user_id, $post->ID) ) {
			$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Upload your selfie image', 'woocommerce' ) . ' <span class="required">*</span></label><input type="file" id="comment" name="attachment" aria-required="true" required><input type="hidden" id="selfie_img" name="selfie_img" value="1"></p>';

			return $comment_form;

		} else {
			$comment_form['comment_field'] .= '<input type="hidden" id="selfie_img" name="selfie_img" value="0">';

		}

		return $comment_form;
	}
	
	/**
	 * Upload Image to Uploads
	 * 
	 * Move selfie image to uploads folder
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
     */
	public function insertAttachment( $fileHandler, $postId ) {

		require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
		require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
		require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

		return media_handle_upload($fileHandler, $postId);
	}

	/**
	 * Save selfie comments
	 * 
	 * Save woocommerce selfie comment
	 * 
	 * @package Woocommerce Product Selfie Review
     * @param $commentId
	 * @since 1.0.0
     */
	public function woo_psr_save_comment_meta_data( $commentId ) {
	   if( $_FILES['attachment']['size'] > 0 ) {

	       $bindId		= ATT_BIND ? $_POST['comment_post_ID'] : 0;
	       $attachId 	= $this->insertAttachment( 'attachment', $bindId );
	       add_comment_meta( $commentId, 'attachmentId', $attachId );
	       unset( $_FILES );
	   }
	}
	
	/**
	 * Add Form Tag
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_comment_form_top() {

		echo '</form><form action="'. get_home_url() .'/wp-comments-post.php" method="POST" enctype="multipart/form-data" id="attachmentForm" class="comment-form" novalidate>';		  
	}
	
	/**
	 * Add Form Tag
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_review_popup() {

		echo '<div id="sidebar_modal_right" class="sidebar rightside bigwidth open" hidden>
		    <div class="sidebaroverlay"></div>
		    <div class="sidebarin selfieClass">
		    <div align="center" class="sd-loader hidden">
	            <img class="js_svgLoader" onerror="this.src=\'https://i1.sdlcdn.com/img/revamp/cyclicLoader.gif\'; this.onerror=null;" height="32" width="32" src="https://i2.sdlcdn.com/img/sdDarwinLoader.svg">
		    </div>
		        <div class="close close1 marT10 marR10"><i class="sd-icon sd-icon-delete-sign"></i></div>
		        <div class="sidebarin-heading">
		        	<h3>Good Product</h3>
		        </div>
		        <div class="sidebarin-content" style="display: block;">
		        
		        <div class="imgBlock"><img class="externalContentSRO pdpSelfieImg wooble" alt="atanu.laskar13" title="atanu.laskar13" data-enlargedimg="https://snapdeal-res.cloudinary.com/image/upload/v1478257230/rnr/nvfsdygdgocafx49v6ir.jpg" src="https://snapdeal-res.cloudinary.com/image/upload/v1478257230/rnr/nvfsdygdgocafx49v6ir.jpg"></div><div class="selfieTextSection"><i class="arrow-top"></i><div class="userimg"><span class="reviewer-imgName" style="background:#5fcbef">A</span><span class="_reviewOrangeDot"></span><span class="_reviewUserName">atanu.laskar13</span></div><div class="user-review"><div class="ratingAndCreatedAt"> <div class="rating-stars ">  <div class="grey-stars"></div> <div class="filled-stars"></div></div>   <span class="date LTgray">Nov 04, 2016</span></div><div class="selfie-headLine">Good Product</div><div class="selfieDescription">nice watch . size is little small for my wrist.</div></div></div><input type="hidden" value="38" class="selfieCountValue"></div>
		    </div>
		</div>';
	}
	
	public function woo_psr_after_single_product() {
		
		global $post;
		
		// product ID
		$product_id	= isset( $post->ID ) ? $post->ID : '';
		
		$enable		= get_option( 'psr_display_on_single_product_page' );
		$heading	= get_option( 'psr_review_heading' );

		$heading_title	= !empty( $heading ) ? 'heading="' . $heading . '"' : '';

		if ( $enable == 'no') {
 			return;
 		}

 		echo do_shortcode( '[woo_product_selfie_review product_id="' . $product_id . '" ' . $heading_title . '][/woo_product_selfie_review]' );
	}
	
	/**
	 * Function for custom fields validation
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.1
	 */
	public function woo_psr_custom_fields_validation( $commentdata ) {
		
		if ( ! empty( $_POST['selfie_img'] ) && ! isset( $_POST['attachment'] ) )
		wp_die( __( '<strong>ERROR</strong>: Please upload selfie image.<p><a href="javascript:history.back()">« Back</a></p>' ) );

		return $commentdata;
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

		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'woo_psr_product_review_custom_field'), 11, 1 );

		// Add action for save comment of woocommerece product
		add_action( 'comment_post', array( $this, 'woo_psr_save_comment_meta_data' ), 10, 1 );

		// Add action for add form tag
		add_action( 'comment_form_top', array( $this, 'woo_psr_comment_form_top' ) );

		// Add action for add form tag
		add_action( 'wp_footer', array( $this, 'woo_psr_review_popup' ) );

		//add_action( 'woocommerce_after_single_product', array( $this, 'woo_psr_after_single_product' ) );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'woo_psr_after_single_product' ), 9 );

		// add action for custom field validation
		add_action( 'preprocess_comment', array( $this, 'woo_psr_custom_fields_validation' ) );
	}
}