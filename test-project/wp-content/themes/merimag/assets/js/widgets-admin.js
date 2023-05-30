(function($) {
	let timeoutAddId;
	
	$(document).on('widget-added', function(ev, $widget){
		clearTimeout(timeoutAddId);
		timeoutAddId = setTimeout(function(){ // wait a few milliseconds for html replace to finish
			fwEvents.trigger('fw:options:init', { $elements: $widget });
		}, 3000);
	});

	$(document).on('widget-added', function(ev, $widget){
    	$widget.addClass('just-added-now');

	});
	
	$(document).on('widget-updated', function(ev, $widget){
    	fwEvents.trigger('fw:options:init', { $elements: $widget });
	});
	
	var count = 0;
	$( document ).ajaxStop( function() {
	    var $saveBtns = $('.just-added-now').find('.widget-control-save');
	    if (count < $saveBtns.length) {
	        $saveBtns.each( function( index, value ){
	            $(value).trigger('click');
	            $('.just-added-now').removeClass('just-added-now');
	            count++;
	        });
	    } else {
	        count = 0;
	        return;
	    }
	});
})(jQuery);

