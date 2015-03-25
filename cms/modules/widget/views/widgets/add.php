<?php echo Form::open(Request::current()->uri(), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>	
	<div class="panel-heading">
		<span class="panel-title" data-icon="info-circle"><?php echo __('General Information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3"><?php echo __('Widget Header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('name', NULL, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Widget Description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea('description', NULL, array(
					'class' => 'form-control', 'rows' => 4
				)); ?>
			</div>
		</div>
	</div>

	<div class="panel-heading">
		<span class="panel-title" data-icon="list"><?php echo __('Widget data'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-md-3"><?php echo __('Type'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('type', $types, 'html', array(
					'class' => 'col-md-6', 'size' => 10
				)); ?> 
			</div>
		</div>
	</div>
	<div class="panel-footer form-actions">
		<?php echo UI::button( __('Create widget'), array(
			'icon' => UI::icon( 'plus'), 
			'class' => 'btn-lg btn-primary',
			'data-hotkeys' => 'ctrl+s'
		)); ?>
	</div>
<?php echo Form::close(); ?>