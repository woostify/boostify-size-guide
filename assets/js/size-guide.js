/**
 * Size Guide js
 *
 * @package Boostify_Size_Guide
 */

'use strict';

// Size guide.
function boostifySizeGuide() {
	var sizeGuide = jQuery( '.boostify-size-guide-popup' ),
		popUp     = jQuery( '.cd-popup' );

	sizeGuide.on(
		'click',
		function(e) {
			e.preventDefault();
			popUp.toggleClass( 'is-visible' );
		}
	);

	// close popup when clicking the esc keyboard button.
	jQuery( document ).keyup(
		function( event ) {
			if ( event.which == '27' ) {
				jQuery( '.cd-popup' ).removeClass( 'is-visible' );
			}
		}
	);
};

jQuery( document ).ready(
	function(){
		// For frontend.
		jQuery( window ).on(
			'load',
			function() {
				boostifySizeGuide();
			}
		);

		if ( undefined !== window.elementorFrontend && undefined !== window.elementorFrontend.hooks ) {
			boostifySizeGuide();

			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/global',
				function() {
					boostifySizeGuide();
				}
			);
		}
	}
);
