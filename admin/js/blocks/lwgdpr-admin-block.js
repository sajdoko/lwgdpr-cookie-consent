/**
 * Admin Block JavaScript.
 *
 * @package    Lw_Gdpr_Cookie_Consent
 * @subpackage Lw_Gdpr_Cookie_Consent/admin
 * @author     sajdoko <https://www.localweb.it/>
 */

(function( $ ) {
	const {registerBlockType} = wp.blocks; // Blocks API.
	const {createElement}     = wp.element; // React.createElement.
	const {__}                = wp.i18n; // Translation functions.
	const {InspectorControls,withColors,PanelColorSettings,getColorClassName} = wp.editor; // Block inspector wrapper.
	const {SelectControl,ServerSideRender}                                    = wp.components; // Block inspector wrapper.

	registerBlockType(
		'lwgdpr/block',
		{
			title: __( 'GDPR Cookie Details' ),
			category:  __( 'common' ),
			keywords: [
			__( 'lwgdpr' ),
			__( 'cookie' ),
			__( 'cookie links' )
			],
			edit( props ){
				return createElement(
					'div',
					{},
					[
					// Preview will go here.
					createElement(
						ServerSideRender,
						{
							block: 'lwgdpr/block',
							key:'lwgdpr'
						}
					)
					]
				)
			},
			save(){
				return null; // Save has to exist. This all we need.
			}
		}
	);
})( jQuery );
