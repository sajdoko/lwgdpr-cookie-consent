<?php
/**
 * Provide a admin area view for the buttons tab.
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
$buttons_sub_tab = array(
	'accept-button'    => __( 'Accept Button', 'lwgdpr-cookie-consent' ),
	'reject-button'    => __( 'Decline Button', 'lwgdpr-cookie-consent' ),
	'settings-button'  => __( 'Settings Button', 'lwgdpr-cookie-consent' ),
	'read-more-button' => __( 'Read More Link', 'lwgdpr-cookie-consent' ),
	'confirm-button'   => __( 'Confirm Button', 'lwgdpr-cookie-consent' ),
	'cancel-button'    => __( 'Cancel Button', 'lwgdpr-cookie-consent' ),
	'donotsell-button' => __( 'Optout Link', 'lwgdpr-cookie-consent' ),
);
$buttons_sub_tab = apply_filters( 'gdprcookieconsent_buttons_sub_tabs', $buttons_sub_tab );
?>
<div class="lwgdpr-cookie-consent-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<ul class="lwgdpr_sub_tab">
		<?php foreach ( $buttons_sub_tab as $key => $value ) : ?>
			<li data-target="<?php echo esc_html( $key ); ?>"><a><?php echo esc_html( $value ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<div class="lwgdpr_sub_tab_container">
		<div class="lwgdpr_sub_tab_content" data-id="accept-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="lwgdpr" lwgdpr_tab_frm_tgl-val1="eprivacy">
			<p></p>
			<p><?php esc_attr_e( 'This button/link can be customized to either simply close the cookie bar, or follow a link. You can also customize the colors and styles, and show it as a link or a button.', 'lwgdpr-cookie-consent' ); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_accept_is_on_field"><?php esc_attr_e( 'Enable', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_accept_is_on_field_yes" name="button_accept_is_on_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_accept_is_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_accept_is_on_field_no" name="button_accept_is_on_field" class="styled" value="false" <?php echo ( false === $the_options['button_accept_is_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_accept_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_accept_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_accept_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_accept_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_accept_link_color_field" id="lwgdpr-color-link-button-accept" value="<?php echo esc_attr( $the_options['button_accept_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_accept_as_button_field"><?php esc_attr_e( 'Show as', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" lwgdpr_frm_tgl-target="lwgdpr_accept_type" id="button_accept_as_button_field_yes" name="button_accept_as_button_field" class="styled lwgdpr_form_toggle" value="true" <?php echo ( true === $the_options['button_accept_as_button'] ) ? ' checked="checked"' : ' '; ?> /> <?php esc_attr_e( 'Button', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" lwgdpr_frm_tgl-target="lwgdpr_accept_type" id="button_accept_as_button_field_no" name="button_accept_as_button_field" class="styled lwgdpr_form_toggle" value="false" <?php echo ( false === $the_options['button_accept_as_button'] ) ? ' checked="checked"' : ''; ?>  /> <?php esc_attr_e( 'Link', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_accept_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_accept_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_accept_button_color_field" id="lwgdpr-color-btn-button-accept" value="<?php echo esc_attr( $the_options['button_accept_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_accept_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_accept_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_accept_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_accept_button_size'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_accept_action_field"><?php esc_attr_e( 'Action', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_accept_action_field" id="lwgdpr-plugin-button-accept-action" class="vvv_combobox lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_accept_action">
							<?php $this->print_combobox_options( $this->get_js_actions(), $the_options['button_accept_action'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_accept_action" lwgdpr_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_accept_url_field"><?php esc_attr_e( 'URL', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_accept_url_field" id="button_accept_url_field" value="<?php echo esc_attr( $the_options['button_accept_url'] ); ?>" />
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Button will only link to URL if Action = Open URL', 'lwgdpr-cookie-consent' ); ?></span>
					</td>
				</tr>

				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_accept_action" lwgdpr_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_accept_new_win_field"><?php esc_attr_e( 'Open URL in new window?', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_accept_new_win_field_yes" name="button_accept_new_win_field" class="styled" value="true" <?php echo ( true === $the_options['button_accept_new_win'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Yes', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_accept_new_win_field_no" name="button_accept_new_win_field" class="styled" value="false" <?php echo ( false === $the_options['button_accept_new_win'] ) ? ' checked="checked"' : ''; ?> /> <?php esc_attr_e( 'No', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="reject-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="lwgdpr" lwgdpr_tab_frm_tgl-val1="eprivacy">
			<p></p>
			<table class="form-table" >
				<tr valign="top">
					<th scope="row"><label for="button_decline_is_on_field"><?php esc_attr_e( 'Enable', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_decline_is_on_field_yes" name="button_decline_is_on_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_decline_is_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_decline_is_on_field_no" name="button_decline_is_on_field" class="styled" value="false" <?php echo ( false === $the_options['button_decline_is_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_decline_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_decline_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_decline_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_decline_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_decline_link_color_field" id="lwgdpr-color-link-button-decline" value="<?php echo esc_attr( $the_options['button_decline_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_decline_as_button_field"><?php esc_attr_e( 'Show as', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_decline_as_button_field_yes" name="button_decline_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_reject_type" value="true" <?php echo ( true === $the_options['button_decline_as_button'] ) ? ' checked="checked"' : ' '; ?>  /> <?php esc_attr_e( 'Button', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_decline_as_button_field_no" name="button_decline_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_reject_type" value="false" <?php echo ( false === $the_options['button_decline_as_button'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Link', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_reject_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_decline_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_decline_button_color_field" id="lwgdpr-color-btn-button-decline" value="<?php echo esc_attr( $the_options['button_decline_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_reject_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_decline_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_decline_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_decline_button_size'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_decline_action_field"><?php esc_attr_e( 'Action', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_decline_action_field" id="lwgdpr-plugin-button-decline-action" class="vvv_combobox lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_reject_action">
							<?php
								$action_list = $this->get_js_actions();
								$action_list[ __( 'Close Header', 'lwgdpr-cookie-consent' ) ] = '#cookie_action_close_header_reject';
								$this->print_combobox_options( $action_list, $the_options['button_decline_action'] );
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_reject_action" lwgdpr_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_decline_url_field"><?php esc_attr_e( 'URL', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_decline_url_field" id="button_decline_url_field" value="<?php echo esc_attr( $the_options['button_decline_url'] ); ?>" />
						<span class="lwgdpr_form_help"><?php esc_attr_e( 'Button will only link to URL if Action = Open URL', 'lwgdpr-cookie-consent' ); ?></span>
					</td>
				</tr>

				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_reject_action" lwgdpr_frm_tgl-val="CONSTANT_OPEN_URL">
					<th scope="row"><label for="button_decline_new_win_field"><?php esc_attr_e( 'Open URL in new window?', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_decline_new_win_field_yes" name="button_decline_new_win_field" class="styled" value="true" <?php echo ( true === $the_options['button_decline_new_win'] ) ? ' checked="checked"' : ''; ?>  /><?php esc_attr_e( 'Yes', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_decline_new_win_field_no" name="button_decline_new_win_field" class="styled" value="false" <?php echo ( false === $the_options['button_decline_new_win'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'No', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="settings-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="lwgdpr">
			<p></p>
			<table class="form-table" >
				<tr valign="top">
					<th scope="row"><label for="button_settings_is_on_field"><?php esc_attr_e( 'Enable', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_settings_is_on_field_yes" name="button_settings_is_on_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_settings_is_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_settings_is_on_field_no" name="button_settings_is_on_field" class="styled" value="false" <?php echo ( false === $the_options['button_settings_is_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_settings_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_settings_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_settings_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_settings_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_settings_link_color_field" id="lwgdpr-color-link-button-settings" value="<?php echo esc_attr( $the_options['button_settings_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_settings_as_button_field"><?php esc_attr_e( 'Show as', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_settings_as_button_field_yes" name="button_settings_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_settings_type" value="true" <?php echo ( true === $the_options['button_settings_as_button'] ) ? ' checked="checked"' : ' '; ?>  /> <?php esc_attr_e( 'Button', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_settings_as_button_field_no" name="button_settings_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_settings_type" value="false" <?php echo ( false === $the_options['button_settings_as_button'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Link', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_settings_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_settings_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_settings_button_color_field" id="lwgdpr-color-btn-button-settings" value="<?php echo esc_attr( $the_options['button_settings_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_settings_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_settings_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_settings_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_settings_button_size'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_settings_display_cookies_field"><?php esc_attr_e( 'Display Cookies List on Frontend', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_settings_display_cookies_field_yes" name="button_settings_display_cookies_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_settings_display_cookies'] ) ? ' checked="checked"' : ' '; ?>  /> <?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_settings_display_cookies_field_no" name="button_settings_display_cookies_field" class="styled" value="false" <?php echo ( false === $the_options['button_settings_display_cookies'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_cookiebar_as" lwgdpr_frm_tgl-val="banner">
					<th scope="row"><label for="button_settings_as_popup_field"><?php esc_attr_e( 'Cookie Settings Layout', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_settings_as_popup_field" class="vvv_combobox">
							<?php
							if ( $the_options['button_settings_as_popup'] ) {
								?>
								<option value="true" selected="selected"><?php echo esc_attr__( 'Popup', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="false"><?php echo esc_attr__( 'Extended Banner', 'lwgdpr-cookie-consent' ); ?></option>
							<?php } else { ?>
								<option value="true"><?php echo esc_attr__( 'Popup', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="false" selected="selected"><?php echo esc_attr__( 'Extended Banner', 'lwgdpr-cookie-consent' ); ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="read-more-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="lwgdpr" lwgdpr_tab_frm_tgl-val1="eprivacy">
			<p></p>
			<p><?php esc_attr_e( 'This button/link can be used to provide a link out to your Privacy & Cookie Policy. You can customize it any way you like.', 'lwgdpr-cookie-consent' ); ?></p>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_readmore_is_on_field"><?php esc_attr_e( 'Enable', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_readmore_is_on_field_yes" name="button_readmore_is_on_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_readmore_is_on'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_readmore_is_on_field_no" name="button_readmore_is_on_field" class="styled" value="false" <?php echo ( false === $the_options['button_readmore_is_on'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_readmore_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_readmore_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_readmore_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_readmore_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_readmore_link_color_field" id="lwgdpr-color-link-button-readmore" value="<?php echo esc_attr( $the_options['button_readmore_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_readmore_as_button_field"><?php esc_attr_e( 'Show as', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_readmore_as_button_field_yes" name="button_readmore_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_readmore_type" value="true" <?php echo ( true === $the_options['button_readmore_as_button'] ) ? ' checked="checked"' : ''; ?>  /> <?php esc_attr_e( 'Button', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_readmore_as_button_field_no" name="button_readmore_as_button_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_readmore_type" value="false" <?php echo ( false === $the_options['button_readmore_as_button'] ) ? ' checked="checked"' : ''; ?> /> <?php esc_attr_e( 'Link', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_readmore_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_readmore_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_readmore_button_color_field" id="lwgdpr-color-btn-button-readmore" value="<?php echo esc_attr( $the_options['button_readmore_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_readmore_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_readmore_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_readmore_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_readmore_button_size'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_readmore_url_type_field"><?php esc_attr_e( 'Page or Custom URL', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_readmore_url_type_field_yes" name="button_readmore_url_type_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_readmore_url_type" value="true" <?php echo ( true === $the_options['button_readmore_url_type'] ) ? ' checked="checked"' : ''; ?>  /> <?php esc_attr_e( 'Page', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_readmore_url_type_field_no" name="button_readmore_url_type_field" class="styled lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_readmore_url_type" value="false" <?php echo ( false === $the_options['button_readmore_url_type'] ) ? ' checked="checked"' : ''; ?> /> <?php esc_attr_e( 'Custom Link', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<?php $readmore_pages = $this->get_readmore_pages(); ?>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_readmore_url_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_readmore_page_field"><?php esc_attr_e( 'Page', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_readmore_page_field" class="vvv_combobox">
							<option value="0">Select Privacy Page</option>
							<?php foreach ( $readmore_pages as $r_page ) : ?>
								<option value="<?php echo esc_attr( $r_page->ID ); ?>" <?php echo ( $r_page->ID === (int) $the_options['button_readmore_page'] ? 'selected' : '' ); ?>><?php echo esc_html( $r_page->post_title ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_readmore_url_type" lwgdpr_frm_tgl-val="true">
					<th scope="row"><label for="button_readmore_wp_page_field"><?php esc_attr_e( 'Synchronize with WordPress Policy Page', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_readmore_wp_page_field_yes" name="button_readmore_wp_page_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['button_readmore_wp_page'] ) ? ' checked="checked"' : ''; ?>  /> <?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_readmore_wp_page_field_no" name="button_readmore_wp_page_field" class="styled" value="false" <?php echo ( false === $the_options['button_readmore_wp_page'] ) ? ' checked="checked"' : ''; ?> /> <?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_readmore_url_type" lwgdpr_frm_tgl-val="false">
					<th scope="row"><label for="button_readmore_url_field"><?php esc_attr_e( 'URL', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_readmore_url_field" id="button_readmore_url_field" value="<?php echo esc_attr( $the_options['button_readmore_url'] ); ?>" />
					</td>
				</tr>

				<tr valign="top" class="lwgdpr-plugin-row">
					<th scope="row"><label for="button_decline_new_win_field"><?php esc_attr_e( 'Open URL in new window?', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="button_readmore_new_win_field_yes" name="button_readmore_new_win_field" class="styled" value="true" <?php echo ( true === $the_options['button_readmore_new_win'] ) ? ' checked="checked"' : ''; ?>  /><?php esc_attr_e( 'Yes', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="button_readmore_new_win_field_no" name="button_readmore_new_win_field" class="styled" value="false" <?php echo ( false === $the_options['button_readmore_new_win'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'No', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="confirm-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="ccpa">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_confirm_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_confirm_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_confirm_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_confirm_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_confirm_link_color_field" id="lwgdpr-color-link-button-confirm" value="<?php echo esc_attr( $the_options['button_confirm_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_confirm_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_confirm_button_color_field" id="lwgdpr-color-btn-button-confirm" value="<?php echo esc_attr( $the_options['button_confirm_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_confirm_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_confirm_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_confirm_button_size'] ); ?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="cancel-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="ccpa">
			<p></p>
			<table class="form-table" >
				<tr valign="top">
					<th scope="row"><label for="button_cancel_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_cancel_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_cancel_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_cancel_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_cancel_link_color_field" id="lwgdpr-color-link-button-cancel" value="<?php echo esc_attr( $the_options['button_cancel_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_cancel_button_color_field"><?php esc_attr_e( 'Background color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_cancel_button_color_field" id="lwgdpr-color-btn-button-cancel" value="<?php echo esc_attr( $the_options['button_cancel_button_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_cancel_button_size_field"><?php esc_attr_e( 'Size', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="button_cancel_button_size_field" class="vvv_combobox">
							<?php $this->print_combobox_options( $this->get_button_sizes(), $the_options['button_cancel_button_size'] ); ?>
						</select>
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
		<div class="lwgdpr_sub_tab_content" data-id="donotsell-button" lwgdpr_tab_frm_tgl-id="lwgdpr_usage_option" lwgdpr_tab_frm_tgl-val="ccpa">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="button_donotsell_text_field"><?php esc_attr_e( 'Text', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_donotsell_text_field" value="<?php echo esc_html( stripslashes( $the_options['button_donotsell_text'] ) ); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="button_donotsell_link_color_field"><?php esc_attr_e( 'Text color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="button_donotsell_link_color_field" id="lwgdpr-color-link-button-donotsell" value="<?php echo esc_attr( $the_options['button_donotsell_link_color'] ); ?>" class="lwgdpr-color-field" />
					</td>
				</tr>
			</table><!-- end custom button -->
		</div>
	</div>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>
