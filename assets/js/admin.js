(function ($) {

	'use strict';

	// Show Auto Complete display
	$( 'body' ).on(
		'change',
		'.display-on',
		function(e) {
			var btn      = $( this );
			var postType = $( this ).val();
			var data     = {
				action: 'boostify_hf_load_autocomplate',//boostify_hf_post_admin
				_ajax_nonce: admin.nonce,
				post_type: postType,
			};

			$.ajax(
				{
					type: 'POST',
					url: admin.url,
					data: data,
					beforeSend: function (response) {
						$( '#ht_hf_setting' ).addClass( 'loading' );
					},
					success: function (response) {
						$( '#ht_hf_setting' ).removeClass( 'loading' );
						console.log( response );
						btn.parents( '.condition-group' ).find( '.child-item' ).html( response );
					},
				}
			);
		}
	);

	// Show Auto Complete No Display
	$( 'body' ).on(
		'change',
		'.no-display-on',
		function(e) {
			var btn      = $( this );
			var postType = btn.val();
			var data     = {
				action: 'boostify_hf_ex_auto',//boostify_hf_post_admin
				_ajax_nonce: admin.nonce,
				post_type: postType,
			};
			$.ajax(
				{
					type: 'POST',
					url: admin.url,
					data: data,
					beforeSend: function (response) {
						$( '#ht_hf_setting' ).addClass( 'loading' );
					},
					success: function (response) {
						$( '#ht_hf_setting' ).removeClass( 'loading' );
						btn.parents( '.condition-group' ).find( '.child-item' ).html( response );
					},
				}
			);
		}
	);

	// Load post display
	$( 'body' ).on(
		'keyup',
		'.boostify--hf-post-name',
		function () {
			var btn        = $( this );
			var parentItem = btn.parents( '.input-item-wrapper' );
			var postType   = parentItem.find( '.bsg-post-type' ).val();
			var keyword    = btn.val();

			console.log( postType );
			var data = {
				action: 'boostify_hf_post_admin',//boostify_hf_post_admin
				_ajax_nonce: admin.nonce,
				post_type: postType,
				key: keyword,
			};
			$.ajax(
				{
					type: 'GET',
					url: admin.url,
					data: data,
					beforeSend: function (response) {
						$( '#ht_hf_setting' ).addClass( 'loading' );
					},
					success: function (response) {
						$( '#ht_hf_setting' ).removeClass( 'loading' );
						parentItem.find( '.boostify-data' ).html( response );
					},
				}
			);
		}
	);

	// Select Post display
	$( 'body' ).on(
		'click',
		'.display--on .post-item',
		function () {

			var listPost = $( 'input[name=bsg_post]' ).val();
			var all      = $( '.display--on .boostify-select-all-post' );
			var parent   = all.parents( '.boostify-section-select-post' );
			if ( ! all.hasClass( 'hidden' ) ) {
				all.addClass( 'hidden' );
			}
			if ( ! listPost || 'all' == listPost ) {
				listPost = [];
			} else {
				listPost = listPost.split( ',' );
			}
			if ( ! parent.hasClass( 'has-option' ) ) {
				parent.addClass( 'has-option' );
			}
			var id    = $( this ).attr( 'data-item' );
			var title = $( this ).html();
			if ( ! listPost.includes( id ) ) {
				listPost.push( id );
				var html = '<span class="boostify-auto-complete-key">' +
								'<span class="boostify-title">' + title + '</span>' +
								'<span class="btn-boostify-auto-complete-delete ion-close" data-item="' + id + '"></span>'
							'</span>';
				$( '.boostify--hf-post-name' ).before( html );
			}
			$( 'input[name=bsg_post]' ).val( listPost );
			$( '.boostify--hf-post-name' ).val( '' );// Reset Input
			$( '.boostify-data' ).html( '' );
		}
	);

	// Select Post not display
	$( 'body' ).on(
		'click',
		'.not-display .post-item',
		function () {

			var listPost = $( 'input[name=bsg_ex_post]' ).val();
			var all      = $( '.not-display .boostify-select-all-post' );
			var parent   = all.parents( '.boostify-section-select-post' );
			if ( ! all.hasClass( 'hidden' ) ) {
				all.addClass( 'hidden' );
			}
			if ( ! listPost || 'all' == listPost ) {
				listPost = [];
			} else {
				listPost = listPost.split( ',' );
			}
			if ( ! parent.hasClass( 'has-option' ) ) {
				parent.addClass( 'has-option' );
			}
			var id    = $( this ).attr( 'data-item' );
			var title = $( this ).html();
			if ( ! listPost.includes( id ) ) {
				listPost.push( id );
				var html = '<span class="boostify-auto-complete-key">' +
								'<span class="boostify-title">' + title + '</span>' +
								'<span class="btn-boostify-auto-complete-delete ion-close" data-item="' + id + '"></span>'
							'</span>';
				$( '.boostify--hf-post-name' ).before( html );
			}
			$( 'input[name=bsg_ex_post]' ).val( listPost );
			$( '.boostify--hf-post-name' ).val( '' );// Reset Input
			$( '.boostify-data' ).html( '' );
		}
	);

	// Focus Input Field
	$( 'body' ).on(
		'click',
		'.boostify-auto-complete-field',
		function(e) {
			var btn  = $( this );
			var form = btn.find( '.boostify--hf-post-name' );
			$( form , this ).focus();
		}
	);

	// Delete Post
	$( 'body' ).on(
		'click',
		'.btn-boostify-auto-complete-delete',
		function(e) {
			var id       = $( this ).attr( 'data-item' );
			var listPost = $( 'input[name=bsg_post]' ).val();
			var render   = $( '.boostify-section-render--post' );
			var parent   = render.parents( '.boostify-section-select-post' );
			listPost     = listPost.replace( ',' + id, '' );
			listPost     = listPost.replace( id, '' );
			if ( listPost == '' ) {
				listPost = 'all';
				render.addClass( 'hidden' );
				parent.removeClass( 'render--post' ).addClass( 'select-all' );
				parent.removeClass( 'has-option' );
				render.siblings( '.boostify-select-all-post' ).removeClass( 'hidden' );
				$( '.boostify-data' ).html( '' );
			}
			$( 'input[name=bsg_post]' ).val( listPost );
			$( this ).parents( '.boostify-auto-complete-key' ).remove();
		}
	);

	// Select All Post
	$( 'body' ).on(
		'click',
		'.boostify-select-all-post',
		function(e) {
			var parents = $( this ).parents( '.boostify-section-select-post' );
			var render  = parents.find( '.boostify-section-render--post' );
			if ( render.hasClass( 'hidden' ) ) {
				render.removeClass( 'hidden' );
				parents.removeClass( 'select-all' ).addClass( 'render--post' );
			} else {
				render.addClass( 'hidden' );
				parents.removeClass( 'render--post' ).addClass( 'select-all' );
			}
		}
	);

	$( 'body' ).on(
		'click',
		function( e ) {
			$( '.boostify-data' ).html( '' );
			$( '.boostify--hf-post-name' ).val( '' );// Reset data
		}
	);

	// Select-type
	$( '#container' ).on(
		'change',
		function(e) {
			var btn  = $( this );
			var type = $( this ).val();
			var data = {
				action: 'boostify_hf_type',//boostify_hf_post_admin
				_ajax_nonce: admin.nonce,
				type: type,
			};
			if ( 'sub_menu' == type ) {
				btn.parents('.input-wrapper').siblings('.input-wrapper').remove();
			} else {
				var check = btn.parents('.input-wrapper').siblings('.input-wrapper');
				console.log( check  );
				if ( check.length == 0 ) {
					$.ajax(
						{
							type: 'GET',
							url: admin.url,
							data: data,
							beforeSend: function (response) {
								$( '#ht_hf_setting' ).addClass( 'loading' );
							},
							success: function (response) {
								$( '#ht_hf_setting' ).removeClass( 'loading' );
								$('.form-meta-footer').append( response );
							},
						}
					);
				}
			}
		}
	);

} )( jQuery );
