<?php
/**
 * The custom cookie functionality of the plugin.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 */

if (!defined('ABSPATH')) {
  exit;
}
require plugin_dir_path(__FILE__) . 'classes/class-lwgdpr-cookie-consent-cookie-custom-ajax.php';
/**
 * The admin-specific functionality for custom cookies.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin/modules
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_Cookie_Custom {

  /**
   * Categories table.
   *
   * @since 1.0
   * @access public
   * @var string $category_table Scan categories table.
   */
  public $category_table = 'lwgdpr_cookie_scan_categories';
  /**
   * Post cookies table.
   *
   * @since 1.0
   * @access public
   * @var string $post_cookies_table Post cookies table.
   */
  public $post_cookies_table = 'lwgdpr_cookie_post_cookies';
  /**
   * Not to keep records flag.
   *
   * @since 1.0
   * @access public
   * @var bool $not_keep_records Not to keep records flag.
   */
  public $not_keep_records = true;

  /**
   * Lw_Gdpr_Cookie_Consent_Cookie_Custom constructor.
   */
  public function __construct() {
    // Creating necessary tables for cookie custom.
    register_activation_hook(LW_GDPR_COOKIE_CONSENT_PLUGIN_FILENAME, array($this, 'lwgdpr_activator'));
    $this->status_labels = array(
      0 => '',
      1 => __('Incomplete', 'lwgdpr-cookie-consent'),
      2 => __('Completed', 'lwgdpr-cookie-consent'),
      3 => __('Stopped', 'lwgdpr-cookie-consent'),
    );
    if (Lw_Gdpr_Cookie_Consent::is_request('admin')) {
      add_filter('lwgdpr_module_settings_tabhead', array(__CLASS__, 'settings_tabhead'));
      add_action('lwgdpr_module_settings_form', array($this, 'settings_form'));
      add_action('lwgdpr_module_settings_general', array($this, 'settings_general'), 5);
    }
  }

  /**
   * Settings for Cookies About message under General Tab.
   *
   * @since 1.0
   */
  public function settings_general() {
    $the_options = Lw_Gdpr_Cookie_Consent::lwgdpr_get_settings();
    ?>
			<tr valign="top" lwgdpr_frm_tgl-id="lwgdpr_usage_option" lwgdpr_frm_tgl-val="lwgdpr">
				<th scope="row"><label for="about_message_field"><?php esc_attr_e('About Cookies Message', 'lwgdpr-cookie-consent');?></label></th>
				<td>
					<?php
echo '<textarea name="about_message_field" class="vvv_textbox">';
    echo esc_html(apply_filters('format_to_edit', stripslashes($the_options['about_message']))) . '</textarea>';
    ?>
				</td>
			</tr>
		<?php
}
  /**
   * Tab head for settings page.
   *
   * @since 1.0
   * @param array $arr Settings array.
   *
   * @return mixed
   */
  public static function settings_tabhead($arr) {
    $arr['lwgdpr-cookie-consent-cookie-list'] = __('Cookie List', 'lwgdpr-cookie-consent');
    return $arr;
  }

  /**
   * Return categories.
   *
   * @since 1.0
   * @return array|mixed|object
   */
  public function lwgdpr_get_categories() {
    include plugin_dir_path(__FILE__) . '/classes/class-lwgdpr-cookie-consent-cookie-serve-api.php';
    $cookie_serve_api = new Lw_Gdpr_Cookie_Consent_Cookie_Serve_Api();
    $categories = $cookie_serve_api->get_categories();
    return $categories;
  }

  /**
   * Returns category array depending on $mode.
   *
   * @since 1.0
   * @param bool $mode Used to return required data.
   *
   * @return array|null|object
   */
  public function lwgdpr_get_categories_arr($mode = false) {
    global $wpdb;
    $out = array();
    $data_arr = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'lwgdpr_cookie_scan_categories ORDER BY id_lwgdpr_cookie_category ASC', ARRAY_A); // db call ok; no-cache ok.
    if ($data_arr) {
      if ($mode) {
        foreach ($data_arr as $key => $value) {
          $tmp = $data_arr[$key];
          if ('necessary' === $value['lwgdpr_cookie_category_slug']) {
            $data_arr[$key] = $data_arr[0];
            $data_arr[0] = $tmp;
          }
        }
        $out = $data_arr;
      } else {
        foreach ($data_arr as $arr) {
          $out[$arr['id_lwgdpr_cookie_category']] = $arr['lwgdpr_cookie_category_name'];
        }
      }
    }
    return $out;
  }

  /**
   * Returns category array depending on $mode.
   *
   * @since 1.0
   * @param bool $mode Used to return required data.
   *
   * @return array|null|object
   */
  public static function get_categories($mode = false) {
    if ($mode) {
      $categories = (new self())->lwgdpr_get_categories_arr($mode);
    } else {
      $categories = (new self())->lwgdpr_get_categories_arr();
    }
    return $categories;
  }

  /**
   * Returns cookie types.
   *
   * @since 1.0
   * @return array
   */
  public static function get_types() {
    $types = array(
      'HTTP' => __('HTTP Cookie', 'lwgdpr-cookie-consent'),
      'HTML' => __('HTML Local Storage', 'lwgdpr-cookie-consent'),
      'Flash Local' => __('Flash Local Shared Object', 'lwgdpr-cookie-consent'),
      'Pixel' => __('Pixel Tracker', 'lwgdpr-cookie-consent'),
      'IndexedDB' => __('IndexedDB', 'lwgdpr-cookie-consent'),
    );
    return $types;
  }

  /**
   * Returns specific cookie type.
   *
   * @since 1.0
   * @param string $type Cookie type.
   *
   * @return mixed|void
   */
  public static function get_cookie_type($type) {
    $types = self::get_types();
    if (isset($types[$type])) {
      return $types[$type];
    } else {
      return;
    }
  }

  /**
   * Displays select box options in admin form for cookie categories and types.
   *
   * @since 1.0
   * @param array  $options Options.
   * @param string $selected Selection.
   */
  public static function print_combobox_options($options, $selected) {
    foreach ($options as $key => $value) {
      echo '<option value="' . esc_html($key) . '"';
      if (strval($key) === $selected) {
        echo ' selected="selected"';
      }
      echo '>' . esc_html($value) . '</option>';
    }
  }

  /**
   * Admin settings form for cookie list tab.
   *
   * @since 1.0
   */
  public function settings_form() {
    $post_cookie_list = $this->get_post_cookie_list();
    wp_enqueue_script('gdprcookieconsent_cookie_custom', plugin_dir_url(__FILE__) . 'assets/js/cookie-custom' . LW_GDPR_CC_SUFFIX . '.js', array(), LW_GDPR_COOKIE_CONSENT_VERSION, true);
    $params = array(
      'nonces' => array(
        'lwgdpr_cookie_custom' => wp_create_nonce('lwgdpr_cookie_custom'),
      ),
      'ajax_url' => admin_url('admin-ajax.php'),
      'loading_gif' => plugin_dir_url(__FILE__) . 'assets/images/loading.gif',
      'post_cookie_list' => $post_cookie_list,
    );
    wp_localize_script('gdprcookieconsent_cookie_custom', 'gdprcookieconsent_cookie_custom', $params);

    $view_file = 'cookies.php';
    $error_message = '';

    $view_file = plugin_dir_path(__FILE__) . 'views/' . $view_file;

    Lw_Gdpr_Cookie_Consent::lwgdpr_envelope_settings_tabcontent('lwgdpr-cookie-consent-tab-content', 'lwgdpr-cookie-consent-cookie-list', $view_file, '', $params, 1, $error_message);
  }

  /**
   * Run during the plugin's activation to install required tables in database.
   *
   * @since 1.0
   */
  public function lwgdpr_activator() {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    if (is_multisite()) {
      // Get all blogs in the network and activate plugin on each one.
      $blog_ids = $wpdb->get_col('SELECT blog_id FROM ' . $wpdb->blogs); // db call ok; no-cache ok.
      foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        $this->lwgdpr_install_tables();
        restore_current_blog();
      }
    } else {
      $this->lwgdpr_install_tables();
      $this->lwgdpr_wpfc_compability();
    }
  }

  /**
   * Installs necessary tables.
   *
   * @since 1.0
   */
  public function lwgdpr_install_tables() {
    global $wpdb;

    $wild = '%';
    // Creating post cookies table.
    $table_name = $wpdb->prefix . $this->post_cookies_table;
    $find = $table_name;
    $like = $wild . $wpdb->esc_like($find) . $wild;
    $result = $wpdb->get_results($wpdb->prepare('SHOW TABLES LIKE %s', array($like)), ARRAY_N); // db call ok; no-cache ok.
    if (!$result) {
      $create_table_sql = "CREATE TABLE `$table_name`(
			    `id_lwgdpr_cookie_post_cookies` INT NOT NULL AUTO_INCREMENT,
			    `name` VARCHAR(255) NOT NULL,
			    `domain` VARCHAR(255) NOT NULL,
			    `duration` VARCHAR(255) NOT NULL,
			    `type` VARCHAR(255) NOT NULL,
			    `category` VARCHAR(255) NOT NULL,
			    `category_id` INT NOT NULL,
			    `description` TEXT NULL DEFAULT '',
			    PRIMARY KEY(`id_lwgdpr_cookie_post_cookies`)
			);";
      dbDelta($create_table_sql);
    }
    // Creating categories table.
    $table_name = $wpdb->prefix . $this->category_table;
    $find = $table_name;
    $like = $wild . $wpdb->esc_like($find) . $wild;
    $result = $wpdb->get_results($wpdb->prepare('SHOW TABLES LIKE %s', array($like)), ARRAY_N); // db call ok; no-cache ok.
    if (!$result) {
      $create_table_sql = "CREATE TABLE `$table_name`(
				 `id_lwgdpr_cookie_category` INT NOT NULL AUTO_INCREMENT,
				 `lwgdpr_cookie_category_name` VARCHAR(191) NOT NULL,
				 `lwgdpr_cookie_category_slug` VARCHAR(191) NOT NULL,
				 `lwgdpr_cookie_category_description` TEXT  NULL,
				 PRIMARY KEY(`id_lwgdpr_cookie_category`),
				 UNIQUE `cookie` (`lwgdpr_cookie_category_name`)
			 );";
      dbDelta($create_table_sql);
    }
    $this->lwgdpr_insert_default_cookies();
    $this->lwgdpr_update_category_table();
  }

  
  /**
   * Wp Fastest Cache Compability.
   *
   * @since 1.0
   */
  public function lwgdpr_wpfc_compability() {

    if($wp_fastest_cache_exclude = get_option("WpFastestCache", false)) {
      $new_rule = new stdClass;
      $new_rule->prefix = "contain";
      $new_rule->content = "lwgdpr_";
      $new_rule->type = "cookie";

      if($wp_fastest_cache_exclude = get_option("WpFastestCacheExclude", false)) {

        $rules_std = json_decode($wp_fastest_cache_exclude);

        if(!is_array($rules_std)){
          $rules_std = array();
        }

        if(!in_array($new_rule, $rules_std)){
          array_push($rules_std, $new_rule);
          update_option("WpFastestCacheExclude", json_encode($rules_std));
        }
      } else {

        $rules_std = array();

        array_push($rules_std, $new_rule);
        add_option("WpFastestCacheExclude", json_encode($rules_std), null, "yes");

      }

    }

  }

  /**
   * Insert default cookies.
   *
   * @since 1.0
   */
  private function lwgdpr_insert_default_cookies() {
    global $wpdb;
		$post_cookies_table = $wpdb->prefix . $this->post_cookies_table;

		$cur_site_url = str_replace(array('http://', 'https://'), '', esc_url( home_url( ) ));

    $def_cookies = array(
      array(
        'name' => __('lwgdpr_user_preference', 'lwgdpr-cookie-consent'),
        'domain' => __($cur_site_url, 'lwgdpr-cookie-consent'),
        'duration' => __('365', 'lwgdpr-cookie-consent'),
        'type' => __('HTTP', 'lwgdpr-cookie-consent'),
        'category' => __('Necessario', 'lwgdpr-cookie-consent'),
        'category_id' => __('1', 'lwgdpr-cookie-consent'),
        'description' => __('Preferenze di consenso sui Cookie.', 'lwgdpr-cookie-consent'),
      ),
      array(
        'name' => __('lwgdpr_viewed_cookie', 'lwgdpr-cookie-consent'),
        'domain' => __($cur_site_url, 'lwgdpr-cookie-consent'),
        'duration' => __('365', 'lwgdpr-cookie-consent'),
        'type' => __('HTTP', 'lwgdpr-cookie-consent'),
        'category' => __('Necessario', 'lwgdpr-cookie-consent'),
        'category_id' => __('1', 'lwgdpr-cookie-consent'),
        'description' => __('Preferenze di consenso sui Cookie.', 'lwgdpr-cookie-consent'),
      ),
		);

		foreach ($def_cookies as $def_cookie) {
			$wpdb->query($wpdb->prepare('INSERT IGNORE INTO `' . $post_cookies_table . '` (`name`,`domain`,`duration`,`type`,`category`,`category_id`,`description`) VALUES (%s,%s,%s,%s,%s,%d,%s)', array($def_cookie['name'], $def_cookie['domain'], $def_cookie['duration'], $def_cookie['type'], $def_cookie['category'], $def_cookie['category_id'], $def_cookie['description']))); // db call ok; no-cache ok.
		}
  }

  /**
   * Updates category table.
   *
   * @since 1.0
   */
  private function lwgdpr_update_category_table() {
    global $wpdb;
    $cat_table = $wpdb->prefix . $this->category_table;
    $categories = $this->lwgdpr_get_categories();
    $cat_arr = array();
    if (!empty($categories)) {
      foreach ($categories as $category) {
        $cat_description = isset($category['description']) ? addslashes($category['description']) : '';
        $cat_category = isset($category['name']) ? $category['name'] : '';
        $cat_slug = isset($category['slug']) ? $category['slug'] : '';
        $wpdb->query($wpdb->prepare('INSERT IGNORE INTO `' . $wpdb->prefix . 'lwgdpr_cookie_scan_categories` (`lwgdpr_cookie_category_name`,`lwgdpr_cookie_category_slug`,`lwgdpr_cookie_category_description`) VALUES (%s,%s,%s)', array($cat_category, $cat_slug, $cat_description))); // db call ok; no-cache ok.
      }
    }
  }

  /**
   * Returns manually created cookie list from db.
   *
   * @since 1.0
   * @param int $offset Offset.
   * @param int $limit Limit.
   *
   * @return array
   */
  public function get_post_cookie_list($offset = 0, $limit = 100) {
    global $wpdb;
    $out = array(
      'total' => 0,
      'data' => array(),
    );

    $count_arr = $wpdb->get_row('SELECT COUNT(id_lwgdpr_cookie_post_cookies) AS ttnum FROM ' . $wpdb->prefix . 'lwgdpr_cookie_post_cookies', ARRAY_A); // db call ok; no-cache ok.
    if ($count_arr) {
      $out['total'] = $count_arr['ttnum'];
    }

    $data_arr = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'lwgdpr_cookie_post_cookies ORDER BY id_lwgdpr_cookie_post_cookies DESC LIMIT %d, %d', array($offset, $limit)), ARRAY_A); // db call ok; no-cache ok.
    if ($data_arr) {
      $out['data'] = $data_arr;
    }
    return $out;
  }

  /**
   * Returns scanned and custom cookies removing duplicate entries.
   *
   * @since 1.0
   * @return array
   */
  public function get_cookies() {
    $cookies_array = array();
    $post_cookies = $this->get_post_cookie_list();
    $post_cookies = array_reverse($post_cookies['data']);
    foreach ($post_cookies as $key => $post_arr) {
      $num_days = $post_arr['duration'];
      if (is_numeric($num_days)) {
        if ('1' === $num_days) {
          $num_days .= ' day';
        } elseif ($num_days < 365) {
          if ($num_days >= 30) {
            $num_days = round($num_days / 30);
            if ($num_days > 1) {
              $num_days .= ' months';
            } else {
              $num_days .= ' month';
            }
          } else {
            $num_days .= ' days';
          }
        } elseif ($num_days >= 365) {
          $num_days = round($num_days / 365);
          if ($num_days > 1) {
            $num_days .= ' years';
          } else {
            $num_days .= ' year';
          }
        }
        $post_arr['duration'] = $num_days;
        $post_cookies[$key] = $post_arr;
      }
    }
    $cookies_array = array_merge($post_cookies);
    $temp_arr = array_unique(array_column($cookies_array, 'name'));
    $cookies_array = array_intersect_key($cookies_array, $temp_arr);
    return $cookies_array;
  }

}
new Lw_Gdpr_Cookie_Consent_Cookie_Custom();
