<?php echo $content; ?>

<div class="panel-heading" data-icon="list">
	<span class="panel-title"><?php echo __('List settings'); ?></span>
</div>
<div class="panel-body ">
	<div class="form-group">
		<label class="control-label col-md-3" for="list_offset"><?php echo __('Number of documents to omit'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('list_offset', $widget->list_offset, array(
				'class' => 'form-control', 'id' => 'list_offset'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="list_size"><?php echo __('Number of documents per page'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('list_size', $widget->list_size, array(
				'class' => 'form-control', 'id' => 'list_size'
			)); ?>
		</div>
	</div>
</div>