cms.filemanager = {
	open: function(object, type) {

		return $.fancybox.open({
			href : BASE_URL + '/elfinder/',
			type: 'iframe'
		}, {
			autoSize: false,
			width: 1000,
			afterLoad: function() {
				this.content[0].contentWindow.elfinderInit(object, type)
			}
		});
	}
}

cms.ui.add('filemanager', function() {
	var input = $('input.input-filemanager:not(.init)')
		.addClass('init')
	
	$('<button class="btn" type="button"><i class="icon-folder-open"></i></button>')
		.insertAfter(input)
		.on('click', function() {
			cms.filemanager.open($(this).prev());
		});
		
	$('body').on('click', '.btn-filemanager', function() {
		var el = $(this).data('el');
		var type = $(this).data('type');

		if(!el) return false;
		
		cms.filemanager.open(el, type);
		return false;
	});
});

if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.elfinder = {
	init: function ()
	{
		var self = this;
		this.addBtnSeparator();
		this.addBtn('filemanager', 'elFinder', function() {
			cms.filemanager.open(self.$el.attr('id'));
		});
	},
};
