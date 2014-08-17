<div class="panel-heading" data-icon="envelope">
	<span class="panel-title"><?php echo __( 'Email queue settings' ); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Email queue batch size'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('setting[email_queue][batch_size]', (int) Config::get('email_queue', 'batch_size'), array(
				'class' => 'form-control'
			)); ?>
		</div>
		<div class="col-md-offset-3 col-md-9">
			<p class="help-block"><?php echo __('The number of emails to send out in each batch. This should be tuned to your servers abilities and the frequency of the cron.'); ?></p>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Email queue interval'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('setting[email_queue][interval]', (int) Config::get('email_queue', 'interval'), array(
				'class' => 'form-control'
			)); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Email queue max attempts'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('setting[email_queue][max_attempts]', (int) Config::get('email_queue', 'max_attempts'), array(
				'class' => 'form-control'
			)); ?>
		</div>
		<div class="col-md-offset-3 col-md-9">
			<p class="help-block"><?php echo __('The maximum number of attempts to send an email before giving up. An email may fail to send if the server is too busy, or there`s a problem with the email itself.'); ?></p>
		</div>
	</div>
</div>