cms.init.add('messages_add', function(){
	$('input[name="to"]').typeahead({
		source: function(query, process) {
			$.get('/ajax-messages-users_list', {username: query}, function(resp) {
				return process(resp.data);
			}, 'json');
		}
	});
});