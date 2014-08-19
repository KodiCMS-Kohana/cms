<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<?php if( ! $widget->ds_id ): ?>
<div class="note note-warning">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="doc_id_ctx"><?php echo __('Document ID (Ctx)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'doc_id_ctx', $widget->doc_id_ctx, array(
				'class' => 'input-small', 'id' => 'doc_id_ctx'
			) ); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
		</div>
	</div>
</div>
<?php endif; ?>