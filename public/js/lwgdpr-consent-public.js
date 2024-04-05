/**
 * Frontend JavaScript.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/public
 * @author     sajdoko <https://www.localweb.it/>
 */

GDPR_ACCEPT_COOKIE_NAME = (typeof GDPR_ACCEPT_COOKIE_NAME !== 'undefined' ? GDPR_ACCEPT_COOKIE_NAME : 'lwgdpr_viewed_cookie');
GDPR_CCPA_COOKIE_NAME = (typeof GDPR_CCPA_COOKIE_NAME !== 'undefined' ? GDPR_CCPA_COOKIE_NAME : 'wpl_optout_cookie');
US_PRIVACY_COOKIE_NAME = (typeof US_PRIVACY_COOKIE_NAME !== 'undefined' ? US_PRIVACY_COOKIE_NAME : 'usprivacy');
GDPR_ACCEPT_COOKIE_EXPIRE = (typeof GDPR_ACCEPT_COOKIE_EXPIRE !== 'undefined' ? GDPR_ACCEPT_COOKIE_EXPIRE : 365);
GDPR_CCPA_COOKIE_EXPIRE = (typeof GDPR_CCPA_COOKIE_EXPIRE !== 'undefined' ? GDPR_CCPA_COOKIE_EXPIRE : 365);

