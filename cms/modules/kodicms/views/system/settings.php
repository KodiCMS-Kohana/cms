<?php echo form::open(Route::get('api')->uri(array(
		'controller' => 'settings', 
		'backend' => ADMIN_DIR_NAME,
		'action' => 'save'
	)), array(
	'id' => 'settingForm', 'class' => 'form-horizontal form-ajax'
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel">
		<div class="panel-heading" data-icon="info">
			<span class="panel-title"><?php echo __( 'Site information' ); ?></span>
		</div>
		<div class="panel-body">
			<div class="control-group">
				<label class="control-label title" for="settingTitle"><?php echo __( 'Site title' ); ?></label>
				<div class="controls">
					<?php echo Form::input( 'setting[site][title]', Config::get('site', 'title' ), array(
						'class' => 'input-title input-block-level', 'id' => 'settingTitle'
					) ); ?>
					<p class="help-block"><?php echo __( 'This text will be present at backend and can be used in frontend pages.' ); ?></p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="settingDescription"><?php echo __( 'Site description' ); ?></label>
				<div class="controls">
					<?php echo Form::textarea( 'setting[site][description]', Config::get('site', 'description' ), array(
						'id' => 'settingDescription', 'class' => 'input-block-level', 'rows' => 3
					) ); ?>
				</div>
			</div>
		</div>
		<div class="panel-heading" data-icon="globe">
			<span class="panel-title"><?php echo __( 'Site settings' ); ?></span>
		</div>
		<div class="panel-body">
			<div class="control-group">
				<?php echo Form::label('setting_default_locale', __('Default interface language'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::select('setting[site][default_locale]', I18n::available_langs(), Config::get('site', 'default_locale'), array('id' => 'setting_default_locale')); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo Form::label('setting_date_format', __('Date format'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::select('setting[site][date_format]', $dates, Config::get('site', 'date_format'), array('id' => 'setting_date_format')); ?>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Default backend section' ); ?></label>
				<div class="controls">
					<?php echo Form::select('setting[site][default_tab]', $site_pages, Config::get('site', 'default_tab')); ?>
					<p class="help-block"><?php echo __( 'This allows you to specify which section you will see by default after login.' ); ?></p>
				</div>
			</div>
			<span class="panel-title"><?php echo __( 'Debug' ); ?></span>
			<hr />
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Profiling' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][profiling]', Form::choices(), Config::get('site', 'profiling' )); ?>
					<p class="help-block"><?php echo __('For detailed profiling use Kohana::$enviroment = Kohana::DEVELOPMENT or SetEnv KOHANA_ENV DEVELOPMENT in .htaccess'); ?></p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Debug mode' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][debug]', Form::choices(), Config::get('site', 'debug' )); ?>
				</div>
			</div>
			<hr />
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Revision templates' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][templates_revision]', Form::choices(), Config::get('site', 'templates_revision' )); ?>
					<p class="help-block"><?php echo __( 'After save layouts or snippets create revision copy in logs directory' ); ?></p>
				</div>
			</div>
			<span class="panel-title"><?php echo __( 'Design' ); ?></span>
			<hr />
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Show breadcrumbs' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][breadcrumbs]', Form::choices(), Config::get('site', 'breadcrumbs', Config::NO )); ?>
				</div>
			</div>
		</div>
		<div class="panel-heading" data-icon="edit">
			<span class="panel-title"><?php echo __( 'Page settings' ); ?></span>
		</div>
		<div class="panel-body">
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Default page status' ); ?> </label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][default_status_id]', $default_status_id, Config::get('site', 'default_status_id' )); ?>
					<p class="help-block"><?php echo __( 'This status will be autoselected when page creating.' ); ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo __( 'Default filter' ); ?></label>
				<div class="controls">
					<?php echo Form::select('setting[site][default_filter_id]', $filters, Config::get('site', 'default_filter_id' )); ?>
					<p class="help-block"><?php echo __( 'Only for filter in pages, <i>not</i> in snippets.' ); ?></p>
				</div>
			</div>
			<hr />
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Find similar pages' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[site][find_similar]', Form::choices(), Config::get('site', 'find_similar' )); ?>
					<p class="help-block"><?php echo __( 'If requested page url is incorrect, then find similar page.' ); ?></p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo __( 'Check page date' ); ?></label>
				<div class="controls">
					<?php echo Form::select( 'setting[page][check_date]', Form::choices(), Config::get('site', 'check_page_date', Config::NO ));?>
				</div>
			</div>
		</div>
		
		<div class="panel-heading" data-icon="hdd-o">
			<span class="panel-title"><?php echo __( 'Session settings' ); ?></span>
		</div>
		<div class="panel-body">
			<?php if( ACL::check('system.session.clear') AND Session::$default == 'database'): ?>
			<div class="well">
				<?php echo UI::button(__('Clear user sessions'), array(
					'icon' => UI::icon('trash-o fa-lg'),
					'class' => 'btn btn-warning btn-api btn-large',
					'data-url' => 'session.clear'
				)); ?>
			</div>
			<?php endif; ?>
			
			<div class="control-group">
				<?php echo Form::label('setting_session_storage', __('Session storage'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::select('', array('native' => __('Native'), 'database' => __('Database'), 'cookie' => __('Cookie')), Session::$default, array(
						'id' => 'setting_session_storage', 'disabled', 'readonly'));?>

					<div class="help-block">
						<?php echo UI::icon('lightbulb-o'); ?> <?php echo __('The session storage driver can change in the config file (:path)', array(':path' => CFGFATH)); ?>
					</div>
				</div>
			</div>
		</div>
		<?php Observer::notify( 'view_setting_plugins' ); ?>
		<div class="form-actions panel-footer">
			<?php echo Form::button( 'submit', UI::icon( 'check' ) . ' ' . __( 'Save settings' ), array(
				'class' => 'btn btn-large',
				'hotkeys' => 'ctrl+s'
			) ); ?>
		</div>
	</div>
<?php Form::close(); ?>