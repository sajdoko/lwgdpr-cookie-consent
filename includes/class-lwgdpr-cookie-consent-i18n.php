<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.localweb.it/
 * @since      1.0
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/includes
 * @author     sajdoko <https://www.localweb.it/>
 */
class Lw_Gdpr_Cookie_Consent_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain() {
		__( 'HTTP Cookie', 'lwgdpr-cookie-consent' );
		__( 'HTML Local Storage', 'lwgdpr-cookie-consent' );
		__( 'Flash Local Shared Object', 'lwgdpr-cookie-consent' );
		__( 'Pixel Tracker', 'lwgdpr-cookie-consent' );
		__( 'IndexedDB', 'lwgdpr-cookie-consent' );
		__( 'Read More', 'lwgdpr-cookie-consent' );
		__( 'Decline', 'lwgdpr-cookie-consent' );
		__( 'Accept', 'lwgdpr-cookie-consent' );
		__( 'Confirm', 'lwgdpr-cookie-consent' );
		__( 'Cancel', 'lwgdpr-cookie-consent' );
		__( 'Necessary', 'lwgdpr-cookie-consent' );
		__( 'Marketing', 'lwgdpr-cookie-consent' );
		__( 'Analytics', 'lwgdpr-cookie-consent' );
		__( 'Preferences', 'lwgdpr-cookie-consent' );
		__( 'Unclassified', 'lwgdpr-cookie-consent' );
		__( 'Cookie Settings', 'lwgdpr-cookie-consent' );
		__( 'Necessary cookies help make a website usable by enabling basic functions like page navigation and access to secure areas of the website. The website cannot function properly without these cookies.', 'lwgdpr-cookie-consent' );
		__( 'Marketing cookies are used to track visitors across websites. The intention is to display ads that are relevant and engaging for the individual user and thereby more valuable for publishers and third party advertisers.', 'lwgdpr-cookie-consent' );
		__( 'Analytics cookies help website owners to understand how visitors interact with websites by collecting and reporting information anonymously.', 'lwgdpr-cookie-consent' );
		__( 'Preference cookies enable a website to remember information that changes the way the website behaves or looks, like your preferred language or the region that you are in.', 'lwgdpr-cookie-consent' );
		__( 'Unclassified cookies are cookies that we are in the process of classifying, together with the providers of individual cookies.', 'lwgdpr-cookie-consent' );
		__( 'I cookie sono piccoli file di testo che possono essere utilizzati dai siti web per rendere più efficiente l\'esperienza per l\'utente.
		La legge afferma che possiamo memorizzare i cookie sul suo dispositivo se sono strettamente necessari per il funzionamento di questo sito. Per tutti gli altri tipi di cookie abbiamo bisogno del suo permesso.
		Questo sito utilizza diversi tipi di cookie. Alcuni cookie sono collocate da servizi di terzi che compaiono sulle nostre pagine.
		In qualsiasi momento è possibile modificare o revocare il proprio consenso dalla Dichiarazione dei cookie sul nostro sito Web.
		Scopra di più su chi siamo, come può contattarci e come trattiamo i dati personali nella nostra Informativa sulla privacy.
		Specifica l’ID del tuo consenso e la data di quando ci hai contattati per quanto riguarda il tuo consenso.', 'lwgdpr-cookie-consent' );
		__( 'This website uses cookies', 'lwgdpr-cookie-consent' );
		__( 'This website uses cookies to improve your experience. We\'ll assume you\'re ok with this, but you can opt-out if you wish.', 'lwgdpr-cookie-consent' );
		__( 'In case of sale of your personal information, you may opt out by using the link', 'lwgdpr-cookie-consent' );
		__( 'Do Not Sell My Personal Information', 'lwgdpr-cookie-consent' );
		__( 'Do you really wish to opt-out?', 'lwgdpr-cookie-consent' );
		load_plugin_textdomain(
			'lwgdpr-cookie-consent',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}



}
