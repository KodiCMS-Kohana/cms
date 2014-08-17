<div class="form-group">
	<label class="control-label col-md-3" for="array_type"><?php echo __( 'Datasource' ); ?></label>
	<div class="col-md-9">
		<?php echo Form::select( 'from_ds', Datasource_Data_Manager::get_all_as_options('hybrid'), $field->from_ds); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<div class="col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox( 'one_to_many', 1, ($field->one_to_many == 1), array(
					'id' => 'one_to_many'
				)); ?>
				<?php echo __('Remove the related documents when deleting a document'); ?>
			</label>
		</div>
	</div>
</div>