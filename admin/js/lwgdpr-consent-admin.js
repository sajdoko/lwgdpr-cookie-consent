/**
 * Admin JavaScript.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin
 * @author     sajdoko <https://www.localweb.it/>
 */

(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(
		function() {
			$( '.lwgdpr-color-field' ).wpColorPicker();

			var lwgdpr_nav_tab = $( '.lwgdpr-cookie-consent-tab-head .nav-tab' );
			if (lwgdpr_nav_tab.length > 0) {
				lwgdpr_nav_tab.click(
					function(){
						var lwgdpr_tab_hash = $( this ).attr( 'href' );
						lwgdpr_nav_tab.removeClass( 'nav-tab-active' );
						$( this ).addClass( 'nav-tab-active' );
						lwgdpr_tab_hash    = lwgdpr_tab_hash.charAt( 0 ) == '#' ? lwgdpr_tab_hash.substring( 1 ) : lwgdpr_tab_hash;
						var lwgdpr_tab_elm = $( 'div[data-id="' + lwgdpr_tab_hash + '"]' );
						$( '.lwgdpr-cookie-consent-tab-content' ).hide();
						if (lwgdpr_tab_elm.length > 0) {
							lwgdpr_tab_elm.fadeIn();
						}
					}
				);
				var location_hash = window.location.hash;
				if (location_hash != "") {
					var lwgdpr_tab_hash = location_hash.charAt( 0 ) == '#' ? location_hash.substring( 1 ) : location_hash;
					if ( lwgdpr_tab_hash != "" ) {
						$( 'div[data-id="' + lwgdpr_tab_hash + '"]' ).show();
						$( 'a[href="#' + lwgdpr_tab_hash + '"]' ).addClass( 'nav-tab-active' );
					}
				} else {
					lwgdpr_nav_tab.eq( 0 ).click();
				}
			}
			$( '.lwgdpr_sub_tab li' ).click(
				function(){
					var trgt = $( this ).attr( 'data-target' );
					var prnt = $( this ).parent( '.lwgdpr_sub_tab' );
					var ctnr = prnt.siblings( '.lwgdpr_sub_tab_container' );
					prnt.find( 'li a' ).css( {'color':'#0073aa','cursor':'pointer'} );
					$( this ).find( 'a' ).css( {'color':'unset','cursor':'default'} );
					ctnr.find( '.lwgdpr_sub_tab_content' ).hide();
					ctnr.find( '.lwgdpr_sub_tab_content[data-id="' + trgt + '"]' ).fadeIn();
				}
			);
			$( '.lwgdpr_sub_tab' ).each(
				function(){
					var template   = $( 'input[name="lwgdpr_template"]' ).val();
					var active_tab = $( '.lwgdpr-cookie-consent-tab-head .nav-tab.nav-tab-active' ).html();
					var elm        = $( this ).children( 'li' ).eq( 0 );
					if ( active_tab == 'Design' && template && template != 'none' ) {
						elm = $( this ).children( 'li' ).eq( 1 );
					}
					elm.click();
				}
			);
			$( '.lwgdpr_cookie_sub_tab li' ).click(
				function(){
					var trgt = $( this ).attr( 'data-target' );
					var prnt = $( this ).parent( '.lwgdpr_cookie_sub_tab' );
					var ctnr = prnt.siblings( '.lwgdpr_cookie_sub_tab_container' );
					prnt.find( 'li a' ).css( {'color':'#0073aa','cursor':'pointer'} );
					$( this ).find( 'a' ).css( {'color':'unset','cursor':'default'} );
					ctnr.find( '.lwgdpr_cookie_sub_tab_content' ).hide();
					ctnr.find( '.lwgdpr_cookie_sub_tab_content[data-id="' + trgt + '"]' ).fadeIn();
				}
			);
			$( '.lwgdpr_cookie_sub_tab' ).each(
				function(){
					var elm = $( this ).children( 'li' ).eq( 0 );
					elm.click();
				}
			);
			$( '.lwgdpr_add_cookie' ).click(
				function() {
					lwgdpr_add_cookie_form();
				}
			);
			$( '.lwgdpr_delete_cookie' ).click(
				function() {
					lwgdpr_hide_cookie_form()
				}
			);
			$( document ).on(
				'change',
				'#post_cookie_list .cookie-type-field',
				function(){
					var parent        = $( this ).parents( 'table:first' );
					var cid           = parent.find( 'input[type="hidden"]' ).val();
					var selectedValue = $( this ).find( ":selected" ).val();
					if (selectedValue == 'HTTP') {
						parent.find( 'input[name="cookie_duration_field_' + cid + '"]' ).val( '' );
						parent.find( 'input[name="cookie_duration_field_' + cid + '"]' ).removeAttr( 'disabled' );
					} else {
						parent.find( 'input[name="cookie_duration_field_' + cid + '"]' ).val( 'Persistent' );
						parent.find( 'input[name="cookie_duration_field_' + cid + '"]' ).attr( 'disabled','disabled' );
					}
				}
			);
			$( '.form-table.add-cookie' ).find( '.cookie-type-field' ).on(
				'change',
				function() {
					var selectedValue = $( this ).find( ":selected" ).val();
					if (selectedValue == 'HTTP') {
						$( '.form-table.add-cookie' ).find( 'input[name="cookie_duration_field"]' ).val( '' );
						$( '.form-table.add-cookie' ).find( 'input[name="cookie_duration_field"]' ).removeAttr( 'disabled' );
					} else {
						$( '.form-table.add-cookie' ).find( 'input[name="cookie_duration_field"]' ).val( 'Persistent' );
						$( '.form-table.add-cookie' ).find( 'input[name="cookie_duration_field"]' ).attr( 'disabled','disabled' );
					}
				}
			);
			$(document).on('click', '#lwgdpr_export_settings', function (event) {
				event.preventDefault();
				var form_data = new FormData();
				form_data.append('action', 'lwgdpr_export_settings');
				form_data.append('security', lwgdpr_admin_ajax_object.security);
				$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: form_data,
						contentType: false,
						cache: false,
						processData: false
					})
					.done(function (response) {
						if (response.success === true) {
							// Assuming 'response.data' contains the JSON data you want to download
							var blob = new Blob([JSON.stringify(response.data.settings)], {type: "application/json"});
							var url = URL.createObjectURL(blob);
							// Create a link and set the URL as the link's href attribute
							var downloadLink = document.createElement("a");
							downloadLink.href = url;
							downloadLink.download = "lwgdpr_cookie_consent_settings.json";

							// Append the link to the body, click it, and then remove it
							document.body.appendChild(downloadLink);
							downloadLink.click();
							document.body.removeChild(downloadLink);

							// Clean up by revoking the Object URL
							URL.revokeObjectURL(url);

							lwgdpr_notify_msg.success(response.data.message);
						} else {
							lwgdpr_notify_msg.error(response.data.message);
						}
					})
					.fail(function (response) {
						console.log(response);
						lwgdpr_notify_msg.error(response.statusText);
					})
			});

			$(document).on('click', '#lwgdpr_import_settings', function (e) {
				e.preventDefault();
				var formData = new FormData();
				formData.append('action', 'lwgdpr_import_settings');
				formData.append('security', lwgdpr_admin_ajax_object.security);
				formData.append('import_settings_json', $('#import_settings_json')[0].files[0]);

				$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: formData,
						contentType: false,
						processData: false,
						success: function(response) {
							if (response.success) {
								lwgdpr_notify_msg.success(response.data.message + ', Reloading page in 2 seconds');
								setTimeout(
									function(){
										window.location.reload();
									},
									2000
								);
							} else {
								lwgdpr_notify_msg.error(response.data.message);
							}
						}
				});
			});

			$( '#lwgdpr_settings_form' ).submit(
				function(e){
					var submit_action = $( '#lwgdpr_update_action' ).val();
					e.preventDefault();
					var data       = $( this ).serialize();
					var url        = $( this ).attr( 'action' );
					var spinner    = $( this ).find( '.spinner' );
					var submit_btn = $( this ).find( 'input[type="submit"]' );
					spinner.css( {'visibility':'visible'} );
					submit_btn.css( {'opacity':'.5','cursor':'default'} ).prop( 'disabled',true );
					$.ajax(
						{
							url:url,
							type:'POST',
							data:data + '&lwgdpr_settings_ajax_update=' + submit_action,
							success:function(data)
						{
								spinner.css( {'visibility':'hidden'} );
								submit_btn.css( {'opacity':'1','cursor':'pointer'} ).prop( 'disabled',false );
								var flag = $( 'input[name="lwgdpr_template_updated"]' ).val();

								lwgdpr_notify_msg.success( lwgdpr_settings_success_message );

								lwgdpr_bar_active_msg();
								if ( flag && flag == '1' ) {
									setTimeout(
										function(){
											window.location.reload();
										},
										1000
									);
								} else {
									$( 'input[name="lwgdpr_template"]' ).val( 'none' )
									$( 'select[name="banner_template_field"]' ).val( 'none' );
									$( 'select[name="popup_template_field"]' ).val( 'none' );
									$( 'select[name="widget_template_field"]' ).val( 'none' );
									$( '.lwgdpr-template-type' ).hide();
								}
							},
							error:function ()
						{
								spinner.css( {'visibility':'hidden'} );
								submit_btn.css( {'opacity':'1','cursor':'pointer'} ).prop( 'disabled',false );

								lwgdpr_notify_msg.error( lwgdpr_settings_error_message );

							}
						}
					);
				}
			);

			function lwgdpr_add_cookie_form() {
				$( '.form-table.add-cookie' ).parent().find( '.lwgdpr_postbar' ).hide();
				$( '.form-table.add-cookie' ).show();
				$( '.form-table.add-cookie' ).find( 'input[type="hidden"]' ).remove();
				$( '.form-table.add-cookie' ).append( '<input type="hidden" name="lwgdpr_addcookie" value="1">' );
			}
			function lwgdpr_hide_cookie_form() {
				$( '.form-table.add-cookie' ).find( 'input' ).val( '' );
				$( '.form-table.add-cookie' ).find( 'select[name="cookie_type_field"]' ).val( 'HTTP' );
				$( '.form-table.add-cookie' ).find( 'textarea' ).val( '' );
				$( '.form-table.add-cookie' ).find( 'input[type="hidden"]' ).remove();
				$( '.form-table.add-cookie' ).parent().find( '.lwgdpr_postbar' ).show();
				$( '.form-table.add-cookie' ).hide();
			}
			function lwgdpr_scroll_accept_er()
			{
				if ($( '[name="cookie_bar_as_field"] option:selected' ).val() == 'popup' && $( '[name="popup_overlay_field"]:checked' ).val() == 'true' && $( '[name="scroll_close_field"]:checked' ).val() == 'true') {
					$( '.lwgdpr_scroll_accept_er' ).show();
				} else {
					$( '.lwgdpr_scroll_accept_er' ).hide();
				}
			}
			lwgdpr_scroll_accept_er();
			$( '[name="cookie_bar_as_field"]' ).change(
				function(){
					lwgdpr_scroll_accept_er();
				}
			);
			$( '[name="popup_overlay_field"], [name="scroll_close_field"]' ).click(
				function(){
					lwgdpr_scroll_accept_er();
				}
			);

			function lwgdpr_bar_active_msg()
			{
				$( '.lwgdpr_bar_state tr' ).hide();
				if ($( 'input[type="radio"].lwgdpr_bar_on' ).is( ':checked' )) {
					$( '.lwgdpr_bar_state tr.lwgdpr_bar_on' ).show();
				} else {
					$( '.lwgdpr_bar_state tr.lwgdpr_bar_off' ).show();
				}
			}
			var lwgdpr_tab_form_toggler = {
				set:function() {
					$( 'select.lwgdpr_tab_form_toggle' ).each(
						function(){
							lwgdpr_tab_form_toggler.toggle( $( this ) );
						}
					);
					$( 'select.lwgdpr_tab_form_toggle' ).change(
						function(){
							lwgdpr_tab_form_toggler.toggle( $( this ) );
						}
					);
				},
				toggle:function(elm) {
					var vl            = elm.val();
					var lwgdpr_tab_head = $( '.lwgdpr-cookie-consent-tab-head' );
					if ( vl == 'lwgdpr' || vl == 'both') {
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-cookie-list']" ).show();
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-script-blocker']" ).show();
					} else if ( vl == 'ccpa' ) {
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-cookie-list']" ).hide();
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-script-blocker']" ).hide();
					} else if ( vl == 'eprivacy' ) {
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-cookie-list']" ).hide();
						lwgdpr_tab_head.find( "a[href='#lwgdpr-cookie-consent-script-blocker']" ).show();
					}
					var trgt = elm.attr( 'lwgdpr_tab_frm_tgl-target' );
					$( '[lwgdpr_tab_frm_tgl-id="' + trgt + '"]' ).hide();
					var target_ids = $( '[lwgdpr_tab_frm_tgl-id="' + trgt + '"]' ).filter(
						function(){
							return $( '[lwgdpr_tab_frm_tgl-id="' + trgt + '"]' );
						}
					);
					target_ids.each(
						function(){
							var target_id  = $( this ).attr( 'data-id' );
							var target_elm = $( "li[data-target='" + target_id + "']" );
							if (target_id == 'design-template') {
								$( "li[data-target='design-general']" ).trigger( 'click' );
							}
							target_elm.hide();
						}
					);

					var selcted_trget = $( '[lwgdpr_tab_frm_tgl-id="' + trgt + '"]' ).filter(
						function(){
							if (vl == 'both') {
								return $( this ).attr( 'lwgdpr_tab_frm_tgl-val' ) == 'lwgdpr' || $( this ).attr( 'lwgdpr_tab_frm_tgl-val' ) == 'ccpa';
							} else if (vl == 'eprivacy') {
								return $( this ).attr( 'lwgdpr_tab_frm_tgl-val1' ) == 'eprivacy';
							} else {
								return $( this ).attr( 'lwgdpr_tab_frm_tgl-val' ) == vl;
							}
						}
					);
					selcted_trget.each(
						function(){
							var target_id  = $( this ).attr( 'data-id' );
							var target_elm = $( "li[data-target='" + target_id + "']" );
							target_elm.show();
							if ( ( vl == 'both' && target_id == 'accept-button' ) || ( ( vl == 'lwgdpr' && target_id == 'accept-button' ) ) || ( vl == 'ccpa' && target_id == 'confirm-button' ) || ( vl == 'eprivacy' && target_id == 'accept-button' ) ) {
								target_elm.css( 'border-left','none' );
								target_elm.css( 'padding-left','0px' );
								target_elm.trigger( 'click' );
							} else {
								target_elm.css( 'padding','3px 10px' );
								target_elm.css( 'border-left', 'solid 1px #ccc' );
							}
							// $(this).show();
						}
					);
				}
			}
			lwgdpr_tab_form_toggler.set();
			var lwgdpr_form_toggler     =
			{
				set:function()
				{
					$( '.lwgdpr-template-type' ).hide();
					var template = $( 'input[name="lwgdpr_template"]' ).val();
					$( '.lwgdpr-template-type.' + template ).show();
					$( 'select[name="banner_template_field"]' ).change(
						function() {
							var vl = $( this ).val();
							$( 'input[name="lwgdpr_template_updated"]' ).val( '1' );
							$( '.lwgdpr-template-type' ).hide();
							$( '.lwgdpr-template-type.' + vl ).show();
						}
					)
					$( 'select[name="popup_template_field"]' ).change(
						function() {
							var vl = $( this ).val();
							$( 'input[name="lwgdpr_template_updated"]' ).val( '1' );
							$( '.lwgdpr-template-type' ).hide();
							$( '.lwgdpr-template-type.' + vl ).show();
						}
					)
					$( 'select[name="widget_template_field"]' ).change(
						function() {
							var vl = $( this ).val();
							$( 'input[name="lwgdpr_template_updated"]' ).val( '1' );
							$( '.lwgdpr-template-type' ).hide();
							$( '.lwgdpr-template-type.' + vl ).show();
						}
					)
					$( 'select.lwgdpr_form_toggle' ).each(
						function(){
							lwgdpr_form_toggler.toggle( $( this ) );
						}
					);
					$( 'input[type="radio"].lwgdpr_form_toggle' ).each(
						function(){
							if ($( this ).is( ':checked' )) {
								lwgdpr_form_toggler.toggle( $( this ) );
							}
						}
					);
					$( 'select.lwgdpr_form_toggle' ).change(
						function(){
							lwgdpr_form_toggler.toggle( $( this ) );
						}
					);
					$( 'input[type="radio"].lwgdpr_form_toggle' ).click(
						function(){
							if ($( this ).is( ':checked' )) {
								lwgdpr_form_toggler.toggle( $( this ) );
							}
						}
					);
				},
				toggle:function(elm)
				{
					var vl       = elm.val();
					var lwgdpr_val = $( '[lwgdpr_frm_tgl-val="' + vl + '"] select[name="' + vl + '_template_field"]' ).val();
					if (vl == 'banner') {
						$( '.lwgdpr-template-type' ).hide();
						$( '.lwgdpr-template-type.' + lwgdpr_val ).show();
					}
					if (vl == 'popup') {
						$( '.lwgdpr-template-type' ).hide();
						$( '.lwgdpr-template-type.' + lwgdpr_val ).show();
					}
					if (vl == 'widget') {
						$( '.lwgdpr-template-type' ).hide();
						$( '.lwgdpr-template-type.' + lwgdpr_val ).show();
					}
					var trgt = elm.attr( 'lwgdpr_frm_tgl-target' );
					$( '[lwgdpr_frm_tgl-id="' + trgt + '"]' ).hide();
					var selcted_trget = $( '[lwgdpr_frm_tgl-id="' + trgt + '"]' ).filter(
						function(){
							if (vl == 'both') {
								return $( this ).attr( 'lwgdpr_frm_tgl-val' ) == 'lwgdpr' || $( this ).attr( 'lwgdpr_frm_tgl-val' ) == 'ccpa';
							} else if (vl == 'eprivacy') {
								return $( this ).attr( 'lwgdpr_frm_tgl-val1' ) == 'eprivacy' || $( this ).attr( 'lwgdpr_frm_tgl-val' ) == 'eprivacy'
							} else {
								return $( this ).attr( 'lwgdpr_frm_tgl-val' ) == vl;
							}
						}
					);
					selcted_trget.show();
				}
			}

			lwgdpr_form_toggler.set();

			$( document ).on(
				'change',
				'input[name="is_eu_on_field"]',
				function(){
					if (this.value == 'true') {
						$( '.lwgdpr-maxmind-notice' ).show();
					} else {
						if ($( 'input[name="is_ccpa_on_field"]:checked' ).val() == 'false') {
							$( '.lwgdpr-maxmind-notice' ).hide();
						}
					}
				}
			);
			$( document ).on(
				'change',
				'input[name="is_ccpa_on_field"]',
				function(){
					if (this.value == 'true') {
						$( '.lwgdpr-maxmind-notice' ).show();
					} else {
						if ($( 'input[name="is_eu_on_field"]:checked' ).val() == 'false') {
							$( '.lwgdpr-maxmind-notice' ).hide();
						}
					}
				}
			);

		}
	);

})( jQuery );
var lwgdpr_notify_msg =
	{
		error:function(message)
		{
			var er_elm = jQuery( '<div class="notify_msg" style="background:#d9534f; border:solid 1px #dd431c;">' + message + '</div>' );
			this.setNotify( er_elm );
		},
		success:function(message)
		{
			var suss_elm = jQuery( '<div class="notify_msg" style="background:#5cb85c; border:solid 1px #2bcc1c;">' + message + '</div>' );
			this.setNotify( suss_elm );
		},
		setNotify:function(elm)
		{
			jQuery( 'body' ).append( elm );
			elm.stop( true,true ).animate( {'opacity':1,'top':'50px'},1000 );
			setTimeout(
				function(){
					elm.animate(
						{'opacity':0,'top':'100px'},
						1000,
						function(){
							elm.remove();
						}
					);
				},
				3000
			);
		}
	}
	function lwgdpr_store_settings_btn_click(vl)
	{
		document.getElementById( 'lwgdpr_update_action' ).value = vl;
	}
	function lwgdpr_print_value(src, trgt)
	{
		var source   = document.getElementById( src );
		var target   = document.getElementById( trgt );
		target.value = source.value;
	}
