var Core = {
	global: {},
	init: {
		callbacks: [],
		add: function(rout, callback) {
			if (typeof(callback) != 'function')
				return false;

			if (typeof(rout) == 'object') {
				for (var i=0; i < rout.length; i++)
					Core.init.callbacks.push([rout[i], callback]);
			}
			else if (typeof(rout) == 'string')
				Core.init.callbacks.push([rout, callback]);
			else
				return false;
		},
		run: function(body_id) {
			if(!body_id)
				var body_id = $('body:first').attr('id').toString();

			for (var i=0; i < Core.init.callbacks.length; i++) {
				var rout_to_id = Core.init.callbacks[i][0];

				if (body_id == rout_to_id)
					Core.init.callbacks[i][1]();
			}
		}
	},

	ui: {
		callbacks: [],
		add: function(module, callback) {
			if (typeof(callback) != 'function')
				return false;

			Core.ui.callbacks.push([module, callback]);
		},
		init: function() {
			for (var i=0; i < Core.ui.callbacks.length; i++) {
				Core.ui.callbacks[i][1]();
			}
		}
	},

	filters: {
		filters: [],
		switchedOn: [],

		add: function( name, to_editor_callback, to_textarea_callback ) {	
			if( to_editor_callback == undefined || to_textarea_callback == undefined ) {
				Core.error('System try to add filter without required callbacks.', name, to_editor_callback, to_textarea_callback);
				return;
			}

			this.filters.push([ name, to_editor_callback, to_textarea_callback ]);
		},

		switchOn: function( textarea_id, filter ) {
			$( '#' + textarea_id ).css( 'display', 'block' );

			if( this.filters.length > 0 ) {
				this.switchOff( textarea_id );

				for( var i=0; i < this.filters.length; i++ ) {
					if( this.filters[i][0] == filter ) {
						try {
							this.filters[i][1]( textarea_id );
							this.switchedOn[textarea_id] = this.filters[i];
						} catch(e) {
							Core.error('Errors with filter switch on!', e);
						}
						break;
					}
				}
			}
		},

		switchOff: function( textarea_id ) {
			for( var key in this.switchedOn ) {
				if( textarea_id != undefined && key != textarea_id )
					continue;

				try {
					if( this.switchedOn[key] != undefined && this.switchedOn[key] != null && typeof(this.switchedOn[key][2]) == 'function' ) {
						this.switchedOn[key][2]( textarea_id );
					}
				} catch(e) {
					Core.error('Errors with filter switch off!', e);
				}

				if( this.switchedOn[key] != undefined || this.switchedOn[key] != null )
				{
					this.switchedOn[key] = null;
				}
			}
		}
	}
};

var Form = {
	is_init: false,
	submit: $.Deferred(),
	response: null,
	afterSubmit: function(r, e) {},
	init: function() {
		if(Form.is_init)
			return;

		Form.is_init = true;
	
		$('body')
			.undelegate('form.ajax')
			.delegate('form.ajax', 'submit', function() {
				Form.submit = $.Deferred();

				var $self = $(this),
				href = $self.attr('action');

				if(href.length == 0) {
					Core.error('Не указанна ссылка для отправки данных');
					return false;
				}

				$.post(href, $self.serialize(), function(response){
					if(response.validation) {
						for(msg in response.validation) {
							$.jGrowl(response.validation[msg], {
								life: 7000
							});
						}
					}

					if(response.redirect) {
						window.location = response.redirect
						return;
					}

					Form.response = response;

					if(response.status === true) {
						Form.submit.resolve(Form.response, $self);
					} else {
						Form.submit.reject(Form.response, $self);
					}

					Form.afterSubmit(Form.response, $self);

				}, 'json');
				return false;
			});
	}
};

Core.modal = {
	element: false,
	buttons: [],
	init: function(id) {
		this.element = $('#' + id);
	},
	title: function(title) {
		$('.modal-header h3', this.element).text(title);
		return this;
	},
	body: function(html) {
		if(!html)
			return $('.modal-body', this.element);

		$('.modal-body', this.element).html(html);
		return this;
	},
	footer: function(html) {
		$('.modal-footer', this.element).html(html);
		return this;
	},
	clear: function() {
		return this
			.title('')
			.body('')
			.footer('');
	},
	get: function()
	{
		return this.element;
	},
	options: function(options){
		this.element.modal(options);
		return this;
	},
	show: function() {
		this.element.modal('show');
		return this;
	},
	hide: function() {
		this.clear();
		this.element.modal('hide');
		return this;
	}
	
};

// Skip errors when no access to console
var console = console || {
	log: function(){}
};

var __ = function(str) {
	if (Core.translations.array[str] !== undefined)
		return Core.translations.array[str];
	else
		return str;
};

// Error
Core.error = function(msg, e) {
	$.jGrowl(msg);
};

// Run
jQuery(document).ready(function()
{
	if( $.browser.msie )
		$('html:first').addClass('msie');
	
	Core.ui.add('Form', Form.init);
	
	Core.ui.add('Button confirm', function() {
		$('.btn-confirm').click(function(){
			if (confirm(__('Are you sure?')))
				return true;

			return false;
		});
	});
	
	Core.ui.init();
	
	Core.modal.init('modal-window');

	// init
	Core.init.run();
	
	$(document).ajaxComplete(function(e, response) {
		try {
			var json = $.parseJSON(response.responseText);
			if(typeof(json.message) == 'string') 
			{
				$.jGrowl(json.message);
			}
			else if(typeof(json.message) == 'object')
			{
				for(msg in json.message)
				{
					$.jGrowl(json.message[msg]);
				}
			}

		} catch(e) {}
	});
});