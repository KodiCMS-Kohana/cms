<div class="control-group">
	<label class="control-label" for="array_type"><?php echo __( 'Datasource' ); ?></label>
	<div class="controls">
		<?php echo Form::select( 'from_ds', Datasource_Data_Manager::get_all_as_options('hybrid'), $field->from_ds); ?>
	</div>
</div>