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
		var gpoups = $('.control-group:not(.error)');
		
		input = $(':input[name*="' + name + '"]', gpoups)
			.after('<span class="help-inline error-message">' + message + '</span>')
			.parentsUntil( '.control-group' )
			.parent()
			.addClass('error');
	
		var $tab_pane = input.parentsUntil('tab-pane');
		if($tab_pane.length) {
			$tab_id = $tab_pane.attr('id');
			$tab_pane.parent().parent().find('.nav-tabs li a[href="#'+$tab_id+'"]').addClass('tab-error');
		}
	},
	clear_error: function() {
		$('.control-group')
			.removeClass('error')
			.find('.error-message')
			.remove();
	
		$('.nav-tabs li a').removeClass('tab-error');
	},
	// Convert slug
	convert_dict: {
		'ą':'a', 'ä':'a', 'č':'c', 'ę':'e', 'ė':'e', 'i':'i', 'į':'i', 'š':'s', 'ū':'u', 'ų':'u', 'ü':'u', 'ž':'z', 'ö':'o',
		'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh','з':'z','и':'i','й':'j','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'h','ц':'c','ч':'ch','ш':'sh','щ':'shh','ы':'y','э':'e','ю':'yu','я':'ya','ь':'','ъ':'','і':'i','ї':'yi','А':'A','Б':'B','В':'V','Г':'G','Д':'D','Е':'E','Ё':'YO','Ж':'ZH','З':'Z','И':'I','Й':'J','К':'K','Л':'L','М':'M','Н':'N','О':'O','П':'P','Р':'R','С':'S','Т':'T','У':'U','Ф':'F','Х':'H','Ц':'C','Ч':'CH','Ш':'SH','Щ':'SHH','Ы':'Y','Э':'E','Ю':'YU','Я':'YA','І':'I','Ї':'YI','Є':'E','Ь':'','Ъ':'',
		'Ą':'A','Č':'C','Ę':'E','Ė':'E','Į':'I','Š':'S','Ū':'U','Ų':'U','Ž':'Z','ą':'a','č':'c','ę':'e','ė':'e','i':'i','į':'i','š':'s','ū':'u','ų':'u','ž':'z'
	},
	convertSlug: function (str, separator) {
		var default_separator = '-';
		if(!separator) {
			separator = default_separator;
		}
		return str
			.toString()
			.toLowerCase()
			.replace(/[àâ]/g, 'a')
			.replace(/[éèêë]/g, 'e')
			.replace(/[îï]/g, 'i')
			.replace(/[ô]/g, 'o')
			.replace(/[ùû]/g, 'u')
			.replace(/[ñ]/g, 'n')
			.replace(/[äæ]/g, 'ae')
			.replace(/[öø]/g, 'oe')
			.replace(/[ü]/g, 'ue')
			.replace(/[ß]/g, 'ss')
			.replace(/[å]/g, 'aa')
			.replace(/(.)/g, function (c) {
				return (cms.convert_dict[c] != undefined ? cms.convert_dict[c] : c);
			})
			.replace(/[^a-zа-яіїє0-9\.\_]/g, separator)
			.replace(/ /g, separator)
			.replace(/\-+/g, separator)
			.replace(/^-/, '');
	},

	loader: {
		init: function () {
			$('body')
				.append('<div class="_loader_container"><div class="_loader_bg"></div><span>' + __('Loading') + '</span>\n\
</div>');
		},
		show: function (speed) {
			if(!speed) {
				speed = 500;
			}
			$('._loader_container').fadeTo(speed, 0.4);
		},
		hide: function () {
			$('._loader_container').stop().fadeOut();
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
			contentContPadding = contentCont.outerHeight(!$('body').hasClass('iframe')) - contentCont.innerHeight() + ($('body').hasClass('iframe')) ? 0 : 85;

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

cms.navigation = {
	counter: {
		init: function() {
			$('#site_nav .dropdown').each(function() {
				var total = 0;

				$('.dropdown-menu a', this).each(function() {
					if($(this).data('counter') > 0) {
						total += $(this).data('counter');
						$(this).append('<span class="counter">' + $(this).data('counter') + '</span>');
					}
				});

				if(total > 0)
					$('.dropdown-toggle', this).append('<span class="counter">' + total + '</span>');
			});
			
			$('#subnav a').each(function() {
				if($(this).data('counter') > 0) {
					$(this).append('<span class="counter">' + $(this).data('counter') + '</span>');
				}
			});
		},
		add: function(href, count) {
			$('.dropdown-menu a[href*="'+href+'"]').data('counter', count);
			$('#subnav a[href*="'+href+'"]').data('counter', count);
			this.init();
		},
		remove: function(href) {
			$('.dropdown-menu a[href*="'+href+'"]').removeData('counter');
			$('#subnav a[href*="'+href+'"]').removeData('counter');
			this.init();
		}
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
		var body_id = $('body:first').attr('id');

		for (var i = 0; i < cms.init.callbacks.length; i++) {
			var rout_to_id = 'body_' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
	}
};

cms.ui.add('flags', function() {
	$('body').on('click', '.flags .label', function(e) {
		var $src = $(this).parent().data('target');
		if( ! $src ) $src = $(this).parent().prevAll(':input');
		
		var $container = $(this).parent();
		var $append = $container.data('append') == true;
		var $array = $container.data('array') == true;
		var $value = $(this).data('value');
		
		if($array) $value = $value.split(',');
		
		$('.label', $container).removeClass('label-success');
		$(this).addClass('label-success');

		if($append) {
			var $old_value = '';
			if($src.is(':input')) {
				$old_value += $src.val();
				$value = $old_value.length > 0 ? $old_value + ', ' + $value: $value;
				$src.val($value);
			}
			else {
				$old_value += $src.val();
				$value = $old_value.length > 0 ? $old_value + ', ' : $value;
				$src.text($value);
			}
		} else {
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
}).add('nav-counter', function() {

	$('.dropdown-submenu .caret').remove();
	cms.navigation.counter.init();

}).add('outline', function() {
	$('.widget')
		.addClass('outline_inner')
		.wrap('<div class="outline"></div>');
}).add('tabable', function() {
	if($('.tabbable').length > 0) {
		$('#content .widget-header').each(function(i) {
			if($(this).hasClass('widget-section')) {
				$('<li class="nav-section"><h5><i class="icon-arrow-down"></i> ' + $(this).text() + '</h5></li>').appendTo($('.tabbable .nav'));
			} else {
				var text = $(this).text();
				
				if($(this).data('icon')) {
					text = '<i class="icon-'+$(this).data('icon')+'"></i> ' + text;
				}
				
				$('<li class="tab-item"><a href="#tab' + i + '" data-toggle="tab">' + text + '</a></li>')
					.appendTo($('.tabbable .nav'));
				
				if($(this).hasClass('widget-header-onlytab'))
					$('<div class="tab-pane" id="tab' + i + '">' + $(this).next().html() + '</div>').appendTo($('.tabbable .tab-content'));
				else 
					$('<div class="tab-pane" id="tab' + i + '"><h2>'+$(this).text()+'</h2><hr />' + $(this).next().html() + '</div>').appendTo($('.tabbable .tab-content'));

				$(this).next().remove();
			}
			
			$(this).remove();
			
			
		});
		$('.tabbable .nav li a').on('click', function() {
			window.location.hash = $(this).attr('href');
		});

		if(window.location.hash.length > 0 && $('.tabbable .nav li a[href='+window.location.hash+']').length > 0) {
			$('.tabbable .nav li a[href='+window.location.hash+']').parent().addClass('active');
			$('.tabbable ' + window.location.hash).addClass('active');
		} else {
			$('.tabbable .nav li:first-child').addClass('active');
			$('.tabbable .tab-pane:first-child').addClass('active');
		}

		$('.tabbable .tab-pane').css({
			'min-height': cms.calculateContentHeight() - 130
		});
		
		$(window).resize(function() {
			$('.tabbable .tab-pane').css({
				'min-height': cms.calculateContentHeight() - 130
			});
		});
	}
	
	$(window).trigger('tabbable');
}).add('calculate_height', function() {
	cms.calculateContentHeight();

	$(window).resize(function() {
		cms.content_height = null;
		cms.calculateContentHeight();
	});
}).add('navbar', function() {
	
	var user_nav = $('#user_nav');
	var brand = $('.brand');

	var sw = $('#site_nav').outerWidth();
	var uw = user_nav.outerWidth();
	var bw = brand.outerWidth(true);

	$(window).resize(function() {
		calcNav();
	});
	
	function calcNav() {
		var nw = $('.navbar-inner').width();
		var w = nw - sw - uw - bw - 20;
		var wh = false;
		
		if(w <= 0) {
			$('.text, .divider-vertical', user_nav).hide();
			wh = true;
		} else {
			wh = false;
			$('.text, .divider-vertical', user_nav).show();
		}
		
		if(wh) {
			var unw = $(user_nav).outerWidth();
			var nw = w + uw - unw;
			
			if(w <= 0 && nw < 0) brand.hide();
			else brand.fadeIn();
				
		} else brand.fadeIn();
	};
	
	calcNav();
}).add('spoiler', function() {
	var icon_open = 'icon-chevron-up',
		icon_close = 'icon-chevron-down';

	$('.spoiler-toggle')
		.click(function () {
			var $self = $(this);
			var $spoiler_cont = $('.spoiler');
			
			if($(this).data('spoiler')) {
				$spoiler_cont = $($(this).data('spoiler'));
			}
		
			$spoiler_cont.slideToggle('fast', function() {
				var $icon = $self.find('.spoiler-toggle-icon');
				if($(this).is(':hidden')) {
					$icon.removeClass(icon_open).addClass(icon_close);
				} else {
					$icon.addClass(icon_open).removeClass(icon_close);
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
		.find('h3, h4')
		.append(' <i class="spoiler-toggle-icon '+icon_close+'"></i>');
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
	// Slug & metadata
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
		
        if ($slug_cont.val() == '')
            slugs[$slug_cont] = true;

        if (slugs[$slug_cont]) {
            var new_slug = cms.convertSlug($(this).val(), $separator);

            $slug_cont.val(new_slug);
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
		
		$(this).val(cms.convertSlug($(this).val(), $separator));
		slugs[$(this)] = false;

		if ($(this).val() == '')
			slugs[$(this)] = true;
	});
}).add('focus', function() {
	$('.focus').focus();
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
}).add('loader', function() {
    cms.loader.init();
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
			url: '/api-tags',
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
		var $self = $(this);
		var $buttons = $('button', $self)
			.attr('disabled', 'disabled');

		Api.post($self.attr('action'), $self.serialize(), function(response) {
			setTimeout(function() {
				$buttons.removeAttr('disabled');
			}, 5000);
		});

		return false;
	});
}).add('filemanager', function() {
	var input = $('input.input-filemanager:not(.init)')
		.addClass('init');
	
	$('<button class="btn" type="button"><i class="icon-folder-open"></i></button>')
		.insertAfter(input)
		.on('click', function() {
			cms.filemanager.open($(this).prev());
		});
		
	$('body').on('click', '.btn-filemanager', function() {
		var el = $(this).data('el');
		var type = $(this).data('type');

		if(!el) return false;
		
		cms.filemanager.open(el, type);
		return false;
	});
}).add('hotkeys', function(){
	$('*[hotkeys]').each(function() {
		var $self = $(this),
			$hotkeys = $self.attr('hotkeys'),
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
		} else if($self.hasClass('spoiler-toggle')) {
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
});

var Api = {
	_response: null,

	get: function(uri, data, callback) {
		this.request('GET', uri, data, callback);
		
		return this.response();
	},
	post: function(uri, data, callback) {
		this.request('POST', uri, data, callback);
		
		return this.response();
	},
	put: function(uri, data, callback) {
		this.request('PUT', uri, data, callback);
		
		return this.response();
	},
	'delete': function(uri, data, callback) {
		this.request('DELETE', uri, data, callback);
		
		return this.response();
	},
	build_url: function(uri) {
		uri = uri.replace('/' + ADMIN_DIR_NAME,'');
		
		if(uri.indexOf('-') == -1)
		{
			uri = '-' + uri;
		}
		else if(uri.indexOf('-') > 0 && uri.indexOf('/') == -1)
		{
			uri = '/' + uri;
		}
		
		if(uri.indexOf('/api') == -1)
		{
			uri = 'api' + uri;
		}
		
		return SITE_URL + uri;
	},
	request: function(method, uri, data, callback, show_loader) {
		url = Api.build_url(uri);
		
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
			beforeSend: function(){
				if(show_loader) cms.loader.show();
			},
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
		}).always(function() { 
			cms.loader.hide();
		});
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
    cms.init.run();
    cms.ui.init();
	
	parse_messages(MESSAGE_ERRORS, 'error');
	parse_messages(MESSAGE_SUCCESS);

	$.fn.check=function(){return this.each(function(){this.checked=true})}
	$.fn.uncheck=function(){return this.each(function(){this.checked=false})};
	$.fn.checked=function(){return this.attr("checked")}

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

	$.fn.serializeObject=function(){var e={};var t=this.serializeArray();$.each(t,function(){if(e[this.name]!==undefined){if(!e[this.name].push){e[this.name]=[e[this.name]]}e[this.name].push(this.value||"")}else{e[this.name]=this.value||""}});return e}
	
	$.fn.scrollTo=function(e,t,n){if(typeof t=="function"&&arguments.length==2){n=t;t=e}var r=$.extend({scrollTarget:e,offsetTop:50,duration:500,easing:"swing"},t);return this.each(function(){var e=$(this);var t=typeof r.scrollTarget=="number"?r.scrollTarget:$(r.scrollTarget);var i=typeof t=="number"?t:t.offset().top+e.scrollTop()-parseInt(r.offsetTop);e.animate({scrollTop:i},parseInt(r.duration),r.easing,function(){if(typeof n=="function"){n.call(this)}})})}
});

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