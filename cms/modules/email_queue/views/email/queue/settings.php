<div class="widget-header">
	<h3><?php echo __( 'Email queue settings' ); ?></h3>
</div>
<div class="widget-content">
	<div class="control-group">
		<label class="control-label"><?php echo __( 'Email queue batch size' ); ?></label>
		<div class="controls">
			<?php echo Form::input('setting[email_queue][batch_size]', (int) Config::get('email_queue', 'batch_size'), array(
				'class' => 'input-mini'
			)); ?>

			<div class="help-block"><?php echo __('The number of emails to send out in each batch. This should be tuned to your servers abilities and the frequency of the cron.'); ?></div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"><?php echo __( 'Email queue interval' ); ?></label>
		<div class="controls">
			<?php echo Form::input('setting[email_queue][interval]', (int) Config::get('email_queue', 'interval'), array(
				'class' => 'input-mini'
			)); ?>
		</div>
	</div>

	<hr />

	<div class="control-group">
		<label class="control-label"><?php echo __( 'Email queue max attempts' ); ?></label>
		<div class="controls">
			<?php echo Form::input('setting[email_queue][max_attempts]', (int) Config::get('email_queue', 'max_attempts'), array(
				'class' => 'input-mini'
			)); ?>

			<div class="help-block"><?php echo __('The maximum number of attempts to send an email before giving up. An email may fail to send if the server is too busy, or there`s a problem with the email itself.'); ?></div>
		</div>
	</div>
</div>