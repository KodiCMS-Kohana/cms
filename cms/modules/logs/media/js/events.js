cms.ui.add('logs.check', function() {
	Api.get('log.get', {interval: 'last-week', limit: 50, level: '0,1,2,3,4'}, function(response){
		if(response.response) {
			var $cont = $('#main-navbar-notifications');
			var length = 0;
			for(i in response.response) {
				var $row = response.response[i];
				var $notification = $('<div class="notification" />');
				
				$('<div class="notification-description" />').html($row.message).appendTo($notification);
//				$('<br /><div class="notification-ago" />').html($row.created_on).appendTo($notification);
				$('<div class="notification-icon fa fa-exclamation-triangle bg-danger" />').appendTo($notification);
				$notification.appendTo($cont);
				
				length++;
			}
			
			$('#main-navbar-notifications').slimScroll({ height: 250 });

			$('.nav-logs .counter').text(parseInt(length));
		}
	});
});