<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<div class="panel-heading" data-icon="info">
		<span class="panel-title"><?php echo __('Datasource Information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="name"><?php echo __('Datasource Header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('name', Arr::get($data, 'name'), array(
					'class' => 'form-control', 'id' => 'name'
				)); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3" for="description"><?php echo __('Datasource Description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea('description', Arr::get($data, 'description'), array(
					'class' => 'form-control', 'id' => 'description', 'rows' => 3
				)); ?>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-md-9 col-md-offset-3">
				<div class="checkbox">
					<label>
						<?php echo Form::checkbox('show_in_root_menu', 1, Arr::get($data, 'show_in_root_menu') == 1, array(
							'id' => 'show_in_root_menu'
						)); ?> <?php echo __('Show in root menu'); ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	
	<div class="panel-footer">
		<?php echo UI::button( __('Create section'), array(
			'icon' => UI::icon( 'plus'),
			'class' => 'btn-lg btn-primary',
			'data-hotkeys' => 'ctrl+s'
		)); ?>
	</div>
<?php echo Form::close(); ?>