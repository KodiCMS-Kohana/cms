var cms = {
	models: {},
	views: {},
	collections: {},
	routes: {},
	popup_target: null,
	
	// Error
	error: function (msg, e) {
		this.message(msg, 'error');
	},
		
	message: function(msg, type) {
		if(!type) type = 'success';
		
		var title = type.charAt(0).toUpperCase() + type.slice(1);
		window.top.$.pnotify({
			title: __(title),
			text: msg,
			sticker: false,
			type: type,
			history: false
		});
	},
	error_field: function(name, message) {
		name = name.indexOf('.') !== -1 ? '['+name.replace(/\./g, '][') + ']' : name;
		var gpoups = $('.form-group:not(.has-error)');
		
		input = $(':input[name*="' + name + '"]', gpoups)
			.after('<span class="help-inline error-message">' + message + '</span>')
			.closest('.form-group')
			.addClass('has-error');
	},
	clear_error: function() {
		$('.form-group')
			.removeClass('has-error')
			.find('.error-message')
			.remove();
	
		$('.nav-tabs li a').removeClass('tab-error');
	},
	loader: {
		counter: 0,
		init: function (container) {
			if(!(container instanceof jQuery)) 
				container = $('body');

			return $('<div class="_loader_container"><div class="_loader_bg"></div><span>' + __('Loading') + '</span></div>')
				.appendTo(container)
				.css({
					width: container.outerWidth(true), 
					height: container.outerHeight(true),
					top: container.offset().top,
					left: container.offset().left
				})
				.prop('id', 'loader' + ++this.counter);
		},
		show: function (container, speed) {
			if(!speed) {
				speed = 500;
			}

			var loader = this.init(container).fadeTo(speed, 0.4);
			return this.counter;
		},
		hide: function (id) {
			if(!id)
				cont = $('._loader_container');
			else 
				cont = $('#loader'+id);

			cont.stop().fadeOut(400, function() {
				$(this).remove();
			});
		}
	},
	
	/**
	 * Вычисление высоты контейнера с контентом
	 */
	content_height: null,
	calculateContentHeight: function() {
		if(this.content_height != null) 
			return this.content_height;

		var contentCont = $('#content'),
			headerCont = $('header'),
			footerCont = $('footer'),
			windowCont = $(window);

		var contentContHeight = windowCont.outerHeight() - headerCont.outerHeight(),
			contentContPadding = contentCont.outerHeight(!$('body').hasClass('iframe')) - contentCont.innerHeight() + ($('body').hasClass('iframe')) ? 0 : 160;

		this.content_height = contentContHeight - contentContPadding;

		return this.content_height;
	},
	
	translations: {},
	
	// Plugins
	plugins: {},
	
	// Messages
	messages: {
		init: function () {
			$('.message')
				.animate({top:0}, 1000);
		}
	},
	filters: {
		filters: [],
		switchedOn: {},
		editors: {},
		add: function (name, switchOn_handler, switchOff_handler, exec_handler) {
			if (switchOn_handler == undefined || switchOff_handler == undefined) {
				cms.error('System try to add filter without required callbacks.', name, switchOn_handler, switchOff_handler);
				return;
			}
			this.filters.push([ name, switchOn_handler, switchOff_handler, exec_handler ]);
		},
		switchOn: function (textarea_id, filter, params) {
			$('#' + textarea_id).css('display', 'block');
			if (this.filters.length > 0) {
				var old_filter = this.get(textarea_id);
				var new_filter = null;
				
				for (var i = 0; i < this.filters.length; i++) {
					if (this.filters[i][0] == filter) {
						new_filter = this.filters[i];
						break;
					}
				}
				if(old_filter !== new_filter) {
					this.switchOff(textarea_id);
				}
				try {
					this.switchedOn[textarea_id] = new_filter;
					this.editors[textarea_id] = new_filter[1](textarea_id, params);
					$('#' + textarea_id).trigger('filter:switch:on', this.editors[textarea_id]);
				}
				catch (e) {}
			}
		},
		switchOff: function (textarea_id) {
			var filter = this.get(textarea_id);
			try {
				if ( filter && typeof(filter[2]) == 'function' ) {
					filter[2](this.editors[textarea_id], textarea_id);
				}
				this.switchedOn[textarea_id] = null;
				$('#' + textarea_id).trigger('filter:switch:off');
			}
			catch (e) {}
		},
		get: function(textarea_id) {
			for (var key in this.switchedOn) {
				if ( key == textarea_id )
					return this.switchedOn[key];
			}
			return null;
		},	
		exec: function(textarea_id, command, data) {
			var filter = this.get(textarea_id);
			if( filter && typeof(filter[3]) == 'function' )
				return filter[3](this.editors[textarea_id], command, textarea_id, data);
			return false;
		}
	},
	filemanager: {
		open: function(object, type) {
			return $.fancybox.open({
				href : BASE_URL + '/elfinder/',
				type: 'iframe'
			}, {
				autoSize: false,
				width: 1000,
				afterLoad: function() {
					this.content[0].contentWindow.elfinderInit(object, type);
				}
			});
		}
	}
};

cms.addTranslation = function (obj) {
    for (var i in obj) {
        cms.translations[i] = obj[i];
    }
};

