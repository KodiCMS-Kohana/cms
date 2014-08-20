<div class="form-group form-inline">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __('Default value'); ?></label>
	<div class="col-md-9">
		<?php echo Form::input('default', $field->default, array(
			'class' => 'form-control datetimepicker', 'id' => 'primitive_default', 'size' => 10, 'maxlength' => 10,
			'autocomplete' => 'off'
		)); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('set_current', 1, $field->set_current == 1, array(
					'id' => 'set_current'
				)); ?> <?php echo __('Current datetime'); ?>
			</label>
		</div>
	</div>
</div>