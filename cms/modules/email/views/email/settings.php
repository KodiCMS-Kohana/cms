<script type="text/javascript">
var show_button = true;
var current_driver = '';
$(function() {
	init_test_email_button_vars();
	
	$('.panel')
		.on('change', '#email_driver', function() {
			change_email_driver($(this).val());
			test_email_button_visible();
		})
		.on('change', '#settingEncryption', function() {
			var $encryption = $(this).val();
			change_email_port($encryption);
			
			test_email_button_visible();
		});
	
	change_email_driver($('#email_driver').val());
	change_email_port($('#settingEncryption').val());

	$('body').on('click', '#send-test-email', function() {
		Api.post('email.send', {
			subject: __('Test email'),
			to: '<?php echo Config::get('email', 'default'); ?>',
			message: __('Test email'),
		}, function(response) {
			cms.messages.show(__('Test email') + (response.send ? __('sent successfully') : __('not sent')), response.send ? 'success' : 'error');
		});
		return false;
	});
	
	$('body').on('post:backend:api-settings.save', function() {
		init_test_email_button_vars();
		test_email_button_visible();
	});
});

function init_test_email_button_vars() {
	show_button = true;
	current_driver = $('#email_driver').val();
	test_email_button_visible();
}

function change_email_port($encryption) {
	var $port = $('#settingPort');
	switch($encryption){
		case 'ssl':
		case 'tls':
			$port.val(465);
			break;
		default: 
			$port.val(25);
			break;
	}
}

function change_email_driver(driver) {
	if(current_driver != driver)
		show_button = false;
	else
		show_button = true;

    $('fieldset').attr('disabled', 'disabled').hide();
	
	var $fieldset = $('fieldset#' + driver + '-driver-settings');
    $fieldset.removeAttr('disabled').show();
	cms.clear_error($fieldset, false);
}

function test_email_button_visible() {
	var $button = $('#send-test-email');
	var $tips = $('.test-email-message');
	if(show_button) {
		$button.show();
		$tips.hide();
	} else {
		$button.hide();
		$tips.show();
	}
}
</script>

<div class="panel-heading" data-icon="envelope">
	<span class="panel-title"><?php echo __('Email settings'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="settingDefault"><?php echo __('Default email address'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('setting[email][default]', Config::get('email', 'default'), array(
				'id' => 'settingDefault', 'class' => 'form-control'
			) ); ?>
		</div>
	</div>
	<div class="well">
		<div class="form-group">
			<?php echo Form::label('setting_driver', __('Email driver'), array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-6">
				<?php echo Form::select('setting[email][driver]', $drivers, Config::get('email', 'driver'), array('id' => 'email_driver')); ?>
				
				<p class="help-block test-email-message"><?php echo __('To send a test message, save the settings'); ?></p>
			</div>
			<div class="col-md-3 input-group-btn">
				<?php echo HTML::anchor('#', __('Send test email'), array(
					'class' => 'btn btn-primary', 'id' => 'send-test-email', 'data-icon' => 'envelope'
				)); ?>
			</div>
		</div>

		<fieldset id="sendmail-driver-settings">
			<hr class="panel-wide"/>
			<div class="form-group">
				<label class="control-label col-md-3" for="settingPath"><?php echo __('Executable path'); ?></label>
				<div class="col-md-9">
					<?php 
					$path = is_array(Arr::get($settings, 'options')) ? NULL : Arr::get($settings, 'options');
					echo Form::input('setting[email][options]', $path, array(
						'id' => 'settingPath', 'class' => 'form-control',
						'placeholder' => __('For example: :path', array(':path' => '/usr/sbin/sendmail'))
					) ); ?>

					<p class="help-block"><?php echo __('Where the sendmail program can be found, usually :path1 or :path2. :link', array(
						':path1' => '/usr/sbin/sendmail',
						':path2' => '/usr/lib/sendmail',
						':link' => HTML::anchor('http://www.php.net/manual/en/mail.configuration.php', 'www.php.net', array('target' => 'blank'))
					)); ?></p>
				</div>
			</div>
		</fieldset>

		<fieldset id="smtp-driver-settings">
			<hr class="panel-wide"/>
			<div class="form-group">
				<label class="control-label col-md-3" for="settingHost"><?php echo __('SMTP Host'); ?></label>
				<div class="col-md-9">
					<?php echo Form::input('setting[email][options][hostname]', Arr::path($settings, 'options.hostname'), array(
						'id' => 'settingHost', 'class' => 'form-control'
					)); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3" for="settingPort"><?php echo __('SMTP Port'); ?></label>
				<div class="col-md-2">
					<?php echo Form::input('setting[email][options][port]', Arr::path($settings, 'options.port', 25), array(
						'id' => 'settingPort', 'class' => 'form-control'
					)); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3" for="settingUsername"><?php echo __('SMTP Username'); ?></label>
				<div class="col-md-3">
					<?php echo Form::input('setting[email][options][username]', Arr::path($settings, 'options.username'), array(
						'id' => 'settingUsername', 'class' => 'form-control'
					) ); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3" for="settingPassword"><?php echo __('SMTP Password'); ?></label>
				<div class="col-md-3">
					<?php echo Form::password('setting[email][options][password]', Arr::path($settings, 'options.password'), array(
						'id' => 'settingPassword', 'class' => 'form-control'
					) ); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3" for="settingEncryption"><?php echo __('SMTP Encryption'); ?></label>
				<div class="col-md-2">
					<?php echo Form::select('setting[email][options][encryption]', array(
						NULL => 'Disable', 
						'ssl' => 'SSL', 
						'tls' => 'TLS'
					), Arr::path($settings, 'options.encryption'), array(
						'id' => 'settingEncryption'
					) ); ?>
				</div>
			</div>
		</fieldset>
	</div>
</div>