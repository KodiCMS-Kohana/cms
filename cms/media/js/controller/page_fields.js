cms.init.add('page_edit', function() {
	$('.spoiler-page-fields').on('click', '.btn-add', function() {
		var fields = $(this).parent().find('input');
		Api.put('pagefield', {field: fields.serializeObject(), page_id: PAGE_ID}, function(response) {
			if(response.response)
			{
				$('.spoiler-page-fields').append(response.response);
				fields.val('');
			}
		});

		return false;
	});
	
	$('.spoiler-page-fields').on('click', '.btn-remove', function() {
		var field = $(this).parent();
		Api.delete('pagefield', {field_id: field.data('id')}, function(response) {
			field.remove();
		});
		return false;
	});
	
	$('.page-field').on('change', 'input[name=value]', function() {
		if($(this).parent().data('id'))
			updateField($(this));
	});
	
	function updateField(field) {
		Api.post('pagefield', {field_id: field.parent().data('id'), value: field.val()}, function(response) {});
	}
});