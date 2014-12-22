cms.init.add(['datasources_data_index'], function() {
	init_section_folders();
	
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
	
	$('.form-search').on('click', '.btn', function(e) {
		headline_search(e, $(this).closest('.form-search').find('.form-control'));
	});
	
	$('.form-search').on('keypress', '.form-control', function(e) {
		if(e.which == 13) {
			headline_search(e, $(this));
		}
	});
});

function headline_search(e, $input) {
	e.preventDefault();
	
	var $fields = $('.form-search .form-control').serializeObject();
	
	update_headline($fields);
}

function update_headline(keyword) {
	var data = {
		page: $.query.get('page'),
		ds_id: DS_ID
	};

	Api.get('/datasource-document.headline', _.extend(data, keyword), function(response) {
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

function init_section_folders() {
	$('.page-mail').on('click', '.create-folder-button', function() {
		$('#folder-modal').modal();
	});
	
	$('#folder-modal').on('submit', 'form', function(e) {
		cms.clear_error();
		var field = $(this).find('input[name="folder-name"]');

		if(field.val()) {
			Api.put('/datasource-data.folder', {
				name: field.val()
			}, function(resp) {
				if(resp.status) reload_menu();
				field.val('');
			});

			$('#folder-modal').modal('hide')
		} else {
			cms.error_field(field, __('Pleas set folder name'));
		}
		e.preventDefault();
	});
	
	$('.page-mail').on('click', '.mail-nav-header', function() {
		var $sections = $(this).next('.sections');
		if($('li', $sections).length == 0) return;

		$sections.toggle();
	
		var data = {};
		$('.folder-container .mail-nav-header').each(function() {
			data[$(this).data('id')] = !$(this).next('.sections').is(':hidden');
		});
		
		if(!_.isEmpty(data))
			Api.post('user-meta', {key: 'datasource_folders', value: data});
	});
	
	$('.page-mail').on('click', '.remove-folder', function() {
		if (!confirm(__('Are you sure?')))
			return;

		Api.delete('/datasource-data.folder', {
			id: $(this).closest('.mail-nav-header').data('id')
		}, function(response) {
			reload_menu();
		});
	});
	
	init_sections_sortable();
}

function init_sections_sortable() {
	if($('.folders-list').size() == 0) {
		$('.section-draggable').remove();
		return;
	}

	$(".page-mail .mail-nav .sections li").draggable({
		handle: ".section-draggable",
		axis: "y",
		revert: "invalid"
	});
	
	$(".folder-container .sections li[data-id="+DS_ID+"]")
		.parent()
		.show();
	
	$(".folder-container")
		.add('.sections-list')
		.droppable({
			hoverClass: "dropable-hover",
			drop: function (event, ui) {
				var self = $(this);
				if(self.hasClass('sections-list')) {
					var folder_id = 0;
				} else {
					var folder_id = self.find('.mail-nav-header').data('id');
				}

				Api.post('/datasource-data.menu', {
					ds_id: ui.draggable.data('id'),
					folder_id: parseInt(!folder_id ? 0 : folder_id)
				}, function(response) {
					if(response.status) reload_menu();
				});

				ui.draggable.remove();
			}
		});
}

function reload_menu() {
	Api.get('/datasource-data.menu', {ds_id: DS_ID}, function(response) {
		$('.page-mail .mail-nav').html(response.response);
		cms.ui.init('icon');
		init_sections_sortable();
	});
}