cms.init.add(['datasources_data_index'], function () {
	
	$('#headline table tbody tr').on('click', function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });
	
	$('#headline table tbody .doc-checkbox').on('change', function() {
		if(!$(this).prop('checked')){
			$(this).closest('tr').removeClass("warning");
		} else {
			$(this).closest('tr').addClass("warning");
		}
	});
	
	$('#apply-doc-action').click(function() {
		var actions = $('#doc-actions');
		var action = actions.val();
		var section = actions.data('section');
		
		if( ! section ) {
			cms.error(__('Section not selected'));
			return;
		}
		
		var data = checkboxes.filter(':checked').serialize();
		
		if(action == 0) {
			cms.error(__('You need to select action'));
			return;
		}
		
		if(data.length == 0) {
			return;
		}
		
		if( ! confirm(__('Are you sure?')) )
			return;

		Api.post('/datasource/' + section + '-document.' + action, data, function(response) {
			if(response.code == 200)
				window.location = '';
		})
	})
});