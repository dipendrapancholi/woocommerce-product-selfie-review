<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all the different features and functions
 * for the admin pages.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
class Woo_Psr_Admin {

	public function __construct() {
		
	}

	/**
	 * Adding Product Review setting tab
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_add_settings_tab( $tabs ) {
		$tabs['woopsr'] = __( 'Product Review', 'woopsr' );
		return $tabs;
	}

	/**
	 * Settings Tab Content
	 * 
	 * Adds the settings content to the product review tab.
	 *
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_settings_tab() {

		woocommerce_admin_fields( $this->woo_psr_get_settings() );		
	}

	/**
	 * Update Settings
	 * 
	 * Updates the signature options when being saved.
	 *
	 *  @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_update_settings() {

		woocommerce_update_options( $this->woo_psr_get_settings() );		
	}

	/**
 	 * Add plugin settings
 	 * 
 	 * Handles to add plugin settings
 	 * 
 	 * @package Woocommerce Product Selfie Review
 	 * @since 1.0.0
 	 */
	public function woo_psr_get_settings() {

		$woo_psr_settings	= array(	
									array( 
										'name'		=>	__( 'Product Selfie Review Options', 'woopsr' ),
										'type'		=>	'title',
										'desc'		=>	'',
										'id'		=>	'psr_general_settings'
									),
									array(
										'id'		=> 'psr_enable_review',
										'name'		=> __( 'Enable Product Review:', 'woopsr' ),
										'desc'		=> '',
										'type'		=> 'checkbox',
										'desc_tip'	=> '<p class="description">'.__( 'If you want to use the selfie review option on your site, then you have to enable this setting.', 'woopsr' ).'</p>'
									),
									array(
										'id'		=> 'psr_display_on_single_product_page',
										'name'		=> __( 'Display On Single Product Page:', 'woopsr' ),
										'desc'		=> '',
										'type'		=> 'checkbox',
										'desc_tip'	=> '<p class="description">'.__( 'If you want to display the selfie reivew slider on single product page then please enable this option.', 'woopsr' ).'</p>'
									),
									array(
										'id'		=> 'psr_review_heading',
										'name'		=> __( 'Product Review Slider Heading:', 'woopsr' ),
										'desc'		=> '',
										'type'		=> 'text',
										'desc_tip'	=> '<p class="description">'.__( 'Please enter heading title which you want to display on product review slider on sigle product page.', 'woopsr' ).'</p>'
									),
									array(
										'id'		=> 'psr_custom_css',
										'name'		=> __( 'Custom CSS:', 'woopsr' ),
										'desc'		=> '',
										'type'		=> 'textarea',
										'css' 		=> 'width:100%;min-height:100px',
										'desc_tip'	=> '<p class="description">'.__( 'Customize css option to design change for product selfie reviews and slider.', 'woopsr' ).'</p>'
									),
									array( 
										'type' 		=> 'sectionend',
										'id' 		=> 'psr_general_settings'
									),
								);
		
		return apply_filters( 'woo_psr_get_settings', $woo_psr_settings );
	}
	/**
	 * Add Admin Hook
	 * 
	 * Handle to add admin hooks
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function add_hooks() {

		//add Signature tab to woocommerce setting page
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woo_psr_add_settings_tab' ), 99 );

		// add signature tab content
		add_action( 'woocommerce_settings_tabs_woopsr', array( $this, 'woo_psr_settings_tab' ) );

		// save custom update content
		add_action( 'woocommerce_update_options_woopsr', array( $this, 'woo_psr_update_settings' ), 100 );
	}
}