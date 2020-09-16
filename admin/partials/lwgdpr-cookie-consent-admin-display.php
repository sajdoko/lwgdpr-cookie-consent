<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$lwgdpr_admin_view_path = plugin_dir_path( LW_GDPR_COOKIE_CONSENT_PLUGIN_FILENAME ) . 'admin/views/';
?>
<script type="text/javascript">
	var lwgdpr_settings_success_message='<?php echo esc_attr__( 'Settings updated.', 'lwgdpr-cookie-consent' ); ?>';
	var lwgdpr_settings_error_message='<?php echo esc_attr__( 'Unable to update Settings.', 'lwgdpr-cookie-consent' ); ?>';
</script>
<div style="clear:both;"></div>
<div class="wrap">
	<div class="nav-tab-wrapper wp-clearfix lwgdpr-cookie-consent-tab-head">
		<?php
		$tab_head_arr = array(
			'lwgdpr-cookie-consent-general'        => __( 'General', 'lwgdpr-cookie-consent' ),
			'lwgdpr-cookie-consent-design'         => __( 'Design', 'lwgdpr-cookie-consent' ),
			'lwgdpr-cookie-consent-buttons'        => __( 'Buttons', 'lwgdpr-cookie-consent' ),
			'lwgdpr-cookie-consent-script-blocker' => __( 'Script Blocker', 'lwgdpr-cookie-consent' ),
		);
		Lw_Gdpr_Cookie_Consent::lwgdpr_generate_settings_tabhead( $tab_head_arr );
		?>
	</div>
	<div class="lwgdpr_settings_left">
	<div class="lwgdpr-cookie-consent-tab-container">
		<?php
		$display_views_a = array(
			'lwgdpr-cookie-consent-general'        => 'admin-display-general.php',
			'lwgdpr-cookie-consent-design'         => 'admin-display-design.php',
			'lwgdpr-cookie-consent-buttons'        => 'admin-display-buttons.php',
			'lwgdpr-cookie-consent-script-blocker' => 'admin-display-script-blocker.php',
		);
		?>
		<form method="post" action="
		<?php
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			echo esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );}
		?>
		" id="lwgdpr_settings_form">
			<input type="hidden" name="lwgdpr_update_action" value="" id="lwgdpr_update_action" />
			<?php
			// Set nonce.
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( 'gdprcookieconsent-update-' . LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD );
			}
			foreach ( $display_views_a as $target_id => $value ) {
				$display_view = $lwgdpr_admin_view_path . $value;
				if ( file_exists( $display_view ) ) {
					include $display_view;
				}
			}
			// settings form fields for module.
			do_action( 'lwgdpr_module_settings_form' );
			?>
		</form>
	</div>
</div>
	<div class="lwgdpr_settings_right">
		<?php
		require plugin_dir_path( LW_GDPR_COOKIE_CONSENT_PLUGIN_FILENAME ) . 'admin/views/admin-display-promotional.php';
		?>
	</div>
</div>
