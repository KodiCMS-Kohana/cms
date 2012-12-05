$(function() {
	cms.models.plugin = Backbone.Model.extend({
		defaults: {
			title: '',
			description: '',
			version: '0.0.0',
			settings: false,
			status: false
		},

		toggleStatus: function() {
			this.save({status: !this.get("status")});
		},

		clear: function() {
			this.destroy();
		}
	});

});