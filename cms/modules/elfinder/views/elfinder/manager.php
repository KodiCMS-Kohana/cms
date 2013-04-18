<script>
	$(function() {
		var elfinder = $('body').elfinder({
			lang: 'ru',
			url : '/api/elfinder',
			height: 595,
			getFileCallback : function(file) {
				if(window.top.cms.filters.insert($.QueryString['id'], file))
					window.top.$.fancybox.close();
				
            },
            resizable: false
		}).elfinder('instance');
	});
</script>