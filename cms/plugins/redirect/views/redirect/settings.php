<div class="page-header">
	<h1><?php echo __('Domain redirect settings'); ?></h1> 
</div>

<?php echo Form::open('redirect/settings', array(
	'class' => 'form-horizontal', 'method' => 'post'
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>
	
	<div class="control-group">
		<label class="control-label" for="domain"><?php echo __( 'Domain' ); ?></label>
		<div class="controls">

			<?php echo Form::input('setting[domain]', Arr::get($settings, 'domain'), array(
				'class' => 'input-xlarge', 'id' => 'domain'
			)); ?>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="check_url_suffix"><?php echo __('Check url suffix (<strong>:url_suffix</strong>)', array(':url_suffix' => URL_SUFFIX)); ?></label>
		<div class="controls">

			<label class="checkbox">
				<?php echo Form::checkbox('setting[check_url_suffix]', 'yes', Arr::get($settings, 'check_url_suffix') == 'yes', array(
					'class' => 'input-xlarge', 'id' => 'check_url_suffix'
				)); ?>
			</label>
		</div>
	</div>

	<div class="form-actions">
	<?php echo UI::button(__('Save setting'), array(
		'class' => 'btn btn-large btn-success', 'icon' => UI::icon('ok'),
		'name' => 'submit'
	)); ?>
	</div>
<?php echo Form::close(); ?>