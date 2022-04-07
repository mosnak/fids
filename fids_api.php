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
// STORES QUERY PARAMS
global $paramsTableName;
$paramsTableName = 'fids_query_params';
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
global $defaultParamSettings;
$defaultParamSettings = [
    'excludeAirlines' => '',
    'includeAirlines' => '',
    'includeCodeshares' => 1,
    'terminal' => '',
    'timeFormat' => 12,
    'timeWindowBegin' => '',
    'timeWindowEnd' => '',
    'maxFlights' => '',
    'lateMinutes' => '',
    'boardingMinutes' => '',
    'useRunwayTimes' => 0,
    'excludeCargoOnlyFlights' => 0,
    'sort1' => '',
    'sort2' => '',
    'sort3' => '',
];

// FETCH FIDS ACTION
function fids_fetch() {
    global $wpdb;
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;
    global $paramsTableName;

    $settings = $wpdb->get_results("SELECT * FROM $fidsSettingsTableName WHERE state = 'default'");
    if(!count($settings)) {
        return;
    }
    $airports = explode(',', $settings[0]->airports);
    if(!count($airports) || $settings[0]->app_id == '' || $settings[0]->app_key == '') {
        return;
    }
    $dbRequestedFields = $wpdb->get_results("SELECT * FROM $fidsElementsTableName");
    $requestedFields = implode(',', array_map(function($dbField) {
        return $dbField->api_key;
    }, $dbRequestedFields));
    foreach ($airports as $airport) {
        $types = [1, 2]; // 1 = arrival, 2 = departures
        foreach ($types as $type) {
            $existingRecord = $wpdb->get_results("SELECT * FROM $fidsDataTableName WHERE `airport`='$airport' AND `type`='$type'");
            if(!count($existingRecord)) {
                $shouldFetch = true;
            } else {
                $now = new DateTime();
                $diff = $now->diff(new DateTime($existingRecord[0]->updated_at));
                $shouldFetch = $diff->i > 0 ? true : false;
            }
            if($shouldFetch) {
                $stingType = $type == 1 ? 'arrivals' : 'departures';
                $apiURL = 'https://api.flightstats.com/flex/fids/rest/v1/json/' . $airport . '/' . $stingType . '?appId=' . $settings[0]->app_id . '&appKey=' . $settings[0]->app_key;
                $apiURL .= '&requestedFields=' . $requestedFields;
                $DBparams = $wpdb->get_results("SELECT * FROM $paramsTableName");
                $paramQuery = '';
                $sort1 = '';
                $sort2 = '';
                $sort3 = '';
                foreach ($DBparams as $param) {
                    if($param->param_val != '' && $param->param_key != 'sort1' && $param->param_key != 'sort2' && $param->param_key != 'sort3') {
                        if($param->param_key == 'includeCodeshares' || $param->param_key == 'useRunwayTimes' || $param->param_key == 'excludeCargoOnlyFlights') {
                            $paramVal = $param->param_val == 0 ? 'false' : true;
                        } else {
                            $paramVal = $param->param_val;
                        }
                        $paramQuery .= '&'.$param->param_key . '=' . $paramVal;
                    }
                    if($param->param_key == 'sort1') {
                        $sort1 = $param->param_val;
                    }
                    if($param->param_key == 'sort2') {
                        $sort2 = $param->param_val;
                    }
                    if($param->param_key == 'sort3') {
                        $sort3 = $param->param_val;
                    }
                }
                $apiURL .= $paramQuery;
                $sortArr = [];
                foreach ([$sort1, $sort2, $sort3] as $s) {
                    if($s != '') {
                        $sortArr[] = $s;
                    }
                }
                if($sort1 != '' || $sort2 != '' || $sort3 != '') {
                    $sort = implode(',', $sortArr);
                    $apiURL .= '&sortFields=' . $sort;
                }
                $response = wp_remote_get($apiURL);
                $data = wp_remote_retrieve_body($response);
                if(count($existingRecord)) {
                    $wpdb->update($fidsDataTableName, [
                        'raw_data' => $data,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'type' => $type
                    ], ['airport' => $airport, 'type' => $type]);
                } else {
                    $wpdb->insert($fidsDataTableName, [
                        'raw_data' => $data,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'airport' => $airport,
                        'type' => $type
                    ]);
                }
            }
        }
    }
}
add_action('fids_fetch_cron_hook', 'fids_fetch');

