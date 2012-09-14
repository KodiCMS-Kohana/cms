<div class="control-group">
	<label class="control-label" for="domain"><?php echo __( 'Domain' ); ?></label>
	<div class="controls">

		<?php echo Form::input('setting[domain]', $plugin->get('domain'), array(
			'class' => 'input-xlarge', 'id' => 'domain'
		)); ?>
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="check_url_suffix"><?php echo __('Check url suffix (<strong>:url_suffix</strong>)', array(':url_suffix' => URL_SUFFIX)); ?></label>
	<div class="controls">

		<label class="checkbox">
			<?php echo Form::checkbox('setting[check_url_suffix]', 'yes', $plugin->get('check_url_suffix') == 'yes', array(
				'class' => 'input-xlarge', 'id' => 'check_url_suffix'
			)); ?>
		</label>
	</div>
</div>