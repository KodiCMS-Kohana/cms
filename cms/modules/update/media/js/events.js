cms.ui.add('update.check', function() {
	Api.get('update.check_version', {}, function(response) {
		if(!response.response) return;

		cms.navigation.counter.add('/backend/update', 1);

	}, false);
})