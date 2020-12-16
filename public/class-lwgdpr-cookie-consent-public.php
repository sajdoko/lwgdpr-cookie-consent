<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/public
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Public {

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
	 * Public module list, Module folder and main file must be same as that of module name.
	 * Please check the `public_modules` method for more details.
	 *
	 * @since 1.0
	 * @access private
	 * @var array $modules Admin module list.
	 */
	private $modules = array();
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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( ! shortcode_exists( 'lw_shortcode_cookie_details' ) ) {
			add_shortcode( 'lw_shortcode_cookie_details', array( $this, 'gdprcookieconsent_shortcode_cookie_details' ) );         // a shortcode [lw_shortcode_cookie_details].
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lwgdpr-consent-public' . LW_GDPR_CC_SUFFIX . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_register_script( $this->plugin_name . '-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lwgdpr-consent-public' . LW_GDPR_CC_SUFFIX . '.js#async', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Register public modules
	 *
	 * @since 1.0
	 */
	public function public_modules() {
		$initialize_flag     = false;
		$active_flag         = false;
		$non_active_flag     = false;
		$lwgdpr_public_modules = get_option( 'lwgdpr_public_modules' );
		if ( false === $lwgdpr_public_modules ) {
			$lwgdpr_public_modules = array();
			$initialize_flag     = true;
		}
		foreach ( $this->modules as $module ) {
			$is_active = 1;
			if ( isset( $lwgdpr_public_modules[ $module ] ) ) {
				$is_active = $lwgdpr_public_modules[ $module ]; // checking module status.
				if ( 1 === $is_active ) {
					$active_flag = true;
				}
			} else {
				$active_flag                    = true;
				$lwgdpr_public_modules[ $module ] = 1; // default status is active.
			}
			$module_file = plugin_dir_path( __FILE__ ) . "modules/$module/class-lwgdpr-cookie-consent-$module.php";
			if ( file_exists( $module_file ) && 1 === $is_active ) {
				self::$existing_modules[] = $module; // this is for module_exits checking.
				require_once $module_file;
			} else {
				$non_active_flag                = true;
				$lwgdpr_public_modules[ $module ] = 0;
			}
		}
		if ( $initialize_flag || ( $active_flag && $non_active_flag ) ) {
			$out = array();
			foreach ( $lwgdpr_public_modules as $k => $m ) {
				if ( in_array( $k, $this->modules, true ) ) {
					$out[ $k ] = $m;
				}
			}
			update_option( 'lwgdpr_public_modules', $out );
		}
	}

	/**
	 * Removes leading # characters from a string.
	 *
	 * @since 1.0
	 * @param string $str String from hash to be removed.
	 *
	 * @return bool|string
	 */
	public static function gdprcookieconsent_remove_hash( $str ) {
		if ( '#' === $str[0] ) {
			$str = substr( $str, 1, strlen( $str ) );
		} else {
			return $str;
		}
		return self::gdprcookieconsent_remove_hash( $str );
	}

	/**
	 * Parse enqueue url for async parameter.
	 *
	 * @since 1.8.5
	 * @param string $url URL.
	 * @return mixed|string
	 */
	public function gdprcookieconsent_clean_async_url( $url ) {
		if ( strpos( $url, '#async' ) === false ) {
			return $url;
		} elseif ( is_admin() ) {
			return str_replace( '#async', '', $url );
		} else {
			return str_replace( '#async', '', $url ) . "' async='async";
		}
	}

	/**
	 * Outputs the cookie control script in the footer.
	 * This function should be attached to the wp_footer action hook.
	 *
	 * @since 1.0
	 */
	public function gdprcookieconsent_inject_lwgdpr_script() {
		$the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
		if ( true === $the_options['is_on'] ) {
			if ( 'ccpa' === $the_options['cookie_usage_for'] || 'both' === $the_options['cookie_usage_for'] ) {
				wp_enqueue_script( $this->plugin_name . '-uspapi', plugin_dir_url( __FILE__ ) . 'js/iab/uspapi.js', array( 'jquery' ), $this->version, false );
			}
			wp_enqueue_style( $this->plugin_name );
			wp_enqueue_script( $this->plugin_name . '-bootstrap-js' );
			wp_enqueue_script( $this->plugin_name );
			wp_localize_script( $this->plugin_name, 'log_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			add_filter( 'clean_url', array( $this, 'gdprcookieconsent_clean_async_url' ) );
			$timber           = new Timber\Timber();
			$lwgdpr_message     = '';
			$ccpa_message     = '';
			$eprivacy_message = '';
			// Output the HTML in the footer.
			if ( 'eprivacy' === $the_options['cookie_usage_for'] ) {
				$eprivacy_message               = nl2br( $the_options['notify_message_eprivacy'] );
				$the_options['eprivacy_notify'] = true;
			}
			if ( 'lwgdpr' === $the_options['cookie_usage_for'] ) {
				$lwgdpr_message               = nl2br( $the_options['notify_message'] );
				$the_options['lwgdpr_notify'] = true;
			}
			if ( 'ccpa' === $the_options['cookie_usage_for'] ) {
				$ccpa_message                  = nl2br( $the_options['notify_message_ccpa'] );
				$the_options['ccpa_notify']    = true;
				$the_options['optout_text']    = __( 'Do you really wish to opt-out?', 'lwgdpr-cookie-consent' );
				$the_options['confirm_button'] = __( 'Confirm', 'lwgdpr-cookie-consent' );
				$the_options['cancel_button']  = __( 'Cancel', 'lwgdpr-cookie-consent' );
			}
			if ( 'both' === $the_options['cookie_usage_for'] ) {
				$lwgdpr_message                  = nl2br( $the_options['notify_message'] );
				$ccpa_message                  = nl2br( $the_options['notify_message_ccpa'] );
				$the_options['lwgdpr_notify']    = true;
				$the_options['ccpa_notify']    = true;
				$the_options['optout_text']    = __( 'Do you really wish to opt-out?', 'lwgdpr-cookie-consent' );
				$the_options['confirm_button'] = __( 'Confirm', 'lwgdpr-cookie-consent' );
				$the_options['cancel_button']  = __( 'Cancel', 'lwgdpr-cookie-consent' );
			}
			$about_message    = stripslashes( nl2br( $the_options['about_message'] ) );
			$eprivacy_message = stripslashes( $eprivacy_message );
			$lwgdpr_message     = stripslashes( $lwgdpr_message );
			$ccpa_message     = stripslashes( $ccpa_message );
			$eprivacy_str     = $eprivacy_message;
			$lwgdpr_str         = $lwgdpr_message;
			$ccpa_str         = $ccpa_message;
			$head             = $the_options['bar_heading_text'];
			$head             = trim( stripslashes( $head ) );
			$default_array    = array( 'none', 'default', 'classic' );
			$template         = $the_options['template'];
			if ( 'none' !== $template ) {
				$template_parts = explode( '-', $template );
				$template       = array_pop( $template_parts );
			}
			$the_options['container_class'] = $the_options['cookie_usage_for'] . ' lwgdpr-' . $the_options['cookie_bar_as'] . ' lwgdpr-' . $template;
			if ( in_array( $template, $default_array, true ) ) {
				$template = 'default';
			}
			$template                                 = apply_filters( 'gdprcookieconsent_template', $template );
			$the_options['eprivacy_str']              = $eprivacy_str;
			$the_options['lwgdpr_str']                  = $lwgdpr_str;
			$the_options['ccpa_str']                  = $ccpa_str;
			$the_options['head']                      = $head;
			$the_options['version']                   = $this->version;
			$the_options['show_again_container_id']   = $this->gdprcookieconsent_remove_hash( $the_options['show_again_div_id'] );
			$the_options['container_id']              = $this->gdprcookieconsent_remove_hash( $the_options['notify_div_id'] );
			$the_options['button_accept_action_id']   = $this->gdprcookieconsent_remove_hash( $the_options['button_accept_action'] );
			$the_options['button_readmore_action_id'] = $this->gdprcookieconsent_remove_hash( $the_options['button_readmore_action'] );
			$the_options['button_decline_action_id']  = $this->gdprcookieconsent_remove_hash( $the_options['button_decline_action'] );
			$the_options['button_settings_action_id'] = $this->gdprcookieconsent_remove_hash( $the_options['button_settings_action'] );

			$the_options['backdrop'] = $the_options['popup_overlay'] ? 'static' : 'false';

			$credit_link_href = 'https://www.localweb.it/';

			if ( 'lwgdpr' === $the_options['cookie_usage_for'] ) {
				$credit_link_text = __( 'GDPR Cookie Consent Plugin', 'lwgdpr-cookie-consent' );
			} elseif ( 'ccpa' === $the_options['cookie_usage_for'] ) {
				$credit_link_text = __( 'CCPA Cookie Notice Plugin', 'lwgdpr-cookie-consent' );
			} elseif ( 'eprivacy' === $the_options['cookie_usage_for'] ) {
				$credit_link_text = __( 'Cookie Notice Plugin', 'lwgdpr-cookie-consent' );
			} elseif ( 'both' === $the_options['cookie_usage_for'] ) {
				$credit_link_text = __( 'GDPR & CCPA Notice plugin', 'lwgdpr-cookie-consent' );
			} else {
				$credit_link_text = __( 'GDPR Cookie Consent Plugin', 'lwgdpr-cookie-consent' );
			}
			$credit_link = sprintf(
				/* translators: 1: GDPR Cookie Consent Plugin*/
				__( 'Powered by %1$s', 'lwgdpr-cookie-consent' ),
				'<a href="' . esc_url( $credit_link_href ) . '" id="cookie_credit_link" rel="nofollow noopener" target="_blank">' . $credit_link_text . '</a>'
			);

			$button_readmore_url_link = '';
			if ( true === $the_options['button_readmore_url_type'] ) {
				if ( true === $the_options['button_readmore_wp_page'] ) {
					$button_readmore_url_link = get_privacy_policy_url();
				}
				if ( empty( $button_readmore_url_link ) ) {
					if ( '0' !== $the_options['button_readmore_page'] ) {
						$button_readmore_url_link = get_page_link( $the_options['button_readmore_page'] );
					} else {
						$button_readmore_url_link = '#';
					}
				}
			} else {
				$button_readmore_url_link = $the_options['button_readmore_url'];
			}
			$the_options['button_readmore_url_link'] = $button_readmore_url_link;

			$the_options['button_accept_classes'] = 'lwgdpr_action_button ';
			if ( $the_options['button_accept_as_button'] ) {
				switch ( $the_options['button_accept_button_size'] ) {
					case 'medium':
						$the_options['button_accept_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_accept_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_accept_classes'] .= 'btn btn-sm';
						break;
				}
			}
			$the_options['button_readmore_classes'] = '';
			if ( $the_options['button_readmore_as_button'] ) {
				switch ( $the_options['button_readmore_button_size'] ) {
					case 'medium':
						$the_options['button_readmore_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_readmore_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_readmore_classes'] .= 'btn btn-sm';
						break;
				}
			} else {
				$the_options['button_readmore_classes'] = 'lwgdpr_link_button';
			}
			$the_options['button_decline_classes'] = 'lwgdpr_action_button ';
			if ( $the_options['button_decline_as_button'] ) {
				switch ( $the_options['button_decline_button_size'] ) {
					case 'medium':
						$the_options['button_decline_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_decline_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_decline_classes'] .= 'btn btn-sm';
						break;
				}
			}
			$the_options['button_settings_classes'] = 'lwgdpr_action_button ';
			if ( $the_options['button_settings_as_button'] ) {
				switch ( $the_options['button_settings_button_size'] ) {
					case 'medium':
						$the_options['button_settings_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_settings_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_settings_classes'] .= 'btn btn-sm';
						break;
				}
			}
			$the_options['button_donotsell_classes'] = 'lwgdpr_action_button lwgdpr_link_button';
			$the_options['button_confirm_classes']   = 'lwgdpr_action_button ';
			if ( $the_options['button_accept_as_button'] ) {
				switch ( $the_options['button_confirm_button_size'] ) {
					case 'medium':
						$the_options['button_confirm_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_confirm_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_confirm_classes'] .= 'btn btn-sm';
						break;
				}
			}
			$the_options['button_cancel_classes'] = 'lwgdpr_action_button ';
			if ( $the_options['button_cancel_as_button'] ) {
				switch ( $the_options['button_cancel_button_size'] ) {
					case 'medium':
						$the_options['button_cancel_classes'] .= 'btn';
						break;
					case 'large':
						$the_options['button_cancel_classes'] .= 'btn btn-lg';
						break;
					case 'small':
						$the_options['button_cancel_classes'] .= 'btn btn-sm';
						break;
				}
			}
			$categories                   = Lw_Gdpr_Cookie_Consent_Cookie_Custom::get_categories( true );
			$cookies                      = $this->get_cookies();
			$categories_data              = array();
			$preference_cookies           = isset( $_COOKIE['lwgdpr_user_preference'] ) ? json_decode( stripslashes( sanitize_text_field( wp_unslash( $_COOKIE['lwgdpr_user_preference'] ) ) ), true ) : '';
			$viewed_cookie                = isset( $_COOKIE['lwgdpr_viewed_cookie'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['lwgdpr_viewed_cookie'] ) ) : '';
			$the_options['viewed_cookie'] = $viewed_cookie;
			foreach ( $categories as $category ) {
				$total     = 0;
				$temp      = array();
				$json_temp = array();
				foreach ( $cookies as $cookie ) {
					if ( $cookie['category_id'] === $category['id_lwgdpr_cookie_category'] ) {
						$total++;
						$temp[]                = $cookie;
						$cookie['description'] = str_replace( '"', '\"', $cookie['description'] );
						$json_temp[]           = $cookie;
					}
				}
				$category['data']  = $temp;
				$category['total'] = $total;
				if ( isset( $preference_cookies[ $category['lwgdpr_cookie_category_slug'] ] ) && 'yes' === $preference_cookies[ $category['lwgdpr_cookie_category_slug'] ] ) {
					$category['is_ticked'] = true;
				} else {
					$category['is_ticked'] = false;
				}
				$categories_data[]      = $category;
				$category['data']       = $json_temp;
				$categories_json_data[] = $category;
			}

			if ( true === $the_options['button_settings_is_on'] ) {
				$cookie_data                      = array();
				$cookie_data['categories']        = $categories_data;
				$cookie_data['msg']               = $about_message;
				$cookie_data['show_credits']      = $the_options['show_credits'];
				$cookie_data['credits']           = $the_options['show_credits'] ? $credit_link : '';
				$cookie_data['backdrop']          = $the_options['backdrop'];
				$cookie_data['about']             = __( 'Informazioni sui cookie', 'lwgdpr-cookie-consent' );
				$cookie_data['declaration']       = __( 'Dichiarazione sui cookie', 'lwgdpr-cookie-consent' );
				$cookie_data['always']            = __( 'Sempre attivo', 'lwgdpr-cookie-consent' );
				$cookie_data['save_button']       = __( 'Salva e accetta', 'lwgdpr-cookie-consent' );
				$cookie_data['name']              = __( 'Nome', 'lwgdpr-cookie-consent' );
				$cookie_data['domain']            = __( 'Dominio', 'lwgdpr-cookie-consent' );
				$cookie_data['purpose']           = __( 'Scopo', 'lwgdpr-cookie-consent' );
				$cookie_data['expiry']            = __( 'Scadenza', 'lwgdpr-cookie-consent' );
				$cookie_data['type']              = __( 'Tipo', 'lwgdpr-cookie-consent' );
				$cookie_data['cookies_not_found'] = __( 'Non utilizziamo cookie di questo tipo.', 'lwgdpr-cookie-consent' );
				$cookie_data['consent_notice']    = __( 'Acconsento all\'uso dei seguenti cookie:', 'lwgdpr-cookie-consent' );
				$the_options['cookie_data']       = $cookie_data;
			}
			$the_options['credits'] = $the_options['show_credits'] ? $credit_link : '';

			ob_start();
			$notify_html = $timber->render( 'templates/' . $template . '.twig', $the_options );
			ob_end_clean();
			echo $notify_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<script type="text/javascript">
				/* <![CDATA[ */
				lwgdpr_cookies_list = '<?php echo str_replace( "'", "\'", wp_json_encode( $categories_json_data ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
				lwgdpr_cookiebar_settings='<?php echo Lw_Gdpr_Cookie_Consent::lwgdpr_get_json_settings(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
				/* ]]> */
			</script>
			<?php
		}
	}

	/**
	 * Returns scanned and custom cookies.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_cookies() {
		$cookies_array = array();
		$cookie_custom = new Lw_Gdpr_Cookie_Consent_Cookie_Custom();
		$cookies_array = $cookie_custom->get_cookies();
		$cookies_array = apply_filters( 'gdprcookieconsent_cookies', $cookies_array );
		return $cookies_array;
	}

	/**
	 * Returns policy data for shortcode lw_shortcode_cookie_details.
	 *
	 * @return string|void
	 */
	public function gdprcookieconsent_shortcode_cookie_details() {
		if ( is_admin() ) {
			return;
		}
		$args                = array(
			'numberposts' => -1,
			'post_type'   => 'gdprpolicies',
		);
		$wp_legalpolicy_data = get_posts( $args );
		$content             = '';
		if ( is_array( $wp_legalpolicy_data ) && ! empty( $wp_legalpolicy_data ) ) {
			$content .= '<p>For further information on how we use cookies, please refer to the table below.</p>';
			$content .= "<div class='wp_legalpolicy' style='overflow-x:scroll;overflow:auto;'>";
			$content .= '<table style="width:100%;margin:0 auto;border-collapse:collapse;">';
			$content .= '<thead>';
			$content .= '<th>Third Party Companies</th><th>Purpose</th><th>Applicable Privacy/Cookie Policy Link</th>';
			$content .= '</thead>';
			$content .= '<tbody>';
			foreach ( $wp_legalpolicy_data as $policypost ) {
				$content .= '<tr>';
				$content .= '<td>' . $policypost->post_title . '</td>';
				$content .= '<td>' . $policypost->post_content . '</td>';
				$links    = get_post_meta( $policypost->ID, '_lwgdpr_policies_links_editor' );
				$content .= '<td>' . $links[0] . '</td>';
				$content .= '</tr>';
			}
			$content .= '</tbody></table></div>';
		}
		return $content;
	}

	/**
	 * Template redirect for header, body and footer scripts.
	 *
	 * @since 1.9.0
	 */
	public function gdprcookieconsent_template_redirect() {
		global $post;

		if ( is_admin() || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) {
			return;
		}

		$viewed_cookie = isset( $_COOKIE['lwgdpr_viewed_cookie'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['lwgdpr_viewed_cookie'] ) ) : '';
		$the_options   = Lw_GDPR_Cookie_Consent::lwgdpr_get_settings();

		$body_open_supported = function_exists( 'wp_body_open' ) && version_compare( get_bloginfo( 'version' ), '5.2', '>=' );

		if ( ( is_singular() && $post ) || is_home() ) {
			if ( ( $the_options['is_script_blocker_on'] && 'yes' === $viewed_cookie ) || ( ! $the_options['is_script_blocker_on'] ) ) {
				add_action( 'wp_head', array( $this, 'gdprcookieconsent_output_header' ) );
				if ( $body_open_supported ) {
					add_action( 'wp_body_open', array( $this, 'gdprcookieconsent_output_body' ) );
				}
				add_action( 'wp_footer', array( $this, 'gdprcookieconsent_output_footer' ) );
			}
		}
	}

	/**
	 * Output header scripts.
	 *
	 * @since 1.9.0
	 */
	public function gdprcookieconsent_output_header() {
		$the_options    = Lw_GDPR_Cookie_Consent::lwgdpr_get_settings();
		$header_scripts = $the_options['header_scripts'];
		if ( $header_scripts ) {
			echo "\r\n" . $header_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output body scripts.
	 *
	 * @since 1.9.0
	 */
	public function gdprcookieconsent_output_body() {
		$the_options  = Lw_GDPR_Cookie_Consent::lwgdpr_get_settings();
		$body_scripts = $the_options['body_scripts'];
		if ( $body_scripts ) {
			echo "\r\n" . $body_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output footer scripts.
	 *
	 * @since 1.9.0
	 */
	public function gdprcookieconsent_output_footer() {
		$the_options    = Lw_GDPR_Cookie_Consent::lwgdpr_get_settings();
		$footer_scripts = $the_options['footer_scripts'];
		if ( $footer_scripts ) {
			echo "\r\n" . $footer_scripts . "\r\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
