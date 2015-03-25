<?php echo Form::open(Route::get('api')->uri(array(
		'controller' => 'settings', 
		'backend' => ADMIN_DIR_NAME,
		'action' => 'save'
	)), array(
	'id' => 'settingForm', 
	'class' => 'form-horizontal form-ajax panel tabbable'
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel-heading" data-icon="info">
		<span class="panel-title"><?php echo __('Site information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="settingTitle"><?php echo __('Site title'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('setting[site][title]', Config::get('site', 'title'), array(
					'class' => 'form-control', 'id' => 'settingTitle'
				)); ?>
				<p class="help-block"><?php echo __('This text will be present at backend and can be used in frontend pages.'); ?></p>
			</div>

		</div>
		<div class="form-group">
			<label class="control-label col-md-3" for="settingDescription"><?php echo __('Site description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea('setting[site][description]', Config::get('site', 'description'), array(
					'id' => 'settingDescription', 'class' => 'form-control', 'rows' => 3
				)); ?>
			</div>
		</div>
	</div>

	<div class="panel-heading" data-icon="globe">
		<span class="panel-title"><?php echo __('Regional settings'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<?php echo Form::label('setting_default_locale', __('Default interface language'), array('class' => 'control-label col-md-4')); ?>
			<div class="col-md-3">
				<?php echo Form::select('setting[site][default_locale]', I18n::available_langs(), Config::get('site', 'default_locale'), array('id' => 'setting_default_locale', 'class' => 'form-control')); ?>
			</div>
		</div>

		<div class="form-group">
			<?php echo Form::label('setting_date_format', __('Date format'), array('class' => 'control-label col-md-4')); ?>
			<div class="col-md-3">
				<?php echo Form::select('setting[site][date_format]', $dates, Config::get('site', 'date_format'), array('id' => 'setting_date_format')); ?>
			</div>
		</div>
	</div>
	<div class="panel-heading" data-icon="cog">
		<span class="panel-title"><?php echo __('Debug'); ?></span>
	</div>
	<div class="panel-body">
		<div class="note note-warning">
			<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('For detailed profiling use Kohana::$enviroment = Kohana::DEVELOPMENT or SetEnv KOHANA_ENV DEVELOPMENT in .htaccess'); ?>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Profiling'); ?></label>
			<div class="col-md-2">
				<?php echo Form::select('setting[site][profiling]', Form::choices(), Config::get('site', 'profiling')); ?>
			</div>
		</div>
		<hr class="panel-wide" />
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Debug mode'); ?></label>
			<div class="col-md-2">
				<?php echo Form::select('setting[site][debug]', Form::choices(), Config::get('site', 'debug')); ?>
			</div>
		</div>
		<hr class="panel-wide" />
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Revision templates'); ?></label>
			<div class="col-md-2">
				<?php echo Form::select('setting[site][templates_revision]', Form::choices(), Config::get('site', 'templates_revision')); ?>
			</div>
			<div class="col-md-offset-3 col-md-9">
				<p class="help-block"><?php echo __('After save layouts or snippets create revision copy in logs directory'); ?></p>
			</div>
		</div>
	</div>
	<div class="panel-heading" data-icon="edit">
		<span class="panel-title"><?php echo __('Page settings'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Default page status'); ?> </label>
			<div class="col-md-9">
				<?php echo Form::select('setting[site][default_status_id]', $default_status_id, Config::get('site', 'default_status_id')); ?>
				<p class="help-block"><?php echo __('This status will be auto selected when page creating.'); ?></p>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Default HTML editor'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('setting[site][default_html_editor]', $html_editors, Config::get('site', 'default_html_editor')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Default Code editor'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('setting[site][default_code_editor]', $code_editors, Config::get('site', 'default_code_editor')); ?>
			</div>
		</div>

		<hr />

		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Find similar pages'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('setting[site][find_similar]', Form::choices(), Config::get('site', 'find_similar')); ?>
				<p class="help-block"><?php echo __('If requested page url is incorrect, then find similar page.'); ?></p>
			</div>
		</div>

		<hr />
		
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Check URL suffix'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('setting[site][check_url_suffix]', Form::choices(), Config::get('site', 'check_url_suffix', Config::NO)); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Check page date'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('setting[page][check_date]', Form::choices(), Config::get('site', 'check_page_date', Config::NO)); ?>
			</div>
		</div>
	</div>

	<div class="panel-heading" data-icon="hdd-o">
		<span class="panel-title"><?php echo __('Session settings'); ?></span>
	</div>
	<div class="panel-body">
		<?php if (ACL::check('system.session.clear') AND Session::$default == 'database'): ?>
		<div class="well">
			<?php echo UI::button(__('Clear user sessions'), array(
				'icon' => UI::icon('trash-o fa-lg'),
				'class' => 'btn-warning btn-lg',
				'data-api-url' => 'session.clear'
			)); ?>
		</div>
		<?php endif; ?>

		<div class="note note-warning">
			<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('The session storage driver can change in the config file (:path)', array(':path' => CFGFATH)); ?>
		</div>

		<div class="form-group">
			<?php echo Form::label('setting_session_storage', __('Session storage'), array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo Form::select('', array('native' => __('Native'), 'database' => __('Database'), 'cookie' => __('Cookie')), Session::$default, array(
					'id' => 'setting_session_storage', 'disabled', 'readonly'));?>
			</div>
		</div>
	</div>
	<?php Observer::notify( 'view_setting_plugins' ); ?>

	<div class="form-actions panel-footer">
		<?php echo Form::button('submit', UI::icon('check') . ' ' . __('Save settings'), array(
			'class' => 'btn btn-lg btn-primary',
			'data-hotkeys' => 'ctrl+s'
		)); ?>
	</div>
<?php Form::close(); ?>