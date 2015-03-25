<script>
	$(function() {
		var elfinder = $('#elfinder').elfinder({
			lang: 'ru',
			url : Api.build_url('elfinder'),
            resizable: false,
			height: cms.content_height,
			uiOptions: {
				toolbar : [
					[<?php if (ACL::check('filemanager.mkdir')): ?>'mkdir'<?php endif; ?>, <?php if (ACL::check('filemanager.upload')): ?>'upload'<?php endif; ?>],
					['open', 'download'],
					['info'],
					['quicklook'],
					<?php if (ACL::check('filemanager.edit')): ?>['copy', 'cut', 'paste'],<?php endif; ?>
					<?php if (ACL::check('filemanager.delete')): ?>['rm'],<?php endif; ?>
					<?php if (ACL::check('filemanager.edit')): ?>['duplicate', 'rename', 'edit', 'resize'],<?php endif; ?>
					<?php if (ACL::check('filemanager.edit')): ?>['extract', 'archive'],<?php endif; ?>
					['search'],
					['view']
				]
			}
			<?php if (!ACL::check('filemanager.edit')): ?>,contextmenu: false<?php endif; ?>
			<?php if (!ACL::check('filemanager.upload')): ?>,dragUploadAllow: false<?php endif; ?>
			<?php if (!ACL::check('filemanager.edit')): ?>,allowShortcuts : false<?php endif; ?>
		}).elfinder('instance');
		
		$(window).resize(function() {
			var node = elfinder.getUI('node');
			var h = cms.content_height - 40;
			node.height(h);
			node.find('.elfinder-navbar')
				.add(node.find('.elfinder-cwd'))
				.add(node.find('.elfinder-cwd-wrapper'))
				.height(h - node.find('.elfinder-toolbar').height() - node.find('.elfinder-statusbar').height() )
		});
	});
</script>

<div id="elfinder"></div>