<?php
/**
 * Plugin Name: FIDS API Integration
 * Description: FIDS API Integration
 */

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// STORES DATA RECEIVED FROM API
global $fidsDataTableName;
$fidsDataTableName  = 'fids_api_data';
// STORES API CREDENTIALS AND MAPPER DATA
global $fidsSettingsTableName;
$fidsSettingsTableName  = 'fids_api_settings';
// STORES DEFAULT ELEMENTS
global $fidsElementsTableName;
$fidsElementsTableName  = 'fids_api_elements';
// DEFAULT ELEMENTS
global $fidsElements;
$fidsElements = [
    'flightId' => 'Numeric flight ID',
    'lastUpdatedTime' => 'Time of day that this flight information was last updated, according to the selected airport\'s timezone.',
    'lastUpdatedTimeUtc' => 'Time of day that this flight information was last updated, in UTC.',
    'lastUpdatedDate' => 'Date that this flight information was last updated, according to the selected airport\'s timezone.',
    'lastUpdatedDateUtc' => 'Date that this flight information was last updated, in UTC.',
    'dayOffset' => 'Nonnegative integer representing the number of days forward from today (according to the selected airport\'s time zone). For today\'s flights, this will be 0.',
    'statusCode' => 'Single-character Cirium code for the status of the flight',
    'airlineName' => 'Name of the airline. For wet leases, this will be the marketing airline rather than the operating airline.',
    'airlineCode' => 'Airline code of the airline. For wet leases, this will be the code for the marketing airline rather than the operating airline.',
    'flightNumber' => 'Flight number assigned by the airline.',
    'airlineLogoUrlPng' => 'URL of the airline logo as a 300x100 PNG image. Logo is for the operating airline, or in the case of a wet lease, the marketing airline. (The logos are provided As Is. Some may be out-of-date or missing.)',
    'airlineLogoUrlSvg' => 'URL of the airline logo as an SVG image. Logo is for the operating airline, or in the case of a wet lease, the marketing airline. (The logos are provided As Is. Some may be out-of-date or missing.)',
    'isCodeshare' => 'Boolean value indicating whether the flight is a codeshare (flight marketed by multiple airlines).',
    'operatedFlightNumber' => 'Flight number assigned by the operating airline.',
    'operatingAirlineName' => 'Name of the operating airline.',
    'operatingAirlineCode' => 'Code of the operating airline.',
    'originCity' => 'Name of the flight\'s origin city.',
    'originAirportName' => 'Name of the flight\'s origin airport.',
    'originAirportCode' => 'Code of the flight\'s origin airport.',
    'originFamiliarName' => 'A name for the flight origin that the travelers expect, particularly used to distinguish between cities with the same name or multiple airports that serve the same city.',
    'originStateCode' => 'State (or province, where applicable) code of the origin airport.',
    'originCountryCode' => 'Country code of the flight\'s origin airport.',
    'destinationAirportName' => 'Name of the flight\'s destination airport.',
    'destinationAirportCode' => 'Code of the flight\'s destination airport.',
    'destinationFamiliarName' => 'A name for the destination that the travelers expect, particularly used to distinguish between cities with the same name or multiple airports that serve the same city.',
    'destinationStateCode' => 'State (or province, where applicable) code of the destination airport.',
    'destinationCountryCode' => 'Country code of the flight\'s destination airport.',
    'flight' => 'The combination of the airline code and the flight number.',
    'remarks' => 'A phrase with additional information about the status of the flight, including delay information (in minutes) if appropriate.',
    'remarksWithTime' => 'A phrase with additional information about the status of the flight, including local time if appropriate.',
    'remarksCode' => 'One of a small fixed set of strings describing the kind of remark appearing in the remarks field. See Remarks Codes. This field can be requested even if the request does not actually include the remarks field.',
    'airportCode' => 'Code for the destination (for departing flights) or origin (for arrivals) airport.',
    'airportName' => 'Name of the destination (for departing flights) or origin (for arrivals) airport.',
    'city' => 'Name of the destination (for departing flights) or origin (for arrivals) city.',
    'gate' => 'Name of the gate where the flight will depart/arrive.',
    'terminal' => 'Name of the terminal where the flight will depart/arrive.',
    'baggage' => 'NThe baggage claim information for the flight arrival.',
    'scheduledTime' => 'The scheduled arrival or departure time, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'scheduledDate' => 'The scheduled arrival or departure date, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'estimatedTime' => 'The estimated arrival or departure time, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'estimatedDate' => 'The estimated arrival or departure date, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'actualTime' => 'The actual arrival or departure time, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'actualDate' => 'The actual arrival or departure date, depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'currentTime' => 'The most accurate arrival or departure time (priority: 1. actual 2. estimated 3 scheduled), depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'currentDate' => 'The most accurate arrival or departure date (priority: 1. actual 2. estimated 3 scheduled), depending on whether you asked for arrivals or departures. If you have opted to use runway times this may be a runway time if no gate time is available.',
    'scheduledGateTime' => 'The scheduled gate arrival or departure time, depending on whether you asked for arrivals or departures.',
    'scheduledGateDate' => 'The scheduled gate arrival or departure date, depending on whether you asked for arrivals or departures.',
    'estimatedGateTime' => 'The estimated gate arrival or departure time, depending on whether you asked for arrivals or departures.',
    'estimatedGateDate' => 'The estimated gate arrival or departure date, depending on whether you asked for arrivals or departures.',
    'actualGateTime' => 'The actual gate arrival or departure time, depending on whether you asked for arrivals or departures.',
    'actualGateDate' => 'The actual gate arrival or departure date, depending on whether you asked for arrivals or departures.',
    'currentGateTime' => 'The most accurate gate arrival or departure time, depending on whether you asked for arrivals or departures.',
    'currentGateDate' => 'The most accurate gate arrival or departure date, depending on whether you asked for arrivals or departures.',
    'codesharesAsNames' => 'If this flight is marketed by multiple carriers, this fields lists those codeshare flights with the airline name and flight number.',
    'codesharesAsCodes' => 'If this flight is marketed by multiple carriers, this field lists those codeshare flights with the airline code and flight number.',
    'uplineAirportNames' => 'The names of the airports, in order, from the flight segments preceding this segment.',
    'uplineAirportCodes' => 'The codes of the airports, in order, from the flight segments preceding this segment.',
    'downlineAirportNames' => 'The names of the airports, in order, from the flight segments after this segment.',
    'downlineAirportCodes' => 'The codes of the airports, in order, from the flight segments after segment.',
    'weather' => 'A short description of the weather conditions at the remote airport.',
    'temperatureC' => 'Air temperature, in degrees Celsius/centigrade, at the remote airport.',
    'temperatureF' => 'Air temperature, in degrees Fahrenheit, at the remote airport.',
    'primaryMarketingAirlineName' => 'The name of the primary marketing airline.',
    'primaryMarketingAirlineCode' => 'The FS code of the primary marketing airline.',
];

