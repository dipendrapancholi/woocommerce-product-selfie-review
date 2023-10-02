<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Classs
 * 
 * Handles all the different style and jquery
 * for the front end and backend pages.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
class Woo_Psr_Scripts {
	
	public function __construct() {
		
	}
	
	/**
	 * Register Script
	 * 
	 * Registers scripts and stylesheets
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_frontend_scripts_and_styles() {
		
		wp_register_style( 'woo-psr-owl-carousel-style', WOO_PSR_INC_URL . '/css/owl.carousel.css' );
		wp_register_style( 'woo-psr-owl-theme-style', WOO_PSR_INC_URL . '/css/owl.theme.css' );
		wp_register_style( 'woo-psr-owl-transitions', WOO_PSR_INC_URL . '/css/owl.transitions.css' );
		wp_register_style( 'woo-psr-font-awesome', WOO_PSR_INC_URL . '/css/font-awesome.min.css' );
		wp_register_style( 'woo-psr-custom-style', WOO_PSR_INC_URL . '/css/woo-psr-styles.css' );
		
		wp_register_script( 'woo-psr-owl-carousel-js', WOO_PSR_INC_URL . '/js/owl.carousel.js', array('jquery'),null, true );
		
		wp_register_script( 'woo-psr-script-js', WOO_PSR_INC_URL . '/js/woo-psr-script.js', array('jquery'),null, true );
		wp_enqueue_script( 'woo-psr-script-js' );
		
		wp_register_script( 'woo-psr-carousel-js', WOO_PSR_INC_URL . '/js/woo-psr-carousel.js', array('jquery'),null, true );
	}
	
	/**
	 * Add Public Hook
	 * 
	 * Handle to add public hooks
	 * 
	 * @package Woocommerce Product Selfie Review
	 * @since 1.0.0
	 */
	public function woo_psr_custom_css_styles() {
		
		$custom_css	= get_option( 'psr_custom_css' ); 
		
		if( !empty( $custom_css ) ) { ?>
			
			<style type="text/css"><?php 
				echo $custom_css;?>
			</style><?php
		}
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

		add_action( 'wp_enqueue_scripts', array( $this, 'woo_psr_frontend_scripts_and_styles' ) );
		
		add_action( 'wp_head', array( $this, 'woo_psr_custom_css_styles' ) );
	}
}