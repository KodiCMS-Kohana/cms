<div class="panel-heading" data-icon="clock-o">
	<span class="panel-title"><?php echo __('Log settings'); ?></span>
</div>
<div class="panel-body">
	<div class="well">
		<?php echo UI::button(__('Clear logs older 30 days'), array(
			'icon' => UI::icon( 'trash-o fa-lg' ),
			'class' => 'btn-warning',
			'data-api-url' => 'log.clear_old',
			'data-method' => Request::POST
		)); ?>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3"><?php echo __('Logs level'); ?></label>
		<div class="col-md-3">
			<?php echo Form::select( 'setting[site][log_level]', Log::levels(), (int) Config::get('site', 'log_level' )); ?>
		</div>
	</div>
</div>