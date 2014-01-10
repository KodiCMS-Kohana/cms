<?php echo View::factory('widgets/widget/blocks/section', array(
	'widget' => $widget
)); ?>

<div class="widget-header">
	<h4><?php echo __('Properties'); ?></h4>
</div>
<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="list_offset"><?php echo __('Number of documents to omit'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'list_offset', $widget->list_offset, array(
				'class' => 'input-small', 'id' => 'list_offset'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="list_size"><?php echo __('Number of documents per page'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'list_size', $widget->list_size, array(
				'class' => 'input-small', 'id' => 'list_size'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="doc_uri"><?php echo __('Document page (URI)'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'doc_uri', $widget->doc_uri, array(
				'class' => 'input-xlarge', 'id' => 'doc_uri'
			) );
			?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="doc_id"><?php echo __('Identificator field'); ?></label>
		<div class="controls">
			<?php
			echo Form::textarea( 'doc_id', $widget->doc_id, array(
				'class' => 'input-xlarge', 'id' => 'doc_id', 'rows' => 4
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
			<label class="checkbox"><?php echo Form::checkbox('only_published', 1, $widget->only_published); ?> <?php echo __('Show only published documents'); ?></label>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/widget/blocks/fields', array(
	'widget' => $widget
)); ?>

<?php echo View::factory('widgets/widget/blocks/sorting', array(
	'widget' => $widget
)); ?>