<?php
/**
 * Plugin Name: FIDS API Integration
 * Description: FIDS API Integration
 */

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

global $fidsDataTableName;
$fidsDataTableName  = 'fids_api_data';
global $fidsSettingsTableName;
$fidsSettingsTableName  = 'fids_api_settings';

// PLUGIN ACTIVATION
function fids_api_activation() {
    global $wpdb;
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    $charset_collate = $wpdb->get_charset_collate();

    $dataTableSql = "CREATE TABLE $fidsDataTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		type int(9) NOT NULL,
		raw_data text NOT NULL,
		updated_at datetime NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    $settingsTableSql = "CREATE TABLE $fidsSettingsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		app_id varchar (255),
		app_key varchar (255),
		UNIQUE KEY id (id)
	) $charset_collate;";
    dbDelta($dataTableSql);
    dbDelta($settingsTableSql);
}
register_activation_hook( __FILE__, 'fids_api_activation');

// PLUGIN DEACTIVATION
function fids_api_deactivation() {
    global $wpdb;
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    $charset_collate = $wpdb->get_charset_collate();

    $dataTableDeleteSql = "DELETE TABLE $fidsDataTableName";
    $settingsTableDeleteSql = "DELETE TABLE $fidsSettingsTableName";
    $wpdb->query($dataTableDeleteSql);
    $wpdb->query($settingsTableDeleteSql);
}
register_deactivation_hook( __FILE__, 'fids_api_deactivation');



//add_action('admin_menu', 'test_plugin_setup_menu');