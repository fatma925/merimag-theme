/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
		

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
	function replaceAll(str, find, replace) {
    	return str.split(find).join(replace);
	}
	
	function capitalize(str) {
	  strVal = '';
	  str = str.split(' ');
	  for (var chr = 0; chr < str.length; chr++) {
	    strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length);
	    if( chr < ( str.length - 1 ) ) {
	    	strVal += ' ';
	    }
	  }
	  return strVal
	}
	for( var typo_setting in customizer_data.typography_selectors ) {
		wp.customize('fw_options[' + typo_setting + ']', function(value) {
			value.bind( function(to) {
				var css = '';
				var typo_obj = JSON.parse(to);
				var get_setting = replaceAll(typo_obj[0].name, '[', '' );
				get_setting = replaceAll(get_setting, ']', '' );
				get_setting = replaceAll(get_setting, 'fw_options', '' );
				get_setting = replaceAll(get_setting, 'family', '' );
				
				if( $('style#' + get_setting ).length > 0 ) {
					$('style#' + get_setting ).remove();
				}
				var compnents = customizer_data.typography_components;
				for( var typo_index in typo_obj ) {
					for( var compnent in compnents ) {
						if( typo_obj[typo_index].name && typo_obj[typo_index].name.indexOf(compnent) !== -1 && typo_obj[typo_index].value ) {
							var css_value = typo_obj[typo_index].value;
							if( compnent === 'size' || compnent === 'letter-spacing') {
								css_value += 'px';
							}
							if( compnent === 'family' ) {
								css_value = capitalize( replaceAll(css_value, '-', ' ') );
								var font_name = replaceAll( css_value, ' ', '+');
								var font_link = 'https://fonts.googleapis.com/css?family=' + font_name;
								if( $('link#' + typo_obj[typo_index].value ).length === 0 ) {
									$('head').append($('<link rel="stylesheet" id="' + typo_obj[typo_index].value + '" type="text/css" href="' + font_link + '" />'));
								}
							}
							css += compnents[compnent] + ' : ' + css_value + '; ';
							continue;
						}
					}
				}
				if( css ) {
					css = customizer_data.typography_selectors[get_setting] + '{ ' + css + ' } ';
					$('head').append($('<style type="text/css" id="' + get_setting + '">' + css + '</style>'));
				}
			});
		});
	}
} )( jQuery );