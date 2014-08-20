<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox( 'only_current', 1, ($field->only_current == 1), array(
					'id' => 'only_current'
				)); ?> <?php echo __('Only current user'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox( 'set_current', 1, ($field->set_current == 1), array(
					'id' => 'set_current'
				)); ?> <?php echo __('Set current user on create document'); ?>
			</label>
		</div>
	</div>
</div>