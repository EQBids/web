/**
 * Handles toggling the main navigation menu for small screens.
 */
jQuery( document ).ready( function( $ ) {
	var $masthead = $( '.navigation-wrap' ),
	    timeout = false;

	$.fn.smallMenu = function() {
		$masthead.find( '.site-navigation' ).removeClass( 'main-navigation' ).addClass( 'main-small-navigation' );
		$masthead.find( '.site-navigation h1' ).removeClass( 'assistive-text' ).addClass( 'menu-toggle' );

		$('.header-search .toggle-box-search form')[0].outerHTML = '<li id="menu-search">' + $('.header-search .toggle-box-search form')[0].outerHTML + '</li>';
		$('.header-search').find('.toggle-box-search li').appendTo('#menu-head-navigation');

		$( '.menu-toggle' ).unbind( 'click' ).click( function() {
			$('#page').toggleClass('small-menu');
			$masthead.find( '.menu' ).slideToggle(200);
			$( this ).toggleClass( 'toggled-on' );
			$('.header-search,.toggle-icons').toggle();
		} );
	};

	// Check viewport width on first load.
	if ( $( window ).width() < 991 )
		$.fn.smallMenu();

	// Check viewport width when user resizes the browser window.
	$( window ).resize( function() {
		var browserWidth = $( window ).width();

		if ( false !== timeout )
			clearTimeout( timeout );

		timeout = setTimeout( function() {
			if ( browserWidth < 991 ) {
				$.fn.smallMenu();
				$('.header-search,.toggle-icons').hide();
			} else {
				if ($('#page').hasClass('small-menu')) {
					$('#page').toggleClass('small-menu');
				}

				$('#menu-head-navigation form').appendTo('.toggle-box-search');
				$masthead.find( '.site-navigation' ).removeClass( 'main-small-navigation' ).addClass( 'main-navigation' );
				$masthead.find( '.site-navigation h1' ).removeClass( 'menu-toggle' ).addClass( 'assistive-text' );
				$masthead.find( '.menu' ).removeAttr( 'style' );
				$('.header-search,.toggle-icons').show();
			}
		}, 200 );
	} );

	$('.fa-search').on('click', function() {
		window.location.href = 'http://blog.pusher.com/?s=' + encodeURIComponent($('.header-search input').val());
	})
} );