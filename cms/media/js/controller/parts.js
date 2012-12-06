$(function() {
	cms.models.part = Backbone.Model.extend({
		urlRoot: SITE_URL + ADMIN_DIR_NAME + '/part/index/',
		defaults: {
			name: '',
			filter_id: '',
			page_id: PAGE_ID,
			content: '',
			is_protected: false
		},
		
		switchProtected: function() {
			this.save({is_protected: this.get('is_protected') == 1 ? 0 : 1});
		},
		
		changeFilter: function(filter_id) {
			if(this.get('filter_id') != filter_id) 
				this.save({filter_id: filter_id});
			
			if(filter_id )
				cms.filters.switchOn( 'pageEditPartContent-' + this.get('name'), filter_id );
		},

		clear: function() {
			this.destroy();
		}
	});
	
	cms.collections.parts = Backbone.Collection.extend({
		url: SITE_URL + ADMIN_DIR_NAME + '/part/index/',

		model: cms.models.part,

		parse: function(response) {
			return response.data;
		},

		comparator: function(a) {
			return a.get('name');
		}
	});
	
	cms.views.part = Backbone.View.extend({
		tagName: 'div',

		template: _.template($('#part-body').html()),
		
		events: {
			'click .part-options-button': 'toggleOptions',
			'change .item-filter': 'changeFilter',
			'change .is_protected': 'switchProtected',
			'click .item-remove': 'clear'
		},
		
		changeFilter: function() {
			this.model.changeFilter(this.$el.find('.item-filter').val());
		},
		
		toggleOptions: function(e) {
			e.preventDefault();

			this.$el.find('.part-options').slideToggle();
		},
		
		switchProtected: function() {
			this.model.switchProtected();
		},

		initialize: function() {
			this.model.on('add', this.render, this);
			this.model.on('destroy', this.remove, this);
		},

		// Re-render the titles of the todo item.
		render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			
			if(this.model.get('is_protected') == 1) {
				this.$el.find('.is_protected').check();
			}

			return this;
		},

		// Remove the item, destroy the model.
		clear: function() {
			if (confirm(__('Are you sure?'))) this.model.clear();
		}
	});
	
	cms.views.parts = Backbone.View.extend({

		el: $("#pageEditParts"),

		initialize: function() {
			var $self = this;
			this.collection.fetch({
				data: {page_id: PAGE_ID},
				success: function () {
					$self.render();
				}
			});
			
			cms.event.on('part::add', this.addPart, this);
		},

		render: function() {
			this.$el.empty();

			this.collection.each(function(part) {
				this.addPart(part);
			}, this);
		},
		
		clear: function() {
			this.$el.empty();
		},

		addPart: function(part) {
			var view = new cms.views.part({model: part});
			this.$el.append(view.render().el);
			view.changeFilter();
		}
	});
	
	cms.views.newPart = Backbone.View.extend({
		el: $("#pageEditPartAddButton"),

		events: {
			'click': 'createPart'
		},
		
		createPart: function(e) {
			e.preventDefault();

			this.model = new cms.models.part();
			if(this.collection.length == 0)
				this.model.set('name', 'body');

			this.model.save();
			cms.event.trigger('part::add', this.model);
		}
	});
	
	var PartCollection = new cms.collections.parts();
	var AppParts = new cms.views.parts({
		collection: PartCollection
	});
	var AppEdit = new cms.views.newPart({
		collection: PartCollection
	});
})