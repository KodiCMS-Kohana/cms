cms.init.add('page_index', function() {
	var cache_key = 'expanded_pages';
	
	var expanded_pages = KodiCMS.getStoredValue(cache_key, true);
	if(!expanded_pages) expanded_pages = [];
	else expanded_pages = decodeURIComponent(expanded_pages).split(',');

	expanded_pages = _.map(expanded_pages, function(num) { return parseInt(num); });

	var expandedPagesAdd = function(page_id) {
		expanded_pages.push(page_id);
		KodiCMS.storeValue(cache_key, _.uniq(expanded_pages).join(','), true);
	};


	var expandedPagesRemove = function(page_id) {
		expanded_pages = _.filter(expanded_pages, function(num) {
			return num != page_id;
		});
		KodiCMS.storeValue(cache_key, _.uniq(expanded_pages).join(','), true);
	};

	$('#page-tree-list').on('click', '.item-expander', function() {
		var expander = $(this),
			li = expander.closest('li'),
			parent_id = li.data('id');
		
		if ( ! li.hasClass('item-expanded')) {
			var level = parseInt(li.parent().data('level'));
			var success_handler = function(html) {
				li.append(html);
				expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				li.addClass('item-expanded');

				expandedPagesAdd(parent_id);
			};

			// When ajax error of updating information about page position
			var error_handler = function(html) {
				cms.messages.error('Ajax: Sub pages not loaded!');
				cms.loader.hide();
			};

			// Sending information about page position to frog
			$.ajax({
				url: SITE_URL + ADMIN_DIR_NAME + '/page/children/',
				dataType: 'html',
				data: {
					parent_id: parent_id,
					level: level
				},
				success: success_handler,
				error: error_handler
			});
		}
		else {
			if (expander.hasClass('item-expander-expand')) {
				expander
					.removeClass('item-expander-expand')
					.removeClass('fa-minus')
					.addClass('fa-plus');

				li.find('>ul').hide();

				expandedPagesRemove(parent_id);
			}
			else {
				expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				li.find('>ul').show();

				expandedPagesAdd(parent_id);
			}
		}
	});


	// Reordering
	$('#pageMapReorderButton').on('click', function() {
		var self = $(this);

		if (self.hasClass('btn-inverse')) {
			$('#page-search-list').empty().hide();
			$('#page-tree-header').show();
			self.removeClass('btn-inverse');

			$.get(SITE_URL + ADMIN_DIR_NAME + '/page/children', {parent_id: 1, level: 0}, function(resp) {
				$('#page-tree-list')
					.find('ul')
					.remove();

				$('#page-tree-list')
					.show()
					.find('li')
					.append(resp);

				cms.ui.init('icon');
			}, 'html');

		} else {
			self.addClass('btn-inverse');
			$('#page-tree-list').hide();
			$('#page-tree-header').hide();

			Api.get('pages.sort', {}, function(response) {
				$('#page-search-list')
					.html(response.response)
					.show();

				$('#nestable').nestable({
					group: 1,
					maxDepth: 10,
					listNodeName: 'ul',
					listClass: 'dd-list list-unstyled',
				}).on('change', function(e, el) {
					var list = e.length ? e : $(e.target);
					var pages = list.nestable('serialize');
					if (!pages.length)
						return false;

					Api.post('pages.sort', {'pages': pages});
				});
			}, self.parent());
		}
	});

	$('.form-search').on('submit', function(event) {
		var form = $(this);

		if ($('#page-seacrh-input').val() !== '') {
			$('#page-tree-list').hide();

			Api.get('pages.search', form.serialize(), function(resp) {
				$('#page-search-list').html(resp.response);
			});

		} else {
			$('#page-tree-list').show();
			$('#page-search-list').hide();
		}

		return false;
	});
	
	var editable_status = {
		type: 'select2',
		title: __('Page status'),
		send: 'always',
		highlight: false,
		ajaxOptions: {
			dataType: 'json'
		},
		params: function(params) {
			params.page_id = $(this).closest('li').data('id');
			return params;
		},
		url: '/api-pages.change_status',
		source: PAGE_STATUSES,
		select2: {
			width: 200,
			placeholder: __('Page status')
		},
		success: function(response, newValue) {
			if(response.response) {
				$(this)
					.replaceWith($(response.response).editable(editable_status));
			}
		}
	};
	
	$('.editable-status').editable(editable_status);
});


cms.init.add('page_add', function() {
	$('body').on('keyup', 'input[name="page[title]"]', function() {
		$('input[name="page[breadcrumb]"]')
			.add('input[name="page[meta_title]"]')
			.val($(this).val());
	});
	
	$('.panel-toggler').click();
});

cms.init.add(['page_add', 'page_edit'], function() {
	$('body').on('change', 'select[name="page[status_id]"]', function() {
		show_password_field($(this).val());
	});
	
	$('#page-meta-panel').on('click', ':input', function() {
		var $fields = {};
		var $array = ['breadcrumb', 'meta_title', 'meta_keywords', 'meta_description'];
		for(i in $array) {
			$fields[$array[i]] = $('#page_' + $array[i]).val();
		}
	
		Api.get('pages.parse_meta', {
			page_id: PAGE_ID,
			fields: $fields
		}, function(response) {
			if(response.response) {
				for(field in response.response) {
					$('#field-' + field + ' .help-block').text(response.response[field]);
				}
			}
		});
	});

	$('input[name="page[use_redirect]"]').on('change', function() {
		show_redirect_field($(this))
	});

	show_redirect_field($('input[name="page[use_redirect]"]'));
	show_password_field($('select[name="page[status_id]"]').val());

	function show_redirect_field(input) {
		var cont = $('#redirect-to-container'),
			meta_cont = $('#page-meta-panel-li');

		if (input.is(':checked')) {
			cont.show();
			meta_cont.hide();
		} else {
			cont.hide();
			meta_cont.show();
		}
	}

	function show_password_field(val) {
		var select = $('select[name="page[status_id]"]');

		if (val == 200) {
			select.parent().addClass('well well-small').find('.password-container').removeClass('hidden');
		} else {
			select.parent().removeClass('well well-small')
				.find('.password-container').addClass('hidden')
				.find('input').val('');
		}
	}
});