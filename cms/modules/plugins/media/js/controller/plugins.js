cms.init.add('plugins_repo', function () {
	cms.models.plugin_repo = Backbone.Model.extend({
		defaults: {
			name: '',
			description: '',
			is_installed: false,
			is_new: false,
			url: '',
			archive: '',
			last_update: '',
			plugin_path: '',
			stars: 0,
			watchers: 0,
			version: '1.0.0',
			required_cms_version: '0.0.0',
			is_installable: true
		},

		clear: function() {
			this.destroy();
		}
	});
	
	cms.collections.plugins_repo = Backbone.Collection.extend({
	    url: Api.build_url('plugins.repositories_list'),

		model: cms.models.plugin_repo,

		parse: function(response) {
			return response.response;
		},
		
		comparator: function(a) {
			return a.get('is_installed');
		}
	});
	
	cms.views.plugin_repo = Backbone.View.extend({
		tagName: 'tr',
		template: _.template($('#plugin-item').html()),
		initialize: function() {
			this.model.on('destroy', this.remove, this);
		},
		// Re-render the titles of the todo item.
		render: function() {
			this.$el.toggleClass('info', this.model.get('is_new'));
			this.$el.toggleClass('success', this.model.get('is_installed'));
			this.$el.toggleClass('warning', !this.model.get('is_installable'));

			this.$el.html(this.template(this.model.toJSON()));
			return this;
		},

		// Remove the item, destroy the model.
		clear: function() {
			this.model.clear();
		}
	});
	
	cms.views.plugins_repo = Backbone.View.extend({

		el: $("#pluginsMap tbody"),

		initialize: function() {
			var $self = this;
			this.collection = new cms.collections.plugins_repo();
			this.collection.fetch({
				success: function () {
					$self.render();
				}
			});
		},

		render: function() {
			this.clear();

			this.collection.each(function(plugin_repo) {
				this.addPlugin(plugin_repo);
			}, this);
		},
		
		clear: function() {
			this.$el.empty();
		},

		addPlugin: function(plugin_repo) {
			var view = new cms.views.plugin_repo({model: plugin_repo});
			this.$el.append(view.render().el);
		}
	});
	
	var AppPlugins = new cms.views.plugins_repo();
})

cms.init.add('plugins_index', function () {
	cms.models.plugin = Backbone.Model.extend({
		defaults: {
			title: '',
			description: '',
			version: '',
			settings: false,
			installed: false
		},

		toggleStatus: function(remove_data) {
			if(!remove_data) remove_data = false;
			this.save({installed: ! this.get("installed"), remove_data: remove_data});
		},

		clear: function() {
			this.destroy();
		}
	});
	
	cms.collections.plugins = Backbone.Collection.extend({
	    url: Api.build_url('plugins'),

		model: cms.models.plugin,

		parse: function(response) {
			return response.response;
		},
		
		// Filter down the list of all todo items that are finished.
		activated: function() {
			return this.filter(function(plugin){ return plugin.get('status'); });
		},

		comparator: function(a) {
			return !a.get('installed');
		}
	});

	cms.views.plugin = Backbone.View.extend({
		tagName: 'tr',

		template: _.template($('#plugin-item').html()),

		events: {
			"click .change-status": "toggleStatus"
		},

		initialize: function() {
			this.model.on('change', this.render, this);
			this.model.on('destroy', this.remove, this);
		},

		toggleStatus: function() {
			remove_data = false;
			if( this.model.get('installed') && confirm(__('Remove database data')))
				remove_data = true;
			
			this.model.toggleStatus(remove_data);
		},

		// Re-render the titles of the todo item.
		render: function() {
			this.$el.toggleClass('success', this.model.get('installed'));
			this.$el.toggleClass('danger', !this.model.get('is_installable'));
			
			this.$el.html(this.template(this.model.toJSON()));
			
			var button = this.$el.find('button');

			if(this.model.get('installed')) {
				button.addClass('btn-danger');
				button.html('<span class="fa fa-power-off" />');
			} else {
				button.html('<span class="fa fa-play-circle" />');
			}

			return this;
		},

		// Remove the item, destroy the model.
		clear: function() {
			this.model.clear();
		}
	});

	cms.views.plugins = Backbone.View.extend({

		el: $("#pluginsMap tbody"),

		initialize: function() {
			var $self = this;
			this.collection = new cms.collections.plugins();
			this.collection.fetch({
				success: function () {
					$self.render();
				}
			});
		},

		render: function() {
			this.clear();

			this.collection.each(function(plugin) {
				this.addPlugin(plugin);
			}, this);
		},
		
		clear: function() {
			this.$el.empty();
		},

		addPlugin: function(plugin) {
			var view = new cms.views.plugin({model: plugin});
			this.$el.append(view.render().el);
		}
	});
	
	var AppPlugins = new cms.views.plugins();
});
