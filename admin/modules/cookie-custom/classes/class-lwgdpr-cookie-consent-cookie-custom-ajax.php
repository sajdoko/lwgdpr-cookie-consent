<?php
/**
 * The custom cookie functionality of the plugin.
 *
 * @link       https://www.localweb.it//
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific ajax functionality for custom cookie.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin/modules
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Cookie_Custom_Ajax extends Lw_Gdpr_Cookie_Consent_Cookie_Custom {

	/**
	 * Lw_Gdpr_Cookie_Consent_Cookie_Custom_Ajax constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_lwgdpr_cookie_custom', array( $this, 'ajax_cookie_custom' ) );
	}

	/**
	 * Main ajax hook for processing request.
	 */
	public function ajax_cookie_custom() {
		$out = array(
			'response' => false,
			'message'  => __( 'Unable to handle your request.', 'lwgdpr-cookie-consent' ),
		);
		if ( isset( $_POST['lwgdpr_custom_action'] ) ) {
			check_admin_referer( 'lwgdpr_cookie_custom', 'security' );
			$lwgdpr_custom_action = sanitize_text_field( wp_unslash( $_POST['lwgdpr_custom_action'] ) );
			$allowed_actions    = array( 'post_cookie_list', 'save_post_cookie', 'update_post_cookie', 'delete_post_cookie' );
			if ( in_array( $lwgdpr_custom_action, $allowed_actions, true ) && method_exists( $this, $lwgdpr_custom_action ) ) {
				$out = $this->{$lwgdpr_custom_action}();
			}
		}
		echo wp_json_encode( $out );
		exit();
	}

	/**
	 * Ajax processing for save manual cookies.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function save_post_cookie() {
		$out = array(
			'response' => false,
			'message'  => __( 'Unable to add cookie', 'lwgdpr-cookie-consent' ),
		);
		if ( isset( $_POST['cookie_arr'] ) ) {
			check_admin_referer( 'lwgdpr_cookie_custom', 'security' );

			$cname     = isset( $_POST['cookie_arr']['cname'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['cname'] ) ) : '';
			$cdomain   = isset( $_POST['cookie_arr']['cdomain'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['cdomain'] ) ) : '';
			$ccategory = isset( $_POST['cookie_arr']['ccategory'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['ccategory'] ) ) : '';
			$ctype     = isset( $_POST['cookie_arr']['ctype'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['ctype'] ) ) : '1';
			$cduration = isset( $_POST['cookie_arr']['cduration'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['cduration'] ) ) : '';
			$cdesc     = isset( $_POST['cookie_arr']['cdesc'] ) ? sanitize_text_field( wp_unslash( $_POST['cookie_arr']['cdesc'] ) ) : '';
			global $wpdb;
			$cat_data_arr = $wpdb->get_row( $wpdb->prepare( 'SELECT lwgdpr_cookie_category_name FROM ' . $wpdb->prefix . 'lwgdpr_cookie_scan_categories WHERE id_lwgdpr_cookie_category=%d', array( $ccategory ) ), ARRAY_A ); // db call ok; no-cache ok.
			if ( $cat_data_arr ) {
				$ccategoryname = $cat_data_arr['lwgdpr_cookie_category_name'];
			}
			$post_cookies_table = $wpdb->prefix . $this->post_cookies_table;
			$data_arr           = array(
				'name'        => $cname,
				'domain'      => $cdomain,
				'category'    => $ccategoryname,
				'category_id' => $ccategory,
				'type'        => $ctype,
				'description' => $cdesc,
				'duration'    => $cduration,
			);
			$insert_status      = $wpdb->insert( $post_cookies_table, $data_arr ); // db call ok; no-cache ok.
			if ( $insert_status ) {
				$out['response'] = true;
				$out['message']  = __( 'Cookie added successfully', 'lwgdpr-cookie-consent' );
			}
		}
		return $out;
	}

	/**
	 * Ajax processing for update manual cookies.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function update_post_cookie() {
		$out = array(
			'response' => false,
			'message'  => __( 'Unable to update cookies', 'lwgdpr-cookie-consent' ),
		);
		if ( isset( $_POST['cookie_arr'] ) ) {
			check_admin_referer( 'lwgdpr_cookie_custom', 'security' );
			$cookie_arr = gdprcc_clean( wp_unslash( $_POST['cookie_arr'] ) ); // phpcs:ignore input var ok, CSRF ok, sanitization ok.
			$flag       = 0;
			foreach ( $cookie_arr as $cookie ) {
				$cid       = isset( $cookie['cid'] ) ? sanitize_text_field( $cookie['cid'] ) : '';
				$cname     = isset( $cookie['cname'] ) ? sanitize_text_field( $cookie['cname'] ) : '';
				$cdomain   = isset( $cookie['cdomain'] ) ? sanitize_text_field( $cookie['cdomain'] ) : '';
				$ccategory = isset( $cookie['ccategory'] ) ? sanitize_text_field( $cookie['ccategory'] ) : '';
				$ctype     = isset( $cookie['ctype'] ) ? sanitize_text_field( $cookie['ctype'] ) : '1';
				$cduration = isset( $cookie['cduration'] ) ? sanitize_text_field( $cookie['cduration'] ) : '';
				$cdesc     = isset( $cookie['cdesc'] ) ? sanitize_text_field( $cookie['cdesc'] ) : '';
				global $wpdb;
				$cat_data_arr = $wpdb->get_row( $wpdb->prepare( 'SELECT lwgdpr_cookie_category_name FROM ' . $wpdb->prefix . 'lwgdpr_cookie_scan_categories WHERE id_lwgdpr_cookie_category=%d', array( $ccategory ) ), ARRAY_A ); // db call ok; no-cache ok.
				if ( $cat_data_arr ) {
					$ccategoryname = $cat_data_arr['lwgdpr_cookie_category_name'];
				}
				$post_cookies_table = $wpdb->prefix . $this->post_cookies_table;
				$data_arr           = array();
				if ( ! empty( $cname ) ) {
					$data_arr['name'] = $cname;
				}
				if ( ! empty( $cdomain ) ) {
					$data_arr['domain'] = $cdomain;
				}
				if ( ! empty( $ccategoryname ) ) {
					$data_arr['category'] = $ccategoryname;
				}
				if ( ! empty( $ccategory ) ) {
					$data_arr['category_id'] = $ccategory;
				}
				if ( ! empty( $ctype ) ) {
					$data_arr['type'] = $ctype;
				}
				if ( ! empty( $cdesc ) ) {
					$data_arr['description'] = $cdesc;
				}
				if ( ! empty( $cduration ) ) {
					$data_arr['duration'] = $cduration;
				}

				$update_status = $wpdb->update( $post_cookies_table, $data_arr, array( 'id_lwgdpr_cookie_post_cookies' => $cid ) ); // db call ok; no-cache ok.
				if ( $update_status >= 1 ) {
					$flag = 1;
				} elseif ( 0 === $update_status ) {
					$out['message'] = __( 'No data was modified on the form.', 'lwgdpr-cookie-consent' );
				}
			}
			if ( 1 === $flag ) {
				$out['response'] = true;
				$out['message']  = __( 'Cookies updated successfully', 'lwgdpr-cookie-consent' );
			}
		}
		return $out;
	}

	/**
	 * Ajax processing for delete manual cookies.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function delete_post_cookie() {
		$out = array(
			'response' => false,
			'message'  => __( 'Unable to delete cookie', 'lwgdpr-cookie-consent' ),
		);
		if ( isset( $_POST['cookie_id'] ) ) {
			check_admin_referer( 'lwgdpr_cookie_custom', 'security' );
			$cookie_id = sanitize_text_field( wp_unslash( $_POST['cookie_id'] ) );

			global $wpdb;
			$post_cookies_table = $wpdb->prefix . $this->post_cookies_table;
			$delete_status      = $wpdb->delete( $post_cookies_table, array( 'id_lwgdpr_cookie_post_cookies' => $cookie_id ) ); // db call ok; no-cache ok.
			if ( $delete_status ) {
				$out['response'] = true;
				$out['message']  = __( 'Cookie deleted successfully', 'lwgdpr-cookie-consent' );
			}
		}
		return $out;
	}

	/**
	 * Ajax processing for manual cookie list.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function post_cookie_list() {
		$out = array(
			'response' => false,
		);
		check_admin_referer( 'lwgdpr_cookie_custom', 'security' );
		$post_cookie_list = $this->get_post_cookie_list();
		$view             = plugin_dir_path( LW_GDPR_COOKIE_CONSENT_PLUGIN_FILENAME ) . 'admin/modules/cookie-custom/views/custom-cookie-list.php';
		ob_start();
		include $view;
		$contents = ob_get_clean();
		ob_get_flush();
		$out['response'] = true;
		$out['message']  = __( 'Success', 'lwgdpr-cookie-consent' );
		$out['content']  = $contents;
		return $out;
	}
}
new Lw_Gdpr_Cookie_Consent_Cookie_Custom_Ajax();
