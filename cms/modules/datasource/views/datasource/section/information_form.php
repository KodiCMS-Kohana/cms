<div class="panel-heading panel-toggler" data-target-spoiler=".general-spoiler" data-icon="info">
	<span class="panel-title"><?php echo __('Datasource Information'); ?></span>
</div>
<div class="panel-body panel-spoiler general-spoiler">
	<div class="form-group form-group-lg">
		<label class="control-label col-md-3" for="name"><?php echo __('Datasource Header'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('name', $ds->name, array(
				'class' => 'form-control', 'id' => 'name'
			)); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="ds_description"><?php echo __('Datasource Description'); ?></label>
		<div class="col-md-9">
			<?php echo Form::textarea( 'description', $ds->description, array(
				'class' => 'form-control', 'id' => 'ds_description', 'rows' => 4
			)); ?>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-3" for="created_by_id"><?php echo __('Datasource Author'); ?></label>
		<div class="col-md-9">
			<?php echo Form::select('created_by_id', $users, $ds->created_by_id(), array(
				'class' => 'form-control', 'id' => 'created_by_id'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="icon"><?php echo __('Datasource icon'); ?></label>
		<div class="col-md-9">
			<?php echo View::factory('helper/icons', array('icon' => $ds->icon())); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-9 col-md-offset-3">
			<div class="checkbox">
				<label>
					<?php echo Form::checkbox('show_in_root_menu', 1, $ds->show_in_root_menu(), array(
						'id' => 'show_in_root_menu'
					)); ?> <?php echo __('Show datasource in root menu'); ?>
				</label>
			</div>
		</div>
	</div>
</div>