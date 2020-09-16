<?php
/**
 * Provide a admin area view for the import page.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin/views
 * @author     sajdoko <https://www.localweb.it/>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="wrap">
	<h2><?php esc_attr_e( 'Import from a CSV file', 'lwgdpr-cookie-consent' ); ?></h2>
	<?php
	if ( isset( $_GET['import'] ) ) { // phpcs:ignore

		switch ( $_GET['import'] ) { // phpcs:ignore
			case 'file':
				echo '<div class="error"><p><strong>' . esc_attr__( 'Error during file upload.', 'lwgdpr-cookie-consent' ) . '</strong></p></div>';
				break;
			case 'data':
				echo '<div class="error"><p><strong>' . esc_attr__( 'Cannot extract data from uploaded file or no file was uploaded.', 'lwgdpr-cookie-consent' ) . '</strong></p></div>';
				break;
			case 'fail':
				echo '<div class="error"><p><strong>' . esc_attr__( 'No posts was successfully imported.', 'lwgdpr-cookie-consent' ) . '</strong></p></div>';
				break;
			case 'errors':
				echo '<div class="error"><p><strong>' . esc_attr__( 'Some posts were successfully imported but some were not.', 'lwgdpr-cookie-consent' ) . '</strong></p></div>';
				break;
			case 'success':
				echo '<div class="updated"><p><strong>' . esc_attr__( 'Post import was successful.', 'lwgdpr-cookie-consent' ) . '</strong></p></div>';
				break;
			default:
				break;
		}
	}
	?>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'lwgdpr-policies-import-page', '_wpnonce-lwgdpr-policies-import-page' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="policies_csv"><?php esc_attr_e( 'CSV File', 'lwgdpr-cookie-consent' ); ?></label></th>
				<td>
					<input type="file" id="policies_csv" name="policies_csv" value="" class="all-options" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td>
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Import', 'lwgdpr-cookie-consent' ); ?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