// PLUGIN ACTIVATION
function fids_api_activation() {
    global $wpdb;
    // table names
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;
    global $paramsTableName;
    // elements array
    global $fidsElements;
    // default query params
    global $defaultParamSettings;

    // prepare sql
    $charset_collate = $wpdb->get_charset_collate();
    $dataTableSql = "CREATE TABLE IF NOT EXISTS $fidsDataTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		type mediumint(9) NOT NULL,
		airport varchar (255) NOT NULL,
		raw_data longtext NOT NULL,
		updated_at datetime NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    $settingsTableSql = "CREATE TABLE IF NOT EXISTS $fidsSettingsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		app_id varchar (255),
		app_key varchar (255),
		airports text DEFAULT '',
		departure_elements text (255) DEFAULT '',
		arrival_elements text (255) DEFAULT '',
		state varchar (255),
		UNIQUE KEY id (id)
	) $charset_collate;";
    $fidsElementsTableSql = "CREATE TABLE IF NOT EXISTS $fidsElementsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_id mediumint(9) NOT NULL DEFAULT 0,
		api_key varchar (255) NOT NULL,
		api_title varchar (255) NOT NULL,
		internal_title varchar (255) DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";
    $paramsTableSql = "CREATE TABLE IF NOT EXISTS $paramsTableName (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		param_key varchar (255) NOT NULL,
		param_val varchar (255) NOT NULL DEFAULT '',
		UNIQUE KEY id (id)
	) $charset_collate;";

    // execute sql
    dbDelta($dataTableSql);
    dbDelta($settingsTableSql);
    dbDelta($fidsElementsTableSql);
    dbDelta($paramsTableSql);

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
    $paramsExists = $wpdb->get_results("SELECT * FROM $paramsTableName");
//    var_dump($paramsExists);exit;
    if(count($paramsExists) < 1) {
        foreach ($defaultParamSettings as $key => $val) {
            $wpdb->insert($paramsTableName, [
                'param_key' => $key,
                'param_val' => $val
            ]);
        }
    }

    if(!wp_next_scheduled('fids_fetch_cron_hook')) {
        wp_schedule_event(time(), 'fids_interval', 'fids_fetch_cron_hook');
    }
}
register_activation_hook( __FILE__, 'fids_api_activation');

// PLUGIN DEACTIVATION
function fids_api_deactivation() {
    $timestamp = wp_next_scheduled('fids_fetch_cron_hook');
    wp_unschedule_event($timestamp, 'fids_fetch_cron_hook');
}
register_deactivation_hook( __FILE__, 'fids_api_deactivation');

// PLUGIN UNINSTALL
function fids_api_uninstall() {
    global $wpdb;
    global $fidsDataTableName;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;

    $dataTableDeleteSql = "DROP TABLE IF EXISTS $fidsDataTableName";
    $settingsTableDeleteSql = "DROP TABLE IF EXISTS  $fidsSettingsTableName";
    $fidsElementsTableDeleteSql = "DROP TABLE IF EXISTS  $fidsElementsTableName";
    $wpdb->query($dataTableDeleteSql);
    $wpdb->query($settingsTableDeleteSql);
    $wpdb->query($fidsElementsTableDeleteSql);

    $timestamp = wp_next_scheduled('fids_fetch_cron_hook');
    wp_unschedule_event($timestamp, 'fids_fetch_cron_hook');
}
register_uninstall_hook( __FILE__, 'fids_api_uninstall');

// ADD PLUGIN MENU ITEMS
function fids_api_settings_menu_page() {
    global $wpdb;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;
    global $paramsTableName;

    $defaultSettings = $wpdb->get_results("SELECT * FROM $fidsSettingsTableName WHERE state = 'default'")[0];
    $elements = $wpdb->get_results("SELECT * FROM $fidsElementsTableName ORDER BY order_id");
    $queryParams = $wpdb->get_results("SELECT * FROM $paramsTableName");
    $sort1 = '';
    $sort2 = '';
    $sort3 = '';
    foreach ($queryParams as $param) {
        if($param->param_key == 'sort1') {
            $sort1 = $param->param_val;
        }
        if($param->param_key == 'sort2') {
            $sort2 = $param->param_val;
        }
        if($param->param_key == 'sort3') {
            $sort3 = $param->param_val;
        }
    }
    wp_enqueue_script('jquery-ui-sortable');

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
    $selectedArrivalElements = isset($_POST['arrivalElements']) ? $_POST['arrivalElements'] : [];
    $selectedDepartureElements = isset($_POST['departureElements']) ? $_POST['departureElements'] : [];

    $wpdb->update($fidsSettingsTableName, [
        'departure_elements' => json_encode($selectedDepartureElements),
        'arrival_elements' => json_encode($selectedArrivalElements),
    ], ['state' => 'default']);

    // update element titles
    foreach($_POST['custom_titles'] as $key => $val) {
        $wpdb->update($fidsElementsTableName, [
            'internal_title' => stripslashes($val),
        ], ['api_key' => $key]);
    }

    // update order
    $order = explode(',', $_POST['order']);
    $i = 1;
    foreach($order as $orderKey) {
        $wpdb->update($fidsElementsTableName, [
            'order_id' => $i
        ], ['api_key' => $orderKey]);
        $i++;
    }

    wp_redirect(admin_url('admin.php?page=fids-settings'));
}
add_action('admin_post_fids_update_visible_elements', 'fids_admin_update_visible_elements');

