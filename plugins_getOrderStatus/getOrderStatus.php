<?php
/*
Plugin Name: Get Order Status
Description: get EA order status based on store and customer number
Version: 0.1
Author: Bob Hampton
*/
/*
=============================================================================
@(#)O getOrderStatus.php         revision 1.1-2                   09/02/2016
@(#)P WordPress plugin for ajax send/recv on Order Status Website
@(#)Q bobh                       revision 1.0-1                   08/24/2016
@(#)R /var/www/ordstat/wp-content/plugins/getOrderStatus (challenger)
=============================================================================
*/

# Add CSS for order status form to page
function add_ordstat_css() {
  wp_register_style( 'ordstat_css', plugins_url( '/css/orderstatus.css', __FILE__ ));
  wp_enqueue_style( 'ordstat_css' );
}
add_action( 'wp_enqueue_scripts', 'add_ordstat_css', 99 );

# Add get_ord_stat.js jQuery script to page
function get_ord_stat_enqueue( $hook ) {
  // if ( ! is_page( 'Order Status' ) ) return;
  wp_enqueue_script( 'get_ord_stat',
                     plugins_url( '/js/get_ord_stat.js', __FILE__ ),
                     array( 'jquery' )
  );
  $parms = array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'ajax_nonce' => wp_create_nonce('get_ord_stat_nonce'),
  );
  wp_localize_script( 'get_ord_stat', 'get_ord_stat_ajax_obj', $parms );
}
add_action( 'template_redirect', 'get_ord_stat_enqueue' );

# Register get_ord_stat API route / endpoint
function prefix_register_get_ord_stat_route() {
  register_rest_route( 'furniturepro/v1', '/getordstat', array(
    // 'methods' => 'WP_REST_Server::READABLE',
    'methods' => 'GET',
    'callback' => 'prefix_get_get_ord_stat',
  ) );
}
add_action( 'rest_api_init', 'prefix_register_get_ord_stat_route' );

# Callback function for get_ord_stat API - Calls CGI script on target server
function prefix_get_get_ord_stat($json) {
  $server = $json['server'];
  $custno = $json['custno'];
  $ordstat = join(file( "http://" . $server .
               "/cgi-bin/get_order_status_json?q=" . $custno ), "");
  return rest_ensure_response($ordstat);
}

# Add get_order_status ajax handler to page - Call CGI script on target server
function get_ord_stat_ajax_handler() {
  // check_ajax_referer( 'get_ord_stat_nonce', 'get_ord_stat_nonce' );
  $server = $_POST['server'];
  $custno = $_POST['custno'];
  $ordstat = @stripslashes( @join( @file( "http://" . $server .
               "/cgi-bin/get_order_status?q=" . $custno ),"" ) );
  echo $ordstat;
  wp_die();
}
add_action( 'wp_ajax_get_ord_stat', 'get_ord_stat_ajax_handler' );
add_action( 'wp_ajax_nopriv_get_ord_stat', 'get_ord_stat_ajax_handler' );

?>
