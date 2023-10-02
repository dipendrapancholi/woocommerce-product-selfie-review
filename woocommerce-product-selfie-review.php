<?php

/**
 * Plugin Name: Woocommerce Product Selfie Review
 * Plugin URI: https://dharmisoft.com/
 * Description: The Woocommerce Product Selfie Review plugin allowes customers to add selfie in product review and admin can display carousel slider of uploaded selfie on any page using shortcode and widget.
 * Version: 1.0.0
 * Author: Dipendra Pancholi
 * Author URI: https://profiles.wordpress.org/dipendrapancholi/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
if( !defined( 'WOO_PSR_VERSION' ) ) {
	define( 'WOO_PSR_VERSION', '1.0.0' );// Plugin Version
}
if( !defined( 'WOO_PSR_DIR' ) ) {
	define( 'WOO_PSR_DIR', dirname( __FILE__ ) );// Plugin dir
}
if( !defined( 'WOO_PSR_URL' ) ) {
	define( 'WOO_PSR_URL', plugin_dir_url( __FILE__ ) );// Plugin url
}
if( !defined( 'WOO_PSR_INC_DIR' ) ) {
	define( 'WOO_PSR_INC_DIR', WOO_PSR_DIR . '/includes' );// Plugin include dir
}
if( !defined( 'WOO_PSR_INC_URL' ) ) {
	define( 'WOO_PSR_INC_URL', WOO_PSR_URL . 'includes' );// Plugin include url
}
if( !defined( 'WOO_PSR_ADMIN_DIR' ) ) {
	define( 'WOO_PSR_ADMIN_DIR', WOO_PSR_INC_DIR . '/admin' );// Plugin admin dir
}
if( !defined( 'WOO_PSR_BASENAME' ) ) {
	define( 'WOO_PSR_BASENAME', basename( WOO_PSR_DIR ) ); // base name
}
if( !defined( 'WOO_PSR_META_PREFIX' ) ) {
	define( 'WOO_PSR_META_PREFIX', '_woo_psr_' );// Plugin Prefix
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
function woo_psr_load_textdomain() {
	
	// Set filter for plugin's languages directory
	$woo_psr_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$woo_psr_lang_dir	= apply_filters( 'woo_psr_languages_directory', $woo_psr_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'woopsr' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'woopsr', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $woo_psr_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WOO_PSR_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/woocommerce-product-selfie-review folder
		load_textdomain( 'woopsr', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/woocommerce-product-selfie-review/languages/ folder
		load_textdomain( 'woopsr', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'woopsr', false, $woo_psr_lang_dir );
	}
}

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'woo_psr_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
function woo_psr_install() {
	
	//get option for when plugin is activating first time
	$woo_psr_set_option = get_option( 'woo_psr_set_option' );
	
	if( empty( $woo_psr_set_option ) ) { //check plugin version option
		
		//update plugin version to option
		update_option( 'woo_psr_set_option', '1.0' );
		
		update_option( 'psr_enable_review', 'yes' );
		update_option( 'psr_display_on_single_product_page', 'yes' );
		update_option( 'psr_review_heading', __( 'Customer Product Selfies', 'woopsr' ) );
		update_option( 'psr_custom_css', '' );
	}
}

/**
 * Add plugin action links
 *
 * Adds a Settings, Support and Docs link to the plugin list.
 *
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
function woo_os_add_plugin_links( $links ) {
	
	$plugin_links = array(
		'<a href="admin.php?page=wc-settings&tab=woopsr">' . __( 'Settings', 'woopsr' ) . '</a>',
		//'<a target="_blank" href="http://support.serveonetech.com/">' . __( 'Support', 'woopsr' ) . '</a>',
		'<a target="_blank" href="http://www.serveonetech.com/documents/woocommerce-product-selfie-review/">' . __( 'Docs', 'woopsr' ) . '</a>'
	);
	
	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_os_add_plugin_links' );

//add action to load plugin
add_action( 'plugins_loaded', 'woo_psr_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package Woocommerce Product Selfie Review
 * @since 1.0.0
 */
function woo_psr_plugin_loaded() {
	
	if( class_exists( 'Woocommerce' ) ) { //check Woocommerce is activated or not
		
		//Gets the plugin ready for translation
		woo_psr_load_textdomain();
		
		/**
		 * Deactivation Hook
		 * 
		 * Register plugin deactivation hook.
		 * 
		 * @package Woocommerce Product Selfie Review
		 * @since 1.0.0
		 */
		register_deactivation_hook( __FILE__, 'woo_psr_uninstall');
		
		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package Woocommerce Product Selfie Review
		 * @since 1.0.0
		 */
		function woo_psr_uninstall() {
		  	global $wpdb;
		}
		
		// Global variables
		global $woo_psr_scripts, $woo_psr_public, $woo_psr_admin, $woo_psr_widget, $woo_psr_shortcode;
		
		// Include Misc Functions File
		//include_once( WOO_PSR_INC_DIR . '/woo-psr-misc-functions.php' );
		
		// Script class handles most of script functionalities of plugin
		include_once( WOO_PSR_INC_DIR . '/class-woo-psr-scripts.php' );
		$woo_psr_scripts = new Woo_Psr_Scripts();
		$woo_psr_scripts->add_hooks();
		
		// Public class handles most of admin panel functionalities of plugin
		include_once( WOO_PSR_INC_DIR.'/class-woo-psr-public.php' );
		$woo_psr_public = new Woo_Psr_Public();
		$woo_psr_public->add_hooks();
		
		// Admin class handles most of public functionalities of plugin
		include_once( WOO_PSR_ADMIN_DIR.'/class-woo-psr-admin.php' );
		$woo_psr_admin = new Woo_Psr_Admin();
		$woo_psr_admin->add_hooks();
		
		// Shortcode class handles most of shortcode functionalities of plugin
		include_once( WOO_PSR_INC_DIR.'/class-woo-psr-shortcode.php' );
		$woo_psr_shortcode = new Woo_Psr_Shortcode();
		$woo_psr_shortcode->add_hooks();
		
		// Widget class handles widget functionalities of plugin
		include_once( WOO_PSR_INC_DIR.'/widget/class-woo-psr-widget.php' );
		
		// Register and load the widget
		function psr_load_widget() {
			register_widget( 'Woo_Psr_Widget' );
		}
		add_action( 'widgets_init', 'psr_load_widget' );
	}
}