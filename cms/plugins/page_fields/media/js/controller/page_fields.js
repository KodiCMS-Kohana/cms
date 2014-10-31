cms.init.add('page_edit', function() {
	$('.spoiler-page-fields').on('click', '.btn-add', function() {
		var container = $(this).closest('.page-field');
		var fields = container.find('input');
		Api.put('page-field', {field: fields.serializeObject(), page_id: PAGE_ID}, function(response) {
			if(response.response) {
				$('.spoiler-page-fields').append(response.response);
				cms.ui.init('filemanager');
				fields.val('');
			}
		});
		return false;
	});

	$('.spoiler-page-fields').on('click', '.btn-remove', function() {
		var field = $(this).closest('.page-field');
		Api.delete('page-field', {field_id: field.data('id')}, function(response) {
			field.remove();
		});
		return false;
	});

	$('.page-field').on('change', 'input[name=value]', function() {
		var $container = $(this).closest('.page-field');
		if($container.data('id'))
			updateField(container.data('id'), $(this).val());
	});

	function updateField(field_id, value) {
		Api.post('page-field', {field_id: field_id, value: value}, function(response) {});
	}

	$('#select-page-field').on('click', function() {
		var $container = $(this).closest('.well'),
			$select_container = $('.select-field-container');

		if($container.find('.page-field .select-field-container').length > 0) {
			$select_container = $container.find('.page-field .select-field-container');
		} else {
			$select_container = $select_container.clone();
		}
		
		var $select = $("#select-page-field-container", $select_container);
			
		if($container.hasClass('select-init')) {
			$(this).text(__('Show field select'));
			
			$container
				.removeClass('select-init')
				.find('.system-field')
				.show()
				.find('input')
				.removeAttr('disabled');

			$select_container.remove();
				
			$select
				.select2("destroy")
				.attr('disabled', 'disabled');

		} else {
			$select
				.removeAttr('disabled')
				.select2({
					placeholder: __("Select field"),
					minimumInputLength: 0,
					ajax: {
						url: Api.build_url('page-field.all'),
						dataType: 'json',
						results: function (data, page) {
							return {results: data.response};
						}
					}
				});
			
			$(this).text(__('Hide field select'));
			
			$container
				.addClass('select-init')
				.find('.system-field')
				.hide()
				.find('input')
				.attr('disabled', 'disabled');
				
			$select_container
				.prependTo($('.page-field', $container))
				.show();
		}
		
		return false;
	});
});