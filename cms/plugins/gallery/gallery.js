cms.init.add('photos_index', function(){
	$('#photo-upload-button').click(function() {
		$('#photo-upload-form').click();
	});

	$("#photo-upload-form").html5_upload({
		url: SITE_URL + 'ajax-photos-upload/' + CATEGORY_ID,
		sendBoundary: true,
		onStart: function() {
			return true;
		},
		onProgress: function(event, progress, name, number, total) {
			cms.loader.show('fast');
		},
		onFinishOne: function(event, response, name, number, total) {
			try {
				response = $.parseJSON(response);
				var row = $(response.file).hide();
				$('.photos').append(row.fadeIn(500));
			} catch (e) {}
		},
		onFinish: function(event, response, name, number, total) {
			cms.loader.hide();
		}
	});

	$('.photos').on('click', '.icon-trash', function() {
		var cont = $(this).parent();
		var id = cont.data('id');
		
		$.post(SITE_URL + 'ajax-photos-delete', {id: id}, function(request){
			if(request.status == true) {
				cont.fadeOut(function() {
					$(this).remove();
				});
			}
		},'json')
	});

	$('.photos').on('click', '.icon-picture', function() {
		var cont = $(this).parent();
		var id = cont.data('id');
		var self = $(this);
		
		$.post(SITE_URL + 'ajax-photos-category_image', {id: id, category_id: CATEGORY_ID}, function(request){
			if(request.status == true) {
				$('.photos .thumbnail').removeClass('category-image');
				$('.thumbnail', cont).addClass('category-image');
				self.remove();
				
			}
		},'json')
	});

	$('.categories').on('click', '.icon-trash', function() {
		if ( ! confirm(__('Are you sure?')))
			return;
		
		var cont = $(this).parent();
		var id = cont.data('id');
		
		$.post(SITE_URL + 'ajax-photos-category_delete', {id: id}, function(request){
			if(request.status == true) {
				window.location = '';
			}
		},'json')
	});

	$( ".droppable .span1" ).droppable({
		tolerance: 'intersect',
		accept: ".sortable .span1",
		hoverClass: "drop",
		drop: function( event, ui ) {
			var element = $(ui.draggable);
			var id = element.data('id');
			var category_id = $(this).data('id');
			
			element.hide();
			$.post(SITE_URL + 'ajax-photos-move', {id: id, category_id: category_id, category_image: $('.thumbnail ', element).hasClass('category-image')}, function(request){
				if(request.status == true) {
					cms.loader.hide();
					element.remove();
				} else {
					element.show();
				}
			},'json')
		},
    });
	
	$('.categories').sortable({
		cursor: 'move',
		items: '.ui-sort',
		update: function(event, ui){
			var pos = $('.categories').sortable("toArray", {attribute: 'data-id'});
			cms.loader.show();
			$.post(SITE_URL + 'ajax-photos-categories_sort', {pos: pos, parent_id: CATEGORY_ID}, function(request){
				if(request.status == true) {
					cms.loader.hide();
				}
			},'json')
		}
	});

	$('.sortable').sortable({
		cursor: 'move',
		update: function(event, ui){
			var pos = $('.sortable').sortable("toArray", {attribute: 'data-id'});
			cms.loader.show();
			$.post(SITE_URL + 'ajax-photos-sort', {pos: pos, category_id: CATEGORY_ID}, function(request){
				if(request.status == true) {
					cms.loader.hide();
				}
			},'json')
		}
	});

	$('#create-category').click(function()
	{
		var html = '<form class="dialog-form" onsubmit="return false;">'
		          +'<label>'+__('Category name')+'</label><input type="text" name="name" value="" class="slug-generator" />'
				  +'<label>'+__('slug')+'</label><input type="text" name="slug" value="" class="slug" />'
				  +'</form>';
		
		var buttons = {};
		
		buttons[__('Create')] = function()
		{
			var category_name = $(this).find('input[name="name"]').val();
			var slug = cms.convertSlug($(this).find('input[name="slug"]').val());
			var self = $(this);
			
			$.post(SITE_URL + 'ajax-photos-category_add', {
				name: category_name, 
				slug: slug,
				parent_id: CATEGORY_ID
			}, function(resp) {
				if(resp.status == true) {
					window.location = '';
				}
			}, 'json');
		};
		
		buttons[__('Cancel')] = function()
		{
			$(this).dialog('close');
		};
		
		$form = $(html).dialog({
			modal:     true,
			width:     250,
			resizable: false,
			title:     __('Create category'),
			buttons:   buttons
		});
	});
})