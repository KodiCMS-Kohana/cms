cms.init.add('update_index', function() {
	Api.get('update.check_files', {}, function(resp) {
		$('#files').html(resp.response);
	}, $('#files'));
});