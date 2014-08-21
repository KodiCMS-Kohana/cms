cms.init.add('page_index', function () {
	// Read coockie of expanded pages
	var matches = document.cookie.match(/expanded_rows=(.+?);/);
	var expanded_pages = matches ? decodeURIComponent(matches[1]).split(',') : [];
	var arr = [];

	for (var i = 0; i < expanded_pages.length; i++) {
		if (typeof(parseInt(expanded_pages[i])) == 'number')
			arr[i] = parseInt(expanded_pages[i]);
	}

	expanded_pages = arr;
	var expandedPagesAdd = function (page_id) {
		expanded_pages.push(page_id);

		document.cookie = ['expanded_rows', '=', encodeURIComponent(jQuery.unique(expanded_pages).join(',')), '; path=', window.location.pathname].join('');
	};

	var expandedPagesRemove = function (page_id) {
		expanded_pages = jQuery.grep(expanded_pages, function (value, i) {
			return value != page_id;
		});

		document.cookie = ['expanded_rows', '=', encodeURIComponent(jQuery.unique(expanded_pages).join(',')), '; path=', window.location.pathname].join('');
	};

	$('#page-tree-list').on('click', '.item-expander', function () {
		var li = $(this).parent().parent().parent().parent();
		var parent_id = li.data('id');

		var expander = $(this);

		if (!li.hasClass('item-expanded')) {
			var level = parseInt(li.parent().data('level'));
			var success_handler = function (html) {
				li.append(html);
				expander
					.addClass('item-expander-expand')
					.removeClass('icon-plus')
					.addClass('icon-minus');

				li.addClass('item-expanded');

				expandedPagesAdd(parent_id);

				cms.loader.hide();
			};

			// When ajax error of updating information about page position
			var error_handler = function (html) {
				cms.error('Ajax: Sub pages not loaded!', html);
				cms.loader.hide();
			};

			cms.loader.show($(this).parent());

			// Sending information about page position to frog
			$.ajax({
				url: SITE_URL + ADMIN_DIR_NAME + '/page/children/',
				dataType:'html',
				data:{
					parent_id: parent_id,
					level: level
				},
				success:success_handler,
				error:error_handler
			});
		}
		else {
			if (expander.hasClass('item-expander-expand')) {
				expander
					.removeClass('item-expander-expand')
					.removeClass('fa-minus')
					.addClass('fa-plus');

				li.find('> ul').hide();

				expandedPagesRemove(parent_id);
			}
			else {
				expander
					.addClass('item-expander-expand')
					.removeClass('fa-plus')
					.addClass('fa-minus');

				li.find('> ul').show();

				expandedPagesAdd(parent_id);
			}
		}
	});


	// Reordering
	$('#pageMapReorderButton').on('click', function () {
		var self = $(this);
		
		if(self.hasClass('btn-inverse')) {
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
					var list   = e.length ? e : $(e.target);
					var pages = list.nestable('serialize');
					if(!pages.length) return false;
					
					Api.post('pages.sort', {'pages': pages});
				});
			}, self.parent());
		}
	});

	$('#page-tree .form-search')
		.on('submit', function (event) {
			var form = $(this);

			if ($('.search-query', this).val() !== '') {
				$('#page-tree-list').hide();
				
				Api.get('pages.search', form.serialize(), function(resp) {
					$('#page-search-list')
						.html(resp.response);
				});
		
			} else {
				$('#page-tree-list').show();
				$('#page-search-list').hide();
			}

			return false;
		});
});


cms.init.add('page_add', function () {
	$('body').on('keyup', 'input[name="page[title]"]', function () {
		$('input[name="page[breadcrumb]"]')
			.add('input[name="page[meta_title]"]')
			.val($(this).val());
	});
});

cms.init.add(['page_add', 'page_edit'], function () {
	$('body').on('change', 'select[name="page[status_id]"]', function () {
		show_password_field($(this).val());
	});
	
	$('input[name="page[use_redirect]"]').on('change', function() {
		show_redirect_field($(this))
	});
	
	show_redirect_field($('input[name="page[use_redirect]"]'));
	show_password_field($('select[name="page[status_id]"]').val());
	
	function show_redirect_field(input) {
		var cont = $('#redirect-to-container'),
			meta_cont = $('#page-meta-panel-li');
		if(input.is(':checked')) {
			cont.show();
			meta_cont.hide();
		} else {
			cont.hide();
			meta_cont.show();
		}	
	}
	
	function show_password_field(val) {
		var select = $('select[name="page[status_id]"]');
		
		if(val == 200){
			select.parent().addClass('well well-small').find('.password-container').removeClass('hidden');
		} else {
			select.parent().removeClass('well well-small')
				.find('.password-container').addClass('hidden')
				.find('input').val('');
		}
	}
});