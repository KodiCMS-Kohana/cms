if (!RedactorPlugins) var RedactorPlugins = {};
RedactorPlugins.typograf = {
	init: function ()
	{
		var self = this;
		this.addBtnAfter('formatting', 'typograf', 'Typograf', function() {
			Api.get('typograf', {text: $.trim(self.$editor.html())}, function(response) {
				if(response.response) {
					self.$editor.html(response.response)
					self.syncCode();
				}
			});
		});
	},
};