cms.ui = {
    callbacks:[],
    add:function (module, callback) {
        if (typeof(callback) != 'function')
            return this;

        cms.ui.callbacks.push([module, callback]);
		
		return this;
    },
    init:function (module) {
		
		$('body').trigger('before_ui_init');
		
        for (var i = 0; i < cms.ui.callbacks.length; i++) {
			try {
				if(!module)
					cms.ui.callbacks[i][1]();
				else if(_.isArray(module) && _.indexOf(module, cms.ui.callbacks[i][0]) != -1 ) {
					cms.ui.callbacks[i][1]();
				}
				else if (module == cms.ui.callbacks[i][0]) {
					cms.ui.callbacks[i][1]();
				}
			} catch (e) {}
        }
		
		$('body').trigger('after_ui_init');
    }
};

cms.init = {
	callbacks:[],
	add:function (rout, callback) {
		if (typeof(callback) != 'function')
			return this;

		if (typeof(rout) == 'object') {
			for (var i = 0; i < rout.length; i++)
				cms.init.callbacks.push([rout[i], callback]);
		} else if (typeof(rout) == 'string') {
			cms.init.callbacks.push([rout, callback]);
		}
		
		cms.init.callbacks.reverse();
		return this;
	},
	run:function () {
		$('body').trigger('before_cms_init');
		
		var body_id = $('body:first').attr('id');

		for (var i = 0; i < cms.init.callbacks.length; i++) {
			var rout_to_id = 'body_' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
		
		$('body').trigger('after_cms_init');
	}
};

cms.ui.add('flags', function() {
	$('body').on('click', '.flags .label', function(e) {
		var $src = $(this).parent().data('target');
		if( ! $src ) $src = $(this).parent().prevAll(':input');
		else $src = $($src);
		
		var $container = $(this).parent();
		var $append = $container.data('append') == true;
		var $array = $container.data('array') == true;
		var $value = $(this).data('value');
		
		if($array) $value = $value.split(',');

		if($append) {
			if($src.is(':input')) {
				var $values = $src.val().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.val($values.join(', '));
			}
			else {
				var $values = $src.text().split(', ');
				$values.push($value);
				$values = _.uniq(_.compact($values));
				$src.text($values.join(', '));
			}
			
			$('.label', $container).removeClass('label-success');
			for(i in $values) {
				$('.label[data-value="'+$values[i]+'"]').addClass('label-success');
			}
			
		} else {
			$('.label', $container).removeClass('label-success');
			$(this).addClass('label-success');

			if($src.hasClass('select2-offscreen'))
			{
				$src.select2("val", $value);
			}
			else if($src.is(':input'))
			{
				$src.val($value);
			}
			else {
				$src.text($value);
			}
		}
		
		e.preventDefault();
	});
}).add('btn-confirm', function() {
	$('body').on('click', '.btn-confirm', function () {
		if (confirm(__('Are you sure?')))
			return true;

		return false;
	});
}).add('calculate_height', function() {
	cms.calculateContentHeight();

	$(window).resize(function() {
		cms.content_height = null;
		cms.calculateContentHeight();
	});
}).add('panel-toggler', function() {
	var icon_open = 'fa-chevron-up',
		icon_close = 'fa-chevron-down';

	$('.panel-toggler')
		.click(function () {
			var $self = $(this);
	
			if($self.data('target-')) {
				$_cont = $($self.data('target-'));
			} else {
				var $_cont = $self.next('.panel-spoiler');
			}
		
			$_cont.slideToggle('fast', function() {
				var $icon = $self.find('.panel-toggler-icon');
				if($(this).is(':hidden')) {
					$icon.removeClass(icon_open).addClass(icon_close).addClass('fa');
				} else {
					$icon.addClass(icon_open).removeClass(icon_close).addClass('fa');
				}
			});
			
			return false;
		}).each(function() {
			if($(this).data('hash') == window.location.hash.substring(1))
			{
				$(this).click();
				$('html,body').animate({scrollTop: $(this).offset().top}, 'slow');
			}
		})
		.append('<div class="panel-heading-controls"><span class="text-sm"><i class="panel-toggler-icon fa '+icon_close+'" />&nbsp;&nbsp;&nbsp;'+__('Toggle')+'</span></div>');

}).add('datepicker', function() {
	$('.datetimepicker').datetimepicker({
		format:'Y-m-d H:i:00',
		lang: LOCALE,
		dayOfWeekStart:1
	});
	
	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		lang: LOCALE,
		dayOfWeekStart:1
	});
	
	$('.timepicker').datetimepicker({
		timepicker: true,
		datepicker: false,
		format: 'H:i:s',
		lang: LOCALE,
		dayOfWeekStart:1
	});
}).add('slug', function() {

    var slugs = {};
    $('body').on('keyup', '.slug-generator', function () {
		var $slug_cont = $('.slug');

		if($(this).data('slug')) {
			$slug_cont = $($(this).data('slug'));
		}
		
		$separator = '-';
		if($(this).data('separator')) {
			$separator = $(this).data('separator');
		}
		
        if ($slug_cont.is(':input') && $slug_cont.val() == '')
            slugs[$slug_cont] = true;
		
		$slug_cont.on('keyup', function() {
			slugs[$slug_cont] = false;
		});

        if (slugs[$slug_cont]) {
			var slug = getSlug($(this).val(), {
				separator: $separator
			});
			
			if($slug_cont.is(':input'))
				$slug_cont.val(slug);
			else
				$slug_cont.text(slug);
        }
    });

	$('body').on('keyup', '.slug', function () {
		var c = String.fromCharCode(event.keyCode);
		var isWordcharacter = c.match(/\w/);
		
		if( ! isWordcharacter && event.keyCode != '32') return;
		
		$separator = '-';
		if($(this).data('separator')) {
			$separator = $(this).data('separator');
		}
		
		var slug = getSlug($(this).val(), {
			separator: $separator
		});
		
		$(this).val(slug);
		slugs[$(this)] = false;

		if ($(this).val() == '')
			slugs[$(this)] = true;
	});

}).add('dropzone', function() {
	cms.uploader = new Dropzone('.dropzone', {
		success: function(file, r) {
			var response = $.parseJSON(r);
			var self = this;
			if(response.code != 200) {
				cms.message(response.message, 'error');
				
			} else if(response.message) {
				cms.message(response.message);
			}
			
			$(file.previewElement).fadeOut(500, function() {
				self.removeFile(file);
			});
		},
		error: function(file, message) {
			cms.message(message, 'error');
			this.removeFile(file);
		},
        dictDefaultMessage: __("Drop files here to upload"),
        dictFallbackMessage: __("Your browser does not support drag'n'drop file uploads."),
        dictFallbackText: __("Please use the fallback form below to upload your files like in the olden days."),
        dictFileTooBig: __("File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB."),
        dictInvalidFileType: __("You can't upload files of this type."),
        dictResponseError: __("Server responded with {{statusCode}} code."),
        dictCancelUpload: __("Cancel upload"),
        dictCancelUploadConfirmation: __("Are you sure you want to cancel this upload?"),
        dictRemoveFile: __("Remove file"),
        dictMaxFilesExceeded: __("You can only upload {{maxFiles}} files."),
	});
}).add('fancybox', function() {
    $(".fancybox-image").fancybox();
}).add('popup', function() {
	$(".popup").fancybox({
		fitToView	: true,
		autoSize	: false,
		width		: '99%',
		height		: '99%',
		openEffect	: 'none',
		closeEffect	: 'none',
		beforeLoad: function() {
			this.href = updateQueryStringParameter(this.href, 'type', 'iframe');
			
			var title = this.element.data('title');
			if(title !== false) {
				this.title = title ? title : this.element.html();
			}
			
			cms.popup_target = this.element;
		},
		helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
	});

	var method = ACTION == 'add' ? 'put' : 'post';
	var $form_actions = $('.iframe .form-actions');
	
	var $action = CONTROLLER;

	if((typeof API_FORM_ACTION != 'undefined'))
		$action = API_FORM_ACTION;

	$('.btn-save', $form_actions).on('click', function(e) {
		var $data = $('form').serializeObject();
		Api[method]($action, $data);
		
		e.preventDefault();
	});

	$('.btn-save-close', $form_actions).on('click', function(e) {
		var $data = $('form').serializeObject();
		Api[method]($action, $data, function(response) {
			window.top.$.fancybox.close();
		});
		e.preventDefault();
	});

	$('.btn-close', $form_actions).on('click', function(e) {
		window.top.$.fancybox.close();
		e.preventDefault();
	});
}).add('select2', function() {
	$('select').not('.no-script').select2();
	$('.tags').select2({
		tags: [],
		minimumInputLength: 0,
		tokenSeparators: [TAG_SEPARATOR],
		createSearchChoice: function(term, data) {
			if ($(data).filter(function() {
				return this.text.localeCompare(term) === 0;
			}).length === 0) {
				return {
					id: term,
					text: term
				};
			}
		},
		multiple: true,
		ajax: {
			url: Api.build_url('tags'),
			dataType: "json",
			data: function(term, page) {
				return {term: term};
			},
			results: function(data, page) {
				if(!data.response) return {results: []};
				return {results: data.response};
			}
		},
		initSelection: function(element, callback) {
			var data = [];
			
			var tags = element.val().split(",");
			for(i in tags) {
				data.push({
					id: tags[i],
					text: tags[i]
				});
			};
			callback(data);
		}
	});
}).add('ajax_form', function() {
	$('body').on('submit', 'form.form-ajax', function() {
		var $self = $(this),
			$buttons = $('button', $self).attr('disabled', 'disabled'),
			$action = $self.attr('action');

		if($self.data('ajax-action'))
			$action = $self.data('ajax-action');

		Api.post($action, $self.serialize(), function(response) {
			setTimeout(function() {
				$buttons.removeAttr('disabled');
			}, 5000);
		});

		return false;
	});
}).add('filemanager', function() {
	var $input = $(':input[data-filemanager]');
	
	$input.each(function() {
		var $self = $(this);
		var $btn = $('<button class="btn" type="button"><i class="fa fa-folder-open"></i></button>');
		if($self.next().hasClass('input-group-btn')) {
			$btn.prependTo($self.next());
		} else {
			$btn.insertAfter($self);
		}

		$btn.on('click', function() {
			cms.filemanager.open($self);
		});
	})
	
		
	$('body').on('click', '.btn-filemanager', function() {
		var el = $(this).data('el');
		var type = $(this).data('type');

		if(!el) return false;
		
		cms.filemanager.open(el, type);
		return false;
	});
}).add('hotkeys', function(){
	$('*[data-hotkeys]').each(function() {
		var $self = $(this),
			$hotkeys = $self.data('hotkeys'),
			$callback = function(e){ e.preventDefault(); };
			
		if($self.is(':submit') || $self.hasClass('popup')) {
			$callback = function( e ) {
				$self.trigger('click');
				e.preventDefault();
			} 
		} else if($self.attr('href')) {

			$callback = function( e ) {
				if($self.hasClass('btn-confirm')) {
					if ( ! confirm(__('Are you sure?')))
						return false;
				}
				window.location = $self.attr('href');
				e.preventDefault();
			} 
		} else if($self.hasClass('panel-toggler')) {
			$callback = function( e ) {
				$self.trigger('click');
				$('body').scrollTo($self);
				e.preventDefault();
			} 
		} else if($self.hasClass('nav-tabs')) {
			$callback = function( e ) {
				var $current_li = $self.find('li.active'),
					$next_li = $current_li.next();
				
				if($next_li.hasClass('nav-section')) {
					$next_li = $next_li.next();
				}
				if($current_li.is(':last-child')) {
					$next_li = $self.parent().find('li:first-child');
				}
				
				$next_li.find('a').trigger('click');
				e.preventDefault();
			} 
		} else if($self.is(':checkbox')) {
			$callback = function( e ) {
				if($self.prop("checked"))
					$self.uncheck().trigger('change');
				else
					$self.check().trigger('change');
				e.preventDefault();
			}
		}
		
		$(document).on('keydown', null, $hotkeys, $callback);
	});
	
	// GLOBAL HOTKEYS
	$(document).on('keydown', null, 'shift+f1', function(e) {
		Api.get('cache.clear');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'shift+f3', function(e) {
		Api.get('search.update_index');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'shift+f4', function(e) {
		Api.post('layout.rebuild');
		e.preventDefault();
	});
	
	$(document).on('keydown', null, 'ctrl+shift+l', function(e) {
		window.location = '/backend/logout';
		e.preventDefault();
	});
}).add('api_buttons', function(){
	$('.btn-api').on('click', function(e) {
		e.preventDefault();
		
		var $callback = function(response) {};
		var $url = $(this).data('url');
		if( ! $url) return;
		
		var $method = $(this).data('method');
		var $reload = $(this).data('reload');
		
		if($reload) {
			if($reload === true)
				$callback = function() { window.location = ''}
			else
				$callback = function() { window.location = $reload}
		}
		
		if( ! $method) $method = 'GET';
		Api.request($method, $url, null, $callback);
	})
}).add('select_all_checkbox', function() {
	$(document).on('change', 'input[name="check_all"]', function(e) {
		var $self = $(this),
			$target = $self.data('target');
		
		if( ! $target) return false;

		$($target).prop("checked" , this.checked).trigger('change');
		e.preventDefault();
    });
}).add('icon', function() {
	$('*[data-icon]').add('*[data-icon-pepend]').each(function() {
		$(this).html('<i class="fa fa-' + $(this).data('icon') + '"></i>&nbsp&nbsp' + $(this).html());
		$(this).removeAttr('data-icon-pepend').removeAttr('data-icon');
	});
	
	$('*[data-icon-append]').each(function() {
		$(this).html($(this).html() + '&nbsp&nbsp<i class="fa fa-' + $(this).data('icon-append') + '"></i>');
		$(this).removeAttr('data-icon-append');
	});
}).add('tabbable', function() {
	$('.tabbable').each(function(i) {
		var $self = $(this);
		var $tabs_content = $('<div class="tab-content no-padding-t" />').prependTo($self);
		var $tabs_ul = $('<ul class="nav nav-tabs tabs-generated" style="position:relative; margin-top: 10px;" />').insertBefore($self);
		$('> .panel-heading', $self).each(function(j) {
			var $li = $('<li></li>').appendTo($tabs_ul);
			var $content = $(this).nextUntil('.panel-heading').not('.panel-footer').removeClass('panel-spoiler');
			
			$(this).find('.panel-title').removeClass('panel-title');
			$(this).find('.panel-heading-controls').remove();
			var $content_container = $('<div class="tab-pane" id="panel-tab-' + i + '' +  '' + j+ '" />').append($content).appendTo($tabs_content);
			$('<a href="#panel-tab-' + i + '' +  '' + j+ '" data-toggle="tab"></a>').html($(this).html()).appendTo($li);
			
			$(this).remove();
		});
		
		$('li a', $tabs_ul).on('click', function() {
			window.location.hash = $(this).attr('href');
		});
	});
	
	if(window.location.hash.length > 0 && $('.tabs-generated li a[href='+window.location.hash+']').length > 0) {
		$('li a[href='+window.location.hash+']').parent().addClass('active');
		$('.tabbable .tab-pane' + window.location.hash).addClass('active');
	} else {
		$('.tabs-generated li:first-child').addClass('active');
		$('.tabbable .tab-pane:first-child').addClass('active');
	}
	
	$('.tabs-generated').tabdrop();
});

var Api = {
	_response: null,
	get: function(uri, data, callback, show_loader) {
		this.request('GET', uri, data, callback, show_loader);
		
		return this.response();
	},
	post: function(uri, data, callback, show_loader) {
		this.request('POST', uri, data, callback, show_loader);
		
		return this.response();
	},
	put: function(uri, data, callback, show_loader) {
		this.request('PUT', uri, data, callback, show_loader);
		
		return this.response();
	},
	'delete': function(uri, data, callback, show_loader) {
		this.request('DELETE', uri, data, callback, show_loader);
		
		return this.response();
	},
	build_url: function(uri) {
		uri = uri.replace('/' + ADMIN_DIR_NAME, '');
		
		if(uri.indexOf('-') == -1)
		{
			uri = '-' + uri;
		}
		else if(uri.indexOf('-') > 0 && (uri.indexOf('/') == -1 || uri.indexOf('/') > 0))
		{
			uri = '/' + uri;
		}
		
		if(uri.indexOf('/api') == -1)
		{
			uri = 'api' + uri;
		}
		
		if(uri.indexOf(ADMIN_DIR_NAME) == -1)
		{
			// Add the ADMIN DIR NAME
			if(uri.indexOf('/') != 0)
			{
				uri = ADMIN_DIR_NAME + '/' + uri; 
			}
			else
			{
				uri = ADMIN_DIR_NAME + uri; 
			}	
		}
		
		if(uri.indexOf(SITE_URL) == -1)
		{
			// Add SITE_URL.
			uri = SITE_URL + uri;
		}
		
		return uri;
	},
	request: function(method, uri, data, callback, show_loader) {
		url = Api.build_url(uri);
		
		var obj = new Object();

		if(show_loader == 'undefined')
			show_loader = true;
		
		$.ajaxSetup({
			contentType : 'application/json'
		});

		if(typeof(data) == 'object' && method != 'GET') 
			data = JSON.stringify(data);

		$.ajax({
			type: method,
			url: url,
			data: data,
			dataType: 'json',
			beforeSend: $.proxy(function(){
				if(show_loader) 
					obj._loader_id = cms.loader.show(show_loader);					
			}, obj),
			success: function(response) {
				if(response.code != 200) {
					if(typeof(callback) == 'function') callback(response);
					return Api.exception(response);
				}
				
				if (response.message) {
					cms.clear_error();

					if(response.message instanceof Object) {
						parse_messages(response.message);
					} else {
						cms.message(response.message);
					}
				}
	
				if(response.redirect) {
					$.get(window.top.CURRENT_URL, function(resp){
						window.location = response.redirect + '?type=iframe';
					});
				}
				this._response = response;
				
				var $event = method + url.replace(SITE_URL, ":").replace(/\//g, ':');
				window.top.$('body').trigger($event.toLowerCase(), [this._response.response]);

				if(typeof(callback) == 'function') callback(this._response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(typeof(callback) == 'function') callback(textStatus);
			}
		}).always($.proxy(function(){
			cms.loader.hide(obj._loader_id);
		}, obj));
	},
	exception: function(response) {
		if(response.code == 120 && typeof(response.errors) == 'object') {
			for(i in response.errors) {
				cms.message(response.errors[i], 'error');
				cms.error_field(i, response.errors[i]);
			}
		} else if (response.message) {
			cms.message(response.message, 'error');
		}
	},
	response: function() {
		return this._response;
	}
};

// Run
$(function() {
	cms.messages.init();

	cms.ui.init();
	cms.init.run();

	parse_messages(MESSAGE_ERRORS, 'error');
	parse_messages(MESSAGE_SUCCESS);

	$.fn.check=function(){return this.each(function(){this.checked=true})}
	$.fn.uncheck=function(){return this.each(function(){this.checked=false})};
	$.fn.checked=function(){return this.prop("checked")}

	$.fn.tabs = function () {
		return $('li a', this).on('click', function() {
			$(this)
				.parent()
				.addClass('active')
				.siblings()
				.removeClass('active');

			$('div.tab-pane').removeClass('active');
			$($(this).attr('href')).addClass('active');

			return false;
		});
	};
});



$.fn.serializeObject=function(){var e={};var t=this.serializeArray();$.each(t,function(){if(e[this.name]!==undefined){if(!e[this.name].push){e[this.name]=[e[this.name]]}e[this.name].push(this.value||"")}else{e[this.name]=this.value||""}});return e};

$.fn.scrollTo=function(e,t,n){if(typeof t=="function"&&arguments.length==2){n=t;t=e}var r=$.extend({scrollTarget:e,offsetTop:50,duration:500,easing:"swing"},t);return this.each(function(){var e=$(this);var t=typeof r.scrollTarget=="number"?r.scrollTarget:$(r.scrollTarget);var i=typeof t=="number"?t:t.offset().top+e.scrollTop()-parseInt(r.offsetTop);e.animate({scrollTop:i},parseInt(r.duration),r.easing,function(){if(typeof n=="function"){n.call(this)}})})};

jQuery.browser = {};
jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());

function readImage(input, target) {
	if ( input.files && input.files[0] && target ) {
		var FR = new FileReader();
		FR.onload = function(e) {
			var img = new Image();
				img.src = e.target.result;
			
			var ratio = img.width / img.height;

			var canvas = document.createElement("canvas");
				canvas.width = 100 * ratio;
				canvas.height = 100;

			var ctx = canvas.getContext("2d");
				ctx.drawImage(img, 0, 0, canvas.width, canvas.height );
			target.attr( "src", canvas.toDataURL("image/jpeg", 1) );
		};       
		FR.readAsDataURL( input.files[0] );
	}
}

function updateQueryStringParameter(uri, key, value) {
	var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
	separator = uri.indexOf('?') !== -1 ? "&" : "?";
	if (uri.match(re)) {
		return uri.replace(re, '$1' + key + "=" + value + '$2');
	}
	else {
		return uri + separator + key + "=" + value;
	}
}

function parse_messages($messages, $type) {
	for(text in $messages) {
		if(text == '_external') {
			parse_messages($messages[text], $type);
			continue;
		}
		
		if($type == 'error'){
			cms.error_field(text, $messages[text]);
		}

		cms.message($messages[text], $type);
	}
}

function strtr(e,t,n){if(typeof t==="object"){var r="";for(var i=0;i<e.length;i++){r+="0"}var s=0;var o=-1;var u="";for(fr in t){s=0;while((o=e.indexOf(fr,s))!=-1){if(parseInt(r.substr(o,fr.length))!=0){s=o+1;continue}for(var a=0;a<t[fr].length;a++){u+="1"}r=r.substr(0,o)+u+r.substr(o+fr.length,r.length-(o+fr.length));e=e.substr(0,o)+t[fr]+e.substr(o+fr.length,e.length-(o+fr.length));s=o+t[fr].length+1;u=""}}return e}for(var f=0;f<t.length;f++){e=e.replace(new RegExp(t.charAt(f),"g"),n.charAt(f))}return e}

function __(e,t){if(cms.translations[e]!==undefined){var e=cms.translations[e]}return t==undefined?e:strtr(e,t)}

(function(e){function t(t){if(typeof t.data==="string"){t.data={keys:t.data}}if(!t.data||!t.data.keys||typeof t.data.keys!=="string"){return}var n=t.handler,r=t.data.keys.toLowerCase().split(" "),i=["text","password","number","email","url","range","date","month","week","time","datetime","datetime-local","search","color","tel"];t.handler=function(t){if(this!==t.target&&(/textarea|select/i.test(t.target.nodeName)||e.inArray(t.target.type,i)>-1)){return}var s=e.hotkeys.specialKeys[t.keyCode],o=String.fromCharCode(t.which).toLowerCase(),u="",a={};e.each(["alt","ctrl","meta","shift"],function(e,n){if(t[n+"Key"]&&s!==n){u+=n+"+"}});u=u.replace("alt+ctrl+meta+shift","hyper");if(s){a[u+s]=true}if(o){a[u+o]=true;a[u+e.hotkeys.shiftNums[o]]=true;if(u==="shift+"){a[e.hotkeys.shiftNums[o]]=true}}for(var f=0,l=r.length;f<l;f++){if(a[r[f]]){return n.apply(this,arguments)}}}}e.hotkeys={version:"0.8",specialKeys:{8:"backspace",9:"tab",10:"return",13:"return",16:"shift",17:"ctrl",18:"alt",19:"pause",20:"capslock",27:"esc",32:"space",33:"pageup",34:"pagedown",35:"end",36:"home",37:"left",38:"up",39:"right",40:"down",45:"insert",46:"del",59:";",61:"=",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9",106:"*",107:"+",109:"-",110:".",111:"/",112:"f1",113:"f2",114:"f3",115:"f4",116:"f5",117:"f6",118:"f7",119:"f8",120:"f9",121:"f10",122:"f11",123:"f12",144:"numlock",145:"scroll",173:"-",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'"},shiftNums:{"`":"~",1:"!",2:"@",3:"#",4:"$",5:"%",6:"^",7:"&",8:"*",9:"(",0:")","-":"_","=":"+",";":": ","'":'"',",":"<",".":">","/":"?","\\":"|"}};e.each(["keydown","keyup","keypress"],function(){e.event.special[this]={add:t}})})(this.jQuery)

!function(){"use strict";var a=function(a,b){var f,g,h,i,j,k="object"==typeof b&&b.maintainCase||!1,l="object"==typeof b&&b.titleCase?b.titleCase:!1,m="object"==typeof b&&"object"==typeof b.custom&&b.custom?b.custom:{},n="object"==typeof b&&b.separator||"-",o="object"==typeof b&&+b.truncate>1&&b.truncate||!1,p="object"==typeof b&&b.uric||!1,q="object"==typeof b&&b.uricNoSlash||!1,r="object"==typeof b&&b.mark||!1,s="object"==typeof b&&b.lang&&e[b.lang]?e[b.lang]:"object"!=typeof b||b.lang!==!1&&b.lang!==!0?e.en:{},t=[";","?",":","@","&","=","+","$",",","/"],u=[";","?",":","@","&","=","+","$",","],v=[".","!","~","*","'","(",")"],w="",x=n;if(l&&"number"==typeof l.length&&Array.prototype.toString.call(l)&&l.forEach(function(a){m[a+""]=a+""}),"string"!=typeof a)return"";for("string"==typeof b?n=b:"object"==typeof b&&(p&&(x+=t.join("")),q&&(x+=u.join("")),r&&(x+=v.join(""))),Object.keys(m).forEach(function(b){var d;d=b.length>1?new RegExp("\\b"+c(b)+"\\b","gi"):new RegExp(c(b),"gi"),a=a.replace(d,m[b])}),l&&(a=a.replace(/(\w)(\S*)/g,function(a,b,c){var d=b.toUpperCase()+(null!==c?c:"");return Object.keys(m).indexOf(d.toLowerCase())<0?d:d.toLowerCase()})),x=c(x),a=a.replace(/(^\s+|\s+$)/g,""),j=!1,g=0,i=a.length;i>g;g++)h=a[g],d[h]?(h=j&&d[h].match(/[A-Za-z0-9]/)?" "+d[h]:d[h],j=!1):!s[h]||p&&-1!==t.join("").indexOf(h)||q&&-1!==u.join("").indexOf(h)||r&&-1!==v.join("").indexOf(h)?(j&&(/[A-Za-z0-9]/.test(h)||w.substr(-1).match(/A-Za-z0-9]/))&&(h=" "+h),j=!1):(h=j||w.substr(-1).match(/[A-Za-z0-9]/)?n+s[h]:s[h],h+=void 0!==a[g+1]&&a[g+1].match(/[A-Za-z0-9]/)?n:"",j=!0),w+=h.replace(new RegExp("[^\\w\\s"+x+"_-]","g"),n);return w=w.replace(/\s+/g,n).replace(new RegExp("\\"+n+"+","g"),n).replace(new RegExp("(^\\"+n+"+|\\"+n+"+$)","g"),""),o&&w.length>o&&(f=w.charAt(o)===n,w=w.slice(0,o),f||(w=w.slice(0,w.lastIndexOf(n)))),k||l||l.length||(w=w.toLowerCase()),w},b=function(b){return function(c){return a(c,b)}},c=function(a){return a.replace(/[-\\^$*+?.()|[\]{}\/]/g,"\\$&")},d={"À":"A","Á":"A","Â":"A","Ã":"A","Ä":"Ae","Å":"A","Æ":"AE","Ç":"C","È":"E","É":"E","Ê":"E","Ë":"E","Ì":"I","Í":"I","Î":"I","Ï":"I","Ð":"D","Ñ":"N","Ò":"O","Ó":"O","Ô":"O","Õ":"O","Ö":"Oe","Ő":"O","Ø":"O","Ù":"U","Ú":"U","Û":"U","Ü":"Ue","Ű":"U","Ý":"Y","Þ":"TH","ß":"ss","à":"a","á":"a","â":"a","ã":"a","ä":"ae","å":"a","æ":"ae","ç":"c","è":"e","é":"e","ê":"e","ë":"e","ì":"i","í":"i","î":"i","ï":"i","ð":"d","ñ":"n","ò":"o","ó":"o","ô":"o","õ":"o","ö":"oe","ő":"o","ø":"o","ù":"u","ú":"u","û":"u","ü":"ue","ű":"u","ý":"y","þ":"th","ÿ":"y","ẞ":"SS","α":"a","β":"v","γ":"g","δ":"d","ε":"e","ζ":"z","η":"i","θ":"th","ι":"i","κ":"k","λ":"l","μ":"m","ν":"n","ξ":"ks","ο":"o","π":"p","ρ":"r","σ":"s","τ":"t","υ":"y","φ":"f","χ":"x","ψ":"ps","ω":"o","ά":"a","έ":"e","ί":"i","ό":"o","ύ":"y","ή":"i","ώ":"o","ς":"s","ϊ":"i","ΰ":"y","ϋ":"y","ΐ":"i","Α":"A","Β":"B","Γ":"G","Δ":"D","Ε":"E","Ζ":"Z","Η":"I","Θ":"TH","Ι":"I","Κ":"K","Λ":"L","Μ":"M","Ν":"N","Ξ":"KS","Ο":"O","Π":"P","Ρ":"R","Σ":"S","Τ":"T","Υ":"Y","Φ":"F","Χ":"X","Ψ":"PS","Ω":"W","Ά":"A","Έ":"E","Ί":"I","Ό":"O","Ύ":"Y","Ή":"I","Ώ":"O","Ϊ":"I","Ϋ":"Y","ş":"s","Ş":"S","ı":"i","İ":"I","ğ":"g","Ğ":"G","Ќ":"Kj","ќ":"kj","Љ":"Lj","љ":"lj","Њ":"Nj","њ":"nj","Тс":"Ts","тс":"ts","а":"a","б":"b","в":"v","г":"g","д":"d","е":"e","ё":"yo","ж":"zh","з":"z","и":"i","й":"j","к":"k","л":"l","м":"m","н":"n","о":"o","п":"p","р":"r","с":"s","т":"t","у":"u","ф":"f","х":"h","ц":"c","ч":"ch","ш":"sh","щ":"sh","ъ":"","ы":"y","ь":"","э":"e","ю":"yu","я":"ya","А":"A","Б":"B","В":"V","Г":"G","Д":"D","Е":"E","Ё":"Yo","Ж":"Zh","З":"Z","И":"I","Й":"J","К":"K","Л":"L","М":"M","Н":"N","О":"O","П":"P","Р":"R","С":"S","Т":"T","У":"U","Ф":"F","Х":"H","Ц":"C","Ч":"Ch","Ш":"Sh","Щ":"Sh","Ъ":"","Ы":"Y","Ь":"","Э":"E","Ю":"Yu","Я":"Ya","Є":"Ye","І":"I","Ї":"Yi","Ґ":"G","є":"ye","і":"i","ї":"yi","ґ":"g","č":"c","ď":"d","ě":"e","ň":"n","ř":"r","š":"s","ť":"t","ů":"u","ž":"z","Č":"C","Ď":"D","Ě":"E","Ň":"N","Ř":"R","Š":"S","Ť":"T","Ů":"U","Ž":"Z","ą":"a","ć":"c","ę":"e","ł":"l","ń":"n","ś":"s","ź":"z","ż":"z","Ą":"A","Ć":"C","Ę":"E","Ł":"L","Ń":"N","Ś":"S","Ź":"Z","Ż":"Z","ā":"a","ē":"e","ģ":"g","ī":"i","ķ":"k","ļ":"l","ņ":"n","ū":"u","Ā":"A","Ē":"E","Ģ":"G","Ī":"I","Ķ":"k","Ļ":"L","Ņ":"N","Ū":"U","ا":"a","أ":"a","إ":"i","آ":"aa","ؤ":"u","ئ":"e","ء":"a","ب":"b","ت":"t","ث":"th","ج":"j","ح":"h","خ":"kh","د":"d","ذ":"th","ر":"r","ز":"z","س":"s","ش":"sh","ص":"s","ض":"dh","ط":"t","ظ":"z","ع":"a","غ":"gh","ف":"f","ق":"q","ك":"k","ل":"l","م":"m","ن":"n","ه":"h","و":"w","ي":"y","ى":"a","ة":"h","ﻻ":"la","ﻷ":"laa","ﻹ":"lai","ﻵ":"laa","َ":"a","ً":"an","ِ":"e","ٍ":"en","ُ":"u","ٌ":"on","ْ":"","٠":"0","١":"1","٢":"2","٣":"3","٤":"4","٥":"5","٦":"6","٧":"7","٨":"8","٩":"9","“":'"',"”":'"',"‘":"'","’":"'","∂":"d","ƒ":"f","™":"(TM)","©":"(C)","œ":"oe","Œ":"OE","®":"(R)","†":"+","℠":"(SM)","…":"...","˚":"o","º":"o","ª":"a","•":"*",$:"USD","€":"EUR","₢":"BRN","₣":"FRF","£":"GBP","₤":"ITL","₦":"NGN","₧":"ESP","₩":"KRW","₪":"ILS","₫":"VND","₭":"LAK","₮":"MNT","₯":"GRD","₱":"ARS","₲":"PYG","₳":"ARA","₴":"UAH","₵":"GHS","¢":"cent","¥":"CNY","元":"CNY","円":"YEN","﷼":"IRR","₠":"EWE","฿":"THB","₨":"INR","₹":"INR","₰":"PF","đ":"d","Đ":"D","ẹ":"e","Ẹ":"E","ẽ":"e","Ẽ":"E","ế":"e","Ế":"E","ề":"e","Ề":"E","ệ":"e","Ệ":"E","ễ":"e","Ễ":"E","ọ":"o","Ọ":"o","ố":"o","Ố":"O","ồ":"o","Ồ":"O","ộ":"o","Ộ":"O","ỗ":"o","Ỗ":"O","ơ":"o","Ơ":"O","ớ":"o","Ớ":"O","ờ":"o","Ờ":"O","ợ":"o","Ợ":"O","ỡ":"o","Ỡ":"O","ị":"i","Ị":"I","ĩ":"i","Ĩ":"I","ụ":"u","Ụ":"U","ũ":"u","Ũ":"U","ư":"u","Ư":"U","ứ":"u","Ứ":"U","ừ":"u","Ừ":"U","ự":"u","Ự":"U","ữ":"u","Ữ":"U","ỳ":"y","Ỳ":"Y","ỵ":"y","Ỵ":"Y","ỹ":"y","Ỹ":"Y","ạ":"a","Ạ":"A","ấ":"a","Ấ":"A","ầ":"a","Ầ":"A","ậ":"a","Ậ":"A","ẫ":"a","Ẫ":"A","ă":"a","Ă":"A","ắ":"a","Ắ":"A","ằ":"a","Ằ":"A","ặ":"a","Ặ":"A","ẵ":"a","Ẵ":"A"},e={ar:{"∆":"delta","∞":"la-nihaya","♥":"hob","&":"wa","|":"aw","<":"aqal-men",">":"akbar-men","∑":"majmou","¤":"omla"},de:{"∆":"delta","∞":"unendlich","♥":"Liebe","&":"und","|":"oder","<":"kleiner als",">":"groesser als","∑":"Summe von","¤":"Waehrung"},nl:{"∆":"delta","∞":"oneindig","♥":"liefde","&":"en","|":"of","<":"kleiner dan",">":"groter dan","∑":"som","¤":"valuta"},en:{"∆":"delta","∞":"infinity","♥":"love","&":"and","|":"or","<":"less than",">":"greater than","∑":"sum","¤":"currency"},es:{"∆":"delta","∞":"infinito","♥":"amor","&":"y","|":"u","<":"menos que",">":"mas que","∑":"suma de los","¤":"moneda"},fr:{"∆":"delta","∞":"infiniment","♥":"Amour","&":"et","|":"ou","<":"moins que",">":"superieure a","∑":"somme des","¤":"monnaie"},pt:{"∆":"delta","∞":"infinito","♥":"amor","&":"e","|":"ou","<":"menor que",">":"maior que","∑":"soma","¤":"moeda"},ru:{"∆":"delta","∞":"beskonechno","♥":"lubov","&":"i","|":"ili","<":"menshe",">":"bolshe","∑":"summa","¤":"valjuta"},vn:{"∆":"delta","∞":"vo cuc","♥":"yeu","&":"va","|":"hoac","<":"nho hon",">":"lon hon","∑":"tong","¤":"tien te"}};if("undefined"!=typeof module&&module.exports)module.exports=a,module.exports.createSlug=b;else if("undefined"!=typeof define&&define.amd)define([],function(){return a});else try{if(window.getSlug||window.createSlug)throw"speakingurl: globals exists /(getSlug|createSlug)/";window.getSlug=a,window.createSlug=b}catch(f){}}();

if($.validator){$.validator.setDefaults({highlight:function(e){return $(e).closest(".form-group").addClass("has-error")},unhighlight:function(e){return $(e).closest(".form-group").removeClass("has-error").find("help-block-hidden").removeClass("help-block-hidden").addClass("help-block").show()},errorElement:"div",errorClass:"jquery-validate-error",errorPlacement:function(e,t){var n,r,i;i=t.is('input[type="checkbox"]')||t.is('input[type="radio"]');r=t.closest(".form-group").find(".jquery-validate-error").length;if(!i||!r){if(!r){t.closest(".form-group").find(".help-block").removeClass("help-block").addClass("help-block-hidden").hide()}e.addClass("help-block");if(i){return t.closest('[class*="col-"]').append(e)}else{n=t.parent();if(n.is(".input-group")){return n.parent().append(e)}else{return n.append(e)}}}}})}