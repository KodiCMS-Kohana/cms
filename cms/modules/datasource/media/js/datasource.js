cms.init.add(['datasources_data_index'], function() {
	// Open nav on mobile
	$('.mail-nav .navigation li.active a').click(function () {
		$('.mail-nav .navigation').toggleClass('open');
		return false;
	});

	// Fix navigation if main menu is fixed
	if ($('body').hasClass('main-menu-fixed')) {
		$('.mail-nav').addClass('fixed');
	}
	
	$(document).on('click', '.headline tr[data-id]', function(event) {
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).trigger('click');
		}
	});

	$(document).on('change', '.headline [data-id] .doc-checkbox', function() {
		checkbox_check();
	});
	
	checkbox_check();
	
	$('.checkbox-control .action[data-action]').on('click', function(e) {
		var action = $(this).data('action');
		var sibling = ':checked';
		if(action == 'check_all')
			sibling = ':not(:checked)';
		
		$('.headline [data-id] .doc-checkbox' + sibling).trigger('click');
		
		e.preventDefault();
	});
	
	$('.headline-actions .doc-actions .action').on('click', function() {
		var action = $(this).data('action');

		var data = $('.headline [data-id] .doc-checkbox:checked')
			.serialize();
	
		var page = $.query.get('page');
		data = $.query.parseNew(data)
			.set('page', page)
			.set('ds_id', DS_ID)
			.toString().substring(1);

		if (data.length == 0 || !action) {
			return;
		}
		
		if (!confirm(__('Are you sure?')))
			return;
		
		Api.post('/datasource-document.' + action, data, function(response) {
			update_headline();
		});
	});
});

function update_headline() {
	var data = {
		page: $.query.get('page'),
		ds_id: DS_ID
	}
	Api.get('/datasource-document.headline', data, function(response) {
		if(response.response) {
			$('.headline').html(response.response);
			cms.ui.init('icon');
		}
	});
}

function checkbox_check() {
	var $checkboxes = $('.headline [data-id] .doc-checkbox');
	var $total_checked = $checkboxes.filter(':checked').length;
	
	if($total_checked > 0)
		$('.headline-actions .doc-actions .action').removeClass('disabled');
	else
		$('.headline-actions .doc-actions .action').addClass('disabled');
	
	$checkboxes.each(function() {
		if (!$(this).prop('checked')) {
			$(this).closest('[data-id]').removeClass("info");
		} else {
			$(this).closest('[data-id]').addClass("info");
		}
	});
}