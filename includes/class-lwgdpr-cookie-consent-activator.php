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

    if (is_plugin_active('italy-cookie-choices/italy-cookie-choices.php')) {
      deactivate_plugins('italy-cookie-choices/italy-cookie-choices.php');
    } elseif (is_plugin_inactive('italy-cookie-choices/italy-cookie-choices.php')) {
      delete_plugins(array('italy-cookie-choices/italy-cookie-choices.php'));
    }

    if (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {
      if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
        $GLOBALS['wp_fastest_cache']->deleteCache(true);
      }
    }

		// Exclude from cache 'lwgdpr_*' cookies
		$WpFastestCacheExclude = get_option('WpFastestCacheExclude', []);
		$WpFastestCacheExclude = json_decode($WpFastestCacheExclude, true);
		$WpFastestCacheExclude[] = ["prefix" => "contain", "content" => "lwgdpr_", "type" => "cookie"];
		update_option('WpFastestCacheExclude', json_encode($WpFastestCacheExclude));
	}

}
