cms.ui.add('update.check', function() {
	Api.get('update.check_version', {}, function(response) {
		if(!response.response)
			cms.notifications.add(
				__('You have the latest version of :cms_name', {':cms_name': 'KodiCMS'}),
				null,
				__('Update'),
				'text-muted',
				'cloud-download bg-default',
				false
			);
		else
			cms.notifications.add(
				__('There is a new :cms_name version (:version)', {':cms_name': 'KodiCMS', ':version': response.response}),
				null,
				__('Update'),
				'text-success',
				'cloud-download bg-info'
			);

	}, false);
});