<script>
	function elfinderInit(id, type) {
		var elfinder = $('body').elfinder({
			lang: 'ru',
			url : '/api/elfinder',
			height: 595,
			getFileCallback : function(file) {

				if(type == 'codemirror') {
					window.top.cms.plugins.codemirror.editor.replaceSelection(file);
					window.top.$.fancybox.close();
					return;
				}

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
	};
</script>