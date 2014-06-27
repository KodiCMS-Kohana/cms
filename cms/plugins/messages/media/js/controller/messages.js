cms.init.add('messages_add', function(){
	$('#messageTo').select2({
		placeholder: __("Type first 2 chars"),
		minimumInputLength: 2,
		ajax: {
			url: Api.build_url('users.like'),
			data: function(query, pageNumber, context) {
				return {
					key: query, 
					sin: 'username,email,name', 
					fields:'username'
				}
			},
			dataType: 'json',
			results: function (resp, page) {
				var users = [];
				if(resp.response) {
					for(i in resp.response) {
						users.push({
							id: resp.response[i]['id'],
							text: resp.response[i]['username']
						});
					}
				}

				return {results: users};
			}
		}
	});
	
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.widget-title').outerHeight(true) + $('.form-actions').outerHeight(true) + 77;
		return conentH - h;
	}
	
	$('#message-content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('message-content', 'changeHeight', calculateEditorHeight());
	});
	
	$(window).resize(function() {
		$('#message-content').trigger('filter:switch:on')
	});
	
});

function get_messages() {
	Api.get('user-messages.list', {
		uid: USER_ID, 
		fields: 'author,from_user_id,title,is_read,created_on,text',
		pid: MESSAGE_ID
	}, function(response) {
		if(!response.response) return;
		
		var $msg_cont = $('#messages').empty();
		for(msg in response.response) {
			$msg_cont.append($(response.response[msg]));
		}
		
		cms.navigation.counter.add('messages', $('.new-message', $msg_cont).length)
		
	}, false)
	
	setTimeout(get_messages, 10000);
}

cms.init.add('messages_view', function(){
	get_messages();
});

cms.init.add('messages_index', function(){
	$('.btn-remove').click(function() {
		var $cont = $(this).parent().parent();
		var $message_id = $cont.data('id');
		
		$.post(Api.build_url('user-messages.delete'), { id: $message_id, uid: USER_ID }, function( resp ) {
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