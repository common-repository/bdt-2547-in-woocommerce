<?php
/*
Plugin Name: BDT & Payment Gateways in WooCommerce
Plugin URI: http://wordpress.org/plugins/bdt-2547-in-woocommerce/
Description: Add Bangladeshi currency (BDT &#2547;) & Bangladeshi Local Payment Gateways into WooCommerce. bKash, DBBL Mobile Banking etc.
Version: 2.0.1
Author: Mehdi Akram
Author URI: http://shamokaldarpon.com/
License: GPLv2
*/

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'bdt_currency_register_plugin_links', 10, 2 );
function bdt_currency_register_plugin_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="http://royaltechbd.com/" target="_blank">' . __( 'Royal Technologies', 'rsb' ) . '</a>';
		$links[] = '<a href="http://shamokaldarpon.com/" target="_blank">' . __( 'Shamokal Darpon', 'rsb' ) . '</a>';
	}
	return $links;
}



add_filter( 'woocommerce_currencies', 'add_bdt_currency' );
function add_bdt_currency( $currencies ) {
$currencies['BDT'] = __( 'Bangladeshi Taka', 'woocommerce' );
return $currencies;
}
 
add_filter('woocommerce_currency_symbol', 'add_bdt_currency_symbol', 10, 2);
function add_bdt_currency_symbol( $currency_symbol, $currency ) {
switch( $currency ) {
case 'BDT': $currency_symbol = 'BDT &#2547;&nbsp;'; break;
}
return $currency_symbol;
}




/**********************************Bangladeshi Local Payment Gateways*******************/


/* WooCommerce fallback notice. */
function wcMBanking_woocommerce_fallback_notice() {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Mobile Banking Payment Gateways depends on the last version of %s to work!', 'wcMBanking' ), '<a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>' ) . '</p></div>';
}

/* Load functions. */
function wcMBanking_gateway_load() {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
        add_action( 'admin_notices', 'wcMBanking_woocommerce_fallback_notice' );
        return;
    }
   
    function wcmMBanking_add_gateway( $methods ) {
        $methods[] = 'WC_bKash_Gateway';
        $methods[] = 'WC_dbblMB_Gateway';
        return $methods;
    }
	add_filter( 'woocommerce_payment_gateways', 'wcmMBanking_add_gateway' );
	
	
    // Include the WC_bKash_Gateway class.
    require_once plugin_dir_path( __FILE__ ) . 'class-wc-bKash-gateway.php';
    require_once plugin_dir_path( __FILE__ ) . 'class-wc-dbblMB-gateway.php';
}

add_action( 'plugins_loaded', 'wcMBanking_gateway_load', 0 );



/* Adds custom settings url in plugins page. */
function wcMBanking_action_links( $links ) {
    $settings = array(
		'settings' => sprintf(
		'<a href="%s">%s</a>',
		admin_url( 'admin.php?page=woocommerce_settings&tab=payment_gateways' ),
		__( 'Payment Gateways', 'wcMBanking' )
		)
    );

    return array_merge( $settings, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcMBanking_action_links' );


?>