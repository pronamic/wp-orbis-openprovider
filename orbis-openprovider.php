<?php
/*
Plugin Name: Orbis Openprovider
Plugin URI: http://www.orbiswp.com/
Description: The Orbis Openprovider plugin can check domains against Orbis subscriptions.

Version: 1.0.0
Requires at least: 3.5

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: orbis_openprovider
Domain Path: /languages/

License: Copyright (c) Pronamic

GitHub URI: https://github.com/wp-orbis/wp-orbis-openprovider
*/

function orbis_openprovider_bootstrap() {
	include 'classes/orbis-openprovider-plugin.php';
	include 'classes/orbis-openprovider-admin.php';

	global $orbis_openprovider_plugin;

	$orbis_openprovider_plugin = new Orbis_Openprovider_Plugin( __FILE__ );
}

add_action( 'orbis_bootstrap', 'orbis_openprovider_bootstrap' );
