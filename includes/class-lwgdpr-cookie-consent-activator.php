<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0
	 */
	public static function activate() {

    if (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {
      // Exclude from cache 'lwgdpr_*' cookies
      update_option( 'WpFastestCacheExclude', json_encode([["prefix" => "contain", "content" => "lwgdpr_", "type" => "cookie"]]));
      if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
        $GLOBALS['wp_fastest_cache']->deleteCache(true);
      }
    }
	}

}
