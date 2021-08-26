(function( $ ) {
	'use strict';

	/**
	 * Vars
	 */
	window.isAYGReady = false;
	
	/**
	 * Init Automatic YouTube Gallery. Called when YouTube API is ready.
	 *
	 * @since 1.0.0
	 */
	function initAYG() {
		if ( true == window.isAYGReady ) {
			return;
		}

		window.isAYGReady = true;
		$( document ).trigger( 'AYG.onReady' );
		
		// Classic theme
		$( '.ayg-theme-classic' ).each(function() {
			var params = JSON.parse( $( this ).attr( 'data-params' ) );
			var options = window.AYGResolveThemeOptions( params );

			$( this ).AYGTheme( options );
		});		
	}

	/**
	 * jQuery Plugin: AYGTheme
	 *
	 * @since 1.0.0
	 */
	$.fn.AYGTheme = function( options ) {
		var defaults = {            
			autoplay: 0,
			loop: 0,
			controls: 1,
			modestbranding: 1,
			cc_load_policy: 0,
			iv_load_policy: 1,
			hl: '',
			cc_lang_pref: '',
			autoadvance: 0,
			player_title: 1,
			player_description: 1,
			scrollTop: 1
		};

		var settings = $.extend( {}, defaults, options );		
		
		// Private vars
		var root = this;

		var videos = {
			count: 0,
			currentIndex: 0
		};

		var gallery = this.find( '.ayg-gallery' );

		// Private methods
		var onVideoEnded = function() {
			if ( settings.autoadvance && videos.count > 1 ) {
				var nextItem = parseInt( videos.currentIndex ) + 1;

				if ( nextItem >= videos.count ) {
					nextItem = settings.loop ? 0 : -1;
				}

				if ( nextItem > -1 ) {
					root.find( '.ayg-thumbnail' ).eq( nextItem ).trigger( 'click' );
				}
			} else if ( settings.loop ) {
				root.player.playVideo();
			}
		}

		var onVideoChanged = function() {
			// Make item active
			root.find( '.ayg-thumbnail' ).removeClass( 'ayg-active' );
			$( this ).addClass( 'ayg-active' );

			// Reset active item index
			videos.currentIndex = $( this ).attr( 'data-index' );

			// Replace title
			if ( settings.player_title ) {
				var title = $( this ).attr( 'data-title' );
				root.find( '.ayg-player-title' ).html( title );
			}

			// Replace player
			var videoId = $( this ).attr( 'data-id' );
			root.player.loadVideoById( videoId );

			// Replace description
			if ( settings.player_description ) {
				var description = $( this ).find( '.ayg-thumbnail-description' ).html();
				root.find( '.ayg-player-description' ).html( description );
			}

			// Scroll to top
			if ( settings.scrollTop ) {
				$( 'html, body' ).animate({
					scrollTop: root.offset().top - ayg_public.top_offset
				}, 500);
			}

			// Load next page
			loadNextPage();
		}	

		var loadNextPage = function() {
			if ( settings.autoadvance ) {
				var nextItem = parseInt( videos.currentIndex ) + 1;

				if ( nextItem >= videos.count && root.loadNextPage ) {
					setTimeout(function() { 
						root.loadNextPage();
					}, 300);
				}
			}
		}

		var update = function() {
			var index = -1;

			root.find( '.ayg-thumbnail' ).each(function() {
				$( this ).attr( 'data-index', ++index );
			});

			videos.count = index + 1;
		}

		// Public methods
        this.initialize = function() {			
			update();

			// Player
			this.AYGPlayer({
				playerId: this.find( '.ayg-player-iframe' ).attr( 'id' )
			});

			this.on( 'AYG.onVideoEnded', function() { 
				onVideoEnded();
			});	

			// Gallery
			this.on( 'click', '.ayg-thumbnail', function( event ) {
				event.preventDefault();
				onVideoChanged.apply( this );
			});
			
			// Pagination
			this.AYGPagination();

			this.on( 'AYG.onGalleryUpdated', function( event, response ) {
				if ( response.currentIndex ) {
					videos.currentIndex = response.currentIndex;
				}

				update();
			});

			return this;			
		}		
		
		return this.initialize();		
	}
	
	/**
	 * jQuery Plugin: AYGPlayer
	 *
	 * @since 1.0.0
	 */
	$.fn.AYGPlayer = function( options ) {
		var defaults = {
            playerId: ''
		};

		var settings = $.extend( {}, defaults, options );

		// Private variables
		var root = this;	

		// Public variables	
		this.player = null;	

		// Public methods
		this.initialize = function() {
			this.player = new YT.Player( settings.playerId, {
				events: {
					'onStateChange': function( event ) {
						if ( event.data == YT.PlayerState.ENDED ) {
							root.player.stopVideo();
							root.trigger( 'AYG.onVideoEnded' );
						}				 
					}
				}
			});

			return this;
		}

		return this.initialize();
	}

	/**
	 * jQuery Plugin: AYGPagination
	 *
	 * @since 1.0.0
	 */
	$.fn.AYGPagination = function() {
		// Private variables
		var root = this;

		var pagination = this.find( '.ayg-pagination' );		
		if ( 0 === pagination.length ) {
			return this;
		}

		var params = JSON.parse( pagination.attr( 'data-params' ) );
		params.nonce = pagination.attr( 'data-nonce' );

		var gallery = this.find( '.ayg-gallery' );

		// Private methods
		var load = function( type ) {
			var $this = this;

			pagination.addClass( 'ayg-loading' );			

			params.action = 'ayg_load_more_videos';
			params.pageToken = ( 'prev' == type ) ? params.prev_page_token : params.next_page_token;

			$.post( ayg_public.ajax_url, params, function( response ) {
				if ( response.success ) {
					var args = {};

					switch ( type ) {
						case 'prev':
							prev.apply( $this, [response] );
							args.currentIndex = -1; 
							break;
						case 'next':
							next.apply( $this, [response] );
							args.currentIndex = -1; 
							break;
						case 'more':
							more.apply( $this, [response] );
							break;
					}

					root.trigger( 'AYG.onGalleryUpdated', args );
				} else {
					pagination.removeClass( 'ayg-loading' ).hide();
				}
			});
		}

		var prev = function( response ) {
			params.paged = Math.max( parseInt( params.paged ) - 1, 1 );

			if ( response.data.next_page_token ) {
				params.next_page_token = response.data.next_page_token;
			} else {
				params.next_page_token = '';
			}

			if ( response.data.prev_page_token ) {
				params.prev_page_token = response.data.prev_page_token;
			} else {
				params.prev_page_token = '';
			}

			if ( 1 == params.paged ) {
				params.prev_page_token = '';
				this.hide();
			}

			pagination.find( '.ayg-pagination-next-btn' ).show();
			pagination.find( '.ayg-pagination-current-page-number' ).html( params.paged );			
			pagination.removeClass( 'ayg-loading' );

			gallery.html( response.data.html );
		}

		var next = function( response ) {
			var num_pages = parseInt( params.num_pages );
			params.paged = Math.min( parseInt( params.paged ) + 1, num_pages );					

			if ( response.data.next_page_token ) {
				params.next_page_token = response.data.next_page_token;
			} else {
				params.next_page_token = '';
			}

			if ( response.data.prev_page_token ) {
				params.prev_page_token = response.data.prev_page_token;
			} else {
				params.prev_page_token = '';
			}

			if ( params.paged == num_pages ) {
				params.next_page_token = '';
				this.hide();
			}

			pagination.find( '.ayg-pagination-prev-btn' ).show();
			pagination.find( '.ayg-pagination-current-page-number' ).html( params.paged );			
			pagination.removeClass( 'ayg-loading' );

			gallery.html( response.data.html );
		}	

		var more = function( response ) {
			var num_pages = parseInt( params.num_pages );
			params.paged = Math.min( parseInt( params.paged ) + 1, num_pages );					

			if ( response.data.next_page_token ) {
				params.next_page_token = response.data.next_page_token;
			} else {
				params.next_page_token = '';
			}

			if ( params.paged == num_pages ) {
				params.next_page_token = '';
				this.hide();
			}
			
			pagination.removeClass( 'ayg-loading' );

			gallery.append( response.data.html );
		}

		// Public methods
        this.initialize = function() {
			// On 'Prev' button clicked
			this.find( '.ayg-pagination-prev-btn' ).on( 'click', function( event ) {
				event.preventDefault();
				load.apply( $( this ), ['prev'] );
			});

			// On 'Next' button clicked
			this.find( '.ayg-pagination-next-btn' ).on( 'click', function( event ) {
				event.preventDefault();
				load.apply( $( this ), ['next'] );
			});				

			// On 'More Videos' button clicked
			this.find( '.ayg-pagination-more-btn' ).on( 'click', function( event ) {
				event.preventDefault();
				load.apply( $( this ), ['more'] );
			});

			return this;
		}

		this.loadNextPage = function() {
			var paged = parseInt( params.paged );
			var num_pages = parseInt( params.num_pages );

			if ( paged < num_pages ) {
				if ( 'load_more' == params.pagination_type ) {
					root.find( '.ayg-pagination-more-btn' ).trigger( 'click' );
				}
			}
		}

		return this.initialize();
	}

	/**
	 * Called when the page has loaded.
	 *
	 * @since 1.0.0
	 */
	$(function() {
		// Init Automatic YouTube Gallery
		if ( 'undefined' === typeof window['YT'] ) {
			var tag = document.createElement( 'script' );
			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName( 'script' )[0];
			firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );		
		}
		
		if ( 'undefined' == typeof window.onYouTubeIframeAPIReady ) {
			window.onYouTubeIframeAPIReady = function() {
				initAYG();
			};
		} else if ( 'undefined' !== typeof window.YT ) {
			initAYG();
		}
		
		var interval = setInterval(
			function() {
				if ( 'undefined' !== typeof window.YT && window.YT.loaded )	{
					clearInterval( interval );
					initAYG();					
				}
			}, 
			10
		);

		// Toggle more/less content in the player description
		$( document ).on( 'click', '.ayg-player-description-toggle-btn', function( event ) {
			event.preventDefault();

			var $this = $( this);
			var $description = $this.closest( '.ayg-player-description' );
			var $dots = $description.find( '.ayg-player-description-dots' );
			var $more = $description.find( '.ayg-player-description-more' );

			if ( $dots.is( ':visible' ) ) {
				$this.html( ayg_public.i18n.show_less );
				$dots.hide();
				$more.fadeIn();									
			} else {
				$this.html( ayg_public.i18n.show_more );	
				$more.fadeOut(function() {
					$dots.show();					
				});								
			}	
		});
	});

})( jQuery );

