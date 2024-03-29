<?php
/**
 * Provide a admin area view for the general tab.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin/views
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$general_sub_tab = array(
	'settings-general'   => __( 'General', 'lwgdpr-cookie-consent' ),
	'show-again-general' => __( 'Show Again Tab', 'lwgdpr-cookie-consent' ),
	'other-general'      => __( 'Other', 'lwgdpr-cookie-consent' ),
	'import-export-settings-general'      => __( 'Import/Export Settings', 'lwgdpr-cookie-consent' ),
);
$general_sub_tab = apply_filters( 'gdprcookieconsent_general_sub_tabs', $general_sub_tab );
?>
<div class="lwgdpr-cookie-consent-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<ul class="lwgdpr_sub_tab">
		<?php foreach ( $general_sub_tab as $key => $value ) : ?>
			<li data-target="<?php echo esc_html( $key ); ?>"><a><?php echo esc_html( $value ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<div class="lwgdpr_sub_tab_container">
		<div class="lwgdpr_sub_tab_content" data-id="settings-general" style="display:block;">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="is_on_field"><?php esc_attr_e( 'Cookie Bar is currently', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="is_on_field_yes" name="is_on_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['is_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="is_on_field_no" name="is_on_field" class="styled" value="false" <?php echo ( false === $the_options['is_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cookie_usage_for_field"><?php esc_attr_e( 'Cookie Bar Usage for', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="cookie_usage_for_field" class="vvv_combobox lwgdpr_form_toggle lwgdpr_tab_form_toggle lwgdpr_nav_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-target="lwgdpr_usage_option">
							<?php $this->print_combobox_options( $this->get_cookie_usage_for_options(), $the_options['cookie_usage_for'] ); ?>
						</select>
					</td>
				</tr>
				<?php
				do_action( 'lwgdpr_module_settings_cookie_usage_for' );
				?>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="ccpa">
					<th scope="row"><label for="is_ccpa_iab_on_field"><?php esc_attr_e( 'Enable IAB Transparency and Consent Framework (TCF)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="is_ccpa_iab_on_field_yes" name="is_ccpa_iab_on_field" class="styled wpl_bar_on" value="true" <?php echo ( true === $the_options['is_ccpa_iab_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="is_ccpa_iab_on_field_no" name="is_ccpa_iab_on_field" class="styled" value="false" <?php echo ( false === $the_options['is_ccpa_iab_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Compatibility for the customization of advertising tracking preferences in case of CCPA.', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr">
					<th scope="row"><label for="bar_heading_text_field"><?php esc_attr_e( 'Message Heading', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="bar_heading_text_field" value="<?php echo esc_attr( $the_options['bar_heading_text'] ); ?>" />
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Leave it blank, If you do not need a heading', 'lwgdpr-cookie-consent' ); ?></span>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="eprivacy">
					<th scope="row"><label for="notify_message_eprivacy_field"><?php esc_attr_e( 'Message', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<textarea id="notify_message_eprivacy_field" name="notify_message_eprivacy_field" class="vvv_textbox"><?php echo wp_kses( apply_filters( 'format_to_edit', stripslashes( $the_options['notify_message_eprivacy'] ) ), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_html(), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_protocols() ); ?></textarea>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr">
					<th scope="row"><label for="notify_message_field"><?php esc_attr_e( 'GDPR Message', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<textarea id="notify_message_field" name="notify_message_field" class="vvv_textbox"><?php echo wp_kses( apply_filters( 'format_to_edit', stripslashes( $the_options['notify_message'] ) ), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_html(), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_protocols() ); ?></textarea>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="ccpa">
					<th scope="row"><label for="notify_message_ccpa_field"><?php esc_attr_e( 'CCPA Message', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<textarea id="notify_message_ccpa_field" name="notify_message_ccpa_field" class="vvv_textbox"><?php echo wp_kses( apply_filters( 'format_to_edit', stripslashes( $the_options['notify_message_ccpa'] ) ), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_html(), Lw_Gdpr_Cookie_Consent::lwgdpr_allowed_protocols() ); ?></textarea>
					</td>
				</tr>
				<?php
				// general settings form fields for module.
				do_action( 'lwgdpr_module_settings_general' );
				?>
			</table>
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="show-again-general" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="lwgdpr" lwgdpr_tab_frm_tgl-val1="eprivacy">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="show_again_field"><?php esc_attr_e( 'Show Again Tab', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="show_again_field_yes" name="show_again_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['show_again'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="show_again_field_no" name="show_again_field" class="styled" value="false" <?php echo ( false === $the_options['show_again'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" >
					<th scope="row"><label for="show_again_position_field"><?php esc_attr_e( 'Show Again Tab Position', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="show_again_position_field" class="vvv_combobox">
							<?php
							if ( 'left' === $the_options['show_again_position'] ) {
								?>
								<option value="left" selected="selected"><?php echo esc_attr__( 'Left', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="right"><?php echo esc_attr__( 'Right', 'lwgdpr-cookie-consent' ); ?></option>
							<?php } else { ?>
								<option value="left"><?php echo esc_attr__( 'Left', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="right" selected="selected"><?php echo esc_attr__( 'Right', 'lwgdpr-cookie-consent' ); ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="show_again_margin_field"><?php esc_attr_e( 'Show Again Tab Margin (in percent)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="number" step="1" min="0" max="100" name="show_again_margin_field" value="<?php echo esc_html( stripslashes( $the_options['show_again_margin'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="show_again_text_field"><?php esc_attr_e( 'Show Again Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="show_again_text_field" value="<?php echo esc_html( stripslashes( $the_options['show_again_text'] ) ); ?>" />
					</td>
				</tr>
				<?php
				// general settings form fields for module.
				do_action( 'lwgdpr_module_show_again_general' );
				?>
			</table>
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="other-general">
			<p></p>
			<table class="form-table">
				<?php
				do_action( 'lwgdpr_module_before_other_general' );
				?>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr">
					<th scope="row"><label for="is_ticked_field"><?php esc_attr_e( 'Autotick for Non-Necessary Cookies', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="is_ticked_field_yes" name="is_ticked_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['is_ticked'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="is_ticked_field_no" name="is_ticked_field" class="styled" value="false" <?php echo ( false === $the_options['is_ticked'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr" lwgdpr_frm_tgl-val1="eprivacy">
					<th scope="row"><label for="auto_hide_field"><?php esc_attr_e( 'Auto Hide (Accept)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="auto_hide_field_yes" lwgdpr_frm_tgl-target="lwgdpr_auto_hide" name="auto_hide_field" class="styled lwgdpr_bar_on lwgdpr_form_toggle" value="true" <?php echo ( true === $the_options['auto_hide'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="auto_hide_field_no" lwgdpr_frm_tgl-target="lwgdpr_auto_hide" name="auto_hide_field" class="styled lwgdpr_form_toggle" value="false" <?php echo ( false === $the_options['auto_hide'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_auto_hide" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="auto_hide_delay_field"><?php esc_attr_e( 'Auto Hide Delay (in Milliseconds)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="number" step="1000" min="5000" max="60000" name="auto_hide_delay_field" value="<?php echo esc_html( stripslashes( $the_options['auto_hide_delay'] ) ); ?>" />
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Specify milliseconds e.g. 5000 = 5 seconds', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr" lwgdpr_frm_tgl-val1="eprivacy">
					<th scope="row"><label for="auto_scroll_field"><?php esc_attr_e( 'Auto Scroll (Accept)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="auto_scroll_field_yes" lwgdpr_frm_tgl-target="lwgdpr_auto_scroll" name="auto_scroll_field" class="styled lwgdpr_form_toggle lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['auto_scroll'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="auto_scroll_field_no" lwgdpr_frm_tgl-target="lwgdpr_auto_scroll" name="auto_scroll_field" class="styled lwgdpr_form_toggle" value="false" <?php echo ( false === $the_options['auto_scroll'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Use this option with discretion especially if you serve EU', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_auto_scroll" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="auto_scroll_offset_field"><?php esc_attr_e( 'Auto Scroll Offset (in percent)', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="number" step="1" min="10" max="100" name="auto_scroll_offset_field" value="<?php echo esc_html( stripslashes( $the_options['auto_scroll_offset'] ) ); ?>" />
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Consent will be assumed after user scrolls more than the specified page height', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr" lwgdpr_frm_tgl-val1="eprivacy">
					<th scope="row"><label for="auto_scroll_reload_field"><?php esc_attr_e( 'Reload after Scroll Accept', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="auto_scroll_reload_yes" name="auto_scroll_reload_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['auto_scroll_reload'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="auto_scroll_reload_no" name="auto_scroll_reload_field" class="styled" value="false" <?php echo ( false === $the_options['auto_scroll_reload'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="accept_reload_field"><?php esc_attr_e( 'Reload after Accept', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="accept_reload_yes" name="accept_reload_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['accept_reload'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="accept_reload_no" name="accept_reload_field" class="styled" value="false" <?php echo ( false === $the_options['accept_reload'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr" lwgdpr_frm_tgl-val1="eprivacy">
					<th scope="row"><label for="decline_reload_field"><?php esc_attr_e( 'Reload after Decline', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="decline_reload_yes" name="decline_reload_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['decline_reload'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="decline_reload_no" name="decline_reload_field" class="styled" value="false" <?php echo ( false === $the_options['decline_reload'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<?php
				// general settings form fields for module.
				do_action( 'lwgdpr_module_other_general' );
				?>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr" lwgdpr_frm_tgl-val1="eprivacy">
					<th scope="row"><label for="cookie_expiry_field"><?php esc_attr_e( 'Cookie Expiry', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="cookie_expiry_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_cookie_expiry_options(), $the_options['cookie_expiry'] ); ?>
						</select>
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'The amount of time that the cookie should be stored for.', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="delete_on_deactivation_field"><?php esc_attr_e( 'Delete Plugin data on Deactivation', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="delete_on_deactivation_field_yes" name="delete_on_deactivation_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['delete_on_deactivation'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="delete_on_deactivation_field_no" name="delete_on_deactivation_field" class="styled" value="false" <?php echo ( false === $the_options['delete_on_deactivation'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Enable if you want all plugin data to be deleted on deactivation.', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="show_credits_field"><?php esc_attr_e( 'Show Credits', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="show_credits_field_yes" name="show_credits_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['show_credits'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="show_credits_field_no" name="show_credits_field" class="styled" value="false" <?php echo ( false === $the_options['show_credits'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="import-export-settings-general">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php esc_attr_e( 'Export Settings', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<a href="#" id="lwgdpr_export_settings" class="button button-secondary"><?php esc_attr_e( 'Export', 'lwgdpr-cookie-consent' ); ?></a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="import_settings_json"><?php esc_attr_e( 'Import Settings', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="file" id="import_settings_json" name="import_settings_json" value="" class="all-options" accept=".json" />
						<a href="#" id="lwgdpr_import_settings" class="button button-primary"><?php esc_attr_e( 'Import', 'lwgdpr-cookie-consent' ); ?></a>
					</td>
				</tr>
			</table>
		</div>
		<?php do_action( 'lwgdpr_settings_general_tab' ); ?>
	</div>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>
