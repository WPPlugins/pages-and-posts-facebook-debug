<?php
/*
Plugin Name: Facebook Debug
Description: Add a widget on the Wordpress Dashboard that let you debug all your URLs on the Facebook Debugguer tool to scrape new informations.
Author: <a href="http://www.eralion.com" target="_blank">ERALION.com</a>
Text Domain: pagesandpostsfacebookdebugguer
Domain Path: /languages
Version: 1.8
*/
add_action( 'plugins_loaded', 'pagesandpostsfacebookdebugguer_init' );
function pagesandpostsfacebookdebugguer_init()
{
    load_plugin_textdomain( 'pagesandpostsfacebookdebugguer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
include('inc/dashboard.php');include('inc/automatic.php');include('inc/metaboxes.php');include('inc/panel.php');
?>