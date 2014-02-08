<?php echo View::factory('widgets/backend/blocks/section', array(
	'widget' => $widget
)); ?>

<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="doc_id_field"><?php echo __('Document ID field'); ?></label>
		<div class="controls">
			<?php
			echo Form::select('doc_id_field', $widget->get_doc_ids(), $widget->doc_id_field, array(
				'class' => 'input-xlarge'
			));
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="doc_id_ctx"><?php echo __('Document id (Ctx)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'doc_id_ctx', $widget->doc_id_ctx, array(
				'class' => 'input-small', 'id' => 'doc_id_ctx'
			) ); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change bread crumbs'); ?></label>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/backend/blocks/fields', array(
	'widget' => $widget
)); ?>