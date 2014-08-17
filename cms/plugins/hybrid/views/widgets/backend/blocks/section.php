<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="ds_id"><?php echo __('Hybrid section'); ?></label>
		<div class="col-md-3">
			<?php echo Form::select( 'ds_id', Datasource_Data_Manager::get_all_as_options('hybrid'), $widget->ds_id, array(
				'id' => 'ds_id'
			)); ?>
		</div>
	</div>
</div>