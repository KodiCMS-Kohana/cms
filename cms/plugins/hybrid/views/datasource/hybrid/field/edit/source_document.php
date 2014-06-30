<div class="control-group">
	<label class="control-label" for="document_type"><?php echo __( 'Datasource' ); ?></label>
	<div class="controls">
		<?php echo Form::select( 'from_ds', Datasource_Data_Manager::get_all_as_options('hybrid'), $field->from_ds); ?>
	</div>
</div>

<hr />

<div class="control-group">
	<label class="control-label" for="one_to_one"><?php echo __('One to one relation'); ?></label>
	<div class="controls">
		<div class="checkbox">
			<?php echo Form::checkbox( 'one_to_one', 1, ($field->one_to_one == 1), array(
				'id' => 'one_to_one'
			)); ?>
		</div>
	</div>
</div>