(function ($) {
	'use strict';

	/**
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetSiteSearch = function ($scope, $) {
	/**
	 * Top search form functions.
	 */
		var searchContainer = $scope.find('.boostify-search--toggle');
		var btn = $scope.find( '.boostify-search-icon--toggle' );
		var close = $scope.find( '.boostify--site-search-close' );

		btn.on( 'click', function () {
			searchContainer.addClass( 'show' );
		} );

		close.on('click', function () {
			searchContainer.removeClass( 'show' );
		});

		$( document ).on( 'keydown', function ( e ) {
			if ( e.keyCode === 27 ) { // ESC
				searchContainer.removeClass( 'show' );
			}
		});

	};


	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ht-site-search.default', WidgetSiteSearch);
	});
})(jQuery);