cms.init.add('messages_add', function(){
	$('input[name="to"]').typeahead({
		source: function(query, process) {
			$.get('/api/users.like', {key: query, sin: 'username,email,name', fields:'username'}, function(resp) {
				users = [];
				
				if(resp.response) {
					
					for(i in resp.response) {
						users.push(resp.response[i]['username']);
					}
				}
				return process(users);
			}, 'json');
		}
	});
});

cms.init.add('messages_index', function(){
	$('.btn-remove').click(function() {
		var $cont = $(this).parent().parent();
		var $message_id = $cont.data('id');
		
		$.post('/api/messages.delete', { id: $message_id, uid: USER_ID }, function( resp ) {
			if(resp.response) {
				$cont.remove();
				
				if($('#MessagesList tbody tr').length == 0 )
				{
					window.location = CURRENT_URL;
				}
			}
		}, 'json');
	})
});