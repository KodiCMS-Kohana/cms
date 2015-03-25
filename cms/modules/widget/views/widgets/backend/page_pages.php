<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Page'); ?></label>
		<div class="col-md-4">
			<?php echo Form::select('page_id', $select, $widget->page_id, array('id' => 'select_page_id')); ?>	
		</div>
	</div>
	
	<hr class="panel-wide"/>

	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<div class="checkbox">
				<label><?php echo Form::checkbox('include_users_object', 1, $widget->include_users_object == 1); ?> <?php echo __('Include users object'); ?></label>
			</div>
		</div>
	</div>
</div>