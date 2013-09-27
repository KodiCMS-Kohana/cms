<?php echo form::open(Route::url('backend', array('controller' => 'setting')), array(
	'id' => 'settingForm', 'class' => 'form-horizontal'
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>

<div class="widget">
	<div class="widget-header spoiler-toggle" data-spoiler=".site-information-content">
		<h3><?php echo __( 'Site information' ); ?></h3>
	</div>
	<div class="widget-content spoiler site-information-content">
		<div class="control-group">
			<label class="control-label title" for="settingTitle"><?php echo __( 'Site title' ); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'setting[site_title]', Setting::get( 'site_title' ), array(
					'class' => 'input-title input-block-level', 'id' => 'settingTitle'
				) );
				?>
				<p class="help-block"><?php echo __( 'This text will be present at backend and can be used in frontend pages.' ); ?></p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="settingDescription"><?php echo __( 'Site description' ); ?></label>
			<div class="controls">
				<?php echo Form::textarea( 'setting[site_description]', Setting::get( 'site_description' ), array(
					'id' => 'settingDescription', 'class' => 'input-block-level', 'rows' => 3
				) ); ?>
			</div>
		</div>
	</div>
	
	<div class="widget-header">
		<h3>
			<?php echo __( 'Site options' ); ?>
			
			<?php if( ACL::check('setting.clear_cache')): ?>
			<?php echo UI::button(__('Clear cache'), array(
				'icon' => UI::icon( 'stethoscope' ), 
				'href' => Route::url('backend', array(
					'controller' => 'setting', 
					'action' => 'clear_cache'
				)),
				'class' => 'btn btn-warning'
			)); ?>
			<?php endif; ?>
		</h3>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<?php echo Form::label('setting_date_format', __('Date format'), array('class' => 'control-label')); ?>
			<div class="controls">
				<?php
				echo Form::select('setting[date_format]', $dates, Setting::get('date_format'), array('id' => 'setting_date_format'));
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="settingSection"><?php echo __( 'Default backend section' ); ?></label>
			<div class="controls">
				<select id="settingSection" name="setting[default_tab]">
					<?php $current_default_nav = Setting::get( 'default_tab' ); ?>
					<?php foreach ( Model_Navigation::get() as $section ): ?>
						<optgroup label="<?php echo $section->name(); ?>">
							<?php foreach ( $section->get_pages() as $item ): ?>
							<?php $tab = trim(str_replace(ADMIN_DIR_NAME, '', $item->url()), '/'); ?>
							<option value="<?php echo $tab; ?>" <?php if ( $tab == $current_default_nav ) echo 'selected="selected"'; ?> ><?php echo $item->name(); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>

				<p class="help-block"><?php echo __( 'This allows you to specify which section you will see by default after login.' ); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="settingFindSimilar"><?php echo __( 'Find similar pages' ); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'setting[find_similar]', array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) ), Setting::get( 'find_similar' ), array(
					'id' => 'settingFindSimilar'
				) );
				?>

				<p class="help-block"><?php echo __( 'If requested page url is incorrect, then find similar page.' ); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="settingProfiling"><?php echo __( 'Profiling' ); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'setting[profiling]', array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) ), Setting::get( 'profiling' ), array(
					'id' => 'settingProfiling'
				) );
				?>

				<p class="help-block"><?php echo __('For detailed profiling use Kohana::$enviroment = Kohana::DEVELOPMENT or SetEnv KOHANA_ENV DEVELOPMENT in .htaccess'); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="settingDebugMode"><?php echo __( 'Debug mode' ); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'setting[debug]', array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) ), Setting::get( 'debug' ), array(
					'id' => 'settingDebugMode'
				) );
				?>
			</div>
		</div>
	</div>

	<div class="widget-header spoiler-toggle" data-spoiler=".page-options-container">
		<h3><?php echo __( 'Page options' ); ?></h3>
	</div>

	<div class="widget-content spoiler page-options-container">
		<div class="control-group">
			<label class="control-label"><?php echo __( 'Default page status' ); ?> </label>
			<div class="controls">
				<label class="radio inline" for="settingPageStatusDraft">
					<?php
					echo Form::radio( 'setting[default_status_id]', Model_Page::STATUS_DRAFT, (Setting::get( 'default_status_id' ) == Model_Page::STATUS_DRAFT ), array(
						'id' => 'settingPageStatusDraft'
					) ) . ' ' . __( 'Draft' );
					?>
				</label>

				<label class="radio inline" for="settingPageStatusPublished">
					<?php
					echo Form::radio( 'setting[default_status_id]', Model_Page::STATUS_PUBLISHED, (Setting::get( 'default_status_id' ) == Model_Page::STATUS_PUBLISHED ), array(
						'id' => 'settingPageStatusPublished'
					) ) . ' ' . __( 'Published' );
					?>
				</label>

				<p class="help-block"><?php echo __( 'This status will be autoselected when page creating.' ); ?></p>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="settingPageFilter"><?php echo __( 'Default filter' ); ?></label>
			<div class="controls">
				<?php echo Form::select('setting[default_filter_id]', $filters, Setting::get( 'default_filter_id' )); ?>
				<p class="help-block"><?php echo __( 'Only for filter in pages, <i>not</i> in snippets.' ); ?></p>
			</div>
		</div>
	</div>

	<?php Observer::notify( 'view_setting_plugins' ); ?>

	<div class="form-actions widget-footer">
		<?php echo Form::button( 'submit', UI::icon( 'ok' ) . ' ' . __( 'Save setting' ), array(
			'class' => 'btn btn-large'
		) ); ?>
	</div>
</div>
<?php Form::close(); ?>