// PLUGIN ACTIVATION
function fids_api_activation() {
    global $wpdb;
    // table names
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;
    // elements array
    global $fidsElements;

    // prepare sql
    $charset_collate = $wpdb->get_charset_collate();
    $dataTableSql = "CREATE TABLE IF NOT EXISTS $fidsDataTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		type int(9) NOT NULL,
		raw_data text NOT NULL,
		updated_at datetime NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    $settingsTableSql = "CREATE TABLE IF NOT EXISTS $fidsSettingsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		app_id varchar (255),
		app_key varchar (255),
		airport_code varchar (255) DEFAULT '',
		departure_elements text (255) DEFAULT '',
		arrival_elements text (255) DEFAULT '',
		state varchar (255),
		UNIQUE KEY id (id)
	) $charset_collate;";
    $fidsElementsTableSql = "CREATE TABLE IF NOT EXISTS $fidsElementsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		api_key varchar (255) NOT NULL,
		api_title varchar (255) NOT NULL,
		internal_title varchar (255) DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";

    // execute sql
    dbDelta($dataTableSql);
    dbDelta($settingsTableSql);
    dbDelta($fidsElementsTableSql);

    // init DB
    $hasDefaultSettings = $wpdb->get_results("SELECT * FROM $fidsSettingsTableName WHERE state = 'default'");
    if(!$hasDefaultSettings) {
        $wpdb->insert($fidsSettingsTableName, [
            'app_id' => '',
            'app_key' => '',
            'departure_elements' => json_encode([]),
            'arrival_elements' => json_encode([]),
            'state' => 'default'
        ]);
    }
    $hasElements = $wpdb->get_results("SELECT * FROM $fidsElementsTableName ");
    if(!count($hasElements)) {
        foreach ($fidsElements as $key => $val) {
            $wpdb->insert($fidsElementsTableName, [
                'api_key' => $key,
                'api_title' => $val,
            ]);
        }
    }
}
register_activation_hook( __FILE__, 'fids_api_activation');

// PLUGIN DEACTIVATION
function fids_api_deactivation() {
    global $wpdb;
    global $fidsDataTableName;
    global $fidsSettingsTableName;

    $dataTableDeleteSql = "DELETE TABLE $fidsDataTableName";
    $settingsTableDeleteSql = "DELETE TABLE $fidsSettingsTableName";
    $wpdb->query($dataTableDeleteSql);
    $wpdb->query($settingsTableDeleteSql);
}
register_deactivation_hook( __FILE__, 'fids_api_deactivation');

// ADD PLUGIN MENU ITEMS
function fids_api_settings_menu_page() {
    global $wpdb;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;

    $defaultSettings = $wpdb->get_results("SELECT * FROM $fidsSettingsTableName WHERE state = 'default'")[0];
    $elements = $wpdb->get_results("SELECT * FROM $fidsElementsTableName");

    include_once(__DIR__ . '/views/admin/settings.php');
}
function fids_api_settings_menu() {
    add_menu_page( 'FIDS Settings', 'FIDS Settings', 'manage_options', 'fids-settings', 'fids_api_settings_menu_page');
}
add_action('admin_menu', 'fids_api_settings_menu');

// ADMIN ACTIONS
function fids_admin_update_visible_elements() {
    global $wpdb;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;

    // update visibility
    $selectedArrivalElements = isset($_POST['departureElements']) ? $_POST['departureElements'] : [];
    $selectedDepartureElements = isset($_POST['departureElements']) ? $_POST['departureElements'] : [];
    $wpdb->update($fidsSettingsTableName, [
        'departure_elements' => json_encode($selectedArrivalElements),
        'arrival_elements' => json_encode($selectedDepartureElements),
    ], ['state' => 'default']);
    // update element titles
    foreach($_POST['custom_titles'] as $key => $val) {
        $wpdb->update($fidsElementsTableName, [
            'internal_title' => $val,
        ], ['api_key' => $key]);
    }

    wp_redirect(admin_url('admin.php?page=fids-settings'));
}
add_action( 'admin_post_update_visible_elements', 'fids_admin_update_visible_elements' );