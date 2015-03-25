cms.ui.add('update.check', function() {
	Api.get('update.check', {}, function(response) {
			if(!response.response.version) {
				cms.notifications.add(
					__('You have the latest version of :cms_name', {':cms_name': 'KodiCMS'}),
					null,
					__('Update'),
					'text-muted',
					'cloud-download bg-default',
					false
				);
			} else {
				cms.notifications.add(
					__('There is a new :cms_name version (:version)', {':cms_name': 'KodiCMS', ':version': response.response}),
					null,
					__('Update'),
					'text-success',
					'cloud-download bg-info'
				);
			}
			
			if(response.response.database > 0) {
				cms.notifications.add(
					__('There are changes to the database schema'),
					null,
					__('Update'),
					'text-warning',
					'exclamation-triangle bg-warning'
				);
			}
	}, false);
});