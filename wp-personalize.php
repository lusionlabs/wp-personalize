<?php
/**
 * Plugin Name:     WP Personalize
 * Plugin URI:      http://wordpress.org/plugins/wp-personalize/
 * Description:     Personalize and customize your WordPress single site or multisite (for the entire network or individual sites).
 * Author:          Lusion Labs
 * Author URI:      https://wppersonalize.com
 * Text Domain:     wp-personalize
 * Domain Path:     /languages
 * Version:         2.2.0
 *
 * @package         WP_Personalize
 */

/**
 * @package   WP Personalize
 * @author    Lusion Labs
 * @version   2.1.0
 * @copyright Lusion Labs
 */

// Constants
define( 'WWP_PLUGIN_NAME', 'wp-personalize' );
define( 'WWP_PLUGIN_LANG_DOMAIN', 'wp-personalize' );
define( 'WWP_PLUGIN_DISPLAY_NAME', __( 'WP Personalize', WWP_PLUGIN_LANG_DOMAIN ) );
define( 'WWP_PLUGIN_TITLE_NAME', __( 'WP Personalize Editor', WWP_PLUGIN_LANG_DOMAIN ) );
define( 'WWP_PLUGIN_VERSION', '2.0.0' );

// Variables
$scriptSiteArr = get_option( 'wp_personalize_script_arr', array() );
$scriptNetArr  = get_site_option( 'wp_personalize_script_net_arr', array() );
$scriptSetArr  = get_site_option( 'wp_personalize_script_set_arr', array() );
$locationArr   = array(
	'head'     => __( 'Head', WWP_PLUGIN_LANG_DOMAIN ),
	'bodyTop'  => __( 'Body Top', WWP_PLUGIN_LANG_DOMAIN ),
	'bodyFoot' => __( 'Body Footer', WWP_PLUGIN_LANG_DOMAIN ),
);
$typeArr       = array(
	'html' => __( 'HTML', WWP_PLUGIN_LANG_DOMAIN ),
	'css'  => __( 'CSS', WWP_PLUGIN_LANG_DOMAIN ),
	'js'   => __( 'Javascript', WWP_PLUGIN_LANG_DOMAIN ),
	'php'  => __( 'PHP', WWP_PLUGIN_LANG_DOMAIN ),
);
$areaArr       = array(
	'site'  => __( 'Site Only', WWP_PLUGIN_LANG_DOMAIN ),
	'admin' => __( 'Admin Only', WWP_PLUGIN_LANG_DOMAIN ),
	'both'  => __( 'Both', WWP_PLUGIN_LANG_DOMAIN ),
);
// Hooks
register_activation_hook( __FILE__, 'wppActivation' );
register_deactivation_hook( __FILE__, 'wppDeactivation' );
add_action( 'plugins_loaded', 'checkIsSuperAdmin' );

// Admin script and styles
add_action( 'admin_enqueue_scripts', 'wppLoadScriptsStyles' );

// Load Scripts
$wpHeadScript          = '';
$wpAdminHeadScript     = '';
$wpBodyTopScript       = '';
$wpBodyMidScript       = '';
$wpBodyFootScript      = '';
$wpAdminBodyFootScript = '';
$isAdmin               = is_admin();
$isNetworkAdmin        = is_network_admin();
$isSuperAdmin          = false;

// Is network admin AJAX request hack
if ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_multisite() && preg_match( '#^' . network_admin_url() . '#i', $_SERVER['HTTP_REFERER'] ) ) {
	$isNetworkAdmin = true;
}

handleScripts( $scriptSiteArr );
if ( ! $isNetworkAdmin ) {
	handleScripts( $scriptNetArr );
}

add_filter( 'wp_head', 'hookHeadScript', 999, 0 );
add_filter( 'admin_head', 'hookAdminHeadScript', 999, 0 );
add_filter( 'wp_footer', 'hookBodyFootScript', 999, 0 );
add_filter( 'admin_footer', 'hookAdminBodyFootScript', 999, 0 );

// Ajax Hooks
add_action( 'wp_ajax_wpp_load_list', 'wppLoadList' );
add_action( 'wp_ajax_wpp_update_script', 'wppUpdateScript' );
add_action( 'wp_ajax_wpp_update_settings', 'wppUpdateSettings' );
add_action( 'wp_ajax_wpp_load_script', 'wppLoadScript' );
add_action( 'wp_ajax_wpp_delete_script', 'wppDeleteScript' );

// Menu Pages
add_action( 'admin_menu', 'wppAddOptionsPage' );
if ( is_multisite() ) {
	add_action( 'network_admin_menu', 'wppAddNetworkOptionsPage' );
}

