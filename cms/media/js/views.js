$(function() {
	
	// Plugins
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
			this.model.toggleStatus();
		},

		// Re-render the titles of the todo item.
		render: function() {
			this.$el.toggleClass('success', this.model.get('status'));

			this.$el.html(this.template(this.model.toJSON()));
			
			var button = this.$el.find('button');

			if(this.model.get('status')) {
				button.addClass('btn-danger');
				button.html('<span class="icon icon-off icon-white" />');
			} else {
				button.html('<span class="icon icon-play-circle" />');
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
			this.collection.each(function(plugin) {
				this.addPlugin(plugin);
			}, this);
		},

		addPlugin: function(plugin) {
			var view = new cms.views.plugin({model: plugin});
			this.$el.append(view.render().el);
		}
	});
});