$(function() {
	
	cms.models.page = Backbone.Model.extend({
		urlRoot: '/api/page',
		
		defaults: {
			slug: '',
			parent_id: 0,
			position: 0
		},
	});

	cms.collections.pages = Backbone.Collection.extend({
		url: '/api/page',
		model: cms.models.page,
		
		parse: function(response) {
			return response.data;
		},
			
		comparator: function(a) {
			return a.get('position');
		}
	});
	
});