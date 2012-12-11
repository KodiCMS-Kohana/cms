$(function() {
	cms.models.part = Backbone.Model.extend({
		urlRoot: '/api/part',
		defaults: {
			name: 'part',
			filter_id: '',
			page_id: PAGE_ID,
			content: '',
			is_protected: false
		},
		
		validate: function(attrs) {
			if(!$.trim(attrs.name))
				return 'Name must be set';
		},
		
		switchProtected: function() {
			this.save({is_protected: this.get('is_protected') == 1 ? 0 : 1});
		},
		
		toggleMinimize: function() {
			
		},
		
		changeFilter: function(filter_id) {
			if(this.get('filter_id') != filter_id) 
				this.save({filter_id: filter_id});

			cms.filters.switchOn( 'pageEditPartContent-' + this.get('name'), filter_id );
		},

		clear: function() {
			this.destroy();
		}
	});
	
	cms.collections.parts = Backbone.Collection.extend({
		url: '/api/part',

		model: cms.models.part,

		parse: function(response) {
			return response.data;
		},

		comparator: function(a) {
			return a.get('id');
		}
	});
	
	cms.views.part = Backbone.View.extend({
		tagName: 'div',

		template: _.template($('#part-body').html()),
		
		events: {
			'click .part-options-button': 'toggleOptions',
			'click .part-minimize-button': 'toggleMinimize',
			'change .item-filter': 'changeFilter',
			'change .is_protected': 'switchProtected',
			'click .item-remove': 'clear',
			'dblclick .part-name': 'editName',
			'blur .edit-name': 'closeEditName',
			'keypress .edit-name': 'updateOnEnter'
		},
		
		updateOnEnter: function(e) {
			if (e.keyCode == 13) this.close();
			this.input.val(this.input.val().replace(/[^a-z0-9\-\_]/, ''));
		},
	
		editName: function() {
			this.$el.addClass("editing");
			this.input.show().focus();
			this.$el.find('.part-name').hide();
		},
		
		closeEditName: function() {
			var value = $.trim(this.input.val());
			this.model.save({name: value});
			this.render();
		},

		toggleMinimize: function(e) {
			e.preventDefault();
			
			this.$el.find('.part-minimize-button i')
				.toggleClass('icon-chevron-up')
				.toggleClass('icon-chevron-down');
				
			this.$el.find('.item-filter-cont').toggle();
	
			this.$el.find('.part-textarea').slideToggle();
			this.model.toggleMinimize();
		},
		
		changeFilter: function() {
			this.model.changeFilter(this.$el.find('.item-filter').val());
		},
		
		toggleOptions: function(e) {
			e.preventDefault();

			this.$el.find('.part-options').toggle();
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
			
			this.input = this.$el.find('.edit-name').hide();
			
			if(this.model.get('is_protected') == 1) {
				this.$el.find('.is_protected').check();
			}
			
			this.changeFilter();

			return this;
		},

		// Remove the item, destroy the model.
		clear: function(e) {
			e.preventDefault();
			
			if (confirm(__('Remove part :name?', {":name": this.model.get('name')}))) this.model.clear();
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
			
			this.collection.on('add', this.render, this);
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
	
	cms.views.PartPanel = Backbone.View.extend({
		el: $("#pageEditPartsPanel"),
				
		initialize: function() {
			if(PAGE_ID == 0)
				this.$el.remove();
		},

		events: {
			'click #pageEditPartAddButton': 'createPart'
		},
		
		createPart: function(e) {
			e.preventDefault();

			this.model = new cms.models.part();
			if(this.collection.length == 0)
				this.model.set('name', 'body');

			this.model.save();
			this.collection.add(this.model);
		}
	});
	
	var PartCollection = new cms.collections.parts();
	var AppParts = new cms.views.parts({
		collection: PartCollection
	});
	var AppEdit = new cms.views.PartPanel({
		collection: PartCollection
	});
})