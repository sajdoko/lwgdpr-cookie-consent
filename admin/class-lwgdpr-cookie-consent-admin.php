<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Admin module list, Module folder and main file must be same as that of module name.
	 * Please check the `admin_modules` method for more details.
	 *
	 * @since 1.0
	 * @access private
	 * @var array $modules Admin module list.
	 */
	private $modules = array(
		'cookie-custom',
		'policy-data',
	);

	/**
	 * Existing modules array.
	 *
	 * @since 1.0
	 * @access public
	 * @var array $existing_modules Existing modules array.
	 */
	public static $existing_modules = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Lw_Gdpr_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lw_Gdpr_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style('wp-color-picker');
		wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lwgdpr-consent-admin' . LW_GDPR_CC_SUFFIX . '.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Lw_Gdpr_Cookie_Consent_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lw_Gdpr_Cookie_Consent_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lwgdpr-consent-admin' . LW_GDPR_CC_SUFFIX . '.js', array('jquery', 'wp-color-picker', 'gdprcookieconsent_cookie_custom'), $this->version, false);
	}

	/**
	 * Register admin modules
	 *
	 * @since 1.0
	 */
	public function admin_modules() {
		$initialize_flag    = false;
		$active_flag        = false;
		$non_active_flag    = false;
		$lwgdpr_admin_modules = get_option('lwgdpr_admin_modules');
		if (false === $lwgdpr_admin_modules) {
			$initialize_flag    = true;
			$lwgdpr_admin_modules = array();
		}
		foreach ($this->modules as $module) {
			$is_active = 1;
			if (isset($lwgdpr_admin_modules[$module])) {
				$is_active = $lwgdpr_admin_modules[$module]; // checking module status.
				if (1 === $is_active) {
					$active_flag = true;
				}
			} else {
				$active_flag                   = true;
				$lwgdpr_admin_modules[$module] = 1; // default status is active.
			}
			$module_file = plugin_dir_path(__FILE__) . "modules/$module/class-lwgdpr-cookie-consent-$module.php";
			if (file_exists($module_file) && 1 === $is_active) {
				self::$existing_modules[] = $module; // this is for module_exits checking.
				require_once $module_file;
			} else {
				$non_active_flag               = true;
				$lwgdpr_admin_modules[$module] = 0;
			}
		}
		if ($initialize_flag || ($active_flag && $non_active_flag)) {
			$out = array();
			foreach ($lwgdpr_admin_modules as $k => $m) {
				if (in_array($k, $this->modules, true)) {
					$out[$k] = $m;
				}
			}
			update_option('lwgdpr_admin_modules', $out);
		}
	}

	/**
	 * Registers menu options, hooked into admin_menu.
	 *
	 * @since 1.0
	 */
	public function admin_menu() {
		add_menu_page('Cookie', __('Cookies', 'lwgdpr-cookie-consent'), 'manage_options', 'lwgdpr-cookie-consent', array($this, 'admin_settings_page'), LW_GDPR_COOKIE_CONSENT_PLUGIN_URL . 'admin/images/lwgdpr_icon.png', 80);
		add_submenu_page('lwgdpr-cookie-consent', __('Cookie Settings', 'lwgdpr-cookie-consent'), __('Cookie Settings', 'lwgdpr-cookie-consent'), 'manage_options', 'lwgdpr-cookie-consent', array($this, 'admin_settings_page'));
	}

	/**
	 * Admin settings page.
	 *
	 * @since 1.0
	 */
	public function admin_settings_page() {
		wp_enqueue_style($this->plugin_name);
		wp_enqueue_script($this->plugin_name);
		wp_localize_script(
			$this->plugin_name,
			'lwgdpr_admin_ajax_object',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce($this->plugin_name),
			)
		);
		// Lock out non-admins.
		if (!current_user_can('manage_options')) {
			wp_die(esc_attr__('You do not have sufficient permission to perform this operation', 'lwgdpr-cookie-consent'));
		}
		// Get options.
		$the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
		// Check if form has been set.
		if (isset($_POST['update_admin_settings_form']) || (isset($_POST['lwgdpr_settings_ajax_update']))) {
			// Check nonce.
			check_admin_referer('gdprcookieconsent-update-' . LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD);

			if ('update_admin_settings_form' === $_POST['lwgdpr_settings_ajax_update']) {
				// module settings saving hook.
				do_action('lwgdpr_module_save_settings');
				foreach ($the_options as $key => $value) {
					if (isset($_POST[$key . '_field'])) {
						// Store sanitised values only.
						$the_options[$key] = Lw_Gdpr_Cookie_Consent::lwgdpr_sanitise_settings($key, wp_unslash($_POST[$key . '_field'])); // phpcs:ignore input var ok, CSRF ok, sanitization ok.
					}
				}
				switch ($the_options['cookie_bar_as']) {
					case 'banner':
						$the_options['template'] = $the_options['banner_template'];
						break;
					case 'popup':
						$the_options['template'] = $the_options['popup_template'];
						break;
					case 'widget':
						$the_options['template'] = $the_options['widget_template'];
						break;
				}
				$the_options = apply_filters('lwgdpr_module_after_save_settings', $the_options);
				update_option(LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD, $the_options);
				echo '<div class="updated"><p><strong>' . esc_attr__('Settings Updated.', 'lwgdpr-cookie-consent') . '</strong></p></div>';
			}
		}
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(sanitize_text_field(wp_unslash($_SERVER['HTTP_X_REQUESTED_WITH']))) === 'xmlhttprequest') {
			exit();
		}
		require_once plugin_dir_path(__FILE__) . 'partials/lwgdpr-cookie-consent-admin-display.php';
	}

	public function lwgdpr_export_settings() {
		if (!current_user_can('manage_options')) {
			return;
		}
		if (!check_ajax_referer($this->plugin_name, 'security')) {
			wp_send_json_error(array('message' => __('Security is not valid!', 'lwgdpr-cookie-consent')));
			die();
		}
		if (isset($_POST['action']) && $_POST['action'] === "lwgdpr_export_settings") {
			// Export settings.
			$the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
			$export_data = array(
				'settings' => array(),
				'cookies' => array(),
			);
			foreach ($the_options as $key => $value) {
				$export_data['settings'][$key] = $value;
			}

			global $wpdb;
			$data_arr = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'lwgdpr_cookie_post_cookies ORDER BY id_lwgdpr_cookie_post_cookies DESC'), ARRAY_A);
			if (is_array($data_arr) && !empty($data_arr)) {
				foreach ($data_arr as $data) {
					$export_data['cookies'][] = $data;
				}
			}

			$export_data = apply_filters('lwgdpr_module_after_export_settings', $export_data);
			wp_send_json_success(array('settings' => $export_data, 'message' => __('Exported!', 'lwgdpr-cookie-consent')));
			die();
		} else {
			wp_send_json_error(array('message' => __('Action is not valid!', 'lwgdpr-cookie-consent')));
			die();
		}
	}

	public function lwgdpr_import_settings() {
		if (!current_user_can('manage_options')) {
			return;
		}
		if (!check_ajax_referer($this->plugin_name, 'security')) {
			wp_send_json_error(array('message' => __('Security is not valid!', 'lwgdpr-cookie-consent')));
			die();
		}
		if (isset($_POST['action']) && $_POST['action'] === "lwgdpr_import_settings") {
			// Check if file is uploaded
			if (isset($_FILES['import_settings_json']['tmp_name'])) {
				$file_contents = file_get_contents($_FILES['import_settings_json']['tmp_name']);
				$settings = json_decode($file_contents, true);

				if (is_array($settings)) {
					if (isset($settings['settings'])) {
						$the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
						foreach ($the_options as $key => $value) {
							if (isset($settings['settings'][$key])) {
								// Store sanitised values only.
								$the_options[$key] = Lw_Gdpr_Cookie_Consent::lwgdpr_sanitise_settings($key, wp_unslash($settings['settings'][$key])); // phpcs:ignore input var ok, CSRF ok, sanitization ok.
							}
						}
						update_option(LW_GDPR_COOKIE_CONSENT_SETTINGS_FIELD, $the_options);
					}

					if (isset($settings['cookies'])) {
						global $wpdb;
						$wpdb->query('TRUNCATE TABLE ' . $wpdb->prefix . 'lwgdpr_cookie_post_cookies');
						foreach ($settings['cookies'] as $cookie) {
							$data_arr = array(
								'name'        => sanitize_text_field( wp_unslash( $cookie['name'] ) ),
								'domain'      => sanitize_text_field( wp_unslash( $cookie['domain'] ) ),
								'duration'    => sanitize_text_field( wp_unslash( $cookie['duration'] ) ),
								'type'        => sanitize_text_field( wp_unslash( $cookie['type'] ) ),
								'category'    => sanitize_text_field( wp_unslash( $cookie['category'] ) ),
								'category_id' => sanitize_text_field( wp_unslash( $cookie['category_id'] ) ),
								'description' => sanitize_text_field( wp_unslash( $cookie['description'] ) ),
							);
							$wpdb->insert($wpdb->prefix . 'lwgdpr_cookie_post_cookies', $data_arr);
						}
					}

					wp_send_json_success(array('message' => __('Settings imported successfully.', 'lwgdpr-cookie-consent')));
				} else {
					wp_send_json_error(array('message' => __('Invalid file format.', 'lwgdpr-cookie-consent')));
				}
			} else {
				wp_send_json_error(array('message' => __('No file uploaded.', 'lwgdpr-cookie-consent')));
			}

			die();
		} else {
			wp_send_json_error(array('message' => __('Action is not valid!', 'lwgdpr-cookie-consent')));
			die();
		}
	}


	/**
	 * Register block.
	 *
	 * @since 1.8.4
	 */
	public function lwgdpr_register_block_type() {
		if (!function_exists('register_block_type')) {
			return;
		}
		wp_register_script(
			$this->plugin_name . '-block',
			plugin_dir_url(__FILE__) . 'js/blocks/lwgdpr-admin-block.js',
			array(
				'jquery',
				'wp-blocks',
				'wp-i18n',
				'wp-editor',
				'wp-element',
				'wp-components',
			),
			$this->version,
			true
		);
		register_block_type(
			'lwgdpr/block',
			array(
				'editor_script'   => $this->plugin_name . '-block',
				'render_callback' => array($this, 'lwgdpr_block_render_callback'),
			)
		);
	}

	/**
	 * Render callback for block.
	 *
	 * @since 1.8.4
	 * @return string
	 */
	public function lwgdpr_block_render_callback() {
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if (method_exists($screen, 'is_block_editor')) {
				wp_enqueue_script($this->plugin_name . '-block');
			}
		}
		if (has_block('lwgdpr/block', get_the_ID())) {
			wp_enqueue_script($this->plugin_name . '-block');
		}
		$styles = '';
		if (defined('REST_REQUEST') && REST_REQUEST) {
			$styles = 'border: 1px solid #767676';
		}
		$args                = array(
			'numberposts' => -1,
			'post_type'   => 'gdprpolicies',
		);
		$wp_legalpolicy_data = get_posts($args);
		$content             = '';
		if (is_array($wp_legalpolicy_data) && !empty($wp_legalpolicy_data)) {
			$content .= '<p>For further information on how we use cookies, please refer to the table below.</p>';
			$content .= "<div class='wp_legalpolicy' style='overflow-x:scroll;overflow:auto;'>";
			$content .= '<table style="width:100%;margin:0 auto;border-collapse:collapse;">';
			$content .= '<thead>';
			$content .= "<th style='" . $styles . "'>Third Party Companies</th><th style='" . $styles . "'>Purpose</th><th style='" . $styles . "'>Applicable Privacy/Cookie Policy Link</th>";
			$content .= '</thead>';
			$content .= '<tbody>';
			foreach ($wp_legalpolicy_data as $policypost) {
				$content .= '<tr>';
				$content .= "<td style='" . $styles . "'>" . $policypost->post_title . '</td>';
				$content .= "<td style='" . $styles . "'>" . $policypost->post_content . '</td>';
				$links    = get_post_meta($policypost->ID, '_lwgdpr_policies_links_editor');
				$content .= "<td style='" . $styles . "'>" . $links[0] . '</td>';
				$content .= '</tr>';
			}
			$content .= '</tbody></table></div>';
		}
		return $content;
	}

	/**
	 * Prints a combobox based on options and selected=match value.
	 *
	 * @since 1.0
	 * @param array  $options Array of options.
	 * @param string $selected Which of those options should be selected (allows just one; is case sensitive).
	 */
	public function print_combobox_options($options, $selected) {
		foreach ($options as $key => $value) {
			echo '<option value="' . esc_html($value) . '"';
			if ($value === $selected) {
				echo ' selected="selected"';
			}
			echo '>' . esc_html($key) . '</option>';
		}
	}

	/**
	 * Return cookie expiry options.
	 *
	 * @since 1.8.1
	 */
	public function get_cookie_expiry_options() {
		$options = array(
			__('1 Ora', 'lwgdpr-cookie-consent')  => '' . number_format(1 / 24, 2) . '',
			__('1 Giorno', 'lwgdpr-cookie-consent')    => '1',
			__('1 Settimana', 'lwgdpr-cookie-consent')   => '7',
			__('1 Mese', 'lwgdpr-cookie-consent')  => '30',
			__('3 Mesi', 'lwgdpr-cookie-consent') => '90',
			__('6 Mesi', 'lwgdpr-cookie-consent') => '180',
			__('1 Anno', 'lwgdpr-cookie-consent')   => '365',
		);
		$options = apply_filters('gdprcookieconsent_cookie_expiry_options', $options);
		return $options;
	}

	/**
	 * Return cookie usage options.
	 *
	 * @since 1.8.1
	 */
	public function get_cookie_usage_for_options() {
		$options = array(
			__('ePrivacy', 'lwgdpr-cookie-consent')    => 'eprivacy',
			__('GDPR', 'lwgdpr-cookie-consent')        => 'lwgdpr',
			__('CCPA', 'lwgdpr-cookie-consent')        => 'ccpa',
			__('GDPR & CCPA', 'lwgdpr-cookie-consent') => 'both',
		);
		$options = apply_filters('gdprcookieconsent_cookie_usage_for_options', $options);
		return $options;
	}

	/**
	 * Return cookie design options.
	 *
	 * @since 1.8.1
	 */
	public function get_cookie_design_options() {
		$options = array(
			__('Banner', 'lwgdpr-cookie-consent') => 'banner',
			__('Popup', 'lwgdpr-cookie-consent')  => 'popup',
			__('Widget', 'lwgdpr-cookie-consent') => 'widget',
		);
		$options = apply_filters('gdprcookieconsent_cookie_design_options', $options);
		return $options;
	}

	/**
	 * Returns button sizes, used when printing admin form.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_button_sizes() {
		$sizes = array(
			__('Large', 'lwgdpr-cookie-consent')  => 'large',
			__('Medium', 'lwgdpr-cookie-consent') => 'medium',
			__('Small', 'lwgdpr-cookie-consent')  => 'small',
		);
		$sizes = apply_filters('gdprcookieconsent_sizes', $sizes);
		return $sizes;
	}

	/**
	 * Return WordPress policy pages for Readmore button.
	 *
	 * @since 1.9.0
	 * @return mixed|void
	 */
	public function get_readmore_pages() {
		$args           = array(
			'sort_order'   => 'ASC',
			'sort_column'  => 'post_title',
			'hierarchical' => 0,
			'child_of'     => 0,
			'parent'       => -1,
			'offset'       => 0,
			'post_type'    => 'page',
			'post_status'  => 'publish',
		);
		$readmore_pages = get_pages($args);
		return apply_filters('gdprcookieconsent_readmore_pages', $readmore_pages);
	}

	/**
	 * Returns list of available jQuery actions, used by buttons/links in header.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_js_actions() {
		$js_actions = array(
			__('Close Header', 'lwgdpr-cookie-consent') => '#cookie_action_close_header',
			__('Open URL', 'lwgdpr-cookie-consent')     => 'CONSTANT_OPEN_URL',   // Don't change this value, is used by jQuery.
		);
		return $js_actions;
	}
}
