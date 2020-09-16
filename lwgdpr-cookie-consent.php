<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://www.localweb.it/
 * @since             1.0
 * @package           Lw_Gdpr_Cookie_Consent
 *
 * @wordpress-plugin
 * Plugin Name:       LWGDPR Cookie Consent
 * Plugin URI:        https://www.localweb.it//
 * Description:       Soluzione conforme al GDPR per informare gli utenti che il sito Web utilizza i cookie, con la possibilità di bloccare gli script prima del consenso.
 * Version:           1.0.0
 * Author:            Local Web S.R.L
 * Author URI:        https://www.localweb.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lwgdpr-cookie-consent
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/sajdoko/lwgdpr-cookie-consent',
	__FILE__,
	'lwgdpr-cookie-consent'
);
$myUpdateChecker->setBranch('master');

define( 'LW_GDPR_COOKIE_CONSENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Currently plugin version.
 */
define( 'LW_GDPR_COOKIE_CONSENT_VERSION', '1.0.0' );
define( 'LW_GDPR_COOKIE_CONSENT_PLUGIN_DEVELOPMENT_MODE', false );
define( 'LW_GDPR_COOKIE_CONSENT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'LW_GDPR_COOKIE_CONSENT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LW_GDPR_COOKIE_CONSENT_DB_KEY_PREFIX', 'LWGDPR_cookie_consent_' );
define( 'LW_GDPR_COOKIE_CONSENT_LATEST_VERSION_NUMBER', '1' );
define( 'LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD', LW_GDPR_COOKIE_CONSENT_DB_KEY_PREFIX . LW_GDPR_COOKIE_CONSENT_LATEST_VERSION_NUMBER );
define( 'LW_GDPR_COOKIE_CONSENT_PLUGIN_FILENAME', __FILE__ );
define( 'LW_GDPR_POLICY_DATA_POST_TYPE', 'gdprpolicies' );
define( 'LW_GDPR_CSV_DELIMITER', ',' );
if ( ! defined( 'LW_GDPR_CC_SUFFIX' ) ) {
	define( 'LW_GDPR_CC_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min' );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 *
 * @return string|array
 */
function lwgdprcc_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'lwgdprcc_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lwgdpr-cookie-consent-activator.php
 */
function activate_lwgdpr_cookie_consent() {
	require_once LW_GDPR_COOKIE_CONSENT_PLUGIN_PATH . 'includes/class-lwgdpr-cookie-consent-activator.php';
	Lw_Gdpr_Cookie_Consent_Activator::activate();
	register_uninstall_hook( __FILE__, 'uninstall_lwgdpr_cookie_consent' );
	add_option( 'analytics_activation_redirect_gdpr-cookie-consent', true );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lwgdpr-cookie-consent-deactivator.php
 */
function deactivate_lwgdpr_cookie_consent() {
	require_once LW_GDPR_COOKIE_CONSENT_PLUGIN_PATH . 'includes/class-lwgdpr-cookie-consent-deactivator.php';
	Lw_Gdpr_Cookie_Consent_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lwgdpr_cookie_consent' );
register_deactivation_hook( __FILE__, 'deactivate_lwgdpr_cookie_consent' );

require plugin_dir_path( __FILE__ ) . 'includes/class-lwgdpr-cookies-read-csv.php';

/**
 * Delete all settings related to plugin.
 */
function uninstall_lwgdpr_cookie_consent() {
	delete_option( LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD );
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require LW_GDPR_COOKIE_CONSENT_PLUGIN_PATH . 'includes/class-lwgdpr-cookie-consent.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_lwgdpr_cookie_consent() {

	$plugin = new Lw_Gdpr_Cookie_Consent();
	$plugin->run();

}
run_lwgdpr_cookie_consent();
