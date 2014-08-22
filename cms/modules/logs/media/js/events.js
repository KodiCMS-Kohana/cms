cms.ui.add('logs.check', function() {
	Api.get('log.get', {interval: 'last-week', limit: 50, level: '0,1,2,3,4'}, function(response){
		if(response.response) {
			var $cont = $('#main-navbar-notifications');
			for(i in response.response) {
				var $row = response.response[i];
				cms.notifications.add($row.message, $row.created_on, $row.level, 'text-danger', 'exclamation-triangle text-danger');				
			}
		}
	});
});