(function($) {
	"use strict";
	$(document).ready( function() {
		$.initialize('.fw-link-spacing-fields', function() {
	      var field_id = $(this).data('id');
	      var btn	   = $(this);
	      $(this).on('click', function() {
	      	$(this).toggleClass('fw-link-fields');
	      	if( $('input[data-id="' + field_id + '"].last-edited').length > 0 ) {
	      		var value = $('input[data-id="' + field_id + '"].last-edited').first().val();
	      	} else {
	      		var value = $('input[data-id="' + field_id + '"]').first().val();
	      	}
	      	$('input[data-id="' + field_id + '"]').val( value );
	      });
	      $('input[data-id="' + field_id + '"]').on('change keypress', function() {
	      	var value = $(this).val();
	      	$('input[data-id="' + field_id + '"]').removeClass('last-edited');
	      	$(this).addClass('last-edited');
	      	if( btn.hasClass('fw-link-fields') === true ) {
	      		$('input[data-id="' + field_id + '"]').val( value );
	      	}
	      });
	 	});
	})
})(jQuery);
