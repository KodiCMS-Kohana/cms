cms.ui.add('messages.check', function() {
	function count_new() {
		Api.get('user-messages.count_new', {uid: USER_ID}, function(response){
			if(response.response)
				$('.nav-messages .counter').text(parseInt(response.response));
		});

		setTimeout(count_new, 30000);
	}

	count_new();
});