/**
 * Resolve Theme Options.
 *
 * @since 1.6.1
 */
function AYGResolveThemeOptions( params ) {
	var options = {				
		loop: parseInt( params.loop ),
		autoadvance: parseInt( params.autoadvance )
	};

	if ( params.hasOwnProperty( 'is_rtl' ) ) {
		options.is_rtl = parseInt( params.is_rtl );
	}

	if ( params.hasOwnProperty( 'autoplay' ) ) {
		options.autoplay = parseInt( params.autoplay );
	}

	if ( params.hasOwnProperty( 'controls' ) ) {
		options.controls = parseInt( params.controls );
	}

	if ( params.hasOwnProperty( 'modestbranding' ) ) {
		options.modestbranding = parseInt( params.modestbranding );
	}

	if ( params.hasOwnProperty( 'cc_load_policy' ) ) {
		options.cc_load_policy = parseInt( params.cc_load_policy );
	}

	if ( params.hasOwnProperty( 'iv_load_policy' ) ) {
		options.iv_load_policy = parseInt( params.iv_load_policy );
	}

	if ( params.hasOwnProperty( 'hl' ) ) {
		options.hl = params.hl;
	}

	if ( params.hasOwnProperty( 'cc_lang_pref' ) ) {
		options.cc_lang_pref = params.cc_lang_pref;
	}

	if ( params.hasOwnProperty( 'player_title' ) ) {
		options.player_title = parseInt( params.player_title );
	}

	if ( params.hasOwnProperty( 'player_description' ) ) {
		options.player_description = parseInt( params.player_description );
	}

	if ( params.hasOwnProperty( 'arrow_size' ) ) {
		options.arrow_size = params.arrow_size;
	}

	if ( params.hasOwnProperty( 'arrow_bg_color' ) ) {
		options.arrow_bg_color = params.arrow_bg_color;
	}

	if ( params.hasOwnProperty( 'arrow_icon_size' ) ) {
		options.arrow_icon_size = params.arrow_icon_size;
	}

	if ( params.hasOwnProperty( 'arrow_icon_color' ) ) {
		options.arrow_icon_color = params.arrow_icon_color;
	}

	if ( params.hasOwnProperty( 'arrow_radius' ) ) {
		options.arrow_radius = params.arrow_radius;
	}

	if ( params.hasOwnProperty( 'arrow_top_offset' ) ) {
		options.arrow_top_offset = params.arrow_top_offset;
	}

	if ( params.hasOwnProperty( 'arrow_left_offset' ) ) {
		options.arrow_left_offset = params.arrow_left_offset;
	}

	if ( params.hasOwnProperty( 'arrow_right_offset' ) ) {
		options.arrow_right_offset = params.arrow_right_offset;
	}

	if ( params.hasOwnProperty( 'dot_size' ) ) {
		options.dot_size = params.dot_size;
	}

	if ( params.hasOwnProperty( 'dot_color' ) ) {
		options.dot_color = params.dot_color;
	}

	return options;
}

/**
 * Resolve Player URL.
 *
 * @since 1.6.1
 */
function AYGResolvePlayerURL( videoId, settings ) {
	var url = 'https://www.youtube-nocookie.com/embed/' + videoId + '?rel=0&playsinline=1&enablejsapi=1';

	if ( settings.autoplay ) {
		url += '&autoplay=1';
	}

	if ( settings.loop ) {
		url += '&loop=1';
	}

	if ( ! settings.controls ) {
		url += '&controls=0';
	}

	if ( settings.modestbranding ) {
		url += '&modestbranding=1';
	}

	if ( settings.cc_load_policy ) {
		url += '&cc_load_policy=1';
	}

	if ( ! settings.iv_load_policy ) {
		url += '&iv_load_policy=3';
	}

	if ( settings.hl ) {
		url += '&hl=' + settings.hl;
	}

	if ( settings.cc_lang_pref ) {
		url += '&cc_lang_pref=' + settings.cc_lang_pref;
	}
	
	return url;
}