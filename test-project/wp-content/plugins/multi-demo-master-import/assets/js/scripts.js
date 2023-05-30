(function($) {
	"use strict";
	$(document).ready(function() {
		
		var xH, cH;
		$('.wpmdm-import-demo-image img.wpmdm-import-image-scroll').hover(function(e) {
			e.stopPropagation();
			xH = $(this).css('height');
			xH = parseInt(xH);
			cH = $(this).parents('.wpmdm-import-demo-image').css('height');
			cH = parseInt(cH);
			xH = xH - cH;
			xH = "-" + xH + "px";
			$(this).stop().animate({"top":xH}, { duration: 3000, queue: false});
		}, function() {
			$(this).animate({"top":'0px'}, { duration: 3000, queue: false});
		});
		$(document).on('click', '.wpmdm-import-button', function(e) {
			e.preventDefault();
			var elm = $(this);
			var id = $(this).data( 'id' );
			var import_types = $('#wpmdm-import-form-' + $(this).data('id') + ' :input').serialize();
			if( import_types ) {
				$.ajax({
					type:'POST',
					url: wpmdm_import.ajax,
					data: {
						action: 'wpmdm_import_ajax_import',
						nonce : wpmdm_import.nonce,
						import_types: import_types,
						id: id,
					},
					beforeSend: function( data ) {

						$(elm).parents('.wpmdm-import-demo-item').find('.wpmdm-import-status').html('<span class="wpmdm-loading"><i class="fa fa-spin fa-circle-o-notch"></i></span>');
						wpmdm_import_options_UI.add_message('progress', elm.data('message-progress'));
					},
					success: function( data ) {
						wpmdm_import_options_UI.add_message('success', elm.data('message-success') );
						elm.parents('.wpmdm-import-form').find( '.wpmdm-import-status' ).html( data);
					},
					error: function(  jqXHR, textStatus, errorThrown) {
	                    wpmdm_import_options_UI.add_message('error', elm.data( 'message-error'));

	                },
	                complete(data) {
	                	console.log(data);
	                }
				});
			} else {
	            wpmdm_import_options_UI.add_message('error', elm.data( 'message-empty'));

			}
		})		
	});



})(jQuery);