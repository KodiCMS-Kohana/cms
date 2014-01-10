<?php echo View::factory('widgets/widget/blocks/section', array(
	'widget' => $widget
)); ?>

<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="doc_id"><?php echo __('Document ID field'); ?></label>
		<div class="controls">
			<?php
			echo Form::select('doc_id', $widget->get_doc_ids(), $widget->doc_id, array(
				'class' => 'input-xlarge'
			));
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="docs_uri"><?php echo __('Documents (URI)'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'docs_uri', $widget->docs_uri, array(
				'class' => 'input-xlarge', 'id' => 'docs_uri'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('throw_404', 1, $widget->throw_404); ?> <?php echo __('Generate error 404 when page has no content'); ?></label>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<label class="checkbox"><?php echo Form::checkbox('crumbs', 1, $widget->crumbs); ?> <?php echo __('Change Bread Crumbs'); ?></label>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/widget/blocks/fields', array(
	'widget' => $widget
)); ?>