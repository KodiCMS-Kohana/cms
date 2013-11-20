<?php echo form::open(Route::url('backend', array('controller' => 'email')), array(
	'id' => 'settingForm', 'class' => 'form-horizontal'
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>

<div class="widget">
	<div class="widget-header">
		<h3><?php echo __( 'Email settings' ); ?></h3>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label" for="settingDefault"><?php echo __( 'Default email address' ); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[email][default]', Config::get('email', 'default'), array(
					'id' => 'settingDefault'
				) ); ?>
			</div>
		</div>
		<hr />
		<div class="control-group">
			<?php echo Form::label('setting_driver', __('Email driver'), array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo Form::select('setting[email][driver]', $drivers, Config::get('email', 'driver'), array('id' => 'smtp_driver')); ?>
			</div>
		</div>
	</div>
	
	<fieldset class="widget-content widget-no-border-radius" id="sendmail-driver-settings">
		<div class="control-group">
			<label class="control-label" for="settingPath"><?php echo __( 'Executable path' ); ?></label>
			<div class="controls">
				<?php 
				$path = is_array(Arr::get($settings, 'options')) ? NULL : Arr::get($settings, 'options');
				echo Form::input('setting[email][options]', $path, array(
					'id' => 'settingPath', 'class' => Bootstrap_Form_Element_Input::XXLARGE
				) ); ?>
				
				<p class="help-block"><?php echo __( 'executable path, with -bs or equivalent attached' ); ?></p>
			</div>
		</div>
	</fieldset>
	
	<fieldset class="widget-content widget-no-border-radius" id="smtp-driver-settings">
		<div class="control-group">
			<label class="control-label" for="settingHost"><?php echo __( 'STMP Host' ); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[email][options][hostname]', Arr::path($settings, 'options.hostname'), array(
					'id' => 'settingHost', 'class' => Bootstrap_Form_Element_Input::XXLARGE
				) ); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="settingPort"><?php echo __( 'STMP Port' ); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[email][options][port]', Arr::path($settings, 'options.port', 25), array(
					'id' => 'settingPort', 'class' => Bootstrap_Form_Element_Input::MINI
				) ); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="settingUsername"><?php echo __( 'STMP Username' ); ?></label>
			<div class="controls">
				<?php echo Form::input('setting[email][options][username]', Arr::path($settings, 'options.username'), array(
					'id' => 'settingUsername'
				) ); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="settingPassword"><?php echo __( 'STMP Password' ); ?></label>
			<div class="controls">
				<?php echo Form::password('setting[email][options][username]', Arr::path($settings, 'options.password'), array(
					'id' => 'settingPassword'
				) ); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="settingEncryption"><?php echo __( 'SMTP Encryption' ); ?></label>
			<div class="controls">
				<?php echo Form::select( 'setting[email][options][encryption]', array(NULL => 'Disable', 'ssl' => 'SSL', 'tls' => 'TLS'), Arr::path($settings, 'options.encryption'), array(
					'id' => 'settingEncryption'
				) ); ?>
			</div>
		</div>
	</fieldset>
	<div class="form-actions widget-footer">
		<?php echo Form::button( 'submit', UI::icon( 'ok' ) . ' ' . __( 'Save settings' ), array(
			'class' => 'btn btn-large'
		) ); ?>
	</div>
</div>
<?php Form::close(); ?>