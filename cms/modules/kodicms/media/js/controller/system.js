cms.init.add('system_settings', function () {
	$('.widget').on('click', '#clear-cache', function() {
		Api.get('cache.clear', {}, function() {

		});
		return false;
	});
});

cms.init.add('system_information', function () {
	function calculateEditorHeight() {
		var conentH = cms.calculateContentHeight();
		var h = 130
		
		return conentH - h;
	}
	
	$('#phpinfo').height(calculateEditorHeight());
	$(window).resize(function() {
		$('#phpinfo').height(calculateEditorHeight())
	});
	
	console.log();
});