<?php echo View::factory('helper/rows_only_value', array(
	'label' => __('Field options'),
	'field' => 'options',
	'data' => $field->options,
	'slugify' => FALSE
)); ?>

<hr />

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('custom_option', 1, $field->custom_option == 1, array(
					'id' => 'custom_option'
				)); ?> <?php echo __('Can use custom value'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('empty_value', 1, $field->empty_value == 1, array(
					'id' => 'empty_value'
				)); ?> <?php echo __('Can select empty value'); ?>
			</label>
		</div>
	</div>
</div>