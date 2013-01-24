function strtr (str, from, to) {
	if (typeof from === 'object') {
		var cmpStr = '';
		for (var j=0; j < str.length; j++){
			cmpStr += '0';
		}
		var offset = 0;
		var find = -1;
		var addStr = '';
		for (fr in from) {
			offset = 0;
			while ((find = str.indexOf(fr, offset)) != -1){
				if (parseInt(cmpStr.substr(find, fr.length)) != 0){
					offset = find + 1;
					continue;
				}
				for (var k =0 ; k < from[fr].length; k++){
					addStr += '1';
				}
				cmpStr = cmpStr.substr(0, find) + addStr + cmpStr.substr(find + fr.length, cmpStr.length - (find + fr.length));
				str = str.substr(0, find) + from[fr] + str.substr(find + fr.length, str.length - (find + fr.length));
				offset = find + from[fr].length + 1;
				addStr = '';
			}
		}
		return str;
	}

	for(var i = 0; i < from.length; i++) {
		str = str.replace(new RegExp(from.charAt(i),'g'), to.charAt(i));
	}

	return str;
}


// Skip errors when no access to console
var console = console || {log:function () {}};


// Main object
var cms = {
	
	models: {},
	views: {},
	collections: {},
	event: _.extend({}, Backbone.Events),
	
	// Error
	error: function (msg, e) {
		this.message(msg, 'error')
		$.jGrowl(msg, {theme: 'alert alert-error'});
	},
		
	message: function(msg, type) {
		if(!type) type = 'success';
		window.top.$.jGrowl(decodeURI(msg), {theme: 'alert alert-' + type});
		
		if(type == 'error') {
			cms.error_field(name, msg)
		}
	},
	error_field: function(name, message) {
		return input = $('input[name*="' + name + '"]:not(:hidden)', $('.control-group:not(.error)'))
			.after('<span class="help-inline">' + message + '</span>')
			.parentsUntil( '.control-group' )
			.parent()
			.addClass('error');
	},
	// Convert slug
	convert_dict: {
		'ą':'a', 'ä':'a', 'č':'c', 'ę':'e', 'ė':'e', 'i':'i', 'į':'i', 'š':'s', 'ū':'u', 'ų':'u', 'ü':'u', 'ž':'z', 'ö':'o'},
	
	convertSlug: function (str) {
		return str.toString().toLowerCase()
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
			.replace(/[^a-zа-яіїє0-9\.\_]/g, '-')
			.replace(/ /g, '-')
			.replace(/\-{2,}/g, '-')
			.replace(/^-/, '');
		//.replace(/-$/,           '' ); // removed becouse this function used in #pageEditMetaSlugField
	},
	
	// Loader
	loader: {
		init: function () {
			$('body')
				.append('<div id="loader" class="loader"><span>' + __('Loading') + '</span></div>');
		},
		
		show: function () {
			$('#loader')
				.show()
				.animate({
					opacity:1
				}, 300);
		},
		
		hide: function () {
			$('#loader')
				.animate({
					opacity:0
				}, 300, function () {
					$(this).hide();
				});
		}
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
	
	// Filters
	filters: {
		// Filters array
		filters: [],
		switchedOn: {},
		
		// Add new filter
		add: function (name, to_editor_callback, to_textarea_callback) {
			if (to_editor_callback == undefined || to_textarea_callback == undefined) {
				cms.error('System try to add filter without required callbacks.', name, to_editor_callback, to_textarea_callback);
				return;
			}

			this.filters.push([ name, to_editor_callback, to_textarea_callback ]);
		},
		
		// Switch On filter
		switchOn: function (textarea_id, filter, params) {

			jQuery('#' + textarea_id).css('display', 'block');

			if (this.filters.length > 0) {
				// Switch off previouse editor with textarea_id
				cms.filters.switchOff(textarea_id);

				for (var i = 0; i < this.filters.length; i++) {
					if (this.filters[i][0] == filter) {
						try {
							// Call handler that will switch on editor
							this.filters[i][1](textarea_id, params);

							// Add editor to switchedOn stack
							cms.filters.switchedOn[textarea_id] = this.filters[i];
						}
						catch (e) {
							//frog.error('Errors with filter switch on!', e);
						}

						break;
					}
				}
			}
		},
		
		// Switch Off filter
		switchOff: function (textarea_id) {
			for (var key in cms.filters.switchedOn) {
				// if textarea_id param is set we search only one editor and switch off it
				if (textarea_id != undefined && key != textarea_id)
					continue;
				else
					textarea_id = key;

				try {
					if (cms.filters.switchedOn[key] != undefined && cms.filters.switchedOn[key] != null && typeof(cms.filters.switchedOn[key][2]) == 'function') {
						// Call handler that will switch off editor and showed up simple textarea
						cms.filters.switchedOn[key][2](textarea_id);
					}
				}
				catch (e) {
					//cms.error('Errors with filter switch off!', e);
				}

				// Remove editor from switchedOn editors stack
				if (cms.filters.switchedOn[key] != undefined || cms.filters.switchedOn[key] != null) {
					cms.filters.switchedOn[key] = null;
				}
			}
		}
	}
};

cms.addTranslation = function (obj) {
    for (var i in obj) {
        cms.translations[i] = obj[i];
    }
};

var __ = function (str, values) {
    if (cms.translations[str] !== undefined)
	{
		var str = cms.translations[str];
	}

    return values == undefined ? str : strtr(str, values);
};

cms.ui = {
    callbacks:[],
    add:function (module, callback) {
        if (typeof(callback) != 'function')
            return this;

        cms.ui.callbacks.push([module, callback]);
		
		return this;
    },
    init:function () {
        for (var i = 0; i < cms.ui.callbacks.length; i++) {
            cms.ui.callbacks[i][1]();
        }
    }
};

// Pages init
cms.init = {
	callbacks:[],
	add:function (rout, callback) {
		if (typeof(callback) != 'function')
			return false;

		if (typeof(rout) == 'object') {
			for (var i = 0; i < rout.length; i++)
				cms.init.callbacks.push([rout[i], callback]);
		}
		else if (typeof(rout) == 'string')
			cms.init.callbacks.push([rout, callback]);
		else
			return false;
	},
	run:function () {
		var body_id = $('body:first').attr('id').toString();
		
		

		for (var i = 0; i < cms.init.callbacks.length; i++) {
			var rout_to_id = 'body_' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
	}
};


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


    $('#pageMapItems .item-expander').live('click', function () {
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

                pages_ids = pages_ids.reverse();

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
}); // end init page/index


cms.init.add(['page_add', 'page_edit'], function () {
    // Datepicker
    $('#pageEditOptions input[name="page[published_on]"]').datepicker({
        // options
        dateFormat:'yy-mm-dd',

        // events
        onSelect:function (dateText, inst) {
            inst.input.val(dateText + ' 00:00:00');
        }
    });


    // Slug & metadata
    var slug_is_fresh = false;

    $('#pageEditMetaTitleField').focus();

    $('#pageEditMetaTitleField').keyup(function () {
        if ($('#pageEditMetaSlugField').val() == '')
            slug_is_fresh = true;

        if (slug_is_fresh) {
            var new_slug = cms.convertSlug($(this).val()).replace(/-$/, '');

            $('#pageEditMetaSlugField').val(new_slug);
        }

        $('#pageEditMetaBreadcrumbField').val($(this).val());
    });

    $('#pageEditMetaMoreButton').click(function () {
        $('#pageEditMetaMore').slideToggle();

        return false;
    });

    $('#pageEditMetaSlugField').keyup(function () {
        $(this).val(cms.convertSlug($(this).val()));
    });

//    $('#pageEditPartAddButton').click(function () {
//        var $form = $('<form class="dialog-form">' +
//            '<p><label>' + __('Page part name') + '</label><span><input class="input-text" type="text" name="part_name" /></span></p>' +
//            '</form>');
//
//        var buttons = {};
//
//        var buttons_add_action = function () {
//            var part_name = $form.find('input[name="part_name"]').val().toLowerCase()
//                .replace(/[^a-z0-9\-\_]/g, '_')
//                .replace(/ /g, '_')
//                .replace(/_{2,}/g, '_')
//                .replace(/^_/, '')
//                .replace(/_$/, '');
//
//            $form.find('input[name="part_name"]').val(part_name);
//
//            if (part_name == '') {
//                alert(__('Part name can\'t be empty! Use english chars a-z, 0-9 and _ (underline char).'));
//
//                $form.find('input[name="part_name"]').focus();
//            }
//            else {
//                var part_index = parseInt($('#pageEditParts .part:last').attr('id').substring(13)) + 1;
//
//                $(this).dialog('close');
//
//                cms.loader.show();
//
//                $.ajax({
//                    url:SITE_URL + ADMIN_DIR_NAME + '/page/add_part',
//                    type:'POST',
//                    dataType:'html',
//
//                    data:{
//                        name:part_name,
//                        index:part_index
//                    },
//                    success:function (html_data) {
//                        cms.loader.hide();
//
//                        $('#pageEditParts').append(html_data);
//                    },
//                    error:function () {
//                        cms.error('Ajax error!');
//                    }
//                });
//            }
//
//            return false;
//        };
//
//        buttons[__('Add')] = buttons_add_action;
//
//        buttons[__('Cancel')] = function () {
//            $(this).dialog('close');
//        };
//
//        $form.submit(buttons_add_action);
//
//        $form.dialog({
//            width:235,
//            modal:true,
//            buttons:buttons,
//            resizable:false,
//            title:__('Creating page part')
//        });
//
//        $form.find('input[name="part_name"]')
//            .keyup(function () {
//                $(this).val(cms.convertSlug($(this).val()).replace(/[^a-z0-9\-\_]/, ''));
//            });
//
//        return false;
//    });
}); // end init page/add, page/edit

cms.ui.add('btn-confirm', function() {
	$('.btn-confirm').live('click', function () {
		if (confirm(__('Are you sure?')))
			return true;

		return false;
	});
}).add('slug', function() {
	$('.slug')
		.keyup(function () {
			var val = $(this).val()
				.replace(/ /g, '_')
				.replace(/[^a-z0-9\_\-\.]/ig, '');

			$(this).val(val);
		});
}).add('focus', function() {
	$('.focus').focus();
}).add('popup', function() {
	$(".popup").fancybox({
		fitToView	: true,
		autoSize	: false,
		width		: '70%',
		height		: '90%',
		openEffect	: 'none',
		closeEffect	: 'none',
		beforeLoad: function() {
			this.href += '?type=iframe';
			this.title = $(this.element).html();
		},
		helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
	});

	var method = ACTION == 'add' ? 'put' : 'post';
	var $form_actions = $('.iframe .form-actions');
	
	$('.btn-save', $form_actions).on('click', function() {
		var $data = $('form').serializeObject();
		Api[method](CONTROLLER, $data);
		return false;
	});

	$('.btn-save-close', $form_actions).on('click', function() {
		var $data = $('form').serializeObject();
		Api[method](CONTROLLER, $data);
		window.top.$.fancybox.close();	
		return false;
	});

	$('.btn-close', $form_actions).on('click', function() {
		window.top.$.fancybox.close();
		return false;
	})
})

var Api = {
	_response: null,

	get: function(uri, data) {
		this.request('GET', uri, data);
		
		return this.response();
	},
	post: function(uri, data) {
		this.request('POST', uri, data);
		
		return this.response();
	},
	put: function(uri, data) {
		this.request('PUT', uri, data);
		
		return this.response();
	},

	'delete': function(uri, data) {
		this.request('DELETE', uri, data);
		
		return this.response();
	},

	request: function(method, uri, data) {
		uri = '/api/' + uri;
		
		$.ajaxSetup({
			contentType : 'application/json',
			processData : false
		});
	
		if(typeof(data) == 'object') data = JSON.stringify(data);

		$.ajax({
			type: method,
			url: uri,
			data: data,
			dataType: 'json',
			success: function(response) {
				if(response.code != 200) return Api.exception(response);
				
				if (response.message) {
					cms.message(response.message);
				}
	
				if(response.redirect) {
					$.get(window.top.CURRENT_URL, function(resp){
						window.top.$('#content').html(resp);
						
						window.location = response.redirect + '?type=iframe';
					});
				}
				this._response = response;
			}
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
}

// Run
jQuery(document).ready(function () {
    // loader
    cms.loader.init();

    // messages
    cms.messages.init();

    // init
    cms.init.run();
    cms.ui.init();
	
	for(error in MESSAGE_ERRORS) {
		cms.message(MESSAGE_ERRORS[error], 'error');
		cms.error_field(error, MESSAGE_ERRORS[error]);
	}
	
	for(text in MESSAGE_SUCCESS) {
		cms.message(MESSAGE_SUCCESS[text]);
	}
});

// Checkbox status
$.fn.check = function () {
    return this.each(function () {
        this.checked = true;
    });
};

$.fn.uncheck = function () {
    return this.each(function () {
        this.checked = false;
    });
};

$.fn.checked = function () {
    return this.attr('checked');
};

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

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};