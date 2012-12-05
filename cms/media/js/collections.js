$(function() {
	cms.collections.plugins = Backbone.Collection.extend({
		url: SITE_URL + ADMIN_DIR_NAME + '/plugins',

		model: cms.models.plugin,

		parse: function(response) {
			return response.data;
		},

		comparator: function(plugin) {
			return !plugin.get('status');
		}
	});
})