<script>
	$(function() {
		var elfinder = $('#elfinder').elfinder({
			lang: 'ru',
			url : '/api-elfinder',
            resizable: false,
			height: calculateContentHeight()
		}).elfinder('instance');
		
		$(window).resize(function() {
			var node = elfinder.getUI('node');
			var h = calculateContentHeight();
			node.height(h);
			node.find('.elfinder-navbar')
				.add(node.find('.elfinder-cwd'))
				.add(node.find('.elfinder-cwd-wrapper'))
				.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height() )
		});
	});
</script>

<div id="elfinder"></div>