function fids_admin_update_general_settings() {
    global $wpdb;
    global $fidsSettingsTableName;
    global $defaultParamSettings;
    global $paramsTableName;


    $airports = implode(',', array_map(function($el) {
        return trim($el);
    }, explode(',', $_POST['airports'])));
    $wpdb->update($fidsSettingsTableName, [
        'app_id' => $_POST['app_id'],
        'app_key' => $_POST['app_key'],
        'airports' => $airports
    ], ['state' => 'default']);

    foreach ($defaultParamSettings as $key => $val) {
        if(isset($_POST[$key])) {
            $wpdb->update($paramsTableName, [
                'param_val' => $_POST[$key]
            ], ['param_key' => $key]);
        }
    }

    wp_redirect(admin_url('admin.php?page=fids-settings'));
}
add_action( 'admin_post_fids_update_general_settings', 'fids_admin_update_general_settings' );

// PROVIDE SHORTCODE

function getShortcodeHTML($attrAirport, $attrType) {
    global $wpdb;
    global $fidsSettingsTableName;
    global $fidsElementsTableName;
    $type = $attrType == 'departures' ? 2 : 1;

    // get headers
    $settings = $wpdb->get_results("SELECT * FROM $fidsSettingsTableName WHERE `state`='default'");
    if(!count($settings)){
        return '';
    }
    if($attrType == 'departures') {
        $headers = json_decode($settings[0]->departure_elements, true);
    } else {
        $headers = json_decode($settings[0]->arrival_elements, true);
    }

    // map headers to headers with custom title
    $headersWithCustomTitle = [];
    $elements = $wpdb->get_results("SELECT * FROM $fidsElementsTableName ORDER BY order_id");
    foreach ($elements as $element) {
        foreach ($headers as $header) {
            if($element->api_key == $header) {
                $headersWithCustomTitle[] = [
                    'title' => $element->internal_title,
                    'key' => $element->api_key
                ];
                continue;
            }
        }
    }

    // get data
    $elementsData = getElementsData($attrAirport, $attrType);
    if(!$elementsData) {
        return '';
    }
    $data = $elementsData['allElements'];
    $now = new DateTime();
    $diff = $now->diff(new DateTime($elementsData['updated_at']));
//    $lastUpdatedBefore = $diff->i > 2 ? $diff->i . ' minutes ago' : 'minute ago';
    $lastUpdatedBefore = $elementsData['updated_at'] . ' UTC';

    // output
    ob_start();
    include(__DIR__ . '/views/public/fids_shortcode.php');
    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

function fids_shortcode($attrs) {
    $attrs = shortcode_atts(array(
        'airport' => 'ABQ',
        'type' => 'departures'
    ), $attrs, 'fids' );

    $html = getShortcodeHTML($attrs['airport'], $attrs['type']);
    if($attrs['type'] == 'departures') {
        $html .= '<script>const fidsAirport_departures = "'. $attrs['airport'] .'";</script>';
    } else {
        $html .= '<script>const fidsAirport_arrivals = "'. $attrs['airport'] .'";</script>';
    }

    return $html;
}
add_shortcode('fids', 'fids_shortcode');

// REGISTER ASSETS
function fids_register_script() {
    global $post;
    if(has_shortcode($post->post_content, 'fids')) {
        wp_register_script('fids_script', plugins_url('assets/main.js', __FILE__), array('jquery'));
        wp_enqueue_script('fids_script', '', [], false, true);
        wp_localize_script( 'fids_script', 'fids_client', [
            'ajax_url' => admin_url( 'admin-ajax.php')
        ]);

        wp_register_style('fids_style', plugins_url('assets/main.css', __FILE__));
        wp_enqueue_style('fids_style');
    }
}
add_action('wp_enqueue_scripts', 'fids_register_script');

// AJAX FETCH ACTION
function fids_ajax_handler() {
    $airport = $_POST['airport'];
    $type = $_POST['type'];
    $html = getShortcodeHTML($airport, $type);
    echo $html;
    wp_die();
}
add_action('wp_ajax_fids', 'fids_ajax_handler');


// CREATE INTERVAL
function fids_add_cron_interval( $schedules ) {
    $schedules['fids_interval'] = array(
        'interval' => 60,
        'display' => esc_html__('FIDS fetch interval'));
    return $schedules;
}
add_filter('cron_schedules', 'fids_add_cron_interval');

// DOMAIN FUNCTIONS

function getElementsData($airport, $type) {
    global $wpdb;
    global $fidsElementsTableName;
    global $fidsDataTableName;

    $typeInt = $type == 'arrivals' ? 1 : 2;
    $elements = $wpdb->get_results("SELECT * FROM $fidsDataTableName WHERE `airport`='$airport' AND `type`='$typeInt'");
    if(!count($elements)) {
        return false;
    }
    $allElements = json_decode($elements[0]->raw_data, true);
    return [
        'allElements' => $allElements['fidsData'],
        'updated_at' => $elements[0]->updated_at
    ];
}
