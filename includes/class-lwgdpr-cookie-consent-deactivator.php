<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0
	 */
	public static function deactivate() {
		$the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
		if ( isset( $the_options['delete_on_deactivation'] ) && true === $the_options['delete_on_deactivation'] ) {
			global $wpdb;
			$tables_arr = array(
				'lwgdpr_cookie_post_cookies',
				'lwgdpr_cookie_scan_categories',
			);
			foreach ( $tables_arr as $table ) {
				$tablename = $wpdb->prefix . $table;
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $tablename ); // phpcs:ignore
			}
			delete_option( 'lwgdpr_admin_modules' );
			delete_option( 'lwgdpr_public_modules' );
			delete_option( LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD );
		}
	}

}
