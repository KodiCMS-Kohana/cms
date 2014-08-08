<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('Database update SQL script'); ?></h3>
	</div>

	<div class="widget-content widget-nopad">
		<?php if(!empty($actions)): ?>
		<textarea id="highlight_content" data-readonly="on" data-mode="mysql">
SET FOREIGN_KEY_CHECKS = 0;

<?php echo HTML::chars($actions); ?>

SET FOREIGN_KEY_CHECKS = 1;</textarea>
		<?php else: ?>
		<h2 style="padding: 20px 40px;"><?php echo __('There are no changes to the database structure'); ?></h2>
		<?php endif; ?>
	</div>
</div>

<script>
$(function() {
	function calculateEditorHeight() {
		var conentH = cms.content_height;
		var h = $('.widget-title').outerHeight(true) + $('.widget-header').outerHeight(true) + $('.form-actions').outerHeight(true) + 10;

		return conentH - h;
	}

	$('#highlight_content').on('filter:switch:on', function(e, editor) {
		cms.filters.exec('highlight_content', 'changeHeight', calculateEditorHeight);
	});

	$(window).resize(function() {
		$('#highlight_content').trigger('filter:switch:on')
	});
	
	$('#highlight_content').trigger('filter:switch:on')
})	
</script>