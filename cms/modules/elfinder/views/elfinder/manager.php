<script>
	function elfinderInit(id) {
		var elfinder = $('body').elfinder({
			lang: 'ru',
			url : '/api/elfinder',
			height: 595,
			getFileCallback : function(file) {
				if(_.isObject(id)) {
					id.val(file);
					window.top.$.fancybox.close();
				}
				else {
					if(window.top.cms.filters.insert(id, file))
						window.top.$.fancybox.close();
				}
				
            },
            resizable: false
		}).elfinder('instance');
	}
</script>