<div class="panel-heading" data-icon="hdd-o">
	<span class="panel-title" id="cache-settings"><?php echo __('Cache settings'); ?></span>
</div>
<div class="panel-body">
	<?php if (ACL::check('system.cache.clear')): ?>
	<div class="well">
		<?php echo UI::button(__('Clear cache'), array(
			'icon' => UI::icon('trash-o fa-lg'),
			'class' => 'btn-warning',
			'data-api-url' => 'cache',
			'data-method' => Request::DELETE
		)); ?>
	</div>
	<?php endif; ?>
	
	<div class="form-group">
		<div class="col-md-12">
			<div class="note note-warning">
				<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('The cache driver can change in the config file (:path)', array(':path' => CFGFATH)); ?>
			</div>
		</div>
		<?php echo Form::label('setting_cache_driver', __('Cache driver'), array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-3">
			<?php echo Form::select(NULL, Cache::drivers(), Cache::$default, array('id' => 'setting_cache_driver', 'disabled', 'readonly'));?>
		</div>
		
	</div>
	
	<div class="form-group">
		<?php echo Form::label('setting_cache_status', __('Cache status'), array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-3">
			<?php echo Form::select(NULL, array('1' => __('Enabled'), '0' => __('Disabled')), (int) (Kohana::$environment === Kohana::PRODUCTION), array('disabled', 'readonly'));?>
		</div>
	</div>

	<hr class="panel-wide" />

	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Pages cache lifetime'); ?></label>
		<div class="col-md-3">
			<div class="input-group">
				<?php echo Form::input('setting[cache][front_page]', (int) Config::get('cache', 'front_page'), array(
					'class' => 'form-control'
				)); ?>
				<span class="input-group-addon bg-success"><?php echo __('(Sec.)'); ?></span>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Page parts cache lifetime'); ?></label>
		<div class="col-md-3">
			<div class="input-group">
				<?php echo Form::input('setting[cache][page_parts]', (int) Config::get('cache', 'page_parts'), array(
					'class' => 'form-control'
				)); ?>
				<span class="input-group-addon bg-success"><?php echo __('(Sec.)'); ?></span>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Page tags cache lifetime'); ?></label>
		<div class="col-md-3">
			<div class="input-group">
				<?php echo Form::input('setting[cache][tags]', (int) Config::get('cache', 'tags'), array(
					'class' => 'form-control'
				)); ?>
				<span class="input-group-addon bg-success"><?php echo __('(Sec.)'); ?></span>
			</div>
		</div>
	</div>
</div>