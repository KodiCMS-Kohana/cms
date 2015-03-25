cms.init.add('messages_add', function(){
	$('#messageTo').select2({
		placeholder: __("Type first 2 chars"),
		minimumInputLength: 2,
		multiple: true,
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
		var h = $('.panel-heading').outerHeight(true) + $('.form-actions').outerHeight(true) + 77;
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
		
		$('.nav-messages .counter').text(parseInt($msg_cont).length);		
	}, false)
	
	setTimeout(get_messages, 10000);
}

cms.init.add('messages_view', function(){
	get_messages();
	
	$('.btn-remove').on('click', function() {
		$.post(Api.build_url('user-messages.delete'), {id: MESSAGE_ID, uid: USER_ID }, function( resp ) {
			if(resp.response) {
				window.location = $('.btn-go-back').attr('href');
			}
		}, 'json');
	});
});

cms.init.add('messages_index', function(){
	$('.btn-remove').on('click', function(e) {
		$('.mail-list .mail-item .select-checkbox:checked').each(function() {
			var $cont = $(this).closest('.mail-item');
			var $message_id = $cont.data('id');
			
			$.post(Api.build_url('user-messages.delete'), {id: $message_id, uid: USER_ID }, function( resp ) {
				if(resp.response) {
					$cont.remove();
					
					if($('.mail-list .mail-item').length == 0) window.location = '';
				}
			}, 'json');
		});
		
		e.preventDefault();
	});
	
	$('body').on('click', '.m-star a', function(e) {
		var $li = $(this).closest('.mail-item');
		var $id = $li.data('id');
		var $status = $li.hasClass('starred') ? 0 : 1;
		
		$.post(Api.build_url('user-messages.starred'), {id: $id, uid: USER_ID, status: $status }, function( resp ) {
			if(resp.response == 1)
				$li.addClass('starred');
			else
				$li.removeClass('starred');
		}, 'json');
		
		e.preventDefault();
	});
	
	$('.btn-check-new').on('click', function(e) {
		Api.get('user-messages.get', {
			uid: USER_ID, 
			fields: 'author,title,is_read,created_on,from_user_id,is_starred',
			use_template: true
		}, function(response) {
			if(!response.response) return;

			var $msg_cont = $('#messages-container').html(response.response);
		}, false);
		
		e.preventDefault();
	});
});