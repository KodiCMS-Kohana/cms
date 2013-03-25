$(function() {
	$(document).on('keydown', function(e) {
		if(e.ctrlKey){
			var $blocks = $('.widget-block');
			$blocks.toggleClass('visible');
		}
	});
});