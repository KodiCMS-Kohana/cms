<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Page'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('page_id',  $select, $widget->page_id, array('id' => 'select_page_id')); ?>	
		</div>
	</div>
</div>