cms.init.add('page_index', function () {
	// Read coockie of expanded pages
	var matches = document.cookie.match(/expanded_rows=(.+?);/);
	var expanded_pages = matches ? matches[1].split(',') : [];

	var arr = [];

	for (var i = 0; i < expanded_pages.length; i++) {
		if (typeof(parseInt(expanded_pages[i])) == 'number')
			arr[i] = parseInt(expanded_pages[i]);
	}

	expanded_pages = arr;


	var expandedPagesAdd = function (page_id) {
		expanded_pages.push(page_id);

		document.cookie = "expanded_rows=" + jQuery.unique(expanded_pages).join(',');
	};

	var expandedPagesRemove = function (page_id) {
		expanded_pages = jQuery.grep(expanded_pages, function (value, i) {
			return value != page_id;
		});

		document.cookie = "expanded_rows=" + jQuery.unique(expanded_pages).join(',');
	}


	$('#pageMapItems').on('click', ' .item-expander', function () {
		var li = $(this).parent().parent().parent().parent();
		var parent_id = li.data('id');

		var expander = $(this);

		if (!li.hasClass('item-expanded')) {
			var level = parseInt(li.parent().data('level'));
			//alert(level);
			// When information of page reordering updated
			var success_handler = function (html) {
				li.append(html);

				//cms.cssZebraItems('.map-items .item');

				//li.find('ul .page-expander').click(frogPages.expanderClick);

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
			}

			cms.loader.show();

			// Sending information about page position to frog
			jQuery.ajax({
				// options
				url:SITE_URL + ADMIN_DIR_NAME + '/page/children/',
				dataType:'html',
				data:{
					parent_id:parent_id,
					level:level
				},

				// events
				success:success_handler,
				error:error_handler
			});
		}
		else {
			if (expander.hasClass('item-expander-expand')) {
				expander
					.removeClass('item-expander-expand')
					.removeClass('icon-minus')
					.addClass('icon-plus');

				li.find('> ul').hide();

				expandedPagesRemove(parent_id);
			}
			else {
				expander
					.addClass('item-expander-expand')
					.removeClass('icon-plus')
					.addClass('icon-minus');

				li.find('> ul').show();

				expandedPagesAdd(parent_id);
			}
		}
	});


	// Reordering
	$('#pageMapReorderButton').click(function () {
		var self = $(this);
		var $pageMapUl = $('#pageMapItems > li > ul');

		if (self.hasClass('btn-inverse')) {
			self.removeClass('btn-inverse');

			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy')
				.find('li')
				.draggable('destroy');

			return false;
		}

		if (!$pageMapUl.hasClass('map-drag')) {

			var dragStart_handler = function (event, ui) {
				ui.item.find('ul').hide();
			};

			var dragOver_handler = function (event, ui) {
				var level = parseInt(ui.placeholder.parent().data('level'));
				$('.item .title', ui.item).css('padding-left', (35 * level) + 'px');
			};

			var dragStopped_handler = function (event, ui) {
				ui.item.find('ul').show();

				var ul = ui.item.parent();
				var parent_id = parseInt(ul.parent().data('id'));

				var li = ul.children('li');

				var pages_ids = [];

				li.each(function(i){
					var child_id = $(this).data('id');
					if (child_id !== undefined)
						pages_ids.push(child_id);
				});

				var success_handler = function () {
					cms.loader.hide();
				};

				var error_handler = function () {
					cms.error('Ajax return error (pages reordering).');
					cms.loader.hide();
				};

				cms.loader.show();

				// Save reordered positons
				jQuery.ajax({
					// options
					url:SITE_URL + ADMIN_DIR_NAME + '/page/reorder/',
					type:'post',

					data:{
						parent_id:parent_id,
						pages:pages_ids
					},

					// events
					success:success_handler,
					error:error_handler
				});
			};

			// Begin sorting
			$pageMapUl
				.addClass('map-drag')
				.sortable({
					// options
					axis:'y',
					items:'li',
					connectWith:'ul',
					placeholder: 'map-placeholder',
					grid: [5, 8],
					cursor: 'move',

					// events
					start: dragStart_handler,
					over: dragOver_handler,
					stop: dragStopped_handler
				});

			self.addClass('btn-inverse');
		}
		else {
			$pageMapUl
				.removeClass('map-drag')
				.sortable('destroy');

			self.removeClass('btn-inverse');
		}
	});

	// Search
	var search = function (form) {
		var success_handler = function (data) {
			$('#pageMapSearchItems')
				.removeClass('map-wait')
				.html(data);
		};

		var error_handler = function () {
			cms.error('Search: Ajax return error.');
		};

		$('#pageMapItems').hide();
		$('#pageMapSearchItems')
			.addClass('map-wait')
			.show();

		$.ajax({
			url:form.attr('action'),
			type:'post',
			dataType:'html',

			data:form.serialize(),

			success:success_handler,
			error:error_handler
		});
	};

	$('#pageMap .form-search')
		.on('submit', function (event) {
			var form = $(this);

			if (form.attr('action').length == 0) {
				$.jGrowl('Не указанна ссылка для отправки данных');
				return false;
			}

			if ($('.search-query', this).val() !== '') {
				search(form);
			} else {
				$('#pageMapItems').show();
				$('#pageMapSearchItems').hide();
			}

			return false;
		});
});


cms.init.add('page_add', function () {
	$('body').on('keyup', 'input[name="page[title]"]', function () {
		$('input[name="page[breadcrumb]"]').val($(this).val());
	});
});

$(function() {
	cms.models.page = Backbone.Model.extend({
		urlRoot: SITE_URL + 'api-page',
		
		defaults: {
			slug: '',
			parent_id: 0,
			position: 0
		},
	});

	cms.collections.pages = Backbone.Collection.extend({
		url: '/api-page',
		model: cms.models.page,
		
		parse: function(response) {
			return response.data;
		},
			
		comparator: function(a) {
			return a.get('position');
		}
	});
	
});