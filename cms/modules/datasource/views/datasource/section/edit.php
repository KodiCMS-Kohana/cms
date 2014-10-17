<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	
	<div class="panel-heading" data-icon="info">
		<span class="panel-title"><?php echo __('Datasource Information'); ?></span>
	</div>
	<div class="panel-body">
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
	</div>
	
	<div class="form-actions panel-footer">
		<?php echo UI::actions(NULL, Datasource_Section::uri()); ?>
	</div>
<?php echo Form::close(); ?>