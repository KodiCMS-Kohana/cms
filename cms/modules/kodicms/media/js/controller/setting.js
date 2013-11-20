cms.init.add('setting_index', function () {
	$('.widget').on('click', '#clear-cache', function() {
		Api.get('cache.clear', {}, function() {

		});
		return false;
	});
});