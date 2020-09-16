<?php
/**
 * The cookie api functionality of the plugin.
 *
 * @link       https://www.localweb.it//
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Lw_Gdpr_Cookie_Consent_Cookie_Serve_Api' ) ) {
	/**
	 * The admin-specific functionality for cookies api.
	 *
	 * @package    Lw_Gdpr_Cookie_Consent
	 * @subpackage Lw_Gdpr_Cookie_Consent/admin/modules
	 * @author     sajdoko <https://www.localweb.it/>
	 */
	class Lw_Gdpr_Cookie_Consent_Cookie_Serve_Api {

		/**
		 * Lw_Gdpr_Cookie_Consent_Cookie_Serve_Api constructor.
		 */
		public function __construct() {
		}

		/**
		 * Fetch categories.
		 *
		 * @since 1.0
		 * @return array|mixed|object
		 */
		public function get_categories() {
			return json_decode(
				'[{
				"term_id": 1,
				"name": "Necessario",
				"slug": "necessary",
				"term_group": 0,
				"term_taxonomy_id": 1,
				"taxonomy": "wplcookies-category",
				"description": "I cookie necessari aiutano a rendere fruibile un sito web abilitando le funzioni di base come la navigazione della pagina e lo accesso alle aree protette del sito. Il sito web non può funzionare correttamente senza questi cookie.",
				"parent": 0,
				"count": 100,
				"filter": "raw"
			}, {
				"term_id": 2,
				"name": "Preferenze",
				"slug": "preferences",
				"term_group": 0,
				"term_taxonomy_id": 2,
				"taxonomy": "wplcookies-category",
				"description": "I cookie di preferenza consentono a un sito web di ricordare le informazioni che cambiano il modo in cui il sito web si comporta o appare, come la tua lingua preferita o la regione in cui ti trovi.",
				"parent": 0,
				"count": 45,
				"filter": "raw"
			}, {
				"term_id": 3,
				"name": "Analitici",
				"slug": "analytics",
				"term_group": 0,
				"term_taxonomy_id": 4,
				"taxonomy": "wplcookies-category",
				"description": "I cookie analitici aiutano i proprietari di siti web a capire come i visitatori interagiscono con i siti raccogliendo e riportando informazioni in modo anonimo. ",
				"parent": 0,
				"count": 214,
				"filter": "raw"
			}, {
				"term_id": 4,
				"name": "Marketing",
				"slug": "marketing",
				"term_group": 0,
				"term_taxonomy_id": 4,
				"taxonomy": "wplcookies-category",
				"description": "I cookie di marketing vengono utilizzati per monitorare i visitatori sui siti Web. La intenzione è quella di visualizzare annunci pertinenti e coinvolgenti per il singolo utente e quindi più preziosi per editori e inserzionisti di terze parti.",
				"parent": 0,
				"count": 53,
				"filter": "raw"
			}]', true );
		}

		/**
		 * Check curl availability.
		 *
		 * @since 1.0
		 * @return bool
		 */
		public static function curl_enabled() {
			return function_exists( 'curl_version' );
		}
	}
}
