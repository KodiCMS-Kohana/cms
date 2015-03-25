<div class="form-group">
	<div class="col-md-offset-3 col-md-9">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('isreq', 1, (Arr::get($post_data, 'isreq', $field->isreq) == 1), array(
					'id' => 'isreq'
				)); ?> <?php echo __('Required'); ?>
			</label>
		</div>
	</div>
</div>