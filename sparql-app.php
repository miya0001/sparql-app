<?php
/*
Plugin Name: SPARQL App
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: sparql-app
Domain Path: /languages
*/

require_once dirname( __FILE__ ) . '/lib/sparql-admin.php';
require_once dirname( __FILE__ ) . '/lib/Sparql_App.php';

$sparql_admin = new Sparql_Admin( array(
	'plugins_root' => dirname( __FILE__ ),
	'plugins_url' => plugins_url( '', __FILE__ ),
) );

$sparql_admin->register();
