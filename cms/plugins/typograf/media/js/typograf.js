if (!RedactorPlugins) var RedactorPlugins = {};
RedactorPlugins.typograf = {
	init: function ()
	{
		var self = this;
		this.addBtnAfter('formatting', 'typograf', 'Typograf', function() {
			Api.get('typograf', {text: $(self.getParentNode()).html()}, function(response) {
				if(response.response)
					$(self.getParentNode()).html(response.response);
			});
		});
	},
};