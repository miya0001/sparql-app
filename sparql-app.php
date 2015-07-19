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

$sparql_admin = new Sparql_Admin();
$sparql_admin->register();
