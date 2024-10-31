<?php
/*
@wordpress-plugin
Plugin Name: Paysepro for WooCommerce
Author URI: https://www.paysepro.com
Description: Paysepro for Woocommerce. Paysepro is an online payment service provider.
Author: Paysepro
Version: 1.1
License: GNU General Public License v2
Text Domain: paysepro_wc
*/
$plugin_header_translate = array( __('Paysepro for WooCommerce', 'paysepro_wc'), __('Allows your customers from all around the world to pay by using a wide variety of both international and local payment methods.', 'paysepro_wc') );
add_action( 'plugins_loaded', 'paysepro_plugins_loaded' );
add_action( 'init', 'paysepro_init' );
//////////////////////////////////////////////////////////////////////
function paysepro_init() {
	load_plugin_textdomain( "paysepro_wc", false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
//////////////////////////////////////////////////////////////////////
function paysepro_plugins_loaded() {
	if ( !class_exists( 'WC_Payment_Gateway' ) ) exit;

	include_once ('class_wc_paysepro_gateway.php');
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_gateway_paysepro_gateway' );
}
//////////////////////////////////////////////////////////////////////       
function woocommerce_add_gateway_paysepro_gateway($methods) {
	$methods[] = 'WC_Paysepro_Gateway';
	return $methods;
}
//////////////////////////////////////////////////////////////////////
function paysepro_plugin_row_meta( $links, $file ){
	if ( strpos( $file, basename( __FILE__ ) ) !== false ) {
		$new_links = array(					
          '<a href="'.plugin_dir_url(__FILE__).'readme.txt" target="_blank">' . __( 'Documentation', "paysepro_wc" ) . '</a>'
				);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter('plugin_row_meta', 'paysepro_plugin_row_meta', 10, 2); 
?>
