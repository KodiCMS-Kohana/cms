<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	
	<?php echo View::factory('datasource/section/information_form', array(
		'users' => $users,
		'ds' => $ds
	)); ?>
	
	<div class="form-actions panel-footer">
		<?php echo UI::actions(NULL, Datasource_Section::uri()); ?>
	</div>
<?php echo Form::close(); ?>