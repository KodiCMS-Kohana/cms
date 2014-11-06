<div class="container-fluid margin-sm-vr">
	<h1 class="pull-left no-margin-t"><?php echo $title; ?></h1>
	<?php echo Form::open('install/go', array(
		'class' => Form::HORIZONTAL
	)); ?>
	<div id="wizard" class="wizard">
		<h1><?php echo __( 'Language' ); ?></h1>
		<div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Current language'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('locale', I18n::available_langs(), Arr::get($_GET, 'lang', I18n::lang()), array(
							'id' => 'current-lang'
						)); ?>
					</div>
				</div>
			</div>
		</div>
		<h1><?php echo __('Environment Tests'); ?></h1>
		<div>
			<?php echo $env_test; ?>
			<?php Observer::notify('installer_step_environment', $data); ?>
		</div>
		<h1><?php echo __('Database information'); ?></h1>
		<div>
			<div class="note note-info">
				<?php echo __('Below you should enter your database connection details. If you’re not sure about these, contact your host.'); ?>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Database driver'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[db_driver]', $database_drivers, Arr::get($data, 'db_driver'), array(
							'id' => 'database-driver'
						)); ?>
					</div>
				</div>

				<div class="connection-settings">
					<div class="form-group">
						<label class="control-label col-md-3" for="db_server"><?php echo __('Database server'); ?></label>
						<div class="col-md-9 form-inline">
							<?php echo Form::input('install[db_server]', Arr::get($data, 'db_server'), array(
								'class' => 'form-control col-sm-auto', 'id' => 'db_server', 'required'
							) ); ?>

							<?php echo Form::input('install[db_port]', Arr::get($data, 'db_port'), array(
								'class' => 'form-control col-sm-auto', 'size' => 5, 'required'
							) ); ?>	
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_user"><?php echo __( 'Database user' ); ?></label>
						<div class="col-md-9 form-inline">
							<?php echo Form::input('install[db_user]', Arr::get($data, 'db_user'), array(
								'class' => 'form-control col-sm-auto', 'id' => 'db_user', 'required'
							)); ?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3" for="db_password"><?php echo __( 'Database password' ); ?></label>
						<div class="col-md-9">
							<?php echo Form::password('install[db_password]', Arr::get($data, 'db_password'), array(
								'class' => 'form-control col-sm-auto', 'id' => 'db_password'
							)); ?>

							<p class="help-block"><?php echo __('If there is no database password, leave it blank.'); ?></p>
						</div>
					</div>
				</div>
				<div class="form-group well well-sm">
					<label class="control-label col-md-3" for="db_name"><?php echo __('Database name'); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[db_name]', Arr::get($data, 'db_name'), array(
							'class' => 'form-control col-sm-auto', 'id' => 'db_name', 'required'
						)); ?>

						<p class="help-block"><?php echo __('You have to create a database manually and enter its name here.'); ?></p>
					</div>
					
					<div class="col-md-offset-3 col-md-9">
						<hr />
						<label class="checkbox btn btn-danger btn-checkbox">
							<?php echo Form::checkbox( 'install[empty_database]', 1, (bool) Arr::get( $data,'empty_database'), array('class' => 'px'));?>
							<span class="lbl"><?php echo __('Empty database'); ?></span>
						</label>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="db_preffix"><?php echo __( 'Prefix' ); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[db_table_prefix]', Arr::get($data, 'db_table_prefix'), array(
							'class' => 'form-control', 'id' => 'db_preffix'
						)); ?>

						<p class="help-block"><?php echo __('Usefull to prevent conflicts if you have, or plan to have, multiple :cms installations with a single database.', array(':cms' => CMS_NAME)); ?></p>
					</div>
				</div>
			</div>
		</div>

		<h1><?php echo __('Site information'); ?></h1>
		<div>
			<div class="panel-heading" data-icon="user">
				<span class="panel-title"><?php echo __('User settings'); ?></span>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3" for="username"><?php echo __('Administrator username'); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[username]', Arr::get($data, 'username'), array(
							'class' => 'form-control', 'id' => 'username', 'required'
						)); ?>
					</div>
				</div>
				<div class="well well-small">
					<div id="password-form">
						<div class="form-group">
							<label class="control-label col-md-3" for="password"><?php echo __('Password'); ?></label>
							<div class="col-md-9 form-inline">
								<?php echo Form::password('install[password_field]', Arr::get( $data,'password_field'), array(
									'class' => 'form-control', 'id' => 'password', 'required'
								)); ?>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-3 col-md-9 form-inline">
								<?php echo Form::password('install[password_confirm]', Arr::get( $data,'password_confirm'), array(
									'class' => 'form-control', 'id' => 'password_confirm', 'placeholder' => __('Confirm Password'), 'required'
								)); ?>

								<p class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
								':num' => Kohana::$config->load('auth')->get('password_length', 5)
								)); ?></p>
							</div>
						</div>
						
						<hr class="panel-wide"/>
					</div>
					
					<div class="form-group no-margin-vr">
						<div class="col-md-offset-3 col-md-9 ">
							<label class="checkbox btn btn-success btn-checkbox">
								<?php echo Form::checkbox('install[password_generate]', 1, (bool) Arr::get($data, 'password_generate'), array(
									'id' => 'generate-password-checkbox'
								)); ?> <?php echo __('Generate password'); ?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="email"><?php echo __('Administrator email'); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[email]', Arr::get($data, 'email'), array(
							'class' => 'form-control', 'id' => 'email', 'required'
						)); ?>
					</div>
				</div>
			</div>
			<div class="panel-heading" data-icon="exclamation-circle">
				<span class="panel-title"><?php echo __('Site settings'); ?></span>
			</div>
			<div class="panel-body">
				<div class="form-group form-group-lg">
					<label class="control-label col-md-3" for="site_name"><?php echo __('Site title'); ?></label>
					<div class="col-md-9">
						<?php echo Form::input('install[site_name]', Arr::get($data, 'site_name'), array(
							'class' => 'form-control', 'id' => 'site_name', 'required'
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="admin_dir_name"><?php echo __('Admin dir name'); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[admin_dir_name]', Arr::get($data, 'admin_dir_name'), array(
							'class' => 'form-control', 'id' => 'admin_dir_name', 'size' => 20, 'maxlength' => 20, 'required'
						)); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="url_suffix"><?php echo __('URL suffix'); ?></label>
					<div class="col-md-9 form-inline">
						<?php echo Form::input('install[url_suffix]', Arr::get($data, 'url_suffix'), array(
							'class' => 'form-control', 'id' => 'url_suffix', 'size' => 6, 'maxlength' => 6
						)); ?>

						<p class="help-block"><?php echo __('Add a suffix to simulate static html files.'); ?></p>
					</div>
				</div>
			</div>
			<div class="panel-heading" data-icon="globe">
				<span class="panel-title"><?php echo __('Regional settings'); ?></span>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Interface language'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[locale]', I18n::available_langs(), Arr::get($data, 'locale')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Timezone'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[timezone]', Date::timezones(), Arr::get($data, 'timezone')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Date format'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[date_format]', $dates, Arr::get($data, 'date_format')); ?>
					</div>
				</div>
			</div>
			<?php Observer::notify('installer_step_site_imformation', $data); ?>
		</div>

		<h1><?php echo __('Other'); ?></h1>
		<div>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Cache type'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[cache_type]', $cache_types, Arr::get($data, 'cache_type')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __('Session storage'); ?></label>
					<div class="col-md-3">
						<?php echo Form::select('install[session_type]', $session_types, Arr::get($data, 'session_type')); ?>
					</div>
				</div>
			</div>

			<?php Observer::notify('installer_step_other', $data); ?>
		</div>

		<?php Observer::notify('installer_step_new', $data); ?>
	</div>
	<?php echo Form::close(); ?>
</div>