// Load Languages Files
add_action( 'plugins_loaded', 'loadLangFiles' );
function loadLangFiles() {
	load_plugin_textdomain( WWP_PLUGIN_LANG_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/lang' );
}

function wppLoadScriptsStyles() {
	wp_register_script( WWP_PLUGIN_NAME . '-admin', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery-ui-dialog', 'jquery' ) );
	wp_register_script( WWP_PLUGIN_NAME . '-blockUI', plugins_url( '/js/jquery.blockUI.js', __FILE__ ), array( 'jquery-ui-dialog', 'jquery' ) );
	wp_register_style( WWP_PLUGIN_NAME . '-admin', plugins_url( '/css/admin.css', __FILE__ ) );
	wp_register_style( WWP_PLUGIN_NAME . '-whhg', plugins_url( '/css/whhg.css', __FILE__ ) );
	wp_register_style( WWP_PLUGIN_NAME . '-jquery-ui', plugins_url( '/css/jquery-ui.css', __FILE__ ) );

	wp_enqueue_script( WWP_PLUGIN_NAME . '-admin' );
	wp_enqueue_script( WWP_PLUGIN_NAME . '-blockUI' );
	wp_enqueue_style( WWP_PLUGIN_NAME . '-admin' );
	wp_enqueue_style( WWP_PLUGIN_NAME . '-whhg' );
	wp_enqueue_style( WWP_PLUGIN_NAME . '-jquery-ui' );
}

function wppActivation() {
	update_option( WWP_PLUGIN_NAME, WWP_PLUGIN_VERSION );
	if ( is_multisite() ) {
		update_site_option( WWP_PLUGIN_NAME, WWP_PLUGIN_VERSION );
	}
}

function wppDeactivation() {

}

function wppAddOptionsPage() {
	add_options_page( WWP_PLUGIN_TITLE_NAME, WWP_PLUGIN_DISPLAY_NAME, 'manage_options', WWP_PLUGIN_NAME, 'wppOptionsPage' );
}

function wppAddNetworkOptionsPage() {
	add_submenu_page( 'settings.php', WWP_PLUGIN_TITLE_NAME, WWP_PLUGIN_DISPLAY_NAME, 'manage_options', WWP_PLUGIN_NAME, 'wppNetworkOptionsPage' );
}

function wppOptionsPage() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( _e( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $isNetworkAdmin, $isSuperAdmin, $scriptSiteArr, $scriptSetArr, $locationArr, $typeArr, $areaArr;
	include_once WP_PLUGIN_DIR . '/' . WWP_PLUGIN_NAME . '/inc/optionsPage.php';
}

function wppNetworkOptionsPage() {
	if ( ! current_user_can( 'manage_network_options' ) ) {
		wp_die( _e( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $isNetworkAdmin, $isSuperAdmin, $scriptNetArr, $scriptSetArr, $locationArr, $typeArr, $areaArr;
	include_once WP_PLUGIN_DIR . '/' . WWP_PLUGIN_NAME . '/inc/networkOptionsPage.php';
}

function checkIsSuperAdmin() {
	global $isSuperAdmin;
	if ( is_multisite() ) {
		$isSuperAdmin = is_super_admin( wp_get_current_user() );
	}
}

function wppLoadList() {
	global $isSuperAdmin, $scriptSiteArr, $scriptNetArr, $isNetworkAdmin;

	if ( $isSuperAdmin and $isNetworkAdmin ) {
		$scriptNetArr = get_site_option( 'wp_personalize_script_net_arr', array() );
		echo json_encode( $scriptNetArr );
	} else {
		$scriptSiteArr = get_option( 'wp_personalize_script_arr', array() );
		echo json_encode( $scriptSiteArr );
	}

	die();
}

function wppUpdateScript() {
	global $scriptSiteArr, $scriptNetArr, $isAdmin, $isNetworkAdmin, $isSuperAdmin, $isNetworkAdmin;

	if ( $isSuperAdmin and $isNetworkAdmin ) {
		$scriptNetArr[ trim( $_POST['title'] ) ] = array(
			'title'    => $_POST['title'],
			'location' => $_POST['location'],
			'type'     => $_POST['type'],
			'area'     => $_POST['area'],
			'code'     => $_POST['codeEditor'],
		);

		$result = update_site_option( 'wp_personalize_script_net_arr', $scriptNetArr );
	} else {
		$scriptSiteArr[ trim( $_POST['title'] ) ] = array(
			'title'    => $_POST['title'],
			'location' => $_POST['location'],
			'type'     => $_POST['type'],
			'area'     => $_POST['area'],
			'code'     => $_POST['codeEditor'],
		);

		$result = update_option( 'wp_personalize_script_arr', $scriptSiteArr );
	}//end if

	if ( $result ) {
		echo json_encode( array( 'result' => 'true' ) );
	} else {
		echo json_encode( array( 'result' => 'false' ) );
	}

	die();
}

function wppUpdateSettings() {
	global $scriptSetArr, $isAdmin, $isNetworkAdmin, $isSuperAdmin, $isNetworkAdmin;

	if ( $isSuperAdmin and $isNetworkAdmin ) {
		$scriptSetArr['location'] = $_POST['location'];
		$scriptSetArr['type']     = $_POST['type'];
		$scriptSetArr['area']     = $_POST['area'];

		$result = update_site_option( 'wp_personalize_script_set_arr', $scriptSetArr );
	} else {
		$result = false;
	}

	if ( $result ) {
		echo json_encode( array( 'result' => 'true' ) );
	} else {
		echo json_encode( array( 'result' => 'false' ) );
	}

	die();
}

function wppLoadScript() {
	global $scriptSiteArr, $scriptNetArr, $isSuperAdmin, $isNetworkAdmin;

	if ( $isSuperAdmin and $isNetworkAdmin ) {
		$scriptArr = $scriptNetArr;
	} else {
		$scriptArr = $scriptSiteArr;
	}

	$scriptArr[ trim( $_POST['title'] ) ]['code'] = stripslashes( $scriptArr[ trim( $_POST['title'] ) ]['code'] );
	echo json_encode( $scriptArr[ trim( $_POST['title'] ) ] );

	die();
}

function wppDeleteScript() {
	global $scriptSiteArr, $scriptNetArr, $isSuperAdmin, $isNetworkAdmin;

	if ( $isSuperAdmin and $isNetworkAdmin ) {
		unset( $scriptNetArr[ trim( $_POST['title'] ) ] );
		$result = update_site_option( 'wp_personalize_script_net_arr', $scriptNetArr );
	} else {
		unset( $scriptSiteArr[ trim( $_POST['title'] ) ] );
		$result = update_option( 'wp_personalize_script_arr', $scriptSiteArr );
	}

	if ( $result ) {
		echo json_encode( array( 'result' => 'true' ) );
	} else {
		echo json_encode( array( 'result' => 'false' ) );
	}

	die();
}

function handleScripts( $scriptArr ) {
	global $wpHeadScript, $wpAdminHeadScript, $wpBodyTopScript, $wpBodyMidScript,
				 $wpBodyFootScript, $wpAdminBodyFootScript, $isAdmin, $isNetworkAdmin;

	foreach ( $scriptArr as $key => $value ) {
		$valueArray = $value;

		switch ( $valueArray['location'] ) {
			case 'head':
				if ( $valueArray['type'] != 'php' ) {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$wpAdminHeadScript .= stripslashes( $valueArray['code'] ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$wpHeadScript .= stripslashes( $valueArray['code'] ) . "\n";
					}
				} else {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$scriptTemp         = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpAdminHeadScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$scriptTemp    = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpHeadScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					}
				}
				break;
			case 'bodyTop':
				if ( $valueArray['type'] != 'php' ) {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$wpBodyTopScript .= stripslashes( $valueArray['code'] ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$wpBodyTopScript .= stripslashes( $valueArray['code'] ) . "\n";
					}
				} else {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$scriptTemp       = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpBodyTopScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$scriptTemp       = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpBodyTopScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					}
				}
				break;
			break;
			case 'bodyFoot':
				if ( $valueArray['type'] != 'php' ) {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$wpAdminBodyFootScript .= stripslashes( $valueArray['code'] ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$wpBodyFootScript .= stripslashes( $valueArray['code'] ) . "\n";
					}
				} else {
					if ( $isAdmin and in_array( $valueArray['area'], array( 'admin', 'both' ) ) ) {
						$scriptTemp             = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpAdminBodyFootScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					} elseif ( ! $isAdmin and in_array( $valueArray['area'], array( 'site', 'both' ) ) ) {
						$scriptTemp        = substr( $valueArray['code'], strpos( $valueArray['code'], "\n" ) + 1 );
						$wpBodyFootScript .= eval( stripslashes( $scriptTemp ) ) . "\n";
					}
				}
				break;
			default:
				// Do nothing
		}//end switch
	}//end foreach
}


function hookAdminHeadScript() {
	global $wpAdminHeadScript;

	echo $wpAdminHeadScript;
	hookBodyTopScript();
}

function hookHeadScript() {
	global $wpHeadScript;

	echo $wpHeadScript;
	hookBodyTopScript();
}

function hookBodyTopScript() {
	global $wpBodyTopScript;

	$wpBodyTopScript = addslashes( $wpBodyTopScript );
	$wpBodyTopScript = str_replace( '</', '<\/', $wpBodyTopScript );
	$wpBodyTopScript = str_replace( array( "\r", "\n" ), '', $wpBodyTopScript );
	?>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$('body').prepend('<?php echo $wpBodyTopScript; ?>');
		});
	</script>
	<?php
}

function hookAdminBodyFootScript() {
	global $wpAdminBodyFootScript;

	echo $wpAdminBodyFootScript;
}

function hookBodyFootScript() {
	global $wpBodyFootScript;

	echo $wpBodyFootScript;
}

?>
