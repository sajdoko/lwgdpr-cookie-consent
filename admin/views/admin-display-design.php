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
$design_sub_tab = array(
	'design-general' => __( 'General', 'lwgdpr-cookie-consent' ),
);
$design_sub_tab = apply_filters( 'gdprcookieconsent_design_sub_tabs', $design_sub_tab );
?>
<div class="lwgdpr-cookie-consent-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<ul class="lwgdpr_sub_tab">
		<?php foreach ( $design_sub_tab as $key => $value ) : ?>
			<li data-target="<?php echo esc_html( $key ); ?>"><a><?php echo esc_html( $value ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<div class="lwgdpr_sub_tab_container">
		<div class="lwgdpr_sub_tab_content" data-id="design-general" style="display:block;">
			<p></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="cookie_bar_as_field"><?php esc_attr_e( 'Cookie Bar as', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="cookie_bar_as_field" class="vvv_combobox lwgdpr_form_toggle" lwgdpr_frm_tgl-target="lwgdpr_cookiebar_as">
							<?php $this->print_combobox_options( $this->get_cookie_design_options(), $the_options['cookie_bar_as'] ); ?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_cookiebar_as" lwgdpr_frm_tgl-val="banner">
					<th scope="row"><label for="notify_position_vertical_field"><?php esc_attr_e( 'Cookie Bar Position', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="notify_position_vertical_field" class="vvv_combobox">
							<?php
							if ( 'bottom' === $the_options['notify_position_vertical'] ) {
								?>
								<option value="bottom" selected="selected"><?php echo esc_attr__( 'Bottom', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="top"><?php echo esc_attr__( 'Top', 'lwgdpr-cookie-consent' ); ?></option>
							<?php } else { ?>
								<option value="bottom"><?php echo esc_attr__( 'Bottom', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="top" selected="selected"><?php echo esc_attr__( 'Top', 'lwgdpr-cookie-consent' ); ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_cookiebar_as" lwgdpr_frm_tgl-val="popup">
					<th scope="row"><label for="popup_overlay_field"><?php esc_attr_e( 'Add Overlay', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="radio" id="popup_overlay_field_yes" name="popup_overlay_field" class="styled lwgdpr_bar_on" value="true" <?php echo ( true === $the_options['popup_overlay'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'On', 'lwgdpr-cookie-consent' ); ?>
						<input type="radio" id="popup_overlay_field_no" name="popup_overlay_field" class="styled" value="false" <?php echo ( false === $the_options['popup_overlay'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Off', 'lwgdpr-cookie-consent' ); ?>
					</td>
				</tr>
				<tr valign="top" class="lwgdpr-plugin-row" lwgdpr_frm_tgl-id="lwgdpr_cookiebar_as" lwgdpr_frm_tgl-val="widget">
					<th scope="row"><label for="notify_position_horizontal_field"><?php esc_attr_e( 'Cookie Bar Position', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="notify_position_horizontal_field" class="vvv_combobox">
							<?php
							if ( 'left' === $the_options['notify_position_horizontal'] ) {
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
					<th scope="row"><label for="notify_animate_hide_field"><?php esc_attr_e( 'On hide', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<select name="notify_animate_hide_field" class="vvv_combobox">
							<?php
							if ( true === $the_options['notify_animate_hide'] ) {
								?>
								<option value="true" selected="selected"><?php echo esc_attr__( 'Animate', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="false"><?php echo esc_attr__( 'Disappear', 'lwgdpr-cookie-consent' ); ?></option>
							<?php } else { ?>
								<option value="true"><?php echo esc_attr__( 'Animate', 'lwgdpr-cookie-consent' ); ?></option>
								<option value="false" selected="selected"><?php echo esc_attr__( 'Disappear', 'lwgdpr-cookie-consent' ); ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="background_field"><?php esc_attr_e( 'Cookie Bar Color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="background_field" id="lwgdpr-color-background" value="<?php echo esc_attr( $the_options['background'] ); ?>" class="lwgdpr-color-field" data-default-color="#fff" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="opacity_field"><?php esc_attr_e( 'Cookie Bar Opacity', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input id="range-opacity-slider" type="range" max="1" min="0" step="0.01" name="opacity_field" onchange="lwgdpr_print_value('range-opacity-slider','range-opacity-input')" value="<?php echo esc_attr( $the_options['opacity'] ); ?>" /><input type="text" id="range-opacity-input" name="opacity_field" value="<?php echo esc_attr( $the_options['opacity'] ); ?>" style="display: inline-block;width: 10%;margin-left: 10px;"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="text_field"><?php esc_attr_e( 'Text Color', 'lwgdpr-cookie-consent' ); ?></label></th>
					<td>
						<input type="text" name="text_field" id="lwgdpr-color-text" value="<?php echo esc_attr( $the_options['text'] ); ?>" class="lwgdpr-color-field" data-default-color="#000" />
					</td>
				</tr>
				<?php
				// messagebar settings form fields for module.
				do_action( 'lwgdpr_module_settings_design' );
				?>
			</table>
		</div>
		<?php do_action( 'lwgdpr_settings_design_tab' ); ?>
	</div>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>