(function ($) {
	'use strict';
	let GDPR_Cookie = {
		set: function (name, value, days, domain) {
			let expires = "";
			if (days) {
				let date = new Date();
				date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
				expires = "; expires=" + date.toGMTString();
			}
			if (domain) {
				domain = "; domain=" + domain;
			} else {
				domain = "";
			}
			document.cookie = name + "=" + value + expires + "; path=/" + domain;
		},
		read: function (name) {
			let nameEQ = name + "=";
			let ca = document.cookie.split(';');
			let ca_length = ca.length;
			for (let i = 0; i < ca_length; i++) {
				let c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1, c.length);
				}
				if (c.indexOf(nameEQ) === 0) {
					return c.substring(nameEQ.length, c.length);
				}
			}
			return null;
		},
		exists: function (name) {
			return (this.read(name) !== null);
		},
		getallcookies: function () {
			let pairs = document.cookie.split(";");
			let cookieslist = {};
			let pairs_length = pairs.length;
			for (let i = 0; i < pairs_length; i++) {
				let pair = pairs[i].split("=");
				cookieslist[(pair[0] + '').trim()] = unescape(pair[1]);
			}
			return cookieslist;
		},
		erase: function (name, domain) {
			if (domain) {
				this.set(name, "", -10, domain);
			} else {
				this.set(name, "", -10);
			}
		},
	}

	let GDPR = {
		bar_config: {},
		show_config: {},
		allowed_categories: [],
		set: function (args) {
			if (typeof JSON.parse !== "function") {
				console.log("LWGDPRCookieConsent requires JSON.parse but your browser doesn't support it");
				return;
			}

			this.settings = JSON.parse(args.settings);
			GDPR_ACCEPT_COOKIE_EXPIRE = this.settings.cookie_expiry;
			this.bar_elm = jQuery(this.settings.notify_div_id);
			this.show_again_elm = jQuery(this.settings.show_again_div_id);

			this.details_elm = this.bar_elm.find('.lwgdpr_messagebar_detail');

			/* buttons */
			this.main_button = jQuery('#cookie_action_accept');
			this.main_link = jQuery('#cookie_action_link');
			this.donotsell_link = jQuery('#cookie_donotsell_link');
			this.reject_button = jQuery('#cookie_action_reject');
			this.settings_button = jQuery('#cookie_action_settings');
			this.save_button = jQuery('#cookie_action_save');
			this.credit_link = jQuery('#cookie_credit_link');
			this.confirm_button = jQuery('#cookie_action_confirm');
			this.cancel_button = jQuery('#cookie_action_cancel');
			this.accept_all_button = jQuery('#cookie_action_accept_all');

			if (this.settings.maxmind_integrated == '2') {
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').hide();
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').hide();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').hide();
				jQuery(GDPR.settings.notify_div_id).find('.both.lwgdpr-banner').hide();

			}

			this.configBar();
			this.toggleBar();
			this.attachEvents();
			this.configButtons();

			if (this.settings.pro_active && this.settings.maxmind_integrated == '2') {
				this.check_ccpa_eu();
			}
			if (this.settings.cookie_usage_for == 'lwgdpr' || this.settings.cookie_usage_for == 'eprivacy' || this.settings.cookie_usage_for == 'both') {
				if (this.settings.auto_scroll) {
					window.addEventListener("scroll", GDPR.acceptOnScroll, false);
				}
				let lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
				let lwgdpr_viewed_cookie = GDPR_Cookie.read('lwgdpr_viewed_cookie');
				let event = '';
				if (this.settings.cookie_usage_for == 'lwgdpr') {
					event = new CustomEvent(
						'LwGdprCookieConsentOnLoad', {
							detail: {
								'lwgdpr_user_preference': lwgdpr_user_preference,
								'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie,
							}
						}
					);
					window.dispatchEvent(event);
				} else if (this.settings.cookie_usage_for == 'eprivacy') {
					event = new CustomEvent(
						'LwGdprCookieConsentOnLoad', {
							detail: {
								'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie
							}
						}
					);
					window.dispatchEvent(event);
				}

			}
		},
		check_ccpa_eu: function () {

			let data = {
				action: 'show_cookie_consent_bar',
			};
			$.ajax({
				type: 'post',
				url: log_obj.ajax_url,
				data: data,
				dataType: 'json',
				success: function (response) {

					if (response.error) {
						// handle error here.
					} else {
						let geo_flag = true;
						let lwgdpr_flag = false;
						let ccpa_flag = false;
						let cookieData = JSON.parse(lwgdpr_cookiebar_settings);
						let cookie_for = cookieData['cookie_usage_for'];
						if ('both' == cookie_for) {
							if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && !GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
								GDPR.checkEuAndCCPAStatus(response);
							} else if (GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && !GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
								GDPR.hideHeader();
							} else if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
								GDPR.hideHeader(true);
							}
						} else if ('lwgdpr' == cookie_for) {
							GDPR.checkEuAndCCPAStatus(response);
						} else if ('ccpa' == cookie_for) {
							GDPR.checkEuAndCCPAStatus(response);
						} else if ('eprivacy' == cookie_for) {
							GDPR.checkEuAndCCPAStatus(response);
						}
					}
				},
			});
		},
		checkEuAndCCPAStatus: function (response) {
			if (response.eu_status == 'on' && response.ccpa_status == 'off') {
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').show();
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').hide();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').show();
			} else if (response.eu_status == 'off' && response.ccpa_status == 'on') {
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').show();
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').css('text-align', 'center');
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').hide();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').hide();
			}
			if (response.eu_status == 'on' && response.ccpa_status == 'on') {
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').show();
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').show();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').show();
			}
			if (response.eu_status == 'off' && response.ccpa_status == 'off') {
				GDPR.hideHeader(true);
				GDPR.displayHeader(false, false);
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').hide();
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').hide();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').hide();
				jQuery(GDPR.settings.notify_div_id).find('.both.lwgdpr-banner').hide();

			}
		},
		attachEvents: function () {
			jQuery('.lwgdpr_action_button').click(
				function (e) {
					e.preventDefault();
					let event = '';
					let lwgdpr_user_preference = '';
					let lwgdpr_user_preference_val = '';
					let lwgdpr_viewed_cookie = '';
					let lwgdpr_optout_cookie = '';
					let elm = jQuery(this);
					let button_action = elm.attr('data-lwgdpr_action');
					let open_link = elm[0].hasAttribute("href") && elm.attr("href") != '#' ? true : false;
					let new_window = false;
					if (button_action == 'accept_all') {
						GDPR.enableAllCookies();
						GDPR.accept_all_close();
						new_window = GDPR.settings.button_accept_new_win ? true : false;
						lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
						lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference);
						lwgdpr_viewed_cookie = GDPR_Cookie.read('lwgdpr_viewed_cookie');
						if (GDPR.settings.cookie_usage_for == 'lwgdpr') {
							GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
							event = new CustomEvent(
								'LwGdprCookieConsentOnAcceptAll', {
									detail: {
										'lwgdpr_user_preference': lwgdpr_user_preference,
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie,
									}
								}
							);
							window.dispatchEvent(event);

							window.dataLayer = window.dataLayer || [];
							GDPR.gtag("consent", "update", {
								ad_user_data: "granted",
								ad_personalization: "granted",
								analytics_storage: "granted",
								ad_storage: "granted",
							});
						} else if (GDPR.settings.cookie_usage_for == 'eprivacy') {
							event = new CustomEvent(
								'LwGdprCookieConsentOnAcceptAll', {
									detail: {
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie
									}
								}
							);
							window.dispatchEvent(event);
						}

						GDPR.logConsent(button_action);
					} else if (button_action == 'accept') {
						GDPR.accept_close();
						new_window = GDPR.settings.button_accept_new_win ? true : false;
						lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
						lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference);
						lwgdpr_viewed_cookie = GDPR_Cookie.read('lwgdpr_viewed_cookie');
						if (GDPR.settings.cookie_usage_for == 'lwgdpr') {
							GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);

							event = new CustomEvent(
								'LwGdprCookieConsentOnAccept', {
									detail: {
										'lwgdpr_user_preference': lwgdpr_user_preference,
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie,
									}
								}
							);
							window.dispatchEvent(event);

							window.dataLayer = window.dataLayer || [];
							GDPR.gtag("consent", "update", {
								ad_user_data: lwgdpr_user_preference.marketing === "yes" ? 'granted' : 'denied',
								ad_personalization: lwgdpr_user_preference.marketing === "yes" ? 'granted' : 'denied',
								analytics_storage: lwgdpr_user_preference.analytics === "yes" ? 'granted' : 'denied',
								ad_storage: (lwgdpr_user_preference.marketing === 'yes' || lwgdpr_user_preference.analytics === 'yes') ? 'granted' : 'denied',
							});
						} else if (GDPR.settings.cookie_usage_for == 'eprivacy') {
							event = new CustomEvent(
								'LwGdprCookieConsentOnAccept', {
									detail: {
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie
									}
								}
							);
							window.dispatchEvent(event);
						}

						GDPR.logConsent(button_action);
					} else if (button_action == 'reject') {
						GDPR.reject_close();
						new_window = GDPR.settings.button_decline_new_win ? true : false;
						lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
						lwgdpr_viewed_cookie = GDPR_Cookie.read('lwgdpr_viewed_cookie');
						if (GDPR.settings.cookie_usage_for == 'lwgdpr') {
							GDPR_Cookie.erase('lwgdpr_user_preference');
							// GDPR_Cookie.erase('lwgdpr_viewed_cookie');
							event = new CustomEvent(
								'LwGdprCookieConsentOnReject', {
									detail: {
										'lwgdpr_user_preference': lwgdpr_user_preference,
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie,
									}
								}
							);
							window.dispatchEvent(event);
							let allCookiesList = GDPR_Cookie.getallcookies();
							if (typeof allCookiesList === 'object' && allCookiesList !== null) {
								jQuery.each(
									allCookiesList,
									function (key, value) {
										if (key != GDPR_ACCEPT_COOKIE_NAME) {
											GDPR_Cookie.erase(key, "." + window.location.host);
										}
									}
								);
							}

							window.dataLayer = window.dataLayer || [];
							GDPR.gtag("consent", "update", {
								ad_user_data: "denied",
								ad_personalization: "denied",
								analytics_storage: "denied",
								ad_storage: "denied",
							});
						} else if (GDPR.settings.cookie_usage_for == 'eprivacy') {
							event = new CustomEvent(
								'LwGdprCookieConsentOnReject', {
									detail: {
										'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie
									}
								}
							);
							window.dispatchEvent(event);
						}

						GDPR.logConsent(button_action);
					} else if (button_action == 'settings') {
						GDPR.bar_elm.slideUp(GDPR.settings.animate_speed_hide);
						if (GDPR.settings.cookie_bar_as == 'popup') {
							$("#lwgdpr-popup").modal('hide');
						}
						GDPR.show_again_elm.slideUp(GDPR.settings.animate_speed_hide);
					} else if (button_action == 'close') {
						GDPR.displayHeader();
					} else if (button_action == 'show_settings') {
						GDPR.show_details();
					} else if (button_action == 'hide_settings') {
						GDPR.hide_details();
					} else if (button_action == 'donotsell') {
						if (GDPR.settings.cookie_usage_for == 'ccpa' || jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').css('display') == 'none') {
							GDPR.hideHeader(true);
						} else {
							GDPR.hideHeader();
						}
						$('#lwgdpr-ccpa-modal').modal('show');
					} else if (button_action == 'ccpa_close') {
						GDPR.displayHeader();
					} else if (button_action == 'cancel') {
						GDPR.ccpa_cancel_close();
						lwgdpr_optout_cookie = GDPR_Cookie.read('wpl_optout_cookie');

						event = new CustomEvent(
							'LwGdprCookieConsentOnCancelOptout', {
								detail: {
									'wpl_optout_cookie': lwgdpr_optout_cookie,
								}
							}
						);
						window.dispatchEvent(event);
						GDPR.logConsent(button_action);
					} else if (button_action == 'confirm') {
						GDPR.confirm_close();
						lwgdpr_optout_cookie = GDPR_Cookie.read('wpl_optout_cookie');

						event = new CustomEvent(
							'LwGdprCookieConsentOnOptout', {
								detail: {
									'wpl_optout_cookie': lwgdpr_optout_cookie,
								}
							}
						);
						window.dispatchEvent(event);
						GDPR.logConsent(button_action);
					} else if (button_action == 'close_banner') {
						GDPR.hideHeader();
						GDPR.accept_close();
						lwgdpr_viewed_cookie = GDPR_Cookie.read('lwgdpr_viewed_cookie');
						if (lwgdpr_viewed_cookie != 'yes') {
							new_window = GDPR.settings.button_accept_new_win ? true : false;
							lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
							lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference);
							if (GDPR.settings.cookie_usage_for == 'lwgdpr') {
								GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
	
								event = new CustomEvent(
									'LwGdprCookieConsentOnAccept', {
										detail: {
											'lwgdpr_user_preference': lwgdpr_user_preference,
											'lwgdpr_viewed_cookie': lwgdpr_viewed_cookie,
										}
									}
								);
								window.dispatchEvent(event);

								window.dataLayer = window.dataLayer || [];
								GDPR.gtag("consent", "update", {
									ad_user_data: lwgdpr_user_preference.marketing === "yes" ? 'granted' : 'denied',
									ad_personalization: lwgdpr_user_preference.marketing === "yes" ? 'granted' : 'denied',
									analytics_storage: lwgdpr_user_preference.analytics === "yes" ? 'granted' : 'denied',
									ad_storage: (lwgdpr_user_preference.marketing === 'yes' || lwgdpr_user_preference.analytics === 'yes') ? 'granted' : 'denied',
								});
							}
						}
					}
					if (open_link) {
						if (new_window) {
							window.open(elm.attr("href"), '_blank');
						} else {
							window.location.href = elm.attr("href");
						}
					}
				}
			);

			jQuery('.lwgdpr_messagebar_detail input').each(
				function () {
					let key = jQuery(this).val();
					let lwgdpr_user_preference_arr = {};
					let lwgdpr_user_preference_val = '';
					if (GDPR_Cookie.read('lwgdpr_user_preference')) {
						lwgdpr_user_preference_arr = (JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference')));
					}
					if (key == 'necessary' || jQuery(this).is(':checked')) {
						lwgdpr_user_preference_arr[key] = 'yes';
						GDPR.allowed_categories.push(key);
					} else {
						lwgdpr_user_preference_arr[key] = 'no';
						let length = GDPR.allowed_categories.length;
						for (let i = 0; i < length; i++) {
							if (GDPR.allowed_categories[i] == key) {
								GDPR.allowed_categories.splice(i, 1);
							}
						}
					}
					lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference_arr);
					GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
				}
			);
			jQuery(document).on(
				'click',
				'#lwgdpr-cookie-consent-show-again',
				function (e) {
					e.preventDefault();
					jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').show();
					jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').show();
					GDPR.displayHeader();
					$(this).hide();
				}
			);
			jQuery(document).on(
				'click',
				'.lwgdpr_messagebar_detail input',
				function () {
					let key = jQuery(this).val();
					let lwgdpr_user_preference_arr = {};
					let lwgdpr_user_preference_val = '';
					if (GDPR_Cookie.read('lwgdpr_user_preference')) {
						lwgdpr_user_preference_arr = (JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference')));
					}
					if (jQuery(this).is(':checked')) {
						lwgdpr_user_preference_arr[key] = 'yes';
						GDPR.allowed_categories.push(key);
					} else {
						lwgdpr_user_preference_arr[key] = 'no';
						let length = GDPR.allowed_categories.length;
						for (let i = 0; i < length; i++) {
							if (GDPR.allowed_categories[i] == key) {
								GDPR.allowed_categories.splice(i, 1);
							}
						}
					}
					lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference_arr);
					GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
				}
			);

			jQuery(document).on(
				'click',
				'#lwgdpr_messagebar_detail_body_content_tabs_overview',
				function (e) {
					e.preventDefault();
					let elm = jQuery(this);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs').find('a').removeClass('lwgdpr_messagebar_detail_body_content_tab_item_selected');
					elm.addClass('lwgdpr_messagebar_detail_body_content_tab_item_selected');
					elm.css('border-bottom-color', GDPR.settings.border_active_color);
					elm.css('background-color', GDPR.settings.background_active_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs_about').css('border-bottom-color', GDPR.settings.border_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs_about').css('background-color', GDPR.settings.background_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_about').hide();
					jQuery('#lwgdpr_messagebar_detail_body_content_overview').show();
				}
			);
			jQuery(document).on(
				'click',
				'#lwgdpr_messagebar_detail_body_content_tabs_about',
				function (e) {
					e.preventDefault();
					let elm = jQuery(this);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs').find('a').removeClass('lwgdpr_messagebar_detail_body_content_tab_item_selected');
					elm.addClass('lwgdpr_messagebar_detail_body_content_tab_item_selected');
					elm.css('border-bottom-color', GDPR.settings.border_active_color);
					elm.css('background-color', GDPR.settings.background_active_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs_overview').css('border-bottom-color', GDPR.settings.border_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_tabs_overview').css('background-color', GDPR.settings.background_color);
					jQuery('#lwgdpr_messagebar_detail_body_content_overview').hide();
					jQuery('#lwgdpr_messagebar_detail_body_content_about').show();
				}
			);
			jQuery(document).on(
				'click',
				'#lwgdpr_messagebar_detail_body_content_overview_cookie_container_types a',
				function (e) {
					e.preventDefault();
					let elm = jQuery(this);
					let prnt = elm.parent();
					prnt.find('a').removeClass('lwgdpr_messagebar_detail_body_content_overview_cookie_container_type_selected');
					prnt.find('a').css('border-right-color', GDPR.settings.border_color);
					prnt.find('a').css('background-color', GDPR.settings.background_color);
					elm.addClass('lwgdpr_messagebar_detail_body_content_overview_cookie_container_type_selected');
					elm.css('border-right-color', GDPR.settings.border_active_color);
					elm.css('background-color', GDPR.settings.background_active_color);
					let trgt = jQuery(this).attr('data-target');
					let cntr = prnt.siblings('#lwgdpr_messagebar_detail_body_content_overview_cookie_container_type_details');
					cntr.find('.lwgdpr_messagebar_detail_body_content_cookie_type_details').hide();
					cntr.find('#' + trgt + '').show();
				}
			);

		},
		configButtons: function () {

			this.settings_button.attr('style', `color: ${this.settings.button_settings_link_color} !important`);
			if (this.settings.button_settings_as_button) {
				this.settings_button.attr('style', `color: ${this.settings.button_settings_link_color} !important; background-color: ${this.settings.button_settings_button_color} !important`);
			}

			this.main_button.css('color', this.settings.button_accept_link_color);
			if (this.settings.button_accept_as_button) {
				this.main_button.css('background-color', this.settings.button_accept_button_color);
			}

			this.accept_all_button.css('color', this.settings.button_accept_link_color);
			if (this.settings.button_accept_as_button) {
				this.accept_all_button.css('background-color', this.settings.button_accept_button_color);
			}

			this.confirm_button.css('color', this.settings.button_confirm_link_color);
			if (this.settings.button_confirm_as_button) {
				this.confirm_button.css('background-color', this.settings.button_confirm_button_color);
			}

			/* [wpl_cookie_link] */
			this.main_link.css('color', this.settings.button_readmore_link_color);
			if (this.settings.button_readmore_as_button) {
				this.main_link.css('background-color', this.settings.button_readmore_button_color);
			}

			this.donotsell_link.css('color', this.settings.button_donotsell_link_color);

			this.reject_button.attr('style', `color: ${this.settings.button_decline_link_color} !important`);
			if (this.settings.button_decline_as_button) {
				this.reject_button.css('display', 'inline-block');
				this.reject_button.attr('style', `color: ${this.settings.button_decline_link_color} !important; background-color: ${this.settings.button_decline_button_color} !important`);
			}

			this.cancel_button.css('color', this.settings.button_cancel_link_color);
			if (this.settings.button_cancel_as_button) {
				this.cancel_button.css('display', 'inline-block');
				this.cancel_button.css('background-color', this.settings.button_cancel_button_color);
			}

			this.save_button.css('color', this.settings.button_accept_link_color);
			this.save_button.css('background-color', this.settings.button_accept_button_color);

			this.details_elm.find('table.lwgdpr_messagebar_detail_body_content_cookie_type_table tr').css('border-color', GDPR.settings.border_color);
			this.details_elm.find('.lwgdpr_messagebar_detail_body_content_cookie_type_intro').css('border-color', GDPR.settings.border_color);
			this.details_elm.find('a').each(
				function () {
					jQuery(this).css('border-color', GDPR.settings.border_color);
					jQuery(this).css('background-color', GDPR.settings.background_color);
				}
			)
			this.details_elm.find('a.lwgdpr_messagebar_detail_body_content_overview_cookie_container_type_selected').css('border-right-color', GDPR.settings.border_active_color);
			this.details_elm.find('a.lwgdpr_messagebar_detail_body_content_overview_cookie_container_type_selected').css('background-color', GDPR.settings.background_active_color);
			this.details_elm.find('#lwgdpr_messagebar_detail_body_content').css('border-color', GDPR.settings.border_color);
			this.details_elm.find('#lwgdpr_messagebar_detail_body_content_tabs').css('border-color', GDPR.settings.border_color);
			this.details_elm.find('#lwgdpr_messagebar_detail_body_content_tabs .lwgdpr_messagebar_detail_body_content_tab_item_selected').css('border-bottom-color', GDPR.settings.border_active_color);
			this.details_elm.find('#lwgdpr_messagebar_detail_body_content_tabs .lwgdpr_messagebar_detail_body_content_tab_item_selected').css('background-color', GDPR.settings.background_active_color);

			this.credit_link.css('color', this.settings.button_readmore_link_color);

		},
		configBar: function () {
			this.bar_config = {
				'background-color': this.settings.background,
				'color': this.settings.text,
				'font-family': this.settings.font_family
			};
			this.show_config = {
				'width': 'auto',
				'background-color': this.settings.background,
				'color': this.settings.text,
				'font-family': this.settings.font_family,
				'position': 'fixed',
				'opacity': this.settings.opacity,
				'bottom': '0',
			};
			if (this.settings.show_again_position == 'right') {
				this.show_config['right'] = this.settings.show_again_margin + '%';
			} else {
				this.show_config['left'] = this.settings.show_again_margin + '%';
			}
			this.bar_config['position'] = 'fixed';
			if (this.settings.cookie_bar_as == 'banner') {
				this.bar_config['opacity'] = this.settings.opacity;
				this.bar_elm.find('.lwgdpr_messagebar_content').css('max-width', '800px');
				if (this.settings.notify_position_vertical == 'bottom') {
					this.bar_config['bottom'] = '0';
				} else {
					this.bar_config['top'] = '0';
				}
			}
			if (this.settings.cookie_bar_as == 'widget') {
				this.bar_config['bottom'] = '20px';
				this.bar_config['max-width'] = '600px';
				this.bar_config['padding'] = '1rem';
				this.bar_config['opacity'] = this.settings.opacity;
				this.bar_elm.find('.lwgdpr_messagebar_content').css('max-width', '600px');
				if (this.settings.notify_position_horizontal == 'left') {
					this.bar_config['left'] = '20px';
				} else {
					this.bar_config['right'] = '20px';
				}
			}
			if (this.settings.cookie_bar_as == 'popup') {
				this.bar_config['position'] = 'unset';
				this.bar_config['box-shadow'] = 'unset';
				jQuery('#lwgdpr-popup .modal-content').css('opacity', this.settings.opacity);
			}
			this.bar_elm.css(this.bar_config).hide();
			this.show_again_elm.css(this.show_config).hide();
		},
		toggleBar: function () {
			if (this.settings.cookie_usage_for == 'lwgdpr') {
				if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME)) {
					this.displayHeader();
					if (this.settings.auto_hide) {
						setTimeout(
							function () {
								GDPR.accept_close();
							},
							this.settings.auto_hide_delay
						);
					}
				} else {
					this.hideHeader();
				}
			} else if (this.settings.cookie_usage_for == 'eprivacy') {
				if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME)) {
					this.displayHeader();
					if (this.settings.auto_hide) {
						setTimeout(
							function () {
								GDPR.accept_close();
							},
							this.settings.auto_hide_delay
						);
					}
				} else {
					this.hideHeader();
				}
			} else if (this.settings.cookie_usage_for == 'ccpa') {
				if (!GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
					this.displayHeader();
				} else {
					this.hideHeader();
				}
			} else if (this.settings.cookie_usage_for == 'both') {
				if (GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
					this.hideHeader();
				} else if (GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && !GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
					this.displayHeader(true, false);
				} else if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) && GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
					this.displayHeader(false, true);
					if (this.settings.auto_hide) {
						setTimeout(
							function () {
								GDPR.accept_close();
							},
							this.settings.auto_hide_delay
						);
					}
				} else {
					this.displayHeader(false, false);
					if (this.settings.auto_hide) {
						setTimeout(
							function () {
								GDPR.accept_close();
							},
							this.settings.auto_hide_delay
						);
					}
				}
				if (!GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME) || !GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {

				} else {
					this.hideHeader();
				}
			}
		},
		ccpa_cancel_close: function () {
			GDPR_Cookie.set(GDPR_CCPA_COOKIE_NAME, 'no', GDPR_CCPA_COOKIE_EXPIRE);
			if (this.settings.is_ccpa_iab_on) {
				GDPR_Cookie.set(US_PRIVACY_COOKIE_NAME, '1YNY', GDPR_CCPA_COOKIE_EXPIRE);
			}
			if (this.settings.notify_animate_hide) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide);
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('hide');
			}
			if (this.settings.accept_reload == true) {
				window.location.reload(true);
			}
			return false;
		},
		confirm_close: function () {
			GDPR_Cookie.set(GDPR_CCPA_COOKIE_NAME, 'yes', GDPR_CCPA_COOKIE_EXPIRE);
			if (this.settings.is_ccpa_iab_on) {
				GDPR_Cookie.set(US_PRIVACY_COOKIE_NAME, '1YYY', GDPR_CCPA_COOKIE_EXPIRE);
			}
			if (this.settings.notify_animate_hide) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide);
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('hide');
			}
			if (this.settings.accept_reload == true) {
				window.location.reload(true);
			}
			return false;
		},
		accept_close: function () {
			GDPR_Cookie.set(GDPR_ACCEPT_COOKIE_NAME, 'yes', GDPR_ACCEPT_COOKIE_EXPIRE);
			if (this.settings.notify_animate_hide) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide, GDPR_Blocker.runScripts);
			} else {
				this.bar_elm.hide(GDPR_Blocker.runScripts);
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('hide');
			}
			this.show_again_elm.slideDown(this.settings.animate_speed_hide);
			if (this.settings.accept_reload == true) {
				window.location.reload(true);
			}
			return false;
		},
		accept_all_close: function () {
			GDPR_Cookie.set(GDPR_ACCEPT_COOKIE_NAME, 'yes', GDPR_ACCEPT_COOKIE_EXPIRE);
			if (this.settings.notify_animate_hide) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide, GDPR_Blocker.runScripts);
			} else {
				this.bar_elm.hide(GDPR_Blocker.runScripts);
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('hide');
			}
			this.show_again_elm.slideDown(this.settings.animate_speed_hide);
			if (this.settings.accept_reload == true) {
				window.location.reload(true);
			}
			return false;
		},
		reject_close: function () {
			GDPR_Cookie.set(GDPR_ACCEPT_COOKIE_NAME, 'yes', GDPR_ACCEPT_COOKIE_EXPIRE);
			GDPR.disableAllCookies();
			if (this.settings.notify_animate_hide) {
				this.bar_elm.slideUp(this.settings.animate_speed_hide, GDPR_Blocker.runScripts);
			} else {
				this.bar_elm.hide(GDPR_Blocker.runScripts);
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('hide');
			}
			this.show_again_elm.slideDown(this.settings.animate_speed_hide);
			if (this.settings.decline_reload == true) {
				window.location.reload(true);
			}
			return false;
		},
		logConsent: function (btn_action) {
			if (this.settings.logging_on && this.settings.pro_active) {
				jQuery.ajax({
					url: log_obj.ajax_url,
					type: 'POST',
					data: {
						action: 'lwgdpr_log_consent_action',
						lwgdpr_user_action: btn_action,
						cookie_list: GDPR_Cookie.getallcookies()
					},
					success: function (response) {}
				});
			}
		},
		disableAllCookies: function () {
			let lwgdpr_user_preference_arr = {};
			let lwgdpr_user_preference_val = '';
			if (GDPR_Cookie.read('lwgdpr_user_preference')) {
				lwgdpr_user_preference_arr = (JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference')));
				jQuery.each(
					lwgdpr_user_preference_arr,
					function (key, value) {
						if (key != 'necessary') {
							lwgdpr_user_preference_arr[key] = 'no';
							$('.lwgdpr_messagebar_detail input[value="' + key + '"]').prop('checked', false);
							let length = GDPR.allowed_categories.length;
							for (let i = 0; i < length; i++) {
								if (GDPR.allowed_categories[i] == key) {
									GDPR.allowed_categories.splice(i, 1);
								}
							}
						}
					}
				);
				lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference_arr);
				GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
			}
		},
		enableAllCookies: function () {
			let lwgdpr_user_preference_arr = {};
			let lwgdpr_user_preference_val = '';
			if (GDPR_Cookie.read('lwgdpr_user_preference')) {
				lwgdpr_user_preference_arr = (JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference')));
				jQuery.each(
					lwgdpr_user_preference_arr,
					function (key, value) {
						if (key != 'necessary') {
							lwgdpr_user_preference_arr[key] = 'yes';
							$('.lwgdpr_messagebar_detail input[value="' + key + '"]').prop('checked', true);
							let length = GDPR.allowed_categories.length;
							for (let i = 0; i < length; i++) {
								if (GDPR.allowed_categories[i] == key) {
									GDPR.allowed_categories.splice(i, 1);
								}
							}
						}
					}
				);
				lwgdpr_user_preference_val = JSON.stringify(lwgdpr_user_preference_arr);
				GDPR_Cookie.set('lwgdpr_user_preference', lwgdpr_user_preference_val, GDPR_ACCEPT_COOKIE_EXPIRE);
			}
		},
		show_details: function () {
			this.details_elm.show();
			this.bar_elm.css('opacity', 1);
			this.details_elm.css('border-top-color', GDPR.settings.border_color);
			this.settings_button.attr('data-lwgdpr_action', 'hide_settings');
		},
		hide_details: function () {
			this.details_elm.hide();
			this.bar_elm.css('opacity', GDPR.settings.opacity);
			this.settings_button.attr('data-lwgdpr_action', 'show_settings');
		},
		displayHeader: function (lwgdpr_flag, ccpa_flag) {
			this.bar_elm.show();
			if (lwgdpr_flag) {
				jQuery(GDPR.settings.notify_div_id).find('p.lwgdpr').hide();
				jQuery(GDPR.settings.notify_div_id).find('.lwgdpr.group-description-buttons').hide();
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').css('text-align', 'center');
			}
			if (ccpa_flag || GDPR_Cookie.exists(GDPR_CCPA_COOKIE_NAME)) {
				jQuery(GDPR.settings.notify_div_id).find('p.ccpa').hide();
			}
			if (this.settings.cookie_bar_as == 'popup') {
				$("#lwgdpr-popup").modal('show');
			}
			if (this.settings.cookie_usage_for == 'lwgdpr' || this.settings.cookie_usage_for == 'eprivacy' || this.settings.cookie_usage_for == 'both') {
				this.show_again_elm.slideUp(this.settings.animate_speed_hide);

			}
		},
		hideHeader: function (geo_flag) {
			this.bar_elm.slideUp(this.settings.animate_speed_hide);
			if (!geo_flag) {
				if (this.settings.cookie_bar_as == 'popup') {
					$("#lwgdpr-popup").modal('hide');
				}
				if (this.settings.cookie_usage_for == 'lwgdpr' || this.settings.cookie_usage_for == 'eprivacy' || this.settings.cookie_usage_for == 'both') {
					this.show_again_elm.slideDown(this.settings.animate_speed_hide);
				}
			}
		},
		acceptOnScroll: function () {
			let scrollTop = $(window).scrollTop();
			let docHeight = $(document).height();
			let winHeight = $(window).height();
			let scrollPercent = (scrollTop) / (docHeight - winHeight);
			let scrollPercentRounded = Math.round(scrollPercent * 100);

			if (scrollPercentRounded > GDPR.settings.auto_scroll_offset && !GDPR_Cookie.exists(GDPR_ACCEPT_COOKIE_NAME)) {
				GDPR.accept_close();
				window.removeEventListener("scroll", GDPR.acceptOnScroll, false);
				if (GDPR.settings.auto_scroll_reload == true) {
					window.location.reload();
				}
			}
		},
		gtag: function () {
			dataLayer.push(arguments);
		},
		setGoogleConsent: function () {
			if (GDPR_Cookie.read('lwgdpr_user_preference') != null) {
				let lwgdpr_user_preference = JSON.parse(GDPR_Cookie.read('lwgdpr_user_preference'));
				let ad_user_data = lwgdpr_user_preference.marketing == 'yes' ? 'granted' : 'denied';
				let ad_personalization = lwgdpr_user_preference.marketing == 'yes' ? 'granted' : 'denied';
				let analytics_storage = lwgdpr_user_preference.analytics == 'yes' ? 'granted' : 'denied';
				let ad_storage = ((ad_user_data == 'granted') || (ad_personalization == 'granted') || (analytics_storage == 'granted')) ? 'granted' : 'denied';

				window.dataLayer = window.dataLayer || [];
				GDPR.gtag('consent', 'default', {
					'ad_user_data': ad_user_data,
					'ad_personalization': ad_personalization,
					'analytics_storage': analytics_storage,
					'ad_storage': ad_storage,
					'wait_for_update': 500,
				});

			} else {
				console.log('lwgdpr_user_preference not set');
			}
		},
	}

	let GDPR_Blocker = {
		blockingStatus: true,
		scriptsLoaded: false,
		set: function (args) {
			if (typeof JSON.parse !== "function") {
				console.log("LWGDPRCookieConsent requires JSON.parse but your browser doesn't support it");
				return;
			}
			this.cookies = JSON.parse(args.cookies);
		},
		removeCookieByCategory: function () {
			if (GDPR_Blocker.blockingStatus == true) {
				for (let key in GDPR_Blocker.cookies) {
					let cookie = GDPR_Blocker.cookies[key];
					let current_category = cookie['lwgdpr_cookie_category_slug'];
					if (GDPR.allowed_categories.indexOf(current_category) === -1) {
						let cookies = cookie['data'];
						if (cookies && cookies.length != 0) {
							for (let c_key in cookies) {
								let c_cookie = cookies[c_key];
								GDPR_Cookie.erase(c_cookie['name']);
							}
						}
					}
				}
			}
		},
		runScripts: function () {
			let srcReplaceableElms = ['iframe', 'IFRAME', 'EMBED', 'embed', 'OBJECT', 'object', 'IMG', 'img'];
			let genericFuncs = {
				renderByElement: function (callback) {
					scriptFuncs.renderScripts();
					htmlElmFuncs.renderSrcElement();
					callback();
					GDPR_Blocker.scriptsLoaded = true;
				},
				reviewConsent: function () {
					jQuery(document).on(
						'click',
						'.wpl_manage_current_consent',
						function () {
							GDPR.displayHeader();
						}
					);
				}
			};
			let scriptFuncs = {
				scriptsDone: function () {
					let DOMContentLoadedEvent = document.createEvent('Event')
					DOMContentLoadedEvent.initEvent('DOMContentLoaded', true, true)
					window.document.dispatchEvent(DOMContentLoadedEvent)
				},
				seq: function (arr, callback, index) {
					if (typeof index === 'undefined') {
						index = 0
					}

					arr[index](function () {
						index++
						if (index === arr.length) {
							callback()
						} else {
							scriptFuncs.seq(arr, callback, index)
						}
					})
				},

				insertScript: function ($script, callback) {
					let allowedAttributes = [
						'data-wpl-class',
						'data-wpl-label',
						'data-wpl-placeholder',
						'data-wpl-script-type',
						'data-wpl-src'
					];
					let scriptType = $script.getAttribute('data-wpl-script-type');
					let elementPosition = $script.getAttribute('data-wpl-element-position');
					let isBlock = $script.getAttribute('data-wpl-block');
					let s = document.createElement('script');
					s.type = 'text/plain';
					if ($script.async) {
						s.async = $script.async;
					}
					if ($script.defer) {
						s.defer = $script.defer;
					}
					if ($script.src) {
						s.onload = callback
						s.onerror = callback
						s.src = $script.src
					} else {
						s.textContent = $script.innerText
					}
					let attrs = jQuery($script).prop("attributes");
					let length = attrs.length;
					for (let ii = 0; ii < length; ++ii) {
						if (attrs[ii].nodeName !== 'id') {
							if (allowedAttributes.indexOf(attrs[ii].nodeName) !== -1) {
								s.setAttribute(attrs[ii].nodeName, attrs[ii].value);
							}
						}
					}
					if (GDPR_Blocker.blockingStatus === true) {
						if ((GDPR_Cookie.read(GDPR_ACCEPT_COOKIE_NAME) == 'yes' && GDPR.allowed_categories.indexOf(scriptType) !== -1) || (GDPR_Cookie.read(GDPR_ACCEPT_COOKIE_NAME) == null && isBlock === 'false')) {
							s.setAttribute('data-wpl-consent', 'accepted');
							s.type = 'text/javascript';
						}
					} else {
						s.type = 'text/javascript';
					}
					if ($script.type != s.type) {
						if (elementPosition === 'head') {
							document.head.appendChild(s);
							if (!$script.src) {
								callback()
							}
							$script.parentNode.removeChild($script);
						} else {
							document.body.appendChild(s);
							if (!$script.src) {
								callback()
							}
							$script.parentNode.removeChild($script);
						}
					}
				},
				renderScripts: function () {
					let $scripts = document.querySelectorAll('script[data-wpl-class="wpl-blocker-script"]');
					if ($scripts.length > 0) {
						let runList = []
						let typeAttr
						Array.prototype.forEach.call(
							$scripts,
							function ($script) {
								typeAttr = $script.getAttribute('type')
								let elmType = $script.tagName;
								runList.push(
									function (callback) {
										scriptFuncs.insertScript($script, callback)
									}
								)
							}
						)
						scriptFuncs.seq(runList, scriptFuncs.scriptsDone);
					}
				}
			};
			let htmlElmFuncs = {
				renderSrcElement: function () {
					let blockingElms = document.querySelectorAll('[data-wpl-class="wpl-blocker-script"]');
					let length = blockingElms.length;
					for (let i = 0; i < length; i++) {
						let currentElm = blockingElms[i];
						let elmType = currentElm.tagName;
						if (srcReplaceableElms.indexOf(elmType) !== -1) {
							let elmCategory = currentElm.getAttribute('data-wpl-script-type');
							let isBlock = currentElm.getAttribute('data-wpl-block');
							if (GDPR_Blocker.blockingStatus === true) {
								if ((GDPR_Cookie.read(GDPR_ACCEPT_COOKIE_NAME) == 'yes' && GDPR.allowed_categories.indexOf(elmCategory) !== -1) || (GDPR_Cookie.read(GDPR_ACCEPT_COOKIE_NAME) != null && isBlock === 'false')) {
									this.replaceSrc(currentElm);
								} else {
									this.addPlaceholder(currentElm);
								}
							} else {
								this.replaceSrc(currentElm);
							}
						}
					}
				},
				addPlaceholder: function (htmlElm) {
					if (jQuery(htmlElm).prev('.wpl-iframe-placeholder').length === 0) {

						let htmlElemType = htmlElm.getAttribute('data-wpl-placeholder');
						let htmlElemWidth = htmlElm.getAttribute('width');
						let htmlElemHeight = htmlElm.getAttribute('height');
						if (htmlElemWidth == null) {
							htmlElemWidth = htmlElm.offsetWidth;
						}
						if (htmlElemHeight == null) {
							htmlElemHeight = htmlElm.offsetHeight;
						}
						let pixelPattern = /px/;
						htmlElemWidth = ((pixelPattern.test(htmlElemWidth)) ? htmlElemWidth : htmlElemWidth + 'px');
						htmlElemHeight = ((pixelPattern.test(htmlElemHeight)) ? htmlElemHeight : htmlElemHeight + 'px');
						let addPlaceholder = '<div style="width:' + htmlElemWidth + '; height:' + htmlElemHeight + ';" class="wpl-iframe-placeholder"><div class="wpl-inner-text">' + htmlElemType + '</div></div>';
						if (htmlElm.tagName !== 'IMG') {
							jQuery(addPlaceholder).insertBefore(htmlElm);
						}
						htmlElm.removeAttribute('src');
						htmlElm.style.display = 'none';
					}
				},
				replaceSrc: function (htmlElm) {
					if (!htmlElm.hasAttribute('src')) {
						let htmlElemSrc = htmlElm.getAttribute('data-wpl-src');
						htmlElm.setAttribute('src', htmlElemSrc);
						if (jQuery(htmlElm).prev('.wpl-iframe-placeholder').length > 0) {
							jQuery(htmlElm).prev('.wpl-iframe-placeholder').remove();
						}
						htmlElm.style.display = 'block';
					}
				}
			};
			genericFuncs.reviewConsent();
			genericFuncs.renderByElement(GDPR_Blocker.removeCookieByCategory);
		}
	}

	$(document).ready(
		function () {
			if (typeof lwgdpr_cookiebar_settings != 'undefined') {
				GDPR.set({
					settings: lwgdpr_cookiebar_settings
				});
			}

			if (typeof lwgdpr_cookies_list != 'undefined') {
				GDPR_Blocker.set({
					cookies: lwgdpr_cookies_list
				});
				GDPR_Blocker.runScripts();
			}

			GDPR.setGoogleConsent();

		},

		function () {
			$(".lwgdpr-column").click(
				function () {
					$(".lwgdpr-column", this);
					if (!$(this).children(".lwgdpr-columns").hasClass("active-group")) {
						$(".lwgdpr-columns").removeClass("active-group")
						$(this).children(".lwgdpr-columns").addClass("active-group")
					}
					if ($(this).siblings(".description-container").hasClass("hide")) {
						$(".description-container").addClass("hide")
						$(this).siblings(".description-container").removeClass("hide")
					}
				}
			);
		}
	);

	$(window).on("load", function () {
		$('a[href^="mailto"]').click(function () {
			let gaCategory = this.getAttribute("data-vars-ga-category") || "email";
			let gaAction = this.getAttribute("data-vars-ga-action") || "send";
			let gaLabel = this.getAttribute("data-vars-ga-label") || this.href;
			GDPR.gtag("event", gaAction, {"event_category": gaCategory,"event_label": gaLabel});
		});

		$('a[href^="tel"]').click(function () {
			let gaCategory = this.getAttribute("data-vars-ga-category") || "telephone";
			let gaAction = this.getAttribute("data-vars-ga-action") || "call";
			let gaLabel = this.getAttribute("data-vars-ga-label") || this.href;
			GDPR.gtag("event", gaAction, {"event_category": gaCategory,"event_label": gaLabel});
		});

		$(".wpcf7").on('wpcf7mailsent', function (event) {
			let gaCategory = "form";
			let gaAction = "submit";
			let gaLabel = event.currentTarget.baseURI;
			GDPR.gtag("event", gaAction, {"event_category": gaCategory,"event_label": gaLabel});
		});
	});

})(jQuery);